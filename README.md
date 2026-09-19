# P2MPP Politeknik Negeri Malang

Website Pusat Penjaminan Mutu dan Pengembangan Pembelajaran (P2MPP) Politeknik Negeri Malang. Aplikasi ini menyediakan portal publik bilingual, repository dokumen, berita, program pelatihan, halaman informasi, serta panel administrasi untuk pengelolaan konten.

## Fitur Utama

- Beranda dengan hero image slider, navigasi responsif, dan konten terbaru.
- Navigasi menu dan submenu yang dapat dikelola dari panel admin.
- Dukungan bahasa Indonesia dan Inggris berbasis session.
- Halaman publik:
  - berita dan detail berita;
  - program pelatihan dan detail pelatihan;
  - repository dokumen dengan kategori, pencarian, detail, dan download;
  - halaman konten statis.
- Panel admin dengan dua tingkat akses:
  - `admin`: seluruh pengelolaan konten dan staff;
  - `editor`: pengelolaan konten operasional tanpa manajemen kategori, menu, dan staff.
- Autentikasi Laravel Fortify dan Breeze:
  - login, registrasi, logout;
  - forgot/reset password;
  - perubahan profil dan password;
  - two-factor authentication (2FA) opsional;
  - passkey/WebAuthn yang disediakan Fortify.
- Rich text editor TinyMCE untuk penulisan konten admin.
- Logo Politeknik Negeri Malang dan favicon.
- Sitemap publik di `/sitemap.xml`.
- Seeder demo, bilingual, dan importer konten valid P2MPP.

## Teknologi

- PHP `^8.3` (dikembangkan dan diuji dengan PHP 8.4).
- Laravel 13.
- Laravel Fortify 1.x.
- Laravel Breeze 2.x.
- SQLite sebagai database default.
- Vite dan Alpine.js.
- Tailwind CSS utility classes.
- TinyMCE 7.
- PHPUnit 12.

## Persyaratan Sistem

Pastikan perangkat memiliki:

- PHP 8.3 atau lebih baru;
- Composer;
- Node.js dan npm;
- ekstensi PHP SQLite, PDO, Mbstring, OpenSSL, Tokenizer, XML, Ctype, JSON, dan BCMath sesuai kebutuhan Laravel;
- Git, jika mengambil source dari repository.

Periksa instalasi:

```powershell
php -v
composer -V
node -v
npm -v
```

## Instalasi

### 1. Clone project

```powershell
git clone <url-repository> p2mpp-repo-app
cd p2mpp-repo-app
```

### 2. Instal dependency PHP

```powershell
composer install
```

### 3. Buat file environment

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Atur minimal konfigurasi berikut di `.env`:

```dotenv
APP_NAME="P2MPP Polinema"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
```

Jika file database belum ada:

```powershell
New-Item database\database.sqlite -ItemType File
```

### 4. Instal dependency frontend

```powershell
npm install
```

### 5. Migrasi database

```powershell
php artisan migrate
```

### 6. Isi data awal

Untuk mengisi seluruh data demo, menu, halaman, dokumen, dan konten valid P2MPP:

```powershell
php artisan db:seed
```

Seeder utama dijalankan oleh `DatabaseSeeder`:

| Seeder | Kegunaan |
|---|---|
| `DocumentRepositorySeeder` | Data dasar kategori, pelatihan, dan berita repository |
| `DemoDataSeeder` | Data demo kategori, kursus, berita, dan dokumen |
| `MenuSeeder` | Struktur menu dan halaman standar aplikasi |
| `P2mppContentSeeder` | Halaman dan tautan dokumen P2MPP yang telah disaring dari spam |

Untuk menjalankan importer P2MPP saja:

```powershell
php artisan db:seed --class=P2mppContentSeeder --force
```

Seeder importer menggunakan operasi idempoten dan dapat dijalankan ulang tanpa menggandakan data.

> **Peringatan:** `php artisan migrate:fresh --seed` menghapus seluruh tabel dan data. Gunakan hanya pada development atau setelah memastikan backup tersedia.

## Menjalankan Aplikasi

### Mode development

Terminal pertama:

