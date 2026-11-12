<?php
/**
 * PART 3: PERCABANGAN & LOGIKA PENGAMBILAN KEPUTUSAN
 * Sub-topik 3.1 s/d 3.5
 */
return [
    '3.1' => [
        'title' => '3.1 Operator Pembanding (==, !=, <, >, <=, >=)',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Palang Pintu Gerbang Tol Otomatis
        </div>
        <p>Bayangkan palang gerbang tol otomatis. Ketika mobil Anda menempelkan kartu e-toll, sensor memeriksa: <em>"Apakah saldo kartu lebih besar atau sama dengan Rp 15.000?"</em>. Jawabannya hanya ada dua kemungkinan di dunia: <strong>YA (True)</strong> atau <strong>TIDAK (False)</strong>. Di Python, operator pembanding adalah mata dan telinga komputer untuk membuat keputusan berbasis fakta!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Daftar Operator Pembanding Python
        </h4>
        <p>Setiap operasi pembandingan SELALU menghasilkan tipe data <strong>Boolean</strong> (<code>True</code> atau <code>False</code>):</p>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li><code>==</code> (Sama dengan): Memeriksa apakah nilai kiri dan kanan identik.</li>
            <li><code>!=</code> (Tidak sama dengan): Bernilai True jika kedua nilai berbeda.</li>
            <li><code>&gt;</code> (Lebih besar dari): Memeriksa apakah nilai kiri lebih besar.</li>
            <li><code>&lt;</code> (Lebih kecil dari): Memeriksa apakah nilai kiri lebih kecil.</li>
            <li><code>&gt;=</code> (Lebih besar atau sama dengan): Minimal bernilai sama.</li>
            <li><code>&lt;=</code> (Lebih kecil atau sama dengan): Maksimal bernilai sama.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>kkm = 75
nilai_siswa = 82
umur = 16

# Evaluasi nilai perbandingan
apakah_lulus = nilai_siswa >= kkm
apakah_punya_ktp = umur >= 17
apakah_sempurna = nilai_siswa == 100

print("Nilai Siswa      :", nilai_siswa)
print("Apakah Lulus KKM :", apakah_lulus)     # True
print("Boleh Buat KTP   :", apakah_punya_ktp) # False
print("Nilai Sempurna   :", apakah_sempurna)  # False</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Paling Fatal Pemula: Beda = dan ==
        </div>
        <p>Ini adalah kesalahan nomor 1 bagi programmer pemula di seluruh dunia:</p>
        <ul class="list-disc pl-5 space-y-1 mt-1 text-rose-900">
            <li>Tanda <code>=</code> (Satu buah) adalah <strong>Assignment</strong>: Memberi/mengisi nilai ke dalam variabel (misal: <code>skor = 100</code>).</li>
            <li>Tanda <code>==</code> (Dua buah) adalah <strong>Comparison</strong>: Menguji apakah kedua nilai sama (misal: <code>skor == 100</code>).</li>
        </ul>
        <p class="mt-1 text-xs text-rose-800">Menuliskan <code>if skor = 100:</code> akan langsung menghasilkan <code>SyntaxError: invalid syntax</code>!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Tombol <code>==</code>, <code>!=</code>, <code>&lt;=</code>, dan <code>&gt;=</code> telah disediakan sebagai satu tombol praktis di atas keyboard HP Anda pada PyLMS agar Anda tidak perlu mengetik dua karakter secara terpisah.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Manakah operator Python yang digunakan untuk menguji apakah dua nilai TIDAK SAMA DENGAN?',
                'option_a' => '<>',
                'option_b' => '!=',
                'option_c' => '!==',
                'option_d' => 'not ==',
                'correct_option' => 'B',
                'explanation' => 'Di Python standar modern, tanda seru diikuti sama dengan (!=) merepresentasikan operator pertidaksamaan (not equal).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Berapakah output dari evaluasi kode berikut?
a = 25
b = 30
hasil = (a + 5) == b
print(hasil)',
                'option_a' => 'True',
                'option_b' => 'False',
                'option_c' => '30',
                'option_d' => 'SyntaxError',
                'correct_option' => 'A',
                'explanation' => 'a + 5 bernilai 30. Karena 30 == 30 bernilai benar, maka hasil evaluasi perbandingan adalah True.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang murid menulis baris pemeriksaan password:
password_benar = "smk123"
input_user = "smk123"
if input_user = password_benar:
    print("Akses Diterima")
Error apakah yang akan dimunculkan oleh Python?',
                'option_a' => 'TypeError: str cannot be assigned',
                'option_b' => 'SyntaxError: invalid syntax. Maybe you meant \'==\'?',
                'option_c' => 'NameError: name input_user is not defined',
                'option_d' => 'Program berjalan tanpa error',
                'correct_option' => 'B',
                'explanation' => 'Dalam pernyataan kondisi if, Anda wajib menggunakan operator perbandingan == (dua tanda sama dengan), bukan operator pengisian nilai =.',
                'xp_reward' => 20
            ]
        ]
    ],

    '3.2' => [
        'title' => '3.2 Struktur if, elif, dan else',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Lampu Lalu Lintas Persimpangan
        </div>
        <p>Bayangkan Anda berkendara mendekati persimpangan jalan:
        <br><strong>JIKA (if)</strong> lampu Merah &rarr; Kendaraan Berhenti.
        <br><strong>ATAU JIKA (elif)</strong> lampu Kuning &rarr; Kendaraan Bersiap Pelan.
        <br><strong>SELAIN ITU (else)</strong> &rarr; Kendaraan Jalan Terus (Hijau).
        <br>Hanya satu warna lampu yang aktif dalam satu waktu. Begitulah cara struktur percabangan <code>if-elif-else</code> mengarahkan jalannya program Anda!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Sintaks &amp; Aturan Indentasi
        </h4>
        <p>Di Python, blok kode di dalam kondisi ditandai dengan <strong>titik dua (:)</strong> di akhir baris dan <strong>indentasi</strong> (menjorok masuk 4 spasi):</p>
        <ol class="list-decimal pl-5 space-y-1 mt-2 text-slate-600">
            <li><code>if kondisi:</code> Dievaluasi pertama kali.</li>
            <li><code>elif kondisi:</code> (Singkatan dari <em>else if</em>) Dievaluasi HANYA jika kondisi di atasnya bernilai False. Anda boleh memasang sebanyak mungkin elif.</li>
            <li><code>else:</code> Dijalankan sebagai penampung terakhir bila SEMUA kondisi di atasnya bernilai False.</li>
        </ol>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>nilai = int(input("Masukkan nilai ujian (0-100): "))

if nilai >= 90:
    predikat = "A (Sangat Memuaskan)"
elif nilai >= 80:
    predikat = "B (Baik)"
elif nilai >= 75:
    predikat = "C (Cukup - Batas KKM)"
elif nilai >= 60:
    predikat = "D (Kurang - Remedial)"
else:
    predikat = "E (Wajib Mengulang)"

print(f"Hasil Evaluasi: {predikat}")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Lupa Titik Dua &amp; IndentationError
        </div>
        <p>Dua penyebab utama kode macet pada percabangan:</p>
        <ul class="list-disc pl-5 space-y-1 mt-1 text-rose-900">
            <li><strong>Lupa Titik Dua (:)</strong>: Setiap baris <code>if</code>, <code>elif</code>, dan <code>else</code> WAJIB diakhiri tanda titik dua.</li>
            <li><strong>IndentationError: expected an indented block</strong>: Baris kode di dalam blok kondisi harus menjorok masuk ke dalam.</li>
        </ul>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Editor PyLMS secara otomatis menambahkan indentasi saat Anda menekan Enter setelah tanda titik dua <code>:</code>. Jika indentasi hilang, gunakan tombol <strong>TAB</strong> di bilah alat untuk memajukan baris kode.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Karakter apakah yang wajib diletakkan di akhir baris pernyataan if, elif, dan else di Python?',
                'option_a' => 'Titik koma (;)',
                'option_b' => 'Titik dua (:)',
                'option_c' => 'Tanda kurung kurawal ({)',
                'option_d' => 'Tanda panah (->)',
                'correct_option' => 'B',
                'explanation' => 'Di Python, setiap header blok kontrol (if, elif, else, for, while, def) wajib diakhiri tanda titik dua (:).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
skor = 78
if skor >= 85:
    hasil = "Sangat Baik"
elif skor >= 75:
    hasil = "Baik"
else:
    hasil = "Cukup"
print(hasil)
Apakah output program tersebut?',
                'option_a' => 'Sangat Baik',
                'option_b' => 'Baik',
                'option_c' => 'Cukup',
                'option_d' => 'Baik Sangat Baik',
                'correct_option' => 'B',
                'explanation' => 'skor (78) tidak memenuhi >= 85, tetapi memenuhi kondisi kedua yaitu skor >= 75. Karena itu cabang elif pertama kali dieksekusi menghasilkan "Baik".',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa kode berikut memicu IndentationError saat dijalankan?
umur = 18
if umur >= 17:
print("Dewasa")
else:
print("Anak-anak")',
                'option_a' => 'Karena tidak ada tanda kurung pada umur >= 17',
                'option_b' => 'Karena perintah print tidak menjorok masuk (terindentasi) di bawah klausa if dan else',
                'option_c' => 'Karena variabel umur berupa integer',
                'option_d' => 'Karena else harus diganti dengan elif',
                'correct_option' => 'B',
                'explanation' => 'Python menggunakan indentasi (spasi/tab) untuk menentukan cakupan blok kode. Perintah print harus menjorok 4 spasi ke kanan di bawah if dan else.',
                'xp_reward' => 20
            ]
        ]
    ],

    '3.3' => [
        'title' => '3.3 Operator Logika (and, or, not)',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Syarat Rekrutmen Magang Industri
        </div>
        <p>Sebuah perusahaan teknologi membuka lowongan magang dengan dua kriteria berbeda:
        <br><strong>Jalur A (AND):</strong> Harus Siswa Jurusan RPL <strong>DAN</strong> Nilai Algoritma minimal 80. (Dua syarat ini WAJIB terpenuhi bersamaan).
        <br><strong>Jalur B (OR):</strong> Memiliki Portofolio Aplikasi <strong>ATAU</strong> Juara Lomba LKS. (Cukup salah satu saja terpenuhi, pelamar sudah lolos seleksi).
        <br>Inilah cara operator logika menggabungkan multi-kondisi!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Tabel Kebenaran: and, or, not
        </h4>
        <p>Python menggunakan kata bahasa Inggris baku tanpa simbol aneh:</p>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li><code>and</code>: Menghasilkan <strong>True HANYA JIKA kedua kondisi</strong> bernilai True. Jika salah satu saja False, hasilnya langsung False.</li>
            <li><code>or</code>: Menghasilkan <strong>True jika SALAH SATU atau KEDUA kondisi</strong> bernilai True. Hanya menghasilkan False jika keduanya False.</li>
            <li><code>not</code>: Membalikkan nilai Boolean. <code>not True</code> menghasilkan <code>False</code>, dan <code>not False</code> menghasilkan <code>True</code>.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>nilai_teori = 82
nilai_praktik = 78
sudah_kumpul_laporan = True

# Syarat Kelulusan Uji Sertifikasi:
# 1. Nilai teori minimal 75 DAN praktik minimal 75
# 2. Serta laporan sudah dikumpulkan
if nilai_teori >= 75 and nilai_praktik >= 75 and sudah_kumpul_laporan:
    print("SELAMAT: Anda Lolos Sertifikasi Kompetensi!")
else:
    print("MOHON MAAF: Syarat sertifikasi belum lengkap.")

# Pengecekan tiket diskon (Anak di bawah 5 tahun ATAU Lansia di atas 60)
umur = 4
if umur < 5 or umur >= 60:
    print("Tiket Gratis untuk Balita/Lansia")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Menggunakan Simbol Bahasa Lain (&&, ||)
        </div>
        <p>Bagi yang pernah belajar C++ atau JavaScript, jangan gunakan simbol <code>&&</code> atau <code>||</code> di Python! Python akan memunculkan <code>SyntaxError</code>. Selalu gunakan kata <strong>and</strong> dan <strong>or</strong> huruf kecil.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Rentang nilai dapat ditulis secara berantai seperti rumus matematika di Python! Contoh: <code>70 <= nilai <= 100</code> adalah penulisan ringkas yang sah untuk <code>nilai >= 70 and nilai <= 100</code>.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Kondisi manakah yang menyebabkan operator \'and\' menghasilkan nilai True?',
                'option_a' => 'Jika salah satu kondisi bernilai True',
                'option_b' => 'Hanya jika SELURUH kondisi bernilai True',
                'option_c' => 'Jika semua kondisi bernilai False',
                'option_d' => 'Jika kondisi pertama bernilai False',
                'correct_option' => 'B',
                'explanation' => 'Operator and mewajibkan seluruh kondisi bernilai True agar evaluasi total bernilai True.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan ekspresi logika berikut:
p = True
q = False
hasil = not q and not (p and q)
print(hasil)
Apakah output yang dihasilkan?',
                'option_a' => 'True',
                'option_b' => 'False',
                'option_c' => 'None',
                'option_d' => 'Error',
                'correct_option' => 'A',
                'explanation' => 'not q adalah True. (p and q) bernilai False, sehingga not (p and q) bernilai True. Maka True and True menghasilkan True.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang pengembang ingin mengecek apakah umur seseorang berada di antara 13 sampai 19 tahun (Remaja):
umur = 15
if umur >= 13 or umur <= 19:
    print("Remaja")
Mengapa penggunaan operator \'or\' di atas dianggap sebagai bug logika?',
                'option_a' => 'Karena akan menghasilkan SyntaxError',
                'option_b' => 'Karena ekspresi tersebut akan SELALU bernilai True untuk angka berapa pun (misal umur 5 atau 50 tahun tetap True)',
                'option_c' => 'Karena umur 15 bukan remaja',
                'option_d' => 'Karena operator or tidak bisa dipakai pada angka',
                'correct_option' => 'B',
                'explanation' => 'Angka 5 memenuhi umur <= 19, dan angka 50 memenuhi umur >= 13. Akibat operator or, seluruh angka menjadi True. Seharusnya menggunakan operator and: umur >= 13 and umur <= 19.',
                'xp_reward' => 20
            ]
        ]
    ],

    '3.4' => [
        'title' => '3.4 Percabangan Bersarang (Nested if)',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Sistem Pintu Masuk Bandara
        </div>
        <p>Ketika Anda memasuki bandara untuk penerbangan internasional:
        <br><strong>Pintu 1 (Luar):</strong> Petugas memeriksa apakah Anda punya tiket pesawat. Jika <em>Tidak</em>, langsung ditolak di pintu gerbang luar.
        <br><strong>Pintu 2 (Dalam):</strong> Jika Anda punya tiket, Anda diizinkan masuk ke loket imigrasi untuk memeriksa paspor & visa.
        <br>Pemeriksaan tahap kedua hanya dilakukan jika tahap pertama telah lolos. Inilah konsep <strong>Nested if</strong> (percabangan bersarang)!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Konsep &amp; Indentasi Bertingkat
        </h4>
        <p>Nested if adalah struktur kondisi <code>if</code> yang berada di dalam blok indentasi <code>if</code> lainnya. Setiap tingkatan bersarang membutuhkan tambahan 4 spasi indentasi ke dalam:</p>
        <pre class="bg-slate-900 text-slate-100 p-3 rounded-xl font-mono text-xs"><code>if kondisi_utama:
    # Indentasi level 1 (4 spasi)
    if sub_kondisi:
        # Indentasi level 2 (8 spasi)
        print("Lolos kedua level!")
    else:
        print("Gagal di level kedua.")
else:
    print("Gagal di level pertama.")</code></pre>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Simulasi Login Portal Siswa
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>username_terdaftar = "smkwikrama"
password_terdaftar = "python2026"

input_user = input("Username: ")
input_pass = input("Password: ")

# Level 1: Cek keberadaan akun username
if input_user == username_terdaftar:
    # Level 2: Cek kecocokan password hanya jika username ditemukan
    if input_pass == password_terdaftar:
        print("Login Berhasil! Selamat datang di Dashboard.")
    else:
        print("Error: Password Anda salah!")
else:
    print("Error: Akun Username tidak ditemukan dalam sistem.")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: "Dangling Else" Akibat Salah Spasi
        </div>
        <p>Jika indentasi blok <code>else</code> bergeser sedikit saja, Python akan memasangkannya dengan pernyataan <code>if</code> yang salah, mengubah seluruh alur logika program tanpa menghasilkan pesan error (silent bug)!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Perhatikan garis titik-titik vertikal di area editor kode PyLMS. Garis tersebut menunjukkan keselarasan kolom indentasi agar Anda tahu dengan tepat blok <code>if</code> mana yang berpasangan dengan blok <code>else</code>.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Apakah yang dimaksud dengan istilah \'Nested if\' dalam pemrograman Python?',
                'option_a' => 'Pernyataan if yang tidak memerlukan kondisi',
                'option_b' => 'Pernyataan if yang diletakkan di dalam blok pernyataan if lain',
                'option_c' => 'Perulangan while yang diganti dengan if',
                'option_d' => 'Fungsi print yang diulang berkali-kali',
                'correct_option' => 'B',
                'explanation' => 'Nested if adalah percabangan bersarang di mana sebuah blok if berada di dalam cakupan blok if yang sudah ada.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode berikut:
skor = 90
kehadiran = 70
if skor >= 80:
    if kehadiran >= 75:
        print("Lulus Ujian")
    else:
        print("Remedial Kehadiran")
else:
    print("Remedial Teori")
Apakah teks yang akan dicetak di layar?',
                'option_a' => 'Lulus Ujian',
                'option_b' => 'Remedial Kehadiran',
                'option_c' => 'Remedial Teori',
                'option_d' => 'Tidak mencetak apa pun',
                'correct_option' => 'B',
                'explanation' => 'skor (90) lolos kondisi luar (>= 80). Namun kehadiran (70) gagal kondisi dalam (>= 75), sehingga mengeksekusi cabang else dalam: "Remedial Kehadiran".',
                'xp_reward' => 20
            ],
            [
                'question' => 'Berapa jumlah spasi standar yang dibutuhkan untuk baris kode pada tingkat bersarang kedua (Level 2) di Python?',
                'option_a' => '2 spasi',
                'option_b' => '4 spasi',
                'option_c' => '8 spasi (dua kali indentasi)',
                'option_d' => '16 spasi',
                'correct_option' => 'C',
                'explanation' => 'Setiap level indentasi standar di Python adalah 4 spasi (PEP 8). Tingkat bersarang kedua membutuhkan 4 + 4 = 8 spasi.',
                'xp_reward' => 20
            ]
        ]
    ],

    '3.5' => [
        'title' => '3.5 Boss Challenge: Sistem Evaluasi Siswa & KKM',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-trophy text-amber-600"></i> 1. Uji Kompetensi Part 3: Sistem Akademik Rombel
        </div>
        <p>Saatnya membuktikan penguasaan logika dan percabangan Anda! Anda ditugaskan membangun program evaluasi kelulusan siswa SMK yang menggabungkan verifikasi multi-syarat: <strong>Nilai Ujian Akademik</strong>, <strong>Persentase Kehadiran Absensi</strong>, dan <strong>Predikat Sikap</strong>.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Kriteria &amp; Ketentuan Kelulusan
        </h4>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Syarat Mutlak:</strong> Kehadiran harus &gt;= 85%. Jika kehadiran di bawah 85%, siswa langsung dinyatakan <em>"TIDAK LULUS (Kehadiran Rendah)"</em> tanpa melihat nilai.</li>
            <li><strong>Jika Kehadiran Memenuhi Syarat:</strong>
                <ul class="list-circle pl-5 mt-1 space-y-1 text-slate-500">
                    <li>Nilai &gt;= 85: <em>"LULUS DENGAN PUJIAN (Predikat A)"</em></li>
                    <li>Nilai &gt;= 75: <em>"LULUS MEMENUHI KKM (Predikat B)"</em></li>
                    <li>Nilai &lt; 75: <em>"REMEDIAL AKADEMIK (Belum Capai KKM)"</em></li>
                </ul>
            </li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Solusi
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>print("=== SISTEM EVALUASI SISWA WIKRAMA ===")
nama = input("Nama Siswa : ")
nilai = float(input("Nilai Ujian : "))
absen = float(input("Persentase Kehadiran (%): "))

print("\n" + "=" * 36)
print(f"Laporan Hasil Belajar: {nama}")
print(f"Nilai: {nilai:.1f} | Kehadiran: {absen:.1f}%")
print("-" * 36)

# Evaluasi Berjenjang dengan Nested if
if absen >= 85:
    if nilai >= 85:
        status = "LULUS DENGAN PUJIAN (Predikat A)"
    elif nilai >= 75:
        status = "LULUS MEMENUHI KKM (Predikat B)"
    else:
        status = "REMEDIAL AKADEMIK (Belum KKM)"
else:
    status = "TIDAK LULUS (Kehadiran di bawah 85%)"

print(f"Status Kelulusan : {status}")
print("=" * 36)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Membandingkan String dengan Angka
        </div>
        <p>Jangan lupa mengubah input kehadiran dan nilai dengan <code>float(...)</code>. Jika Anda membiarkannya berupa string teks, Python akan memicu <code>TypeError: \'&gt;=\' not supported between instances of \'str\' and \'int\'</code>.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Setelah menulis kode di editor PyLMS, lakukan uji coba dengan minimal 3 kasus data: (1) Siswa teladan (nilai 95, hadir 100%), (2) Siswa pintar tapi bolos (nilai 95, hadir 70%), dan (3) Siswa rajin tapi remidi (nilai 65, hadir 95%).</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Mengapa pemeriksaan kehadiran diposisikan pada blok if paling luar pada sistem evaluasi ini?',
                'option_a' => 'Karena kehadiran lebih sulit dihitung',
                'option_b' => 'Sebagai filter utama (gatekeeper), karena jika kehadiran tidak mencapai 85%, siswa otomatis tidak lulus tanpa perlu memeriksa nilai ujian',
                'option_c' => 'Karena nilai ujian tidak boleh berupa float',
                'option_d' => 'Agar program berjalan dalam perulangan tak terbatas',
                'correct_option' => 'B',
                'explanation' => 'Dalam logika bisnis sekolah, kehadiran adalah syarat mutlak (prasyarat). Memeriksanya di gerbang awal membuat alur program lebih efisien dan jelas.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Seorang siswa bernama Rizky memperoleh nilai ujian 98, namun persentase kehadirannya hanya 82%. Berdasarkan logika program di atas, apakah status akhir Rizky?',
                'option_a' => 'LULUS DENGAN PUJIAN (Predikat A)',
                'option_b' => 'LULUS MEMENUHI KKM (Predikat B)',
                'option_c' => 'TIDAK LULUS (Kehadiran di bawah 85%)',
                'option_d' => 'REMEDIAL AKADEMIK',
                'correct_option' => 'C',
                'explanation' => 'Karena absen Rizky (82%) tidak memenuhi syarat absen >= 85%, program langsung meloncat ke blok else luar: "TIDAK LULUS (Kehadiran di bawah 85%)".',
                'xp_reward' => 20
            ],
            [
                'question' => 'Bagaimanakah penulisan baris input yang aman jika pengguna memasukkan nilai desimal seperti 84.5?',
                'option_a' => 'nilai = int(input("Nilai: "))',
                'option_b' => 'nilai = float(input("Nilai: "))',
                'option_c' => 'nilai = str(input("Nilai: "))',
                'option_d' => 'nilai = input(float("Nilai: "))',
                'correct_option' => 'B',
                'explanation' => 'Fungsi float(input(...)) dapat menerima nilai bilangan bulat maupun pecahan desimal tanpa memicu ValueError saat desimal dimasukkan.',
                'xp_reward' => 20
            ]
        ]
    ]
];
