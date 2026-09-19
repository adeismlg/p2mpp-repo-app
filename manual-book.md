# Manual Book Aplikasi P2MPP Polinema

## 1. Tentang Aplikasi

Aplikasi P2MPP Politeknik Negeri Malang adalah portal informasi dan repository digital untuk Pusat Penjaminan Mutu dan Pengembangan Pembelajaran. Aplikasi ini menyediakan:

- Beranda dengan slider informasi yang dapat dikelola dari panel admin.
- Portal publik bilingual Bahasa Indonesia dan English.
- Repository dokumen dengan kategori, pencarian, detail, dan download.
- Publikasi berita dan detail berita.
- Informasi program pelatihan.
- Halaman konten statis seperti visi, misi, dan kebijakan mutu.
- Menu navigasi dan submenu yang dapat dikonfigurasi.
- Manajemen akun dan hak akses admin/staff.
- Two-Factor Authentication (2FA) untuk keamanan akun staff.

Manual ini ditujukan untuk:

1. Pengunjung website.
2. Editor atau staff pengelola konten.
3. Administrator aplikasi.
4. Operator/developer yang menjalankan aplikasi di lingkungan lokal.

---

## 2. Persyaratan Sistem

Untuk menjalankan aplikasi, siapkan:

- PHP 8.3 atau lebih baru.
- Composer.
- Node.js dan npm.
- SQLite atau database yang dikonfigurasi pada `.env`.
- Ekstensi PHP yang dibutuhkan Laravel, terutama SQLite, PDO, Mbstring, OpenSSL, XML, Ctype, JSON, dan BCMath.

Periksa instalasi:

```powershell
php -v
composer -V
node -v
npm -v
```

---

## 3. Instalasi dan Menjalankan Aplikasi

### 3.1 Instalasi dependency

Dari folder utama aplikasi:

```powershell
composer install
npm install
```

### 3.2 Konfigurasi environment

Buat file `.env` dari template:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Konfigurasi minimal database SQLite:

```dotenv
DB_CONNECTION=sqlite
```

Jika database SQLite belum ada:

```powershell
New-Item database\database.sqlite -ItemType File
```

### 3.3 Migrasi database

Jalankan seluruh migration:

```powershell
php artisan migrate
```

Jika ingin mengisi data awal dan data demo:

```powershell
php artisan db:seed
```

Seeder utama mengisi struktur menu, konten P2MPP, data repository, data demo, dan contoh slider.

> **Peringatan:** `php artisan migrate:fresh --seed` menghapus seluruh tabel dan data. Gunakan hanya pada development atau setelah memastikan backup tersedia.

### 3.4 Menyiapkan storage file publik

Untuk menampilkan file dokumen, gambar berita, dan gambar slider:

```powershell
php artisan storage:link
```

### 3.5 Menjalankan aplikasi

Cara manual:

```powershell
php artisan serve
npm run dev
```

Buka:

```text
http://127.0.0.1:8000
```

Alternatif dengan satu perintah:

```powershell
composer run dev
```

Untuk menjalankan asset tanpa Vite development server:

```powershell
npm run build
```

---

## 4. Struktur Halaman Publik

| Halaman | URL | Kegunaan |
|---|---|---|
| Beranda | `/` | Menampilkan slider, dokumen terbaru, pelatihan, dan berita terbaru |
| Berita | `/berita` | Melihat daftar berita yang sudah diterbitkan |
| Detail berita | `/berita/{slug}` | Membaca isi lengkap berita |
| Pelatihan | `/pelatihan` | Melihat daftar program pelatihan |
| Detail pelatihan | `/pelatihan/{slug}` | Melihat informasi lengkap program |
| Repository dokumen | `/dokumen` | Mencari dan memfilter dokumen publik |
| Detail dokumen | `/dokumen/{id}` | Melihat detail dokumen |
| Download dokumen | `/dokumen/{id}/download` | Mengunduh dokumen publik |
| Halaman konten | `/halaman/{slug}` | Membaca halaman statis |
| Sitemap | `/sitemap.xml` | Sitemap untuk mesin pencari |

### 4.1 Mengubah bahasa

Bahasa dapat diubah melalui:

