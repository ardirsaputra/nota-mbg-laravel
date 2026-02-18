<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HargaBarangPokokController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserBarangController;

// Public routes
Route::get('/', function () {
    return view('welcome-new');
})->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Public price list for guests
Route::get('/harga-barang-pokok', [HargaBarangPokokController::class, 'index'])->name('harga-barang-pokok.index');
Route::get('/harga-barang-pokok/print', [HargaBarangPokokController::class, 'print'])->name('harga-barang-pokok.print');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Admin helper: create public/storage symlink (admin-only)
// Tries symlink first and falls back to copying storage/app/public -> public/storage when symlinks are not allowed
Route::get('/storage-link', function () {
    $src = storage_path('app/public');
    $dst = public_path('storage');

    // Try the normal artisan command first
    try {
        // Guard against open_basedir restrictions which make storage_path() inaccessible on some hosts
        $openBasedir = ini_get('open_basedir');
        $srcAccessible = true;
        if ($openBasedir) {
            // sanitize open_basedir entries and skip any containing null bytes
            $allowed = array_filter(array_map('trim', explode(PATH_SEPARATOR, $openBasedir)), fn($s) => $s !== '' && strpos($s, "\0") === false);
            $realSrc = @realpath($src);
            $srcAccessible = false;
            if ($realSrc) {
                foreach ($allowed as $a) {
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

        if (!$srcAccessible) {
            // Host prevents access to storage_path — create public/storage directory as best-effort fallback
            @mkdir($dst, 0755, true);
            return redirect()->back()->with('status', 'Host prevents accessing storage_path — created public/storage fallback directory');
        }

        \Artisan::call('storage:link');
        return redirect()->back()->with('status', 'storage:link executed successfully');
    } catch (\Throwable $e) {
        // Fallback: recursively copy files into public/storage (only if source is accessible)
        try {
            if (!is_dir($src)) {
                return redirect()->back()->with('error', "storage:link failed and source '$src' not found: " . $e->getMessage());
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                $target = $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                if ($item->isDir()) {
                    @mkdir($target, 0755, true);
                } else {
                    copy($item->getPathname(), $target);
                }
            }

            return redirect()->back()->with('status', 'storage:link not allowed on host — performed fallback copy to public/storage');
        } catch (\Throwable $ex) {
            return redirect()->back()->with('error', 'storage:link failed and fallback copy failed: ' . $e->getMessage() . ' / ' . $ex->getMessage());
        }
    }
})->name('storage-link');
// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', function () {
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        if ($authUser && $authUser->isAdmin()) {
            return redirect()->route('admin');
        }
        return redirect()->route('nota.index');
    })->name('dashboard');

    // Profile routes (edit own profile + toko)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Admin dashboard
    Route::get('/admin', [HomeController::class, 'admin'])->name('admin')->middleware('admin');

    // Admin-only routes for managing master data
    Route::middleware(['admin'])->group(function () {
        // Harga Barang Pokok routes (admin management)
        Route::get('/harga-barang-pokok/create', [HargaBarangPokokController::class, 'create'])->name('harga-barang-pokok.create');
        Route::post('/harga-barang-pokok', [HargaBarangPokokController::class, 'store'])->name('harga-barang-pokok.store');
        Route::get('/harga-barang-pokok/{id}/edit', [HargaBarangPokokController::class, 'edit'])->name('harga-barang-pokok.edit');
        Route::put('/harga-barang-pokok/{id}', [HargaBarangPokokController::class, 'update'])->name('harga-barang-pokok.update');
        Route::delete('/harga-barang-pokok/{id}', [HargaBarangPokokController::class, 'destroy'])->name('harga-barang-pokok.destroy');
        Route::post('/harga-barang-pokok/update-ajax', [HargaBarangPokokController::class, 'updateAjax'])->name('harga-barang-pokok.update-ajax');

        // Import / Export / WhatsApp
        Route::get('/harga-barang-pokok/export', [HargaBarangPokokController::class, 'exportCsv'])->name('harga-barang-pokok.export');
        Route::get('/harga-barang-pokok/export-wa', [HargaBarangPokokController::class, 'exportWa'])->name('harga-barang-pokok.export-wa');
        Route::get('/harga-barang-pokok/import', [HargaBarangPokokController::class, 'showImportForm'])->name('harga-barang-pokok.import');
        Route::post('/harga-barang-pokok/import', [HargaBarangPokokController::class, 'importCsv'])->name('harga-barang-pokok.import.post');
        Route::get('/harga-barang-pokok/import-wa', [HargaBarangPokokController::class, 'showImportWaForm'])->name('harga-barang-pokok.import-wa');
        Route::post('/harga-barang-pokok/import-wa', [HargaBarangPokokController::class, 'importWa'])->name('harga-barang-pokok.import-wa.post');


        // Satuan routes
        Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
        Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
        Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
        Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
        Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
        Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

        // Kategori routes
        Route::get('/kategori', [\App\Http\Controllers\KategoriController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [\App\Http\Controllers\KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', [\App\Http\Controllers\KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{id}/edit', [\App\Http\Controllers\KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{id}', [\App\Http\Controllers\KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [\App\Http\Controllers\KategoriController::class, 'destroy'])->name('kategori.destroy');

        // Toko routes
        Route::resource('toko', TokoController::class);

        // User Management (admin only)
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::post('/nota/{id}/clone', [NotaController::class, 'clone'])->name('nota.clone');

        // Website Settings (admin only)
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/gallery/upload', [SettingController::class, 'uploadGallery'])->name('settings.gallery.upload');
        Route::delete('/settings/gallery/{gallery}', [SettingController::class, 'deleteGallery'])->name('settings.gallery.delete');
        Route::post('/settings/service/upload', [SettingController::class, 'uploadServiceImage'])->name('settings.service.upload');

        // Admin helper: run migrations + seed (admin-only)
        Route::get('/admin/run-migrations', function () {
            try {
                // Ensure DB cache table exists when CACHE_STORE=database to avoid cache:clear failures
                if (config('cache.default') === 'database') {
                    $cacheTable = config('cache.stores.database.table', 'cache');
                    if (!\Illuminate\Support\Facades\Schema::hasTable($cacheTable)) {
                        \Artisan::call('cache:table');
                    }
                }

                \Artisan::call('migrate', ['--force' => true]);

                // Only run seed if the settings table exists (prevents seeder errors on partial migrations)
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    \Artisan::call('db:seed', ['--force' => true]);
                }

                return redirect()->back()->with('status', 'Migrations and seed executed successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Migrate/Seed failed: ' . $e->getMessage());
            }
        })->name('admin.run-migrations');

        // Admin helper: storage audit (admin-only)
        Route::get('/admin/storage-audit', function () {
            if (!Auth::check() || !Auth::user()->isAdmin())
                abort(403);

            $publicStorage = public_path('storage');
            $isSymlink = is_link($publicStorage) && @realpath($publicStorage) === @realpath(storage_path('app/public'));
            $settingsDir = $publicStorage . DIRECTORY_SEPARATOR . 'settings';

            $files = [];
            if (is_dir($settingsDir)) {
                foreach (new \DirectoryIterator($settingsDir) as $f) {
                    if ($f->isFile()) {
                        $files[] = [
                            'name' => $f->getFilename(),
                            'size' => $f->getSize(),
                            'mtime' => date('c', $f->getMTime()),
                            'perms' => substr(sprintf('%o', $f->getPerms()), -4),
                            'readable' => is_readable($f->getPathname()),
                            'writable' => is_writable($f->getPathname()),
                        ];
                    }
                }
            }

            $companyLogo = \App\Models\Setting::get('company_logo');
            $logoPublic = $companyLogo ? file_exists($publicStorage . '/' . $companyLogo) : false;
            $logoDisk = $companyLogo ? \Illuminate\Support\Facades\Storage::disk('public')->exists($companyLogo) : false;

            return response()->json([
                'is_symlink' => $isSymlink,
                'public_storage_exists' => file_exists($publicStorage),
                'public_storage_writable' => is_writable($publicStorage),
                'settings_files' => $files,
                'company_logo' => $companyLogo,
                'company_logo_public_exists' => $logoPublic,
                'company_logo_disk_exists' => $logoDisk,
            ]);
        })->name('admin.storage-audit');

        // Admin helper: copy storage/app/public -> public/storage (admin-only)
        Route::get('/admin/storage-sync', function () {
            if (!Auth::check() || !Auth::user()->isAdmin())
                abort(403);

            $src = storage_path('app/public');
            $dst = public_path('storage');

            if (!is_dir($src)) {
                return redirect()->back()->with('error', 'Source storage path not accessible on this host.');
            }

            try {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::SELF_FIRST
                );

                foreach ($iterator as $item) {
                    $target = $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                    if ($item->isDir()) {
                        @mkdir($target, 0755, true);
                    } else {
                        @copy($item->getPathname(), $target);
                    }
                }

                return redirect()->back()->with('status', 'Synced storage/app/public -> public/storage');
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Sync failed: ' . $e->getMessage());
            }
        })->name('admin.storage-sync');

        // Admin helper: inspect important env/config values (admin-only)
        Route::get('/admin/debug-config', function () {
            if (!Auth::check() || !Auth::user()->isAdmin())
                abort(403);
            return response()->json([
                'APP_ENV' => env('APP_ENV'),
                'APP_DEBUG' => env('APP_DEBUG'),
                'DB_HOST (env)' => env('DB_HOST'),
                'DB_HOST (config)' => config('database.connections.mysql.host'),
                'config_cached' => file_exists(base_path('bootstrap/cache/config.php')),
                'dotenv_exists' => file_exists(base_path('.env')),
                'base_path' => base_path(),
            ]);
        })->name('admin.debug-config');

    });

    // Nota routes (accessible by both admin and user)
    Route::post('/nota/barang', [NotaController::class, 'storeBarang'])->name('nota.storeBarang');

    // User's own Barang management (accessible to all auth users)
    Route::get('/barang-saya', [UserBarangController::class, 'index'])->name('barang-saya.index');
    Route::post('/barang-saya', [UserBarangController::class, 'store'])->name('barang-saya.store');
    Route::post('/barang-saya/copy', [UserBarangController::class, 'copyFromAdmin'])->name('barang-saya.copy');
    Route::get('/barang-saya/{id}/edit', [UserBarangController::class, 'edit'])->name('barang-saya.edit');
    Route::put('/barang-saya/{id}', [UserBarangController::class, 'update'])->name('barang-saya.update');
    Route::delete('/barang-saya/{id}', [UserBarangController::class, 'destroy'])->name('barang-saya.destroy');

    Route::get('/nota', [NotaController::class, 'index'])->name('nota.index');
    Route::get('/nota/create', [NotaController::class, 'create'])->name('nota.create');
    Route::post('/nota', [NotaController::class, 'store'])->name('nota.store');
    Route::get('/nota/{id}', [NotaController::class, 'show'])->name('nota.show');
    Route::get('/nota/{id}/edit', [NotaController::class, 'edit'])->name('nota.edit');
    Route::put('/nota/{id}', [NotaController::class, 'update'])->name('nota.update');
    Route::delete('/nota/{id}', [NotaController::class, 'destroy'])->name('nota.destroy');
    Route::post('/nota/{id}/items', [NotaController::class, 'addItem'])->name('nota.add-item');
    Route::delete('/nota/{notaId}/items/{itemId}', [NotaController::class, 'deleteItem'])->name('nota.delete-item');
    Route::post('/nota/{id}/toggle-lock', [NotaController::class, 'toggleLock'])->name('nota.toggle-lock');
    Route::post('/nota/{id}/toggle-profit', [NotaController::class, 'toggleProfitInsight'])->name('nota.toggle-profit');
    Route::get('/nota/{id}/print', [NotaController::class, 'print'])->name('nota.print');
    Route::get('/nota-export-month', [NotaController::class, 'exportMonth'])->name('nota.export-month');
});
