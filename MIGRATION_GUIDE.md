# PANDUAN MIGRASI APLIKASI CV MIA JAYA ABADI KE LARAVEL 12

## STATUS IMPLEMENTASI

### ✅ SUDAH SELESAI

1. **Setup Laravel 12 Project** ✅
    - Laravel 12 sudah terinstall di `c:\Project\laravel\cv-miajayaabadi-laravel`
    - Semua dependencies sudah terinstall

2. **Database Migrations** ✅
    - `2024_01_01_000003_create_satuan_table.php` - Table satuan dengan seed data
    - `2024_01_01_000004_create_harga_barang_pokok_table.php` - Table harga barang pokok
    - `2024_01_01_000005_create_nota_table.php` - Table nota
    - `2024_01_01_000006_create_nota_items_table.php` - Table nota items
    - `2024_01_01_000007_update_users_table.php` - Update users table dengan role & default admin

3. **Models** ✅
    - `App\Models\Satuan` - Model satuan
    - `App\Models\HargaBarangPokok` - Model harga barang pokok
    - `App\Models\Nota` - Model nota dengan relationship ke items
    - `App\Models\NotaItem` - Model nota items dengan auto-calculate subtotal
    - `App\Models\User` - Model user dengan role field

4. **Artisan Commands untuk Backup/Migrate/Restore** ✅
    - `App\Console\Commands\DatabaseBackup` - Command `php artisan db:backup`
    - `App\Console\Commands\DatabaseRestore` - Command `php artisan db:restore`
    - `App\Console\Commands\DatabaseMigrateSafe` - Command `php artisan db:migrate-safe`

5. **Controllers** ✅
    - `App\Http\Controllers\AuthController` - Login/logout
    - `App\Http\Controllers\HomeController` - Home, admin, contact
    - `App\Http\Controllers\HargaBarangPokokController` - CRUD harga barang pokok
    - `App\Http\Controllers\NotaController` - CRUD nota & items
    - `App\Http\Controllers\SatuanController` - CRUD satuan

6. **Routes** ✅
    - Semua routes sudah didefinisikan di `routes/web.php`
    - Public routes: home, contact, login
    - Protected routes (middleware auth): admin, harga barang pokok, nota, satuan

7. **Blade Templates (Sebagian)** ⚠️
    - `resources/views/layouts/app.blade.php` - Layout utama dengan navbar
    - `resources/views/auth/login.blade.php` - Halaman login
    - `resources/views/admin.blade.php` - Dashboard admin

### ⏳ BELUM SELESAI - PERLU DILANJUTKAN

#### 1. Blade Views yang Perlu Dibuat

Anda perlu membuat file-file blade berikut dengan UI yang sama seperti aplikasi lama:

**Home & Public Views:**

- `resources/views/home.blade.php` - Halaman beranda (copy dari `index.php`)
- `resources/views/contact.blade.php` - Halaman kontak (copy dari `contact.php`)

**Harga Barang Pokok Views:**

- `resources/views/harga_barang_pokok/index.blade.php` - List harga barang
- `resources/views/harga_barang_pokok/create.blade.php` - Form tambah barang
- `resources/views/harga_barang_pokok/edit.blade.php` - Form edit barang

**Nota Views:**

- `resources/views/nota/index.blade.php` - List nota
- `resources/views/nota/create.blade.php` - Form buat nota baru
- `resources/views/nota/edit.blade.php` - Form edit nota & tambah items
- `resources/views/nota/show.blade.php` - Detail nota
- `resources/views/nota/print.blade.php` - Print nota
- `resources/views/nota/export_month.blade.php` - Export nota per bulan

**Satuan Views:**

- `resources/views/satuan/index.blade.php` - List satuan
- `resources/views/satuan/create.blade.php` - Form tambah satuan
- `resources/views/satuan/edit.blade.php` - Form edit satuan

#### 2. Copy Assets

Copy file CSS, JS, dan images dari aplikasi lama:

