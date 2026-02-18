<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HargaBarangPokok;

class UserBarangController extends Controller
{
    /** Load kategori list from JSON + distinct DB values */
    private function loadKategoriList(): array
    {
        $kategori_file = storage_path('app/categories.json');
        $from_json = [];
        if (file_exists($kategori_file)) {
            $from_json = json_decode(file_get_contents($kategori_file), true) ?? [];
        }
        $from_db = HargaBarangPokok::whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->toArray();

        return array_values(array_unique(array_merge($from_json, $from_db)));
    }

    /** Show current user's own barang list */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', '');

        // User's own list
        $query = HargaBarangPokok::forUser($user->id);
        if ($search) {
            $query->where('uraian', 'like', "%{$search}%");
        }
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        $my_barang = $query->orderBy('kategori')->orderBy('uraian')->get();

        // Admin global list (for copy)
        $globalQuery = HargaBarangPokok::forUser(null);
        if ($search) {
            $globalQuery->where('uraian', 'like', "%{$search}%");
        }
        if ($kategori) {
            $globalQuery->where('kategori', $kategori);
        }
        $global_barang = $globalQuery->orderBy('kategori')->orderBy('uraian')->get();

        // IDs already in user's list (by uraian, case-insensitive)
        $my_uraian = $my_barang->pluck('uraian')->map(fn($u) => strtolower($u))->toArray();

        $kategori_list = $this->loadKategoriList();

        return view('barang_saya.index', compact(
            'my_barang',
            'global_barang',
            'my_uraian',
            'kategori_list',
            'search',
            'kategori'
        ));
    }

    /** Store a new barang directly (from scratch) */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'nilai_satuan' => 'required|numeric|min:0',
            'harga_satuan' => 'required|integer|min:0',
            'profit_per_unit' => 'nullable|integer|min:0',
        ]);

        // Check for duplicate in user's list
        $exists = HargaBarangPokok::forUser($user->id)
            ->whereRaw('LOWER(uraian) = ?', [strtolower($validated['uraian'])])
            ->exists();

        if ($exists) {
            return redirect()->route('barang-saya.index')
                ->with('error', "Barang \"{$validated['uraian']}\" sudah ada dalam daftar Anda.");
        }

        HargaBarangPokok::create(array_merge($validated, ['user_id' => $user->id]));

        return redirect()->route('barang-saya.index')
            ->with('success', "Barang \"{$validated['uraian']}\" berhasil ditambahkan.");
    }

    /** Copy barang from admin global list into user's own list */
    public function copyFromAdmin(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'barang_id' => 'required|integer',
            'profit_per_unit' => 'nullable|integer|min:0',
        ]);

        $global = HargaBarangPokok::forUser(null)
            ->findOrFail($request->barang_id);

        // Prevent duplicate
        $exists = HargaBarangPokok::forUser($user->id)
            ->whereRaw('LOWER(uraian) = ?', [strtolower($global->uraian)])
            ->exists();

        if ($exists) {
            return redirect()->route('barang-saya.index')
                ->with('error', "Barang \"{$global->uraian}\" sudah ada dalam daftar Anda.");
        }

        HargaBarangPokok::create([
            'user_id' => $user->id,
            'uraian' => $global->uraian,
            'kategori' => $global->kategori,
            'satuan' => $global->satuan,
            'nilai_satuan' => $global->nilai_satuan,
            'harga_satuan' => $global->harga_satuan,
            'profit_per_unit' => $request->profit_per_unit ?? $global->profit_per_unit ?? 0,
        ]);

        return redirect()->route('barang-saya.index')
            ->with('success', "Barang \"{$global->uraian}\" berhasil disalin ke daftar Anda.");
    }

    /** Show edit form for a user's own barang */
    public function edit($id)
    {
        $user = Auth::user();
        $barang = HargaBarangPokok::forUser($user->id)->findOrFail($id);
        $kategori_list = $this->loadKategoriList();

        return view('barang_saya.edit', compact('barang', 'kategori_list'));
    }

    /** Update a user's own barang */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $barang = HargaBarangPokok::forUser($user->id)->findOrFail($id);

        $validated = $request->validate([
            'uraian' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'nilai_satuan' => 'required|numeric|min:0',
            'harga_satuan' => 'required|integer|min:0',
            'profit_per_unit' => 'nullable|integer|min:0',
        ]);

        $barang->update($validated);

        return redirect()->route('barang-saya.index')
            ->with('success', "Barang \"{$barang->uraian}\" berhasil diperbarui.");
    }

    /** Delete a user's own barang */
    public function destroy($id)
    {
        $user = Auth::user();
        $barang = HargaBarangPokok::forUser($user->id)->findOrFail($id);
        $name = $barang->uraian;
        $barang->delete();

        return redirect()->route('barang-saya.index')
            ->with('success', "Barang \"{$name}\" berhasil dihapus.");
    }
}
