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
