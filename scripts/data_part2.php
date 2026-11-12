<?php
/**
 * PART 2: OPERATOR ARITMATIKA, INPUT & FORMAT STRING
 * Sub-topik 2.1 s/d 2.5
 */
return [
    '2.1' => [
        'title' => '2.1 Operator Aritmatika (+, -, *, /, //, %, **)',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Mesin Kasir & Kalkulator Cerdas
        </div>
        <p>Bayangkan Anda seorang kasir di minimarket. Anda menjumlahkan total belanjaan (+), memberi potongan diskon (-), mengalikan jumlah barang (*), membagi biaya patungan (/), mencari tahu sisa permen kembalian (%), hingga menghitung bunga simpanan berganda (**). Di Python, komputer bertindak sebagai kalkulator super cepat yang dapat mengeksekusi jutaan operasi matematika dalam sekejap mata!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Konsep Inti &amp; Operator Aritmatika
        </h4>
        <p>Python memiliki 7 operator aritmatika bawaan yang sangat fleksibel:</p>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li><code>+</code> (Penjumlahan): Menjumlahkan dua angka.</li>
            <li><code>-</code> (Pengurangan): Mengurangkan angka kedua dari angka pertama.</li>
            <li><code>*</code> (Perkalian): Mengalikan angka.</li>
            <li><code>/</code> (Pembagian Nyata): Selalu menghasilkan tipe data <strong>float</strong> desimal (misal <code>10 / 2</code> &rarr; <code>5.0</code>).</li>
            <li><code>//</code> (Pembagian Bulat / Floor Division): Membuang angka di belakang koma dan membulatkan ke bawah (misal <code>10 // 3</code> &rarr; <code>3</code>).</li>
            <li><code>%</code> (Modulus): Menghasilkan sisa bagi (misal <code>10 % 3</code> &rarr; <code>1</code>). Sangat bermanfaat untuk mengecek bilangan ganjil/genap!</li>
            <li><code>**</code> (Perpangkatan / Eksponen): Memangkatkan angka (misal <code>2 ** 3</code> &rarr; <code>8</code>).</li>
        </ul>
        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-900 text-xs">
            <strong>Aturan Prioritas (PEMDAS):</strong> Python mendahulukan Kurung <code>()</code>, Pangkat <code>**</code>, Kali/Bagi <code>* / // %</code>, baru Tambah/Kurang <code>+ -</code>.
        </div>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Simulasi Pembagian Permen ke Siswa
total_permen = 25
jumlah_anak = 4

# Pembagian bulat: berapa permen yang didapat tiap anak
permen_per_anak = total_permen // jumlah_anak

# Modulus: berapa sisa permen yang tertinggal di toples
sisa_permen = total_permen % jumlah_anak

print("Setiap anak mendapat:", permen_per_anak, "permen")
print("Sisa permen di toples:", sisa_permen, "buah")

# Menghitung kuadrat (pangkat dua)
sisi = 5
luas = sisi ** 2
print("Luas persegi (5^2):", luas)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Pemula: Salah Urutan Operasi &amp; ZeroDivisionError
        </div>
        <p><strong>1. Mengabaikan Tanda Kurung:</strong></p>
        <pre class="bg-rose-950/20 text-rose-900 p-2.5 rounded-xl font-mono text-xs mt-1"><code># SALAH (prioritas pembagian dieksekusi lebih dulu):
rata_rata = 70 + 80 + 90 / 3  # Hasilnya 180.0!

# BENAR (tambahkan tanda kurung agar dijumlahkan dulu):
rata_rata = (70 + 80 + 90) / 3  # Hasilnya 80.0</code></pre>
        <p class="mt-2"><strong>2. ZeroDivisionError:</strong> Membagi angka dengan 0 (misal <code>10 / 0</code> atau <code>10 % 0</code>) akan langsung mematikan program secara fatal.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Tombol operator matematika seperti <code>+</code>, <code>-</code>, <code>*</code>, <code>/</code>, dan <code>%</code> tersedia langsung di bilah toolbar tepat di atas keyboard sentuh HP Anda. Gunakan untuk mengetik ekspresi tanpa harus berpindah ke mode simbol keyboard bawaan HP.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Manakah operator Python yang digunakan untuk menghasilkan SISA BAGI dari dua bilangan bulat?',
                'option_a' => '// (Floor division)',
                'option_b' => '% (Modulus)',
                'option_c' => '/ (Pembagian pecahan)',
                'option_d' => '** (Perpangkatan)',
                'correct_option' => 'B',
                'explanation' => 'Operator persen (%) di Python bertindak sebagai Modulus, yaitu menghasilkan sisa dari operasi pembagian bulat (misal 17 % 5 = 2).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Berapakah output dari kode berikut?
hasil = 14 % 4 + 2 ** 3
print(hasil)',
                'option_a' => '10',
                'option_b' => '12',
                'option_c' => '8',
                'option_d' => '14',
                'correct_option' => 'A',
                'explanation' => '14 % 4 bernilai 2 (karena 14 dibagi 4 menghasilkan 3 sisa 2). Lalu 2 ** 3 bernilai 8. Terakhir 2 + 8 = 10.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang siswa ingin menghitung rata-rata nilai tiga tugas:
nilai1 = 80
nilai2 = 90
nilai3 = 70
rata_rata = nilai1 + nilai2 + nilai3 / 3
print(rata_rata)
Mengapa output yang dihasilkan tidak sesuai ekspektasi?',
                'option_a' => 'Karena variabel nama tidak boleh menggunakan angka di akhir',
                'option_b' => 'Karena Python mengeksekusi nilai3 / 3 terlebih dahulu akibat urutan prioritas operator matematika',
                'option_c' => 'Karena nilai tidak bisa dibagi jika berupa integer',
                'option_d' => 'Karena fungsi print tidak bisa mencetak variabel bertipe float',
                'correct_option' => 'B',
                'explanation' => 'Pembagian (/) memiliki prioritas lebih tinggi daripada penjumlahan (+). Untuk memperbaikinya, kelompokkan penjumlahan dengan kurung: (nilai1 + nilai2 + nilai3) / 3.',
                'xp_reward' => 20
            ]
        ]
    ],

    '2.2' => [
        'title' => '2.2 Mengambil Input & Type Casting',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Formulir Isian Pendaftaran
        </div>
        <p>Ketika Anda mengisi formulir kertas di sekolah, kolom "Nama", "Nomor Sepatu", dan "Tinggi Badan" semuanya ditulis menggunakan tinta pena di atas kertas. Bagi kertas, semua tulisan itu hanyalah teks! Jika petugas ingin menghitung rata-rata tinggi badan seluruh kelas di kalkulator, ia harus membaca tulisan teks tersebut lalu mengetikkan angkanya ke kalkulator. Inilah konsep dasar <code>input()</code> dan <strong>Type Casting</strong> (konversi tipe data) di pemrograman.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Konsep Inti &amp; Cara Kerja input()
        </h4>
        <p>Fungsi <code>input("Prompt Pesan: ")</code> digunakan untuk meminta pengguna mengetikkan data melalui keyboard. Program akan berhenti sementara (pause) menunggu pengguna menekan tombol Enter.</p>
        <div class="mt-2 p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-950 font-semibold text-xs">
            <i class="fa-solid fa-circle-exclamation text-rose-600"></i> HUKUM MUTLAK INPUT: Semua data yang masuk dari fungsi input() SELALU bertipe String (str), meskipun pengguna mengetikkan angka 100!
        </div>
        <p class="mt-2">Agar bisa dihitung secara matematika, teks input harus di-casting (dikonversi) secara eksplisit menggunakan fungsi pembungkus:</p>
        <ul class="list-disc pl-5 space-y-1 mt-1 text-slate-600">
            <li><code>int()</code>: Mengubah teks angka bulat menjadi integer (misal: <code>int("25")</code> &rarr; <code>25</code>).</li>
            <li><code>float()</code>: Mengubah teks desimal menjadi float (misal: <code>float("75.5")</code> &rarr; <code>75.5</code>).</li>
            <li><code>str()</code>: Mengubah angka menjadi teks kembali.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Mengambil input teks (nama siswa)
nama = input("Masukkan nama Anda: ")

# Mengambil input angka dan langsung melakukan type casting ke integer
tahun_lahir = int(input("Masukkan tahun lahir Anda: "))

# Menghitung usia
tahun_sekarang = 2026
usia = tahun_sekarang - tahun_lahir

print("Halo,", nama)
print("Tahun ini usia Anda adalah:", usia, "tahun")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Pemula: Menjumlahkan Input Teks Mentah
        </div>
        <p>Jika Anda lupa menambahkan <code>int()</code> atau <code>float()</code>, Python akan memperlakukan angka sebagai teks dan menyambungkannya (concatenation) alih-alih menjumlahkannya:</p>
        <pre class="bg-rose-950/20 text-rose-900 p-2.5 rounded-xl font-mono text-xs mt-1"><code>a = input("Angka 1: ") # Misal diketik 10
b = input("Angka 2: ") # Misal diketik 20

print(a + b) # OUTPUT: "1020" BUKAN 30!</code></pre>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Saat menjalankan kode dengan <code>input()</code> di browser HP pada PyLMS, kotak dialog interaktif akan muncul. Ketik jawaban Anda lalu ketuk tombol OK atau Enter pada keyboard HP untuk melanjutkan eksekusi program.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Tipe data apakah yang SELALU dihasilkan oleh fungsi bawaan input() di Python?',
                'option_a' => 'Integer (int)',
                'option_b' => 'String (str)',
                'option_c' => 'Float (float)',
                'option_d' => 'Tergantung apakah pengguna mengetik huruf atau angka',
                'correct_option' => 'B',
                'explanation' => 'Fungsi input() di Python selalu mengembalikan nilai bertipe String (str), apapun karakter yang diketikkan pengguna melalui keyboard.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan kode berikut:
x = input("Masukkan x: ")  # Pengguna mengetik 25
y = input("Masukkan y: ")  # Pengguna mengetik 35
print(x + y)
Apakah output yang tercetak di layar?',
                'option_a' => '60',
                'option_b' => '2535',
                'option_c' => 'TypeError: unsupported operand',
                'option_d' => 'None',
                'correct_option' => 'B',
                'explanation' => 'Karena x dan y bertipe string (teks), tanda plus (+) akan menggabungkan kedua teks menjadi "2535", bukan menjumlahkannya secara matematika.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Bagaimanakah cara memperbaiki kode berikut agar dapat menghitung keliling lingkaran desimal dengan benar?
jari = input("Jari-jari: ")
keliling = 2 * 3.14 * jari',
                'option_a' => 'jari = str(input("Jari-jari: "))',
                'option_b' => 'jari = float(input("Jari-jari: "))',
                'option_c' => 'jari = round(input("Jari-jari: "))',
                'option_d' => 'keliling = int(jari) + 3.14',
                'correct_option' => 'B',
                'explanation' => 'Jari-jari lingkaran bisa berupa angka desimal, sehingga harus di-casting menggunakan float(input(...)) agar dapat dikalikan dengan float 3.14.',
                'xp_reward' => 20
            ]
        ]
    ],

    '2.3' => [
        'title' => '2.3 Debugging: Mengatasi Error Tipe Data',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Memasukkan Koin ke Lubang Kaset
        </div>
        <p>Bayangkan Anda memiliki mesin cuci otomatis koin. Mesin hanya menerima koin logam berbentuk bulat. Jika seseorang memasukkan selembar uang kertas atau batu ke dalam lubang koin, mesin akan macet dan berbunyi alarm error! Di Python, error tipe data terjadi saat Anda menyuruh komputer melakukan operasi pada benda yang tidak kompatibel.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Anatomi TypeError &amp; ValueError
        </h4>
        <p>Dalam pengolahan data pemula, dua error ini adalah yang paling sering ditemui:</p>
        <ul class="list-disc pl-5 space-y-2 mt-2 text-slate-600">
            <li>
                <strong>TypeError:</strong> Terjadi saat operator diterapkan pada tipe data yang tidak cocok.
                <br><span class="text-rose-600 font-mono text-xs">TypeError: can only concatenate str (not "int") to str</span>
                <br>Contoh: <code>"Umur saya: " + 17</code>. Python bingung: apakah harus mengubah angka jadi teks atau teks jadi angka?
            </li>
            <li>
                <strong>ValueError:</strong> Terjadi saat fungsinya benar, tetapi isi nilainya tidak valid untuk dikonversi.
                <br><span class="text-rose-600 font-mono text-xs">ValueError: invalid literal for int() with base 10: \'budi\'</span>
                <br>Contoh: <code>int("budi")</code> atau <code>int("85.5")</code>. Komputer tidak tahu cara mengubah kata "budi" menjadi bilangan bulat.
            </li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Cara Mengatasi Error
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Solusi 1: Menggunakan fungsi str() sebelum penggabungan (+)
skor = 95
pesan = "Skor Anda adalah " + str(skor)
print(pesan)

# Solusi 2: Gunakan pemisah koma pada print() (Otomatis & Aman)
print("Skor Anda adalah", skor)

# Solusi 3: Konversi desimal bertahap jika input berupa teks desimal
teks_angka = "85.75"
# int(teks_angka) -> AKAN ERROR ValueError!
nilai_float = float(teks_angka) # Sukses jadi 85.75
nilai_bulat = int(nilai_float)   # Sukses jadi 85
print("Hasil konversi:", nilai_bulat)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Aturan Emas Debugging: Baca Baris Terakhir Error
        </div>
        <p>Saat terminal PyLMS memunculkan teks merah panjang (Traceback), jangan panik! Langsung scroll ke <strong>baris paling bawah</strong>. Di sanalah nama error (misal <code>ValueError</code>) dan nomor baris penyebab error tertera dengan jelas.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Jika layar output tertutup error, Anda dapat menekan tombol <strong>Clear Output</strong> atau scroll ke bawah untuk melihat nomor baris kode yang ditunjuk oleh panah merah interpreter.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Perintah manakah di bawah ini yang akan memicu terjadinya ValueError di Python?',
                'option_a' => 'float("45.5")',
                'option_b' => 'int("sembilan")',
                'option_c' => 'str(100)',
                'option_d' => 'int("150")',
                'correct_option' => 'B',
                'explanation' => 'Fungsi int("sembilan") akan menghasilkan ValueError karena kata alfabet "sembilan" tidak dapat diterjemahkan menjadi representasi angka numerik.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan baris kode berikut:
total = "Nilai total: " + 100
Apakah jenis error yang akan dilaporkan oleh interpreter Python?',
                'option_a' => 'SyntaxError',
                'option_b' => 'TypeError',
                'option_c' => 'ZeroDivisionError',
                'option_d' => 'IndexError',
                'correct_option' => 'B',
                'explanation' => 'Python melarang penggabungan teks string dan integer secara langsung dengan operator +. Hal ini memicu TypeError: can only concatenate str (not "int") to str.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang murid memiliki variabel str: teks = "99.8". Jika ia ingin mengubahnya menjadi bilangan bulat (integer), langkah paling tepat adalah:',
                'option_a' => 'int(teks)',
                'option_b' => 'int(float(teks))',
                'option_c' => 'round(teks)',
                'option_d' => 'str(int(teks))',
                'correct_option' => 'B',
                'explanation' => 'int("99.8") langsung akan menyebabkan ValueError karena mengandung titik desimal. Teks harus diubah ke float dahulu dengan float(teks), baru diubah ke integer dengan int().',
                'xp_reward' => 20
            ]
        ]
    ],

    '2.4' => [
        'title' => '2.4 F-Strings: Format Teks Modern',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Piagam Sertifikat Berformat Otomatis
        </div>
        <p>Bayangkan Anda mencetak sertifikat juara lomba. Desain sertifikat sudah memiliki bingkai dan teks baku: <em>"Diberikan kepada [NAMA], atas pencapaian skor [SKOR] poin"</em>. Anda hanya perlu menyisipkan nama pemenang ke dalam kotak kurung tersebut tanpa merombak ulang seluruh teks. Inilah fitur <strong>f-string</strong> (Formatted String Literals), senjata paling elegan dan modern di Python!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Konsep Inti &amp; Keunggulan f-string
        </h4>
        <p>Diperkenalkan sejak Python 3.6, f-string memungkinkan Anda menyisipkan variabel dan ekspresi logika langsung ke dalam teks dengan sintaks yang sangat bersih:</p>
        <ol class="list-decimal pl-5 space-y-1 mt-2 text-slate-600">
            <li>Tambahkan huruf <code>f</code> tepat di depan tanda kutip pembuka string: <code>f"..."</code>.</li>
            <li>Bungkus variabel atau perhitungan matematika di dalam kurung kurawal <code>{ }</code>.</li>
            <li>Anda tidak perlu lagi melakukan type casting manual <code>str()</code> atau menghubungkan puluhan tanda tambah <code>+</code>!</li>
            <li>Mendukung pemformatan angka presisi, misalnya <code>{nilai:.2f}</code> untuk menampilkan tepat 2 angka di belakang koma.</li>
        </ol>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>produk = "Kopi Susu Gula Aren"
harga = 18000
jumlah = 3
diskon = 0.1 # Diskon 10%

total_kotor = harga * jumlah
total_bayar = total_kotor * (1 - diskon)

# Menggunakan f-string modern
print(f"Struk Belanja:")
print(f"- Item   : {produk}")
print(f"- Qty    : {jumlah} x Rp {harga:,}")
print(f"- Diskon : {diskon * 100:.0f}%")
print(f"- Total  : Rp {total_bayar:,.2f}")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Pemula: Lupa Huruf f di Depan Kutip
        </div>
        <p>Jika Anda lupa menuliskan huruf <code>f</code> sebelum tanda kutip pembuka, Python akan menganggap kurung kurawal sebagai teks biasa:</p>
        <pre class="bg-rose-950/20 text-rose-900 p-2.5 rounded-xl font-mono text-xs mt-1"><code>nama = "Fadhil"

# SALAH (lupa f):
print("Halo {nama}, selamat datang!")
# Output: Halo {nama}, selamat datang! (Kurung kurawal tercetak mentah)

# BENAR:
print(f"Halo {nama}, selamat datang!")
# Output: Halo Fadhil, selamat datang!</code></pre>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Tanda kurung kurawal <code>{</code> dan <code>}</code> serta tanda kutip dua <code>"</code> terletak bersebelahan di bilah alat bantu simbol PyLMS. Ketuk tombol <code>{ }</code> untuk langsung menyisipkan sepasang kurung kurawal sekaligus.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Karakter apakah yang wajib diletakkan tepat di depan tanda kutip pembuka string agar fitur f-string dapat aktif?',
                'option_a' => 'Huruf s',
                'option_b' => 'Huruf f',
                'option_c' => 'Tanda dollar ($)',
                'option_d' => 'Tanda pagar (#)',
                'correct_option' => 'B',
                'explanation' => 'Awalan huruf f (atau F) menandakan f-string (formatted string literal) di Python.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode berikut:
item = "Buku Tulis"
harga = 5000
print(f"Beli 2 {item} = Rp {harga * 2}")
Apakah output yang dihasilkan kode tersebut?',
                'option_a' => 'Beli 2 Buku Tulis = Rp {harga * 2}',
                'option_b' => 'Beli 2 Buku Tulis = Rp 10000',
                'option_c' => 'Beli 2 item = Rp 10000',
                'option_d' => 'SyntaxError: invalid syntax',
                'correct_option' => 'B',
                'explanation' => 'Di dalam f-string, ekspresi matematika seperti {harga * 2} akan langsung dihitung nilainya sehingga tercetak Rp 10000.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang programmer ingin menampilkan angka phi dengan tepat 2 digit desimal di f-string:
phi = 3.14159265
Manakah format penulisan yang benar di dalam f-string?',
                'option_a' => 'print(f"Nilai phi: {phi:2d}")',
                'option_b' => 'print(f"Nilai phi: {phi:.2f}")',
                'option_c' => 'print(f"Nilai phi: {phi%2}")',
                'option_d' => 'print(f"Nilai phi: {round:2:phi}")',
                'correct_option' => 'B',
                'explanation' => 'Spesifikasi format .2f di dalam kurung kurawal f-string ({phi:.2f}) membulatkan dan membatasi output float menjadi tepat 2 angka di belakang koma.',
                'xp_reward' => 20
            ]
        ]
    ],

    '2.5' => [
        'title' => '2.5 Boss Challenge: Konverter Suhu Multi-Satuan',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-trophy text-amber-600"></i> 1. Uji Kompetensi Part 2: Stasiun Cuaca Mandiri
        </div>
        <p>Selamat telah menyelesaikan materi kalkulasi dan input data! Di Boss Challenge kali ini, Anda ditantang membuat aplikasi kalkulator suhu stasiun cuaca yang menerima input temperatur Celcius dari pengguna, lalu secara otomatis mengonversinya ke tiga skala internasional sekaligus: <strong>Fahrenheit</strong>, <strong>Reamur</strong>, dan <strong>Kelvin</strong> dengan format tampilan rapi 2 angka di belakang koma.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Formula &amp; Aturan Perhitungan
        </h4>
        <p>Gunakan rumus konversi fisika standar dari Celcius (C):</p>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li><strong>Fahrenheit (F):</strong> <code>(C * 9/5) + 32</code></li>
            <li><strong>Reamur (R):</strong> <code>C * 4/5</code></li>
            <li><strong>Kelvin (K):</strong> <code>C + 273.15</code></li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Solusi
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Program Konverter Suhu Cuaca
print("=== STASIUN MONITORING CUACA ===")
celcius = float(input("Masukkan suhu dalam Celcius: "))

# Perhitungan konversi
fahrenheit = (celcius * 9/5) + 32
reamur = celcius * 4/5
kelvin = celcius + 273.15

# Tampilan hasil menggunakan f-string terformat 2 desimal
print("-" * 32)
print(f"Hasil Konversi Suhu ({celcius:.1f}°C):")
print(f"1. Fahrenheit : {fahrenheit:.2f}°F")
print(f"2. Reamur     : {reamur:.2f}°R")
print(f"3. Kelvin     : {kelvin:.2f} K")
print("-" * 32)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Pemisah Desimal Koma vs Titik
        </div>
        <p>Di Indonesia kita terbiasa menulis desimal dengan tanda koma (misal <code>27,5</code>). Namun di Python, tanda koma berfungsi sebagai pemisah argumen! Selalu gunakan tanda <strong>titik</strong> (<code>27.5</code>) saat mengetikkan angka desimal di kode maupun pada input interaktif.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Uji coba program Anda dengan memasukkan angka <code>0</code> (titik beku air). Jika Fahrenheit bernilai 32.0°F dan Kelvin bernilai 273.15 K, berarti rumus kalkulasi Anda telah 100% akurat!</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Mengapa input suhu pengguna harus dikonversi dengan float(input(...)) dan bukan int(input(...))?',
                'option_a' => 'Karena int() tidak bisa menerima input dari keyboard',
                'option_b' => 'Agar program dapat menerima angka suhu pecahan desimal seperti 36.5 atau 27.8 derajat',
                'option_c' => 'Karena fungsi float berjalan lebih cepat dari int',
                'option_d' => 'Karena suhu selalu bernilai negatif',
                'correct_option' => 'B',
                'explanation' => 'Suhu di dunia nyata seringkali berbentuk desimal (misalnya 36.5°C). Penggunaan float() memungkinkan program menerima bilangan bulat maupun desimal tanpa memicu crash ValueError.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Jika suhu Celcius adalah 100°C (titik didih air), berapakah nilai suhu tersebut dalam skala Fahrenheit berdasarkan rumus (C * 9/5) + 32?',
                'option_a' => '180.0°F',
                'option_b' => '212.0°F',
                'option_c' => '100.0°F',
                'option_d' => '244.0°F',
                'correct_option' => 'B',
                'explanation' => '(100 * 9/5) + 32 = (180) + 32 = 212.0°F.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang murid menulis rumus konversi Reamur sebagai berikut:
r = c * 4 / 5
Namun saat c = 20, ia ingin mencetak hasilnya ke layar menggunakan format f-string. Manakah baris print yang tepat?',
                'option_a' => 'print(f"Reamur: {r:.2f}°R")',
                'option_b' => 'print("Reamur: {r:.2f}°R")',
                'option_c' => 'print(f"Reamur: r:.2f")',
                'option_d' => 'print(f"Reamur: " + {r})',
                'correct_option' => 'A',
                'explanation' => 'Sintaks f"Reamur: {r:.2f}°R" mengaktifkan f-string, menyisipkan variabel r dengan format 2 desimal, dan mencetak satuan °R.',
                'xp_reward' => 20
            ]
        ]
    ]
];
