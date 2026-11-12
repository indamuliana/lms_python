<?php
/**
 * Global Header Template - Modern Edu Theme
 */
require_once __DIR__ . '/auth.php';
$currentUser = current_user();
$flash = get_flash();
$pageTitle = $pageTitle ?? APP_NAME;
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= sanitize($pageTitle) ?> - <?= APP_NAME ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        },
                        python: {
                            blue: '#306998',
                            yellow: '#FFD43B',
                            dark: '#1e293b'
                        }
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<?php
$isStudentArea = (strpos($_SERVER['REQUEST_URI'] ?? '', '/student/') !== false);
$isGamingTheme = !empty($isGamingTheme);
?>
<body class="h-full flex flex-col font-['Inter',sans-serif] <?= $isGamingTheme ? 'bg-slate-950 text-slate-100' : '' ?> <?= $isStudentArea ? 'no-copy-student' : '' ?>">

    <!-- Navbar Atas -->
    <header class="<?= $isGamingTheme ? 'bg-slate-950/85 backdrop-blur-md border-b border-slate-800/80 text-slate-100' : 'bg-white border-b border-slate-200 text-slate-900' ?> sticky top-0 z-40 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="<?= $currentUser ? ($currentUser['role'] === 'guru' ? base_url('teacher/dashboard.php') : base_url('student/dashboard.php')) : base_url() ?>" class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl <?= $isGamingTheme ? 'bg-gradient-to-br from-amber-500 to-indigo-600 shadow-amber-500/20' : 'bg-gradient-to-br from-blue-600 to-indigo-700 shadow-blue-500/20' ?> flex items-center justify-center text-white shadow-md">
                            <i class="fa-brands fa-python text-2xl text-yellow-300"></i>
                        </div>
                        <div>
                            <span class="font-extrabold text-lg tracking-tight <?= $isGamingTheme ? 'bg-gradient-to-r from-yellow-300 via-amber-300 to-sky-400' : 'bg-gradient-to-r from-blue-700 to-indigo-700' ?> bg-clip-text text-transparent">PyLMS</span>
                            <span class="text-xs block <?= $isGamingTheme ? 'text-slate-400' : 'text-slate-400' ?> font-medium -mt-1">Akademi Python Siswa</span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <?php if ($currentUser): ?>
                    <nav class="hidden md:flex ml-8 space-x-1">
                        <?php if ($currentUser['role'] === 'siswa'): ?>
                            <a href="<?= base_url('student/dashboard.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-slate-300 hover:text-white hover:bg-slate-900' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-graduation-cap mr-1.5 text-blue-400"></i> Roadmap
                            </a>
                            <a href="<?= base_url('student/leaderboard.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-amber-300 hover:text-amber-200 hover:bg-amber-500/10' : 'text-slate-700 hover:text-amber-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-trophy mr-1.5 text-amber-400"></i> Leaderboard
                            </a>
                            <a href="<?= base_url('student/capstone.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-purple-300 hover:text-purple-200 hover:bg-slate-900' : 'text-slate-700 hover:text-purple-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-rocket mr-1.5 text-purple-400"></i> Proyek Akhir
                            </a>
                        <?php elseif ($currentUser['role'] === 'guru'): ?>
                            <a href="<?= base_url('teacher/dashboard.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-slate-300 hover:text-white hover:bg-slate-900' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-gauge mr-1.5 text-blue-400"></i> Dashboard
                            </a>
                            <a href="<?= base_url('student/dashboard.php') ?>" class="px-3 py-2 rounded-lg text-sm font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 transition flex items-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-chalkboard-user text-amber-600"></i> Tampilan Siswa
                            </a>
                            <a href="<?= base_url('teacher/gradebook.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-emerald-300 hover:text-white hover:bg-slate-900' : 'text-slate-700 hover:text-emerald-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-book-open mr-1.5 text-emerald-400"></i> Buku Nilai
                            </a>
                            <a href="<?= base_url('teacher/curriculum.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-amber-300 hover:text-white hover:bg-slate-900' : 'text-slate-700 hover:text-amber-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-book-bookmark mr-1.5 text-amber-500"></i> Kurikulum
                            </a>
                            <a href="<?= base_url('teacher/students.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-indigo-300 hover:text-white hover:bg-slate-900' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-users mr-1.5 text-indigo-400"></i> Data Siswa
                            </a>
                            <a href="<?= base_url('teacher/import-csv.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-cyan-300 hover:text-white hover:bg-slate-900' : 'text-slate-700 hover:text-cyan-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-file-csv mr-1.5 text-cyan-400"></i> Import CSV
                            </a>
                            <a href="<?= base_url('teacher/capstone-review.php') ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-purple-300 hover:text-white hover:bg-slate-900' : 'text-slate-700 hover:text-purple-600 hover:bg-slate-50' ?> transition">
                                <i class="fa-solid fa-list-check mr-1.5 text-purple-400"></i> Review Capstone
                            </a>
                        <?php endif; ?>
                    </nav>
                    <?php else: ?>
                    <nav class="hidden md:flex ml-8 space-x-1">
                        <a href="<?= base_url() ?>" class="px-3 py-2 rounded-lg text-sm font-semibold <?= $isGamingTheme ? 'text-slate-300 hover:text-white hover:bg-slate-900/60' : 'text-slate-700 hover:text-blue-600 hover:bg-slate-50' ?> transition">
                            <i class="fa-solid fa-house mr-1.5 <?= $isGamingTheme ? 'text-sky-400' : 'text-blue-500' ?>"></i> Beranda
                        </a>
                        <a href="<?= base_url('leaderboard.php') ?>" class="px-3 py-2 rounded-lg text-sm font-bold <?= $isGamingTheme ? 'text-amber-300 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30' : 'text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200/80' ?> transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-trophy <?= $isGamingTheme ? 'text-amber-400' : 'text-amber-600' ?>"></i> Papan Peringkat
                        </a>
                    </nav>
                    <?php endif; ?>
                </div>

                <!-- Right Side: User Profile / Login -->
                <div class="flex items-center space-x-3">
                    <?php if ($currentUser): ?>
                        <?php if ($currentUser['role'] === 'siswa'): ?>
                            <!-- Gamification Pill (Rank ML & XP) -->
                            <div class="hidden sm:flex items-center border rounded-full px-3 py-1 text-xs font-bold gap-2 shadow-sm <?= $currentUser['rank_badge'] ?? 'bg-amber-50 border-amber-200 text-amber-800' ?>">
                                <span class="flex items-center"><i class="fa-solid <?= $currentUser['rank_icon'] ?? 'fa-shield-halved' ?> mr-1.5"></i> <?= $currentUser['level'] ?></span>
                                <span class="h-3 w-px bg-current opacity-30"></span>
                                <span class="flex items-center"><i class="fa-solid fa-bolt mr-1"></i> <?= number_format($currentUser['xp']) ?> XP</span>
                            </div>
                        <?php endif; ?>

                        <!-- User Info & Avatar -->
                        <div class="flex items-center gap-2 pl-2">
                            <?php if ($currentUser['role'] === 'siswa'): ?>
                                <a href="<?= base_url('student/profile.php') ?>" class="flex items-center gap-2 hover:opacity-85 transition group" title="Pengaturan Profil, Foto & Sandi">
                                    <?php $navAvatar = get_user_avatar($currentUser['avatar'] ?? ''); ?>
                                    <?php if ($navAvatar): ?>
                                        <img src="<?= $navAvatar ?>" alt="Avatar" class="w-8 h-8 rounded-xl object-cover border <?= $isGamingTheme ? 'border-slate-700' : 'border-slate-200' ?> shadow-sm">
                                    <?php else: ?>
                                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 font-extrabold text-xs flex items-center justify-center border border-blue-200 shadow-sm">
                                            <?= strtoupper(substr($currentUser['nickname'] ?: $currentUser['full_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-right hidden sm:block">
                                        <div class="text-xs font-bold <?= $isGamingTheme ? 'text-slate-100 group-hover:text-blue-400' : 'text-slate-800 group-hover:text-blue-600' ?> leading-tight transition">
                                            <?= sanitize($currentUser['nickname'] ?: $currentUser['full_name']) ?>
                                        </div>
                                        <div class="text-[10px] <?= $isGamingTheme ? 'text-slate-400' : 'text-slate-500' ?> capitalize">
                                            <?= $currentUser['rombel'] ? sanitize($currentUser['rombel']) : 'Siswa' ?>
                                        </div>
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('teacher/profile.php') ?>" class="flex items-center gap-2 hover:opacity-85 transition group" title="Pengaturan Profil, Foto & Sandi Guru">
                                    <?php $navAvatar = get_user_avatar($currentUser['avatar'] ?? ''); ?>
                                    <?php if ($navAvatar): ?>
                                        <img src="<?= $navAvatar ?>" alt="Avatar" class="w-8 h-8 rounded-xl object-cover border <?= $isGamingTheme ? 'border-slate-700' : 'border-slate-200' ?> shadow-sm">
                                    <?php else: ?>
                                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 font-extrabold text-xs flex items-center justify-center border border-amber-300 shadow-sm">
                                            <?= strtoupper(substr($currentUser['full_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-right hidden sm:block">
                                        <div class="text-xs font-bold <?= $isGamingTheme ? 'text-slate-100 group-hover:text-blue-400' : 'text-slate-800 group-hover:text-blue-600' ?> leading-tight transition"><?= sanitize($currentUser['full_name']) ?></div>
                                        <div class="text-[10px] <?= $isGamingTheme ? 'text-slate-400' : 'text-slate-500' ?> capitalize">Guru Pembimbing</div>
                                    </div>
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('logout.php') ?>" title="Logout" class="w-9 h-9 rounded-lg <?= $isGamingTheme ? 'bg-slate-900 hover:bg-rose-950/60 hover:text-rose-400 text-slate-400' : 'bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600' ?> flex items-center justify-center transition text-sm">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
                        </div>

                        <!-- Mobile Hamburger Button -->
                        <button type="button" onclick="document.getElementById('mobileNav').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg <?= $isGamingTheme ? 'text-slate-300 hover:bg-slate-900' : 'text-slate-600 hover:bg-slate-100' ?>">
                            <i class="fa-solid fa-bars text-lg"></i>
                        </button>
                    <?php else: ?>
                        <a href="<?= base_url('leaderboard.php') ?>" class="inline-flex md:hidden items-center justify-center p-2 <?= $isGamingTheme ? 'text-amber-400 hover:bg-slate-900' : 'text-amber-600 hover:bg-amber-50' ?> rounded-lg text-sm font-bold transition" title="Papan Peringkat">
                            <i class="fa-solid fa-trophy text-base"></i>
                        </a>
                        <a href="<?= base_url('login.php') ?>" class="inline-flex items-center justify-center px-4 py-2 border <?= $isGamingTheme ? 'border-amber-400/40 bg-gradient-to-r from-amber-500 to-yellow-600 text-slate-950 hover:brightness-110 shadow-lg shadow-amber-500/20' : 'border-transparent text-white bg-blue-600 hover:bg-blue-700 shadow-sm' ?> rounded-xl text-sm font-extrabold transition">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <?php if ($currentUser): ?>
        <div id="mobileNav" class="hidden md:hidden border-t <?= $isGamingTheme ? 'border-slate-800 bg-slate-950 text-slate-200' : 'border-slate-200 bg-white' ?> px-4 pt-2 pb-4 space-y-1 shadow-lg">
            <?php if ($currentUser['role'] === 'siswa'): ?>
                <div class="flex items-center justify-between py-2 px-3 rounded-lg text-xs font-bold mb-2 border <?= $currentUser['rank_badge'] ?? 'bg-amber-50 border-amber-200 text-amber-800' ?>">
                    <span class="flex items-center"><i class="fa-solid <?= $currentUser['rank_icon'] ?? 'fa-shield-halved' ?> mr-1.5"></i> <?= $currentUser['level'] ?></span>
                    <span><i class="fa-solid fa-bolt mr-1"></i> <?= number_format($currentUser['xp']) ?> XP</span>
                    <span><i class="fa-solid fa-users text-slate-500 mr-1"></i> <?= sanitize($currentUser['rombel'] ?? '-') ?></span>
                </div>
                <a href="<?= base_url('student/dashboard.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-graduation-cap mr-2 text-blue-500 w-5"></i> Roadmap Belajar
                </a>
                <a href="<?= base_url('student/leaderboard.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-trophy mr-2 text-amber-500 w-5"></i> Papan Peringkat (Leaderboard)
                </a>
                <a href="<?= base_url('student/capstone.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-rocket mr-2 text-purple-500 w-5"></i> Proyek Akhir (Capstone)
                </a>
                <a href="<?= base_url('student/profile.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-user-gear mr-2 text-indigo-500 w-5"></i> Profil &amp; Ganti Sandi
                </a>
            <?php else: ?>
                <a href="<?= base_url('teacher/dashboard.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-gauge mr-2 text-blue-500 w-5"></i> Dashboard Guru
                </a>
                <a href="<?= base_url('student/dashboard.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200">
                    <i class="fa-solid fa-chalkboard-user mr-2 text-amber-600 w-5"></i> Mode Tampilan Siswa
                </a>
                <a href="<?= base_url('teacher/gradebook.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-book-open mr-2 text-emerald-500 w-5"></i> Buku Nilai Siswa
                </a>
                <a href="<?= base_url('teacher/curriculum.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-book-bookmark mr-2 text-amber-500 w-5"></i> Kurikulum &amp; Materi
                </a>
                <a href="<?= base_url('teacher/students.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-users mr-2 text-indigo-500 w-5"></i> Data Siswa & Rombel
                </a>
                <a href="<?= base_url('teacher/import-csv.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-file-csv mr-2 text-cyan-500 w-5"></i> Import Siswa (CSV)
                </a>
                <a href="<?= base_url('teacher/capstone-review.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-list-check mr-2 text-purple-500 w-5"></i> Review Proyek Akhir
                </a>
                <a href="<?= base_url('teacher/profile.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-user-gear mr-2 text-indigo-500 w-5"></i> Profil &amp; Ganti Sandi
                </a>
            <?php endif; ?>
            <a href="<?= base_url('logout.php') ?>" class="block px-3 py-2 rounded-lg text-sm font-semibold text-red-600 hover:bg-red-50 mt-2">
                <i class="fa-solid fa-right-from-bracket mr-2 w-5"></i> Keluar
            </a>
        </div>
        <?php endif; ?>
    </header>

    <?php 
    $isStudentArea = strpos($_SERVER['REQUEST_URI'] ?? '', '/student/') !== false;
    if ($currentUser && $currentUser['role'] === 'guru' && $isStudentArea): 
    ?>
    <!-- Banner Demonstrasi Khusus Guru -->
    <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-yellow-600 text-white text-xs sm:text-sm font-bold py-2.5 px-4 shadow-md sticky top-16 z-30 border-b border-amber-400">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="bg-black/25 text-yellow-200 px-2.5 py-0.5 rounded text-[11px] font-black uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-chalkboard-user"></i> Mode Demo Guru
                </span>
                <span class="text-white/95">Anda sedang membuka halaman siswa. Seluruh Part 1–8 otomatis terbuka penuh.</span>
            </div>
            <a href="<?= base_url('teacher/dashboard.php') ?>" class="bg-white text-amber-900 hover:bg-amber-50 px-3 py-1 rounded-lg text-xs font-black shadow-sm transition inline-flex items-center gap-1.5 ml-auto">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard Guru
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Flash Message Container -->
    <?php if ($flash): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        <?php
            $bgColors = [
                'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
                'danger'  => 'bg-rose-50 border-rose-200 text-rose-800',
                'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
                'info'    => 'bg-sky-50 border-sky-200 text-sky-800',
            ];
            $iconTypes = [
                'success' => 'fa-circle-check text-emerald-500',
                'danger'  => 'fa-circle-exclamation text-rose-500',
                'warning' => 'fa-triangle-exclamation text-amber-500',
                'info'    => 'fa-circle-info text-sky-500',
            ];
            $color = $bgColors[$flash['type']] ?? $bgColors['info'];
            $icon = $iconTypes[$flash['type']] ?? $iconTypes['info'];
        ?>
        <div class="border rounded-xl p-3.5 flex items-center justify-between text-sm font-medium shadow-sm <?= $color ?>">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid <?= $icon ?> text-base"></i>
                <span><?= sanitize($flash['message']) ?></span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <main class="flex-1 w-full">
