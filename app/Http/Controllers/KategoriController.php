<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    protected function categoriesPath()
    {
        return storage_path('app/categories.json');
    }

    protected function loadCategories(): array
    {
        $file = $this->categoriesPath();
        if (!file_exists($file)) return [];
        $data = json_decode(file_get_contents($file), true);
        return is_array($data) ? $data : [];
    }

    protected function saveCategories(array $cats): void
    {
        $file = $this->categoriesPath();
        file_put_contents($file, json_encode(array_values($cats), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function index()
    {
        $categories = $this->loadCategories();
        return view('kategori.index', compact('categories'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $cats = $this->loadCategories();
        $name = trim($request->input('name'));
        if (in_array($name, $cats, true)) {
            return back()->withErrors(['name' => 'Kategori sudah ada.'])->withInput();
        }
        $cats[] = $name;
        $this->saveCategories($cats);
        return redirect()->route('kategori.index')->with('status', 'Kategori ditambahkan');
    }

    public function edit($id)
    {
        $cats = $this->loadCategories();
        if (!isset($cats[$id])) abort(404);
        $name = $cats[$id];
        return view('kategori.edit', ['id' => $id, 'name' => $name]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $cats = $this->loadCategories();
        if (!isset($cats[$id])) abort(404);
        $name = trim($request->input('name'));
        // prevent duplicate (except for same index)
        foreach ($cats as $idx => $c) {
            if ($idx != $id && mb_strtolower($c) === mb_strtolower($name)) {
                return back()->withErrors(['name' => 'Nama kategori sudah digunakan.'])->withInput();
            }
        }
        $cats[$id] = $name;
        $this->saveCategories($cats);
        return redirect()->route('kategori.index')->with('status', 'Kategori diperbarui');
    }

    public function destroy($id)
    {
        $cats = $this->loadCategories();
        if (!isset($cats[$id])) abort(404);
        array_splice($cats, $id, 1);
        $this->saveCategories($cats);
        return redirect()->route('kategori.index')->with('status', 'Kategori dihapus');
    }
}