- `/language/id` untuk Bahasa Indonesia.
- `/language/en` untuk English.

Bahasa disimpan pada session browser. Jika teks English belum tersedia, aplikasi menggunakan teks Bahasa Indonesia sebagai fallback.

### 4.2 Menggunakan repository dokumen

1. Buka menu **Dokumen**.
2. Gunakan kolom pencarian bila ingin mencari judul atau informasi tertentu.
3. Pilih kategori apabila tersedia.
4. Buka detail dokumen untuk membaca informasi lengkap.
5. Tekan tombol download untuk mengunduh file.

Hanya dokumen yang ditandai **Publik** oleh staff yang tampil di halaman publik.

---

## 5. Login, Registrasi, dan Profil

### 5.1 Login

1. Buka halaman **Masuk**.
2. Isi email.
3. Isi password.
4. Centang **Remember me** bila ingin mempertahankan sesi login.
5. Tekan **Log in**.

User dengan role `admin` atau `editor` akan diarahkan ke dashboard admin. User biasa akan diarahkan ke beranda publik.

### 5.2 Registrasi

1. Buka halaman registrasi.
2. Isi nama, email, password, dan konfirmasi password.
3. Tekan **Daftar**.

User hasil registrasi publik memiliki role user biasa dan tidak dapat masuk ke panel admin sampai role-nya diubah oleh administrator.

### 5.3 Lupa dan reset password

1. Pada halaman login, pilih **Forgot your password?**.
2. Masukkan email akun.
3. Ikuti tautan reset yang dikirim melalui email.
4. Buat password baru dan konfirmasi password.

Pastikan konfigurasi email aplikasi sudah benar agar email reset dapat diterima.

### 5.4 Mengelola profil

Pada halaman `/profile`, user dapat:

- Mengubah nama.
- Mengubah email.
- Mengubah password.
- Mengaktifkan atau mengelola 2FA untuk staff.
- Menghapus akun.

### 5.5 Mengaktifkan 2FA

2FA tersedia untuk admin/editor. Untuk mengaktifkan:

1. Login sebagai staff.
2. Buka menu profil.
3. Buka bagian keamanan/Two-Factor Authentication.
4. Ikuti proses pendaftaran authenticator.
5. Simpan recovery codes di tempat aman.

Recovery codes hanya digunakan ketika authenticator utama tidak tersedia. Jangan membagikan password, kode authenticator, atau recovery codes kepada orang lain.

---

## 6. Role dan Hak Akses

### 6.1 Admin

Admin memiliki akses penuh untuk:

- Dashboard.
- Repository dokumen.
- Berita.
- Program pelatihan.
- Halaman konten.
- Slider beranda.
- Kategori dokumen.
- Menu dan submenu.
- Manajemen staff dan role user.

### 6.2 Editor/Staff

Editor/Staff dapat mengelola:

- Repository dokumen.
- Berita.
- Program pelatihan.
- Halaman konten.
- Slider beranda.

Editor/Staff tidak dapat mengelola:

- Kategori dokumen.
- Menu navigasi.
- Role dan akun staff.

### 6.3 User biasa

User biasa dapat menggunakan fitur publik dan profil, tetapi tidak dapat masuk ke panel `/admin`.

---

## 7. Menggunakan Panel Admin

Panel admin tersedia di:

```text
/admin
```

Panel hanya dapat dibuka oleh user dengan role `admin` atau `editor`.

Menu panel:

- **Dashboard**: ringkasan area pengelolaan.
- **Repository Dokumen**: mengunggah dan mengelola dokumen.
- **Berita**: membuat dan menerbitkan berita.
- **Pelatihan**: mengelola program pelatihan.
- **Halaman Konten**: mengelola halaman statis.
- **Slider Beranda**: mengelola slider pada halaman utama.
- **Kategori Dokumen**: khusus admin.
- **Menu Navigasi**: khusus admin.
- **Manajemen Staff**: khusus admin.

Pada setiap daftar data, gunakan tombol:

- **Tambah** untuk membuat data baru.
- **Edit** untuk memperbarui data.
- **Hapus** untuk menghapus data.

