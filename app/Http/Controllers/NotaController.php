<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\NotaItem;
use App\Models\HargaBarangPokok;
use App\Models\Setting;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ramsey\Collection\Set;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('profit_filter', 'all');
        $toko_filter = $request->get('toko_filter', 'all');
        $allowed_filters = ['all', 'included', 'excluded'];

        if (!in_array($filter, $allowed_filters)) {
            $filter = 'all';
        }

        $query = Nota::with('items', 'toko')->orderBy('id', 'desc');

        // Date range filter (optional)
        $tanggalFrom = $request->get('tanggal_from');
        $tanggalTo = $request->get('tanggal_to');

        if ($tanggalFrom) {
            try {
                $d = \Illuminate\Support\Carbon::parse($tanggalFrom);
                $query->whereDate('tanggal', '>=', $d->toDateString());
            } catch (\Exception $e) { /* ignore invalid */
            }
        }
        if ($tanggalTo) {
            try {
                $d = \Illuminate\Support\Carbon::parse($tanggalTo);
                $query->whereDate('tanggal', '<=', $d->toDateString());
            } catch (\Exception $e) { /* ignore invalid */
            }
        }

        // Filter by user role
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if ($authUser && $authUser->isUser()) {
            // Regular users only see their own notas
            $query->where('user_id', Auth::id());
        } else {
            // Admin sees all notas, but can filter by admin-only notes
            if ($request->get('user_filter') === 'admin_only') {
                $query->where('is_admin_nota', true);
            } elseif ($request->get('user_filter') === 'user_notes') {
                $query->where('is_admin_nota', false);
            }
        }

        if ($filter === 'included') {
            $query->where('profit_insight', true);
        } elseif ($filter === 'excluded') {
            $query->where('profit_insight', false);
        }

        // Filter by toko
        if ($toko_filter !== 'all') {
            if ($toko_filter === 'manual') {
                $query->whereNull('toko_id')->whereNotNull('nama_toko_manual');
            } elseif ($toko_filter === 'none') {
                $query->whereNull('toko_id')->whereNull('nama_toko_manual');
            } else {
                $query->where('toko_id', $toko_filter);
            }
        }

        $notas = $query->get();

        // Get all toko for filter dropdown
        $toko_list = Toko::orderBy('nama_toko')->get();

        // Calculate profit for each nota using requested tiers
        foreach ($notas as $nota) {
            $estimated_profit = 0;
            foreach ($nota->items as $item) {
                $qty = (float) $item->qty;
                $harga = (int) $item->harga_satuan;

                // prefer stored profit_per_unit when available; otherwise compute by tier
                if (!empty($item->profit_per_unit) && (int)$item->profit_per_unit > 0) {
                    $profitPerUnit = (int) $item->profit_per_unit;
                    $percent = null;
                } else {
                    $percent = $this->profitPercentForPrice($harga);
                    $profitPerUnit = (int) ($harga * ($percent / 100));
                }

                $estimated_profit += ($profitPerUnit * $qty);
                $item->profit_percent = $percent; // may be null if stored profit used
                $item->profit_per_unit = (int) $profitPerUnit;
            }
            $nota->estimated_profit = (int) $estimated_profit;
        }

        return view('nota.index', compact('notas', 'filter', 'toko_filter', 'toko_list', 'tanggalFrom', 'tanggalTo'));
    }

    public function create()
    {
        // Generate nomor nota otomatis
        $today = date('Y-m-d');
        $count = Nota::whereDate('tanggal', $today)->count() + 1;
        $id = Nota::max('id') + 1;
        $no = 'MJA-' . $id . '-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // provide master lists for client-side UI
        $barang_list = HargaBarangPokok::orderBy('uraian')->get();
        $satuan_list = \App\Models\Satuan::orderBy('nama_satuan')->get();

        // Toko only for admin
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();

        if ($authUser && $authUser->isAdmin()) {
            $toko_list = Toko::orderBy('nama_toko')->get();
        } else {
            // regular users only get their own toko(s)
            $toko_list = Toko::where('user_id', Auth::id())->orderBy('nama_toko')->get();
        }

        return view('nota.create', compact('no', 'barang_list', 'satuan_list', 'toko_list'));
    }

    /**
     * Determine profit percent based on harga_satuan (rules requested):
     * - harga < 1.000  => 20%
     * - harga <= 10.000 => 20%
     * - harga > 150.000 => 5%
     * - harga > 50.000  => 10%
     * - otherwise => 20%
     */
    protected function profitPercentForPrice(int $harga): float
    {
        if ($harga < 1000) return 20.0;
        if ($harga <= 10000) return 20.0; // "dibawah 10 atau sama" interpreted as 10.000
        if ($harga > 150000) return 5.0;
        if ($harga > 50000) return 10.0;
        return 20.0;
    }

    /**
     * Ensure the authenticated user can access the given nota.
     * Regular users cannot access notas that belong to others or notes flagged as admin nota.
     */
    private function ensureCanAccessNota(Nota $nota)
    {
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if ($authUser && $authUser->isUser()) {
            if ($nota->user_id !== $authUser->id || $nota->is_admin_nota) {
                abort(403, 'Anda tidak memiliki akses ke nota ini.');
            }
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no' => 'required',
            'tanggal' => 'required|date',
            'toko_id' => 'nullable|exists:toko,id',
            'nama_toko_manual' => 'nullable|string',
            'alamat_toko_manual' => 'nullable|string',
            'nama_toko' => 'nullable',
            'alamat' => 'nullable',
            'uraian' => 'array',
            'qty' => 'array',
            'harga' => 'array',
            'satuan' => 'array',
            'profit_per_unit' => 'nullable|array',
            'profit_per_unit.*' => 'nullable|integer',
            'update_harga' => 'nullable|boolean',
        ]);

        // Prevent users from updating master prices
        $updateHarga = $validated['update_harga'] ?? false;
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if ($authUser && $authUser->isUser()) {
            $updateHarga = false;
        }

        $nota = Nota::create([
            'no' => $validated['no'],
            'tanggal' => $validated['tanggal'],
            'toko_id' => $validated['toko_id'] ?? null,
            'nama_toko_manual' => $validated['nama_toko_manual'] ?? null,
            'alamat_toko_manual' => $validated['alamat_toko_manual'] ?? null,
            'nama_toko' => $validated['nama_toko'],
            'alamat' => $validated['alamat'],
            'total' => 0,
            'user_id' => Auth::id(),
            'is_admin_nota' => ($authUser && $authUser->isAdmin()),
        ]);

        // Save items if provided
        $this->saveNotaItems($nota, array_merge($validated, ['update_harga' => $updateHarga]));

        return redirect()->route('nota.show', $nota->id)->with('status', 'created');
    }

    public function show($id)
    {
        // form database setting 

        $companyName = "CV. MIA JAYA ABADI";
        $companyLogo = "https://ik.imagekit.io/arsdevahliaja/logo-mja.png";
        $address = "Jalan Raya Metro – Gotong Royong, Dusun III, Pujodadi";
        $phone1 = "0852-1903-4328";
        $phone2 = "0852-8233-3439";
        $companyName = Setting::get('company_name', $companyName);
        $companyLogo = Setting::get('company_logo', $companyLogo);
        $address = Setting::get('company_address', $address);
        $phone1 = Setting::get('company_phone_1', $phone1);
        $phone2 = Setting::get('company_phone_2', $phone2);

        $nota = Nota::with('items')->findOrFail($id);

        // Access control: prevent regular users from viewing others' or admin notas
        $this->ensureCanAccessNota($nota);

        // Calculate profit using tiered rules
        $estimated_profit = 0;
        foreach ($nota->items as $item) {
            $qty = (float) $item->qty;
            $harga = (int) $item->harga_satuan;

            // prefer stored profit_per_unit when present
            if (!empty($item->profit_per_unit) && (int)$item->profit_per_unit > 0) {
                $profitPerUnit = (int) $item->profit_per_unit;
                $percent = null;
            } else {
                $percent = $this->profitPercentForPrice($harga);
                $profitPerUnit = (int) ($harga * ($percent / 100));
            }

            $estimated_profit += ($profitPerUnit * $qty);
            $item->profit_percent = $percent;
            $item->profit_per_unit = (int) $profitPerUnit;
        }

        return view('nota.show', compact('nota', 'estimated_profit'));
    }

    public function edit($id)
    {
        $nota = Nota::with('items')->findOrFail($id);

        // Access control
        $this->ensureCanAccessNota($nota);

        $barang_list = HargaBarangPokok::orderBy('uraian')->get();
        $satuan_list = \App\Models\Satuan::orderBy('nama_satuan')->get();
        $toko_list = Toko::orderBy('nama_toko')->get();

        return view('nota.edit', compact('nota', 'barang_list', 'satuan_list', 'toko_list'));
    }

    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        // Access control
        $this->ensureCanAccessNota($nota);

        $validated = $request->validate([
            'no' => 'required',
            'tanggal' => 'required|date',
            'toko_id' => 'nullable|exists:toko,id',
            'nama_toko_manual' => 'nullable|string',
            'alamat_toko_manual' => 'nullable|string',
            'nama_toko' => 'nullable',
            'alamat' => 'nullable',
            'uraian' => 'array',
            'qty' => 'array',
            'harga' => 'array',
            'satuan' => 'array',
            'profit_per_unit' => 'nullable|array',
            'profit_per_unit.*' => 'nullable|integer',
            'update_harga' => 'nullable|boolean',
        ]);

        $nota->update([
            'no' => $validated['no'],
            'tanggal' => $validated['tanggal'],
            'toko_id' => $validated['toko_id'] ?? null,
            'nama_toko_manual' => $validated['nama_toko_manual'] ?? null,
            'alamat_toko_manual' => $validated['alamat_toko_manual'] ?? null,
            'nama_toko' => $validated['nama_toko'],
            'alamat' => $validated['alamat'],
        ]);

        // Save items if provided
        $this->saveNotaItems($nota, $validated);

        return redirect()->route('nota.show', $id)->with('status', 'updated');
    }

    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);

        // Access control
        $this->ensureCanAccessNota($nota);

        $nota->delete();

        return redirect()->route('nota.index')->with('status', 'deleted');
    }

    public function addItem(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        // Access control
        $this->ensureCanAccessNota($nota);

        $validated = $request->validate([
            'uraian' => 'required',
            'satuan' => 'required',
            'qty' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer',
            'profit_per_unit' => 'nullable|integer',
        ]);

        NotaItem::create([
            'nota_id' => $nota->id,
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'],
            'qty' => $validated['qty'],
            'harga_satuan' => $validated['harga_satuan'],
            'profit_per_unit' => $validated['profit_per_unit'] ?? 0,
        ]);

        $nota->calculateTotal();

        return redirect()->route('nota.edit', $id)->with('status', 'item_added');
    }

    public function deleteItem($notaId, $itemId)
    {
        $nota = Nota::findOrFail($notaId);

        // Access control
        $this->ensureCanAccessNota($nota);

        $item = NotaItem::where('nota_id', $notaId)->where('id', $itemId)->firstOrFail();
        $item->delete();

        $nota->calculateTotal();

        return redirect()->route('nota.edit', $notaId)->with('status', 'item_deleted');
    }

    private function saveNotaItems(Nota $nota, array $validated)
    {
        // Clear existing items for update operation
        $nota->items()->delete();

        $uraianArray = $validated['uraian'] ?? [];
        $qtyArray = $validated['qty'] ?? [];
        $hargaArray = $validated['harga'] ?? [];
        $satuanArray = $validated['satuan'] ?? [];
        $profitArray = $validated['profit_per_unit'] ?? [];
        $updateHarga = $validated['update_harga'] ?? false;

        $count = count($uraianArray);
        for ($i = 0; $i < $count; $i++) {
            $uraian = trim($uraianArray[$i] ?? '');
            $qty = floatval($qtyArray[$i] ?? 0);
            $harga = intval($hargaArray[$i] ?? 0);
            $satuan = trim($satuanArray[$i] ?? '');

            // Skip rows without uraian
            if (empty($uraian)) {
                continue;
            }

            // Coerce numeric defaults (allow zero/negative values)
            $qty = $qty;
            $harga = $harga;

            // Determine profit per unit for this row (if provided)
            $profitPerUnitForRow = isset($profitArray[$i]) ? intval($profitArray[$i]) : 0;

            // Create nota item (qty/harga may be zero or negative)
            NotaItem::create([
                'nota_id' => $nota->id,
                'uraian' => $uraian,
                'satuan' => $satuan,
                'qty' => $qty,
                'harga_satuan' => $harga,
                'subtotal' => $qty * $harga,
                'profit_per_unit' => $profitPerUnitForRow,
            ]);

            // Update master price if requested and item exists in HargaBarangPokok
            if ($updateHarga) {
                $barang = HargaBarangPokok::where('uraian', $uraian)->first();
                if ($barang) {
                    $updateData = [
                        'harga_satuan' => $harga,
                        'satuan' => $satuan,
                    ];
                    // also update master profit if supplied
                    if ($profitPerUnitForRow > 0) {
                        $updateData['profit_per_unit'] = $profitPerUnitForRow;
                    }
                    $barang->update($updateData);
                }
            }
        }

        // Recalculate total
        $nota->calculateTotal();
    }

    public function toggleLock(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        // Access control
        $this->ensureCanAccessNota($nota);

        $nota->is_locked = !$nota->is_locked;
        $nota->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_locked' => $nota->is_locked,
                'message' => $nota->is_locked ? 'Nota berhasil dikunci' : 'Nota berhasil dibuka kuncinya',
            ]);
        }

        return redirect()->back()->with('status', 'lock_toggled');
    }

    public function toggleProfitInsight(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        // Access control
        $this->ensureCanAccessNota($nota);

        $nota->profit_insight = !$nota->profit_insight;
        $nota->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'profit_insight' => $nota->profit_insight,
                'message' => $nota->profit_insight ? 'Nota dimasukkan ke Profit Insight' : 'Nota dikeluarkan dari Profit Insight',
            ]);
        }

        return redirect()->back()->with('status', 'profit_toggled');
    }

    public function print($id)
    {
        $nota = Nota::with('items')->findOrFail($id);

        // Check if user has access to this nota
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if ($authUser && $authUser->isUser() && $nota->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke nota ini.');
        }

        return view('nota.print', compact('nota'));
    }

    public function clone(Request $request, $id)
    {
        // Only admin can clone
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if (!($authUser && $authUser->isAdmin())) {
            abort(403, 'Hanya admin yang dapat melakukan clone nota.');
        }

        $originalNota = Nota::with('items')->findOrFail($id);

        // Generate new nota number
        $today = date('Y-m-d');
        $count = Nota::whereDate('tanggal', $today)->count() + 1;
        $newId = Nota::max('id') + 1;
        $no = 'MJA-' . $newId . '-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Create cloned nota as admin nota
        $clonedNota = Nota::create([
            'no' => $no,
            'tanggal' => now(),
            'toko_id' => $originalNota->toko_id,
            'nama_toko_manual' => $originalNota->nama_toko_manual,
            'alamat_toko_manual' => $originalNota->alamat_toko_manual,
            'nama_toko' => $originalNota->nama_toko,
            'alamat' => $originalNota->alamat,
            'total' => 0,
            'user_id' => Auth::id(),
            'is_admin_nota' => true,
            'cloned_from_id' => $originalNota->id,
            'profit_insight' => $originalNota->profit_insight,
        ]);

        // Clone all items
        foreach ($originalNota->items as $item) {
            NotaItem::create([
                'nota_id' => $clonedNota->id,
                'uraian' => $item->uraian,
                'satuan' => $item->satuan,
                'qty' => $item->qty,
                'harga_satuan' => $item->harga_satuan,
                'subtotal' => $item->subtotal,
            ]);
        }

        $clonedNota->calculateTotal();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Nota berhasil di-clone',
                'nota_id' => $clonedNota->id,
            ]);
        }

        return redirect()->route('nota.show', $clonedNota->id)->with('status', 'Nota berhasil di-clone dari nota pengguna');
    }

    public function exportMonth(Request $request)
    {
        $month = $request->get('month', date('Y-m'));

        $notas = Nota::with('items')
            ->whereYear('tanggal', '=', substr($month, 0, 4))
            ->whereMonth('tanggal', '=', substr($month, 5, 2))
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('nota.export_month', compact('notas', 'month'));
    }
}
