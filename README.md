# CV Mia Jaya Abadi - Laravel 12

Aplikasi manajemen harga bahan pokok dan nota untuk CV Mia Jaya Abadi yang dibangun dengan Laravel 12.

## Fitur

- ✅ Manajemen Harga Barang Pokok
- ✅ Manajemen Nota & Items
- ✅ Manajemen Satuan
- ✅ System Backup, Migrate & Restore Database
- ✅ Authentication & Authorization
- ✅ Export & Print Nota
- ✅ Profit Insight & Tracking

## Instalasi

### 1. Install Dependencies

```bash
composer install
```

### 2. Setup Environment

```bash
copy .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cvmia
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi Database dengan Backup

**PENTING**: Gunakan command `db:migrate-safe` untuk migrasi dengan backup otomatis:

```bash
php artisan db:migrate-safe
```

Command ini akan:

1. Backup semua tabel ke folder `storage/app/backups`
2. Menjalankan migration
3. Membuat default admin user

### 4. Jalankan Development Server

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Login Default

- **Email**: admin@cvmia.com
- **Password**: admin123

## Backup, Migrate & Restore System

### Backup Database

Backup semua tabel:

```bash
php artisan db:backup --all
```

Backup tabel tertentu:

```bash
php artisan db:backup harga_barang_pokok
```

File backup disimpan di: `storage/app/backups/`

### Restore Database

Restore semua tabel dari backup terakhir:

```bash
php artisan db:restore --all
```

Restore tabel tertentu:

```bash
php artisan db:restore harga_barang_pokok
```

### Migrate dengan Backup Otomatis

```bash
php artisan db:migrate-safe --force
```

## Migrasi Data dari Aplikasi Lama

Jika ada data dari aplikasi lama di `c:\Project\laravel\cv.miajayaabadi\backups\`:

1. Copy file backup JSON:

```bash
xcopy /E /I "c:\Project\laravel\cv.miajayaabadi\backups\*" "storage\app\backups\"
```

2. Restore:

```bash
php artisan db:restore --all
```
