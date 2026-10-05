# 📱 Barcode Review System

Aplikasi web berbasis **PHP MVC** untuk sistem ulasan tempat berbasis barcode/QR Code.

Pengunjung scan barcode di lokasi → langsung masuk ke halaman web berisi ulasan & form penilaian tempat tersebut.

Admin Master dapat mengelola semua tempat, melihat & memoderasi ulasan/kritik.

---

## ✨ Fitur

### Publik
- Halaman daftar semua tempat
- Halaman detail tempat (akses via scan barcode/QR)
- Form kirim ulasan + rating bintang (1-5)
- Ulasan ditampilkan setelah disetujui admin

### Admin Master
- Login aman (password hashed)
- Dashboard statistik
- CRUD Tempat (tambah, edit, hapus, detail)
- Generate QR Code otomatis per tempat
- Moderasi ulasan (approve / tolak / hapus)
- Lihat semua kritik & saran per tempat

---

## 📁 Struktur Folder (MVC)

```
barcode-review-app/
├── app/
│   ├── Controllers/     # Home, Place, Auth, Admin
│   ├── Models/          # Admin, Place, Review
│   ├── Views/           # Template HTML
│   │   ├── admin/
│   │   ├── auth/
│   │   ├── layouts/
│   │   ├── places/
│   │   └── ...
│   └── Core/            # Database, Router, Controller, helpers
├── config/
│   ├── app.php
│   └── database.php
├── database/
│   └── schema.sql       # Struktur DB + data sample
├── public/              # Document root
│   ├── index.php        # Front controller
│   ├── css/style.css
│   ├── uploads/
│   └── .htaccess
└── README.md
```

---

## 🚀 Cara Instalasi

### 1. Persyaratan
- PHP 7.4+ (disarankan PHP 8.x)
- MySQL / MariaDB
- Apache dengan `mod_rewrite` (atau PHP built-in server)

### 2. Setup Database

```bash
# Login ke MySQL
mysql -u root -p

# Import schema
source database/schema.sql
```

Atau jalankan isi file `database/schema.sql` melalui phpMyAdmin.

### 3. Konfigurasi

Edit file `config/database.php`:

```php
return [
    'host'     => 'localhost',
    'dbname'   => 'barcode_review',
    'username' => 'root',
    'password' => 'password_anda',
    'charset'  => 'utf8mb4',
];
```

Edit `config/app.php` → sesuaikan `'url'` dengan domain/URL Anda:

```php
'url' => 'http://localhost:8000',
```

### 4. Buat Password Admin yang Benar

Password default di schema menggunakan hash sementara. Buat hash baru:

```bash
php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
```

Lalu update di database:

```sql
UPDATE admins SET password = 'hash_yang_dihasilkan' WHERE username = 'admin';
```

**Atau** gunakan akun default setelah import (lihat catatan di schema.sql).

### 5. Jalankan Aplikasi

**Opsi A – PHP Built-in Server (paling mudah):**

```bash
cd barcode-review-app
php -S localhost:8000 -t public
```

Buka: http://localhost:8000

**Opsi B – Apache/XAMPP:**
- Copy folder ke `htdocs`
- Pastikan DocumentRoot mengarah ke folder `public/`
- Atau akses via `http://localhost/barcode-review-app/public`

---

## 🔑 Akun Default Admin

| Username | Password  |
|----------|-----------|
| admin    | admin123  |

> Setelah login pertama, disarankan segera ganti password.

---

## 📲 Cara Kerja Barcode

1. Admin menambahkan tempat baru → sistem otomatis generate `barcode_code` unik
2. Di halaman detail tempat (Admin), tersedia **QR Code** yang mengarah ke:
   ```
   https://domain-anda.com/p/{barcode_code}
   ```
3. Print/download QR Code → tempel di lokasi fisik
4. Pengunjung scan dengan HP → langsung masuk ke halaman ulasan tempat tersebut
5. Pengunjung isi rating + komentar → menunggu approval admin
6. Admin approve → ulasan tampil di halaman publik

---

## 🛠 Teknologi

- PHP Native (MVC pattern)
- MySQL
- PDO
- CSS modern (tanpa framework)
- QR Code via API publik (api.qrserver.com)

---

## 📝 Catatan

- Ulasan default **perlu approval** admin sebelum tampil publik
- Upload gambar max 2MB (jpg/png/webp/gif)
- Session-based authentication untuk admin
- Responsive design (mobile friendly)

---

Dibuat untuk keperluan sistem review berbasis barcode.
