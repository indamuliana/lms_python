<?php
/**
 * PART 4: PERULANGAN (LOOPING) & KONTROL ALUR
 * Sub-topik 4.1 s/d 4.5
 */
return [
    '4.1' => [
        'title' => '4.1 Perulangan for & Fungsi range()',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Pemanggilan Absensi Berhitung di Lapangan
        </div>
        <p>Bayangkan seorang komandan upacara di sekolah memberi instruksi: <em>"Siswa barisan depan, hitung dari 1 sampai 10, mulai!"</em>. Siswa berurutan menyebutkan: "Satu!", "Dua!", hingga "Sepuluh!". Anda tidak perlu menulis perintah print sepuluh kali secara manual. Di Python, <strong>for loop</strong> bersama fungsi <code>range()</code> bertindak sebagai komandan otomatis yang mengulang perintah dengan jumlah putaran yang terukur!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Anatomi Fungsi range(start, stop, step)
        </h4>
        <p>Fungsi <code>range()</code> menghasilkan deret urutan angka. Memiliki 3 parameter penting:</p>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><code>range(stop)</code>: Mulai dari <code>0</code> hingga <code>stop - 1</code>. Misal <code>range(5)</code> menghasilkan <code>0, 1, 2, 3, 4</code> (tepat 5 putaran).</li>
            <li><code>range(start, stop)</code>: Mulai dari <code>start</code> hingga <code>stop - 1</code>. Misal <code>range(1, 6)</code> menghasilkan <code>1, 2, 3, 4, 5</code>.</li>
            <li><code>range(start, stop, step)</code>: Melompat sebesar <code>step</code>. Misal <code>range(2, 11, 2)</code> menghasilkan bilangan genap: <code>2, 4, 6, 8, 10</code>.</li>
        </ul>
        <div class="mt-2 p-2.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-900 text-xs">
            <strong>Aturan Mutlak Python:</strong> Angka <code>stop</code> bersifat <em>eksklusif</em> (tidak diikutsertakan). Jika ingin berhenti di angka 10, batas stop harus ditulis <code>11</code>!
        </div>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Tabel Perkalian Otomatis
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Mencetak Tabel Perkalian 7 (dari 1 x 7 sampai 10 x 7)
angka = 7
print(f"=== TABEL PERKALIAN {angka} ===")

for i in range(1, 11):
    hasil = i * angka
    print(f"{i} x {angka} = {hasil}")

print("Perhitungan perkalian selesai!")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Kesalahan Off-by-One
        </div>
        <p>Kesalahan paling sering terjadi adalah saat programmer ingin mencetak angka 1 sampai 5, tetapi hanya menulis <code>range(1, 5)</code>. Program hanya akan berjalan sampai 4! Selalu tambahkan <code>+ 1</code> pada batas akhir bila ingin angka target ikut terproses.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Untuk membuat hitung mundur roket peluncuran, Anda cukup memberi nilai langkah negatif pada step: <code>range(10, 0, -1)</code>. Cobalah ketik di HP Anda!</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Berapa kali blok kode di dalam \'for i in range(6):\' akan dieksekusi?',
                'option_a' => '5 kali (angka 0 sampai 4)',
                'option_b' => '6 kali (angka 0 sampai 5)',
                'option_c' => '7 kali (angka 0 sampai 6)',
                'option_d' => 'Tidak tentu',
                'correct_option' => 'B',
                'explanation' => 'range(6) menghasilkan 6 angka berurutan dimulai dari 0 sampai 5. Jadi blok kode dijalankan tepat 6 kali.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode perulangan berikut:
for x in range(3, 15, 4):
    print(x, end=" ")
Apakah output yang dicetak ke layar?',
                'option_a' => '3 7 11 15',
                'option_b' => '3 7 11',
                'option_c' => '3 6 9 12',
                'option_d' => '4 8 12',
                'correct_option' => 'B',
                'explanation' => 'Mulai dari 3, lompat 4: 3 + 4 = 7, 7 + 4 = 11. Angka berikutnya adalah 15, namun karena 15 adalah batas stop (eksklusif), maka 15 tidak ikut dicetak. Output: 3 7 11.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang murid ingin mencetak angka 1 sampai 10:
for n in range(1, 10):
    print(n)
Ternyata angka 10 tidak muncul. Bagaimanakah cara memperbaikinya?',
                'option_a' => 'Ubah range(1, 10) menjadi range(1, 11)',
                'option_b' => 'Ubah range(1, 10) menjadi range(0, 10)',
                'option_c' => 'Tambahkan step: range(1, 10, 2)',
                'option_d' => 'Ganti fungsi range() dengan int()',
                'correct_option' => 'A',
                'explanation' => 'Batas akhir (stop) pada range() tidak diikutsertakan. Untuk mencetak hingga angka 10, batas akhir harus ditulis 11: range(1, 11).',
                'xp_reward' => 20
            ]
        ]
    ],

    '4.2' => [
        'title' => '4.2 Perulangan while & Kondisi Terminasi',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Pengisian Baterai Ponsel Pintar
        </div>
        <p>Saat Anda mencolokkan kabel charger ke HP Anda, sistem operasi menjalankan instruksi: <em>"SELAMA (while) persentase baterai masih kurang dari 100%, terus alirkan arus listrik dan tampilkan animasi petir!"</em>. Kita tidak tahu pasti berapa menit tepatnya baterai akan penuh, tetapi kita tahu PASTI kapan proses harus berhenti: saat baterai menyentuh angka 100%. Inilah hakikat perulangan <code>while</code>!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Tiga Pilar Wajib Perulangan while
        </h4>
        <p>Agar perulangan while berjalan teratur dan tidak macet, 3 elemen ini wajib ada:</p>
        <ol class="list-decimal pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Inisialisasi (Start):</strong> Nilai awal variabel counter sebelum perulangan dimulai (misal: <code>baterai = 95</code>).</li>
            <li><strong>Kondisi Terminasi:</strong> Pengujian logika yang dicek sebelum tiap putaran (misal: <code>while baterai &lt; 100:</code>).</li>
            <li><strong>Pembaruan Nilai (Update/Increment):</strong> Perubahan nilai variabel di dalam loop agar mendekati kondisi berhenti (misal: <code>baterai += 1</code>).</li>
        </ol>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Simulasi Pengisian Daya HP
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># 1. Inisialisasi awal
baterai = 96
print("Mengisi daya ponsel...")

# 2. Kondisi terminasi
while baterai < 100:
    print(f"Status Baterai: {baterai}% [Sedang Mengisi]")
    # 3. Update nilai (increment)
    baterai += 1

print(f"Status Baterai: {baterai}% [BATERAI PENUH! Cabut Charger]")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Lupa Menuliskan Increment
        </div>
        <p>Jika Anda lupa menuliskan baris <code>baterai += 1</code>, maka nilai baterai akan selamanya bernilai 96. Akibatnya, kondisi <code>96 &lt; 100</code> akan selalu bernilai <code>True</code> dan program akan mencetak teks tanpa henti sampai memori browser Anda penuh!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Operator gabungan <code>+= 1</code> adalah singkatan dari <code>variabel = variabel + 1</code>. Sangat hemat ketikan saat menulis kode di layar sentuh ponsel!</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Kapan perulangan while di Python akan menghentikan eksekusinya?',
                'option_a' => 'Saat baris kode mencapai 100 baris',
                'option_b' => 'Saat kondisi logika yang diuji bernilai False',
                'option_c' => 'Saat pengguna menekan sembarang tombol',
                'option_d' => 'Setelah 10 putaran secara otomatis',
                'correct_option' => 'B',
                'explanation' => 'Perulangan while mengevaluasi kondisi sebelum tiap putaran. Begitu kondisi bernilai False, loop akan langsung berhenti.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
hitungan = 1
while hitungan <= 3:
    print(hitungan, end=" ")
    hitungan += 1
Apakah output yang dihasilkan?',
                'option_a' => '1 2 3',
                'option_b' => '1 2 3 4',
                'option_c' => '0 1 2 3',
                'option_d' => '1 1 1 ... tak terhingga',
                'correct_option' => 'A',
                'explanation' => 'Putaran 1: cetak 1, hitungan jadi 2. Putaran 2: cetak 2, hitungan jadi 3. Putaran 3: cetak 3, hitungan jadi 4. Putaran 4: 4 <= 3 bernilai False, loop selesai.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Apa yang terjadi pada kode berikut?
angka = 5
while angka > 0:
    print("Hitung:", angka)
    # Tidak ada pengurangan angka',
                'option_a' => 'Program langsung berhenti tanpa output',
                'option_b' => 'Program mengalami Infinite Loop (perulangan tanpa henti) karena angka selamanya bernilai 5',
                'option_c' => 'Python otomatis menghentikan loop di angka 0',
                'option_d' => 'Muncul pesan TypeError',
                'correct_option' => 'B',
                'explanation' => 'Karena nilai angka tidak pernah diubah (decrement), kondisi angka > 0 selalu True, menciptakan infinite loop.',
                'xp_reward' => 20
            ]
        ]
    ],

    '4.3' => [
        'title' => '4.3 Debugging: Mengatasi Infinite Loop',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Mobil Berputar di Bundaran Tanpa Pintu Keluar
        </div>
        <p>Bayangkan Anda mengemudi di bundaran lalu lintas kota, tetapi semua plang jalan keluar ditutup. Anda akan berputar-putar di lingkaran tersebut selamanya sampai bensin habis! Di dunia komputasi, situasi ini disebut <strong>Infinite Loop</strong>. Komputer terus mengeksekusi instruksi berulang-ulang dengan kecepatan miliaran siklus per detik hingga membuat aplikasi freeze atau browser HP Anda hang!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Penyebab Umum Infinite Loop
        </h4>
        <p>Infinite loop hampir selalu disebabkan oleh salah satu dari 3 kesalahan logika berikut:</p>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Arah Langkah Terbalik:</strong> Nilai awal <code>x = 1</code>, kondisi <code>while x &gt; 0:</code> tapi Anda menulis <code>x += 1</code> (nilainya semakin membesar menjauhi 0).</li>
            <li><strong>Kondisi Pertidaksamaan Tepat:</strong> Menggunakan <code>while x != 10:</code> dengan kenaikan <code>x += 2</code> dimulai dari angka ganjil (<code>1, 3, 5, 7, 9, 11...</code>) sehingga angka 10 terlewati selamanya!</li>
            <li><strong>Kondisi Statis:</strong> Menuliskan <code>while True:</code> tanpa menyertakan perintah <code>break</code> di dalamnya.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Cara Mencegah &amp; Memperbaiki
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># CONTOH KODE RUSAK (Jangan dijalankan):
# n = 1
# while n != 10:
#     n += 2  # n akan jadi 1, 3, 5, 7, 9, 11 (melewati 10!)

# PERBAIKAN AMAN:
# Gunakan operator lebih kecil (<) alih-alih tidak sama dengan (!=)
n = 1
while n < 10:
    print(f"Langkah n: {n}")
    n += 2

print(f"Selesai dengan aman di n = {n}")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Apa yang Harus Dilakukan Jika Terjebak Infinite Loop di HP?
        </div>
        <p>Jika Anda tidak sengaja memicu infinite loop di HP dan tombol browser tidak merespon:
        <br>1. Jangan panik, ketuk tombol <strong>Stop / Interupt</strong> merah di samping tombol Run.
        <br>2. Jika tab browser membeku (freeze), muat ulang (refresh) halaman peramban Anda.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Biasakan selalu menggunakan perbandingan rentang batas seperti <code>&lt;</code> atau <code>&lt;=</code> daripada <code>!=</code> agar perulangan tetap berhenti meski angka melompat melebihi batas.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Mengapa kondisi \'while x != 10:\' dengan pembaruan \'x += 3\' yang dimulai dari x = 0 berbahaya?',
                'option_a' => 'Karena angka x bernilai desimal',
                'option_b' => 'Karena x akan bernilai 0, 3, 6, 9, 12... sehingga angka 10 terlewati dan loop tidak pernah berhenti',
                'option_c' => 'Karena operator += tidak didukung dalam while',
                'option_d' => 'Karena angka 10 adalah bilangan genap',
                'correct_option' => 'B',
                'explanation' => 'Nilai x melompati angka 10 (dari 9 langsung ke 12), sehingga kondisi x != 10 akan selalu bernilai True selamanya.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode berikut:
a = 10
while a > 0:
    a -= 4
print(a)
Berapakah nilai akhir a yang dicetak setelah loop selesai?',
                'option_a' => '0',
                'option_b' => '-2',
                'option_c' => '2',
                'option_d' => 'Loop tidak pernah selesai',
                'correct_option' => 'B',
                'explanation' => 'Putaran 1: a = 10 - 4 = 6. Putaran 2: a = 6 - 4 = 2. Putaran 3: a = 2 - 4 = -2. Pada putaran berikutnya -2 > 0 bernilai False, loop berhenti dan mencetak -2.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Manakah strategi terbaik untuk mencegah risiko terjadinya infinite loop pada perulangan while?',
                'option_a' => 'Selalu menggunakan operator \'==\' pada kondisi terminasi',
                'option_b' => 'Memastikan ada variabel counter yang bergerak mendekati kondisi batas dan menggunakan batas batas perbandingan (< atau <=)',
                'option_c' => 'Tidak pernah menggunakan variabel di dalam kondisi',
                'option_d' => 'Menghapus tanda titik dua di akhir header while',
                'correct_option' => 'B',
                'explanation' => 'Memastikan variabel counter diperbarui ke arah titik henti dan memakai perbandingan batas (< atau <=) adalah metode teraman.',
                'xp_reward' => 20
            ]
        ]
    ],

    '4.4' => [
        'title' => '4.4 Kontrol Loop: break & continue',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Rem Darurat Kereta vs Antrian VIP Fast-Track
        </div>
        <p>Bayangkan dua situasi di perjalanan kereta komuter:
        <br><strong>BREAK (Rem Darurat):</strong> Tiba-tiba ada pohon tumbang di rel depan. Masinis langsung menarik rem darurat; seluruh perjalanan kereta <strong>berhenti total saat itu juga</strong>.
        <br><strong>CONTINUE (Fast-Track):</strong> Di antrian loket tiket, petugas melihat pengunjung dengan tiket VIP digital. Petugas berkata: <em>"Bapak lewat jalur samping saja!"</em>. Loket tidak tutup, petugas langsung <strong>meloncat ke pengunjung antrian berikutnya</strong>.
        <br>Inilah dua pengendali sakti alur perulangan di Python!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Perbedaan Kunci: break vs continue
        </h4>
        <ul class="list-disc pl-5 space-y-2 mt-2 text-slate-600">
            <li>
                <strong>break:</strong> Menghentikan perulangan secara permanen dan langsung melompat keluar dari seluruh blok perulangan ke baris kode setelahnya.
            </li>
            <li>
                <strong>continue:</strong> Menghentikan <em>putaran yang sedang berjalan</em>, melewatkan baris kode di bawahnya, dan langsung melompat ke awal putaran berikutnya.
            </li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># CONTOH 1: CONTINUE (Melewatkan angka genap untuk cetak ganjil saja)
print("Daftar Angka Ganjil:")
for i in range(1, 10):
    if i % 2 == 0:
        continue # Lompat langsung ke angka berikutnya jika genap
    print(i, end=" ") # Hanya tercetak: 1 3 5 7 9

print("\n" + "-" * 30)

# CONTOH 2: BREAK (Mencari nomor undian pemenang lalu berhenti)
target_juara = 7
for nomor in range(1, 20):
    if nomor == target_juara:
        print(f"Nomor Pemenang {nomor} Ditemukan! Hentikan pencarian.")
        break
    print(f"Memeriksa kupon #{nomor}...")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Menulis break di Luar Perulangan
        </div>
        <p>Perintah <code>break</code> dan <code>continue</code> HANYA boleh ditulis di dalam struktur perulangan (<code>for</code> atau <code>while</code>). Menulis <code>break</code> di luar loop (misalnya di dalam <code>if</code> biasa tanpa ada loop di atasnya) akan menyebabkan <code>SyntaxError: \'break\' outside loop</code>.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Kombinasi <code>while True:</code> dengan kondisi <code>if input == "selesai": break</code> adalah pola arsitektur paling populer untuk membuat menu aplikasi interaktif di perangkat bergerak!</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Pernyataan manakah yang paling akurat menggambarkan efek dari kata kunci \'continue\'?',
                'option_a' => 'Mengakhiri seluruh perulangan saat itu juga',
                'option_b' => 'Melewatkan sisa instruksi pada putaran saat ini dan langsung melanjutkan ke iterasi putaran berikutnya',
                'option_c' => 'Mengulangi putaran yang sama dari awal tanpa mengubah counter',
                'option_d' => 'Menghapus variabel di memori',
                'correct_option' => 'B',
                'explanation' => 'continue tidak mematikan loop, melainkan hanya melompati sisa kode pada putaran aktif tersebut dan lanjut ke putaran berikutnya.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
for k in range(1, 6):
    if k == 3:
        continue
    if k == 5:
        break
    print(k, end=" ")
Apakah output yang dicetak?',
                'option_a' => '1 2 4',
                'option_b' => '1 2 3 4',
                'option_c' => '1 2 4 5',
                'option_d' => '1 2',
                'correct_option' => 'A',
                'explanation' => 'k=1 dicetak. k=2 dicetak. k=3 dilewati oleh continue. k=4 dicetak. k=5 memicu break sehingga loop berhenti sebelum mencetak 5. Hasil: 1 2 4.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa kode berikut memicu SyntaxError saat dijalankan?
nilai = 80
if nilai >= 75:
    print("Lulus")
    break',
                'option_a' => 'Karena nilai bertipe integer',
                'option_b' => 'Karena kata kunci break hanya boleh digunakan di dalam struktur perulangan (for atau while)',
                'option_c' => 'Karena tidak ada blok else',
                'option_d' => 'Karena indentasi print salah',
                'correct_option' => 'B',
                'explanation' => 'SyntaxError: \'break\' outside loop muncul karena break diletakkan di dalam percabangan if murni tanpa berada di dalam struktur perulangan.',
                'xp_reward' => 20
            ]
        ]
    ],

    '4.5' => [
        'title' => '4.5 Boss Challenge: Simulator Kasir Perulangan',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-trophy text-amber-600"></i> 1. Uji Kompetensi Part 4: Mesin Kasir Minimarket Cerdas
        </div>
        <p>Di Boss Challenge kali ini, Anda ditantang membuat simulator kasir toko yang dapat menerima input harga barang belanjaan secara terus-menerus tanpa batas menggunakan perulangan <code>while True</code> sampai kasir mengetikkan angka <code>0</code> untuk mengakhiri transaksi, lalu menghitung total belanja dan diskon!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Ketentuan &amp; Aturan Bisnis Kasir
        </h4>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li>Pengguna memasukkan harga barang satu per satu.</li>
            <li>Jika harga diinput <code>0</code>, perulangan berhenti (<code>break</code>).</li>
            <li>Jika pengguna tidak sengaja memasukkan angka negatif, abaikan dengan peringatan dan lanjutkan (<code>continue</code>).</li>
            <li>Jika total belanja &gt;= Rp 100.000, berikan diskon otomatis sebesar 10%.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Solusi
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>print("=== SIMULATOR KASIR TOKO WIKRAMA ===")
print("Ketik angka 0 jika belanjaan sudah selesai diinput.\n")

total_belanja = 0
jumlah_item = 0

while True:
    harga = int(input(f"Harga barang ke-{jumlah_item + 1}: Rp "))
    
    # Syarat berhenti
    if harga == 0:
        break
        
    # Validasi harga negatif
    if harga < 0:
        print("[!] Harga tidak valid, ulangi input!")
        continue
        
    total_belanja += harga
    jumlah_item += 1

# Perhitungan diskon
diskon = 0
if total_belanja >= 100000:
    diskon = total_belanja * 0.10

total_bayar = total_belanja - diskon

print("\n" + "=" * 32)
print("STRUK PEMBELIAN")
print(f"Total Item    : {jumlah_item} barang")
print(f"Subtotal      : Rp {total_belanja:,}")
print(f"Diskon (10%)  : Rp {int(diskon):,}")
print(f"TOTAL AKHIR   : Rp {int(total_bayar):,}")
print("=" * 32)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Meletakkan Inisialisasi Total di Dalam Loop
        </div>
        <p>Kesalahan fatal yang sering terjadi: meletakkan baris <code>total_belanja = 0</code> di DALAM blok perulangan <code>while</code>. Jika itu terjadi, nilai total belanja akan selalu ter-reset kembali ke 0 setiap kali barang baru dimasukkan!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Format ribuan rapi seperti <code>Rp 100,000</code> dapat dibuat instan dengan menambahkan <code>:,</code> di dalam f-string: <code>{total_bayar:,}</code>.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Di manakah posisi yang benar untuk mendeklarasikan variabel akumulator seperti \'total_belanja = 0\'?',
                'option_a' => 'Di dalam perulangan while paling atas',
                'option_b' => 'Di luar SEBELUM perulangan while dimulai',
                'option_c' => 'Setelah kata kunci break',
                'option_d' => 'Di dalam blok continue',
                'correct_option' => 'B',
                'explanation' => 'Variabel penampung akumulasi wajib diinisialisasi sebelum loop dimulai, agar nilainya terus bertambah dan tidak di-reset ulang ke 0 pada setiap putaran.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Seorang kasir memasukkan harga berturut-turut: 40000, 70000, lalu 0. Berapakah total yang harus dibayar konsumen setelah mendapatkan diskon 10% (karena belanja >= 100.000)?',
                'option_a' => 'Rp 110.000',
                'option_b' => 'Rp 99.000',
                'option_c' => 'Rp 100.000',
                'option_d' => 'Rp 88.000',
                'correct_option' => 'B',
                'explanation' => 'Total belanja = 40.000 + 70.000 = 110.000. Diskon 10% = 11.000. Total bayar = 110.000 - 11.000 = Rp 99.000.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Perhatikan skenario di mana pengguna memasukkan angka negatif (-5000) lalu program mengeksekusi \'continue\'. Mengapa input negatif tersebut tidak diakumulasikan ke total_belanja?',
                'option_a' => 'Karena Python melarang angka negatif',
                'option_b' => 'Karena perintah continue langsung melompati baris total_belanja += harga dan kembali ke awal loop',
                'option_c' => 'Karena variabel harga otomatis terhapus',
                'option_d' => 'Karena program langsung memanggil break',
                'correct_option' => 'B',
                'explanation' => 'Instruksi continue melewatkan baris-baris di bawahnya (yaitu akumulasi harga dan jumlah item), sehingga data tidak valid aman dilewati.',
                'xp_reward' => 20
            ]
        ]
    ]
];