```powershell
php artisan serve
```

Terminal kedua:

```powershell
npm run dev
```

Buka:

```text
http://127.0.0.1:8000
```

Laravel juga menyediakan script gabungan:

```powershell
composer run dev
```

Untuk penggunaan tanpa Vite development server, buat asset produksi:

```powershell
npm run build
```

## Struktur URL

### Publik

| URL | Keterangan |
|---|---|
| `/` | Beranda |
| `/berita` | Daftar berita |
| `/berita/{slug}` | Detail berita |
| `/pelatihan` | Daftar pelatihan |
| `/pelatihan/{slug}` | Detail pelatihan |
| `/dokumen` | Repository dokumen |
| `/dokumen/{id}` | Detail dokumen |
| `/dokumen/{id}/download` | Download dokumen |
| `/halaman/{slug}` | Halaman konten |
| `/language/id` | Mengubah bahasa ke Indonesia |
| `/language/en` | Mengubah bahasa ke Inggris |
| `/sitemap.xml` | Sitemap publik |

### Autentikasi dan profil

Route autentikasi disediakan Laravel Fortify dan view-nya dikustomisasi agar mengikuti layout publik. Route profil tersedia pada:

```text
/profile
```

Fortify menangani login, registrasi, reset password, update password, 2FA, dan passkey sesuai konfigurasi di `config/fortify.php`.

### Panel admin

Semua route panel berada di prefix `/admin`.

| URL | Akses | Keterangan |
|---|---|---|
| `/admin` | admin/editor | Dashboard |
| `/admin/dokumen` | admin/editor | Kelola dokumen |
| `/admin/berita` | admin/editor | Kelola berita |
| `/admin/program` | admin/editor | Kelola program pelatihan |
| `/admin/halaman` | admin/editor | Kelola halaman |
| `/admin/kategori` | admin | Kelola kategori dokumen |
| `/admin/menu` | admin | Kelola menu dan submenu |
| `/admin/staff` | admin | Kelola role staff |

## Role dan Authorization

Role utama user disimpan pada kolom `users.role`:

- `admin`: akses penuh ke panel.
- `editor`: akses pengelolaan konten operasional.
- role lain atau user biasa: tidak dapat masuk panel staff.

Middleware yang didaftarkan di `bootstrap/app.php`:

- `staff`: admin atau editor;
- `admin`: hanya admin;
- `2fa`: tersedia untuk kebutuhan proteksi tambahan.

Saat ini 2FA tidak dipaksakan secara global untuk seluruh route admin. User dapat mengaktifkan dan mengonfirmasi 2FA melalui halaman profil sesuai konfigurasi Fortify.

## Multi-bahasa

Bahasa aplikasi disimpan pada session melalui middleware `SetLocale`.

Bahasa yang didukung:

- `id`: Indonesia;
- `en`: English.

Terjemahan antarmuka berada di:

- `resources/lang/id.json`;
- `resources/lang/en.json`.

Konten database memiliki pasangan kolom bahasa Inggris:

| Tabel | Kolom Inggris |
|---|---|
| `pages` | `title_en`, `content_en` |
| `news` | `title_en`, `excerpt_en`, `content_en` |
| `courses` | `name_en`, `description_en`, `facilities_en` |
| `document_categories` | `name_en`, `description_en` |
| `documents` | `title_en`, `description_en` |
| `menu_items` | `label_en` |

Jika terjemahan Inggris kosong, model akan melakukan fallback ke konten Indonesia.

## Pengelolaan Konten

Form admin mendukung field Indonesia dan Inggris. Field konten menggunakan TinyMCE melalui atribut:

```html
<textarea data-rich-editor></textarea>
```

Inisialisasi TinyMCE berada di `resources/js/app.js`. Setelah mengubah JavaScript:

```powershell
npm run build
```

Untuk dokumen eksternal hasil importer P2MPP, `documents.file_path` menyimpan URL sumber resmi. Dokumen yang diunggah melalui panel admin mengikuti konfigurasi filesystem Laravel.

## Slider Beranda

Slider hero berada di `resources/views/home.blade.php` dan memakai Alpine.js. Asset gambar lokal berada di:

