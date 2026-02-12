<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaBarangPokok;

class HargaBarangPokokController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', '');

        $query = HargaBarangPokok::query();

        if ($search) {
            $query->where('uraian', 'like', "%{$search}%");
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $barang_pokok = $query->orderBy('id', 'desc')->get();

        // Get categories
        $kategori_file = storage_path('app/categories.json');
        $kategori_list = [];
        if (file_exists($kategori_file)) {
            $kategori_list = json_decode(file_get_contents($kategori_file), true) ?? [];
        }

        $last_update = HargaBarangPokok::max('updated_at');

        return view('harga_barang_pokok.index', compact('barang_pokok', 'kategori_list', 'last_update', 'search', 'kategori'));
    }

    public function create()
    {
        $kategori_file = storage_path('app/categories.json');
        $kategori_list = [];
        if (file_exists($kategori_file)) {
            $kategori_list = json_decode(file_get_contents($kategori_file), true) ?? [];
        }

        return view('harga_barang_pokok.create', compact('kategori_list'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'uraian' => 'required',
            'kategori' => 'required',
            'satuan' => 'required',
            'nilai_satuan' => 'required|numeric',
            'harga_satuan' => 'required|integer',
        ]);

        HargaBarangPokok::create($validated);

        return redirect()->route('harga-barang-pokok.index')->with('status', 'created');
    }

    public function edit($id)
    {
        $barang = HargaBarangPokok::findOrFail($id);

        $kategori_file = storage_path('app/categories.json');
        $kategori_list = [];
        if (file_exists($kategori_file)) {
            $kategori_list = json_decode(file_get_contents($kategori_file), true) ?? [];
        }

        return view('harga_barang_pokok.edit', compact('barang', 'kategori_list'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'uraian' => 'required',
            'kategori' => 'required',
            'satuan' => 'required',
            'nilai_satuan' => 'required|numeric',
            'harga_satuan' => 'required|integer',
        ]);

        $barang = HargaBarangPokok::findOrFail($id);
        $barang->update($validated);

        return redirect()->route('harga-barang-pokok.index')->with('status', 'updated');
    }

    public function destroy($id)
    {
        $barang = HargaBarangPokok::findOrFail($id);
        $barang->delete();

        return redirect()->route('harga-barang-pokok.index')->with('status', 'deleted');
    }

    public function updateAjax(Request $request)
    {
        $id = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');

        $barang = HargaBarangPokok::findOrFail($id);
        $barang->{$field} = $value;
        $barang->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil diupdate']);
    }
}
