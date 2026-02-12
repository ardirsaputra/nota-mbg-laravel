# 🚀 QUICK START GUIDE

## LANGKAH CEPAT UNTUK MULAI MENGGUNAKAN APLIKASI

### 1. Setup Database (5 menit)

```bash
# Edit file .env
DB_CONNECTION=mysql
DB_DATABASE=cvmia
DB_USERNAME=root
DB_PASSWORD=

# Buat database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS cvmia"
```

### 2. Jalankan Migration dengan Backup (2 menit)

```bash
cd c:\Project\laravel\cv-miajayaabadi-laravel

# Migrate dengan backup otomatis
php artisan db:migrate-safe --force
```

Perintah ini akan:

- ✅ Backup semua tabel existing
- ✅ Menjalankan migrations
- ✅ Membuat admin user default
- ✅ Insert data satuan default

### 3. Jalankan Server (1 menit)

```bash
php artisan serve
```

### 4. Login & Test

Buka browser: `http://localhost:8000`

**Login Admin:**

- Email: `admin@cvmia.com`
- Password: `admin123`

### 5. (Opsional) Restore Data Lama

Jika Anda punya data dari aplikasi lama:

```bash
# Copy backup files ke Laravel
xcopy /E /I "c:\Project\laravel\cv.miajayaabadi\backups\*" "storage\app\backups\"

# Restore semua data
php artisan db:restore --all
```

## ✅ FITUR YANG SUDAH BISA DIGUNAKAN

1. ✅ **Login/Logout** - Sudah berfungsi penuh
2. ✅ **Dashboard Admin** - Tampilan menu utama
3. ✅ **Harga Barang Pokok**:
    - List dengan search & filter kategori
    - Tambah barang baru
    - Edit barang
    - Hapus barang
4. ✅ **Backup/Restore Database**:
    - Backup semua tabel: `php artisan db:backup --all`
    - Restore semua tabel: `php artisan db:restore --all`

## ⏳ YANG MASIH PERLU DITAMBAHKAN

Untuk melengkapi aplikasi, Anda perlu membuat views untuk:

1. **Nota Management** (15 views)
2. **Satuan Management** (3 views)
3. **Home & Contact Page** (2 views)

**Total sekitar 20 views lagi yang perlu dibuat.**

Semua controller dan routes sudah siap, tinggal copy HTML dari aplikasi lama dan convert ke Blade syntax.

## 📖 COMMAND REFERENCE

### Database Commands

```bash
# Backup
php artisan db:backup --all                    # Backup semua tabel
php artisan db:backup harga_barang_pokok      # Backup 1 tabel

# Restore
php artisan db:restore --all                   # Restore semua dari backup latest
php artisan db:restore harga_barang_pokok     # Restore 1 tabel

# Migrate dengan Backup
php artisan db:migrate-safe                    # Migrate + auto backup
php artisan db:migrate-safe --force           # Force mode (no confirmation)
```

### Laravel Commands

```bash
php artisan serve                              # Run server
php artisan migrate                            # Run migrations
php artisan migrate:rollback                   # Rollback last migration
php artisan route:list                         # Show all routes
php artisan make:controller NameController     # Create controller
```

## 🎯 TIPS

1. **Selalu backup sebelum migrate**: Gunakan `php artisan db:migrate-safe`
2. **Backup files di**: `storage/app/backups/`
3. **Format backup**: JSON dengan timestamp
4. **Restore selalu ambil file terbaru** otomatis
5. **Bisa restore file spesifik** dengan `--file=nama_file.json`

## ❓ TROUBLESHOOTING

### Error "Access Denied" saat migrate

```bash
# Pastikan MySQL running dan credentials benar di .env
# Test koneksi:
mysql -u root -p
```

### Error "Class not found"

```bash
composer dump-autoload
```

### Error permission denied di storage

```bash
# Windows PowerShell
icacls "storage" /grant Everyone:F /t
```

### Lupa password admin

```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'admin@cvmia.com')->first();
>>> $user->password = Hash::make('admin123');
>>> $user->save();
```

## 📊 DATABASE STRUCTURE

### Tables:

1. **users** - User accounts dengan role
2. **satuan** - Master satuan (Kg, Liter, dll)
3. **harga_barang_pokok** - Daftar harga barang
4. **nota** - Header nota penjualan
5. **nota_items** - Items dalam nota

### Default Data:

- 1 admin user
- 10 satuan default (Kg, Gram, Liter, dll)

## 🔐 SECURITY

- ✅ Password di-hash dengan bcrypt
- ✅ CSRF protection aktif
- ✅ Middleware authentication
- ✅ Input validation di controller
- ✅ SQL injection protected (Eloquent ORM)

## 📞 SUPPORT

Email: ardi.rs@gmail.com

---

**Aplikasi siap digunakan untuk:**

- ✅ Manajemen harga barang pokok
- ✅ Backup & restore database
- ✅ Authentication & authorization

**Perlu dilengkapi:**

- ⏳ Views untuk Nota
- ⏳ Views untuk Satuan
- ⏳ Views untuk Home & Contact

Semua backend logic sudah lengkap! 🎉