```text
public/images/home-slider/
```

Gambar slider saat ini:

- `kegiatan-p2mpp.jpeg`;
- `kepuasan-mahasiswa-2025.png`.

Slider mendukung autoplay, tombol next/previous, indikator slide, dan tampilan responsif.

## Struktur Direktori Penting

```text
app/
  Http/Controllers/          Controller publik dan admin
  Http/Middleware/           Middleware role, locale, dan 2FA
  Models/                    Model Eloquent
bootstrap/app.php            Registrasi route middleware
config/fortify.php           Konfigurasi autentikasi Fortify
database/
  factories/                 Factory untuk data uji/demo
  migrations/                Struktur database
  seeders/                   Seeder aplikasi dan importer P2MPP
public/images/               Logo dan asset gambar lokal
resources/
  js/app.js                  Alpine.js dan TinyMCE
  lang/                      Terjemahan JSON
  views/                     Blade layout, publik, auth, dan admin
routes/web.php               Route aplikasi
tests/Feature/               Feature test
```

## Pengujian dan Validasi

Jalankan test suite:

```powershell
php artisan test
```

Atau:

```powershell
composer test
```

Validasi Blade:

```powershell
php artisan view:cache
```

Validasi sintaks seeder atau file PHP tertentu:

```powershell
php -l database\seeders\P2mppContentSeeder.php
```

Pemeriksaan whitespace Git:

```powershell
git diff --check
```

## Cache dan Troubleshooting

Jika perubahan route, konfigurasi, translation, atau view belum terlihat:

```powershell
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

Kemudian jalankan ulang:

```powershell
php artisan migrate
php artisan db:seed
```

Masalah umum:

### `Undefined variable $slot`

Layout publik menggunakan Blade inheritance (`@extends`, `@section`, `@yield`), bukan anonymous component. Pastikan konten halaman memakai:

```blade
@extends('layouts.public')

@section('content')
    ...
@endsection
```

### `Attempt to read property "name" on null`

Pastikan layout navigasi membedakan user authenticated dan guest. Halaman publik tidak boleh mengakses `Auth::user()->name` tanpa guard `@auth`.

### Method reset password tidak didukung

Endpoint canonical Fortify untuk update password adalah `PUT /user/password`. Aplikasi juga mempertahankan route kompatibilitas `PUT /password`.

### TinyMCE tidak muncul

Pastikan:

```powershell
npm install
npm run build
```

Kemudian bersihkan cache view dan reload browser:

```powershell
php artisan optimize:clear
```

### Database SQLite gagal dibuka

Pastikan file berikut ada dan dapat ditulis:

```text
database/database.sqlite
```

## Konfigurasi Produksi

Sebelum deployment:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.example
```

Lalu jalankan:

```powershell
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
```

Pastikan:

- `APP_KEY` diisi dan dirahasiakan;
- environment production tidak memakai credential contoh;
- filesystem upload dikonfigurasi sesuai kebutuhan;
- database dan `storage` memiliki permission yang benar;
- backup database dijadwalkan;
- email production dikonfigurasi untuk reset password;
- file `.env` tidak di-commit;
- URL dokumen eksternal diverifikasi secara berkala.

## Catatan Import Konten P2MPP

`P2mppContentSeeder` dibuat dengan whitelist konten. Seeder tidak melakukan crawling bebas dan tidak menyalin teks atau link yang terindikasi spam dari situs sumber. Dokumen yang diimpor berasal dari URL domain resmi P2MPP dan saat ini disimpan sebagai tautan eksternal, bukan salinan file lokal.

Jika file perlu dimiliki secara lokal, unduh melalui proses terpisah, simpan pada disk Laravel, lalu ubah `file_path` melalui migrasi atau seeder yang sesuai.

## Lisensi dan Kepemilikan Konten

Kode dasar mengikuti struktur Laravel dan dependency masing-masing. Konten, logo, gambar, berita, dan dokumen P2MPP adalah milik pemilik website/institusi dan penggunaannya harus mengikuti kebijakan internal serta hak penggunaan aset.

