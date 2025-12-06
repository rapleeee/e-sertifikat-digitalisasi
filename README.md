# Certisat – Sistem Manajemen Sertifikat Siswa

Certisat adalah aplikasi berbasis Laravel untuk mengelola sertifikat siswa di lingkungan sekolah.

Fokus utamanya:

- Kelola data siswa (NISN, NIS, nama, jenis kelamin, kelas, jurusan, angkatan, status).
- Kelola sertifikat (per siswa dan multi siswa).
- Upload massal foto sertifikat berdasarkan NIS di nama file.
- Pencarian sertifikat publik dengan tampilan kartu + detail.
- Verifikasi sertifikat via QR code dan halaman kartu sertifikat.
- Fitur laporan (ticketing) antara pengguna dan admin.
- Dashboard analitik dan manajemen pengguna dengan beberapa role.

---

## Fitur Utama

- **Manajemen Siswa**
  - CRUD siswa.
  - Pencarian berdasarkan nama/NIS.
  - Filter: jenis kelamin, kelas, jurusan, angkatan, status.
  - Bulk hapus siswa (beserta sertifikatnya).
  - Bulk naik kelas tanpa mengubah jurusan.
  - Import data siswa dari Excel dengan template, preview, dan penandaan error per baris.

- **Manajemen Sertifikat**
  - Tambah sertifikat per siswa (mode “Per Siswa”).
  - Tambah sertifikat multi siswa (mode “Multi Siswa” – satu judul & tanggal ke banyak siswa).
  - Upload massal foto sertifikat:
    - Nama file = NIS (misal `123456789.jpg`).
    - Sistem otomatis mencari siswa dan membuat sertifikat baru.
  - Halaman detail sertifikat dengan:
    - Informasi siswa, jenis, judul, tanggal.
    - Preview foto, tombol lihat/unduh foto.
    - Tombol cepat untuk kartu & cetak sertifikat.

- **Pencarian Sertifikat Publik**
  - URL: `/pencarian-sertifikat`.
  - Pencarian berdasarkan:
    - Nama siswa.
    - NIS.
  - Jika banyak siswa dengan nama mirip:
    - Ditampilkan dulu daftar siswa (nama, NIS, kelas, jurusan, jumlah sertifikat).
    - User memilih siswa → muncul daftar sertifikat milik siswa tersebut.
  - Di tampilan sertifikat per siswa:
    - Daftar sertifikat dalam bentuk kartu.
    - Filter client-side berdasarkan judul / jenis (misal “web programming”).
  - Modal detail sertifikat berisi:
    - Informasi lengkap sertifikat.
    - Tombol “Kartu & QR / Cetak”.
    - Tombol “Unduh gambar sertifikat” (jika foto tersedia).

- **QR Code & Kartu Sertifikat**
  - Setiap sertifikat memiliki:
    - Halaman kartu: `GET /sertifikat/{id}/kartu` (`route('sertifikat.card')`).
    - Kartu menampilkan:
      - Data siswa & sertifikat.
      - QR code yang mengarah ke `route('sertifikat.card', $sertifikat)`.
      - Tombol cetak / simpan PDF (via print browser).
  - Di halaman detail admin (`/sertifikat/{id}`):
    - Bila ada foto sertifikat, QR kecil di-overlay di pojok foto untuk verifikasi ketika dicetak.

- **Laporan (Ticketing)**
  - Public form `/laporan`:
    - Nama, email, NIS (opsional), subjek, pesan.
    - Email digunakan sebagai tujuan balasan/konfirmasi.
  - Admin:
    - `/laporan-admin` – daftar laporan dengan status open/closed.
    - `/laporan-admin/{laporan}` – tampilan chat-style antara user dan admin.
    - Admin bisa membalas dan mengubah status laporan.

- **Dashboard**
  - Ringkasan:
    - Total sertifikat.
    - Total siswa.
    - Sertifikat bulan ini.
    - Admin aktif.
  - Grafik sederhana:
    - Sertifikat 12 bulan terakhir.
    - Distribusi sertifikat per jurusan.
  - Daftar sertifikat terbaru dan siswa dengan sertifikat.

