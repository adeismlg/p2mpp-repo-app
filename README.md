# Website Kampus/Unit (Laravel) + Repository Dokumen

Ini adalah **source code tambahan** untuk dipasang di atas project Laravel
baru (bukan Laravel utuh — folder `vendor/` didapat lewat Composer, bukan
ditulis manual). Terinspirasi dari struktur situs UPA Bahasa Polinema, plus
modul **Repository Dokumen** (upload, kategori/folder, pencarian, unduh).

## Fitur

- Halaman publik: Home, Berita, Program/Kursus, **Repository Dokumen**
- Repository Dokumen: upload file, kategori (folder), pencarian judul &
  deskripsi, halaman detail, unduh file + hitung jumlah unduhan
- Panel admin (di balik login) untuk CRUD Dokumen, Kategori, Berita, Program

## 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel upabahasa-clone "^11.0"
cd upabahasa-clone
```

## 2. Install login admin pakai Laravel Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

Breeze otomatis membuat halaman login/register + route `auth`. Nanti kamu
tinggal tentukan siapa yang boleh masuk `/admin` (lihat langkah 6).

## 3. Salin file dari folder ini

Struktur folder di sini sama persis dengan struktur folder Laravel. Salin/
gabungkan folder `app/`, `database/`, `routes/`, `resources/views/` ke dalam
project Laravel barumu. Jangan timpa file bawaan Breeze (`resources/views/auth/`,
`routes/auth.php`) — biarkan berdampingan.

## 4. Tambah disk penyimpanan dokumen

Tambahkan ke array `disks` di `config/filesystems.php`:

```php
'documents' => [
    'driver' => 'local',
    'root' => storage_path('app/public/documents'),
    'url' => env('APP_URL').'/storage/documents',
    'visibility' => 'public',
    'throw' => false,
],
```

Lalu:

```bash
php artisan storage:link
```

## 5. Migrasi database

Siapkan database (MySQL/SQLite) di `.env`, lalu:

```bash
php artisan migrate
```

### Isi dengan data contoh (Faker)

Supaya tampilan langsung terlihat penuh saat dikembangkan/didemokan,
jalankan seeder demo (butuh `database/factories/` dan
`database/seeders/DemoDataSeeder.php` dari folder ini):

```bash
php artisan db:seed --class=DemoDataSeeder
```

Ini akan membuat **8 kategori dokumen, 6 program, 15 berita, dan 40 dokumen**
otomatis lewat Faker — termasuk file dummy asli di
`storage/app/public/documents` supaya tombol unduh benar-benar berfungsi.
Aman dijalankan ulang kalau mau menambah lebih banyak data (dokumen & berita
akan bertambah, bukan menimpa).

Kalau mau data contoh ikut terbuat otomatis setiap `php artisan migrate:fresh --seed`,
tambahkan baris ini di `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call(DemoDataSeeder::class);
}
```

## 6. Sistem role: Admin & Editor/Staff

Sudah disediakan lengkap di folder ini — 3 tingkat akses:

| Role | Bisa masuk `/admin`? | Kelola Dokumen/Berita/Program | Kelola Kategori & Staff |
|---|---|---|---|
| `admin` | ✅ | ✅ | ✅ |
| `editor` (staff) | ✅ | ✅ | ❌ |
| `guest` (user biasa) | ❌ | ❌ | ❌ |

Default role user baru yang mendaftar sendiri (lewat halaman register Breeze)
adalah `guest`, jadi **aman** — mereka tidak otomatis bisa masuk panel admin.

Langkah pasang:

1. Timpa `app/Models/User.php` project kamu dengan `app/Models/User.php`
   dari folder ini (menambahkan helper `isAdmin()`, `isEditor()`, `isStaff()`).
2. Jalankan migrasi (migration `add_role_to_users_table` sudah otomatis
   memindahkan user yang tadinya `is_admin = true` menjadi role `admin`,
   kalau kamu sebelumnya sudah pakai sistem lama):
   ```bash
   php artisan migrate
   ```
3. Daftarkan 2 alias middleware baru di `bootstrap/app.php`, di bagian
   `->withMiddleware(function (Middleware $middleware) { ... })`:
   ```php
   $middleware->alias([
       'staff' => \App\Http\Middleware\EnsureUserIsStaff::class,
       'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
   ]);
   ```
4. Jadikan user pertama admin lewat `php artisan tinker`:
   ```php
   $u = App\Models\User::first();
   $u->role = 'admin';
   $u->save();
   ```
5. Login sebagai admin lalu buka **Manajemen Staff** (`/admin/staff`) untuk
   mengangkat user lain jadi `editor` — cukup pilih dari dropdown di sana,
   tidak perlu tinker lagi setelah ini.

Semua route `/admin/*` sudah dibungkus middleware `['auth', 'staff']`, dan
route `kategori` + `staff` (manajemen staff) tambahan dibungkus middleware
`admin` supaya hanya admin yang bisa akses.

## 7. Aktifkan 2FA (Two-Factor Authentication) via Laravel Fortify

Ini melindungi akun admin/staff dengan kode authenticator (Google
Authenticator/Authy), bukan cuma password — mitigasi paling ampuh untuk
kasus password bocor/ketebak.

### Install

```bash
composer require laravel/fortify
php artisan fortify:install
php artisan migrate
```

`fortify:install` akan:
- Membuat `app/Providers/FortifyServiceProvider.php` (**timpa** dengan
  versi dari folder ini — sudah diarahkan supaya tampilan Fortify pakai
  Blade view Breeze yang sudah ada, bukan bikin tampilan baru)
- Menambah kolom `two_factor_secret`, `two_factor_recovery_codes`, dll ke
  tabel `users` lewat migration otomatis

### Aktifkan fitur 2FA di config

Buka `config/fortify.php`, cari array `'features'`, aktifkan baris ini
(hapus tanda komentar):

```php
'features' => [
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication(),
],
```

> Catatan: sengaja **tidak** pakai opsi `['confirmPassword' => true]` di
> `twoFactorAuthentication()` supaya kompatibel dengan implementasi AJAX
> sederhana di panel profil (folder ini). Kalau kamu mau proteksi ekstra
> (wajib konfirmasi password sebelum ubah setting 2FA), aktifkan opsi itu,
> tapi form di `two-factor-authentication-form.blade.php` perlu disesuaikan
> supaya redirect ke halaman konfirmasi password ditangani dengan benar
> (tidak murni AJAX lagi).

### Nonaktifkan route auth bawaan Breeze (dioper ke Fortify)

Di `routes/web.php`, **hapus/komentari** baris ini di paling bawah:

```php
// require __DIR__.'/auth.php';
```

Fortify otomatis mendaftarkan route dengan nama yang SAMA persis
(`login`, `register`, `password.request`, dst) jadi Blade view Breeze yang
sudah ada tetap jalan tanpa perlu diubah.

### Pasang halaman tantangan 2FA & panel di profil

1. Salin `resources/views/auth/two-factor-challenge.blade.php` dari folder
   ini ke project kamu (Breeze tidak menyediakan ini secara default).
2. Buka `resources/views/profile/edit.blade.php` (dibuat otomatis oleh
   Breeze), tambahkan blok baru di antara "Update Password" dan
   "Delete Account":
   ```blade
   <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
       @include('profile.partials.two-factor-authentication-form')
   </div>
   ```
3. Salin `resources/views/profile/partials/two-factor-authentication-form.blade.php`
   dari folder ini ke project kamu.

### Middleware pendukung

- `app/Providers/FortifyServiceProvider.php` — sudah termasuk rate limit
  login (5x/menit) & rate limit percobaan kode 2FA.
- **(Opsional tapi disarankan)** `app/Http/Middleware/EnsureTwoFactorEnabled.php`
  — memaksa staff/admin setup 2FA sebelum bisa pakai panel. Daftarkan
  alias `2fa` di `bootstrap/app.php`, lalu tambahkan ke grup route admin
  di `routes/web.php`:
  ```php
  Route::middleware(['auth', 'staff', '2fa'])->prefix('admin')-> ...
  ```

### Build ulang asset

Karena view 2FA pakai Alpine.js (`x-data`, dst) yang sudah termasuk paket
Breeze blade stack:

```bash
npm run build
```

### Test

1. Login sebagai admin/editor → buka `/profile` → klik **Aktifkan 2FA**
2. Scan QR code yang muncul dengan Google Authenticator/Authy
3. Masukkan kode 6 digit → simpan kode pemulihan yang muncul di tempat aman
4. Logout, login lagi → seharusnya diminta kode 2FA sebelum masuk

## 8. Jalankan

```bash
php artisan serve
```

Buka `http://localhost:8000`.

## Tambahan di update ini

- **2FA via Laravel Fortify** — lihat langkah 7 di atas. File pendukung:
  `app/Providers/FortifyServiceProvider.php`,
  `resources/views/auth/two-factor-challenge.blade.php`,
  `resources/views/profile/partials/two-factor-authentication-form.blade.php`,
  `app/Http/Middleware/EnsureTwoFactorEnabled.php` (opsional, memaksa staff
  aktifkan 2FA sebelum pakai panel).
- **Keamanan** — baca `SECURITY.md` di root folder ini, checklist khusus
  mencegah kasus website disusupi (mis. spam judi online), termasuk header
  keamanan (`app/Http/Middleware/SecurityHeaders.php`) dan konfigurasi
  server (`server-hardening/`).
- **Dashboard admin didesain ulang** jadi lebih premium: kartu statistik
  bergradasi, grafik batang unduhan per kategori, grafik donat visibilitas
  dokumen (pakai Chart.js lewat CDN — tidak perlu instalasi apa pun), daftar
  dokumen terpopuler, dan aktivitas unggahan terbaru. Lihat
  `app/Http/Controllers/Admin/DashboardController.php` dan
  `resources/views/admin/dashboard.blade.php`.
- **Sistem role Admin/Editor/Staff** — lihat langkah 6 di atas.
- **Migration `is_admin`** (sudah digantikan sistem role di langkah 6, tapi
  kolomnya tetap otomatis dimigrasikan kalau kamu sempat pakai versi lama).
- **Menu mobile (hamburger)** sudah ditambahkan di `layouts/app.blade.php`.
- **SEO dasar**: meta description, Open Graph tag, `public/robots.txt`, dan
  `/sitemap.xml` otomatis (dari `SitemapController` + `resources/views/sitemap.blade.php`).
- **Feature test** contoh di `tests/Feature/DocumentRepositoryTest.php` —
  menguji dokumen publik vs tersembunyi, pencarian, dan penghitung unduhan.
  Jalankan dengan `php artisan test`.

## Peta file

| Bagian | Lokasi |
|---|---|
| Migration | `database/migrations/*` |
| Model | `app/Models/Document.php`, `DocumentCategory.php`, `News.php`, `Course.php` |
| Controller publik | `app/Http/Controllers/*.php` |
| Controller admin | `app/Http/Controllers/Admin/*.php` |
| View publik | `resources/views/home.blade.php`, `documents/`, `news/`, `courses/` |
| View admin | `resources/views/admin/*` |
| Routing | `routes/web.php` |

Styling pakai **Tailwind via CDN** langsung di `layouts/app.blade.php` supaya
bisa langsung dicoba tanpa setup build tool — nanti bisa dipindah ke Vite
kalau sudah siap produksi.

## Cara pakai Repository Dokumen

- `GET /dokumen` — daftar dokumen publik. Filter: `?kategori=slug-kategori`
  dan `?q=kata+kunci` (cari di judul & deskripsi)
- `GET /dokumen/{document}` — detail dokumen
- `GET /dokumen/{document}/download` — unduh file, otomatis menambah
  `downloads_count`
- `/admin/dokumen` — CRUD dokumen (upload/ganti/hapus file)
- `/admin/kategori` — CRUD kategori/folder dokumen

Validasi upload default: `pdf,doc,docx,xls,xlsx,ppt,pptx,zip`, maksimal
20MB — sesuaikan di `app/Http/Controllers/Admin/DocumentController.php`.
