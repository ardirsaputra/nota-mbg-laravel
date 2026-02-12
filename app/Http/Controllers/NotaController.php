<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\NotaItem;
use App\Models\HargaBarangPokok;
use Illuminate\Support\Facades\DB;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('profit_filter', 'all');
        $allowed_filters = ['all', 'included', 'excluded'];

        if (!in_array($filter, $allowed_filters)) {
            $filter = 'all';
        }

        $query = Nota::with('items')->orderBy('id', 'desc');

        if ($filter === 'included') {
            $query->where('profit_insight', true);
        } elseif ($filter === 'excluded') {
            $query->where('profit_insight', false);
        }

        $notas = $query->get();

        // Calculate profit for each nota
        foreach ($notas as $nota) {
            $estimated_profit = 0;
            foreach ($nota->items as $item) {
                $qty = (float) $item->qty;
                $harga = (int) $item->harga_satuan;

                // Profit logic
                if ($harga > 100000) {
                    $profit_per_unit = $harga * 0.10;
                } else {
                    $profit_per_unit = 2500;
                }

                $estimated_profit += ($profit_per_unit * $qty);
            }
            $nota->estimated_profit = $estimated_profit;
        }

        return view('nota.index', compact('notas', 'filter'));
    }

    public function create()
    {
        // Generate nomor nota otomatis
        $today = date('Y-m-d');
        $count = Nota::whereDate('tanggal', $today)->count() + 1;
        $no = 'NOTA-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        return view('nota.create', compact('no'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no' => 'required',
            'tanggal' => 'required|date',
            'nama_toko' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $nota = Nota::create([
            'no' => $validated['no'],
            'tanggal' => $validated['tanggal'],
            'nama_toko' => $validated['nama_toko'],
            'alamat' => $validated['alamat'],
            'total' => 0,
        ]);

        return redirect()->route('nota.edit', $nota->id)->with('status', 'created');
    }

    public function show($id)
    {
        $nota = Nota::with('items')->findOrFail($id);

        // Calculate profit
        $estimated_profit = 0;
        foreach ($nota->items as $item) {
            $qty = (float) $item->qty;
            $harga = (int) $item->harga_satuan;

            if ($harga > 100000) {
                $profit_per_unit = $harga * 0.10;
            } else {
                $profit_per_unit = 2500;
            }

            $estimated_profit += ($profit_per_unit * $qty);
        }

        return view('nota.show', compact('nota', 'estimated_profit'));
    }

    public function edit($id)
    {
        $nota = Nota::with('items')->findOrFail($id);
        $barang_list = HargaBarangPokok::all();

        return view('nota.edit', compact('nota', 'barang_list'));
    }

    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        $validated = $request->validate([
            'no' => 'required',
            'tanggal' => 'required|date',
            'nama_toko' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $nota->update($validated);

        return redirect()->route('nota.edit', $id)->with('status', 'updated');
    }

    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->delete();

        return redirect()->route('nota.index')->with('status', 'deleted');
    }

    public function addItem(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        $validated = $request->validate([
            'uraian' => 'required',
            'satuan' => 'required',
            'qty' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer',
        ]);

        NotaItem::create([
            'nota_id' => $nota->id,
            'uraian' => $validated['uraian'],
            'satuan' => $validated['satuan'],
            'qty' => $validated['qty'],
            'harga_satuan' => $validated['harga_satuan'],
        ]);

        $nota->calculateTotal();

        return redirect()->route('nota.edit', $id)->with('status', 'item_added');
    }

    public function deleteItem($notaId, $itemId)
    {
        $item = NotaItem::where('nota_id', $notaId)->where('id', $itemId)->firstOrFail();
        $item->delete();

        $nota = Nota::findOrFail($notaId);
        $nota->calculateTotal();

        return redirect()->route('nota.edit', $notaId)->with('status', 'item_deleted');
    }

    public function toggleLock($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->is_locked = !$nota->is_locked;
        $nota->save();

        return redirect()->back()->with('status', 'lock_toggled');
    }

    public function toggleProfitInsight($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->profit_insight = !$nota->profit_insight;
        $nota->save();

        return redirect()->back()->with('status', 'profit_toggled');
    }

    public function print($id)
    {
        $nota = Nota::with('items')->findOrFail($id);
        return view('nota.print', compact('nota'));
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
