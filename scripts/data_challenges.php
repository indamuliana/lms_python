<?php
/**
 * Master Data 40 Tantangan Koding PyLMS (Part 1 s/d Part 8)
 * Digunakan untuk sinkronisasi kurikulum dan reset tantangan standar.
 */
return array (
  '1.1' => 
  array (
    'title' => 'Tantangan 1.1: Mencetak Kalimat Sambutan',
    'instructions' => 'Cetaklah tepat teks berikut:
Saya Siap Belajar Python',
    'starter_code' => 'print("")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Kalimat Sambutan',
        'expected_output' => 'Saya Siap Belajar Python',
      ),
    ),
    'hint' => 'Gunakan print("Saya Siap Belajar Python")',
    'xp_reward' => 30,
  ),
  '1.2' => 
  array (
    'title' => 'Tantangan 1.2: Baris Kode Bersih dengan Komentar',
    'instructions' => 'Tuliskan satu baris komentar di baris pertama, kemudian di baris kedua cetak teks:
KODE BERHASIL DIJALANKAN',
    'starter_code' => '# Tulis komentar di sini
',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Output Teks',
        'expected_output' => 'KODE BERHASIL DIJALANKAN',
      ),
    ),
    'hint' => 'Awali baris 1 dengan tanda #, lalu di baris 2 tulis print("KODE BERHASIL DIJALANKAN")',
    'xp_reward' => 30,
  ),
  '1.3' => 
  array (
    'title' => 'Tantangan 1.3: Debugging Penamaan Variabel',
    'instructions' => 'Perbaiki kode yang salah sintaks berikut agar variabel bernama skor_maksimal memiliki nilai 100 dan dicetak:
Output yang diharapkan:
Skor: 100',
    'starter_code' => '# Perbaiki baris di bawah:
# 1_skor = 100
# print("Skor:", 1_skor)
',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Variabel Valid',
        'expected_output' => 'Skor: 100',
      ),
    ),
    'hint' => 'Ganti 1_skor menjadi skor_maksimal = 100 lalu print("Skor:", skor_maksimal)',
    'xp_reward' => 40,
  ),
  '1.4' => 
  array (
    'title' => 'Tantangan 1.4: Cetak 4 Tipe Data',
    'instructions' => 'Buat 4 baris print yang masing-masing mencetak:
Angka: 25
Desimal: 7.5
Teks: Sukses
Aktif: True',
    'starter_code' => '# Buat 4 perintah print di bawah ini',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek 4 Baris Tipe Data',
        'expected_output' => 'Angka: 25
Desimal: 7.5
Teks: Sukses
Aktif: True',
      ),
    ),
    'hint' => 'Cetak sesuai label dan nilainya persis di 4 baris terpisah.',
    'xp_reward' => 40,
  ),
  '1.5' => 
  array (
    'title' => 'Tantangan 1.5 (Boss): Kartu Biodata Digital',
    'instructions' => 'Buatlah program yang mencetak tepat 4 baris berikut:
=== KARTU SISWA ===
NAMA: Budi Santoso
KELAS: PPLG X-1
STATUS: AKTIF',
    'starter_code' => 'print("=== KARTU SISWA ===")
',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Format Kartu Biodata',
        'expected_output' => '=== KARTU SISWA ===
NAMA: Budi Santoso
KELAS: PPLG X-1
STATUS: AKTIF',
      ),
    ),
    'hint' => 'Cetak baris demi baris menggunakan print() dengan teks yang persis.',
    'xp_reward' => 50,
  ),
  '2.1' => 
  array (
    'title' => 'Tantangan 2.1: Menghitung Luas dan Keliling',
    'instructions' => 'Diberikan panjang = 10 dan lebar = 5. Hitung luas (panjang * lebar) dan keliling (2 * (panjang + lebar)).
Cetak:
Luas: 50
Keliling: 30',
    'starter_code' => 'panjang = 10
lebar = 5
# Hitung dan cetak
',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Luas dan Keliling',
        'expected_output' => 'Luas: 50
Keliling: 30',
      ),
    ),
    'hint' => 'luas = panjang * lebar; keliling = 2 * (panjang + lebar)',
    'xp_reward' => 30,
  ),
  '2.2' => 
  array (
    'title' => 'Tantangan 2.2: Pengganda Angka',
    'instructions' => 'Ambil satu angka input dari user, kalikan dengan 2, lalu cetak hasilnya dalam format:
Hasil: <nilai>',
    'starter_code' => 'angka = int(input())
# Kalikan dengan 2 dan cetak
',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji Angka 7',
        'inputs' => 
        array (
          0 => '7',
        ),
        'expected_output' => 'Hasil: 14',
      ),
      1 => 
      array (
        'name' => 'Uji Angka 25',
        'inputs' => 
        array (
          0 => '25',
        ),
        'expected_output' => 'Hasil: 50',
      ),
    ),
    'hint' => 'print(f"Hasil: {angka * 2}")',
    'xp_reward' => 30,
  ),
  '2.3' => 
  array (
    'title' => 'Tantangan 2.3: Perbaiki Bug Penjumlahan Input',
    'instructions' => 'Perbaiki program agar menerima 2 baris input angka, lalu mencetak jumlah matematikanya:
Jumlah: <hasil>',
    'starter_code' => 'a = int(input())
b = int(input())
print(f"Jumlah: {a + b}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji 15 + 25',
        'inputs' => 
        array (
          0 => '15',
          1 => '25',
        ),
        'expected_output' => 'Jumlah: 40',
      ),
    ),
    'hint' => 'Pastikan menggunakan int(input()) untuk kedua variabel.',
    'xp_reward' => 40,
  ),
  '2.4' => 
  array (
    'title' => 'Tantangan 2.4: Cetak Struk Belanja dengan F-String',
    'instructions' => 'Diberikan produk = "Pensil", qty = 4, harga = 2000. Cetak tepat:
Beli 4 Pensil seharga Rp 8000',
    'starter_code' => 'produk = "Pensil"
qty = 4
harga = 2000
# Cetak dengan f-string
',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Struk Pensil',
        'expected_output' => 'Beli 4 Pensil seharga Rp 8000',
      ),
    ),
    'hint' => 'print(f"Beli {qty} {produk} seharga Rp {qty * harga}")',
    'xp_reward' => 40,
  ),
  '2.5' => 
  array (
    'title' => 'Tantangan 2.5 (Boss): Konverter Suhu Lengkap',
    'instructions' => 'Program menerima satu angka input suhu Celcius. Cetak tepat 2 baris:
Fahrenheit: <nilai>
Reamur: <nilai>',
    'starter_code' => 'celcius = float(input())
fah = (celcius * 9/5) + 32
rea = celcius * 4/5
print(f"Fahrenheit: {fah}")
print(f"Reamur: {rea}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji 100 C',
        'inputs' => 
        array (
          0 => '100',
        ),
        'expected_output' => 'Fahrenheit: 212.0
Reamur: 80.0',
      ),
      1 => 
      array (
        'name' => 'Uji 0 C',
        'inputs' => 
        array (
          0 => '0',
        ),
        'expected_output' => 'Fahrenheit: 32.0
Reamur: 0.0',
      ),
    ),
    'hint' => 'Gunakan rumus konversi dan cetak 2 baris terpisah.',
    'xp_reward' => 50,
  ),
  '3.1' => 
  array (
    'title' => 'Tantangan 3.1: Pengecekan Angka Positif',
    'instructions' => 'Ambil satu angka input integer. Jika angka > 0, cetak "POSITIF". Jika tidak, cetak "BUKAN POSITIF".',
    'starter_code' => 'angka = int(input())
if angka > 0:
    print("POSITIF")
else:
    print("BUKAN POSITIF")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji 10',
        'inputs' => 
        array (
          0 => '10',
        ),
        'expected_output' => 'POSITIF',
      ),
      1 => 
      array (
        'name' => 'Uji -5',
        'inputs' => 
        array (
          0 => '-5',
        ),
        'expected_output' => 'BUKAN POSITIF',
      ),
    ),
    'hint' => 'Gunakan if angka > 0:',
    'xp_reward' => 30,
  ),
  '3.2' => 
  array (
    'title' => 'Tantangan 3.2: Penentu Grade Nilai',
    'instructions' => 'Buat program penentu grade:
Nilai >= 85: "GRADE A"
Nilai >= 70: "GRADE B"
Lainnya: "GRADE C"',
    'starter_code' => 'nilai = int(input())
if nilai >= 85:
    print("GRADE A")
elif nilai >= 70:
    print("GRADE B")
else:
    print("GRADE C")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji 90',
        'inputs' => 
        array (
          0 => '90',
        ),
        'expected_output' => 'GRADE A',
      ),
      1 => 
      array (
        'name' => 'Uji 75',
        'inputs' => 
        array (
          0 => '75',
        ),
        'expected_output' => 'GRADE B',
      ),
      2 => 
      array (
        'name' => 'Uji 50',
        'inputs' => 
        array (
          0 => '50',
        ),
        'expected_output' => 'GRADE C',
      ),
    ),
    'hint' => 'Gunakan if-elif-else terurut dari nilai terbesar.',
    'xp_reward' => 30,
  ),
  '3.3' => 
  array (
    'title' => 'Tantangan 3.3: Seleksi Penerima Diskon Kafe',
    'instructions' => 'Siswa berhak diskon jika umur < 18 ATAU memiliki status member True. Input baris 1: umur (int), baris 2: member ("ya" / "tidak"). Jika berhak diskon, cetak "DAPAT DISKON", jika tidak cetak "HARGA NORMAL".',
    'starter_code' => 'umur = int(input())
member = input()
if umur < 18 or member == "ya":
    print("DAPAT DISKON")
else:
    print("HARGA NORMAL")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji Umur 16 Non Member',
        'inputs' => 
        array (
          0 => '16',
          1 => 'tidak',
        ),
        'expected_output' => 'DAPAT DISKON',
      ),
      1 => 
      array (
        'name' => 'Uji Umur 25 Non Member',
        'inputs' => 
        array (
          0 => '25',
          1 => 'tidak',
        ),
        'expected_output' => 'HARGA NORMAL',
      ),
    ),
    'hint' => 'Gunakan operator or: if umur < 18 or member == "ya":',
    'xp_reward' => 40,
  ),
  '3.4' => 
  array (
    'title' => 'Tantangan 3.4: Validasi Gerbang Masuk',
    'instructions' => 'Input baris 1: status kartu ("aktif"/"nonaktif"). Input baris 2: saldo tiket (int). Jika kartu "aktif", periksa saldo: jika saldo >= 5000 cetak "PINTU TERBUKA", jika saldo < 5000 cetak "SALDO KURANG". Jika kartu bukan "aktif", cetak "KARTU TIDAK VALID".',
    'starter_code' => 'kartu = input()
saldo = int(input())
if kartu == "aktif":
    if saldo >= 5000:
        print("PINTU TERBUKA")
    else:
        print("SALDO KURANG")
else:
    print("KARTU TIDAK VALID")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji Aktif Cukup Saldo',
        'inputs' => 
        array (
          0 => 'aktif',
          1 => '10000',
        ),
        'expected_output' => 'PINTU TERBUKA',
      ),
      1 => 
      array (
        'name' => 'Uji Kartu Rusak',
        'inputs' => 
        array (
          0 => 'blokir',
          1 => '5000',
        ),
        'expected_output' => 'KARTU TIDAK VALID',
      ),
    ),
    'hint' => 'Gunakan percabangan bersarang if kartu == "aktif": lalu if saldo >= 5000:',
    'xp_reward' => 40,
  ),
  '3.5' => 
  array (
    'title' => 'Tantangan 3.5 (Boss): Sistem Kelulusan Mandiri',
    'instructions' => 'Input baris 1: nilai (int), baris 2: kehadiran (int), baris 3: sikap (str). Jika nilai >= 75 dan kehadiran >= 80 dan (sikap == "A" or sikap == "B"), cetak "STATUS: LULUS". Jika tidak, cetak "STATUS: TIDAK LULUS".',
    'starter_code' => 'nilai = int(input())
kehadiran = int(input())
sikap = input()

if nilai >= 75 and kehadiran >= 80 and (sikap == "A" or sikap == "B"):
    print("STATUS: LULUS")
else:
    print("STATUS: TIDAK LULUS")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji Lulus',
        'inputs' => 
        array (
          0 => '85',
          1 => '90',
          2 => 'A',
        ),
        'expected_output' => 'STATUS: LULUS',
      ),
      1 => 
      array (
        'name' => 'Uji Nilai Kurang',
        'inputs' => 
        array (
          0 => '70',
          1 => '95',
          2 => 'A',
        ),
        'expected_output' => 'STATUS: TIDAK LULUS',
      ),
    ),
    'hint' => 'Perhatikan tanda kurung pada (sikap == "A" or sikap == "B") agar logika tidak tertukar.',
    'xp_reward' => 50,
  ),
  '4.1' => 
  array (
    'title' => 'Tantangan 4.1: Cetak Deret Angka Kelipatan 3',
    'instructions' => 'Gunakan for loop dan range untuk mencetak angka kelipatan 3 dari 3 sampai 9, masing-masing di baris baru (3, 6, 9).',
    'starter_code' => 'for i in range(3, 10, 3):
    print(i)',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Kelipatan 3',
        'expected_output' => '3
6
9',
      ),
    ),
    'hint' => 'Gunakan range(3, 10, 3)',
    'xp_reward' => 30,
  ),
  '4.2' => 
  array (
    'title' => 'Tantangan 4.2: Hitung Mundur Meluncur',
    'instructions' => 'Gunakan perulangan while untuk mencetak angka dari 3 turun ke 1, lalu di baris terakhir cetak "MELUNCUR!".
Output:
3
2
1
MELUNCUR!',
    'starter_code' => 'x = 3
while x >= 1:
    print(x)
    x -= 1
print("MELUNCUR!")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Hitung Mundur',
        'expected_output' => '3
2
1
MELUNCUR!',
      ),
    ),
    'hint' => 'Gunakan x = 3, while x >= 1: print(x); x -= 1',
    'xp_reward' => 30,
  ),
  '4.3' => 
  array (
    'title' => 'Tantangan 4.3: Perbaiki Akumulator While Loop',
    'instructions' => 'Perbaiki while loop agar menghitung total penjumlahan angka dari 1 sampai 5 (1+2+3+4+5 = 15) dan mencetak:
Jumlah: 15',
    'starter_code' => 'i = 1
total = 0
while i <= 5:
    total += i
    i += 1
print(f"Jumlah: {total}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Jumlah 1 s/d 5',
        'expected_output' => 'Jumlah: 15',
      ),
    ),
    'hint' => 'Pastikan while berjalan dari i = 1 sampai i <= 5 dan di dalam loop ada i += 1.',
    'xp_reward' => 40,
  ),
  '4.4' => 
  array (
    'title' => 'Tantangan 4.4: Cetak Pola Segitiga 3 Tingkat',
    'instructions' => 'Gunakan loop untuk mencetak pola bintang berikut:
*
**
***',
    'starter_code' => 'for i in range(1, 4):
    print("*" * i)',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Segitiga Bintang',
        'expected_output' => '*
**
***',
      ),
    ),
    'hint' => 'Gunakan print("*" * i) di dalam loop range(1, 4)',
    'xp_reward' => 40,
  ),
  '4.5' => 
  array (
    'title' => 'Tantangan 4.5 (Boss): Simulator Keamanan PIN',
    'instructions' => 'Buat simulator PIN dengan PIN benar "1234" dan maksimal 3x coba. Jika benar cetak "AKSES DITERIMA", jika 3x gagal cetak "KARTU DIBLOKIR".',
    'starter_code' => 'pin_benar = "1234"
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
    print("KARTU DIBLOKIR")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'PIN Langsung Benar',
        'inputs' => 
        array (
          0 => '1234',
        ),
        'expected_output' => 'AKSES DITERIMA',
      ),
      1 => 
      array (
        'name' => 'PIN 3x Salah',
        'inputs' => 
        array (
          0 => '0000',
          1 => '1111',
          2 => '2222',
        ),
        'expected_output' => 'KARTU DIBLOKIR',
      ),
    ),
    'hint' => 'Gunakan while percobaan < 3: dan periksa input setiap putaran.',
    'xp_reward' => 50,
  ),
  '5.1' => 
  array (
    'title' => 'Tantangan 5.1: Mengakses Elemen List',
    'instructions' => 'Diberikan list data = ["Merah", "Kuning", "Hijau"]. Cetak elemen pertama dan terakhir dalam format:
Awal: Merah
Akhir: Hijau',
    'starter_code' => 'data = ["Merah", "Kuning", "Hijau"]
print(f"Awal: {data[0]}")
print(f"Akhir: {data[-1]}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Awal dan Akhir',
        'expected_output' => 'Awal: Merah
Akhir: Hijau',
      ),
    ),
    'hint' => 'Gunakan data[0] dan data[-1]',
    'xp_reward' => 30,
  ),
  '5.2' => 
  array (
    'title' => 'Tantangan 5.2: Tambah & Urutkan Nilai',
    'instructions' => 'Buat list nilai = [80, 65, 90]. Tambahkan angka 75 ke dalam list menggunakan append(), urutkan dengan sort(), lalu cetak nilai terkecil (indeks ke-0):
Terkecil: 65',
    'starter_code' => 'nilai = [80, 65, 90]
nilai.append(75)
nilai.sort()
print(f"Terkecil: {nilai[0]}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Nilai Terkecil',
        'expected_output' => 'Terkecil: 65',
      ),
    ),
    'hint' => 'append 75, lalu sort(), lalu print(f"Terkecil: {nilai[0]}")',
    'xp_reward' => 30,
  ),
  '5.3' => 
  array (
    'title' => 'Tantangan 5.3: Iterasi List yang Aman',
    'instructions' => 'Diberikan list skor = [10, 20, 30]. Perbaiki perulangan agar mencetak ketiga elemen tersebut secara aman baris demi baris.',
    'starter_code' => 'skor = [10, 20, 30]
for s in skor:
    print(s)',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek 3 Baris Skor',
        'expected_output' => '10
20
30',
      ),
    ),
    'hint' => 'Gunakan for s in skor: print(s)',
    'xp_reward' => 40,
  ),
  '5.4' => 
  array (
    'title' => 'Tantangan 5.4: Membuat Profil Dictionary',
    'instructions' => 'Buat dictionary dengan key "produk" bernilai "Buku" dan key "harga" bernilai 15000. Cetak:
Produk: Buku - Rp 15000',
    'starter_code' => 'item = {"produk": "Buku", "harga": 15000}
print(f"Produk: {item[\'produk\']} - Rp {item[\'harga\']}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Profil Produk',
        'expected_output' => 'Produk: Buku - Rp 15000',
      ),
    ),
    'hint' => 'Gunakan item["produk"] dan item["harga"] di dalam f-string.',
    'xp_reward' => 40,
  ),
  '5.5' => 
  array (
    'title' => 'Tantangan 5.5 (Boss): Analisis Nilai Siswa',
    'instructions' => 'Ambil 3 angka input integer ke dalam list. Cetak 2 baris:
Rata-rata: <nilai desimal>
Tertinggi: <nilai>',
    'starter_code' => 'daftar_nilai = [int(input()), int(input()), int(input())]
print(f"Rata-rata: {sum(daftar_nilai)/len(daftar_nilai)}")
print(f"Tertinggi: {max(daftar_nilai)}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji 80, 90, 100',
        'inputs' => 
        array (
          0 => '80',
          1 => '90',
          2 => '100',
        ),
        'expected_output' => 'Rata-rata: 90.0
Tertinggi: 100',
      ),
    ),
    'hint' => 'Gunakan sum() dan max()',
    'xp_reward' => 50,
  ),
  '6.1' => 
  array (
    'title' => 'Tantangan 6.1: Fungsi Cetak Header',
    'instructions' => 'Buat fungsi bernama cetak_header(judul) yang mencetak "=== JUDUL ===". Kemudian panggil fungsi tersebut dengan argumen "PYTHON".',
    'starter_code' => 'def cetak_header(judul):
    print(f"=== {judul} ===")

cetak_header("PYTHON")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Header Python',
        'expected_output' => '=== PYTHON ===',
      ),
    ),
    'hint' => 'def cetak_header(judul): print(f"=== {judul} ===")',
    'xp_reward' => 30,
  ),
  '6.2' => 
  array (
    'title' => 'Tantangan 6.2: Fungsi Luas Persegi Panjang',
    'instructions' => 'Buat fungsi bernama luas_persegi(p, l) yang mengembalikan (return) p * l. Panggil fungsi tersebut dengan nilai 8 dan 5, lalu cetak hasilnya:
Luas: 40',
    'starter_code' => 'def luas_persegi(p, l):
    return p * l

print(f"Luas: {luas_persegi(8, 5)}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Luas 8 x 5',
        'expected_output' => 'Luas: 40',
      ),
    ),
    'hint' => 'Gunakan return p * l',
    'xp_reward' => 30,
  ),
  '6.3' => 
  array (
    'title' => 'Tantangan 6.3: Perbaiki Akses Nilai Fungsi',
    'instructions' => 'Perbaiki kode agar fungsi mengembalikan total harga (harga + ongkir) dan dicetak di luar fungsi:
Total: 25000',
    'starter_code' => 'def hitung_total():
    harga = 20000
    ongkir = 5000
    return harga + ongkir

print(f"Total: {hitung_total()}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Total 25000',
        'expected_output' => 'Total: 25000',
      ),
    ),
    'hint' => 'Kembalikan harga + ongkir dengan return',
    'xp_reward' => 40,
  ),
  '6.4' => 
  array (
    'title' => 'Tantangan 6.4: Fungsi Konversi Detik ke Menit',
    'instructions' => 'Buat fungsi bernama detik_ke_menit(detik) yang mengembalikan 2 nilai (tuple): menit dan sisa detik (detik // 60, detik % 60). Panggil dengan argumen 130, cetak: "Menit: 2, Detik: 10".',
    'starter_code' => 'def detik_ke_menit(d):
    return d // 60, d % 60

m, s = detik_ke_menit(130)
print(f"Menit: {m}, Detik: {s}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek 130 detik',
        'expected_output' => 'Menit: 2, Detik: 10',
      ),
    ),
    'hint' => 'return d // 60, d % 60',
    'xp_reward' => 40,
  ),
  '6.5' => 
  array (
    'title' => 'Tantangan 6.5 (Boss): Kasir Modular Terintegrasi',
    'instructions' => 'Implementasikan kedua fungsi di atas. Input: baris 1 harga (10000), baris 2 qty (3), baris 3 diskon (10). Cetak:
Total Akhir: Rp 27000',
    'starter_code' => 'def hitung_subtotal(harga, qty):
    return harga * qty

def hitung_total_akhir(subtotal, diskon):
    return int(subtotal - (subtotal * diskon / 100))

h = int(input())
q = int(input())
d = int(input())
sub = hitung_subtotal(h, q)
tot = hitung_total_akhir(sub, d)
print(f"Total Akhir: Rp {tot}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji Diskon 10%',
        'inputs' => 
        array (
          0 => '10000',
          1 => '3',
          2 => '10',
        ),
        'expected_output' => 'Total Akhir: Rp 27000',
      ),
    ),
    'hint' => 'sub = hitung_subtotal(h, q); tot = hitung_total_akhir(sub, d)',
    'xp_reward' => 50,
  ),
  '7.1' => 
  array (
    'title' => 'Tantangan 7.1: Tangkal Pembagian dengan Nol',
    'instructions' => 'Input satu angka integer x. Coba hitung 50 // x. Jika terjadi ZeroDivisionError, cetak "GAGAL: BAGI NOL". Jika normal, cetak "HASIL: <nilai>".',
    'starter_code' => 'try:
    x = int(input())
    print(f"HASIL: {50 // x}")
except ZeroDivisionError:
    print("GAGAL: BAGI NOL")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji Normal 5',
        'inputs' => 
        array (
          0 => '5',
        ),
        'expected_output' => 'HASIL: 10',
      ),
      1 => 
      array (
        'name' => 'Uji Nol',
        'inputs' => 
        array (
          0 => '0',
        ),
        'expected_output' => 'GAGAL: BAGI NOL',
      ),
    ),
    'hint' => 'try: print(f"HASIL: {50 // x}") except ZeroDivisionError: print("GAGAL: BAGI NOL")',
    'xp_reward' => 30,
  ),
  '7.2' => 
  array (
    'title' => 'Tantangan 7.2: Tulis dan Baca Kembali File',
    'instructions' => 'Tulis teks "SUKSES TERSIMPAN" ke dalam file bernama "log.txt". Lalu baca dan cetak isinya ke layar.',
    'starter_code' => 'with open("log.txt", "w") as f:
    f.write("SUKSES TERSIMPAN")
with open("log.txt", "r") as f:
    print(f.read())',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Baca Tulis File',
        'expected_output' => 'SUKSES TERSIMPAN',
      ),
    ),
    'hint' => 'Gunakan with open("log.txt", "w") lalu with open("log.txt", "r")',
    'xp_reward' => 30,
  ),
  '7.3' => 
  array (
    'title' => 'Tantangan 7.3: Tangkal FileNotFoundError',
    'instructions' => 'Coba buka file bernama "data_siswa.txt" mode "r". Jika file tidak ditemukan (FileNotFoundError), cetak "STATUS: DATA BELUM DIBUAT".',
    'starter_code' => 'try:
    with open("data_siswa.txt", "r") as f:
        print(f.read())
except FileNotFoundError:
    print("STATUS: DATA BELUM DIBUAT")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Tangkal File Hilang',
        'expected_output' => 'STATUS: DATA BELUM DIBUAT',
      ),
    ),
    'hint' => 'Gunakan blok try-except FileNotFoundError',
    'xp_reward' => 40,
  ),
  '7.4' => 
  array (
    'title' => 'Tantangan 7.4: Menghitung Akar dengan math.sqrt',
    'instructions' => 'Ambil satu angka input float n (misal 64.0). Gunakan math.sqrt() untuk menghitung akarnya dan cetak:
Akar: <nilai desimal>',
    'starter_code' => 'import math
n = float(input())
print(f"Akar: {math.sqrt(n)}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji Akar 64',
        'inputs' => 
        array (
          0 => '64.0',
        ),
        'expected_output' => 'Akar: 8.0',
      ),
      1 => 
      array (
        'name' => 'Uji Akar 25',
        'inputs' => 
        array (
          0 => '25.0',
        ),
        'expected_output' => 'Akar: 5.0',
      ),
    ),
    'hint' => 'import math lalu print(f"Akar: {math.sqrt(n)}")',
    'xp_reward' => 40,
  ),
  '7.5' => 
  array (
    'title' => 'Tantangan 7.5 (Boss): Log Transaksi Lengkap',
    'instructions' => 'Input baris 1 nama barang (misal "Buku"), baris 2 harga (15000). Simpan ke "transaksi.txt", lalu cetak:
=== RIWAYAT TRANSAKSI ===
Buku: Rp 15000',
    'starter_code' => 'b = input()
h = int(input())
with open("transaksi.txt", "w") as f:
    f.write(f"{b}: Rp {h}
")
print("=== RIWAYAT TRANSAKSI ===") 
with open("transaksi.txt", "r") as f:
    print(f.read().strip())',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Uji Catatan Buku',
        'inputs' => 
        array (
          0 => 'Buku',
          1 => '15000',
        ),
        'expected_output' => '=== RIWAYAT TRANSAKSI ===
Buku: Rp 15000',
      ),
    ),
    'hint' => 'Tulis ke file lalu baca kembali',
    'xp_reward' => 50,
  ),
  '8.1' => 
  array (
    'title' => 'Tantangan 8.1: Uji Pemahaman Kode Bersih',
    'instructions' => 'Cetaklah tepat teks prinsip kode bersih:
KODE BERSIH: RAPI, MODULAR, TERUJI',
    'starter_code' => 'print("KODE BERSIH: RAPI, MODULAR, TERUJI")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Prinsip Clean Code',
        'expected_output' => 'KODE BERSIH: RAPI, MODULAR, TERUJI',
      ),
    ),
    'hint' => 'print("KODE BERSIH: RAPI, MODULAR, TERUJI")',
    'xp_reward' => 30,
  ),
  '8.2' => 
  array (
    'title' => 'Tantangan 8.2: Mini Health Bar Game',
    'instructions' => 'Buat fungsi render_hp(hp) yang mengembalikan simbol hati "â™¥ " sebanyak jumlah hp. Jika hp = 3, kembalikan "â™¥ â™¥ â™¥ ". Cetak: "Darah: â™¥ â™¥ â™¥ "',
    'starter_code' => 'def render_hp(hp):
    return ("â™¥ " * hp).strip()

print(f"Darah: {render_hp(3)}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Darah 3',
        'expected_output' => 'Darah: u2665 u2665 u2665',
      ),
    ),
    'hint' => 'return ("â™¥ " * hp).strip()',
    'xp_reward' => 30,
  ),
  '8.3' => 
  array (
    'title' => 'Tantangan 8.3: Format Baris Struk Belanja',
    'instructions' => 'Diberikan nama = "Kopi", qty = 2, subtotal = 30000. Cetak tepat:
STRUK: 2x Kopi = Rp 30000',
    'starter_code' => 'nama = "Kopi"
qty = 2
subtotal = 30000
print(f"STRUK: {qty}x {nama} = Rp {subtotal}")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Baris Struk',
        'expected_output' => 'STRUK: 2x Kopi = Rp 30000',
      ),
    ),
    'hint' => 'print(f"STRUK: {qty}x {nama} = Rp {subtotal}")',
    'xp_reward' => 30,
  ),
  '8.4' => 
  array (
    'title' => 'Tantangan 8.4: Format Status Siswa Terformat',
    'instructions' => 'Diberikan nama = "Farhan", nilai = 82, status = "LULUS". Cetak:
[HASIL] Farhan: 82 (LULUS)',
    'starter_code' => 'nama = "Farhan"
nilai = 82
status = "LULUS"
print(f"[HASIL] {nama}: {nilai} ({status})")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Format Hasil Siswa',
        'expected_output' => '[HASIL] Farhan: 82 (LULUS)',
      ),
    ),
    'hint' => 'print(f"[HASIL] {nama}: {nilai} ({status})")',
    'xp_reward' => 30,
  ),
  '8.5' => 
  array (
    'title' => 'Tantangan 8.5 (Boss): Deklarasi Kelulusan Lengkap',
    'instructions' => 'Cetak tepat 2 baris pernyataan kesiapan kelulusan:
STATUS: SELURUH MATERI SELESAI
PROYEK AKHIR: SIAP DIREVIEW',
    'starter_code' => 'print("STATUS: SELURUH MATERI SELESAI")
print("PROYEK AKHIR: SIAP DIREVIEW")',
    'test_cases' => 
    array (
      0 => 
      array (
        'name' => 'Cek Deklarasi Kelulusan',
        'expected_output' => 'STATUS: SELURUH MATERI SELESAI
PROYEK AKHIR: SIAP DIREVIEW',
      ),
    ),
    'hint' => 'Cetak kedua baris persis seperti yang diminta.',
    'xp_reward' => 50,
  ),
);
