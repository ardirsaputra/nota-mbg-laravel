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

        // get model instance so $last_update is a Carbon (not a plain string)
        $lastModel = HargaBarangPokok::orderBy('updated_at', 'desc')->first();
        $last_update = $lastModel ? $lastModel->updated_at : null;

        return view('harga_barang_pokok.index', compact('barang_pokok', 'kategori_list', 'last_update', 'search', 'kategori'));
    }

    /**
     * Printable list (full-page print layout)
     */
    public function print(Request $request)
    {
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', '');

        $query = HargaBarangPokok::query();
        if ($search) $query->where('uraian', 'like', "%{$search}%");
        if ($kategori) $query->where('kategori', $kategori);

        $barang_pokok = $query->orderBy('uraian')->get();

        $lastModel = HargaBarangPokok::orderBy('updated_at', 'desc')->first();
        $last_update = $lastModel ? $lastModel->updated_at : null;

        return view('harga_barang_pokok.print', compact('barang_pokok', 'last_update', 'search', 'kategori'));
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
            'profit_per_unit' => 'nullable|integer',
        ]);

        $barang = HargaBarangPokok::create($validated);

        // Return JSON when called via AJAX so nota views can add the item instantly
        if ($request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'data' => [
                    'id' => $barang->id,
                    'uraian' => $barang->uraian,
                    'kategori' => $barang->kategori,
                    'satuan' => $barang->satuan,
                    'nilai_satuan' => $barang->nilai_satuan,
                    'harga_satuan' => $barang->harga_satuan,
                    'profit_per_unit' => $barang->profit_per_unit ?? 0,
                ],
            ]);
        }

        return redirect()->route('harga-barang-pokok.index')->with('status', 'created');
    }

    public function edit(Request $request, $id)
    {
        $barang = HargaBarangPokok::findOrFail($id);

        $kategori_file = storage_path('app/categories.json');
        $kategori_list = [];
        if (file_exists($kategori_file)) {
            $kategori_list = json_decode(file_get_contents($kategori_file), true) ?? [];
        }

        // If AJAX request, return the form fragment for modal
        if ($request->ajax()) {
            return view('harga_barang_pokok._ajax_edit_form', compact('barang', 'kategori_list'));
        }

        return view('harga_barang_pokok.edit', compact('barang', 'kategori_list'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'uraian' => 'required',
            'kategori' => 'required',
            'satuan' => 'required',
            'nilai_satuan' => 'required|numeric',
            'harga_satuan' => 'required|integer',
            'profit_per_unit' => 'nullable|integer',
        ];

        $validated = $request->validate($rules);

        $barang = HargaBarangPokok::findOrFail($id);
        $barang->update($validated);

        // If request is AJAX, return JSON for client-side update
        if ($request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'data' => [
                    'id' => $barang->id,
                    'uraian' => $barang->uraian,
                    'kategori' => $barang->kategori,
                    'satuan' => $barang->satuan,
                    'harga_formatted' => 'Rp ' . number_format($barang->harga_satuan, 0, ',', '.'),
                    'profit_per_unit' => $barang->profit_per_unit ?? 0,
                    'updated_at' => $barang->updated_at ? $barang->updated_at->format('d/m/Y H:i') : null,
                ],
            ]);
        }

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

    // Export CSV (Excel-compatible) of current filter
    public function exportCsv(Request $request)
    {
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', '');

        $query = HargaBarangPokok::query();
        if ($search) $query->where('uraian', 'like', "%{$search}%");
        if ($kategori) $query->where('kategori', $kategori);

        $fileName = 'harga_barang_pokok_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $columns = ['uraian', 'kategori', 'satuan', 'nilai_satuan', 'harga_satuan', 'profit_per_unit', 'updated_at'];

        $callback = function () use ($query, $columns) {
            $out = fopen('php://output', 'w');
            // header
            fputcsv($out, ['Uraian', 'Kategori', 'Satuan', 'Nilai Satuan', 'Harga Satuan', 'Keuntungan/Satuan (Rp)', 'Terakhir Update']);

            foreach ($query->orderBy('id')->cursor() as $row) {
                fputcsv($out, [
                    $row->uraian,
                    $row->kategori,
                    $row->satuan,
                    $row->nilai_satuan,
                    $row->harga_satuan,
                    $row->profit_per_unit ?? 0,
                    $row->updated_at ? $row->updated_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Show CSV import form
    public function showImportForm()
    {
        return view('harga_barang_pokok.import');
    }

    // Process CSV upload
    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'mode' => 'nullable|in:skip,overwrite',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->withErrors(['file' => 'Tidak dapat membuka file.']);
        }

        $header = fgetcsv($handle);
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            // Expecting columns: Uraian,Kategori,Satuan,Nilai Satuan,Harga Satuan
            $data = array_combine(array_map('strtolower', $header), $row);
            if (!$data) continue;

            $uraian = $data['uraian'] ?? null;
            if (!$uraian) continue;

            $values = [
                'kategori' => $data['kategori'] ?? 'Umum',
                'satuan' => $data['satuan'] ?? 'Kg',
                'nilai_satuan' => isset($data['nilai satuan']) ? (float)$data['nilai satuan'] : 1,
                'harga_satuan' => isset($data['harga satuan']) ? (int)$data['harga satuan'] : 0,
            ];

            $existing = HargaBarangPokok::where('uraian', $uraian)->first();
            if ($existing) {
                if ($request->input('mode') === 'overwrite') {
                    $existing->update($values);
                    $count++;
                }
                // skip by default
            } else {
                HargaBarangPokok::create(array_merge(['uraian' => $uraian], $values));
                $count++;
            }
        }
        fclose($handle);

        return redirect()->route('harga-barang-pokok.index')->with('status', "Import selesai. Baris diproses: {$count}");
    }

    // Export WhatsApp formatted text (new format: index|Name@25.000/Unit)
    // If ?view=1 is provided, render an HTML page so user can copy the text
    public function exportWa(Request $request)
    {
        $items = HargaBarangPokok::orderBy('kategori')->orderBy('uraian')->get();
        $lines = [];

        $i = 1;
        foreach ($items as $it) {
            $price = number_format($it->harga_satuan, 0, ',', '.'); // e.g. 25.000
            $lines[] = "{$i}|{$it->uraian}@{$price}/{$it->satuan}";
            $i++;
        }

        $content = implode("\n", $lines);

        // If the user requested view mode, show a copyable HTML page
        if ($request->get('view')) {
            return view('harga_barang_pokok.export_wa', ['content' => $content]);
        }

        $fileName = 'harga_wa_' . now()->format('Ymd') . '.txt';

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    // Show WA import page (paste text)
    public function showImportWaForm()
    {
        return view('harga_barang_pokok.import_wa');
    }

    // Parse WA text and import (supports both "Nama — Rp 12.000 / Satuan" and "1|Nama@25.000/Satuan")
    public function importWa(Request $request)
    {
        $request->validate(['wa_text' => 'required|string']);
        $text = $request->input('wa_text');
        $lines = preg_split('/\r?\n/', $text);
        $count = 0;

        foreach ($lines as $ln) {
            $ln = trim($ln);
            if ($ln === '' || preg_match('/^\s*(daftar|harga|tanggal)\b/i', $ln)) continue;

            $matched = false;

            // Format A: "Name — Rp 12.000 / Satuan"
            if (preg_match('/^(.+?)\s*[\-—:]\s*Rp\s*([0-9.,]+)\s*\/\s*(\w+)/i', $ln, $m)) {
                $uraian = trim($m[1]);
                $harga = (int) str_replace(['.', ','], ['', ''], $m[2]);
                $satuan = $m[3];

                $values = [
                    'kategori' => 'Umum',
                    'satuan' => $satuan,
                    'nilai_satuan' => 1,
                    'harga_satuan' => $harga,
                ];

                HargaBarangPokok::updateOrCreate(['uraian' => $uraian], $values);
                $count++;
                $matched = true;
            }

            // Format B: "1|Bawang Merah@25.000/Kg"  (index|name@price/unit)
            if (! $matched && preg_match('/^\s*\d+\|\s*(.+?)@\s*([0-9.,]+)\s*\/\s*(\w+)$/i', $ln, $m2)) {
                $uraian = trim($m2[1]);
                $harga = (int) str_replace(['.', ','], ['', ''], $m2[2]);
                $satuan = $m2[3];

                $values = [
                    'kategori' => 'Umum',
                    'satuan' => $satuan,
                    'nilai_satuan' => 1,
                    'harga_satuan' => $harga,
                ];

                HargaBarangPokok::updateOrCreate(['uraian' => $uraian], $values);
                $count++;
                $matched = true;
            }

            // other lines are ignored
        }

        return redirect()->route('harga-barang-pokok.index')->with('status', "Import WA selesai. Baris diproses: {$count}");
    }
}
