<?php
/**
 * PART 6: FUNGSI (FUNCTIONS) & MODULARITAS KODE
 * Sub-topik 6.1 s/d 6.5
 */
return [
    '6.1' => [
        'title' => '6.1 Mendefinisikan Fungsi dengan def',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Resep Rahasia yang Disimpan di Buku Catatan
        </div>
        <p>Bayangkan Anda seorang juru masak di restoran. Setiap kali ada pesanan "Nasi Goreng Spesial", Anda tidak perlu menulis ulang 20 langkah bumbu dari nol di selembar kertas baru. Anda cukup membuka resep bernama <strong>masak_nasi_goreng()</strong> yang sudah tersimpan rapi, lalu menjalankannya. Di pemrograman, <strong>Fungsi</strong> adalah resep kode yang kita bungkus sekali agar bisa dipanggil berkali-kali kapan pun dibutuhkan!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Anatomi Kata Kunci def &amp; Parameter
        </h4>
        <p>Fungsi dibuat menggunakan kata kunci <code>def</code> (singkatan dari <em>define</em>), diikuti nama fungsi, tanda kurung parameter, dan titik dua:</p>
        <pre class="bg-slate-900 text-slate-100 p-3 rounded-xl font-mono text-xs"><code>def nama_fungsi(parameter1, parameter2):
    # Blok kode yang akan dijalankan saat fungsi dipanggil (terindentasi)
    instruksi_kode</code></pre>
        <p class="mt-2 text-xs">Prinsip utama rekayasa perangkat lunak yang diwujudkan fungsi adalah <strong>DRY (Don\'t Repeat Yourself)</strong>: jangan pernah menuliskan kode yang sama berulang kali!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Mendefinisikan fungsi salam pembuka
def sapa_siswa(nama, jurusan):
    print("=" * 35)
    print(f"Halo, Selamat Datang {nama}!")
    print(f"Kompetensi Keahlian : {jurusan}")
    print("=" * 35)

# Memanggil fungsi berkali-kali dengan data berbeda
sapa_siswa("Ahmad Fadhil", "PPLG (RPL)")
sapa_siswa("Siti Nurhaliza", "Teknik Komputer & Jaringan")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Memanggil Fungsi Sebelum Didefinisikan
        </div>
        <p>Karena interpreter Python membaca kode dari atas ke bawah, Anda TIDAK BISA memanggil fungsi di baris atas jika blok <code>def</code>-nya baru ditulis di baris bawah. Hal itu akan memicu <code>NameError: name \'sapa_siswa\' is not defined</code>. Selalu tempatkan definisi fungsi di bagian atas file kode Anda!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Ketik <code>def</code> dengan mudah menggunakan tombol pintas di bilah simbol PyLMS. Pastikan selalu ada tanda titik dua <code>:</code> di akhir baris deklarasi fungsi.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Kata kunci (keyword) resmi apakah yang digunakan untuk mendeklarasikan fungsi baru di Python?',
                'option_a' => 'function',
                'option_b' => 'func',
                'option_c' => 'def',
                'option_d' => 'define',
                'correct_option' => 'C',
                'explanation' => 'Di Python, kata kunci resmi untuk membuat atau mendefinisikan fungsi adalah \'def\'.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
def hitung_luas_persegi(sisi):
    print(sisi * sisi)

hitung_luas_persegi(6)
Apakah output yang dihasilkan kode di atas?',
                'option_a' => '12',
                'option_b' => '36',
                'option_c' => '6',
                'option_d' => 'TypeError',
                'correct_option' => 'B',
                'explanation' => 'Parameter sisi diisi argumen 6. Di dalam fungsi dieksekusi print(6 * 6), menghasilkan output 36.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa kode berikut menghasilkan NameError saat dijalankan?
tampilkan_pesan()

def tampilkan_pesan():
    print("Halo Dunia")',
                'option_a' => 'Karena fungsi tidak memiliki parameter',
                'option_b' => 'Karena pemanggilan fungsi ditulis sebelum blok fungsi itu sendiri didefinisikan dengan def',
                'option_c' => 'Karena nama fungsi mengandung underscore',
                'option_d' => 'Karena fungsi tidak memiliki return',
                'correct_option' => 'B',
                'explanation' => 'Python mengeksekusi kode secara linier baris demi baris dari atas. Fungsi harus didefinisikan terlebih dahulu sebelum dapat dipanggil.',
                'xp_reward' => 20
            ]
        ]
    ],

    '6.2' => [
        'title' => '6.2 Nilai Kembalian (return) vs print()',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Koki Restoran vs Papan Pengumuman
        </div>
        <p>Bayangkan Anda memesan jus jeruk di kafe:
        <br>- <strong>Fungsi dengan return:</strong> Koki memeras jeruk di dapur, lalu <em>menyerahkan gelas jus jeruk itu ke tangan Anda</em> (return). Anda bebas meminumnya, mencampurnya dengan es, atau menyimpannya di ransel.
        <br>- <strong>Fungsi hanya dengan print():</strong> Koki memeras jeruk di dapur, lalu ia meminumnya sendiri sambil berteriak: <em>"Jusnya manis!"</em> (print). Anda sebagai pelanggan tidak mendapatkan apa-apa di tangan Anda!
        <br>Itulah perbedaan mutlak antara mencetak ke layar (print) vs mengembalikan nilai (return)!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Mengapa return Sangat Penting?
        </h4>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><code>return</code> mengirimkan nilai hasil perhitungan kembali ke baris pemanggil, sehingga hasilnya bisa <strong>disimpan di variabel</strong> atau <strong>dihitung lebih lanjut</strong> dalam rumus lain.</li>
            <li>Saat interpreter menemukan perintah <code>return</code>, eksekusi fungsi <strong>segera berhenti</strong> saat itu juga (perintah di bawah return tidak akan dijalankan).</li>
            <li>Jika sebuah fungsi tidak memiliki kata kunci <code>return</code>, fungsi tersebut secara diam-diam mengembalikan nilai <code>None</code>!</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># FUNGSI A (Pakai return): Nilainya bisa diolah lagi
def hitung_diskon(harga, persen):
    potongan = harga * (persen / 100)
    return potongan # Mengembalikan angka potongan

# FUNGSI B (Hanya print): Nilainya hilang di layar
def cetak_salam(nama):
    print(f"Selamat pagi, {nama}")
    # Tidak ada return, mengembalikan None otomatis

# Menggunakan hasil return untuk perhitungan total bayar
harga_baju = 150000
diskon_didapat = hitung_diskon(harga_baju, 20) # 30000
total_bayar = harga_baju - diskon_didapat

print(f"Harga Asli : Rp {harga_baju:,}")
print(f"Diskon     : Rp {diskon_didapat:,}")
print(f"Bayar      : Rp {total_bayar:,}")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Mengoperasikan Hasil print()
        </div>
        <p>Jika fungsi Anda hanya menggunakan <code>print()</code> dan Anda mencoba menjumlahkannya:
        <br><code class="text-rose-900 bg-rose-100 p-1 rounded font-mono text-xs">hasil = fungsi_print() + 10</code>
        <br>Python akan meledak dengan error: <code>TypeError: unsupported operand type(s) for +: \'NoneType\' and \'int\'</code>. Ingat: fungsi tanpa return bernilai None!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Aturan praktis: jika tujuannya menghitung sesuatu, selalu gunakan <code>return</code>. Gunakan <code>print()</code> hanya saat ingin menampilkan tulisan visual ke layar pengguna.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Apakah yang dikembalikan (return value) secara otomatis oleh sebuah fungsi Python jika di dalamnya sama sekali tidak ada kata kunci return?',
                'option_a' => 'Angka 0',
                'option_b' => 'Teks string kosong ""',
                'option_c' => 'Nilai None',
                'option_d' => 'Nilai False',
                'correct_option' => 'C',
                'explanation' => 'Di Python, fungsi apa pun yang tidak mengeksekusi pernyataan return secara eksplisit akan otomatis mengembalikan objek None.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
def kali_dua(n):
    return n * 2

total = kali_dua(5) + kali_dua(3)
print(total)
Berapakah output yang dicetak?',
                'option_a' => '16',
                'option_b' => '10',
                'option_c' => '8',
                'option_d' => 'TypeError',
                'correct_option' => 'A',
                'explanation' => 'kali_dua(5) mengembalikan 10. kali_dua(3) mengembalikan 6. total = 10 + 6 = 16.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang murid membuat fungsi:
def tambah(a, b):
    print(a + b)

x = tambah(4, 6)
y = x * 2
Mengapa pada baris \'y = x * 2\' terjadi TypeError: unsupported operand type for *: \'NoneType\' and \'int\'?',
                'option_a' => 'Karena x bernilai None, sebab fungsi tambah hanya menggunakan print() dan lupa mengembalikan nilai dengan return',
                'option_b' => 'Karena angka 4 dan 6 adalah integer',
                'option_c' => 'Karena perkalian dengan 2 dilarang',
                'option_d' => 'Karena fungsi tambah harus memiliki 3 parameter',
                'correct_option' => 'A',
                'explanation' => 'Karena fungsi tambah hanya memanggil print(a+b) tanpa return, pemanggilan fungsi menghasilkan None. Mengalikan None dengan angka 2 memicu TypeError.',
                'xp_reward' => 20
            ]
        ]
    ],

    '6.3' => [
        'title' => '6.3 Debugging: Variabel Lokal vs Global',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Buku Catatan Pribadi di Kamar vs Mading Sekolah
        </div>
        <p>Bayangkan dua papan tulis:
        <br>- <strong>Variabel Lokal:</strong> Papan tulis kecil di dalam kamar tidur pribadi Anda (di dalam fungsi). Orang luar dari koridor tidak bisa melihat apalagi membaca isi papan itu. Begitu kamar dibongkar, tulisan papan itu hilang.
        <br>- <strong>Variabel Global:</strong> Majalah Dinding (Mading) besar di lobi utama sekolah (di luar fungsi). Seluruh siswa dan guru di sekolah dapat melihat dan membaca isinya dari mana saja.
        <br>Inilah konsep <strong>Scope (Lingkup Variabel)</strong> dalam rekayasa perangkat lunak!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Aturan Cakupan Variabel (Scope)
        </h4>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Variabel Lokal:</strong> Dideklarasikan di dalam blok fungsi. Hanya hidup selama fungsi dieksekusi, dan HANYA bisa diakses dari dalam fungsi itu sendiri.</li>
            <li><strong>Variabel Global:</strong> Dideklarasikan di luar fungsi (di file utama). Dapat dibaca oleh semua fungsi.</li>
            <li>Jika ingin memodifikasi variabel global dari DALAM fungsi, Python mewajibkan deklarasi <code>global nama_variabel</code>. Namun, best practice merekomendasikan melewatkan data via parameter dan return alih-alih mengubah variabel global secara liar!</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Variabel Global (Bisa dibaca dari mana saja)
sekolah = "SMK Wikrama 1 Garut"

def tampilkan_info_siswa():
    # Variabel Lokal (Hanya ada di dalam fungsi ini)
    nama = "Ahmad Fadhil"
    kelas = "X PPLG"
    print(f"Siswa : {nama} ({kelas})")
    print(f"Asal  : {sekolah}") # Membaca variabel global aman!

tampilkan_info_siswa()

# Mencoba mengakses variabel lokal dari luar:
# print(nama) # ERROR! NameError: name \'nama\' is not defined</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: UnboundLocalError
        </div>
        <p>Jika Anda membaca dan mencoba mengubah variabel global di dalam fungsi tanpa kata kunci <code>global</code>:
        <br><code class="text-rose-900 bg-rose-100 p-1 rounded font-mono text-xs">UnboundLocalError: cannot access local variable \'skor\' where it is not associated with a value</code>
        <br>Python berasumsi Anda ingin membuat variabel lokal baru karena ada penugasan (=), namun bingung karena nilainya dibaca sebelum diisi.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Selalu kirim data ke dalam fungsi melalui <strong>parameter</strong>, dan bawa data keluar melalui <strong>return</strong>. Cara ini 100% aman dan membuat kode mudah dites di layar ponsel.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Di manakah variabel lokal dapat diakses di dalam program Python?',
                'option_a' => 'Di seluruh file dari baris mana pun',
                'option_b' => 'Hanya di dalam blok fungsi tempat variabel tersebut dideklarasikan',
                'option_c' => 'Hanya setelah perintah return',
                'option_d' => 'Hanya oleh fungsi yang memiliki kata kunci global',
                'correct_option' => 'B',
                'explanation' => 'Variabel lokal memiliki ruang lingkup terbatas (local scope) hanya di dalam blok fungsi tempat ia diciptakan.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
x = 10

def ubah():
    x = 20
    print("Dalam fungsi:", x)

ubah()
print("Luar fungsi:", x)
Berapakah nilai x yang dicetak di \'Luar fungsi\'?',
                'option_a' => '20',
                'option_b' => '10',
                'option_c' => 'None',
                'option_d' => 'Error: UnboundLocalError',
                'correct_option' => 'B',
                'explanation' => 'Baris x = 20 di dalam fungsi membuat variabel lokal x yang terpisah dari variabel global x. Di luar fungsi, nilai x global tetap 10.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa pemanggilan print(nama) di luar fungsi memicu NameError pada kode berikut?
def daftar():
    nama = "Wikrama"

daftar()
print(nama)',
                'option_a' => 'Karena fungsi daftar belum selesai dieksekusi',
                'option_b' => 'Karena variabel nama adalah variabel lokal yang telah dihancurkan dari memori setelah fungsi daftar() selesai berjalan',
                'option_c' => 'Karena nama variabel tidak boleh bertipe string',
                'option_d' => 'Karena fungsi tidak memiliki parameter',
                'correct_option' => 'B',
                'explanation' => 'Variabel lokal hanya hidup selama eksekusi fungsi berlangsung. Di luar fungsi, identifier nama tidak dikenal oleh interpreter.',
                'xp_reward' => 20
            ]
        ]
    ],

    '6.4' => [
        'title' => '6.4 Default Arguments & Multiple Returns',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Paket Makanan Cepat Saji dengan Minuman Default
        </div>
        <p>Bayangkan Anda memesan paket nasi ayam di kasir:
        <br>- Jika Anda tidak menyebutkan minuman, kasir otomatis memberi Anda <strong>Es Teh Manis</strong> (Nilai Default).
        <br>- Tetapi jika Anda minta khusus: <em>"Minumnya ganti Jus Mangga ya!"</em>, kasir akan menggantinya sesuai permintaan Anda.
        <br>- Dan saat pesanan jadi, nampan kasir mengembalikan dua piring sekaligus: <strong>[Ayam, Jus Mangga]</strong> (Multiple Return!).
        <br>Python mendukung kedua fitur hebat ini secara elegan!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Aturan Default Argument &amp; Multiple Return
        </h4>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Default Arguments:</strong> Memberikan nilai cadangan pada parameter fungsi menggunakan tanda sama dengan (misal <code>diskon=0.05</code>). Parameter ini menjadi opsional saat dipanggil.</li>
            <li><strong>Aturan Posisi:</strong> Parameter default WAJIB diletakkan di posisi paling belakang setelah parameter wajib!</li>
            <li><strong>Multiple Returns:</strong> Fungsi Python dapat mengembalikan banyak nilai sekaligus dipisahkan tanda koma: <code>return nilai1, nilai2</code> (dikembalikan dalam bentuk Tuple).</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Analisis Bangun Datar
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Fungsi dengan default argument (satuan default "cm")
# dan multiple returns (mengembalikan luas dan keliling sekaligus)
def hitung_persegi_panjang(panjang, lebar, satuan="cm"):
    luas = panjang * lebar
    keliling = 2 * (panjang + lebar)
    return luas, keliling, satuan

# Memanggil dengan nilai default (satuan otomatis "cm")
l1, k1, s1 = hitung_persegi_panjang(10, 5)
print(f"Persegi A : Luas = {l1} {s1}², Keliling = {k1} {s1}")

# Memanggil dengan mengubah default menjadi "meter"
l2, k2, s2 = hitung_persegi_panjang(8, 4, satuan="meter")
print(f"Persegi B : Luas = {l2} {s2}², Keliling = {k2} {s2}")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Menaruh Parameter Default di Depan
        </div>
        <p>Jangan pernah menulis parameter yang memiliki default value sebelum parameter biasa:
        <br><code class="text-rose-900 bg-rose-100 p-1 rounded font-mono text-xs">def hitung(diskon=0.1, harga): # SALAH BESAR!</code>
        <br>Python akan memunculkan <code>SyntaxError: non-default argument \'harga\' follows default argument</code>.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Teknik unpacking: <code>a, b = fungsi()</code> memungkinkan Anda menangkap hasil multiple return langsung ke dua variabel terpisah hanya dalam 1 baris kode praktis.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Di manakah parameter yang memiliki nilai bawaan (default argument) WAJIB diletakkan pada deklarasi fungsi?',
                'option_a' => 'Di urutan paling depan sebelum parameter lain',
                'option_b' => 'Di urutan paling belakang setelah semua parameter tanpa nilai default',
                'option_c' => 'Bebas di posisi mana saja',
                'option_d' => 'Di luar tanda kurung',
                'correct_option' => 'B',
                'explanation' => 'Aturan sintaks Python menetapkan bahwa non-default arguments tidak boleh mengikuti default arguments. Jadi default arguments selalu di posisi belakang.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan fungsi berikut:
def hitung_skor(poin, bonus=10):
    return poin + bonus

print(hitung_skor(50))
print(hitung_skor(50, 25))
Apakah dua angka output yang dicetak?',
                'option_a' => '50 dan 75',
                'option_b' => '60 dan 75',
                'option_c' => '60 dan 60',
                'option_d' => 'TypeError',
                'correct_option' => 'B',
                'explanation' => 'Pemanggilan pertama menggunakan bonus default (10) sehingga 50 + 10 = 60. Pemanggilan kedua mengganti bonus menjadi 25 sehingga 50 + 25 = 75.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa deklarasi fungsi berikut memicu SyntaxError saat kode diperiksa interpreter?
def buat_pesanan(diskon=0.05, menu, jumlah):
    pass',
                'option_a' => 'Karena ada kata kunci pass di dalamnya',
                'option_b' => 'Karena parameter default \'diskon=0.05\' diletakkan di depan parameter non-default \'menu\' dan \'jumlah\'',
                'option_c' => 'Karena nama fungsi mengandung underscore',
                'option_d' => 'Karena nilai 0.05 berupa pecahan float',
                'correct_option' => 'B',
                'explanation' => 'SyntaxError: non-default argument follows default argument muncul karena parameter wajib (menu dan jumlah) ditulis setelah parameter default (diskon).',
                'xp_reward' => 20
            ]
        ]
    ],

    '6.5' => [
        'title' => '6.5 Boss Challenge: Sistem Kasir Kafe Modular',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-trophy text-amber-600"></i> 1. Uji Kompetensi Part 6: Arsitektur Kasir Berbasis Modul Fungsi
        </div>
        <p>Di Boss Challenge kali ini, Anda ditantang menyusun program kasir kafe modern dengan standar arsitektur profesional. Tidak boleh ada perhitungan yang menumpuk di file utama! Seluruh logika harus dipecah menjadi fungsi-fungsi modular yang independen dan mudah diuji.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Rancangan Struktur Fungsi Modular
        </h4>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li><code>hitung_subtotal(harga, jumlah)</code>: Menghitung total kotor pesanan.</li>
            <li><code>hitung_diskon(subtotal, kode_promo="")</code>: Jika kode_promo == "WIKRAMA10", beri diskon 10%. Jika tidak, diskon 0.</li>
            <li><code>hitung_pajak(setelah_diskon, tarif_pajak=0.10)</code>: Menghitung pajak PB1 restoran (default 10%).</li>
            <li><code>cetak_struk_modular(...)</code>: Menerima seluruh angka hasil dan mencetak invoice rapi.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Solusi
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># 1. Modul Kalkulasi Subtotal
def hitung_subtotal(harga, jumlah):
    return harga * jumlah

# 2. Modul Evaluasi Diskon Member
def hitung_diskon(subtotal, kode_promo=""):
    if kode_promo.upper() == "WIKRAMA10":
        return subtotal * 0.10
    return 0

# 3. Modul Pajak Restoran (Default 10%)
def hitung_pajak(dasar_pengenaan, tarif=0.10):
    return dasar_pengenaan * tarif

# 4. Modul Format Struk
def cetak_struk(menu, qty, subtotal, diskon, pajak, total_akhir):
    print("=" * 36)
    print("       KAFE KELAS INDUSTRI       ")
    print("=" * 36)
    print(f"Item Pesanan : {menu}")
    print(f"Jumlah       : {qty} porsi")
    print(f"Subtotal     : Rp {subtotal:,}")
    print(f"Diskon Promo : Rp {int(diskon):,}")
    print(f"Pajak Resto  : Rp {int(pajak):,}")
    print("-" * 36)
    print(f"TOTAL AKHIR  : Rp {int(total_akhir):,}")
    print("=" * 36)

# === ALUR PROGRAM UTAMA ===
item = "Kopi Susu Senja"
harga_satuan = 25000
jumlah_beli = 4
promo = "WIKRAMA10"

# Memanggil modul secara berurutan
sub = hitung_subtotal(harga_satuan, jumlah_beli) # 100.000
disk = hitung_diskon(sub, promo)                 # 10.000
dpp = sub - disk                                 # 90.000
pjk = hitung_pajak(dpp)                          # 9.000
total = dpp + pjk                                # 99.000

cetak_struk(item, jumlah_beli, sub, disk, pjk, total)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Keunggulan Arsitektur Modular
        </div>
        <p>Jika besok pemerintah menaikkan pajak resto menjadi 11%, Anda cukup mengubah satu angka di parameter <code>tarif=0.11</code> pada fungsi <code>hitung_pajak()</code> tanpa perlu merombak ribuan baris kode program lainnya!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Gunakan penamaan fungsi yang jelas dengan kata kerja di depan (misal: <code>hitung_...</code>, <code>cetak_...</code>, <code>validasi_...</code>) agar kode sangat mudah dibaca.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Apakah keuntungan terbesar menyusun kode sistem kasir dalam bentuk fungsi-fungsi modular dibandingkan menuliskannya dalam satu file panjang?',
                'option_a' => 'Program akan berjalan lebih cepat seribu kali',
                'option_b' => 'Kode lebih mudah dirawat, mudah diperbaiki jika ada bug pada rumus tertentu, dan fungsi dapat digunakan kembali di program lain (reusable)',
                'option_c' => 'Ukuran file menjadi nol kilobyte',
                'option_d' => 'Program tidak lagi membutuhkan interpreter Python',
                'correct_option' => 'B',
                'explanation' => 'Prinsip modularitas membuat kode rapi, mudah di-debug secara parsial, dan dapat dipakai berulang kali tanpa duplikasi kode.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Jika subtotal belanja adalah Rp 100.000, diskon Rp 10.000 (sisa Rp 90.000), dan tarif pajak adalah 10% dari sisa belanja setelah diskon, berapakah nilai total_akhir yang harus dibayar konsumen?',
                'option_a' => 'Rp 100.000',
                'option_b' => 'Rp 99.000',
                'option_c' => 'Rp 90.000',
                'option_d' => 'Rp 81.000',
                'correct_option' => 'B',
                'explanation' => 'Dasar pengenaan pajak = 100.000 - 10.000 = 90.000. Pajak 10% = 9.000. Total akhir = 90.000 + 9.000 = Rp 99.000.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang programmer lupa menulis \'return\' pada fungsi hitung_diskon(). Apa akibat langsung yang terjadi pada variabel disk yang menampung pemanggilan fungsi tersebut?',
                'option_a' => 'disk akan bernilai 0',
                'option_b' => 'disk akan bernilai None, sehingga baris berikutnya yang melakukan pengurangan matematika (sub - disk) akan memicu TypeError',
                'option_c' => 'Program otomatis mengulang fungsi',
                'option_d' => 'disk akan bernilai True',
                'correct_option' => 'B',
                'explanation' => 'Fungsi tanpa return mengembalikan None. Operasi matematika sub - None akan memicu TypeError: unsupported operand type for -: \'int\' and \'NoneType\'.',
                'xp_reward' => 20
            ]
        ]
    ]
];
