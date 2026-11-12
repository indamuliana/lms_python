<?php
/**
 * PART 8: CAPSTONE PROJECT & CLEAN CODE
 * Sub-topik 8.1 s/d 8.5
 */
return [
    '8.1' => [
        'title' => '8.1 Panduan Arsitektur & Clean Code',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata: Arsitektur Gedung Bertingkat vs Rumah Kardus
        </div>
        <p>Siapa pun bisa menumpuk kardus bekas menjadi gubuk mainan dalam 10 menit. Tetapi untuk membangun gedung sekolah berlantai 4 yang tahan gempa, Anda membutuhkan cetak biru arsitektur (blueprint), pembagian ruangan fungsional, dan saluran instalasi yang teratur. Di dunia industri software, <strong>Clean Code</strong> adalah pembeda antara programmer amatir dan insinyur perangkat lunak profesional!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Struktur Standar File Python Profesional
        </h4>
        <p>Urutan baku penyusunan file kode Python sesuai standar PEP 8:</p>
        <ol class="list-decimal pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Dokumentasi &amp; Impor Modul:</strong> Docstring deskripsi program dan <code>import</code> pustaka di baris teratas.</li>
            <li><strong>Konstanta Global:</strong> Variabel konfigurasi tetap dengan huruf kapital (misal: <code>PAJAK_RESTO = 0.10</code>).</li>
            <li><strong>Definisi Fungsi-Fungsi:</strong> Setiap fungsi memiliki satu tugas spesifik (Single Responsibility Principle).</li>
            <li><strong>Fungsi Utama main():</strong> Tempat orkestrasi alur aplikasi.</li>
            <li><strong>Eksekusi Guard:</strong> Blok <code>if __name__ == "__main__":</code> di baris paling bawah.</li>
        </ol>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Template Clean Code Python
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>"""
Aplikasi Manajemen Nilai Siswa
Dibuat oleh: Tim Pengembang Wikrama
"""
import sys

# Konstanta Konfigurasi
NILAI_KKM = 75
SEKOLAH = "SMK Wikrama 1 Garut"

def evaluasi_nilai(skor):
    """Menentukan apakah skor memenuhi batas KKM"""
    return "LULUS" if skor >= NILAI_KKM else "REMEDIAL"

def main():
    print(f"=== SISTEM AKADEMIK {SEKOLAH} ===")
    nilai = 82
    status = evaluasi_nilai(nilai)
    print(f"Siswa dengan nilai {nilai} dinyatakan: {status}")

# Guard Idiom: Hanya berjalan jika file ini dieksekusi langsung
if __name__ == "__main__":
    main()</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Bahaya "Spaghetti Code"
        </div>
        <p>Spaghetti code adalah sebutan untuk kode yang ditulis ratusan baris secara memanjang ke bawah tanpa menggunakan fungsi, mencampur input, logika bisnis, dan output dalam satu tumpukan rumit. Kode seperti ini sangat sulit diperbaiki saat ada bug dan mustahil dikerjakan secara tim!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Batasi panjang setiap fungsi maksimal 15-20 baris agar Anda dapat membaca satu fungsi utuh di layar ponsel tanpa harus scroll naik-turun berkali-kali.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Apakah fungsi dari blok pengaman \'if __name__ == "__main__":\' di baris paling bawah program Python?',
                'option_a' => 'Membuat kode dapat berjalan tanpa koneksi internet',
                'option_b' => 'Memastikan fungsi main() hanya dieksekusi saat file tersebut dijalankan secara langsung, bukan saat diimpor sebagai modul oleh file lain',
                'option_c' => 'Mengubah file menjadi format .exe',
                'option_d' => 'Menghapus variabel di memori',
                'correct_option' => 'B',
                'explanation' => 'Guard idiom if __name__ == "__main__": membedakan eksekusi langsung (top-level script) dengan proses import oleh file eksternal.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Berdasarkan konvensi Clean Code PEP 8, bagaimanakah penulisan nama variabel KONSTANTA (nilai tetap yang tidak boleh diubah)?',
                'option_a' => 'Huruf kecil bersambung (contoh: nilaikkm)',
                'option_b' => 'Semua huruf besar dengan garis bawah (contoh: NILAI_KKM)',
                'option_c' => 'CamelCase (contoh: nilaiKkm)',
                'option_d' => 'Diawali tanda dollar ($nilai_kkm)',
                'correct_option' => 'B',
                'explanation' => 'Sesuai panduan gaya PEP 8 Python, konstanta didefinisikan menggunakan huruf kapital seluruhnya dengan pemisah underscore (UPPER_CASE).',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa prinsip "Single Responsibility" (Satu fungsi hanya mengerjakan satu tugas spesifik) sangat ditekankan dalam Clean Code?',
                'option_a' => 'Agar fungsi lebih mudah dibaca, mudah diuji coba (testing), dan mudah digunakan kembali tanpa efek samping tak terduga',
                'option_b' => 'Agar ukuran file menjadi lebih besar',
                'option_c' => 'Karena Python melarang fungsi memiliki lebih dari 10 baris',
                'option_d' => 'Agar program tidak memerlukan variabel',
                'correct_option' => 'A',
                'explanation' => 'Fungsi dengan tanggung jawab tunggal (single responsibility) jauh lebih modular, terhindar dari bug sampingan, dan mudah di-maintain.',
                'xp_reward' => 20
            ]
        ]
    ],

    '8.2' => [
        'title' => '8.2 Jalur 1: Deep-Dive Game Teks Edukasi',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-gamepad text-amber-600"></i> 1. Panduan Capstone Jalur 1: Game RPG Labirin Edukasi
        </div>
        <p>Di jalur ini, Anda membangun sebuah game petualangan berbasis teks interaktif (CLI RPG). Pemain mengendalikan seorang pahlawan siswa yang bertualang menjelajahi ruang bawah tanah komputer, memecahkan teka-teki logika pemrograman, bertarung melawan monster bug, dan mengumpulkan item ramuan kesehatan!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Arsitektur State Game
        </h4>
        <p>Status pahlawan dan monster dikelola menggunakan Dictionary:</p>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Pemain:</strong> <code>{"nama": "Ksatria", "hp": 100, "attack": 25, "potion": 2}</code></li>
            <li><strong>Game Loop:</strong> Perulangan <code>while pemain["hp"] &gt; 0 and monster["hp"] &gt; 0:</code></li>
            <li><strong>Pilihan Aksi Pemain:</strong> (1) Serang Monster, (2) Minum Ramuan (+30 HP), (3) Menyerah.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata Mini-RPG
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>import random

def main_game():
    hero = {"nama": "Ksatria Python", "hp": 100, "potion": 2}
    boss = {"nama": "Monster SyntaxError", "hp": 80}

    print(f"=== PERTARUNGAN DIMULAI: {hero[\'nama\']} vs {boss[\'nama\']} ===")

    while hero["hp"] > 0 and boss["hp"] > 0:
        print(f"\nStatus: {hero[\'nama\']} (HP: {hero[\'hp\']}) | {boss[\'nama\']} (HP: {boss[\'hp\']})")
        print("Aksi: [1] Tebas Pedang  [2] Minum Ramuan (+30 HP)")
        pilihan = input("Pilih tindakan (1/2): ")

        if pilihan == "1":
            damage = random.randint(15, 30)
            boss["hp"] -= damage
            print(f"> Anda menebas {boss[\'nama\']} sebesar {damage} DMG!")
        elif pilihan == "2":
            if hero["potion"] > 0:
                hero["hp"] += 30
                hero["potion"] -= 1
                print(f"> Gluk gluk! HP Anda bertambah 30. Sisa ramuan: {hero[\'potion\']}")
            else:
                print("> Ramuan Anda sudah habis!")
        else:
            print("> Tindakan panik! Anda kehilangan giliran.")

        # Serangan balasan Monster jika masih hidup
        if boss["hp"] > 0:
            serangan_boss = random.randint(10, 20)
            hero["hp"] -= serangan_boss
            print(f"> {boss[\'nama\']} menyerang balik sebesar {serangan_boss} DMG!")

    print("\n" + "=" * 35)
    if hero["hp"] > 0:
        print("VICTORY! Anda berhasil menaklukkan Dungeon Bug!")
    else:
        print("GAME OVER! Silakan perbaiki sintaks kode Anda.")

if __name__ == "__main__":
    main_game()</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Monster Masih Menyerang Saat Sudah Mati
        </div>
        <p>Perhatikan baris <code>if boss["hp"] &gt; 0:</code> sebelum serangan balasan boss. Jika pengecekan ini tidak ada, monster yang HP-nya sudah 0 akan tetap menyerang dan bisa membunuh pemain di ronde yang sama (Bug Zombie)!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Tambahkan karakter ASCII art sederhana seperti pedang <code>--===&gt;</code> atau bintang untuk membuat game teks Anda tampak keren di layar ponsel.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Struktur data apakah yang paling ideal untuk menyimpan status pahlawan game (nama, hp, attack, jumlah potion)?',
                'option_a' => 'Dictionary (Pasangan key-value)',
                'option_b' => 'String tunggal',
                'option_c' => 'Integer',
                'option_d' => 'List kosong',
                'correct_option' => 'A',
                'explanation' => 'Dictionary memungkinkan pengaksesan atribut status pahlawan dengan nama yang jelas seperti hero["hp"] atau hero["potion"].',
                'xp_reward' => 15
            ],
            [
                'question' => 'Jika HP awal Hero adalah 100 dan ia menerima serangan 3 kali masing-masing sebesar 22, 18, dan 25 poin kerusakan, berapakah sisa HP Hero?',
                'option_a' => '45',
                'option_b' => '35',
                'option_c' => '25',
                'option_d' => '0',
                'correct_option' => 'B',
                'explanation' => 'Total serangan = 22 + 18 + 25 = 65. Sisa HP = 100 - 65 = 35.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa pemeriksaan \'if boss["hp"] > 0:\' penting dilakukan sebelum monster meluncurkan serangan balik?',
                'option_a' => 'Agar loop berjalan lebih cepat',
                'option_b' => 'Untuk mencegah monster yang telah mati (HP <= 0) tetap menyerang pemain pada giliran tersebut (Zombie Bug)',
                'option_c' => 'Agar pemain mendapat potion tambahan',
                'option_d' => 'Karena Python melarang nilai negatif pada integer',
                'correct_option' => 'B',
                'explanation' => 'Pemeriksaan HP memastikan entitas yang sudah kehabisan darah tidak lagi dapat mengambil tindakan giliran.',
                'xp_reward' => 20
            ]
        ]
    ],

    '8.3' => [
        'title' => '8.3 Jalur 2: Deep-Dive Kasir Toko & File Struk',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-cash-register text-amber-600"></i> 1. Panduan Capstone Jalur 2: Sistem POS Toko &amp; Cetak Struk
        </div>
        <p>Di jalur ini, Anda membangun aplikasi kasir Point of Sale (POS) lengkap untuk Toko Ritel atau Kantin Siswa. Program memiliki menu katalog produk, keranjang transaksi multi-item, perhitungan diskon bertingkat, validasi uang tunai pembayaran, hingga ekspor struk resmi ke file teks permanen <code>struk.txt</code>!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Struktur Modul Aplikasi Kasir
        </h4>
        <ul class="list-disc pl-5 space-y-1.5 mt-2 text-slate-600">
            <li><strong>Katalog Produk:</strong> Dictionary daftar barang dan harga satuan.</li>
            <li><strong>Keranjang Belanja:</strong> List of dictionaries yang mencatat nama item, kuantitas (qty), dan subtotal barang.</li>
            <li><strong>Validasi Pembayaran:</strong> Jika uang tunai yang diberikan pembeli lebih kecil dari total tagihan, sistem menolak dan meminta kekurangan uang.</li>
            <li><strong>File Struk:</strong> Menyimpan salinan invoice transaksi ke media penyimpanan dengan rapi.</li>
        </ul>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>katalog = {
    "1": {"nama": "Buku Pemrograman Python", "harga": 65000},
    "2": {"nama": "Flashdisk SanDisk 32GB", "harga": 50000},
    "3": {"nama": "Mouse Wireless Logitech", "harga": 85000}
}

keranjang = []

print("=== KATALOG TOKO TEKNOLOGI WIKRAMA ===")
for kode, barang in katalog.items():
    print(f"[{kode}] {barang[\'nama\']} - Rp {barang[\'harga\']:,}")

# Simulasi belanja 1 buku dan 1 mouse
keranjang.append({"nama": katalog["1"]["nama"], "qty": 1, "subtotal": 65000})
keranjang.append({"nama": katalog["3"]["nama"], "qty": 1, "subtotal": 85000})

total_belanja = sum(item["subtotal"] for item in keranjang)
uang_bayar = 200000
kembalian = uang_bayar - total_belanja

print("\n" + "=" * 36)
print("STRUK PEMBELIAN RESMI")
for item in keranjang:
    print(f"{item[\'nama\']} x{item[\'qty\']} : Rp {item[\'subtotal\']:,}")
print("-" * 36)
print(f"Total Belanja : Rp {total_belanja:,}")
print(f"Tunai         : Rp {uang_bayar:,}")
print(f"Kembalian     : Rp {kembalian:,}")
print("=" * 36)</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Pembayaran Kurang
        </div>
        <p>Pastikan ada pengecekan kondisi <code>if uang_bayar &lt; total_belanja:</code>. Kasir tidak boleh menyelesaikan transaksi dan mencetak kembalian bernilai negatif!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Format uang rupiah dengan f-string separator koma <code>{angka:,}</code> membuat tampilan struk langsung berstandar minimarket profesional.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Jika total transaksi belanja adalah Rp 145.000 dan pelanggan membayar dengan dua lembar uang seratus ribuan (Rp 200.000), berapakah uang kembalian yang harus diberikan kasir?',
                'option_a' => 'Rp 45.000',
                'option_b' => 'Rp 55.000',
                'option_c' => 'Rp 65.000',
                'option_d' => 'Rp 50.000',
                'correct_option' => 'B',
                'explanation' => 'Kembalian = Uang Bayar - Total Belanja = 200.000 - 145.000 = Rp 55.000.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Tindakan apakah yang harus diambil oleh sistem kasir jika uang_bayar lebih kecil daripada total_belanja?',
                'option_a' => 'Mencetak kembalian negatif dan menyelesaikan transaksi',
                'option_b' => 'Menolak penyelesaian transaksi, menampilkan pesan bahwa uang tunai kurang, dan meminta kekurangan pembayaran',
                'option_c' => 'Otomatis memberikan barang gratis',
                'option_d' => 'Menutup aplikasi paksa',
                'correct_option' => 'B',
                'explanation' => 'Sistem kasir ritel wajib memvalidasi kecukupan pembayaran pembeli sebelum transaksi dinyatakan sah dan dicetak struknya.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Manakah baris kode Python yang paling tepat untuk menghitung total akumulasi subtotal dari seluruh dictionary item di dalam keranjang belanja?',
                'option_a' => 'total = sum(item["subtotal"] for item in keranjang)',
                'option_b' => 'total = keranjang.sum()',
                'option_c' => 'total = count(keranjang)',
                'option_d' => 'total = keranjang["subtotal"]',
                'correct_option' => 'A',
                'explanation' => 'List comprehension generator `sum(item["subtotal"] for item in keranjang)` adalah cara standar dan paling efisien di Python untuk menjumlahkan nilai atribut list of dicts.',
                'xp_reward' => 20
            ]
        ]
    ],

    '8.4' => [
        'title' => '8.4 Jalur 3: Deep-Dive Buku Nilai Mini CLI',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-graduation-cap text-amber-600"></i> 1. Panduan Capstone Jalur 3: Aplikasi Rekap Nilai Siswa
        </div>
        <p>Di jalur ini, Anda membangun aplikasi buku nilai rombel kelas berbasis CLI dengan fitur CRUD (Create, Read, Update, Delete) lengkap. Pengguna (guru atau wali kelas) dapat menambah siswa baru, melihat seluruh daftar nilai dengan status KKM, mencari data siswa berdasarkan NIS unik, dan menghitung peringkat kelas!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-gears text-blue-600"></i> 2. Pola Menu CLI Interaktif
        </h4>
        <p>Aplikasi dikelola menggunakan loop <code>while True</code> dengan menu pilihan bernomor:</p>
        <pre class="bg-slate-900 text-slate-100 p-3 rounded-xl font-mono text-xs"><code>while True:
    print("[1] Lihat Daftar Nilai Siswa")
    print("[2] Tambah Data Siswa Baru")
    print("[3] Cari Siswa Berdasarkan NIS")
    print("[4] Keluar dari Aplikasi")
    pilihan = input("Pilih menu (1-4): ")</code></pre>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>data_siswa = [
    {"nis": "1001", "nama": "Ahmad Fadhil", "nilai": 88.0},
    {"nis": "1002", "nama": "Siti Nurhaliza", "nilai": 92.5},
    {"nis": "1003", "nama": "Budi Santoso", "nilai": 71.0}
]

def tampilkan_data():
    print("\n" + "=" * 45)
    print(f"{\'NIS\':<6} {\'NAMA SISWA\':<20} {\'NILAI\':<8} {\'STATUS\'}")
    print("-" * 45)
    for s in data_siswa:
        status = "LULUS" if s["nilai"] >= 75 else "REMEDIAL"
        print(f"{s[\'nis\']:<6} {s[\'nama\']:<20} {s[\'nilai\']:<8.1f} {status}")
    print("=" * 45)

def cari_siswa(nis_target):
    for s in data_siswa:
        if s["nis"] == nis_target:
            return s
    return None

# Contoh pemanggilan fungsi
tampilkan_data()
hasil_cari = cari_siswa("1002")
if hasil_cari:
    print(f"\nDitemukan: {hasil_cari[\'nama\']} dengan nilai {hasil_cari[\'nilai\']}")
else:
    print("\nSiswa tidak ditemukan!")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error: Duplikasi NIS
        </div>
        <p>Di sekolah, Nomor Induk Siswa (NIS) bersifat unik. Saat menambah siswa baru, selalu lakukan pemeriksaan apakah NIS tersebut sudah pernah terdaftar sebelumnya agar data tidak tertukar.</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Gunakan format string rata kiri <code>{teks:&lt;20}</code> untuk membuat tabel data di terminal tampak sangat lurus dan rapi seperti tabel Excel.</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Apakah kepanjangan dari konsep dasar manajemen basis data CRUD dalam pengembangan software?',
                'option_a' => 'Create, Read, Update, Delete',
                'option_b' => 'Code, Run, Upgrade, Debug',
                'option_c' => 'Compile, Review, Undo, Deploy',
                'option_d' => 'Calculate, Return, Use, Divide',
                'correct_option' => 'A',
                'explanation' => 'CRUD merupakan singkatan dari empat operasi dasar manipulasi data persisten: Create (Buat), Read (Baca), Update (Ubah), Delete (Hapus).',
                'xp_reward' => 15
            ],
            [
                'question' => 'Jika KKM adalah 75, apakah status kelulusan untuk siswa bernama Budi Santoso yang memiliki nilai 71.0?',
                'option_a' => 'LULUS',
                'option_b' => 'REMEDIAL',
                'option_c' => 'DISPENSASI',
                'option_d' => 'None',
                'correct_option' => 'B',
                'explanation' => 'Karena 71.0 lebih kecil dari batas KKM 75, maka status yang diberikan adalah REMEDIAL.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Mengapa pencarian data siswa paling tepat dilakukan berdasarkan nomor unik NIS daripada menggunakan nama siswa?',
                'option_a' => 'Karena angka lebih mudah diketik',
                'option_b' => 'Karena kemungkinan ada siswa dengan nama panggilan yang sama (kembar), sedangkan NIS dijamin selalu unik untuk satu siswa',
                'option_c' => 'Karena fungsi pencarian Python hanya menerima integer',
                'option_d' => 'Agar ukuran memori lebih hemat',
                'correct_option' => 'B',
                'explanation' => 'Primary identifier seperti NIS bersifat unik (unique identifier) sehingga menghilangkan ambiguitas jika ada siswa dengan nama serupa.',
                'xp_reward' => 20
            ]
        ]
    ],

    '8.5' => [
        'title' => '8.5 Boss Challenge: Deklarasi Pengumpulan Capstone',
        'content_html' => '
<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
            <i class="fa-solid fa-crown text-amber-600"></i> 1. Puncak Kelulusan Siswa: Verifikasi Capstone Final
        </div>
        <p>SELAMAT! Anda telah tiba di garis akhir Akademi Python PyLMS SMK Wikrama 1 Garut. Ini adalah gerbang kelulusan di mana Anda memverifikasi proyek akhir Capstone Anda terhadap <strong>5 Pilar Kompetensi Rekayasa Perangkat Lunak</strong> sebelum dinyatakan kompeten dan siap terjun ke industri teknologi!</p>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-blue-600"></i> 2. Matriks 5 Pilar Penilaian Kelulusan
        </h4>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-100 text-slate-800 font-bold">
                    <tr>
                        <th class="p-2.5 text-left">Pilar Kompetensi</th>
                        <th class="p-2.5 text-left">Implementasi Minimal Wajib</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    <tr>
                        <td class="p-2 font-semibold">1. Data &amp; Variabel</td>
                        <td class="p-2">Tipe data primitif (str, int, float, bool) terintegrasi rapi.</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-semibold">2. Percabangan</td>
                        <td class="p-2">Pengambilan keputusan multi-cabang (<code>if-elif-else</code>) terarah.</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-semibold">3. Perulangan</td>
                        <td class="p-2">Navigasi aplikasi berulang dengan <code>while</code> atau <code>for</code> loop.</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-semibold">4. Koleksi Data</td>
                        <td class="p-2">Penyimpanan terstruktur memanfaatkan <code>List</code> atau <code>Dictionary</code>.</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-semibold">5. Modularitas / I/O</td>
                        <td class="p-2">Pemecahan kode menggunakan fungsi (<code>def</code>) atau penyimpanan file teks (<code>open</code>).</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-code text-indigo-600"></i> 3. Program Evaluator Kelulusan Capstone
        </h4>
        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code># Program Verifikator Capstone Otomatis
pilar_kompetensi = {
    "Variabel & Tipe Data"   : True,
    "Percabangan Logika"     : True,
    "Perulangan (Loop)"      : True,
    "Struktur List/Dict"     : True,
    "Fungsi Modular/File I/O": True
}

skor = sum(1 for status in pilar_kompetensi.values() if status)
persentase = (skor / len(pilar_kompetensi)) * 100

print("=== DEKLARASI KELULUSAN CAPSTONE PYTHON ===")
for pilar, terpenuhi in pilar_kompetensi.items():
    simbol = "[V]" if terpenuhi else "[X]"
    print(f"{simbol} {pilar}")

print("-" * 42)
print(f"Kelengkapan Syarat : {skor}/5 Pilar ({persentase:.0f}%)")

if persentase == 100:
    print("\nSTATUS: DINYATAKAN LULUS KOMPETENSI REKAYASA PERANGKAT LUNAK!")
    print("Selamat atas kerja keras dan konsistensi Anda!")
else:
    print("\nSTATUS: Masih ada pilar yang perlu disempurnakan.")</code></pre>
    </div>

    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">
        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">
            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Pesan Integritas Akademik
        </div>
        <p>Kode yang hebat bukan kode yang disalin tanpa dipahami, melainkan baris kode yang Anda ketik, uji, dan pertahankan sendiri logikanya. Pertahankan kebiasaan menulis kode bersih dan jujur ini di dunia industri kelak!</p>
    </div>

    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div>
            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>
            <p class="text-xs text-emerald-800 mt-0.5">Anda dapat membagikan kode proyek Capstone Anda ke guru atau teman rombel melalui fitur Ekspor Kode di profil PyLMS!</p>
        </div>
    </div>
</div>',
        'quizzes' => [
            [
                'question' => 'Berapa banyak pilar kompetensi dasar rekayasa perangkat lunak yang wajib dipenuhi dalam sebuah proyek Capstone Python berkualitas?',
                'option_a' => '1 pilar',
                'option_b' => '3 pilar',
                'option_c' => '5 pilar utama (Variabel, Percabangan, Perulangan, Struktur Data, Modularitas/IO)',
                'option_d' => '100 pilar',
                'correct_option' => 'C',
                'explanation' => 'Standar kurikulum menetapkan 5 pilar inti: Tipe Data/Variabel, Kontrol Percabangan, Kontrol Perulangan, Koleksi Struktur Data, dan Fungsi Modular/File I/O.',
                'xp_reward' => 15
            ],
            [
                'question' => 'Jika sebuah program Capstone siswa memenuhi kelima pilar kompetensi secara lengkap (5 dari 5 terpenuhi), berapakah persentase kelengkapan yang dicapai?',
                'option_a' => '80%',
                'option_b' => '90%',
                'option_c' => '100% (Kompeten Sempurna)',
                'option_d' => '50%',
                'correct_option' => 'C',
                'explanation' => '(5 / 5) * 100 = 100%. Siswa telah menguasai seluruh spektrum materi kurikulum.',
                'xp_reward' => 20
            ],
            [
                'question' => 'Sikap integritas profesional apakah yang paling berharga bagi seorang calon programmer muda saat menyelesaikan Capstone Project?',
                'option_a' => 'Menyalin seluruh proyek orang lain dari internet tanpa memahaminya',
                'option_b' => 'Menulis kode sendiri dengan pemahaman mendalam, mampu menjelaskan cara kerja baris per baris, dan jujur pada hasil uji coba',
                'option_c' => 'Menghapus komentar dokumentasi agar kode terlihat misterius',
                'option_d' => 'Menolak melakukan pengujian bug',
                'correct_option' => 'B',
                'explanation' => 'Integritas akademik dan profesional diwujudkan dengan memahami kode yang ditulis sendiri serta siap mempertanggungjawabkannya di hadapan penguji.',
                'xp_reward' => 20
            ]
        ]
    ]
];