- **Manajemen User & Role**
  - Role yang tersedia:
    - `super_admin`, `admin`, `guru`, `staf`, `yayasan`, `perusahaan`.
  - Halaman Manajemen User (`/users`, gate `manage-users`):
    - Tambah pengguna baru (nama, email, role, password).
    - Ubah role pengguna yang sudah ada.
  - Hak akses (tingkat tinggi):
    - `super_admin` & `admin`:
      - Bisa mengelola user (manajemen role).
      - Mengelola data siswa, sertifikat, laporan, dsb.
    - `guru` & `staf`:
      - Umumnya dipakai untuk kelola laporan dan fitur administratif lain (bisa disesuaikan).
    - `yayasan`:
      - Akses bersifat pantau (read-only, tanpa edit/hapus) – dapat dikembangkan lebih lanjut sesuai kebutuhan.
    - `perusahaan`:
      - Bisa dipakai untuk role eksternal (misal mitra industri) jika diperlukan di masa depan.

---

## Persyaratan

- PHP 8.1+ (mengikuti versi Laravel yang digunakan).
- Composer.
- Node.js & npm.
- Database: MySQL/MariaDB (atau yang sesuai dengan konfigurasi `.env`).

---

## Instalasi & Setup

1. **Clone repo & install dependency**

   ```bash
   git clone <repo-url> e-certifikat
   cd e-certifikat
   composer install
   npm install
   ```

2. **Konfigurasi environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Atur di `.env`:

   - `APP_NAME` – misal `Certisat`.
   - `APP_URL` – misal `http://localhost:8000`.
   - `DB_*` – koneksi database.
   - `MAIL_*` – konfigurasi SMTP (Mailtrap/Gmail) jika ingin mengaktifkan notifikasi email laporan.

3. **Migrasi & seeding**

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

   Seeder default (`DatabaseSeeder`) akan membuat user admin:

   - Email: `test@keren.com`
   - Password: `password`
   - Role: `admin`

   Selain itu, ada migrasi tambahan yang memperluas enum `role` pada tabel `users` menjadi:

   - `super_admin`, `admin`, `guru`, `staf`, `yayasan`, `perusahaan`.

4. **Jalankan aplikasi**

   Untuk development:

   ```bash
   npm run dev
   php artisan serve
   ```

   Akses aplikasi di `http://localhost:8000`.

---

## Alur Penggunaan Singkat

- **Login sebagai admin/super_admin**
  - Gunakan akun seeder atau akun yang sudah dibuat di Manajemen User.
  - Setelah login, masuk ke `/dashboard`.

- **Kelola siswa**
  - `/siswa`:
    - Tambah siswa.
    - Edit/hapus siswa.
    - Bulk hapus / bulk naik kelas.
    - Import Excel → preview → konfirmasi.

- **Kelola sertifikat**
  - `/sertifikat/create`:
    - Tab “Per Siswa” → sertifikat individual.
    - Tab “Multi Siswa” → sertifikat yang sama ke banyak siswa (dengan checkbox).
  - `/sertifikat/upload`:
    - Upload banyak foto sertifikat sekaligus (nama file = NIS).
  - Detail sertifikat:
    - Lihat data, lampiran, tombol kartu & cetak, overlay QR di foto.

- **Pencarian & verifikasi publik**
  - `/pencarian-sertifikat`:
    - Cari by nama atau NIS.
    - Pilih siswa (jika nama pasaran).
    - Filter sertifikat milik siswa itu berdasarkan judul/jenis.
    - Buka modal → tombol “Kartu & QR / Cetak” dan unduh foto.
  - Kartu sertifikat (`/sertifikat/{id}/kartu`):
    - Dapat diprint atau discan QR-nya untuk verifikasi.

- **Laporan**
  - Public: `/laporan`.
  - Admin: `/laporan-admin` dan `/laporan-admin/{laporan}`.

---

## Catatan Pengembangan

- Aplikasi ini menggunakan:
  - Laravel (backend).
  - Tailwind CSS (UI).
  - Alpine.js (interaksi ringan).
  - SweetAlert2 (konfirmasi & notifikasi).
- Hampir semua aksi penting:
  - Mengembalikan flash `success` / `error` yang ditampilkan di view (banner atau dialog).
  - Dibuat supaya pengalaman pengguna jelas (tidak ada aksi besar tanpa feedback).

Silakan sesuaikan README ini jika ada penambahan fitur baru atau perubahan alur kerja di aplikasi.***