Penghapusan biasanya menampilkan dialog konfirmasi. Pastikan data yang dipilih sudah benar sebelum mengonfirmasi.

---

## 8. Mengelola Repository Dokumen

### 8.1 Membuat kategori

Fitur ini hanya tersedia untuk admin.

1. Buka **Admin → Kategori Dokumen**.
2. Tekan **+ Kategori Baru**.
3. Isi nama kategori dan informasi bilingual bila tersedia.
4. Simpan.

Kategori digunakan untuk mengelompokkan dokumen agar mudah ditemukan pengunjung.

### 8.2 Mengunggah dokumen

1. Buka **Admin → Repository Dokumen**.
2. Tekan **+ Unggah Dokumen**.
3. Isi judul dan deskripsi.
4. Pilih kategori dokumen.
5. Pilih file yang akan diunggah.
6. Atur status publik atau tersembunyi.
7. Simpan.

Gunakan nama file yang jelas dan pastikan dokumen tidak berisi informasi rahasia sebelum memilih status **Publik**.

### 8.3 Memperbarui atau menghapus dokumen

1. Cari dokumen pada daftar.
2. Tekan **Edit** untuk memperbarui metadata, kategori, file, atau status publik.
3. Tekan **Hapus** hanya jika dokumen memang sudah tidak diperlukan.

Perhatikan bahwa menghapus dokumen dapat membuat tautan atau referensi lama tidak lagi berfungsi.

---

## 9. Mengelola Berita

### 9.1 Membuat berita

1. Buka **Admin → Berita**.
2. Tekan **+ Berita Baru**.
3. Isi judul Bahasa Indonesia.
4. Isi judul English bila diperlukan.
5. Isi ringkasan dan isi berita.
6. Tambahkan gambar sampul jika diperlukan.
7. Centang **Terbitkan sekarang** untuk menampilkan berita di publik.
8. Simpan.

Jika tidak dicentang, berita disimpan sebagai **Draf** dan tidak muncul pada halaman berita publik.

### 9.2 Menulis konten bilingual

Isi Bahasa Indonesia adalah konten utama. Isi English bersifat opsional. Jika versi English kosong, halaman akan menggunakan versi Bahasa Indonesia ketika pengunjung memilih bahasa English.

Sebelum menerbitkan:

- Periksa judul dan ringkasan.
- Periksa tautan.
- Pastikan gambar sesuai dan tidak terlalu besar.
- Pastikan isi berita sudah final.

---

## 10. Mengelola Program Pelatihan

1. Buka **Admin → Pelatihan**.
2. Tekan **+ Pelatihan Baru**.
3. Isi nama, deskripsi, informasi jadwal, dan field lain yang tersedia.
4. Isi terjemahan English jika diperlukan.
5. Simpan.

Gunakan menu **Edit** untuk memperbarui program yang sudah ada. Program akan tampil pada halaman publik sesuai data yang tersimpan.

---

## 11. Mengelola Halaman Konten

Halaman konten digunakan untuk informasi statis seperti:

- Visi dan misi.
- Kebijakan mutu.
- Informasi organisasi.
- Informasi layanan.

Langkah membuat halaman:

1. Buka **Admin → Halaman Konten**.
2. Tekan **+ Halaman Baru**.
3. Isi judul dan isi halaman.
4. Isi versi English bila dibutuhkan.
5. Tentukan status **Terbit** atau **Draf**.
6. Simpan.

Halaman yang masih berstatus draf tidak boleh dianggap sebagai halaman publik final.

---

## 12. Mengelola Slider Beranda

Slider pada beranda sekarang dikelola dari database dan tidak perlu mengubah kode program.

### 12.1 Membuat slider

1. Buka **Admin → Slider Beranda**.
2. Tekan **+ Slider Baru**.
3. Isi judul Bahasa Indonesia.
4. Isi judul English bila diperlukan.
5. Isi deskripsi Bahasa Indonesia dan English bila diperlukan.
6. Upload gambar slider.
7. Isi URL tombol bila slider memiliki tautan.
8. Isi label tombol.
9. Atur **Urutan tampil**.
10. Centang **Tampilkan di halaman beranda**.
11. Simpan.

