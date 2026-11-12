<?php
/**
 * PART 5: STRUKTUR DATA (LIST & DICTIONARY)
 * Sub-topik 5.1 s/d 5.5
 */
return [
    '5.1' => [
        'title' => '5.1 List: Indexing & Slicing',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Lemari Laci Bernomor Urut
        </div>
        <p>Bayangkan Anda memiliki lemari laci berkas di ruang tata usaha. Setiap laci dapat menyimpan dokumen apa saja, dan di bagian depan laci tertempel stiker nomor urut: <code>0, 1, 2, 3, ...</code>. Jika ingin mengambil dokumen di laci teratas, Anda cukup menyebut nomor <code>0</code>. Di Python, <strong>List</strong> adalah lemari laci serbaguna yang bisa menyimpan ratusan data sekaligus dalam satu wadah rapi!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Karakteristik, Indexing &amp; Slicing
        </h4>
        <p>List didefinisikan menggunakan <strong>tanda kurung siku [ ]</strong> dengan pemisah koma antar elemen:</p>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Zero-based Indexing:</strong> Indeks elemen pertama SELALU dimulai dari angka <code>0</code>, bukan 1! Elemen kedua adalah indeks 1, dan seterusnya.</li>
            <li><strong>Negative Indexing:</strong> Python mengizinkan indeks negatif untuk menghitung mundur dari belakang. Indeks <code>-1</code> selalu menunjuk elemen paling terakhir!</li>
            <li><strong>Slicing [start:stop]:</strong> Memotong sebagian elemen list. Elemen pada batas <code>stop</code> tidak diikutsertakan.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Membuat list bahasa pemrograman
bahasa = ["Python", "JavaScript", "PHP", "Kotlin", "Java"]

# Mengakses elemen tunggal
print("Elemen Pertama [0] :", bahasa[0])  # Python
print("Elemen Terakhir [-1]:", bahasa[-1]) # Java

# Mengambil potongan (slicing) 3 elemen pertama
favorit = bahasa[0:3]
print("Tiga Bahasa Favorit:", favorit)    # [\'Python\', \'JavaScript\', \'PHP\']

# Mengetahui total item dengan len()
print("Jumlah bahasa yang tersedia:", len(bahasa))</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Mengira Indeks Mulai dari 1
        </div>
        <p>Jika list memiliki 3 elemen (<code>["A", "B", "C"]</code>) dan Anda memanggil <code>data[1]</code>, Anda BUKAN mendapatkan huruf "A", melainkan huruf "B"! Ingat selalu: laci pertama adalah laci nomor <strong>0</strong>.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Tombol <code>[ ]</code> tersedia langsung di bilah toolbar PyLMS. Anda cukup mengetuk tombol tersebut untuk membuat sepasang kurung siku tanpa repot mencari di keyboard HP.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Berapakah nomor indeks untuk mengakses elemen pertama dalam list di Python?',
                'option_a' => '1',
                'option_b' => '0',
                'option_c' => '-1',
                'option_d' => 'None',
                'correct_option' => 'B',
                'explanation' => 'Sistem indeks list di Python menganut zero-based indexing, di mana elemen pertama selalu menempati indeks 0.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode slicing berikut:
kendaraan = ["Motor", "Mobil", "Sepeda", "Bus", "Kereta"]
sub = kendaraan[1:4]
print(sub)
Apakah output yang dihasilkan?',
                'option_a' => '[\'Motor\', \'Mobil\', \'Sepeda\']',
                'option_b' => '[\'Mobil\', \'Sepeda\', \'Bus\']',
                'option_c' => '[\'Mobil\', \'Sepeda\', \'Bus\', \'Kereta\']',
                'option_d' => '[\'Motor\', \'Kereta\']',
                'correct_option' => 'B',
                'explanation' => 'Slicing [1:4] mengambil indeks 1 ("Mobil"), 2 ("Sepeda"), dan 3 ("Bus"). Indeks ke-4 ("Kereta") tidak diikutsertakan karena batas stop eksklusif.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Manakah cara paling ringkas dan aman untuk mengambil elemen TERAKHIR dari list data tanpa perlu menghitung panjang list secara manual?',
                'option_a' => 'data[last]',
                'option_b' => 'data[-1]',
                'option_c' => 'data[end]',
                'option_d' => 'data[100]',
                'correct_option' => 'B',
                'explanation' => 'Python mendukung negative indexing, di mana indeks -1 secara otomatis menunjuk ke elemen paling ujung/terakhir dari suatu sequence/list.',
                'xp_reward' => 20
            ]
        ]
    ],

    '5.2' => [
        'title' => '5.2 Manipulasi List: append(), pop(), sort()',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Gerbong Kereta Logistik
        </div>
        <p>Bayangkan rangkaian gerbong kereta barang di stasiun:
        <br>- Menautkan satu gerbong baru di ujung paling belakang dinamakan <strong>append()</strong>.
        <br>- Melepas gerbong terakhir untuk dibongkar muatannya dinamakan <strong>pop()</strong>.
        <br>- Menyusun ulang seluruh gerbong dari muatan teringan ke terberat dinamakan <strong>sort()</strong>.
        <br>List di Python bersifat <em>mutable</em> (bisa diubah, ditambah, atau dikurangi sesuka hati kapan saja!).</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Method Manipulasi List Populer
        </h4>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><code>list.append(nilai)</code>: Menambahkan nilai baru ke posisi paling akhir list.</li>
            <li><code>list.insert(indeks, nilai)</code>: Menyisipkan nilai pada posisi indeks tertentu.</li>
            <li><code>list.pop()</code>: Menghapus dan mengambil elemen paling belakang. Jika diberi argumen <code>list.pop(0)</code>, ia menghapus elemen pada indeks tersebut.</li>
            <li><code>list.remove(nilai)</code>: Menghapus elemen pertama yang cocok dengan nilai tersebut.</li>
            <li><code>list.sort()</code>: Mengurutkan list secara langsung (ascending: A-Z atau angka terkecil ke terbesar).</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Manajemen Antrian Siswa
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>antrian = ["Fadhil", "Siti", "Budi"]
print("Antrian Awal :", antrian)

# Siswa baru mendaftar di antrian belakang
antrian.append("Zahra")
print("Setelah Tambah:", antrian)

# Pasien/Siswa pertama dilayani dan keluar dari antrian
yang_dilayani = antrian.pop(0)
print(f"Sedang Melayani: {yang_dilayani}")
print("Sisa Antrian   :", antrian)

# Mengurutkan sisa antrian sesuai abjad
antrian.sort()
print("Antrian Terurut Abjad:", antrian)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Menulis data = data.append(...)
        </div>
        <p>Method seperti <code>append()</code> dan <code>sort()</code> bekerja secara <em>in-place</em> (mengubah list aslinya langsung) dan mengembalikan nilai <code>None</code>. Jika Anda menulis <code>data = data.append(10)</code>, variabel data Anda akan berubah menjadi <strong>None</strong> dan isi list lenyap!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Cukup panggil <code>data.append(x)</code> tanpa tanda penugasan sama dengan (<code>=</code>) di depannya.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Method manakah yang digunakan untuk menambahkan elemen baru ke posisi paling akhir dari sebuah list?',
                'option_a' => 'list.push()',
                'option_b' => 'list.append()',
                'option_c' => 'list.add()',
                'option_d' => 'list.insert_end()',
                'correct_option' => 'B',
                'explanation' => 'Di Python, append() adalah method resmi bawaan list untuk menambahkan satu item ke ujung akhir list.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
angka = [7, 2, 9]
angka.append(4)
angka.sort()
print(angka)
Apakah output yang dihasilkan?',
                'option_a' => '[7, 2, 9, 4]',
                'option_b' => '[2, 4, 7, 9]',
                'option_c' => '[9, 7, 4, 2]',
                'option_d' => 'None',
                'correct_option' => 'B',
                'explanation' => 'angka.append(4) menjadikan list [7, 2, 9, 4]. Kemudian method sort() mengurutkan elemen dari terkecil ke terbesar menjadi [2, 4, 7, 9].',
                'xp_reward' => 20
            ],
            [
                'question' => 'Seorang murid menulis kode:
nilai = [80, 90, 75]
nilai = nilai.append(85)
print(nilai)
Mengapa output di terminal mencetak \'None\'?',
                'option_a' => 'Karena angka 85 tidak valid',
                'option_b' => 'Karena method append() memodifikasi list secara langsung dan tidak mengembalikan nilai (return None)',
                'option_c' => 'Karena fungsi print salah',
                'option_d' => 'Karena list nilai sudah penuh',
                'correct_option' => 'B',
                'explanation' => 'append() melakukan in-place modification. Nilai baliknya adalah None. Dengan menulis nilai = nilai.append(85), variabel nilai ditimpa dengan None.',
                'xp_reward' => 20
            ]
        ]
    ],

    '5.3' => [
        'title' => '5.3 Debugging: Mengatasi IndexError',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Membuka Laci Nomor 10 di Lemari 3 Laci
        </div>
        <p>Bayangkan Anda menyuruh teman Anda: <em>"Tolong ambilkan map di laci nomor 10!"</em>. Teman Anda menatap lemari dengan bingung karena lemari tersebut hanya memiliki 3 laci (laci 0, 1, dan 2). Teman Anda akan memprotes: "Laci nomor 10 tidak ada!". Di Python, situasi ini memicu <strong>IndexError: list index out of range</strong>!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Mengapa IndexError Bisa Terjadi?
        </h4>
        <p>IndexError terjadi ketika kode program berusaha mengakses indeks yang berada di luar jangkauan ukuran list:</p>
        <div class="p-3 bg-slate-900 text-slate-100 rounded-xl font-mono text-xs">
            skor = [85, 90, 78] # len(skor) adalah 3.<br>
            # Indeks yang sah HANYA: 0, 1, 2<br>
            skor[3] # CRASH! IndexError: list index out of range
        </div>
        <p class="mt-2 text-xs">Ingat aturan emas: <strong>Indeks maksimum yang valid adalah selalu len(list) - 1</strong>.</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Cara Mencegah &amp; Menangani IndexError
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>daftar_siswa = ["Ahmad", "Budi", "Citra"]

target_indeks = int(input("Pilih nomor indeks (0-2): "))

# Metode 1: Pemeriksaan batas aman menggunakan len()
if 0 <= target_indeks < len(daftar_siswa):
    print(f"Siswa pada indeks {target_indeks}: {daftar_siswa[target_indeks]}")
else:
    print(f"Error: Indeks {target_indeks} di luar batas! Maksimal adalah {len(daftar_siswa)-1}")

# Metode 2: Menggunakan for item in list (Tanpa perlu repot urusan indeks)
print("\nSeluruh Siswa:")
for siswa in daftar_siswa:
    print("-", siswa)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Perulangan for i in range(len(list) + 1)
        </div>
        <p>Banyak siswa pemula menulis <code>for i in range(len(data) + 1):</code> karena mengira butuh menambah satu. Perulangan akan berhasil di putaran-putaran awal, lalu tiba-tiba meledak (crash) di putaran paling akhir saat menyentuh <code>data[len(data)]</code>!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Gunakan perulangan langsung: <code>for item in my_list:</code>. Pola ini 100% bebas dari risiko IndexError dan jauh lebih bersih diketik di ponsel.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Jika sebuah list memiliki 4 elemen, berapakah nomor indeks maksimum yang sah untuk diakses?',
                'option_a' => '4',
                'option_b' => '3',
                'option_c' => '5',
                'option_d' => 'Tidak terbatas',
                'correct_option' => 'B',
                'explanation' => 'Karena indeks dimulai dari 0, list dengan 4 elemen memiliki indeks yang sah: 0, 1, 2, dan 3. Indeks maksimumnya adalah len - 1 = 3.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan potongan kode berikut:
kota = ["Jakarta", "Bandung", "Surabaya"]
print(kota[3])
Apakah pesan error yang akan muncul di layar terminal?',
                'option_a' => 'KeyError: 3',
                'option_b' => 'IndexError: list index out of range',
                'option_c' => 'ValueError: invalid list',
                'option_d' => 'TypeError: list indices must be integers',
                'correct_option' => 'B',
                'explanation' => 'List hanya memiliki elemen hingga indeks 2. Memanggil kota[3] melebihi batas jangkauan list sehingga menimbulkan IndexError.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Manakah cara perulangan yang paling aman untuk mencetak seluruh isi list data tanpa risiko menimbulkan IndexError?',
                'option_a' => 'for i in range(len(data) + 1): print(data[i])',
                'option_b' => 'for item in data: print(item)',
                'option_c' => 'for i in range(100): print(data[i])',
                'option_d' => 'while len(data) >= 0: print(data[0])',
                'correct_option' => 'B',
                'explanation' => 'Iterasi langsung `for item in data:` mengekstrak elemen satu per satu dari koleksi secara otomatis tanpa manipulasi angka indeks, sehingga bebas IndexError.',
                'xp_reward' => 20
            ]
        ]
    ],

    '5.4' => [
        'title' => '5.4 Dictionary: Pasangan Key - Value',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Buku Kontak Telepon di Smartphone
        </div>
        <p>Di aplikasi kontak telepon smartphone Anda, Anda tidak mencari nomor teman dengan menghafal "Kontak nomor urut 37". Anda mencari berdasarkan <strong>Nama Teman (Key / Kunci)</strong>, lalu aplikasi akan menampilkan <strong>Nomor Telepon (Value / Nilai)</strong> miliknya. Di Python, struktur data ini disebut <strong>Dictionary</strong> (kamus pasangan Key-Value)!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Sintaks &amp; Cara Mengakses Dictionary
        </h4>
        <p>Dictionary dibuat menggunakan kurung kurawal <code>{ }</code>, dengan format <code>kunci : nilai</code>:</p>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Key (Kunci):</strong> Harus unik (tidak boleh ada kunci kembar) dan biasanya berupa String teks.</li>
            <li><strong>Value (Nilai):</strong> Boleh berupa tipe data apa saja: string, integer, float, boolean, bahkan list!</li>
            <li><strong>Akses Nilai:</strong> Menggunakan <code>dict[key]</code> atau metode aman <code>dict.get(key, nilai_default)</code>.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata: Data Siswa Terstruktur
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Mendefinisikan profil siswa SMK
siswa = {
    "nis": "123098",
    "nama": "Ahmad Fadhil",
    "jurusan": "Pengembangan Perangkat Lunak (PPLG)",
    "nilai": 88.5,
    "aktif": True
}

# Mengakses data
print("Nama Siswa :", siswa["nama"])
print("Jurusan    :", siswa["jurusan"])

# Memperbarui data yang ada
siswa["nilai"] = 92.0

# Menambahkan key-value baru
siswa["peringkat"] = 1

# Mengakses dengan get() aman (tidak akan crash jika key tidak ada)
telepon = siswa.get("no_hp", "Nomor Belum Terdaftar")
print("Kontak HP  :", telepon)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: KeyError Saat Memanggil Key Gaib
        </div>
        <p>Jika Anda memanggil <code>siswa["alamat"]</code> padahal kunci "alamat" tidak ada di dictionary, program akan langsung crash dengan <code>KeyError: \'alamat\'</code>. Solusinya: selalu gunakan method <code>.get("alamat", "Default")</code> jika Anda tidak yakin kunci tersebut pasti ada.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Tanda petik dan kurung kurawal <code>{ }</code> serta titik dua <code>:</code> dapat Anda ketik secara beruntun menggunakan tombol pintas simbol di PyLMS.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Struktur apakah yang digunakan untuk menyimpan pasangan data pada tipe Dictionary di Python?',
                'option_a' => 'Index - Content',
                'option_b' => 'Key - Value (Kunci - Nilai)',
                'option_c' => 'Row - Column',
                'option_d' => 'Variable - Function',
                'correct_option' => 'B',
                'explanation' => 'Dictionary di Python selalu menyimpan data dalam pasangan Key - Value yang dipisahkan oleh tanda titik dua (:).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Perhatikan program berikut:
biodata = {"nama": "Rian", "usia": 16}
biodata["usia"] = 17
biodata["kelas"] = "XI RPL"
print(len(biodata))
Berapakah panjang (jumlah item) dictionary biodata sekarang?',
                'option_a' => '2',
                'option_b' => '3',
                'option_c' => '4',
                'option_d' => 'SyntaxError',
                'correct_option' => 'B',
                'explanation' => 'Awalnya memiliki 2 key ("nama", "usia"). biodata["usia"] = 17 hanya mengubah nilai yang ada. biodata["kelas"] menambah 1 key baru. Total key menjadi 3.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Bagaimanakah cara mengambil nilai dari kunci "email" pada dictionary akun tanpa risiko memicu KeyError jika kunci tersebut belum dibuat?',
                'option_a' => 'akun["email"]',
                'option_b' => 'akun.get("email", "Tidak Ada")',
                'option_c' => 'akun.find("email")',
                'option_d' => 'akun.pop("email")',
                'correct_option' => 'B',
                'explanation' => 'Method dict.get(key, default) mengembalikan nilai kunci jika ada, atau nilai default jika kunci tidak ditemukan, tanpa menimbulkan KeyError.',
                'xp_reward' => 20
            ]
        ]
    ],

    '5.5' => [
        'title' => '5.5 Boss Challenge: Analisis Nilai Rombel',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-trophy text-amber-600"></i> 1. Uji Kompetensi Part 5: Rekapitulasi Rapor Digital Kelas
        </div>
        <p>Di Boss Challenge ini, Anda akan menggabungkan kekuatan <strong>List</strong> dan <strong>Dictionary</strong> untuk membangun sistem analisis nilai satu rombel kelas. Program akan menghitung statistik penting: <strong>Nilai Tertinggi</strong>, <strong>Nilai Terendah</strong>, <strong>Rata-Rata Kelas</strong>, serta mencantumkan nama siswa peraih juara 1!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Fungsi Bawaan Statistik Python
        </h4>
        <p>Untuk mengolah data numerik dalam list, Python menyediakan fungsi bawaan super efisien:</p>
        <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-600">
            <li><code>sum(list_angka)</code>: Menghitung total jumlah seluruh angka.</li>
            <li><code>max(list_angka)</code>: Mengambil nilai terbesar/tertinggi.</li>
            <li><code>min(list_angka)</code>: Mengambil nilai terkecil/terendah.</li>
            <li><code>len(list)</code>: Menghitung total banyaknya data.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Solusi
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Data Nilai Siswa (List of Dictionaries)
kelas_pplg = [
    {"nama": "Ahmad Fadhil", "nilai": 88.0},
    {"nama": "Siti Nurhaliza", "nilai": 95.5},
    {"nama": "Budi Pratama", "nilai": 74.0},
    {"nama": "Zahra Amalia", "nilai": 91.0}
]

# Ekstrak seluruh nilai ke dalam list tunggal
daftar_nilai = [siswa["nilai"] for siswa in kelas_pplg]

total_siswa = len(kelas_pplg)
nilai_tertinggi = max(daftar_nilai)
nilai_terendah = min(daftar_nilai)
rata_rata = sum(daftar_nilai) / total_siswa

print("=== REKAP NILAI KELAS PPLG WIKRAMA ===")
print(f"Total Siswa Terdaftar : {total_siswa} orang")
print(f"Rata-Rata Kelas       : {rata_rata:.2f}")
print(f"Nilai Tertinggi       : {nilai_tertinggi:.1f}")
print(f"Nilai Terendah        : {nilai_terendah:.1f}")
print("-" * 38)

# Menemukan siswa peraih nilai tertinggi
for siswa in kelas_pplg:
    if siswa["nilai"] == nilai_tertinggi:
        print(f"Juara Kelas : {siswa[\"nama\"]} (Nilai {siswa[\"nilai\"]})")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Membagi dengan Nol pada List Kosong
        </div>
        <p>Jika list data tidak memiliki elemen sama sekali (<code>len == 0</code>), rumus <code>sum(data) / len(data)</code> akan menyebabkan <strong>ZeroDivisionError</strong>. Selalu pastikan jumlah siswa lebih besar dari 0 sebelum melakukan pembagian rata-rata!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Pola struktur data <em>List of Dictionaries</em> seperti contoh di atas adalah standar yang persis digunakan dalam komunikasi API dan database web modern (format JSON).</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Fungsi bawaan Python manakah yang paling cepat untuk mencari nilai terbesar dari sekumpulan angka di dalam list?',
                'option_a' => 'highest()',
                'option_b' => 'max()',
                'option_c' => 'top()',
                'option_d' => 'sort_max()',
                'correct_option' => 'B',
                'explanation' => 'Fungsi bawaan max(iterable) mengembalikan elemen dengan nilai terbesar dalam suatu koleksi data.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Diberikan list nilai = [70, 80, 90, 100]. Berapakah nilai rata-rata dari list tersebut jika dihitung dengan rumus sum(nilai) / len(nilai)?',
                'option_a' => '80.0',
                'option_b' => '85.0',
                'option_c' => '90.0',
                'option_d' => '340.0',
                'correct_option' => 'B',
                'explanation' => 'sum(nilai) = 70 + 80 + 90 + 100 = 340. len(nilai) = 4. Maka 340 / 4 = 85.0.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa programmer harus memeriksa apakah panjang list lebih dari 0 sebelum menghitung rata-rata?',
                'option_a' => 'Agar tipe data tidak berubah',
                'option_b' => 'Untuk mencegah terjadinya ZeroDivisionError jika list ternyata kosong (panjang 0)',
                'option_c' => 'Karena list kosong tidak bisa di-print',
                'option_d' => 'Agar fungsi max() menghasilkan nilai default',
                'correct_option' => 'B',
                'explanation' => 'Jika list kosong, len(list) bernilai 0. Membagi apapun dengan 0 akan langsung memicu ZeroDivisionError yang menghentikan program.',
                'xp_reward' => 20
            ]
        ]
    ]
];
