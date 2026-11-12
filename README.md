# PyLMS - Platform Belajar Python Interaktif (Mobile-First & cPanel Ready)

PyLMS adalah Learning Management System (LMS) interaktif untuk siswa belajar pemrograman Python (gaya W3Schools) dari dasar hingga mahir tingkat dasar.

Dirancang khusus dengan:
- **Zero Symbolic Links**: Kompatibel 100% dengan **shared hosting cPanel**.
- **Client-Side WebAssembly (Pyodide CPython 3)**: Seluruh kode siswa dieksekusi di browser HP/laptop siswa, bebas beban CPU server hosting, dan bebas risiko keamanan (RCE).
- **Mobile Touch Shortcut Toolbar**: Baris tombol khusus di atas keyboard HP (`Tab 4-spasi`, `:`, `( )`, `[ ]`, `{ }`, `" "`, `' '`, `=`, `==`, `+`, `-`, `*`, `/`, `%`, `_`, `#`, `Undo`, `Redo`, `▶ Run`).
- **Dual-View Leaderboard**: Peringkat kelas (Rombel) dan Peringkat Global sekolah.
- **Manajemen Guru & Buku Nilai**: Import CSV siswa (auto-grouping Rombel), gradebook interaktif, dan ekspor nilai ke Excel/CSV.
- **Kurikulum 8 Part Lengkap**:
  1. Part 1: Pengenalan Python & Tipe Data
  2. Part 2: Operator, Input & Format Teks
  3. Part 3: Percabangan Logika
  4. Part 4: Perulangan (Loops)
  5. Part 5: Struktur Data (List & Dictionary)
  6. Part 6: Fungsi & Modularitas
  7. Part 7: File I/O & Penanganan Error
  8. Part 8: Proyek Akhir (Capstone: Game Teks / Kasir Toko / Buku Nilai CLI)

---

## Panduan Instalasi di Shared Hosting cPanel

### Langkah 1: Unggah Berkas ke cPanel
1. Buka **cPanel** &rarr; **File Manager**.
2. Masuk ke folder `public_html` (atau subdomain Anda).
3. Unggah seluruh isi repositori ini ke folder tersebut.

### Langkah 2: Buat Database MySQL
1. Di cPanel, buka menu **MySQL Databases** atau **MySQL Database Wizard**.
2. Buat database baru (misal: `u1234_pylms`).
3. Buat user database baru dan buat password yang aman.
4. Berikan hak akses **ALL PRIVILEGES** dari user ke database tersebut.

### Langkah 3: Import `database.sql` via phpMyAdmin
1. Kembali ke menu utama cPanel, klik **phpMyAdmin**.
2. Pilih database yang baru saja dibuat di bilah kiri.
3. Klik tab **Import** di menu atas.
4. Pilih file `database.sql` dari komputer Anda, lalu klik **Import / Go**.
5. Semua tabel dan 8 modul kurikulum akan otomatis terpasang.

### Langkah 4: Sesuaikan `config.php`
Buka file `config.php` di cPanel File Manager (atau editor teks), lalu sesuaikan bagian kredensial database:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'nama_database_anda');
define('DB_USER', 'user_database_anda');
define('DB_PASS', 'password_database_anda');
```

---

## Akun Default & Penggunaan

### Akun Guru Default:
- **URL Login:** `domainanda.com/login.php`
- **Username:** `guru`
- **Password:** `password123`

### Cara Menambahkan Siswa (Import CSV):
1. Login sebagai Guru.
2. Buka menu **Import CSV** (`teacher/import-csv.php`).
3. Unduh contoh template: `teacher/sample_students.csv`.
4. Format kolom file CSV:
   ```csv
   nis,nama,rombel,rayon,username,password
   12301001,Ahmad Fadhilah,PPLG X-1,Ciawi 1,ahmad_f,siswa123
   ```
5. Unggah file CSV Anda. Sistem akan secara otomatis:
   - Membuat akun siswa dengan password ter-hash Bcrypt.
   - Mengelompokkan siswa ke dalam Rombel dan Rayon masing-masing.

### Mengakses Buku Nilai (Gradebook):
- Buka menu **Buku Nilai** (`teacher/gradebook.php`).
- Guru dapat memfilter berdasarkan Rombel atau Rayon, melihat pencapaian part siswa, dan menekan tombol **Export Nilai (CSV / Excel)**.
- Guru juga dapat membuka kunci (unlock) Part tertentu untuk siswa secara manual jika diperlukan.
