<?php
/**
 * PART 1: PENGENALAN PYTHON & TIPE DATA
 * Sub-topik 1.1 s/d 1.5
 */
return [
    '1.1' => [
        'title' => '1.1 Cara Kerja Python & Fungsi print()',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Pengeras Suara Restoran
        </div>
        <p>Bayangkan Anda bekerja di restoran cepat saji. Dapur memasak makanan, dan ada pelayan yang mengumumkan ke pengeras suara: <em>"Pesanan nomor 25 sudah siap!"</em>. Fungsi <code>print()</code> di Python bekerja persis seperti pengeras suara tersebut. Tanpa <code>print()</code>, komputer bisa memproses miliaran data di memorinya, tetapi Anda tidak akan pernah melihat hasil akhirnya di layar!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Konsep Inti &amp; Cara Kerja Interpreter
        </h4>
        <p>Python adalah <strong>Interpreted Language</strong>. Artinya, program dibaca dan dieksekusi <em>baris per baris</em> dari atas ke bawah. Saat Python membaca baris <code>print(...)</code>:</p>
        <ol class="list-decimal pl-5 space-y-1 mt-2 text-slate-600">
            <li>Interpreter mengevaluasi apa pun yang ada di dalam tanda kurung <code>( )</code>.</li>
            <li>Jika berupa teks dalam tanda kutip, Python mencetaknya apa adanya.</li>
            <li>Jika berupa ekspresi matematika (misal <code>10 + 5</code>), Python menghitungnya dulu (menjadi <code>15</code>), baru mencetak hasilnya.</li>
            <li>Setelah mencetak, Python secara otomatis berpindah ke baris baru (newline <code>\n</code>).</li>
        </ol>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Mencetak teks string (wajib pakai tanda kutip)
print("Selamat Datang di Akademi Python SMK!")

# Mencetak angka matematika (tanpa tanda kutip)
print(100)

# Mencetak hasil kalkulasi
print(25 + 75)

# Mencetak banyak data sekaligus dipisahkan koma
print("Tahun:", 2026, "- Status: Aktif")</code></pre>
        <p class="text-[11px] text-slate-500 mt-1"><strong>Keluaran terminal:</strong><br>Selamat Datang di Akademi Python SMK!<br>100<br>100<br>Tahun: 2026 - Status: Aktif</p>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Pemula (Common Mistakes)
        </div>
        <ul class="list-disc pl-5 space-y-1.5 text-slate-700">
            <li><strong>SyntaxError (Lupa Tanda Kurung / Kutip):</strong> Di Python 3, <code>print "Halo"</code> akan error. Wajib menulis <code>print("Halo")</code>.</li>
            <li><strong>NameError (Teks Tanpa Kutip):</strong> Menulis <code>print(Halo)</code> tanpa kutip akan membuat Python mengira <em>Halo</em> adalah variabel yang belum pernah dibuat.</li>
            <li><strong>Huruf Besar-Kecil (Case Sensitivity):</strong> Menulis <code>Print()</code> atau <code>PRINT()</code> akan menghasilkan <code>NameError</code> karena Python hanya mengenali huruf kecil <code>print()</code>.</li>
        </ul>
    </div>

    <div class="p-4 bg-emerald-50/80 border-l-4 border-emerald-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-emerald-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-mobile-screen-button text-emerald-600"></i> 5. Tips Cepat Mengetik di HP
        </div>
        <p>Gunakan tombol pintasan simbol <code>" "</code> dan <code>( )</code> yang ada di atas keyboard emulator HP Anda. Satu ketukan tombol pintas langsung membuat sepasang tanda kurung dan menempatkan kursor di tengahnya!</p>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Mengapa Python dikategorikan sebagai "Interpreted Language" dan apa fungsi utama print()?',
                'option_a' => 'Karena kodenya dikonversi ke file exe sebelum jalan; print() untuk menghapus data',
                'option_b' => 'Karena kodenya dibaca dan dijalankan baris demi baris oleh interpreter; print() untuk menampilkan data ke layar output',
                'option_c' => 'Karena Python hanya bisa dijalankan di peramban web tanpa mesin interpreter',
                'option_d' => 'Karena print() otomatis menghentikan komputer setelah dieksekusi',
                'correct_option' => 'B',
                'explanation' => 'Python adalah bahasa interpretasi yang membaca baris kode secara berurutan. Fungsi print() bertugas mengirimkan teks atau hasil kalkulasi ke terminal output.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode berikut:
print(10 + 5)
print("10 + 5")
Berapakah output yang dihasilkan di layar terminal?',
                'option_a' => '15 lalu baris kedua 15',
                'option_b' => '10 + 5 lalu baris kedua 10 + 5',
                'option_c' => '15 lalu baris kedua 10 + 5',
                'option_d' => 'Terjadi SyntaxError karena teks dan angka dicampur',
                'correct_option' => 'C',
                'explanation' => 'Baris pertama tanpa tanda kutip dihitung sebagai operasi matematika (10+5=15). Baris kedua diapit tanda kutip sehingga dicetak apa adanya sebagai teks ("10 + 5").',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang siswa menulis kode:
Print("Halo Dunia")
Saat dijalankan muncul error NameError: name "Print" is not defined. Mengapa error ini terjadi?',
                'option_a' => 'Tanda kutip harus diganti kutip tunggal',
                'option_b' => 'Python bersifat case-sensitive, fungsi resmi adalah print huruf kecil bukan Print huruf kapital',
                'option_c' => 'Kata "Halo Dunia" dilarang di Python',
                'option_d' => 'Kurang menambahkan titik koma (;) di akhir baris',
                'correct_option' => 'B',
                'explanation' => 'Python membedakan huruf besar dan kecil (case-sensitive). Perintah bawaan Python adalah print() seluruhnya menggunakan huruf kecil.',
                'xp_reward' => 20
            ]
        ]
    ],

    '1.2' => [
        'title' => '1.2 Komentar (#) & Dokumentasi Kode',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Catatan Kertas Sticky Note
        </div>
        <p>Bayangkan Anda menempelkan kertas sticky note di kulkas: <em>"Beli susu sebelum jam 5 sore"</em>. Pesan itu hanya untuk dibaca manusia, kulkas Anda tidak akan melakukan apa pun dengan catatan tersebut. Di dunia pemrograman, itulah fungsi <strong>Komentar</strong>. Komentar adalah catatan untuk programmer lain atau diri Anda sendiri di masa depan yang sepenuhnya diabaikan oleh komputer.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Konsep Inti &amp; Cara Kerja Eksekusi
        </h4>
        <p>Ketika interpreter Python mendeteksi karakter pagar <code>#</code> di sebuah baris:</p>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li>Seluruh karakter setelah tanda <code>#</code> hingga akhir baris tersebut akan <strong>dilewati 100%</strong>.</li>
            <li>Komentar tidak memakai memori dan tidak memperlambat kecepatan eksekusi program.</li>
            <li>Bisa diletakkan di baris tersendiri (Full Line) atau di samping kanan kode yang sedang aktif (Inline).</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Program: Menghitung Keliling Persegi
# Dibuat oleh: Fadhil (X PPLG 1)

sisi = 10 # Satuan dalam centimeter
keliling = 4 * sisi # Rumus: 4 x sisi

# Cetak hasil ke layar
print("Keliling persegi:", keliling)

# print("Baris ini dinonaktifkan sementara saat debugging")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Pemula
        </div>
        <p>Kesalahan umum adalah lupa bahwa tanda <code>#</code> di dalam string teks (diapit tanda kutip) <strong>TIDAK BERLAKU</strong> sebagai komentar!</p>
        <pre class="bg-slate-900 text-slate-100 p-2.5 rounded-xl text-xs font-mono my-1.5"><code>print("Ini tanda # bukan komentar") # Ini baru komentar!</code></pre>
        <p class="text-slate-600">Tanda <code>#</code> di dalam kutip akan ikut dicetak ke layar sebagai teks biasa.</p>
    </div>

    <div class="p-4 bg-emerald-50/80 border-l-4 border-emerald-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-emerald-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-mobile-screen-button text-emerald-600"></i> 5. Tips Cepat Mengetik di HP
        </div>
        <p>Gunakan tombol pintasan simbol <code>#</code> di HP Toolbar agar Anda tidak perlu membuka layer menu simbol pada papan ketik Android/iOS saat ingin membuat catatan kode.</p>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Karakter apakah yang digunakan di Python untuk menandai baris komentar?',
                'option_a' => '// (Garis miring ganda)',
                'option_b' => '/* */ (Bintang slash)',
                'option_c' => '# (Tanda pagar / hash)',
                'option_d' => '-- (Tanda minus ganda)',
                'correct_option' => 'C',
                'explanation' => 'Di Python, komentar satu baris selalu diawali dengan karakter pagar (#). Simbol // di Python adalah pembagian bulat (floor division).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode berikut:
# print("A")
print("B") # print("C")
# print("D")
Huruf apa sajakah yang akan muncul di layar saat kode dijalankan?',
                'option_a' => 'A, B, C, D',
                'option_b' => 'Hanya B',
                'option_c' => 'B dan C',
                'option_d' => 'Tidak ada huruf yang dicetak',
                'correct_option' => 'B',
                'explanation' => 'Baris print("A") dan print("D") berada di baris komentar. Bagian print("C") berada di sebelah kanan tanda # sehingga diabaikan. Hanya print("B") yang dieksekusi.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Manakah pernyataan yang BENAR mengenai sifat komentar di Python?',
                'option_a' => 'Komentar membuat eksekusi program menjadi jauh lebih lambat karena interpreter harus membaca semuanya',
                'option_b' => 'Komentar wajib ditulis di setiap baris kode agar program bisa berjalan tanpa error',
                'option_c' => 'Tanda # di dalam kutip seperti print("#1") akan dianggap komentar dan tidak dicetak',
                'option_d' => 'Komentar diabaikan oleh interpreter dan sangat berguna untuk mendokumentasikan alur logika kode bagi manusia',
                'correct_option' => 'D',
                'explanation' => 'Komentar murni untuk kemudahan membaca kode bagi manusia dan tidak mempengaruhi kinerja maupun logika program.',
                'xp_reward' => 20
            ]
        ]
    ],

    '1.3' => [
        'title' => '1.3 Variabel & Aturan Penamaan',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Kotak Kardus Berlabel
        </div>
        <p>Bayangkan Anda memiliki beberapa kotak kardus di gudang. Agar tidak tertukar, Anda menempelkan stiker label: satu kotak diberi label <code>nama_siswa</code> berisi kertas bertuliskan "Ahmad", kotak lain berlabel <code>umur</code> berisi angka "16". Variabel adalah kotak kardus tersebut yang bertugas menyimpan nilai di memori RAM komputer.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Konsep Inti &amp; Operator Penugasan (=)
        </h4>
        <p>Di Python, tanda sama dengan <code>=</code> bukanlah "sama dengan" dalam matematika, melainkan <strong>Assignment Operator (Penugasan)</strong>. Nilai di sebelah kanan akan disimpan ke dalam variabel di sebelah kiri.</p>
        <p class="mt-1 font-bold text-slate-800">Aturan Resmi Penamaan Variabel (PEP 8 snake_case):</p>
        <ul class="list-disc pl-5 space-y-1 mt-1 text-slate-600">
            <li>Hanya boleh menggunakan huruf (a-z, A-Z), angka (0-9), dan garis bawah (<code>_</code>).</li>
            <li><strong>DILARANG</strong> diawali dengan angka (contoh terlarang: <code>1nama</code>, <code>2data</code>).</li>
            <li><strong>DILARANG</strong> menggunakan spasi atau tanda minus/hubung (contoh terlarang: <code>nama siswa</code>, <code>nama-siswa</code>).</li>
            <li><strong>DILARANG</strong> menggunakan kata kunci cadangan Python (Reserved Keywords seperti <code>print</code>, <code>if</code>, <code>class</code>).</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Penamaan snake_case standar Python
nama_lengkap = "Ahmad Fadhil"
skor_ujian = 95
status_lulus = True

# Menampilkan isi variabel
print("Nama Siswa:", nama_lengkap)
print("Skor:", skor_ujian)

# Mengubah isi variabel (re-assign)
skor_ujian = 100
print("Skor Remedial:", skor_ujian)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Pemula
        </div>
        <p>Banyak pemula menulis kode terbalik, meletakkan nilai di sebelah kiri dan variabel di sebelah kanan:</p>
        <pre class="bg-slate-900 text-slate-100 p-2.5 rounded-xl text-xs font-mono my-1.5"><code># SALAH (SyntaxError: cannot assign to literal)
100 = skor

# BENAR:
skor = 100</code></pre>
        <p class="text-slate-600">Ingat kaidah: <strong>VARIABEL = NILAI</strong> (Variabel selalu di sebelah kiri!).</p>
    </div>

    <div class="p-4 bg-emerald-50/80 border-l-4 border-emerald-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-emerald-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-mobile-screen-button text-emerald-600"></i> 5. Tips Cepat Mengetik di HP
        </div>
        <p>Gunakan tombol pintasan simbol <code>=</code> pada toolbar atas editor untuk menghemat waktu penulisan penugasan variabel tanpa membuka simbol kedua keyboard HP.</p>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Manakah nama variabel berikut yang SAH dan mengikuti konvensi snake_case di Python?',
                'option_a' => '1_skor_maksimal',
                'option_b' => 'skor-maksimal',
                'option_c' => 'skor_maksimal',
                'option_d' => 'skor maksimal',
                'correct_option' => 'C',
                'explanation' => 'skor_maksimal sah karena menggunakan huruf kecil, pemisah garis bawah (_), tidak diawali angka, dan tidak memakai spasi atau tanda hubung (-).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode berikut:
x = 5
x = x + 10
print(x)
Berapakah output dari variabel x?',
                'option_a' => '5',
                'option_b' => '10',
                'option_c' => '15',
                'option_d' => 'Error',
                'correct_option' => 'C',
                'explanation' => 'Nilai awal x adalah 5. Baris kedua mengevaluasi sisi kanan: 5 + 10 = 15, lalu menyimpannya kembali ke variabel x.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang siswa menulis kode: nilai siswa = 90. Pesan error apakah yang akan dimunculkan oleh Python?',
                'option_a' => 'NameError karena nama siswa belum terdaftar',
                'option_b' => 'SyntaxError: invalid syntax karena nama variabel tidak boleh mengandung spasi',
                'option_c' => 'TypeError karena 90 bukan teks',
                'option_d' => 'ZeroDivisionError',
                'correct_option' => 'B',
                'explanation' => 'Python tidak mengizinkan spasi pada penamaan variabel. Spasi dianggap sebagai dua token kata terpisah sehingga memicu SyntaxError.',
                'xp_reward' => 20
            ]
        ]
    ],

    '1.4' => [
        'title' => '1.4 Tipe Data Primitif & Fungsi type()',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Wadah Minuman &amp; Makanan
        </div>
        <p>Anda tidak bisa menuangkan kuah bakso panas ke dalam kantong plastik tipis tanpa bocor, dan Anda tidak bisa memotong daging dengan sedotan es. Setiap benda memiliki wadah dan perlakuannya sendiri. Di Python, komputer perlu mengetahui <strong>Tipe Data</strong> agar tahu operasi apa yang bisa dilakukan terhadap data tersebut.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Mengenal 4 Tipe Data Primitif Utama
        </h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-700 font-bold">
                    <tr><th class="p-2.5">Tipe Data</th><th class="p-2.5">Keterangan</th><th class="p-2.5">Contoh di Python</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr><td class="p-2.5 font-bold font-mono text-blue-600">str (String)</td><td class="p-2.5">Teks dalam tanda kutip</td><td class="p-2.5 font-mono">"Wikrama", \'Ahmad\'</td></tr>
                    <tr><td class="p-2.5 font-bold font-mono text-emerald-600">int (Integer)</td><td class="p-2.5">Bilangan bulat tanpa desimal</td><td class="p-2.5 font-mono">16, 2026, -5</td></tr>
                    <tr><td class="p-2.5 font-bold font-mono text-amber-600">float</td><td class="p-2.5">Bilangan desimal (pecahan titik)</td><td class="p-2.5 font-mono">3.14, 85.5, -0.5</td></tr>
                    <tr><td class="p-2.5 font-bold font-mono text-purple-600">bool (Boolean)</td><td class="p-2.5">Kebenaran logika (True / False)</td><td class="p-2.5 font-mono">True, False</td></tr>
                </tbody>
            </table>
        </div>
        <p class="mt-2 text-slate-600">Gunakan fungsi bawaan <code>type(variabel)</code> untuk mengecek jenis tipe data variabel kapan saja.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>nama = "Inda"      # string (str)
usia = 17           # integer (int)
tinggi = 172.5      # float
punya_ktp = False   # boolean (bool)

print(type(nama))      # <class \'str\'>
print(type(usia))      # <class \'int\'>
print(type(tinggi))    # <class \'float\'>
print(type(punya_ktp)) # <class \'bool\'></code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Pemula
        </div>
        <p>Perbedaan tanda desimal: Di Indonesia kita biasa memakai koma (misal: <code>3,5</code>). Di Python, tanda koma akan membuat Python mengira itu adalah dua nilai (Tuple). Tanda desimal resmi di Python adalah <strong>TITIK</strong> (<code>3.5</code>)!</p>
    </div>

    <div class="p-4 bg-emerald-50/80 border-l-4 border-emerald-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-emerald-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-mobile-screen-button text-emerald-600"></i> 5. Tips Cepat Mengetik di HP
        </div>
        <p>Huruf awal pada nilai Boolean wajib kapital: <code>True</code> dan <code>False</code>. Menulis <code>true</code> huruf kecil akan menyebabkan NameError!</p>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Jika variabel a = "100" dan variabel b = 100, apakah tipe data dari kedua variabel tersebut secara berurutan?',
                'option_a' => 'Keduanya adalah int',
                'option_b' => 'a bertipe str, b bertipe int',
                'option_c' => 'a bertipe float, b bertipe int',
                'option_d' => 'Keduanya adalah str',
                'correct_option' => 'B',
                'explanation' => 'Nilai yang diapit tanda kutip ("100") selalu bertipe string (str), sedangkan angka murni tanpa kutip (100) bertipe integer (int).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Berapakah output dari kode berikut:
x = 7.0
print(type(x))',
                'option_a' => '<class \'int\'>',
                'option_b' => '<class \'str\'>',
                'option_c' => '<class \'float\'>',
                'option_d' => '<class \'number\'>',
                'correct_option' => 'C',
                'explanation' => 'Meskipun angka di belakang titik adalah nol (.0), adanya tanda titik desimal menandakan tipe data float.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang siswa menulis: status_hadir = true. Mengapa kode tersebut memicu NameError: name "true" is not defined?',
                'option_a' => 'Karena Python mewajibkan huruf kapital di awal untuk nilai Boolean: True',
                'option_b' => 'Karena status_hadir harus diapit tanda kutip',
                'option_c' => 'Karena Boolean dilarang disimpan di variabel',
                'option_d' => 'Karena tidak ada titik koma di akhir baris',
                'correct_option' => 'A',
                'explanation' => 'Konstanta Boolean resmi di Python adalah True dan False (dengan huruf T dan F kapital). Menulis true huruf kecil dianggap nama variabel yang belum didefinisikan.',
                'xp_reward' => 20
            ]
        ]
    ],

    '1.5' => [
        'title' => '1.5 Boss Challenge: Kartu Biodata Siswa',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-trophy text-amber-600"></i> 1. Uji Kompetensi Part 1: Kartu Biodata Mandiri
        </div>
        <p>Selamat telah menyelesaikan seluruh materi dasar di Part 1! Sekarang saatnya membuktikan penguasaan Anda: merancang program pembuatan kartu biodata siswa mandiri yang menggabungkan seluruh 4 tipe data primitif, komentar penjelasan, dan pencetakan terformat rapi.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-blue-600"></i> 2. Kriteria &amp; Komponen Wajib Proyek
        </h4>
        <ol class="list-decimal pl-5 space-y-1.5 text-slate-600">
            <li>Membuat variabel <code>nama_siswa</code> bertipe string (str).</li>
            <li>Membuat variabel <code>umur</code> bertipe integer (int).</li>
            <li>Membuat variabel <code>nilai_rata_rata</code> bertipe float.</li>
            <li>Membuat variabel <code>status_aktif</code> bertipe boolean (<code>True</code>).</li>
            <li>Mencetak kartu biodata berbingkai rapi menggunakan fungsi <code>print()</code>.</li>
        </ol>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Solusi
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Program Pembuat Kartu Biodata Siswa SMK
# Dibuat untuk memenuhi Tantangan Boss Part 1

nama_siswa = "Ahmad Fadhil"
umur = 16
nilai_rata_rata = 88.5
status_aktif = True

print("=" * 32)
print("      KARTU BIODATA SISWA       ")
print("=" * 32)
print("Nama   :", nama_siswa)
print("Umur   :", umur, "tahun")
print("Nilai  :", nilai_rata_rata)
print("Status : Aktif (", status_aktif, ")")
print("=" * 32)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Menyambung Teks dan Angka dengan Tanda Tambah (+)
        </div>
        <p>Jangan menulis <code>print("Nilai: " + nilai_rata_rata)</code> karena akan memicu <code>TypeError</code>. Gunakan pemisah koma <code>print("Nilai:", nilai_rata_rata)</code> agar Python otomatis menambahkan spasi dan mengonversinya secara aman.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Karakter garis pemisah panjang seperti <code>"=" * 32</code> adalah trik cerdas Python untuk mencetak 32 buah tanda sama dengan tanpa harus mengetiknya satu per satu di ponsel!</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Komponen apa saja yang wajib hadir dalam program Biodata Siswa yang baik?',
                'option_a' => 'Hanya fungsi print tanpa variabel sama sekali',
                'option_b' => 'Variabel penampung data (str, int, float, bool), komentar dokumentasi, dan fungsi print terformat',
                'option_c' => 'Perulangan while loop tak terhingga',
                'option_d' => 'Koneksi database SQL',
                'correct_option' => 'B',
                'explanation' => 'Program biodata mendasar di Part 1 mengintegrasikan variabel dari berbagai tipe data primitif, komentar penjelasan, dan pencetakan data ke layar.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Jika Anda ingin mencetak teks dan variabel sekaligus dalam satu perintah print:
nama = "Fadhil"
umur = 16
Manakah sintaks yang paling tepat?',
                'option_a' => 'print("Nama:", nama, "- Umur:", umur)',
                'option_b' => 'print "Nama:" nama "Umur:" umur',
                'option_c' => 'print("Nama:" + umur)',
                'option_d' => 'print(Nama, nama; Umur, umur)',
                'correct_option' => 'A',
                'explanation' => 'Fungsi print() dapat menerima banyak argumen yang dipisahkan tanda koma. Setiap nilai akan otomatis dicetak dengan pemisah spasi secara aman.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa perintah print("Nilai: " + 88.5) akan menghasilkan TypeError di Python?',
                'option_a' => 'Karena Python tidak mengizinkan penggabungan operator tambah (+) langsung antara String dan Float tanpa konversi',
                'option_b' => 'Karena angka 88.5 tidak boleh dicetak',
                'option_c' => 'Karena tanda titik dilarang di dalam tanda kurung',
                'option_d' => 'Karena nama variabel salah',
                'correct_option' => 'A',
                'explanation' => 'Operator tambah (+) pada string hanya bisa menggabungkan sesama string. Solusinya gunakan pemisah koma: print("Nilai:", 88.5) atau konversi str(88.5).',
                'xp_reward' => 20
            ]
        ]
    ]
];
