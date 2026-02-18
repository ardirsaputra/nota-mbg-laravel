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
        // Defensive: if migrations haven't been run on the server the galleries table may not exist
        if (\Illuminate\Support\Facades\Schema::hasTable('galleries')) {
            $galleries = Gallery::ordered()->get();
        } else {
            $galleries = collect();
        }

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
        if (($request->hasFile('company_logo') || $request->hasFile('hero_image')) && !$this->ensureStorageLinked()) {
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
            // try delete from storage disk and public/storage fallback
            if ($oldLogo) {
                if (Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                $publicOld = public_path('storage/' . $oldLogo);
                if (file_exists($publicOld)) {
                    @unlink($publicOld);
                }
            }

            $path = $this->saveUploadedPublicFile($request->file('company_logo'), 'settings');
            Setting::set('company_logo', $path, 'image');
        }

        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            $oldImage = Setting::get('hero_image');
            if ($oldImage) {
                if (Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $publicOld = public_path('storage/' . $oldImage);
                if (file_exists($publicOld)) {
                    @unlink($publicOld);
                }
            }

            $path = $this->saveUploadedPublicFile($request->file('hero_image'), 'settings');
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

        if (!$this->ensureStorageLinked()) {
            return redirect()->route('settings.edit')
                ->with('error', 'Folder public/storage belum tersedia — jalankan "php artisan storage:link" terlebih dahulu.');
        }

        $path = $this->saveUploadedPublicFile($request->file('image'), 'gallery');

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
        // delete from storage disk
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        // delete from public/storage fallback
        $publicPath = public_path('storage/' . $gallery->image_path);
        if (file_exists($publicPath)) {
            @unlink($publicPath);
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
        if (file_exists($link))
            return true;

        // If hosting restricts access outside htdocs (open_basedir), avoid touching storage_path()
        $src = storage_path('app/public');
        $openBasedir = ini_get('open_basedir');
        $srcAccessible = true;
        if ($openBasedir) {
            // split and sanitize entries — skip empty entries and any containing null bytes
            $allowed = array_filter(array_map('trim', explode(PATH_SEPARATOR, $openBasedir)), fn($s) => $s !== '' && strpos($s, "\0") === false);
            $realSrc = @realpath($src);
            $srcAccessible = false;
            if ($realSrc) {
                foreach ($allowed as $a) {
                    // skip entries that can't be resolved or contain null bytes
                    if ($a === '' || strpos($a, "\0") !== false) {
                        continue;
                    }
                    $realA = @realpath($a);
                    if ($realA && strpos($realSrc, $realA) === 0) {
                        $srcAccessible = true;
                        break;
                    }
                }
            }
        }

        // If storage_path is not accessible on this host, create public/storage and return (no symlink possible)
        if (!$srcAccessible) {
            @mkdir($link, 0755, true);
            return file_exists($link);
        }

        try {
            Artisan::call('storage:link');
            return file_exists($link);
        } catch (\Throwable $e) {
            // final fallback: try to create the public/storage directory
            @mkdir($link, 0755, true);
            return file_exists($link);
        }
    }

    /**
     * Save an uploaded file so it's accessible via `asset('storage/...')` even when
     * `public/storage` is not a symlink (shared hosts with open_basedir).
     * Returns stored relative path (e.g. "settings/xxx.png").
     */
    private function saveUploadedPublicFile($uploadedFile, string $dir): string
    {
        $publicStorage = public_path('storage');

        // If public/storage is a symlink to storage/app/public, use the Laravel disk (keeps behavior consistent)
        $isSymlink = is_link($publicStorage) && @realpath($publicStorage) === @realpath(storage_path('app/public'));

        if ($isSymlink) {
            return $uploadedFile->store($dir, 'public');
        }

        // Otherwise write directly into public/storage/<dir>
        $fileName = $uploadedFile->hashName();
        $publicDir = public_path('storage/' . $dir);
        if (!is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }

        // move() will persist the uploaded file into public/storage
        $uploadedFile->move($publicDir, $fileName);

        return trim($dir . '/' . $fileName, '/');
    }

    public function uploadServiceImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'service_index' => 'required|integer',
        ]);

        if (!$this->ensureStorageLinked()) {
            return response()->json([
                'success' => false,
                'message' => 'Folder public/storage belum tersedia — jalankan "php artisan storage:link" terlebih dahulu.'
            ], 500);
        }

        $path = $this->saveUploadedPublicFile($request->file('image'), 'services');

        return response()->json([
            'success' => true,
            'path' => $path,
        ]);
    }
}
