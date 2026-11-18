# MaDocs — Sistem Informasi Terpadu Dokumen Akademik & Administrasi Kelulusan

Laravel 11/12 (PHP 8.2+). Paket ini adalah *overlay*: salin ke atas project Laravel baru.

## Instalasi
```bash
composer create-project laravel/laravel madocs
cd madocs
composer require barryvdh/laravel-dompdf
```
1. Salin SEMUA isi folder ini ke dalam folder `madocs` (timpa file yang sudah ada:
   `bootstrap/app.php`, `routes/web.php`, `app/Models/User.php`, `database/seeders/DatabaseSeeder.php`).
2. Atur `.env`:
   ```
   APP_NAME=MaDocs
   APP_LOCALE=id
   APP_TIMEZONE=Asia/Jakarta
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=madocs
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Buat database `madocs` di MySQL (phpMyAdmin/XAMPP).
3. Jalankan:
   ```bash
   php artisan migrate:fresh --seed
   php artisan serve
   ```
4. Buka http://127.0.0.1:8000

> Tampilan memakai Tailwind CSS & Alpine.js via CDN, jadi tidak perlu `npm install` (butuh internet saat membuka halaman).

## Akun demo (password: `password`)
| Role    | Email                 |
|---------|-----------------------|
| Admin   | admin@madocs.test     |
| Petugas | petugas@madocs.test   |
| Siswa   | siswa1@madocs.test … siswa5@madocs.test |

## Hak akses (RBAC)
| Fitur                                   | Admin | Petugas | Siswa |
|-----------------------------------------|:-----:|:-------:|:-----:|
| Master data Pengguna & Jenis Dokumen    |  ✔   |         |       |
| Kelola repositori dokumen (upload/edit) |  ✔   |   ✔    |       |
| Lihat dokumen                           | semua | semua  | milik sendiri |
| Verifikasi (Approved/Pending/Rejected)  |  ✔   |   ✔    |       |
| Catat pengembalian / penyerahan         |  ✔   |   ✔    |       |
| Ajukan pinjam / legalisir / ambil ijazah|       |         |  ✔   |
| Laporan + export PDF/Excel(CSV) + cetak |  ✔   |   ✔    |       |

## Alur layanan
- **Peminjaman rapor fisik**: siswa ajukan → petugas Approved (status fisik dokumen otomatis "dipinjam") → petugas catat pengembalian (status kembali "tersedia"). Terlambat ditandai otomatis.
- **Legalisir ijazah/rapor**: ajukan → verifikasi → catat penyerahan (Selesai).
- **Pengambilan ijazah asli**: siswa pilih jadwal (hari kerja, kuota 5/sesi, bisa diwakilkan) → petugas Approved → sistem membuat **kode validasi** → saat pengambilan petugas memvalidasi kode + nama + identitas penerima (jika diwakilkan, no. KTP harus sama dengan pengajuan) → status Diserahkan.

## Kotak Masuk (admin & petugas)
Halaman `/kotak-masuk` dan kartu di Beranda berisi semua yang harus diurus staf: verifikasi pengajuan baru (peminjaman, legalisir, jadwal ijazah) serta pengembalian/penyerahan yang tertunda. Diurutkan dari yang **mendesak** (terlambat/jadwal hari ini), **perlu verifikasi**, lalu **menunggu penyerahan**. Jumlahnya tampil sebagai badge di menu. Logika ada di `app/Support/Inbox.php`.

## Tampilan & panduan pemula
Tampilan baru terinspirasi Linear (tenang, mudah dipindai), Vercel (netral, minimalis), dan kartu bento lembut. Mendukung mode terang/gelap (tombol di bar atas, mengikuti pengaturan perangkat secara default).
- Palet (token CSS di `resources/views/partials/head.blade.php`): netral `#FAFAFA`/`#171717`, aksen indigo `#5E6AD2`, status: kuning (menunggu), teal (disetujui), merah (ditolak), indigo lembut (selesai).
- Font Geist (Google Fonts), Tailwind CSS & Alpine.js via CDN seperti sebelumnya.
- Ramah pemula: kartu "Mulai di sini" per peran di Beranda, pintasan layanan, penjelasan singkat di setiap halaman, alur tahapan (Diajukan, Diverifikasi, dst.) dan kotak "Apa selanjutnya?" di halaman detail, petunjuk di bawah kolom formulir, status berbahasa sederhana, halaman **Panduan Penggunaan** (`/bantuan`), serta daftar yang berubah menjadi kartu di layar HP.
- Label status hanya tampilan (`app/Support/Label.php`); nilai di database (`pending`, `approved`, ...) tidak berubah. Controller, model, migrasi, dan alur layanan tidak diubah.

## Struktur penting
- Migration: `database/migrations/2026_11_02_*` (users+, jenis_dokumens, documents, peminjamans, legalisirs, pengambilan_ijazahs)
- Seeder: `UserSeeder`, `JenisDokumenSeeder`, `DemoDataSeeder`
- Middleware RBAC: `app/Http/Middleware/RoleMiddleware.php` (alias `role:admin,petugas`)
- File dokumen disimpan privat di `storage/app/private/dokumen` (hanya bisa diakses pemilik/staf lewat route).
