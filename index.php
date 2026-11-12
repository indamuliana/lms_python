<?php
/**
 * PyLMS Landing Page - Gaming & Esports Arena Theme
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

$db = get_db();

// Ambil Statistik Live
$totalStudents = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn();
$totalXp = (int)$db->query("SELECT COALESCE(SUM(xp), 0) FROM users WHERE role = 'siswa'")->fetchColumn();

// Ambil Top 3 Siswa untuk Hall of Fame Mini
$topStudents = $db->query("
    SELECT id, full_name, nickname, xp, avatar, rombel 
    FROM users 
    WHERE role = 'siswa' 
    ORDER BY xp DESC, full_name ASC 
    LIMIT 3
")->fetchAll();

$mlRanks = get_ml_ranks();

$isGamingTheme = true;
$pageTitle = 'Arena Belajar Python & Push Rank Siswa';
require_once __DIR__ . '/includes/header.php';
?>

<div class="overflow-hidden bg-slate-950 text-slate-100">
    <!-- Hero Battle Arena Section -->
    <section class="relative pt-12 pb-20 sm:pt-20 sm:pb-28 overflow-hidden">
        <!-- Ambient Background Glows -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-gradient-to-tr from-amber-500/15 via-indigo-600/20 to-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -top-12 -right-12 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <!-- Badge Promo Esports -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-900/90 border border-amber-500/40 text-amber-300 text-xs font-black mb-6 shadow-lg shadow-amber-500/10 backdrop-blur-sm">
                <i class="fa-solid fa-gamepad text-amber-400 animate-pulse"></i>
                <span>ARENA CODING PYTHON &bull; PUSH RANK SISWA</span>
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
            </div>

            <!-- Headline Utama -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight max-w-4xl mx-auto">
                Kuasai Pemrograman Python <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-amber-400 via-yellow-300 to-orange-400 bg-clip-text text-transparent drop-shadow-[0_4px_12px_rgba(251,191,36,0.3)]">
                    Push Rank dari Smartphone Anda!
                </span>
            </h1>

            <p class="mt-4 sm:mt-6 max-w-2xl mx-auto text-sm sm:text-base text-slate-300 leading-relaxed">
                Tuntaskan tantangan koding harian, naikkan pangkat dari <span class="text-amber-400 font-bold">Warrior</span> menuju <span class="text-rose-400 font-black">Mythical Honor</span>, dan buktikan keahlian algoritma Python langsung dari browser HP tanpa ribet install aplikasi.
            </p>

            <!-- CTA Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                <a href="<?= base_url('login.php') ?>" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-slate-950 font-black text-sm shadow-xl shadow-amber-500/25 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2.5">
                    <i class="fa-solid fa-fire text-slate-950 text-base"></i>
                    <span>Mulai Push Rank Sekarang</span>
                </a>
                <a href="<?= base_url('leaderboard.php') ?>" class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-slate-900/90 hover:bg-slate-800 text-amber-300 font-bold text-sm border border-amber-500/30 shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-400"></i>
                    <span>Papan Peringkat Siswa</span>
                </a>
                <a href="#stages" class="w-full sm:w-auto px-6 py-3.5 rounded-2xl bg-slate-900/60 hover:bg-slate-900 text-slate-300 font-bold text-sm border border-slate-800 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-slate-400"></i>
                    <span>Peta 8 Stage</span>
                </a>
            </div>

            <!-- Live Stats Banner -->
            <div class="mt-12 max-w-3xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                <div class="bg-slate-900/80 border border-slate-800/90 p-4 rounded-2xl backdrop-blur-sm">
                    <div class="text-xl sm:text-2xl font-black text-amber-400"><?= number_format($totalStudents) ?></div>
                    <div class="text-[11px] text-slate-400 uppercase font-bold mt-0.5">Siswa Terdaftar</div>
                </div>
                <div class="bg-slate-900/80 border border-slate-800/90 p-4 rounded-2xl backdrop-blur-sm">
                    <div class="text-xl sm:text-2xl font-black text-amber-300"><?= number_format($totalXp) ?></div>
                    <div class="text-[11px] text-slate-400 uppercase font-bold mt-0.5">Total XP Diraih</div>
                </div>
                <div class="bg-slate-900/80 border border-slate-800/90 p-4 rounded-2xl backdrop-blur-sm">
                    <div class="text-xl sm:text-2xl font-black text-sky-400">8 Stage</div>
                    <div class="text-[11px] text-slate-400 uppercase font-bold mt-0.5">40 Sub-Materi</div>
                </div>
                <div class="bg-slate-900/80 border border-slate-800/90 p-4 rounded-2xl backdrop-blur-sm">
                    <div class="text-xl sm:text-2xl font-black text-emerald-400">0.02s</div>
                    <div class="text-[11px] text-slate-400 uppercase font-bold mt-0.5">WebAssembly HP</div>
                </div>
            </div>

            <!-- Preview Fitur Unggulan Mobile -->
            <div class="mt-12 sm:mt-16 max-w-4xl mx-auto bg-slate-900/90 rounded-3xl p-4 sm:p-6 shadow-2xl border border-slate-800 text-left relative overflow-hidden backdrop-blur-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800 text-xs text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500 shadow-sm shadow-amber-500/50"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                        <span class="ml-2 font-mono text-slate-300 font-bold">mobile_arena_python.py</span>
                    </div>
                    <span class="bg-amber-500/10 border border-amber-500/30 text-amber-300 px-3 py-1 rounded-full text-[11px] font-bold">
                        <i class="fa-brands fa-python text-yellow-400 mr-1"></i> WebAssembly Pyodide CPython 3.11
                    </span>
                </div>
                
                <!-- Quick Mobile Toolbar Preview -->
                <div class="mt-3 bg-slate-950 p-2.5 rounded-2xl flex items-center gap-1.5 overflow-x-auto text-xs font-mono text-slate-200 border border-slate-800/80">
                    <span class="text-[11px] text-amber-400 font-sans font-bold mr-1 flex items-center gap-1">
                        <i class="fa-solid fa-mobile-screen"></i> HP Bar:
                    </span>
                    <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">Tab ⇥</span>
                    <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">:</span>
                    <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">( )</span>
                    <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">" "</span>
                    <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">[ ]</span>
                    <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">{ }</span>
                    <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">==</span>
                    <span class="bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 px-3 py-1 rounded-lg font-black ml-auto shadow-md">▶ Run Code</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 font-mono text-xs">
                    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                        <div class="text-slate-500 text-[11px] mb-2 flex items-center justify-between">
                            <span>// Editor Interaktif Python:</span>
                            <span class="text-emerald-400 font-sans font-bold text-[10px]">Tantangan Selesai +25 XP</span>
                        </div>
                        <div class="text-pink-400">player <span class="text-white">=</span> <span class="text-emerald-300">"Siswa PPLG"</span></div>
                        <div class="text-pink-400">rank_ml <span class="text-white">=</span> <span class="text-amber-300">"Mythical Honor"</span></div>
                        <div class="text-pink-400">total_xp <span class="text-white">=</span> <span class="text-yellow-300">2450</span></div>
                        <div class="text-sky-400 mt-2">print<span class="text-white">(</span><span class="text-yellow-200">f"Hero {player} meraih {rank_ml} ({total_xp} XP)!"</span><span class="text-white">)</span></div>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 flex flex-col justify-between">
                        <div>
                            <div class="text-slate-500 text-[11px] mb-2">// Output Terminal Langsung:</div>
                            <div class="text-emerald-400 font-bold">Hero Siswa PPLG meraih Mythical Honor (2450 XP)!</div>
                        </div>
                        <div class="text-[11px] text-slate-400 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-bolt text-yellow-400"></i> Auto-Grading Instan</span>
                            <span class="text-slate-500 font-sans">Tanpa Kuota Server</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LIVE HALL OF FAME TOP SISWA (MINI PODIUM TEASER) -->
    <?php if (!empty($topStudents)): ?>
    <section class="py-12 bg-slate-900/60 border-y border-slate-800/80 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold mb-2">
                        <i class="fa-solid fa-crown text-amber-400"></i> Top Player Sekolah
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Hall of Fame Juara Bertahan</h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">Petarung dengan perolehan XP tertinggi saat ini. Bisakah kamu menggeser tahta mereka?</p>
                </div>
                <a href="<?= base_url('leaderboard.php') ?>" class="inline-flex items-center gap-2 text-xs font-black text-amber-400 hover:text-amber-300 transition">
                    <span>Buka Papan Peringkat Lengkap</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <?php 
                $medals = [
                    1 => ['title' => 'Juara 1 Global', 'border' => 'border-amber-500/70', 'badge' => 'bg-amber-400 text-slate-950', 'icon' => 'fa-crown text-amber-400'],
                    2 => ['title' => 'Juara 2 Global', 'border' => 'border-slate-500/70', 'badge' => 'bg-slate-300 text-slate-900', 'icon' => 'fa-medal text-slate-300'],
                    3 => ['title' => 'Juara 3 Global', 'border' => 'border-amber-800/70', 'badge' => 'bg-amber-700 text-white', 'icon' => 'fa-award text-amber-600'],
                ];
                $pos = 1;
                foreach ($topStudents as $top): 
                    $rInfo = get_rank_info($top['xp']);
                    $avatar = get_user_avatar($top['avatar'] ?? '');
                    $m = $medals[$pos] ?? $medals[3];
                ?>
                <div class="bg-slate-900/90 rounded-2xl p-5 border-2 <?= $m['border'] ?> shadow-xl flex items-center gap-4 relative overflow-hidden backdrop-blur-sm">
                    <div class="relative flex-shrink-0">
                        <?php if ($avatar): ?>
                            <img src="<?= $avatar ?>" alt="Avatar" class="w-14 h-14 rounded-2xl object-cover border-2 border-slate-700">
                        <?php else: ?>
                            <div class="w-14 h-14 rounded-2xl bg-slate-800 text-amber-300 border-2 border-slate-700 flex items-center justify-center text-xl font-black">
                                <?= strtoupper(substr($top['nickname'] ?: $top['full_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full <?= $m['badge'] ?> flex items-center justify-center font-black text-xs shadow-md">
                            #<?= $pos ?>
                        </span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-black text-white truncate" title="<?= sanitize($top['full_name']) ?>">
                                <?= sanitize($top['nickname'] ?: $top['full_name']) ?>
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-400 truncate"><?= sanitize($top['rombel'] ?? '-') ?></div>
                        <div class="mt-2 flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black border <?= $rInfo['badge_class'] ?>">
                                <i class="fa-solid <?= $rInfo['icon'] ?>"></i> <?= $rInfo['name'] ?>
                            </span>
                            <span class="text-amber-400 font-black text-xs flex items-center gap-1">
                                <i class="fa-solid fa-bolt text-[10px]"></i> <?= number_format($top['xp']) ?> XP
                            </span>
                        </div>
                    </div>
                </div>
                <?php 
                $pos++;
                endforeach; 
                ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- SHOWCASE 8 TIER RANK -->
    <section class="py-16 sm:py-20 bg-slate-950 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold mb-3">
                    <i class="fa-solid fa-shield-halved text-amber-400"></i> Sistem Gamifikasi
                </div>
                <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight">8 Tingkatan Pangkat Rank</h2>
                <p class="mt-2 text-sm text-slate-400">Setiap kuis yang kamu jawab dan tantangan koding yang kamu pecahkan akan menyumbang XP untuk menaikkan tier rank akunmu.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 sm:gap-4">
                <?php foreach ($mlRanks as $rk): ?>
                <div class="bg-slate-900/80 rounded-2xl p-4 border border-slate-800 text-center flex flex-col items-center justify-between hover:border-amber-500/50 hover:bg-slate-900 transition-all group">
                    <div class="w-12 h-12 rounded-2xl <?= $rk['badge_class'] ?> flex items-center justify-center text-xl shadow-lg mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid <?= $rk['icon'] ?>"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase font-black tracking-wider">Tier <?= $rk['tier'] ?></div>
                        <h4 class="font-black text-white text-xs sm:text-sm mt-0.5"><?= $rk['name'] ?></h4>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-800/80 w-full text-[11px] font-black text-amber-400">
                        <?= number_format($rk['min_xp']) ?>+ XP
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ROADMAP 8 STAGE QUEST / DUNGEON SECTION -->
    <section id="stages" class="py-16 sm:py-24 bg-slate-900/40 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-bold mb-3">
                    <i class="fa-solid fa-dungeon text-blue-400"></i> Peta Kurikulum Interaktif
                </div>
                <h2 class="text-2xl sm:text-4xl font-black text-white tracking-tight">8 Stage Quest Python Academy</h2>
                <p class="mt-2 text-sm text-slate-400">Selesaikan setiap babak untuk mengumpulkan XP, membuka stage berikutnya, dan menaklukkan Boss Capstone Project.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Stage 1 -->
                <div class="bg-slate-900/90 p-5 rounded-3xl border border-slate-800 hover:border-amber-500/50 shadow-xl transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-black text-amber-400 mb-3">
                            <span class="bg-amber-500/10 px-2.5 py-1 rounded-lg border border-amber-500/20">STAGE 01</span>
                            <span class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center"><i class="fa-solid fa-seedling"></i></span>
                        </div>
                        <h4 class="font-black text-white text-base mb-1.5 group-hover:text-amber-300 transition">Pengenalan Python</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">Print, komentar, variabel, dan tipe data primitif (int, float, str, bool).</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-800 text-[11px] font-bold text-amber-400 flex items-center justify-between">
                        <span><i class="fa-solid fa-skull mr-1 text-rose-400"></i> Boss: Biodata Siswa</span>
                        <span class="text-slate-500">5 Misi</span>
                    </div>
                </div>

                <!-- Stage 2 -->
                <div class="bg-slate-900/90 p-5 rounded-3xl border border-slate-800 hover:border-indigo-500/50 shadow-xl transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-black text-indigo-400 mb-3">
                            <span class="bg-indigo-500/10 px-2.5 py-1 rounded-lg border border-indigo-500/20">STAGE 02</span>
                            <span class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center"><i class="fa-solid fa-calculator"></i></span>
                        </div>
                        <h4 class="font-black text-white text-base mb-1.5 group-hover:text-indigo-300 transition">Operator &amp; Input</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">Aritmatika, perbandingan, logika, input(), type casting, dan f-strings.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-800 text-[11px] font-bold text-indigo-400 flex items-center justify-between">
                        <span><i class="fa-solid fa-skull mr-1 text-rose-400"></i> Boss: Kalkulator Suhu</span>
                        <span class="text-slate-500">5 Misi</span>
                    </div>
                </div>

                <!-- Stage 3 -->
                <div class="bg-slate-900/90 p-5 rounded-3xl border border-slate-800 hover:border-sky-500/50 shadow-xl transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-black text-sky-400 mb-3">
                            <span class="bg-sky-500/10 px-2.5 py-1 rounded-lg border border-sky-500/20">STAGE 03</span>
                            <span class="w-8 h-8 rounded-xl bg-sky-500/10 text-sky-400 flex items-center justify-center"><i class="fa-solid fa-code-branch"></i></span>
                        </div>
                        <h4 class="font-black text-white text-base mb-1.5 group-hover:text-sky-300 transition">Percabangan Logika</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">Struktur if, elif, else, nested if, dan evaluasi kelulusan nilai siswa.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-800 text-[11px] font-bold text-sky-400 flex items-center justify-between">
                        <span><i class="fa-solid fa-skull mr-1 text-rose-400"></i> Boss: KKM Kelulusan</span>
                        <span class="text-slate-500">5 Misi</span>
                    </div>
                </div>

                <!-- Stage 4 -->
                <div class="bg-slate-900/90 p-5 rounded-3xl border border-slate-800 hover:border-emerald-500/50 shadow-xl transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-black text-emerald-400 mb-3">
                            <span class="bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">STAGE 04</span>
                            <span class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center"><i class="fa-solid fa-arrows-rotate"></i></span>
                        </div>
                        <h4 class="font-black text-white text-base mb-1.5 group-hover:text-emerald-300 transition">Perulangan Loop</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">For loop, while loop, range(), break, continue, dan pola bintang.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-800 text-[11px] font-bold text-emerald-400 flex items-center justify-between">
                        <span><i class="fa-solid fa-skull mr-1 text-rose-400"></i> Boss: Kasir Belanja</span>
                        <span class="text-slate-500">5 Misi</span>
                    </div>
                </div>

                <!-- Stage 5 -->
                <div class="bg-slate-900/90 p-5 rounded-3xl border border-slate-800 hover:border-purple-500/50 shadow-xl transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-black text-purple-400 mb-3">
                            <span class="bg-purple-500/10 px-2.5 py-1 rounded-lg border border-purple-500/20">STAGE 05</span>
                            <span class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center"><i class="fa-solid fa-layer-group"></i></span>
                        </div>
                        <h4 class="font-black text-white text-base mb-1.5 group-hover:text-purple-300 transition">Struktur Data List &amp; Dict</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">List index, append, remove, tuple, set, dan kamus data key-value.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-800 text-[11px] font-bold text-purple-400 flex items-center justify-between">
                        <span><i class="fa-solid fa-skull mr-1 text-rose-400"></i> Boss: Kontak Siswa</span>
                        <span class="text-slate-500">5 Misi</span>
                    </div>
                </div>

                <!-- Stage 6 -->
                <div class="bg-slate-900/90 p-5 rounded-3xl border border-slate-800 hover:border-rose-500/50 shadow-xl transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-black text-rose-400 mb-3">
                            <span class="bg-rose-500/10 px-2.5 py-1 rounded-lg border border-rose-500/20">STAGE 06</span>
                            <span class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center"><i class="fa-solid fa-cubes"></i></span>
                        </div>
                        <h4 class="font-black text-white text-base mb-1.5 group-hover:text-rose-300 transition">Fungsi &amp; Modul</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">Def function, parameter, return, scope variabel, dan modul math/random.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-800 text-[11px] font-bold text-rose-400 flex items-center justify-between">
                        <span><i class="fa-solid fa-skull mr-1 text-rose-400"></i> Boss: Tebak Angka</span>
                        <span class="text-slate-500">5 Misi</span>
                    </div>
                </div>

                <!-- Stage 7 -->
                <div class="bg-slate-900/90 p-5 rounded-3xl border border-slate-800 hover:border-cyan-500/50 shadow-xl transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-black text-cyan-400 mb-3">
                            <span class="bg-cyan-500/10 px-2.5 py-1 rounded-lg border border-cyan-500/20">STAGE 07</span>
                            <span class="w-8 h-8 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center"><i class="fa-solid fa-shield-virus"></i></span>
                        </div>
                        <h4 class="font-black text-white text-base mb-1.5 group-hover:text-cyan-300 transition">String &amp; Error Handling</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">Manipulasi string teks, validasi format, try-except, dan penanganan bug.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-800 text-[11px] font-bold text-cyan-400 flex items-center justify-between">
                        <span><i class="fa-solid fa-skull mr-1 text-rose-400"></i> Boss: Validasi NIS</span>
                        <span class="text-slate-500">5 Misi</span>
                    </div>
                </div>

                <!-- Stage 8 -->
                <div class="bg-gradient-to-b from-amber-950/40 via-slate-900 to-slate-950 p-5 rounded-3xl border-2 border-amber-500/60 shadow-2xl transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-xs font-black text-amber-400 mb-3">
                            <span class="bg-amber-500/20 px-2.5 py-1 rounded-lg border border-amber-400/40 text-amber-300">STAGE FINAL</span>
                            <span class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center"><i class="fa-solid fa-trophy"></i></span>
                        </div>
                        <h4 class="font-black text-white text-base mb-1.5 group-hover:text-amber-300 transition">Capstone Project</h4>
                        <p class="text-xs text-slate-300 leading-relaxed">Puncak ujian petualangan: Rancang aplikasi Python komprehensif untuk review Guru.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-amber-500/30 text-[11px] font-black text-amber-300 flex items-center justify-between">
                        <span><i class="fa-solid fa-crown mr-1 text-amber-400"></i> Grand Finale (+100 XP)</span>
                        <span class="text-amber-400">Review Guru</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 PILAR FITUR UTAMA -->
    <section class="py-16 sm:py-20 bg-slate-950 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Fitur 1 -->
                <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-amber-500/40 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl mb-4 border border-amber-500/20">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <h3 class="text-base font-black text-white mb-2">Ramah Keyboard Smartphone</h3>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        Tak perlu repot berpindah-pindah layer keyboard HP untuk mencari simbol <code>:</code>, <code>( )</code>, <code>[ ]</code>, atau indentasi Tab 4 spasi. Semua tersedia instan dalam 1 tap!
                    </p>
                </div>

                <!-- Fitur 2 -->
                <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-emerald-500/40 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl mb-4 border border-emerald-500/20">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <h3 class="text-base font-black text-white mb-2">100% WebAssembly Cepat</h3>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        Kode Python dijalankan langsung di mesin browser siswa lewat Pyodide WebAssembly. Sangat hemat kuota internet dan menjaga server cPanel tetap ringan tanpa beban CPU tinggi.
                    </p>
                </div>

                <!-- Fitur 3 -->
                <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-purple-500/40 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl mb-4 border border-purple-500/20">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <h3 class="text-base font-black text-white mb-2">Auto-Grading &amp; Buku Nilai</h3>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        Sistem secara otomatis mengevaluasi output kode siswa, menganugerahkan lencana pangkat, dan mengompilasi seluruh nilai ke buku nilai Guru untuk diekspor ke Excel.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL BATTLE CALL-TO-ACTION -->
    <section class="py-16 sm:py-24 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 border-t border-slate-800 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-amber-500/10 via-transparent to-transparent pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <span class="inline-block px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-black uppercase tracking-wider mb-4">
                👑 Siap Menjadi Mythical Honor?
            </span>
            <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
                Tantang Dirimu &amp; Raih Peringkat Tertinggi Sekarang
            </h2>
            <p class="mt-4 text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed">
                Login menggunakan NIS atau Username yang diberikan oleh gurumu, dan mulailah petualangan coding pertamamu hari ini!
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= base_url('login.php') ?>" class="w-full sm:w-auto px-9 py-4 rounded-2xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-slate-950 font-black text-sm shadow-2xl shadow-amber-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket text-base"></i>
                    <span>Masuk ke Arena Belajar</span>
                </a>
                <a href="<?= base_url('leaderboard.php') ?>" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-amber-300 font-bold text-sm border border-amber-500/30 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-400"></i>
                    <span>Cek Papan Peringkat</span>
                </a>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