Format gambar yang didukung:

- JPG/JPEG.
- PNG.
- WebP.

Ukuran maksimum gambar adalah 4 MB.

### 12.2 Mengatur urutan slider

Nilai urutan yang lebih kecil ditampilkan lebih dahulu. Contoh:

| Slider | Urutan |
|---|---:|
| Kegiatan P2MPP | 1 |
| Laporan Kepuasan Mahasiswa | 2 |
| Pengumuman Baru | 3 |

Gunakan nomor urut yang berbeda agar urutan mudah dipahami.

### 12.3 Menonaktifkan slider sementara

Untuk menyembunyikan slider tanpa menghapusnya:

1. Buka **Admin → Slider Beranda**.
2. Tekan **Edit** pada slider.
3. Hilangkan centang **Tampilkan di halaman beranda**.
4. Simpan.

Slider berstatus nonaktif tidak ditampilkan pada beranda.

### 12.4 Rekomendasi isi slider

- Gunakan gambar yang tajam dan memiliki komposisi sederhana.
- Gunakan judul yang singkat.
- Gunakan deskripsi yang langsung menjelaskan isi.
- Hindari teks terlalu panjang pada gambar.
- Gunakan link yang valid dan dapat diakses publik.
- Periksa tampilan beranda setelah menyimpan perubahan.

---

## 13. Mengelola Menu Navigasi

Fitur ini hanya tersedia untuk admin.

### 13.1 Membuat menu utama

1. Buka **Admin → Menu Navigasi**.
2. Tekan **+ Item Menu**.
3. Kosongkan **Induk Menu** untuk membuat menu utama.
4. Isi label Indonesia dan English.
5. Pilih tipe tautan:
   - **Halaman bawaan** untuk route aplikasi.
   - **Halaman konten** untuk halaman yang dibuat melalui admin.
   - **URL eksternal** untuk website atau sistem lain.
6. Aktifkan **Buka di tab baru** bila diperlukan.
7. Simpan.

### 13.2 Membuat submenu

1. Buka daftar menu.
2. Tekan **+ Sub-menu** pada menu utama.
3. Isi data submenu.
4. Simpan.

### 13.3 Mengubah urutan menu

Gunakan tombol panah naik atau turun pada menu utama. Pastikan struktur menu tetap mudah dipahami dan tidak terlalu panjang.

---

## 14. Manajemen Staff

Fitur ini hanya tersedia untuk admin.

1. Buka **Admin → Manajemen Staff**.
2. Cari user berdasarkan nama atau email.
3. Pilih role:
   - **Admin**.
   - **Editor/Staff**.
   - **User Biasa**.
4. Tekan **Simpan**.

Jangan memberikan role admin kecuali kepada user yang memang bertanggung jawab atas seluruh isi dan konfigurasi aplikasi. Admin dapat mengubah role staff lain, struktur menu, kategori, dan konten aplikasi.

Role akun yang sedang digunakan tidak dapat diubah dari baris akun tersebut. Gunakan akun admin lain bila perlu mengubah role administrator.

---

## 15. Prosedur Publikasi Konten yang Benar

Gunakan alur berikut untuk setiap konten:

1. Buat atau edit data.
2. Isi versi Bahasa Indonesia.
3. Isi versi English jika konten ditujukan untuk pengunjung bilingual.
4. Periksa ejaan, tautan, gambar, dan format.
5. Simpan sebagai draf jika masih menunggu pemeriksaan.
6. Terbitkan hanya setelah konten disetujui.
7. Buka halaman publik menggunakan tab baru.
8. Periksa hasil pada desktop dan perangkat mobile.
9. Pastikan status dan urutan konten sudah benar.

---

## 16. Keamanan dan Praktik Penggunaan

- Jangan membagikan password dan recovery codes.
- Gunakan password yang panjang dan unik.
- Aktifkan 2FA untuk akun admin/editor.
- Logout setelah selesai menggunakan panel pada komputer bersama.
- Jangan mengunggah file rahasia sebagai dokumen publik.
- Periksa alamat URL sebelum mengatur link eksternal.
- Gunakan role paling rendah yang cukup untuk pekerjaan user.
- Jangan menghapus data sebelum memastikan tidak dipakai halaman lain.
- Lakukan backup database dan file upload secara berkala.
- Jangan menjalankan `migrate:fresh` pada server produksi.
- Jangan menyimpan file `.env` di repository publik.

