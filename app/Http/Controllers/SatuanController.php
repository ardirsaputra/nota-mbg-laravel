<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satuan;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::orderBy('nama_satuan', 'asc')->get();
        return view('satuan.index', compact('satuans'));
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_satuan' => 'required|unique:satuan,nama_satuan',
            'keterangan' => 'nullable',
        ]);

        Satuan::create($validated);

        return redirect()->route('satuan.index')->with('status', 'created');
    }

    public function edit($id)
    {
        $satuan = Satuan::findOrFail($id);
        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $satuan = Satuan::findOrFail($id);

        $validated = $request->validate([
            'nama_satuan' => 'required|unique:satuan,nama_satuan,' . $id,
            'keterangan' => 'nullable',
        ]);

        $satuan->update($validated);

        return redirect()->route('satuan.index')->with('status', 'updated');
    }

    public function destroy($id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();

        return redirect()->route('satuan.index')->with('status', 'deleted');
    }
}
