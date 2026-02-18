<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        // Defensive: if the `toko` table is missing on the server, return an empty collection
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('toko')) {
                $query = Toko::query();

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_toko', 'like', "%{$search}%")
                            ->orWhere('alamat', 'like', "%{$search}%");
                    });
                }

                $toko_list = $query->orderBy('nama_toko')->get();
            } else {
                $toko_list = collect();
            }
        } catch (\Throwable $e) {
            $toko_list = collect();
        }

        return view('toko.index', compact('toko_list', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('toko.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $toko = Toko::create($validated);

        // Return JSON when called via AJAX
        if ($request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'data' => [
                    'id' => $toko->id,
                    'nama_toko' => $toko->nama_toko,
                    'alamat' => $toko->alamat,
                ],
            ]);
        }

        return redirect()->route('toko.index')->with('status', 'created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Toko $toko)
    {
        return view('toko.show', compact('toko'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Toko $toko)
    {
        return view('toko.edit', compact('toko'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Toko $toko)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $toko->update($validated);

        return redirect()->route('toko.index')->with('status', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Toko $toko)
    {
        $toko->delete();

        return redirect()->route('toko.index')->with('status', 'deleted');
    }
}
