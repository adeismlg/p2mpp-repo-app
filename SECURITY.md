# Checklist Keamanan (khusus mencegah kasus seperti "judol")

Kasus website disusupi konten judi online biasanya lewat salah satu dari:
kredensial admin lemah/bocor, upload file berbahaya, dependency usang
dengan celah keamanan diketahui, atau server misconfigured (document root
salah, directory listing terbuka). Checklist ini menutup semuanya.

## Sudah otomatis aman di source code ini
- [x] Document root Laravel = folder `public/` saja → `.env`, `app/`, `database/`
      tidak bisa diakses lewat browser sama sekali (beda dari CI yang sering
      taruh semua file di satu folder web-accessible).
- [x] Semua query pakai Eloquent/Query Builder → aman dari SQL Injection.
- [x] Semua model pakai `$fillable` → aman dari mass assignment.
- [x] Semua form pakai `@csrf`.
- [x] Upload file divalidasi tipe isi file (bukan cuma nama) + nama file
      di-random otomatis oleh Laravel, tidak bisa ditebak/dipanggil langsung.
- [x] Output di Blade auto-escape (`{{ }}`) mencegah XSS.

## Wajib kamu lakukan di server produksi

1. **`.env` produksi**
   - `APP_DEBUG=false` (WAJIB — kalau true, error page bisa membocorkan
     struktur project & kredensial database ke publik)
   - `APP_ENV=production`
   - Generate `APP_KEY` baru khusus produksi (`php artisan key:generate`)
   - Jangan pernah commit `.env` ke Git publik

2. **Pasang header keamanan** — sudah disediakan di
   `app/Http/Middleware/SecurityHeaders.php`, daftarkan sebagai middleware
   global di `bootstrap/app.php`:
   ```php
   $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
   ```

3. **Cegah eksekusi PHP di folder upload** — pakai salah satu:
   - Apache: taruh `server-hardening/storage.htaccess` sebagai `.htaccess`
     di dalam `storage/app/public/` di server produksi
   - Nginx: tambahkan `server-hardening/nginx-snippet.conf` ke config kamu

4. **Password kuat + 2FA untuk admin/staff**
   - Wajibkan password panjang & unik (bukan dipakai ulang dari akun lain)
   - Pertimbangkan pasang Laravel Fortify untuk two-factor authentication
     di akun admin/editor — ini yang paling sering jadi celah masuk (bukan
     bug kode, tapi password admin ketebak/bocor/phishing)

5. **HTTPS wajib** — pasang SSL (gratis lewat Let's Encrypt), redirect semua
   HTTP ke HTTPS, set `SESSION_SECURE_COOKIE=true` di `.env`

6. **Update dependency rutin**
   ```bash
   composer audit          # cek celah keamanan diketahui di package yang dipakai
   composer update         # update Laravel & package lain ke versi aman terbaru
   ```
   Jadwalkan ini rutin (misal tiap bulan), bukan sekali di awal saja — mayoritas
   web kena hack karena dependency lawas yang celahnya sudah publik diketahui.

7. **Matikan directory listing** di web server (sudah termasuk di snippet
   Nginx/Apache di atas).

8. **Backup rutin** — database + folder `storage/app/public/` — supaya kalau
   suatu saat kena serang, kamu bisa pulihkan cepat tanpa nego sama pelaku.

9. **Batasi siapa yang punya akses `/admin`** — sistem role admin/editor yang
   sudah dibuat artinya HANYA akun yang kamu naikkan rolenya yang bisa masuk.
   Jangan pernah share password admin lewat chat/email biasa.

10. **Pantau perubahan mencurigakan** — kalau mau, pasang uptime monitoring
    (mis. UptimeRobot gratis) yang cek apakah konten homepage berubah tiba-tiba
    (indikasi deface), atau cron sederhana yang bandingkan hash file di
    `public/` secara berkala.

## Kalau nanti buka upload untuk publik (bukan cuma admin)
Saat ini upload dokumen HANYA bisa dilakukan admin/editor yang sudah login
(bukan publik) — ini paling aman. Kalau ke depan kamu ingin publik juga bisa
upload, kabari saya dulu: perlu tambahan lapisan (scan antivirus/ClamAV,
quota per user, moderasi sebelum tampil publik) sebelum dibuka.
