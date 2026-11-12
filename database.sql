-- =====================================================================
-- PyLMS Database Schema & 40 Sub-Topik Kurikulum Python Harian
-- =====================================================================
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS capstone_submissions;
DROP TABLE IF EXISTS student_badges;
DROP TABLE IF EXISTS student_progress;
DROP TABLE IF EXISTS challenges;
DROP TABLE IF EXISTS quizzes;
DROP TABLE IF EXISTS topics;
DROP TABLE IF EXISTS modules;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(30) UNIQUE NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    nickname VARCHAR(50) NULL,
    role ENUM('guru', 'siswa') NOT NULL DEFAULT 'siswa',
    rombel VARCHAR(50) NULL,
    rayon VARCHAR(50) NULL,
    avatar VARCHAR(100) DEFAULT 'default.png',
    xp INT DEFAULT 0,
    current_part INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_rombel (rombel),
    INDEX idx_xp (xp DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    part_number INT NOT NULL UNIQUE,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL,
    description TEXT,
    badge_name VARCHAR(100),
    badge_icon VARCHAR(50),
    order_index INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    sub_code VARCHAR(10) NOT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL,
    content_html LONGTEXT NOT NULL,
    starter_code TEXT,
    order_index INT NOT NULL,
    xp_reward INT DEFAULT 20,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    INDEX idx_subcode (sub_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option CHAR(1) NOT NULL,
    explanation TEXT,
    xp_reward INT DEFAULT 15,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE challenges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    instructions TEXT NOT NULL,
    starter_code TEXT,
    test_cases_json TEXT NOT NULL,
    hint TEXT,
    xp_reward INT DEFAULT 50,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE student_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    topic_id INT NOT NULL,
    is_completed TINYINT(1) DEFAULT 0,
    quiz_score INT DEFAULT 0,
    challenge_completed TINYINT(1) DEFAULT 0,
    hint_used TINYINT(1) DEFAULT 0,
    last_submitted_code TEXT,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_topic (user_id, topic_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE student_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    badge_name VARCHAR(100) NOT NULL,
    badge_icon VARCHAR(50) NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_badge (user_id, badge_name),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE capstone_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    track_choice VARCHAR(100) NOT NULL,
    source_code TEXT NOT NULL,
    notes TEXT,
    teacher_score INT NULL,
    teacher_feedback TEXT NULL,
    status ENUM('pending', 'approved', 'revision') DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (nis, username, password_hash, full_name, role, rombel, rayon, xp, current_part)
VALUES (NULL, 'guru', 'password123', 'Guru Pembimbing Python', 'guru', NULL, NULL, 500, 8);

INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES (1, 'Pengenalan Python & Tipe Data', 'pengenalan-python', 'Memahami cara kerja Python, sintaks dasar print, komentar, variabel, dan tipe data primitif.', 'Python Novice', 'fa-seedling', 1);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (1, 1, '1.1', '1.1 Cara Kerja Python & Fungsi print()', '1-1-cara-kerja-print', '<h3>Selamat Datang di Pemrograman Python!</h3>
<p>Python adalah bahasa pemrograman yang mudah dipahami, sangat populer di dunia kerja, dan digunakan oleh Google, YouTube, hingga NASA.</p>
<h4>1. Fungsi <code>print()</code></h4>
<p>Untuk menampilkan teks atau angka ke layar, gunakan fungsi <code>print()</code>. Teks wajib diapit tanda kutip ganda <code>"</code> atau kutip tunggal <code>''</code>.</p>
<pre><code>print("Halo Dunia!")
print(2026)</code></pre>
<div class="p-3 my-2 bg-blue-50 border-l-4 border-blue-500 rounded text-xs text-blue-900">
<strong>Tips HP:</strong> Gunakan tombol pintasan <code>" "</code> dan <code>( )</code> di atas keyboard HP Anda untuk mempercepat pengetikan!
</div>', 'print("Halo, saya sedang belajar Python!")
print(10 + 25)', 1, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (1, 'Fungsi bawaan Python apa yang digunakan untuk menampilkan teks atau data ke layar/terminal?', 'echo()', 'print()', 'display()', 'write()', 'B', 'Fungsi resmi di Python untuk mencetak teks ke layar output adalah print().', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (1, 'Tantangan 1.1: Mencetak Kalimat Sambutan', 'Cetaklah tepat teks berikut:
Saya Siap Belajar Python', 'print("")', '[{"name": "Cek Kalimat Sambutan", "expected_output": "Saya Siap Belajar Python"}]', 'Gunakan print("Saya Siap Belajar Python")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (2, 1, '1.2', '1.2 Komentar (#) & Dokumentasi Kode', '1-2-komentar-dokumentasi', '<h3>Menulis Catatan dengan Komentar</h3>
<p>Komentar adalah baris catatan yang <strong>diabaikan oleh komputer</strong> saat program dijalankan. Sangat penting agar Anda dan rekan tim memahami alur program.</p>
<h4>Aturan Komentar</h4>
<p>Awali baris dengan tanda pagar <code>#</code>:</p>
<pre><code># Ini adalah baris komentar, tidak akan dijalankan komputer
print("Teks ini yang akan dicetak") # Komentar di akhir baris</code></pre>', '# Program perkenalan diri
# Dibuat oleh Siswa Hebat
print("Program dimulai")
# print("Baris ini dinonaktifkan")
print("Program selesai")', 2, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (2, 'Karakter apakah yang digunakan untuk membuat komentar satu baris di Python?', '//', '/* */', '#', '--', 'C', 'Di Python, komentar satu baris selalu diawali dengan karakter pagar (#).', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (2, 'Tantangan 1.2: Baris Kode Bersih dengan Komentar', 'Tuliskan satu baris komentar di baris pertama, kemudian di baris kedua cetak teks:
KODE BERHASIL DIJALANKAN', '# Tulis komentar di sini
', '[{"name": "Cek Output Teks", "expected_output": "KODE BERHASIL DIJALANKAN"}]', 'Awali baris 1 dengan tanda #, lalu di baris 2 tulis print("KODE BERHASIL DIJALANKAN")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (3, 1, '1.3', '1.3 Variabel & Aturan Penamaan', '1-3-variabel-aturan-penamaan', '<h3>Menyimpan Nilai dalam Variabel</h3>
<p>Variabel ibarat kotak yang diberi label untuk menyimpan data.</p>
<h4>Aturan Penamaan Variabel Python (snake_case):</h4>
<ul class="list-disc pl-5 space-y-1">
<li>Gunakan huruf kecil dan pisahkan kata dengan garis bawah <code>_</code> (contoh: <code>nama_lengkap</code>, <code>nilai_siswa</code>).</li>
<li><strong>TIDAK BOLEH</strong> diawali angka (contoh salah: <code>1nama</code>).</li>
<li><strong>TIDAK BOLEH</strong> menggunakan spasi atau tanda hubung minus (contoh salah: <code>nama-siswa</code>).</li>
</ul>', '# Penamaan variabel yang benar
nama_depan = "Ahmad"
nama_belakang = "Fadhil"
umur = 16

print(nama_depan, nama_belakang, "berumur", umur)', 3, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (3, 'Manakah nama variabel di bawah ini yang VALID dan mengikuti konvensi snake_case di Python?', '123_siswa', 'nama-siswa', 'nama_siswa', 'nama siswa', 'C', 'nama_siswa valid karena menggunakan huruf kecil, pemisah garis bawah (_), dan tidak diawali angka.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (3, 'Tantangan 1.3: Debugging Penamaan Variabel', 'Perbaiki kode yang salah sintaks berikut agar variabel bernama skor_maksimal memiliki nilai 100 dan dicetak:
Output yang diharapkan:
Skor: 100', '# Perbaiki baris di bawah:
# 1_skor = 100
# print("Skor:", 1_skor)
', '[{"name": "Cek Variabel Valid", "expected_output": "Skor: 100"}]', 'Ganti 1_skor menjadi skor_maksimal = 100 lalu print("Skor:", skor_maksimal)', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (4, 1, '1.4', '1.4 Tipe Data Primitif & Fungsi type()', '1-4-tipe-data-primitif', '<h3>Mengenal 4 Tipe Data Dasar</h3>
<ul class="list-disc pl-5 space-y-1.5">
<li><strong>int:</strong> Bilangan bulat, contoh: <code>jumlah = 10</code></li>
<li><strong>float:</strong> Bilangan desimal, contoh: <code>berat = 54.5</code></li>
<li><strong>str (String):</strong> Teks dalam kutip, contoh: <code>bahasa = "Python"</code></li>
<li><strong>bool (Boolean):</strong> Nilai kebenaran <code>True</code> atau <code>False</code></li>
</ul>
<p>Gunakan fungsi <code>type(variabel)</code> untuk memeriksa tipe data suatu variabel.</p>', 'bilangan_bulat = 42
desimal = 3.14
teks = "SMK Bisa"
status = True

print(type(bilangan_bulat))
print(type(desimal))
print(type(teks))
print(type(status))', 4, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (4, 'Jika x = 75.0, tipe data apakah dari variabel x tersebut?', 'int', 'float', 'str', 'bool', 'B', 'Karena memiliki angka di belakang koma desimal (.0), tipe datanya adalah float.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (4, 'Tantangan 1.4: Cetak 4 Tipe Data', 'Buat 4 baris print yang masing-masing mencetak:
Angka: 25
Desimal: 7.5
Teks: Sukses
Aktif: True', '# Buat 4 perintah print di bawah ini
', '[{"name": "Cek 4 Baris Tipe Data", "expected_output": "Angka: 25\nDesimal: 7.5\nTeks: Sukses\nAktif: True"}]', 'Cetak sesuai label dan nilainya persis di 4 baris terpisah.', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (5, 1, '1.5', '1.5 Boss Challenge: Kartu Biodata Siswa', '1-5-boss-challenge-biodata', '<h3>Soal Kenaikan Part 1: Program Biodata Mandiri</h3>
<p>Saatnya membuktikan penguasaan dasar Anda di Part 1. Anda akan menggabungkan variabel teks, angka, dan boolean dalam satu program kartu profil siswa terformat.</p>
<h4>Ketentuan:</h4>
<p>Cetak tepat 4 baris informasi biodata sesuai spesifikasi tantangan di bawah.</p>', '# Boss Challenge Part 1
nama = "Budi Santoso"
kelas = "PPLG X-1"
umur = 16
status = True

# Cetak biodata terformat sesuai instruksi', 5, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (5, 'Apa kegunaan dari tipe data boolean (bool) dalam pemrograman?', 'Menyimpan teks panjang', 'Menyimpan nilai kebenaran True atau False', 'Menyimpan daftar angka', 'Melakukan pembagian bulat', 'B', 'Boolean hanya memiliki 2 nilai: True (benar) atau False (salah), sangat penting untuk logika percabangan.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (5, 'Tantangan 1.5 (Boss): Kartu Biodata Digital', 'Buatlah program yang mencetak tepat 4 baris berikut:
=== KARTU SISWA ===
NAMA: Budi Santoso
KELAS: PPLG X-1
STATUS: AKTIF', 'print("=== KARTU SISWA ===")
', '[{"name": "Cek Format Kartu Biodata", "expected_output": "=== KARTU SISWA ===\nNAMA: Budi Santoso\nKELAS: PPLG X-1\nSTATUS: AKTIF"}]', 'Cetak baris demi baris menggunakan print() dengan teks yang persis.', 50);
INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES (2, 'Operator, Input & Format Teks', 'operator-dan-input', 'Mengolah data input pengguna, operasi aritmatika, perbandingan, logika, dan f-string.', 'Calculation Ace', 'fa-calculator', 2);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (6, 2, '2.1', '2.1 Operator Aritmatika (+, -, *, /, //, %, **)', '2-1-operator-aritmatika', '<h3>Perhitungan Matematika di Python</h3>
<ul class="list-disc pl-5 space-y-1">
<li><code>+</code> (tambah) dan <code>-</code> (kurang)</li>
<li><code>*</code> (kali) dan <code>/</code> (bagi desimal)</li>
<li><code>//</code> (pembagian bulat tanpa sisa desimal, misal: <code>7 // 2 = 3</code>)</li>
<li><code>%</code> (modulus/sisa bagi, misal: <code>7 % 2 = 1</code>)</li>
<li><code>**</code> (pangkat, misal: <code>2 ** 3 = 8</code>)</li>
</ul>', 'a = 15
b = 4

print("Penjumlahan :", a + b)
print("Pembagian   :", a / b)
print("Bagi Bulat  :", a // b)
print("Sisa Bagi % :", a % b)
print("Pangkat **  :", a ** 2)', 1, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (6, 'Berapakah hasil dari ekspresi Python: 10 // 3 ?', '3.33', '3', '1', '4', 'B', 'Operator // adalah pembagian bulat (floor division), sehingga 10 // 3 menghasilkan 3.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (6, 'Tantangan 2.1: Menghitung Luas dan Keliling', 'Diberikan panjang = 10 dan lebar = 5. Hitung luas (panjang * lebar) dan keliling (2 * (panjang + lebar)).
Cetak:
Luas: 50
Keliling: 30', 'panjang = 10
lebar = 5
# Hitung dan cetak
', '[{"name": "Cek Luas dan Keliling", "expected_output": "Luas: 50\nKeliling: 30"}]', 'luas = panjang * lebar; keliling = 2 * (panjang + lebar)', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (7, 2, '2.2', '2.2 Mengambil Input & Type Casting', '2-2-input-type-casting', '<h3>Fungsi input() Interaktif</h3>
<p>Fungsi <code>input()</code> meminta masukan dari pengguna di layar HP. <strong>Ingat:</strong> hasil dari <code>input()</code> selalu bertipe <strong>string (teks)</strong>.</p>
<h4>Konversi Tipe Data (Casting):</h4>
<pre><code># Mengubah teks menjadi bilangan bulat
tahun = int(input("Tahun lahir: "))
# Mengubah teks menjadi desimal
berat = float(input("Berat badan: "))</code></pre>', 'nama = input("Masukkan nama Anda: ")
tahun_lahir = int(input("Masukkan tahun lahir: "))
umur = 2026 - tahun_lahir

print("Halo", nama, "umur Anda adalah", umur, "tahun")', 2, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (7, 'Mengapa perintah x = input() + 5 akan menimbulkan error?', 'Fungsi input() tidak bisa dipanggil', 'Hasil input() berupa string, sehingga tidak bisa langsung dijumlahkan dengan angka integer 5', 'Angka 5 harus diapit tanda kutip', 'Variabel x tidak dideklarasikan', 'B', 'input() menghasilkan string. Kita harus membungkusnya dengan int(input()) sebelum menjumlahkannya dengan angka.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (7, 'Tantangan 2.2: Pengganda Angka', 'Ambil satu angka input dari user, kalikan dengan 2, lalu cetak hasilnya dalam format:
Hasil: <nilai>', 'angka = int(input())
# Kalikan dengan 2 dan cetak
', '[{"name": "Uji Angka 7", "inputs": ["7"], "expected_output": "Hasil: 14"}, {"name": "Uji Angka 25", "inputs": ["25"], "expected_output": "Hasil: 50"}]', 'print(f"Hasil: {angka * 2}")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (8, 2, '2.3', '2.3 Debugging: Mengatasi Error Tipe Data', '2-3-debugging-tipe-data', '<h3>Memperbaiki Error Penjumlahan Teks</h3>
<p>Banyak pemula mengalami bug saat menjumlahkan input: jika mengetik <code>5</code> dan <code>3</code>, hasilnya menjadi <code>53</code> (karena digabung sebagai teks) bukannya <code>8</code>!</p>
<pre><code># SALAH (Hasil teks gabungan "53"):
a = input()
b = input()
print(a + b)

# BENAR:
a = int(input())
b = int(input())
print(a + b)</code></pre>', '# Kode di bawah memiliki bug penggabungan teks
a = input()
b = input()
# Perbaiki agar menjumlahkan secara matematika
print("Jumlah:", a + b)', 3, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (8, 'Jika a = "10" dan b = "20", apa hasil dari print(a + b)?', '30', '1020', 'Error', 'None', 'B', 'Karena keduanya adalah string (teks), tanda + akan menyambung teks tersebut menjadi "1020".', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (8, 'Tantangan 2.3: Perbaiki Bug Penjumlahan Input', 'Perbaiki program agar menerima 2 baris input angka, lalu mencetak jumlah matematikanya:
Jumlah: <hasil>', 'a = int(input())
b = int(input())
print(f"Jumlah: {a + b}")', '[{"name": "Uji 15 + 25", "inputs": ["15", "25"], "expected_output": "Jumlah: 40"}]', 'Pastikan menggunakan int(input()) untuk kedua variabel.', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (9, 2, '2.4', '2.4 F-Strings: Format Teks Modern', '2-4-fstrings-format-modern', '<h3>Format Teks Rapi dengan F-Strings</h3>
<p>Awali string dengan huruf <code>f</code>, lalu masukkan variabel atau ekspresi di dalam kurung kurawal <code>{ }</code>:</p>
<pre><code>nama = "Rani"
nilai = 89.567
print(f"Nama: {nama}, Nilai: {nilai:.2f}")</code></pre>
<p>Format <code>:.2f</code> membulatkan angka desimal ke 2 angka di belakang koma.</p>', 'barang = "Buku Tulis"
harga = 5000
jumlah = 3
total = harga * jumlah

print(f"Barang : {barang}")
print(f"Total  : Rp {total}")', 4, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (9, 'Simbol apa yang diletakkan tepat di depan tanda kutip pembuka pada f-string di Python?', 'Huruf s', 'Huruf f', 'Tanda dolar $', 'Tanda pagar #', 'B', 'F-string selalu diawali dengan huruf f (atau F), contoh: f"Halo {nama}".', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (9, 'Tantangan 2.4: Cetak Struk Belanja dengan F-String', 'Diberikan produk = "Pensil", qty = 4, harga = 2000. Cetak tepat:
Beli 4 Pensil seharga Rp 8000', 'produk = "Pensil"
qty = 4
harga = 2000
# Cetak dengan f-string
', '[{"name": "Cek Struk Pensil", "expected_output": "Beli 4 Pensil seharga Rp 8000"}]', 'print(f"Beli {qty} {produk} seharga Rp {qty * harga}")', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (10, 2, '2.5', '2.5 Boss Challenge: Konverter Suhu Multi-Satuan', '2-5-boss-challenge-suhu', '<h3>Soal Kenaikan Part 2: Kalkulator Suhu Lengkap</h3>
<p>Buat program konversi suhu yang menerima input suhu dalam satuan <strong>Celcius</strong> (float), lalu hitung:</p>
<ul class="list-disc pl-5 space-y-1">
<li><strong>Fahrenheit:</strong> <code>(Celcius * 9/5) + 32</code></li>
<li><strong>Reamur:</strong> <code>Celcius * 4/5</code></li>
</ul>
<p>Cetak hasil dalam format 2 baris terpisah dengan f-string.</p>', 'celcius = float(input())
# Hitung fahrenheit dan reamur
', 5, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (10, 'Berapakah nilai suhu 0 Celcius jika dikonversi ke Fahrenheit?', '0', '32.0', '100.0', '212.0', 'B', 'Rumus Fahrenheit adalah (0 * 9/5) + 32 = 32.0.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (10, 'Tantangan 2.5 (Boss): Konverter Suhu Lengkap', 'Program menerima satu angka input suhu Celcius. Cetak tepat 2 baris:
Fahrenheit: <nilai>
Reamur: <nilai>', 'celcius = float(input())
fah = (celcius * 9/5) + 32
rea = celcius * 4/5
print(f"Fahrenheit: {fah}")
print(f"Reamur: {rea}")', '[{"name": "Uji 100 C", "inputs": ["100"], "expected_output": "Fahrenheit: 212.0\nReamur: 80.0"}, {"name": "Uji 0 C", "inputs": ["0"], "expected_output": "Fahrenheit: 32.0\nReamur: 0.0"}]', 'Gunakan rumus konversi dan cetak 2 baris terpisah.', 50);
INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES (3, 'Percabangan (Kondisi Logika)', 'percabangan-logika', 'Membuat program mengambil keputusan menggunakan struktur if, elif, else, and, or, not.', 'Logic Master', 'fa-code-branch', 3);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (11, 3, '3.1', '3.1 Operator Pembanding (==, !=, <, >, <=, >=)', '3-1-operator-pembanding', '<h3>Membandingkan Nilai</h3>
<p>Operator pembanding selalu menghasilkan nilai boolean (<code>True</code> atau <code>False</code>):</p>
<ul class="list-disc pl-5 space-y-1">
<li><code>==</code> (sama dengan) dan <code>!=</code> (tidak sama dengan)</li>
<li><code><</code> (kurang dari) dan <code>></code> (lebih dari)</li>
<li><code><=</code> (kurang dari atau sama dengan) dan <code>>=</code> (lebih dari atau sama dengan)</li>
</ul>', 'nilai = 80
kkm = 75

print("Apakah tuntas?", nilai >= kkm)
print("Apakah sempurna?", nilai == 100)', 1, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (11, 'Manakah operator yang digunakan untuk menguji apakah dua nilai bernilai sama di Python?', '=', '==', '===', 'eq', 'B', 'Tanda = digunakan untuk mengisi nilai variabel, sedangkan == digunakan untuk membandingkan kesamaan.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (11, 'Tantangan 3.1: Pengecekan Angka Positif', 'Ambil satu angka input integer. Jika angka > 0, cetak "POSITIF". Jika tidak, cetak "BUKAN POSITIF".', 'angka = int(input())
if angka > 0:
    print("POSITIF")
else:
    print("BUKAN POSITIF")', '[{"name": "Uji 10", "inputs": ["10"], "expected_output": "POSITIF"}, {"name": "Uji -5", "inputs": ["-5"], "expected_output": "BUKAN POSITIF"}]', 'Gunakan if angka > 0:', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (12, 3, '3.2', '3.2 Struktur if, elif, dan else', '3-2-if-elif-else', '<h3>Pengambilan Keputusan Bertingkat</h3>
<pre><code>skor = 85

if skor >= 90:
    print("Grade A")
elif skor >= 80:
    print("Grade B")
elif skor >= 70:
    print("Grade C")
else:
    print("Grade D")</code></pre>', 'suhu = 38

if suhu >= 38:
    print("Kondisi: Demam Tinggi")
elif suhu >= 37:
    print("Kondisi: Hangat")
else:
    print("Kondisi: Normal")', 2, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (12, 'Berapa banyak blok elif yang dapat ditulis di dalam satu struktur percabangan di Python?', 'Maksimal 1', 'Maksimal 3', 'Sebanyak yang dibutuhkan (tanpa batas)', 'Tidak boleh ada elif', 'C', 'Anda dapat menulis blok elif sebanyak apapun sesuai jumlah kondisi yang ingin diuji.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (12, 'Tantangan 3.2: Penentu Grade Nilai', 'Buat program penentu grade:
Nilai >= 85: "GRADE A"
Nilai >= 70: "GRADE B"
Lainnya: "GRADE C"', 'nilai = int(input())
if nilai >= 85:
    print("GRADE A")
elif nilai >= 70:
    print("GRADE B")
else:
    print("GRADE C")', '[{"name": "Uji 90", "inputs": ["90"], "expected_output": "GRADE A"}, {"name": "Uji 75", "inputs": ["75"], "expected_output": "GRADE B"}, {"name": "Uji 50", "inputs": ["50"], "expected_output": "GRADE C"}]', 'Gunakan if-elif-else terurut dari nilai terbesar.', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (13, 3, '3.3', '3.3 Operator Logika (and, or, not)', '3-3-operator-logika-and-or', '<h3>Menggabungkan Beberapa Kondisi</h3>
<ul class="list-disc pl-5 space-y-1">
<li><code>and</code>: Benar HANYA JIKA kedua syarat terpenuhi.</li>
<li><code>or</code>: Benar jika SALAH SATU syarat terpenuhi.</li>
<li><code>not</code>: Membalikkan nilai logika.</li>
</ul>', 'kehadiran = 85
nilai_tugas = 78

if kehadiran >= 80 and nilai_tugas >= 75:
    print("LULUS")
else:
    print("REMEDIAL")', 3, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (13, 'Jika a = True dan b = False, apa hasil dari: a and b ?', 'True', 'False', 'None', 'Error', 'B', 'Operator and membutuhkan kedua sisi bernilai True agar hasilnya True.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (13, 'Tantangan 3.3: Seleksi Penerima Diskon Kafe', 'Siswa berhak diskon jika umur < 18 ATAU memiliki status member True. Input baris 1: umur (int), baris 2: member ("ya" / "tidak"). Jika berhak diskon, cetak "DAPAT DISKON", jika tidak cetak "HARGA NORMAL".', 'umur = int(input())
member = input()
if umur < 18 or member == "ya":
    print("DAPAT DISKON")
else:
    print("HARGA NORMAL")', '[{"name": "Uji Umur 16 Non Member", "inputs": ["16", "tidak"], "expected_output": "DAPAT DISKON"}, {"name": "Uji Umur 25 Non Member", "inputs": ["25", "tidak"], "expected_output": "HARGA NORMAL"}]', 'Gunakan operator or: if umur < 18 or member == "ya":', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (14, 3, '3.4', '3.4 Percabangan Bersarang (Nested if)', '3-4-nested-if-bersarang', '<h3>Percabangan di Dalam Percabangan</h3>
<p>Nested if digunakan saat suatu kondisi hanya perlu diperiksa setelah kondisi utama terpenuhi:</p>
<pre><code>punya_tiket = True
usia = 17

if punya_tiket:
    if usia >= 17:
        print("Boleh menonton film dewasa")
    else:
        print("Hanya boleh menonton film remaja")
else:
    print("Harap beli tiket dahulu")</code></pre>', 'pin_benar = 1234
saldo = 50000

input_pin = 1234
tarik = 20000

if input_pin == pin_benar:
    if tarik <= saldo:
        print("Tarik tunai berhasil!")
    else:
        print("Saldo tidak cukup!")
else:
    print("PIN salah!")', 4, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (14, 'Berapa spasi standar indentasi untuk blok kode di dalam nested if di Python?', '2 spasi', '4 spasi untuk level 1, 8 spasi untuk level 2', 'Bebas tanpa aturan', '10 spasi', 'B', 'Setiap kali masuk ke blok baru (:), tambahkan 4 spasi (sehingga nested if masuk 8 spasi).', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (14, 'Tantangan 3.4: Validasi Gerbang Masuk', 'Input baris 1: status kartu ("aktif"/"nonaktif"). Input baris 2: saldo tiket (int). Jika kartu "aktif", periksa saldo: jika saldo >= 5000 cetak "PINTU TERBUKA", jika saldo < 5000 cetak "SALDO KURANG". Jika kartu bukan "aktif", cetak "KARTU TIDAK VALID".', 'kartu = input()
saldo = int(input())
if kartu == "aktif":
    if saldo >= 5000:
        print("PINTU TERBUKA")
    else:
        print("SALDO KURANG")
else:
    print("KARTU TIDAK VALID")', '[{"name": "Uji Aktif Cukup Saldo", "inputs": ["aktif", "10000"], "expected_output": "PINTU TERBUKA"}, {"name": "Uji Kartu Rusak", "inputs": ["blokir", "5000"], "expected_output": "KARTU TIDAK VALID"}]', 'Gunakan percabangan bersarang if kartu == "aktif": lalu if saldo >= 5000:', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (15, 3, '3.5', '3.5 Boss Challenge: Sistem Evaluasi Siswa & KKM', '3-5-boss-challenge-evaluasi-kkm', '<h3>Soal Kenaikan Part 3: Sistem Kelulusan Komprehensif</h3>
<p>Buat program pengecekan kelulusan akhir semester. Siswa dinyatakan <strong>LULUS</strong> jika memenuhi <strong>SEMUA</strong> syarat berikut:</p>
<ol class="list-decimal pl-5 space-y-1">
<li>Nilai Ujian >= 75</li>
<li>Persentase Kehadiran >= 80%</li>
<li>Nilai Sikap adalah "A" atau "B"</li>
</ol>
<p>Jika ada salah satu syarat yang tidak terpenuhi, cetak "TIDAK LULUS".</p>', 'nilai = int(input())
kehadiran = int(input())
sikap = input()

# Tulis logika kelulusan Anda di bawah', 5, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (15, 'Ekspresi logika manakah yang benar untuk memeriksa apakah sikap adalah "A" atau "B"?', 'sikap == "A" or "B"', 'sikap == "A" or sikap == "B"', 'sikap in ("A", "B") benar keduanya', 'Pilihan B dan C benar', 'D', 'sikap == "A" or sikap == "B" dan sikap in ("A", "B") adalah cara yang benar.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (15, 'Tantangan 3.5 (Boss): Sistem Kelulusan Mandiri', 'Input baris 1: nilai (int), baris 2: kehadiran (int), baris 3: sikap (str). Jika nilai >= 75 dan kehadiran >= 80 dan (sikap == "A" or sikap == "B"), cetak "STATUS: LULUS". Jika tidak, cetak "STATUS: TIDAK LULUS".', 'nilai = int(input())
kehadiran = int(input())
sikap = input()

if nilai >= 75 and kehadiran >= 80 and (sikap == "A" or sikap == "B"):
    print("STATUS: LULUS")
else:
    print("STATUS: TIDAK LULUS")', '[{"name": "Uji Lulus", "inputs": ["85", "90", "A"], "expected_output": "STATUS: LULUS"}, {"name": "Uji Nilai Kurang", "inputs": ["70", "95", "A"], "expected_output": "STATUS: TIDAK LULUS"}]', 'Perhatikan tanda kurung pada (sikap == "A" or sikap == "B") agar logika tidak tertukar.', 50);
INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES (4, 'Perulangan (Loops & Iterasi)', 'perulangan-python', 'Menjalankan instruksi berulang kali dengan for loop, while loop, range, break, dan continue.', 'Loop Wizard', 'fa-arrows-rotate', 4);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (16, 4, '4.1', '4.1 Perulangan for & Fungsi range()', '4-1-for-loop-range', '<h3>Mengulang dengan Hitungan Pasti</h3>
<p>Sintaks: <code>range(awal, akhir, langkah)</code>. Ingat bahwa batas akhir bersifat tidak termasuk (exclusive):</p>
<pre><code># Mencetak angka 1 s/d 5
for i in range(1, 6):
    print(f"Iterasi ke-{i}")</code></pre>', '# Mencetak bilangan ganjil 1 sampai 9
for n in range(1, 10, 2):
    print(n)', 1, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (16, 'Berapakah angka terakhir yang dicetak oleh kode: for x in range(1, 5): print(x) ?', '5', '4', '1', '0', 'B', 'Batas akhir pada range(1, 5) adalah 5 yang tidak diikutsertakan, sehingga angka terakhir adalah 4.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (16, 'Tantangan 4.1: Cetak Deret Angka Kelipatan 3', 'Gunakan for loop dan range untuk mencetak angka kelipatan 3 dari 3 sampai 9, masing-masing di baris baru (3, 6, 9).', 'for i in range(3, 10, 3):
    print(i)', '[{"name": "Cek Kelipatan 3", "expected_output": "3\n6\n9"}]', 'Gunakan range(3, 10, 3)', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (17, 4, '4.2', '4.2 Perulangan while & Kondisi Terminasi', '4-2-while-loop-terminasi', '<h3>Mengulang Berdasarkan Kondisi</h3>
<p><code>while</code> akan terus berjalan selama kondisinya bernilai <code>True</code>:</p>
<pre><code>angka = 3
while angka > 0:
    print(angka)
    angka -= 1 # Wajib diubah agar loop berhenti!
print("Selesai!")</code></pre>', 'cacah = 1
while cacah <= 3:
    print("Hitungan:", cacah)
    cacah += 1', 2, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (17, 'Apa yang terjadi jika variabel pengubah kondisi di dalam while loop lupa dituliskan?', 'Program otomatis berhenti', 'Terjadi Infinite Loop (program tidak pernah berhenti)', 'Komputer otomatis restart', 'Sintaks langsung error', 'B', 'Loop akan terus berjalan selamanya karena kondisinya selalu bernilai True.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (17, 'Tantangan 4.2: Hitung Mundur Meluncur', 'Gunakan perulangan while untuk mencetak angka dari 3 turun ke 1, lalu di baris terakhir cetak "MELUNCUR!".
Output:
3
2
1
MELUNCUR!', 'x = 3
while x >= 1:
    print(x)
    x -= 1
print("MELUNCUR!")', '[{"name": "Cek Hitung Mundur", "expected_output": "3\n2\n1\nMELUNCUR!"}]', 'Gunakan x = 3, while x >= 1: print(x); x -= 1', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (18, 4, '4.3', '4.3 Debugging: Mengatasi Infinite Loop', '4-3-debugging-infinite-loop', '<h3>Menemukan Loop yang Macet</h3>
<p>Perhatikan potongan kode ini:</p>
<pre><code># SALAH: i tidak pernah berubah, sehingga i < 5 selalu True!
i = 0
while i < 5:
    print(i)
    # Lupa i += 1

# BENAR:
i = 0
while i < 5:
    print(i)
    i += 1</code></pre>', '# Perbaiki kode di bawah agar tidak macet
i = 1
total = 0
while i <= 4:
    total += i
    i += 1
print("Total:", total)', 3, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (18, 'Operasi apa yang biasa dilakukan untuk mencegah infinite loop pada perulangan while?', 'Menambah kata kunci pass', 'Melakukan increment (i += 1) atau decrement (i -= 1) pada variabel kondisi', 'Menghapus tanda titik dua', 'Mengubah nama variabel', 'B', 'Variabel kondisi harus dimutasi (diubah nilainya) setiap kali iterasi agar akhirnya bernilai False.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (18, 'Tantangan 4.3: Perbaiki Akumulator While Loop', 'Perbaiki while loop agar menghitung total penjumlahan angka dari 1 sampai 5 (1+2+3+4+5 = 15) dan mencetak:
Jumlah: 15', 'i = 1
total = 0
while i <= 5:
    total += i
    i += 1
print(f"Jumlah: {total}")', '[{"name": "Cek Jumlah 1 s/d 5", "expected_output": "Jumlah: 15"}]', 'Pastikan while berjalan dari i = 1 sampai i <= 5 dan di dalam loop ada i += 1.', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (19, 4, '4.4', '4.4 Kontrol Loop: break, continue & Pola Bintang', '4-4-break-continue-pola', '<h3>Mengendalikan Alur Perulangan</h3>
<ul class="list-disc pl-5 space-y-1">
<li><code>break</code>: Keluar dari loop saat itu juga.</li>
<li><code>continue</code>: Lompat langsung ke putaran berikutnya.</li>
</ul>
<pre><code>for n in range(1, 6):
    if n == 3:
        continue # Lewati angka 3
    print(n)</code></pre>', '# Mencetak pola segitiga bintang
for baris in range(1, 4):
    print("*" * baris)', 4, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (19, 'Apa perbedaan utama antara perintah break dan continue?', 'break melompati 1 putaran, continue keluar dari loop', 'break menghentikan seluruh perulangan, continue hanya melompati putaran saat ini', 'Keduanya memiliki fungsi yang persis sama', 'break hanya bisa dipakai di if', 'B', 'break langsung mengakhiri loop, sedangkan continue hanya melewati sisa baris pada iterasi sekarang.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (19, 'Tantangan 4.4: Cetak Pola Segitiga 3 Tingkat', 'Gunakan loop untuk mencetak pola bintang berikut:
*
**
***', 'for i in range(1, 4):
    print("*" * i)', '[{"name": "Cek Segitiga Bintang", "expected_output": "*\n**\n***"}]', 'Gunakan print("*" * i) di dalam loop range(1, 4)', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (20, 4, '4.5', '4.5 Boss Challenge: Simulator Kunci PIN ATM', '4-5-boss-challenge-pin-atm', '<h3>Soal Kenaikan Part 4: Validasi PIN Maksimal 3 Kali</h3>
<p>Simulasikan sistem keamanan PIN ATM. PIN yang benar adalah <code>"1234"</code>.</p>
<p>Sistem akan membaca input berulang kali:</p>
<ul class="list-disc pl-5 space-y-1">
<li>Jika PIN benar, cetak <code>"AKSES DITERIMA"</code> dan langsung berhenti (break).</li>
<li>Jika PIN salah hingga 3 kali berturut-turut, cetak <code>"KARTU DIBLOKIR"</code>.</li>
</ul>', 'pin_benar = "1234"
percobaan = 0
berhasil = False

while percobaan < 3:
    pin_masuk = input()
    percobaan += 1
    if pin_masuk == pin_benar:
        print("AKSES DITERIMA")
        berhasil = True
        break

if not berhasil:
    print("KARTU DIBLOKIR")', 5, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (20, 'Struktur manakah yang paling ideal untuk memvalidasi input pengguna yang berulang sampai benar?', 'Percabangan if tunggal', 'Perulangan while dengan kondisi atau break', 'Fungsi print berulang-ulang', 'Membuat variabel banyak', 'B', 'while loop sangat cocok untuk input berulang yang tidak diketahui pasti berapa kali pengguna akan salah mengetik.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (20, 'Tantangan 4.5 (Boss): Simulator Keamanan PIN', 'Buat simulator PIN dengan PIN benar "1234" dan maksimal 3x coba. Jika benar cetak "AKSES DITERIMA", jika 3x gagal cetak "KARTU DIBLOKIR".', 'pin_benar = "1234"
percobaan = 0
berhasil = False
while percobaan < 3:
    pin_masuk = input()
    percobaan += 1
    if pin_masuk == pin_benar:
        print("AKSES DITERIMA")
        berhasil = True
        break
if not berhasil:
    print("KARTU DIBLOKIR")', '[{"name": "PIN Langsung Benar", "inputs": ["1234"], "expected_output": "AKSES DITERIMA"}, {"name": "PIN 3x Salah", "inputs": ["0000", "1111", "2222"], "expected_output": "KARTU DIBLOKIR"}]', 'Gunakan while percobaan < 3: dan periksa input setiap putaran.', 50);
INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES (5, 'Struktur Data (List & Dictionary)', 'struktur-data', 'Menyimpan dan mengelola kumpulan data majemuk dengan List, Tuple, Set, dan Dictionary.', 'Data Alchemist', 'fa-boxes-stacked', 5);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (21, 5, '5.1', '5.1 List Dasar: Indexing & Slicing', '5-1-list-indexing-slicing', '<h3>Menyimpan Banyak Data dalam Satu List</h3>
<p>List dibuat dengan kurung siku <code>[ ]</code>. Indeks dimulai dari <strong>0</strong>:</p>
<pre><code>buah = ["Apel", "Mangga", "Jeruk"]
print(buah[0])  # "Apel"
print(buah[-1]) # Mengambil elemen terakhir ("Jeruk")</code></pre>', 'bahasa = ["Python", "HTML", "CSS", "JavaScript"]
print("Bahasa pertama :", bahasa[0])
print("Bahasa terakhir:", bahasa[-1])
print("Jumlah bahasa  :", len(bahasa))', 1, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (21, 'Berapakah indeks dari elemen pertama pada sebuah List di Python?', '1', '0', '-1', 'undefined', 'B', 'Dalam Python (dan sebagian besar bahasa pemrograman), indexing selalu dimulai dari angka 0.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (21, 'Tantangan 5.1: Mengakses Elemen List', 'Diberikan list data = ["Merah", "Kuning", "Hijau"]. Cetak elemen pertama dan terakhir dalam format:
Awal: Merah
Akhir: Hijau', 'data = ["Merah", "Kuning", "Hijau"]
print(f"Awal: {data[0]}")
print(f"Akhir: {data[-1]}")', '[{"name": "Cek Awal dan Akhir", "expected_output": "Awal: Merah\nAkhir: Hijau"}]', 'Gunakan data[0] dan data[-1]', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (22, 5, '5.2', '5.2 Manipulasi List: append(), pop(), sort()', '5-2-manipulasi-list', '<h3>Menambah dan Mengubah Isi List</h3>
<ul class="list-disc pl-5 space-y-1">
<li><code>list.append(nilai)</code>: Menambahkan ke ujung akhir.</li>
<li><code>list.pop()</code>: Menghapus elemen terakhir.</li>
<li><code>list.sort()</code>: Mengurutkan elemen secara ascending.</li>
</ul>', 'angka = [40, 10, 30, 20]
angka.append(50)
angka.sort()
print("List terurut:", angka)', 2, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (22, 'Method apakah yang digunakan untuk mengurutkan angka di dalam sebuah list dari terkecil ke terbesar?', 'list.order()', 'list.sort()', 'list.arrange()', 'list.filter()', 'B', 'Method bawaan list untuk mengurutkan adalah .sort().', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (22, 'Tantangan 5.2: Tambah & Urutkan Nilai', 'Buat list nilai = [80, 65, 90]. Tambahkan angka 75 ke dalam list menggunakan append(), urutkan dengan sort(), lalu cetak nilai terkecil (indeks ke-0):
Terkecil: 65', 'nilai = [80, 65, 90]
nilai.append(75)
nilai.sort()
print(f"Terkecil: {nilai[0]}")', '[{"name": "Cek Nilai Terkecil", "expected_output": "Terkecil: 65"}]', 'append 75, lalu sort(), lalu print(f"Terkecil: {nilai[0]}")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (23, 5, '5.3', '5.3 Debugging: Mengatasi IndexError', '5-3-debugging-index-error', '<h3>Mencegah Error Indeks di Luar Batas</h3>
<p>Jika list memiliki 3 elemen, indeks yang tersedia hanya <code>0, 1, 2</code>. Mengakses <code>data[3]</code> akan menghasilkan <strong>IndexError: list index out of range</strong>!</p>', 'siswa = ["Ahmad", "Bella", "Citra"]
# Perbaiki agar tidak IndexError
for i in range(len(siswa)):
    print(f"Siswa #{i+1}: {siswa[i]}")', 3, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (23, 'Jika sebuah list memiliki panjang len(x) = 4, berapakah indeks maksimum yang boleh diakses?', '4', '3', '5', 'Bebas', 'B', 'Indeks dimulai dari 0 sampai n-1, sehingga untuk 4 elemen indeksnya adalah 0, 1, 2, 3.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (23, 'Tantangan 5.3: Iterasi List yang Aman', 'Diberikan list skor = [10, 20, 30]. Perbaiki perulangan agar mencetak ketiga elemen tersebut secara aman baris demi baris.', 'skor = [10, 20, 30]
for s in skor:
    print(s)', '[{"name": "Cek 3 Baris Skor", "expected_output": "10\n20\n30"}]', 'Gunakan for s in skor: print(s)', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (24, 5, '5.4', '5.4 Dictionary: Pasangan Key - Value', '5-4-dictionary-key-value', '<h3>Menyimpan Data Terstruktur</h3>
<p>Dictionary dibuat dengan kurung kurawal <code>{ }</code> dan berpasangan antara kunci (key) dan nilai (value):</p>
<pre><code>siswa = {
    "nama": "Doni",
    "rombel": "PPLG X-1",
    "nilai": 88
}
print(siswa["nama"]) # "Doni"</code></pre>', 'profil = {
    "nama": "Siti",
    "kelas": "X-1",
    "skor": 95
}

print("Nama:", profil["nama"])
print("Skor:", profil["skor"])', 4, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (24, 'Tanda baca apakah yang digunakan untuk memisahkan antara Key dan Value di dalam Dictionary Python?', 'Tanda sama dengan =', 'Tanda titik dua :', 'Tanda panah ->', 'Tanda koma ,', 'B', 'Key dan Value dipisahkan dengan tanda titik dua (:), contoh: {"nama": "Budi"}.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (24, 'Tantangan 5.4: Membuat Profil Dictionary', 'Buat dictionary dengan key "produk" bernilai "Buku" dan key "harga" bernilai 15000. Cetak:
Produk: Buku - Rp 15000', 'item = {"produk": "Buku", "harga": 15000}
print(f"Produk: {item[''produk'']} - Rp {item[''harga'']}")', '[{"name": "Cek Profil Produk", "expected_output": "Produk: Buku - Rp 15000"}]', 'Gunakan item["produk"] dan item["harga"] di dalam f-string.', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (25, 5, '5.5', '5.5 Boss Challenge: Analisis Nilai Rombel', '5-5-boss-challenge-analisis-nilai', '<h3>Soal Kenaikan Part 5: Rekap Nilai Kelas</h3>
<p>Diberikan 3 buah angka nilai siswa (diinputkan user baris demi baris). Masukkan ketiganya ke dalam sebuah List bernama <code>daftar_nilai</code>, lalu hitung:</p>
<ul class="list-disc pl-5 space-y-1">
<li><strong>Rata-rata:</strong> <code>sum(daftar_nilai) / len(daftar_nilai)</code></li>
<li><strong>Nilai Tertinggi:</strong> <code>max(daftar_nilai)</code></li>
</ul>', 'daftar_nilai = []
for _ in range(3):
    daftar_nilai.append(int(input()))

# Hitung rata-rata dan tertinggi', 5, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (25, 'Fungsi bawaan Python apa yang langsung dapat mencari angka terbesar di dalam sebuah List?', 'highest()', 'max()', 'top()', 'peak()', 'B', 'Fungsi max(list) langsung mengembalikan nilai elemen terbesar.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (25, 'Tantangan 5.5 (Boss): Analisis Nilai Siswa', 'Ambil 3 angka input integer ke dalam list. Cetak 2 baris:
Rata-rata: <nilai desimal>
Tertinggi: <nilai>', 'daftar_nilai = [int(input()), int(input()), int(input())]
print(f"Rata-rata: {sum(daftar_nilai)/len(daftar_nilai)}")
print(f"Tertinggi: {max(daftar_nilai)}")', '[{"name": "Uji 80, 90, 100", "inputs": ["80", "90", "100"], "expected_output": "Rata-rata: 90.0\nTertinggi: 100"}]', 'Gunakan sum() dan max()', 50);
INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES (6, 'Fungsi & Modularitas Kode', 'fungsi-modularitas', 'Merapikan kode agar reusable menggunakan fungsi def, parameter, return, dan variable scope.', 'Function Architect', 'fa-cubes', 6);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (26, 6, '6.1', '6.1 Mendefinisikan Fungsi dengan def', '6-1-def-parameter', '<h3>Mencegah Pengulangan Kode</h3>
<p>Fungsi mengelompokkan kode agar bisa dipanggil berulang kali:</p>
<pre><code>def sapa_siswa(nama):
    print(f"Selamat belajar, {nama}!")

sapa_siswa("Ahmad")
sapa_siswa("Citra")</code></pre>', 'def perkenalan(nama, rombel):
    print(f"Halo, saya {nama} dari {rombel}.")

perkenalan("Rian", "PPLG X-1")', 1, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (26, 'Kata kunci apa yang digunakan untuk membuat fungsi di Python?', 'function', 'def', 'void', 'func', 'B', 'Kata kunci resmi Python adalah def (kependekan dari define).', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (26, 'Tantangan 6.1: Fungsi Cetak Header', 'Buat fungsi bernama cetak_header(judul) yang mencetak "=== JUDUL ===". Kemudian panggil fungsi tersebut dengan argumen "PYTHON".', 'def cetak_header(judul):
    print(f"=== {judul} ===")

cetak_header("PYTHON")', '[{"name": "Cek Header Python", "expected_output": "=== PYTHON ==="}]', 'def cetak_header(judul): print(f"=== {judul} ===")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (27, 6, '6.2', '6.2 Nilai Kembalian (return) vs print()', '6-2-return-vs-print', '<h3>Mengembalikan Hasil dengan return</h3>
<p><code>return</code> mengirimkan hasil perhitungan ke pemanggil fungsi agar bisa disimpan dalam variabel:</p>
<pre><code>def kali_dua(x):
    return x * 2

hasil = kali_dua(10)
print(hasil + 5) # 25</code></pre>', 'def hitung_diskon(total, persen):
    return total * (persen / 100)

potongan = hitung_diskon(100000, 10)
print("Potongan:", potongan)', 2, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (27, 'Apa tipe nilai kembalian dari sebuah fungsi jika tidak memiliki pernyataan return di dalamnya?', '0', 'None', 'False', 'Error', 'B', 'Fungsi tanpa return secara implisit mengembalikan nilai None di Python.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (27, 'Tantangan 6.2: Fungsi Luas Persegi Panjang', 'Buat fungsi bernama luas_persegi(p, l) yang mengembalikan (return) p * l. Panggil fungsi tersebut dengan nilai 8 dan 5, lalu cetak hasilnya:
Luas: 40', 'def luas_persegi(p, l):
    return p * l

print(f"Luas: {luas_persegi(8, 5)}")', '[{"name": "Cek Luas 8 x 5", "expected_output": "Luas: 40"}]', 'Gunakan return p * l', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (28, 6, '6.3', '6.3 Debugging: Variabel Lokal vs Global', '6-3-debugging-scope-variabel', '<h3>Memahami Lingkup Variabel (Scope)</h3>
<p>Variabel yang dibuat di dalam fungsi bersifat <strong>lokal</strong> dan tidak bisa diakses dari luar fungsi:</p>
<pre><code>def hitung():
    angka_rahasia = 99
    return angka_rahasia

# print(angka_rahasia) # ERROR: NameError!</code></pre>', '# Variabel global
bonus = 5000

def hitung_gaji(pokok):
    # bonus bisa dibaca dari global
    return pokok + bonus

print("Gaji:", hitung_gaji(50000))', 3, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (28, 'Di manakah variabel lokal suatu fungsi dapat diakses?', 'Di mana saja di seluruh file', 'Hanya di dalam tubuh fungsi tempat variabel tersebut didefinisikan', 'Di file lain yang di-import', 'Hanya di dalam komentar', 'B', 'Variabel lokal hanya hidup dan berlaku di dalam fungsi tempat ia dibuat.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (28, 'Tantangan 6.3: Perbaiki Akses Nilai Fungsi', 'Perbaiki kode agar fungsi mengembalikan total harga (harga + ongkir) dan dicetak di luar fungsi:
Total: 25000', 'def hitung_total():
    harga = 20000
    ongkir = 5000
    return harga + ongkir

print(f"Total: {hitung_total()}")', '[{"name": "Cek Total 25000", "expected_output": "Total: 25000"}]', 'Kembalikan harga + ongkir dengan return', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (29, 6, '6.4', '6.4 Default Arguments & Multiple Returns', '6-4-default-args-multiple-returns', '<h3>Fleksibilitas Parameter Fungsi</h3>
<pre><code>def sapa(nama, gelar="Siswa"):
    return f"Halo {gelar} {nama}"

print(sapa("Ahmad"))             # "Halo Siswa Ahmad"
print(sapa("Budi", "Ketua Rombel")) # "Halo Ketua Rombel Budi"</code></pre>', 'def hitung_pajak(harga, persen=10):
    return harga * (persen / 100)

print("Pajak default:", hitung_pajak(100000))
print("Pajak khusus :", hitung_pajak(100000, 5))', 4, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (29, 'Apa fungsi dari default argument pada parameter fungsi?', 'Membuat parameter menjadi wajib diisi', 'Memberi nilai cadangan jika pemanggil fungsi tidak menyertakan argumen tersebut', 'Menolak argumen angka', 'Mencegah fungsi dipanggil lebih dari sekali', 'B', 'Default argument memberikan nilai otomatis jika pemanggil tidak memasukkan argumen.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (29, 'Tantangan 6.4: Fungsi Konversi Detik ke Menit', 'Buat fungsi bernama detik_ke_menit(detik) yang mengembalikan 2 nilai (tuple): menit dan sisa detik (detik // 60, detik % 60). Panggil dengan argumen 130, cetak: "Menit: 2, Detik: 10".', 'def detik_ke_menit(d):
    return d // 60, d % 60

m, s = detik_ke_menit(130)
print(f"Menit: {m}, Detik: {s}")', '[{"name": "Cek 130 detik", "expected_output": "Menit: 2, Detik: 10"}]', 'return d // 60, d % 60', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (30, 6, '6.5', '6.5 Boss Challenge: Sistem Kasir Kafe Modular', '6-5-boss-challenge-kasir-modular', '<h3>Soal Kenaikan Part 6: Aplikasi Kasir Modular</h3>
<p>Buat 2 fungsi terpisah:</p>
<ol class="list-decimal pl-5 space-y-1">
<li><code>hitung_subtotal(harga, qty)</code>: Mengembalikan harga * qty.</li>
<li><code>hitung_total_akhir(subtotal, diskon_persen)</code>: Mengembalikan subtotal - (subtotal * diskon_persen / 100).</li>
</ol>
<p>Ambil input: harga (int), qty (int), diskon (int). Cetak: <code>"Total Akhir: Rp <nilai_bulat>"</code>.</p>', 'def hitung_subtotal(harga, qty):
    return harga * qty

def hitung_total_akhir(subtotal, diskon):
    return int(subtotal - (subtotal * diskon / 100))

# Ambil input dan cetak', 5, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (30, 'Mengapa memecah program menjadi fungsi-fungsi kecil dianggap sebagai praktik terbaik?', 'Membuat kode lebih mudah dibaca, diuji, dan digunakan kembali', 'Menghilangkan kebutuhan akan variabel', 'Membuat ukuran file Python 0 byte', 'Mempercepat kecepatan internet', 'A', 'Modularitas fungsi membuat kode bersih, terstruktur, dan mudah dirawat.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (30, 'Tantangan 6.5 (Boss): Kasir Modular Terintegrasi', 'Implementasikan kedua fungsi di atas. Input: baris 1 harga (10000), baris 2 qty (3), baris 3 diskon (10). Cetak:
Total Akhir: Rp 27000', 'def hitung_subtotal(harga, qty):
    return harga * qty

def hitung_total_akhir(subtotal, diskon):
    return int(subtotal - (subtotal * diskon / 100))

h = int(input())
q = int(input())
d = int(input())
sub = hitung_subtotal(h, q)
tot = hitung_total_akhir(sub, d)
print(f"Total Akhir: Rp {tot}")', '[{"name": "Uji Diskon 10%", "inputs": ["10000", "3", "10"], "expected_output": "Total Akhir: Rp 27000"}]', 'sub = hitung_subtotal(h, q); tot = hitung_total_akhir(sub, d)', 50);
INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES (7, 'File I/O & Penanganan Error', 'file-dan-error', 'Membaca dan menulis data ke file dengan open(), serta menangani exception dengan try-except.', 'File Guardian', 'fa-shield-halved', 7);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (31, 7, '7.1', '7.1 Penanganan Error: try & except', '7-1-try-except-error', '<h3>Mencegah Program dari Crash</h3>
<p>Gunakan <code>try-except</code> agar kesalahan seperti pembagian nol atau salah ketik input tidak membuat aplikasi mati mendadak:</p>
<pre><code>try:
    angka = int(input("Angka: "))
    print(100 / angka)
except ZeroDivisionError:
    print("Tidak bisa membagi dengan nol!")
except ValueError:
    print("Harus berupa angka!")</code></pre>', 'try:
    a = int(input())
    print(100 // a)
except ZeroDivisionError:
    print("ERROR: NOL")', 1, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (31, 'Blok kode manakah yang akan dieksekusi jika terjadi kesalahan pada blok try?', 'else', 'except', 'finally', 'catch', 'B', 'Di Python, penanganan error menggunakan kata kunci except.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (31, 'Tantangan 7.1: Tangkal Pembagian dengan Nol', 'Input satu angka integer x. Coba hitung 50 // x. Jika terjadi ZeroDivisionError, cetak "GAGAL: BAGI NOL". Jika normal, cetak "HASIL: <nilai>".', 'try:
    x = int(input())
    print(f"HASIL: {50 // x}")
except ZeroDivisionError:
    print("GAGAL: BAGI NOL")', '[{"name": "Uji Normal 5", "inputs": ["5"], "expected_output": "HASIL: 10"}, {"name": "Uji Nol", "inputs": ["0"], "expected_output": "GAGAL: BAGI NOL"}]', 'try: print(f"HASIL: {50 // x}") except ZeroDivisionError: print("GAGAL: BAGI NOL")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (32, 7, '7.2', '7.2 Menulis File: open() Mode w & a', '7-2-menulis-file-open', '<h3>Menyimpan Data ke File Teks</h3>
<ul class="list-disc pl-5 space-y-1">
<li>Mode <code>"w"</code> (write): Membuat file baru atau menimpa file lama.</li>
<li>Mode <code>"a"</code> (append): Menambahkan isi baru ke akhir file tanpa menghapus yang lama.</li>
</ul>
<pre><code>with open("catatan.txt", "w") as f:
    f.write("Halo dari Python!\n")</code></pre>', 'with open("agenda.txt", "w") as f:
    f.write("Belajar Python Bab 7\n")

print("File berhasil disimpan!")', 2, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (32, 'Apa perbedaan mode "w" dan mode "a" pada fungsi open()?', 'w membaca, a menghapus', 'w menimpa seluruh isi file, sedangkan a menambah data ke akhir file', 'w khusus file gambar, a file teks', 'Tidak ada bedanya', 'B', 'Mode w menimpa dari awal, sedangkan mode a (append) menambahkan teks ke ujung akhir file.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (32, 'Tantangan 7.2: Tulis dan Baca Kembali File', 'Tulis teks "SUKSES TERSIMPAN" ke dalam file bernama "log.txt". Lalu baca dan cetak isinya ke layar.', 'with open("log.txt", "w") as f:
    f.write("SUKSES TERSIMPAN")
with open("log.txt", "r") as f:
    print(f.read())', '[{"name": "Cek Baca Tulis File", "expected_output": "SUKSES TERSIMPAN"}]', 'Gunakan with open("log.txt", "w") lalu with open("log.txt", "r")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (33, 7, '7.3', '7.3 Debugging: Mengatasi FileNotFoundError', '7-3-debugging-file-not-found', '<h3>Membaca File dengan Aman</h3>
<p>Jika mencoba membaca file yang belum pernah dibuat dengan mode <code>"r"</code>, Python akan memunculkan <strong>FileNotFoundError</strong>.</p>
<pre><code>try:
    with open("data_rahasia.txt", "r") as f:
        print(f.read())
except FileNotFoundError:
    print("File tidak ditemukan!")</code></pre>', 'try:
    with open("tidak_ada.txt", "r") as f:
        print(f.read())
except FileNotFoundError:
    print("STATUS: FILE KOSONG")', 3, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (33, 'Mengapa konstruksi with open(...) lebih disukai daripada f = open(...) lalu f.close()?', 'with open otomatis menutup file meskipun terjadi error di tengah proses', 'with open membuat file lebih cepat dibaca', 'with open mengompresi ukuran file menjadi zip', 'with open khusus untuk sistem operasi Mac', 'A', 'Context manager with memastikan file tertutup dengan aman tanpa memory leak.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (33, 'Tantangan 7.3: Tangkal FileNotFoundError', 'Coba buka file bernama "data_siswa.txt" mode "r". Jika file tidak ditemukan (FileNotFoundError), cetak "STATUS: DATA BELUM DIBUAT".', 'try:
    with open("data_siswa.txt", "r") as f:
        print(f.read())
except FileNotFoundError:
    print("STATUS: DATA BELUM DIBUAT")', '[{"name": "Cek Tangkal File Hilang", "expected_output": "STATUS: DATA BELUM DIBUAT"}]', 'Gunakan blok try-except FileNotFoundError', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (34, 7, '7.4', '7.4 Modul Standar: math, random & datetime', '7-4-modul-standar-python', '<h3>Memanfaatkan Modul Bawaan Python</h3>
<pre><code>import math
print(math.sqrt(16)) # Akar kuadrat = 4.0

import random
dadu = random.randint(1, 6) # Angka acak 1 s/d 6</code></pre>', 'import math
print("Akar 49 :", math.sqrt(49))
print("Nilai Pi:", round(math.pi, 2))', 4, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (34, 'Fungsi apakah di modul math yang digunakan untuk menghitung akar kuadrat suatu bilangan?', 'math.root()', 'math.sqrt()', 'math.square()', 'math.power()', 'B', 'sqrt adalah singkatan dari Square Root (akar kuadrat).', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (34, 'Tantangan 7.4: Menghitung Akar dengan math.sqrt', 'Ambil satu angka input float n (misal 64.0). Gunakan math.sqrt() untuk menghitung akarnya dan cetak:
Akar: <nilai desimal>', 'import math
n = float(input())
print(f"Akar: {math.sqrt(n)}")', '[{"name": "Uji Akar 64", "inputs": ["64.0"], "expected_output": "Akar: 8.0"}, {"name": "Uji Akar 25", "inputs": ["25.0"], "expected_output": "Akar: 5.0"}]', 'import math lalu print(f"Akar: {math.sqrt(n)}")', 40);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (35, 7, '7.5', '7.5 Boss Challenge: Catatan Transaksi Anti-Crash', '7-5-boss-challenge-catatan-file', '<h3>Soal Kenaikan Part 7: Log Transaksi File Aman</h3>
<p>Buat program yang menerima input nama barang dan harga (int).</p>
<p>Tulis ke dalam file <code>"transaksi.txt"</code> dalam format <code>"{barang}: Rp {harga}\n"</code>, kemudian baca kembali isi file tersebut dan cetak dengan pembuka <code>"=== RIWAYAT TRANSAKSI ===\n"</code>.</p>', 'barang = input()
harga = int(input())

with open("transaksi.txt", "w") as f:
    f.write(f"{barang}: Rp {harga}\n")

print("=== RIWAYAT TRANSAKSI ===")
with open("transaksi.txt", "r") as f:
    print(f.read().strip())', 5, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (35, 'Mengapa penanganan file dan error penting dipelajari sebelum membuat aplikasi utuh di Part 8?', 'Agar data pengguna tidak hilang dan program tidak berhenti tiba-tiba saat terjadi kendala', 'Agar kode terlihat lebih panjang', 'Hanya formalitas kurikulum', 'Agar RAM komputer selalu penuh', 'A', 'Aplikasi yang tangguh harus dapat menyimpan data permanen dan menangani error secara elegan.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (35, 'Tantangan 7.5 (Boss): Log Transaksi Lengkap', 'Input baris 1 nama barang (misal "Buku"), baris 2 harga (15000). Simpan ke "transaksi.txt", lalu cetak:
=== RIWAYAT TRANSAKSI ===
Buku: Rp 15000', 'b = input()
h = int(input())
with open("transaksi.txt", "w") as f:
    f.write(f"{b}: Rp {h}\n")
print("=== RIWAYAT TRANSAKSI ===") 
with open("transaksi.txt", "r") as f:
    print(f.read().strip())', '[{"name": "Uji Catatan Buku", "inputs": ["Buku", "15000"], "expected_output": "=== RIWAYAT TRANSAKSI ===\nBuku: Rp 15000"}]', 'Tulis ke file lalu baca kembali', 50);
INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES (8, 'Proyek Akhir (Capstone Project)', 'proyek-akhir-capstone', 'Menggabungkan semua keahlian Python untuk membuat aplikasi utuh dan siap dinilai oleh Guru.', 'Python Junior Developer', 'fa-trophy', 8);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (36, 8, '8.1', '8.1 Panduan Arsitektur & Clean Code', '8-1-arsitektur-clean-code', '<h3>Menulis Kode Skala Proyek yang Rapi</h3>
<ul class="list-disc pl-5 space-y-1.5">
<li>Pisahkan logika menjadi fungsi-fungsi kecil (maksimal 20-30 baris per fungsi).</li>
<li>Gunakan nama variabel yang bermakna (hindari nama satu huruf seperti <code>a</code>, <code>b</code>, <code>x</code> untuk data penting).</li>
<li>Sertakan komentar ringkas dan docstring pada setiap fungsi.</li>
<li>Gunakan penanganan error <code>try-except</code> pada semua input pengguna.</li>
</ul>', 'def hitung_diskon(total):
    """Menghitung diskon 10% jika total belanja >= 50.000"""
    if total >= 50000:
        return total * 0.1
    return 0', 1, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (36, 'Apa sebutan untuk string dokumentasi di baris pertama sebuah fungsi Python?', 'Docstring', 'Comment-block', 'Note', 'Header-text', 'A', 'Docstring adalah string yang ditulis tepat di bawah def fungsi dengan tanda kutip tiga untuk menjelaskan fungsi tersebut.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (36, 'Tantangan 8.1: Uji Pemahaman Kode Bersih', 'Cetaklah tepat teks prinsip kode bersih:
KODE BERSIH: RAPI, MODULAR, TERUJI', 'print("KODE BERSIH: RAPI, MODULAR, TERUJI")', '[{"name": "Cek Prinsip Clean Code", "expected_output": "KODE BERSIH: RAPI, MODULAR, TERUJI"}]', 'print("KODE BERSIH: RAPI, MODULAR, TERUJI")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (37, 8, '8.2', '8.2 Jalur 1: Deep-Dive Game Teks Edukasi', '8-2-deep-dive-game-teks', '<h3>Panduan Jalur 1: RPG / Game Teks Kuis</h3>
<p>Karakteristik proyek:</p>
<ul class="list-disc pl-5 space-y-1">
<li>Sistem nyawa pemain (HP / Hearts) dan skor akumulatif.</li>
<li>Minimal 3 pertanyaan atau stage tantangan.</li>
<li>Perulangan <code>while</code> utama game loop.</li>
</ul>', 'def mulai_game():
    hp = 3
    skor = 0
    print("Game Teks Siap Dimulai!")', 2, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (37, 'Komponen apa yang menjaga agar sebuah game terus berjalan menunggu aksi pemain berikutnya?', 'Game Loop (biasanya while loop)', 'Fungsi print statis', 'Komentar kode', 'Variabel kosong', 'A', 'Game loop (while running:) adalah inti dari semua game untuk memproses input dan memperbarui status.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (37, 'Tantangan 8.2: Mini Health Bar Game', 'Buat fungsi render_hp(hp) yang mengembalikan simbol hati "♥ " sebanyak jumlah hp. Jika hp = 3, kembalikan "♥ ♥ ♥ ". Cetak: "Darah: ♥ ♥ ♥ "', 'def render_hp(hp):
    return ("♥ " * hp).strip()

print(f"Darah: {render_hp(3)}")', '[{"name": "Cek Darah 3", "expected_output": "Darah: \u2665 \u2665 \u2665"}]', 'return ("♥ " * hp).strip()', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (38, 8, '8.3', '8.3 Jalur 2: Deep-Dive Kasir Toko & File Struk', '8-3-deep-dive-kasir-struk', '<h3>Panduan Jalur 2: Kasir Kafe / Toko Modern</h3>
<p>Karakteristik proyek:</p>
<ul class="list-disc pl-5 space-y-1">
<li>Katalog produk dalam bentuk Dictionary.</li>
<li>Input belanja berulang dengan daftar keranjang (List of Dictionaries).</li>
<li>Penyimpanan struk pembayaran ke file teks otomatis.</li>
</ul>', 'menu = {
    "1": {"nama": "Kopi Susu", "harga": 15000},
    "2": {"nama": "Roti Bakar", "harga": 12000}
}
print("Sistem Menu Siap")', 3, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (38, 'Struktur data apa yang paling efisien untuk menyimpan kode barang dan detail barangnya (nama, harga)?', 'Dictionary', 'Hanya satu string panjang', 'Variabel integer terpisah-pisah', 'File zip', 'A', 'Dictionary dengan pasangan key (kode menu) dan value (data barang) adalah pilihan paling tepat.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (38, 'Tantangan 8.3: Format Baris Struk Belanja', 'Diberikan nama = "Kopi", qty = 2, subtotal = 30000. Cetak tepat:
STRUK: 2x Kopi = Rp 30000', 'nama = "Kopi"
qty = 2
subtotal = 30000
print(f"STRUK: {qty}x {nama} = Rp {subtotal}")', '[{"name": "Cek Baris Struk", "expected_output": "STRUK: 2x Kopi = Rp 30000"}]', 'print(f"STRUK: {qty}x {nama} = Rp {subtotal}")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (39, 8, '8.4', '8.4 Jalur 3: Deep-Dive Buku Nilai Mini CLI', '8-4-deep-dive-buku-nilai-cli', '<h3>Panduan Jalur 3: Sistem Rekap Nilai Rombel</h3>
<p>Karakteristik proyek:</p>
<ul class="list-disc pl-5 space-y-1">
<li>Input data banyak siswa (NIS, Nama, Nilai).</li>
<li>Kalkulasi statistik: Rata-rata kelas, nilai tertinggi, dan persentase kelulusan.</li>
<li>Penyimpanan riwayat rekapitulasi ke file.</li>
</ul>', 'def hitung_kkm(nilai, kkm=75):
    return "LULUS" if nilai >= kkm else "REMEDIAL"', 4, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (39, 'Fungsi apa yang dapat menghitung rata-rata dari list_nilai di Python?', 'sum(list_nilai) / len(list_nilai)', 'list_nilai.average()', 'mean_func()', 'max(list_nilai)', 'A', 'sum(list_nilai) / len(list_nilai) adalah cara standar di Python bawaan.', 15);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (39, 'Tantangan 8.4: Format Status Siswa Terformat', 'Diberikan nama = "Farhan", nilai = 82, status = "LULUS". Cetak:
[HASIL] Farhan: 82 (LULUS)', 'nama = "Farhan"
nilai = 82
status = "LULUS"
print(f"[HASIL] {nama}: {nilai} ({status})")', '[{"name": "Cek Format Hasil Siswa", "expected_output": "[HASIL] Farhan: 82 (LULUS)"}]', 'print(f"[HASIL] {nama}: {nilai} ({status})")', 30);
INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES (40, 8, '8.5', '8.5 Boss Challenge: Deklarasi Pengumpulan Capstone', '8-5-boss-challenge-capstone-final', '<h3>Soal Kenaikan Part 8: Puncak Kelulusan Siswa!</h3>
<p>Selamat atas perjalanan hebat Anda dari Part 1 hingga Part 8! Anda telah menyelesaikan 40 sub-part materi dan latihan mengetik kode.</p>
<p>Sekarang, kunjungi menu <strong>Proyek Akhir</strong> di navigasi atas, pilih jalur proyek Anda, dan kirimkan kode aplikasi utuh Anda untuk dinilai oleh Guru Pembimbing.</p>', 'print("STATUS: SELURUH MATERI PART 1-8 SELESAI")
print("SIAP MENGUMPULKAN CAPSTONE PROJECT")', 5, 25);
INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES (40, 'Setelah menyelesaikan seluruh 8 Part dan proyek akhir disetujui Guru, lencana apa yang akan Anda peroleh?', 'Python Novice', 'Logic Master', 'Python Junior Developer', 'Trial User', 'C', 'Lencana pamungkas adalah Python Junior Developer yang menandai kelulusan dasar hingga mahir tingkat dasar.', 20);
INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES (40, 'Tantangan 8.5 (Boss): Deklarasi Kelulusan Lengkap', 'Cetak tepat 2 baris pernyataan kesiapan kelulusan:
STATUS: SELURUH MATERI SELESAI
PROYEK AKHIR: SIAP DIREVIEW', 'print("STATUS: SELURUH MATERI SELESAI")
print("PROYEK AKHIR: SIAP DIREVIEW")', '[{"name": "Cek Deklarasi Kelulusan", "expected_output": "STATUS: SELURUH MATERI SELESAI\\nPROYEK AKHIR: SIAP DIREVIEW"}]', 'Cetak kedua baris persis seperti yang diminta.', 50);
