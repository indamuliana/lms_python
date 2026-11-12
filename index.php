<?php
/**
 * PyLMS Landing Page
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    $user = current_user();
    if ($user && $user['role'] === 'guru') {
        header('Location: ' . base_url('teacher/dashboard.php'));
    } else {
        header('Location: ' . base_url('student/dashboard.php'));
    }
    exit;
}

$pageTitle = 'Belajar Python Interaktif Ramah HP';
require_once __DIR__ . '/includes/header.php';
?>

<div class="overflow-hidden">
    <!-- Hero Section -->
    <section class="relative py-12 sm:py-20 bg-gradient-to-b from-blue-50/60 via-white to-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge Promo -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100/80 border border-blue-200 text-blue-800 text-xs font-bold mb-6">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span>Dioptimalkan Khusus untuk Belajar Lewat HP Android & iOS</span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Kuasai Pemrograman Python <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-600 bg-clip-text text-transparent">
                    Langsung dari Smartphone Anda
                </span>
            </h1>

            <p class="mt-4 sm:mt-6 max-w-2xl mx-auto text-sm sm:text-base text-slate-600 leading-relaxed">
                Platform pembelajaran Python gaya W3Schools dengan emulator Python WebAssembly asli di browser, tombol pintasan simbol HP, kuis interaktif, tantangan auto-grading, dan buku nilai lengkap untuk Guru.
            </p>

            <!-- CTA Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= base_url('login.php') ?>" class="w-full sm:w-auto px-7 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/25 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i> Masuk dan Mulai Belajar
                </a>
                <a href="<?= base_url('leaderboard.php') ?>" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-sm shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-900"></i> Papan Peringkat Siswa
                </a>
                <a href="#kurikulum" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-bold text-sm border border-slate-300 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-book-bookmark text-slate-500"></i> Kurikulum
                </a>
            </div>

            <!-- Preview Fitur Unggulan Mobile -->
            <div class="mt-12 sm:mt-16 max-w-4xl mx-auto bg-slate-900 rounded-2xl p-4 sm:p-6 shadow-2xl border border-slate-800 text-left">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800 text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="ml-2 font-mono text-slate-300 font-semibold">py_emulator_mobile.py</span>
                    </div>
                    <span class="bg-blue-900/60 text-blue-300 px-2.5 py-0.5 rounded text-[11px] font-semibold">
                        <i class="fa-brands fa-python text-yellow-400 mr-1"></i> Pyodide CPython 3.11
                    </span>
                </div>
                
                <!-- Quick Mobile Toolbar Preview -->
                <div class="mt-3 bg-slate-800/80 p-2 rounded-xl flex items-center gap-1.5 overflow-x-auto text-xs font-mono text-slate-200">
                    <span class="text-[11px] text-slate-400 font-sans font-semibold mr-1">HP Bar:</span>
                    <span class="bg-slate-700 px-2 py-1 rounded">Tab ⇥</span>
                    <span class="bg-slate-700 px-2 py-1 rounded">:</span>
                    <span class="bg-slate-700 px-2 py-1 rounded">( )</span>
                    <span class="bg-slate-700 px-2 py-1 rounded">" "</span>
                    <span class="bg-slate-700 px-2 py-1 rounded">[ ]</span>
                    <span class="bg-slate-700 px-2 py-1 rounded">{ }</span>
                    <span class="bg-slate-700 px-2 py-1 rounded">==</span>
                    <span class="bg-blue-600 text-white px-2.5 py-1 rounded font-bold">▶ Run</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 font-mono text-xs">
                    <div class="bg-slate-950/70 p-3.5 rounded-xl border border-slate-800">
                        <div class="text-slate-400 text-[11px] mb-2">// Editor Interaktif:</div>
                        <div class="text-pink-400">nama <span class="text-white">=</span> <span class="text-emerald-300">"Siswa Hebat"</span></div>
                        <div class="text-pink-400">skor <span class="text-white">=</span> <span class="text-yellow-300">100</span></div>
                        <div class="text-sky-400 mt-2">print<span class="text-white">(</span><span class="text-yellow-200">f"Selamat {nama}, skor: {skor}!"</span><span class="text-white">)</span></div>
                    </div>
                    <div class="bg-slate-950/70 p-3.5 rounded-xl border border-slate-800 flex flex-col justify-between">
                        <div>
                            <div class="text-slate-400 text-[11px] mb-2">// Output Terminal Langsung:</div>
                            <div class="text-emerald-400 font-bold">Selamat Siswa Hebat, skor: 100!</div>
                        </div>
                        <div class="text-[11px] text-slate-500 pt-2 border-t border-slate-800/80">
                            100% Client-Side di HP &bull; Aman & Cepat
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 Pilar Fitur Utama -->
    <section class="py-12 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Fitur 1 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Ramah Keyboard HP</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Tak perlu repot berpindah layer keyboard HP untuk mencari simbol <code>:</code>, <code>( )</code>, <code>[ ]</code>, atau indentasi Tab 4 spasi. Semua tersedia dalam 1 tap!
                    </p>
                </div>

                <!-- Fitur 2 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Shared Hosting Ready</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Dirancang khusus tanpa ketergantungan symbolic link. Kode Python berjalan 100% di browser siswa lewat WebAssembly, menjaga server cPanel tetap ringan dan hemat biaya.
                    </p>
                </div>

                <!-- Fitur 3 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-table-list"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Otomasi Guru & Buku Nilai</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Guru cukup mengimpor file CSV siswa. Sistem otomatis mengelompokkan Rombel dan Rayon, memantau buku nilai (gradebook), dan menyediakan ekspor Excel sekali klik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kurikulum 8 Part Section -->
    <section id="kurikulum" class="py-14 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Roadmap 8 Part Belajar Python</h2>
                <p class="mt-2 text-sm text-slate-600">Disusun sistematis dari pengenalan dasar hingga mahir tingkat dasar dengan studi kasus nyata di setiap bagian.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Part 1 -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-blue-600 mb-2">
                            <span>PART 1</span>
                            <span class="p-1 rounded bg-blue-50 text-blue-500"><i class="fa-solid fa-seedling"></i></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1.5">Pengenalan Python</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Print, komentar, variabel, dan tipe data primitif (int, float, str, bool).</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-laptop-code text-blue-500"></i> Proyek Biodata Siswa
                    </div>
                </div>

                <!-- Part 2 -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-indigo-600 mb-2">
                            <span>PART 2</span>
                            <span class="p-1 rounded bg-indigo-50 text-indigo-500"><i class="fa-solid fa-calculator"></i></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1.5">Operator & Input</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Aritmatika, perbandingan, logika, input(), type casting, dan f-strings.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-laptop-code text-indigo-500"></i> Kalkulator & Suhu
                    </div>
                </div>

                <!-- Part 3 -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-sky-600 mb-2">
                            <span>PART 3</span>
                            <span class="p-1 rounded bg-sky-50 text-sky-500"><i class="fa-solid fa-code-branch"></i></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1.5">Percabangan Logika</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Struktur if, elif, else, nested if, dan evaluasi kelulusan nilai siswa.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-laptop-code text-sky-500"></i> Sistem KKM Kelulusan
                    </div>
                </div>

                <!-- Part 4 -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-emerald-600 mb-2">
                            <span>PART 4</span>
                            <span class="p-1 rounded bg-emerald-50 text-emerald-500"><i class="fa-solid fa-arrows-rotate"></i></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1.5">Perulangan (Loops)</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Perulangan for, while, fungsi range(), break, continue, dan pola angka.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-laptop-code text-emerald-500"></i> Game Tebak Angka
                    </div>
                </div>

                <!-- Part 5 -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-amber-600 mb-2">
                            <span>PART 5</span>
                            <span class="p-1 rounded bg-amber-50 text-amber-500"><i class="fa-solid fa-boxes-stacked"></i></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1.5">Struktur Data</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">List, Tuple, Dictionary, Set, manipulasi item, indexing dan slicing.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-laptop-code text-amber-500"></i> Rekap Nilai Rombel
                    </div>
                </div>

                <!-- Part 6 -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-orange-600 mb-2">
                            <span>PART 6</span>
                            <span class="p-1 rounded bg-orange-50 text-orange-500"><i class="fa-solid fa-cubes"></i></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1.5">Fungsi & Modularitas</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Mendefinisikan fungsi def, parameter, argumen, return, dan variable scope.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-laptop-code text-orange-500"></i> Kasir Toko Modular
                    </div>
                </div>

                <!-- Part 7 -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-cyan-600 mb-2">
                            <span>PART 7</span>
                            <span class="p-1 rounded bg-cyan-50 text-cyan-500"><i class="fa-solid fa-shield-halved"></i></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1.5">File & Error Handling</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Membaca & menulis file dengan open(), try-except, dan modul bawaan.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-laptop-code text-cyan-500"></i> Catatan File Anti-Crash
                    </div>
                </div>

                <!-- Part 8 -->
                <div class="bg-white p-5 rounded-2xl border border-purple-200 shadow-sm flex flex-col justify-between ring-2 ring-purple-500/20">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold text-purple-600 mb-2">
                            <span>PART 8 (FINAL)</span>
                            <span class="p-1 rounded bg-purple-50 text-purple-500"><i class="fa-solid fa-trophy"></i></span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1.5">Capstone Project</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Pilihan 3 jalur proyek utuh untuk dinilai langsung oleh Guru pembimbing.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-purple-600 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-star text-amber-500"></i> Review Kelulusan
                    </div>
                </div>
            </div>

            <!-- Box Ajakan Masuk -->
            <div class="mt-12 text-center">
                <a href="<?= base_url('login.php') ?>" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition">
                    Mulai Belajar Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