```bash
# Copy CSS
xcopy /E /I "c:\Project\laravel\cv.miajayaabadi\assets\css" "c:\Project\laravel\cv-miajayaabadi-laravel\public\css"

# Copy JS
xcopy /E /I "c:\Project\laravel\cv.miajayaabadi\assets\js" "c:\Project\laravel\cv-miajayaabadi-laravel\public\js"

# Copy Images
xcopy /E /I "c:\Project\laravel\cv.miajayaabadi\assets\image" "c:\Project\laravel\cv-miajayaabadi-laravel\public\images"
```

#### 3. Setup Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cvmia
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration dengan backup:

```bash
cd c:\Project\laravel\cv-miajayaabadi-laravel
php artisan db:migrate-safe --force
```

#### 4. Migrasi Data dari Aplikasi Lama (Opsional)

Jika ada data di aplikasi lama:

```bash
# Copy backup files
xcopy /E /I "c:\Project\laravel\cv.miajayaabadi\backups" "c:\Project\laravel\cv-miajayaabadi-laravel\storage\app\backups"

# Restore data
php artisan db:restore --all
```

#### 5. Setup Categories JSON

Copy file categories.json:

```bash
copy "c:\Project\laravel\cv.miajayaabadi\harga_bahan_pokok\categories.json" "c:\Project\laravel\cv-miajayaabadi-laravel\storage\app\categories.json"
```

## CARA MEMBUAT BLADE VIEWS

Untuk membuat blade views, ikuti pattern ini:

### Contoh: Harga Barang Pokok Index

Buat file `resources/views/harga_barang_pokok/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Harga Barang Pokok')

@push('styles')
<style>
    /* Copy style dari harga_bahan_pokok/index.php */
</style>
@endpush

@section('content')
<div class="container">
    <!-- Copy HTML content dari harga_bahan_pokok/index.php -->
    <!-- Ganti PHP code dengan Blade syntax -->

    @foreach($barang_pokok as $barang)
        <div class="item">
            <h3>{{ $barang->uraian }}</h3>
            <p>{{ number_format($barang->harga_satuan) }}</p>
        </div>
    @endforeach
</div>
@endsection
```

### Pattern Konversi PHP ke Blade

**PHP Lama:**

```php
<?php foreach ($barang_pokok as $barang): ?>
    <div><?= htmlspecialchars($barang['uraian']) ?></div>
<?php endforeach; ?>
```

**Blade Baru:**

```blade
@foreach($barang_pokok as $barang)
    <div>{{ $barang->uraian }}</div>
@endforeach
```

**PHP Lama:**

```php
<?php if ($is_logged_in): ?>
    <a href="logout.php">Logout</a>
<?php endif; ?>
```

**Blade Baru:**

```blade
@auth
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endauth
```

## COMMANDS YANG TERSEDIA

```bash
# Backup database
php artisan db:backup --all                     # Backup semua tabel
php artisan db:backup harga_barang_pokok       # Backup tabel tertentu

# Restore database
php artisan db:restore --all                    # Restore semua tabel
php artisan db:restore harga_barang_pokok      # Restore tabel tertentu

# Migrate dengan backup otomatis
php artisan db:migrate-safe --force            # Migrate dengan backup otomatis

# Run development server
php artisan serve                              # Jalankan server di http://localhost:8000
```

## LOGIN DEFAULT

- **Email**: admin@cvmia.com
- **Password**: admin123

## NEXT STEPS

1. ✅ Setup project - SELESAI
2. ✅ Migrations - SELESAI
3. ✅ Models - SELESAI
4. ✅ Controllers - SELESAI
5. ✅ Routes - SELESAI
6. ✅ Backup/Restore Commands - SELESAI
7. ⏳ **Buat Blade Views** - IN PROGRESS (sudah: layout, login, admin)
8. ⏳ **Copy Assets** - BELUM
9. ⏳ **Testing** - BELUM

## CARA MELANJUTKAN

Untuk melanjutkan pembuatan views, saya sarankan:

1. Buat satu view pada satu waktu
2. Test setiap view setelah dibuat
3. Mulai dari yang paling penting: harga_barang_pokok/index.blade.php
4. Copy style dari file PHP lama ke section @push('styles')
5. Copy HTML content dan convert PHP syntax ke Blade
6. Test dengan data dummy dulu

Apakah Anda ingin saya lanjutkan membuat file-file view yang tersisa?
