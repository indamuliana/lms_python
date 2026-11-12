<?php
/**
 * PART 7: HANDLING ERROR, FILE I/O & MODUL STANDAR
 * Sub-topik 7.1 s/d 7.5
 */
return [
    '7.1' => [
        'title' => '7.1 Penanganan Error: try & except',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Sabuk Pengaman & Kantong Udara Mobil
        </div>
        <p>Saat Anda mengendarai mobil di jalan raya, Anda tidak bisa menjamin 100% tidak akan pernah ada lubang atau pengereman mendadak. Karena itu mobil dilengkapi sabuk pengaman dan airbag. Jika terjadi benturan, sistem pengaman aktif melindungi penumpang sehingga perjalanan tidak berakhir fatal. Di Python, blok <strong>try & except</strong> adalah airbag penyelamat agar aplikasi Anda tidak mendadak mati (crash) saat terjadi kesalahan input pengguna!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Mekanisme try &amp; except
        </h4>
        <p>Blok penanganan eksepsi (Exception Handling) bekerja dengan prinsip:</p>
        <ol class="list-decimal pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><code>try:</code> Jalankan blok kode yang berisiko menimbulkan error (misalnya konversi input angka atau pembagian matematika).</li>
            <li><code>except NamaError:</code> Jika error tersebut terjadi di dalam blok try, alihkan eksekusi ke blok ini untuk menampilkan pesan peringatan ramah tanpa mematikan program!</li>
            <li><code>finally:</code> (Opsional) Blok yang SELALU dieksekusi baik saat program sukses maupun saat terjadi error.</li>
        </ol>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Input Angka Anti-Crash
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>print("=== KALKULATOR PEMBAGIAN AMAN ===")

try:
    angka1 = float(input("Masukkan angka pembilang: "))
    angka2 = float(input("Masukkan angka penyebut : "))
    
    hasil = angka1 / angka2
    print(f"Hasil Pembagian: {hasil:.2f}")

except ValueError:
    print("[ERROR] Input tidak valid! Anda harus memasukkan angka, bukan huruf.")

except ZeroDivisionError:
    print("[ERROR] Kesalahan fatal: Angka penyebut tidak boleh nol (0)!")

print("Program selesai dengan aman tanpa crash.")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: "Bare Except" yang Terlalu Luas
        </div>
        <p>Jangan menuliskan <code>except:</code> kosong tanpa menyebutkan tipe errornya. Hal itu disebut <em>Bare Except</em> dan merupakan bad practice karena akan menangkap semua hal termasuk perintah penghentian keyboard (Ctrl+C), sehingga membuat kode sulit di-debug!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Kombinasikan perulangan <code>while True</code> dengan <code>try-except</code> untuk membuat input yang otomatis meminta pengguna mengetik ulang hingga inputnya benar.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Apakah tujuan utama penggunaan blok try dan except dalam aplikasi Python modern?',
                'option_a' => 'Membuat kode berjalan lebih cepat secara otomatis',
                'option_b' => 'Menangkap dan menangani potensi kesalahan (runtime error) secara anggun agar program tidak berhenti mendadak (crash)',
                'option_c' => 'Mengubah tipe data string menjadi integer',
                'option_d' => 'Menyimpan kode ke database MySQL',
                'correct_option' => 'B',
                'explanation' => 'try-except berfungsi sebagai jaring pengaman untuk menangkap runtime exception sehingga program tetap berjalan stabil.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode berikut:
try:
    x = int("seratus")
    print("Sukses")
except ValueError:
    print("Tangkap Error")
Apakah teks yang dicetak di terminal?',
                'option_a' => 'Sukses',
                'option_b' => 'Tangkap Error',
                'option_c' => 'ValueError: invalid literal',
                'option_d' => 'None',
                'correct_option' => 'B',
                'explanation' => 'int("seratus") memicu ValueError. Blok except ValueError langsung menangkapnya dan mencetak "Tangkap Error", baris print("Sukses") dilewati.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Tipe exception apakah yang harus ditangkap jika pengguna memasukkan angka 0 pada operasi pembagian (misal: 100 / 0)?',
                'option_a' => 'TypeError',
                'option_b' => 'IndexError',
                'option_c' => 'ZeroDivisionError',
                'option_d' => 'KeyError',
                'correct_option' => 'C',
                'explanation' => 'Membagi angka dengan 0 di Python secara spesifik memicu ZeroDivisionError.',
                'xp_reward' => 20
            ]
        ]
    ],

    '7.2' => [
        'title' => '7.2 Menulis File: open() Mode w & a',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Buku Catatan Fisik vs Whiteboard
        </div>
        <p>Bayangkan Anda mencatat di ruang kelas:
        <br>- <strong>Mode \'w\' (Write / Overwrite):</strong> Seperti menghapus total seluruh tulisan di papan tulis whiteboard, lalu menulis kalimat baru di atas papan yang sudah bersih. Catatan kemarin hilang!
        <br>- <strong>Mode \'a\' (Append):</strong> Seperti menulis di halaman buku harian. Anda tidak merobek halaman kemarin; Anda hanya menambahkan baris tulisan baru di baris paling bawah.
        <br>Di Python, fungsi <code>open()</code> memungkinkan program Anda menyimpan data secara permanen ke dalam file fisik di media penyimpanan!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Standar Industri: Konteks Manajer \'with open\'
        </h4>
        <p>Jangan pernah membuka file dengan <code>f = open(...)</code> lalu lupa menutupnya dengan <code>f.close()</code>. Standar resmi Python menggunakan statement <strong>with</strong>:</p>
        <pre class="bg-slate-900 text-slate-100 p-3 rounded-xl font-mono text-xs"><code>with open("nama_file.txt", "mode", encoding="utf-8") as f:
    f.write("Teks yang ingin disimpan\n")</code></pre>
        <p class="mt-2 text-xs">Blok <code>with</code> menjamin file akan ditutup secara otomatis oleh sistem operasi segera setelah blok selesai, bahkan jika terjadi error sekalipun!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Buku Log Presensi Kelas
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Mode "a" (Append): Menambahkan catatan tanpa menghapus data lama
nama_siswa = "Ahmad Fadhil"
status = "Hadir Tepat Waktu"

with open("presensi.txt", "a", encoding="utf-8") as file:
    file.write(f"Siswa: {nama_siswa} | Status: {status}\n")

print("Catatan presensi berhasil disimpan ke dalam file!")

# Membaca isi file yang baru saja ditulis
print("\n=== ISI FILE PRESENSI.TXT ===")
with open("presensi.txt", "r", encoding="utf-8") as file:
    isi = file.read()
    print(isi)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Bahaya: Salah Pakai Mode \'w\'
        </div>
        <p>Hati-hati! Jika Anda ingin menambahkan baris catatan baru pada buku kas atau presensi tetapi tidak sengaja menggunakan mode <code>"w"</code> (write), SELURUH isi file yang lama akan terhapus seketika tanpa peringatan konfirmasi. Selalu gunakan mode <code>"a"</code> untuk pencatatan log bertahap!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Ingat selalu menambahkan karakter baris baru <code>\n</code> di ujung teks <code>f.write("...\n")</code> agar catatan berikutnya berpindah ke baris di bawahnya.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Mode apakah yang harus dipilih pada fungsi open() jika Anda ingin MENAMBAHKAN teks baru di akhir file tanpa menghapus data yang sudah ada sebelumnya?',
                'option_a' => '"w" (Write)',
                'option_b' => '"a" (Append)',
                'option_c' => '"r" (Read)',
                'option_d' => '"x" (Exclusive)',
                'correct_option' => 'B',
                'explanation' => 'Mode "a" (append) membuka file untuk penulisan dengan menempatkan kursor di akhir file, sehingga data lama tetap utuh terjaga.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Mengapa penggunaan pernyataan \'with open(...) as f:\' sangat dianjurkan daripada pemanggilan open() biasa?',
                'option_a' => 'Karena with open membuat teks otomatis terenkripsi',
                'option_b' => 'Karena pernyataan with menjamin file akan ditutup secara otomatis (auto-close) dengan aman meskipun terjadi runtime error',
                'option_c' => 'Karena with open tidak memerlukan harddisk',
                'option_d' => 'Karena fungsi f.write() hanya bekerja di dalam with',
                'correct_option' => 'B',
                'explanation' => 'Context manager with open(...) menangani pembersihan resource file secara otomatis (memanggil f.close()) saat blok selesai.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Apa yang terjadi jika Anda membuka file bernama "catatan.txt" yang sudah berisi 100 baris menggunakan mode open("catatan.txt", "w")?',
                'option_a' => 'Teks baru akan ditambahkan di baris 101',
                'option_b' => 'Seluruh 100 baris isi file lama akan terhapus dan ditimpa dari awal',
                'option_c' => 'Python memicu FileExistsError',
                'option_d' => 'Program akan membaca file tersebut',
                'correct_option' => 'B',
                'explanation' => 'Mode "w" (write) memotong (truncate) panjang file menjadi 0 bytes, sehingga seluruh konten sebelumnya terhapus permanen.',
                'xp_reward' => 20
            ]
        ]
    ],

    '7.3' => [
        'title' => '7.3 Debugging: Mengatasi FileNotFoundError',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Mengambil Buku dari Rak yang Belum Pernah Dibeli
        </div>
        <p>Bayangkan Anda menyuruh adik Anda: <em>"Tolong ambilkan novel Harry Potter jilid 8 di rak kamar!"</em>. Adik Anda mencari ke seluruh sudut rak dan tidak menemukannya karena buku itu memang belum pernah Anda beli. Adik Anda akan berteriak: "Bukunya tidak ada sama sekali!". Di Python, situasi ini memicu <strong>FileNotFoundError: [Errno 2] No such file or directory</strong>!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Mengapa FileNotFoundError Terjadi?
        </h4>
        <p>Error ini terjadi ketika Anda mencoba membuka file dalam mode baca <code>"r"</code>, namun:</p>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li>File tersebut memang belum pernah dibuat sebelumnya.</li>
            <li>Ada salah ketik (typo) pada nama file atau ekstensi (misal: <code>data.txt</code> diketik <code>dataa.txt</code>).</li>
            <li>Nama file sensitif terhadap huruf besar dan kecil (case-sensitive) di Linux/Android/Hosting (misal: <code>Nilai.txt</code> vs <code>nilai.txt</code>).</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Cara Membaca File yang Kebal Error
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>nama_berkas = "pengaturan.txt"

# Membaca dengan proteksi try-except FileNotFoundError
try:
    with open(nama_berkas, "r", encoding="utf-8") as f:
        konten = f.read()
        print("Isi Konfigurasi:")
        print(konten)

except FileNotFoundError:
    print(f"[PERINGATAN] File {nama_berkas} belum ditemukan!")
    print("Membuat file konfigurasi baru dengan nilai default...")
    
    # Otomatis buat file baru jika belum ada
    with open(nama_berkas, "w", encoding="utf-8") as f:
        f.write("mode_gelap=True\nukuran_font=14\n")
    print("File baru berhasil dibuat otomatis!")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Perbedaan Mode \'r\' vs \'w\' Terhadap File yang Belum Ada
        </div>
        <p>Mode <code>"w"</code> dan <code>"a"</code> akan <strong>secara otomatis membuatkan file baru</strong> jika file yang diminta belum ada. Sebaliknya, mode <code>"r"</code> (read) <strong>WAJIB menuntut file sudah ada</strong> terlebih dahulu, atau ia akan memicu FileNotFoundError.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Selalu gunakan huruf kecil (lowercase) untuk penamaan file data seperti <code>data_siswa.txt</code> guna menghindari masalah perbedaan sistem operasi.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Kondisi manakah yang menyebabkan fungsi open("laporan.txt", "r") memicu pesan FileNotFoundError?',
                'option_a' => 'Karena file laporan.txt berisi terlalu banyak teks',
                'option_b' => 'Karena file "laporan.txt" belum ada di direktori kerja yang ditentukan saat dibuka dalam mode baca (read)',
                'option_c' => 'Karena file laporan.txt bertipe teks',
                'option_d' => 'Karena mode "r" hanya untuk file gambar',
                'correct_option' => 'B',
                'explanation' => 'Mode "r" mewajibkan file target sudah ada di storage. Jika tidak ditemukan, Python melempar FileNotFoundError.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
try:
    with open("database_rahasia.txt", "r") as f:
        print(f.read())
except FileNotFoundError:
    print("Cadangan diaktifkan")
Jika file database_rahasia.txt tidak ditemukan di disk, apakah yang tercetak?',
                'option_a' => 'Error fatal dan program crash',
                'option_b' => 'Cadangan diaktifkan',
                'option_c' => 'None',
                'option_d' => 'Isi file kosong',
                'correct_option' => 'B',
                'explanation' => 'Blok except FileNotFoundError menangkap eksepsi ketiadaan file dan mengeksekusi print("Cadangan diaktifkan") secara aman.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mode penulisan file manakah yang secara otomatis membuatkan file baru jika file yang dituju belum ada di penyimpanan?',
                'option_a' => 'Hanya mode "r"',
                'option_b' => 'Mode "w" dan mode "a"',
                'option_c' => 'Hanya mode "rb"',
                'option_d' => 'Tidak ada mode yang bisa membuat file baru otomatis',
                'correct_option' => 'B',
                'explanation' => 'Baik mode "w" (write) maupun "a" (append) akan membuat file baru jika file dengan nama tersebut belum eksis di disk.',
                'xp_reward' => 20
            ]
        ]
    ],

    '7.4' => [
        'title' => '7.4 Modul Standar: math, random & datetime',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Kotak Perkakas Tukang Serbaguna
        </div>
        <p>Seorang tukang kayu profesional yang ingin membangun rumah tidak perlu menambang bijih besi untuk membuat palu dan gergaji sendiri. Ia cukup membuka kotak perkakasnya, mengambil obeng dan meteran, lalu langsung bekerja. Filosofi Python dikenal dengan sebutan <strong>"Batteries Included"</strong>: Python sudah menyediakan ratusan modul bawaan siap pakai untuk matematika, angka acak, tanggal, dan internet tanpa perlu menginstal aplikasi pihak ketiga!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Tiga Modul Bawaan Paling Populer
        </h4>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>import math:</strong> Fungsi matematika lanjutan: <code>math.sqrt(x)</code> (akar kuadrat), <code>math.ceil(x)</code> (pembulatan ke atas), <code>math.pi</code> (konstanta pi 3.14159...).</li>
            <li><strong>import random:</strong> Generator angka & pilihan acak: <code>random.randint(1, 6)</code> (lempar dadu 1-6), <code>random.choice(list)</code> (memilih satu pemenang undian secara acak).</li>
            <li><strong>import datetime:</strong> Mengambil tanggal, jam, menit, detik saat ini: <code>datetime.datetime.now()</code>.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Generator Kuis &amp; Struk
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>import math
import random
import datetime

# 1. Matematika
akar = math.sqrt(81)
print(f"Akar kuadrat dari 81 adalah: {akar:.0f}")

# 2. Pengacakan (Simulasi Undian Juara Siswa)
siswa = ["Fadhil", "Siti", "Budi", "Zahra", "Rian"]
pemenang = random.choice(siswa)
dadu = random.randint(1, 6)
print(f"Pemenang undian hari ini: {pemenang} (Dadu #{dadu})")

# 3. Tanggal & Waktu Sekarang
sekarang = datetime.datetime.now()
format_waktu = sekarang.strftime("%d-%m-%Y %H:%M:%S")
print(f"Waktu Transaksi : {format_waktu}")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Fatal: "Module Shadowing"
        </div>
        <p>JANGAN PERNAH menamai file skrip Python Anda sama dengan nama modul bawaan, misalnya menamai file latihan Anda <code>random.py</code> atau <code>math.py</code>! Saat Anda menulis <code>import random</code>, Python akan mengimpor file latihan Anda sendiri alih-alih modul bawaan sistem, menyebabkan error <code>AttributeError: module has no attribute \'randint\'</code>.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Letakkan semua perintah <code>import ...</code> di baris paling atas dari file kode Anda agar mudah dibaca oleh programmer lain.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Fungsi modul bawaan manakah yang tepat digunakan untuk mensimulasikan lemparan dadu bernilai antara 1 sampai 6 secara acak?',
                'option_a' => 'math.random(1, 6)',
                'option_b' => 'random.randint(1, 6)',
                'option_c' => 'random.range(1, 6)',
                'option_d' => 'datetime.random(1, 6)',
                'correct_option' => 'B',
                'explanation' => 'random.randint(a, b) menghasilkan bilangan bulat acak di mana kedua batas a dan b bersifat inklusif (termasuk 1 dan 6).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
import math
hasil = math.sqrt(64) + math.ceil(4.2)
print(hasil)
Berapakah output yang dihasilkan?',
                'option_a' => '12.0',
                'option_b' => '13.0',
                'option_c' => '14.0',
                'option_d' => 'TypeError',
                'correct_option' => 'B',
                'explanation' => 'math.sqrt(64) menghasilkan 8.0. math.ceil(4.2) membulatkan ke atas menjadi 5. Maka 8.0 + 5 = 13.0.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa seorang siswa yang menamai file kodenya \'random.py\' mengalami AttributeError saat memanggil random.randint()?',
                'option_a' => 'Karena fungsi randint tidak ada di Python',
                'option_b' => 'Karena Python mengimpor file kodenya sendiri (Module Shadowing) alih-alih modul random asli bawaan pustaka standar Python',
                'option_c' => 'Karena modul random berbayar',
                'option_d' => 'Karena fungsi random hanya bisa diakses di sistem operasi Linux',
                'correct_option' => 'B',
                'explanation' => 'Menamai file sama dengan nama modul standar menyebabkan Module Shadowing, di mana Python mengimpor file lokal tersebut sehingga fungsi standar tidak ditemukan.',
                'xp_reward' => 20
            ]
        ]
    ],

    '7.5' => [
        'title' => '7.5 Boss Challenge: Catatan Transaksi Anti-Crash',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-trophy text-amber-600"></i> 1. Uji Kompetensi Part 7: Black-Box Pencatat Transaksi
        </div>
        <p>Di Boss Challenge kali ini, Anda ditantang membuat sistem pencatatan transaksi kasir kelas industri yang memiliki ketahanan tinggi (robust). Program harus mampu menangani salah ketik pengguna tanpa crash, membangkitkan nomor resi unik acak, menyematkan tanggal transaksi, dan menyimpannya secara permanen ke file <code>transaksi.log</code> dengan mode append!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Kriteria Sistem
        </h4>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li>Input nominal harga wajib dilindungi <code>try-except ValueError</code>. Jika user mengetik huruf, minta input ulang.</li>
            <li>Gunakan <code>random.randint(1000, 9999)</code> untuk membuat Nomor ID Transaksi.</li>
            <li>Gunakan <code>datetime.datetime.now()</code> untuk mencatat stempel waktu real-time.</li>
            <li>Simpan transaksi ke dalam file menggunakan <code>with open("transaksi.log", "a")</code>.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Solusi
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>import random
import datetime

print("=== SISTEM TRANSAKSI ROBUST WIKRAMA ===")

# 1. Input Aman dengan Try-Except dalam Loop
while True:
    try:
        nama_barang = input("Nama Barang : ").strip()
        if not nama_barang:
            print("[!] Nama barang tidak boleh kosong!")
            continue
            
        harga = int(input("Harga Satuan: Rp "))
        if harga <= 0:
            print("[!] Harga harus lebih besar dari Rp 0!")
            continue
            
        break # Input valid, keluar dari loop
    except ValueError:
        print("[!] Error: Masukkan angka bulat yang sah untuk harga!\n")

# 2. Generate Data Transaksi Otomatis
id_transaksi = f"TRX-{random.randint(1000, 9999)}"
waktu = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")

baris_log = f"[{waktu}] ID: {id_transaksi} | Item: {nama_barang} | Total: Rp {harga:,}\n"

# 3. Penyimpanan Permanen Anti-Data Loss
try:
    with open("transaksi.log", "a", encoding="utf-8") as file:
        file.write(baris_log)
    print("\n[SUKSES] Transaksi tersimpan ke transaksi.log")
    print(baris_log.strip())
except Exception as e:
    print(f"[CRITICAL ERROR] Gagal menulis ke file: {e}")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Standar Aplikasi Produksi
        </div>
        <p>Aplikasi yang baik tidak pernah membiarkan pengguna melihat layar merah error sistem. Seluruh kemungkinan salah ketik dicegah dan ditangani dengan pesan instruktif.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Metode <code>.strip()</code> pada string berfungsi membuang spasi kosong tidak sengaja di awal atau akhir ketikan pengguna pada keyboard layar sentuh HP.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Mengapa perulangan while True yang dikombinasikan dengan try-except ValueError menjadi pola terbaik untuk validasi input angka di program kasir?',
                'option_a' => 'Karena menghapus semua data di memory',
                'option_b' => 'Karena jika pengguna salah mengetik huruf, program tidak mati dan dapat terus mengulang pertanyaan hingga pengguna memasukkan angka yang benar',
                'option_c' => 'Karena membuat kode otomatis tersambung ke printer struk',
                'option_d' => 'Karena memperkecil ukuran file log',
                'correct_option' => 'B',
                'explanation' => 'Pola while True + try-except memastikan program tidak berhenti ketika terjadi ValueError, melainkan meminta input ulang sampai valid.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Format kode apakah yang dihasilkan oleh f"TRX-{random.randint(1000, 9999)}" jika angka acak yang terpilih adalah 5821?',
                'option_a' => 'TRX-5821',
                'option_b' => 'TRX-{5821}',
                'option_c' => 'random-5821',
                'option_d' => '5821-TRX',
                'correct_option' => 'A',
                'explanation' => 'Di dalam f-string ekspresi {random.randint(1000, 9999)} dievaluasi menjadi nilai angkanya (5821) sehingga menghasilkan teks "TRX-5821".',
                'xp_reward' => 20
            ],
            [
                'question' => 'Apa fungsi dari method .strip() pada ekspresi nama_barang = input("...").strip()?',
                'option_a' => 'Mengubah huruf kecil menjadi huruf besar',
                'option_b' => 'Menghapus spasi kosong berlebih di awal dan akhir teks input',
                'option_c' => 'Mengubah string menjadi angka',
                'option_d' => 'Menghapus file log',
                'correct_option' => 'B',
                'explanation' => 'str.strip() menghapus whitespace (spasi, tab, newline) di bagian awal dan akhir string.',
                'xp_reward' => 20
            ]
        ]
    ]
];
