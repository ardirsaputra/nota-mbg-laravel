# Fitur Pendaftaran Pengguna - CV Mia Jaya Abadi

## Fitur Yang Ditambahkan

### 1. **Sistem Autentikasi**

- ✅ Halaman Register (`/register`) - Pengguna umum dapat mendaftar akun baru
- ✅ Halaman Login dengan link ke register
- ✅ Role system (admin/user) pada tabel users
- ✅ Middleware untuk memproteksi route admin-only

### 2. **Fitur Untuk Pengguna Umum**

- ✅ Membuat nota untuk diri sendiri
- ✅ Lihat daftar nota yang telah dibuat
- ✅ Print nota (tanpa watermark/logo background)
- ❌ **TIDAK BISA** menambah barang baru ke master data
- ❌ **TIDAK BISA** update harga master
- ✅ Nomor nota menggunakan generator yang sudah ada (MJA-{id}-{date}-{count})

### 3. **Fitur Clone Nota (Admin Only)**

- ✅ Admin dapat melihat daftar pengguna terdaftar
- ✅ Setiap pengguna menampilkan list nota mereka
- ✅ Admin dapat clone nota pengguna menjadi nota admin
- ✅ Nota yang sudah di-clone ditandai dengan badge
- ✅ Link ke nota hasil clone tersedia

### 4. **User Management (Admin)**

- ✅ Halaman `/users` untuk melihat semua pengguna terdaftar
- ✅ Statistik: Total pengguna, total nota, total nilai
- ✅ Untuk setiap user, tampil: nama, email, tanggal daftar, jumlah nota, total nilai
- ✅ Tabel nota lengkap dengan tombol "Clone" dan "Lihat"

### 5. **Perbedaan Print View**

- **Admin**: Print dengan watermark/logo background "CV MIA JAYA ABADI"
- **User**: Print tanpa watermark/logo background, ada badge "Nota Pengguna"

## Struktur Database

### Migration: `2024_01_02_000001_update_nota_table_for_users.php`

Menambahkan kolom pada tabel `nota`:

- `user_id` (foreign key ke users)
- `is_admin_nota` (boolean, default true)
- `cloned_from_id` (foreign key ke nota, nullable)

### Migration: `2024_01_01_000007_update_users_table.php`

Menambahkan kolom pada tabel `users`:

- `role` (string, default 'admin')
- Default admin user dibuat saat migration

## Routes

### Public Routes

- `GET /register` - Halaman pendaftaran
- `POST /register` - Proses pendaftaran
- `GET /login` - Halaman login (ditambahkan link ke register)

### Protected Routes (Auth Required)

- `GET /dashboard` - Redirect ke admin atau nota.index berdasarkan role
- `GET /nota` - Index nota (filter by user untuk role user)
- `GET /nota/{id}/print` - Print nota (berbeda tampilan untuk admin/user)

### Admin Only Routes

- `GET /users` - User management page
- `POST /nota/{id}/clone` - Clone nota dari user ke admin
- `GET /harga-barang-pokok/**` - Semua route master harga
- `GET /satuan/**` - Semua route master satuan
- `GET /kategori/**` - Semua route master kategori

## File Yang Dibuat/Dimodifikasi

### Controllers

- ✅ `AuthController.php` - Ditambahkan method `showRegister()` dan `register()`
- ✅ `NotaController.php` - Modifikasi untuk support user_id, clone method
- ✅ `UserController.php` - Controller baru untuk user management

### Middleware

- ✅ `AdminMiddleware.php` - Middleware baru untuk proteksi admin-only routes

### Models

- ✅ `User.php` - Ditambahkan method `isAdmin()`, `isUser()`, relasi `notas()`
- ✅ `Nota.php` - Ditambahkan fillable: user_id, is_admin_nota, cloned_from_id
    - Relasi: `user()`, `clonedFrom()`, `clones()`

### Views

- ✅ `auth/register.blade.php` - Halaman pendaftaran dengan form yang stylish
- ✅ `auth/login.blade.php` - Ditambahkan link ke register
- ✅ `users/index.blade.php` - User management page dengan list nota dan tombol clone
- ✅ `nota/print.blade.php` - Print view dengan conditional watermark
- ✅ `layouts/app.blade.php` - Navigation berbeda untuk admin vs user
- ✅ `admin.blade.php` - Ditambahkan card menu untuk User Management
- ✅ `home.blade.php` - Tombol "Daftar Akun" untuk guest, "Dashboard" untuk authenticated

## Cara Testing

### 1. Register User Baru

1. Buka `/register`
2. Isi form: Nama, Email, Password, Konfirmasi Password
3. Submit → otomatis login dan redirect ke dashboard
4. User role 'user' akan redirect ke `/nota` (nota mereka sendiri)

### 2. Buat Nota Sebagai User

1. Login sebagai user
2. Klik "Nota Saya" di navigation
3. Buat nota baru
4. **Catatan**: Checkbox "Update Harga Master" tidak berpengaruh untuk user (otomatis disabled di backend)

### 3. Clone Nota (Admin)

1. Login sebagai admin (email: `admin@cvmia.com`, password: `admin123`)
2. Klik "Pengguna" di navigation
3. Lihat list pengguna dan nota mereka
4. Klik tombol "Clone" pada nota yang belum di-clone
5. Nota baru akan dibuat dengan nomor baru sebagai admin nota

### 4. Print Nota

- **Sebagai User**: Print tidak ada watermark, ada badge "Nota Pengguna"
- **Sebagai Admin**: Print ada watermark "CV MIA JAYA ABADI" semi-transparan

## Credentials Default

**Admin:**

- Email: `admin@cvmia.com`
- Password: `admin123`
- Role: `admin`

**User:** (harus register sendiri)

- Role: `user`

## Security Features

1. ✅ Middleware `admin` untuk proteksi route admin-only
2. ✅ Authorization check di NotaController untuk akses print nota
3. ✅ Prevent user dari update master harga (forced false di backend)
4. ✅ Clone method hanya accessible oleh admin
5. ✅ User hanya bisa lihat nota mereka sendiri di index
6. ✅ Navigation menu dinamis berdasarkan role

## Migration Command

```bash
php artisan migrate
```

Akan menjalankan migration:

- `2024_01_02_000001_update_nota_table_for_users`

## Status Implementasi

✅ Semua fitur yang diminta sudah diimplementasikan:

1. ✅ Pendaftaran akun pengguna umum
2. ✅ Pengguna dapat membuat nota untuk dirinya
3. ✅ Nota bisa di-clone oleh admin
4. ✅ Fitur tambah barang baru dan update harga TIDAK dizinkan untuk user
5. ✅ Generator nomor nota menggunakan sistem existing
6. ✅ Halaman daftar pengguna dengan list nota dan tombol clone
7. ✅ Print untuk user tidak ada logo background/watermark
8. ✅ Middleware dan authorization untuk proteksi

## Next Steps (Opsional)

- [ ] Email verification untuk registrasi
- [ ] Forgot password feature
- [ ] User profile management
- [ ] Export nota user dalam batch
- [ ] Notifikasi untuk admin saat ada nota baru dari user
