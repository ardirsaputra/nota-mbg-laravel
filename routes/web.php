<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HargaBarangPokokController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\SatuanController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Admin dashboard
    Route::get('/admin', [HomeController::class, 'admin'])->name('admin');

    // Harga Barang Pokok routes
    Route::get('/harga-barang-pokok', [HargaBarangPokokController::class, 'index'])->name('harga-barang-pokok.index');
    Route::get('/harga-barang-pokok/create', [HargaBarangPokokController::class, 'create'])->name('harga-barang-pokok.create');
    Route::post('/harga-barang-pokok', [HargaBarangPokokController::class, 'store'])->name('harga-barang-pokok.store');
    Route::get('/harga-barang-pokok/{id}/edit', [HargaBarangPokokController::class, 'edit'])->name('harga-barang-pokok.edit');
    Route::put('/harga-barang-pokok/{id}', [HargaBarangPokokController::class, 'update'])->name('harga-barang-pokok.update');
    Route::delete('/harga-barang-pokok/{id}', [HargaBarangPokokController::class, 'destroy'])->name('harga-barang-pokok.destroy');
    Route::post('/harga-barang-pokok/update-ajax', [HargaBarangPokokController::class, 'updateAjax'])->name('harga-barang-pokok.update-ajax');

    // Nota routes
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

    // Satuan routes
    Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
    Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
    Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
    Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
    Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
    Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
});
