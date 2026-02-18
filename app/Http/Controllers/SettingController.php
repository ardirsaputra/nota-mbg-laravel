<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function edit()
    {
        $galleries = Gallery::ordered()->get();

        return view('settings.edit', compact('galleries'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'website_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'nullable|string',
            'about_title' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'phone_1' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'address_2' => 'nullable|string',
            // Nota & direktur
            'director_name' => 'nullable|string|max:255',
            'nota_notes' => 'nullable|array',
            'nota_notes.*' => 'nullable|string|max:255',
            'operating_hours' => 'nullable|array',
            'features' => 'nullable|array',
            'services' => 'nullable|array',
            'company_logo' => 'nullable|image|max:4096',
            'hero_image' => 'nullable|image|max:4096',
        ]);

        // Ensure public storage link exists before handling uploads
        if (($request->hasFile('company_logo') || $request->hasFile('hero_image')) && ! $this->ensureStorageLinked()) {
            return redirect()->route('settings.edit')
                ->with('error', 'Folder public/storage belum tersedia — jalankan "php artisan storage:link" terlebih dahulu.');
        }

        // Update text settings
        Setting::set('website_name', $validated['website_name']);
        Setting::set('company_name', $validated['company_name']);
        Setting::set('hero_title', $validated['hero_title']);
        Setting::set('hero_description', $validated['hero_description'] ?? '');
        Setting::set('about_title', $validated['about_title'] ?? '');
        Setting::set('about_description', $validated['about_description'] ?? '');
        Setting::set('phone_1', $validated['phone_1'] ?? '');
        Setting::set('phone_2', $validated['phone_2'] ?? '');
        Setting::set('address', $validated['address'] ?? '');
        Setting::set('address_2', $validated['address_2'] ?? '');

        // Direktur & catatan nota
        Setting::set('director_name', $validated['director_name'] ?? '');
        Setting::set('nota_notes', $validated['nota_notes'] ?? []);

        // Update JSON settings
        if (isset($validated['operating_hours'])) {
            Setting::set('operating_hours', $validated['operating_hours']);
        }

        if (isset($validated['features'])) {
            Setting::set('features', $validated['features']);
        }

        if (isset($validated['services'])) {
            Setting::set('services', $validated['services']);
        }

        // Handle company logo upload
        if ($request->hasFile('company_logo')) {
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('company_logo')->store('settings', 'public');
            Setting::set('company_logo', $path, 'image');
        }

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            $oldImage = Setting::get('hero_image');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('hero_image')->store('settings', 'public');
            Setting::set('hero_image', $path, 'image');
        }

        return redirect()->route('settings.edit')->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function uploadGallery(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
            'title' => 'nullable|string|max:255',
        ]);

        if (! $this->ensureStorageLinked()) {
            return redirect()->route('settings.edit')
                ->with('error', 'Folder public/storage belum tersedia — jalankan "php artisan storage:link" terlebih dahulu.');
        }

        $path = $request->file('image')->store('gallery', 'public');

        $maxOrder = Gallery::max('order') ?? 0;

        Gallery::create([
            'title' => $request->title,
            'image_path' => $path,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('settings.edit')->with('success', 'Foto galeri berhasil ditambahkan!');
    }

    public function deleteGallery(Gallery $gallery)
    {
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('settings.edit')->with('success', 'Foto galeri berhasil dihapus!');
    }

    /**
     * Ensure public/storage symlink exists. Attempt to create it automatically if missing.
     * Returns boolean success.
     */
    private function ensureStorageLinked(): bool
    {
        $link = public_path('storage');
        if (file_exists($link)) return true;

        try {
            Artisan::call('storage:link');
            return file_exists($link);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function uploadServiceImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'service_index' => 'required|integer',
        ]);

        if (! $this->ensureStorageLinked()) {
            return response()->json([
                'success' => false,
                'message' => 'Folder public/storage belum tersedia — jalankan "php artisan storage:link" terlebih dahulu.'
            ], 500);
        }

        $path = $request->file('image')->store('services', 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
        ]);
    }
}