---

## 17. Troubleshooting

### 17.1 Halaman admin menampilkan `no such table`

Jalankan migration:

```powershell
php artisan migrate
```

Periksa status migration:

```powershell
php artisan migrate:status
```

### 17.2 Gambar atau file tidak tampil

Pastikan storage link sudah dibuat:

```powershell
php artisan storage:link
```

Periksa apakah file berada pada `storage/app/public` dan konfigurasi disk `public` sesuai.

### 17.3 Perubahan tampilan belum terlihat

Untuk development, pastikan Vite berjalan:

```powershell
npm run dev
```

Atau buat asset produksi:

```powershell
npm run build
```

Bersihkan cache aplikasi bila diperlukan:

```powershell
php artisan optimize:clear
```

### 17.4 User tidak dapat membuka panel admin

Pastikan:

1. User sudah login.
2. Role user adalah `admin` atau `editor`.
3. User membuka URL `/admin`.
4. Administrator sudah memperbarui role melalui **Manajemen Staff**.

### 17.5 Email reset password tidak diterima

Periksa konfigurasi mail pada `.env`, kredensial SMTP, alamat pengirim, dan log aplikasi. Jangan menampilkan kredensial email di dalam kode atau dokumentasi publik.

---

## 18. Perintah Pemeliharaan yang Umum

Melihat daftar route:

```powershell
php artisan route:list
```

Melihat status migration:

```powershell
php artisan migrate:status
```

Menjalankan migration:

```powershell
php artisan migrate
```

Mengisi contoh slider lama:

```powershell
php artisan db:seed --class=SliderSeeder
```

Membersihkan cache:

```powershell
php artisan optimize:clear
```

Menjalankan test:

```powershell
php artisan test --compact
```

---

## 19. Checklist Administrator

### Sebelum aplikasi digunakan

- [ ] `.env` sudah dikonfigurasi.
- [ ] `APP_DEBUG` dimatikan pada production.
- [ ] Database sudah dimigrasikan.
- [ ] `storage:link` sudah dijalankan.
- [ ] Akun admin sudah tersedia.
- [ ] 2FA sudah diaktifkan untuk akun admin.
- [ ] Menu publik sudah diperiksa.
- [ ] Slider aktif sudah memiliki gambar yang benar.
- [ ] Konten publik sudah diperiksa di desktop dan mobile.
- [ ] Backup database dan file upload sudah disiapkan.

### Sebelum menerbitkan konten

- [ ] Judul sudah benar.
- [ ] Deskripsi sudah benar.
- [ ] Versi English sudah diisi bila diperlukan.
- [ ] Gambar sesuai dan tidak melanggar hak penggunaan.
- [ ] Link sudah diuji.
- [ ] Status publikasi sudah benar.
- [ ] Halaman publik sudah dibuka untuk verifikasi.

---

## 20. Ringkasan Alur Penggunaan

### Pengunjung

1. Buka beranda.
2. Pilih bahasa.
3. Jelajahi berita, pelatihan, dan repository dokumen.
4. Buka atau download dokumen publik.

### Editor/Staff

1. Login.
2. Buka `/admin`.
3. Kelola dokumen, berita, pelatihan, halaman, atau slider.
4. Periksa hasil pada halaman publik.
5. Logout setelah selesai.

### Admin

1. Login dan buka `/admin`.
2. Kelola seluruh konten.
3. Atur kategori dokumen.
4. Atur menu dan submenu.
5. Atur role staff.
6. Periksa keamanan akun dan backup.

---

## 21. Catatan Versi Manual

- Nama aplikasi: P2MPP Polinema.
- Bahasa manual: Bahasa Indonesia.
- Format: Markdown.
- Dokumen ini dibuat berdasarkan fitur aplikasi yang tersedia pada saat penyusunan.
- Jika fitur aplikasi berubah, manual ini perlu diperbarui agar tetap sesuai dengan tampilan dan alur terbaru.
