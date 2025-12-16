# NutriCalc - Sistem Perhitungan Gizi & Perencana Makan

Website ini adalah sistem informasi gizi yang dibangun menggunakan PHP Procedural, MySQL, dan Bootstrap 5.

## Fitur Utama
- **Katalog Makanan**: Pencarian dan filtering makanan (kalori, kategori).
- **Kalkulator Gizi**: Menghitung BMR, TDEE, dan kebutuhan makronutrisi (Protein/Karbo/Lemak).
- **Meal Planner**: Membuat rencana makan harian dan menyimpannya.
- **Rekomendasi**: Sistem rekomendasi makanan berbasis konten (TF-IDF).
- **Admin Panel**: Manajemen data makanan, pengguna, dan laporan.

## Persyaratan Sistem
- XAMPP (PHP 7.4 atau lebih baru)
- MySQL / MariaDB
- Composer (Opsional, untuk fitur PDF dan Email)

## Cara Instalasi

1. **Copy File**
   Salin seluruh folder `Gizi_seimbang` ke dalam folder `htdocs` di instalasi XAMPP Anda (biasanya `C:\xampp\htdocs\`).

2. **Setup Database**
   - Buka phpMyAdmin (`http://localhost/phpmyadmin`).
   - Buat database baru dengan nama `nutricalc`.
   - Import file `sql/schema.sql` ke dalam database tersebut.

3. **Konfigurasi**
   - Buka file `inc/config.php`.
   - Pastikan setting database sesuai (default user: `root`, password: kosong).
   - Sesuaikan `BASE_URL` jika nama folder Anda berbeda.

4. **Instalasi Library (Opsional)**
   Jika ingin menggunakan fitur Export PDF dan Email, jalankan perintah berikut di terminal dalam folder project:
   ```bash
   composer require dompdf/dompdf phpmailer/phpmailer
   ```
   *Catatan: Tanpa composer, fitur PDF dan Email tidak akan berfungsi, namun fitur utama lainnya tetap berjalan.*

5. **Jalankan**
   Buka browser dan akses: `http://localhost/Gizi_seimbang/`

## Akun Default

**Admin:**
- Username: `admin`
- Password: `password`

**User (Contoh):**
- Anda bisa mendaftar akun baru melalui halaman Register.

## Struktur Folder
- `/assets`: CSS, JS, Gambar
- `/inc`: File konfigurasi dan fungsi inti
- `/pages`: Halaman user (Home, Login, Katalog, dll)
- `/admin`: Halaman admin
- `/sql`: File database

## Catatan Pengembang
- Sistem menggunakan `password_hash()` untuk keamanan password.
- Semua query database menggunakan Prepared Statements (PDO) untuk mencegah SQL Injection.
- Fitur rekomendasi menggunakan implementasi TF-IDF sederhana.

