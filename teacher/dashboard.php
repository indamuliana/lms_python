<?php
/**
 * Dashboard Guru - Ringkasan & Manajemen Kelas
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$user = current_user();
$db = get_db();

// Statistik
$totalStudents = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn();
$totalRombels = (int)$db->query("SELECT COUNT(DISTINCT rombel) FROM users WHERE role = 'siswa' AND rombel IS NOT NULL AND rombel != ''")->fetchColumn();
$avgXp = (int)$db->query("SELECT COALESCE(AVG(xp), 0) FROM users WHERE role = 'siswa'")->fetchColumn();
$pendingCapstones = (int)$db->query("SELECT COUNT(*) FROM capstone_submissions WHERE status = 'pending'")->fetchColumn();

// Distribusi Rombel
$stmtRombel = $db->query("
    SELECT rombel, COUNT(*) as student_count, AVG(xp) as avg_xp, MAX(current_part) as max_part
    FROM users 
    WHERE role = 'siswa' AND rombel IS NOT NULL AND rombel != ''
    GROUP BY rombel
    ORDER BY rombel ASC
");
$rombelList = $stmtRombel->fetchAll();

// Aktivitas Siswa Terkini
$stmtRecent = $db->query("
    SELECT u.full_name, u.rombel, t.title as topic_title, sp.completed_at, sp.quiz_score
    FROM student_progress sp
    JOIN users u ON u.id = sp.user_id
    JOIN topics t ON t.id = sp.topic_id
    WHERE sp.is_completed = 1
    ORDER BY sp.completed_at DESC
    LIMIT 6
");
$recentActivities = $stmtRecent->fetchAll();

$pageTitle = 'Dashboard Guru';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Banner Guru -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-300">Panel Manajemen Guru</span>
            <h1 class="text-2xl sm:text-3xl font-black mt-1">Selamat Datang, <?= sanitize($user['full_name']) ?></h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1">Pantau pencapaian belajar Python siswa, buku nilai, dan hasil uji proyek akhir.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="<?= base_url('student/dashboard.php') ?>" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-900 font-extrabold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-chalkboard-user"></i> Mode Demonstrasi Siswa
            </a>
            <a href="<?= base_url('teacher/import-csv.php') ?>" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-file-csv"></i> Import Siswa CSV
            </a>
            <a href="<?= base_url('teacher/gradebook.php') ?>" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-book-open"></i> Buka Buku Nilai
            </a>
        </div>
    </div>

    <!-- 4 Metrik Statistik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-bold uppercase">Total Siswa</span>
                <div class="text-2xl font-black text-slate-900 mt-1"><?= number_format($totalStudents) ?></div>
                <div class="text-[11px] text-slate-400 mt-0.5">Siswa terdaftar aktif</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-bold uppercase">Rombel / Kelas</span>
                <div class="text-2xl font-black text-indigo-900 mt-1"><?= number_format($totalRombels) ?></div>
                <div class="text-[11px] text-slate-400 mt-0.5">Otomatis terkelompokkan</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-bold uppercase">Rata-rata XP</span>
                <div class="text-2xl font-black text-amber-900 mt-1"><?= number_format($avgXp) ?></div>
                <div class="text-[11px] text-slate-400 mt-0.5">Skor keaktifan belajar</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-bolt"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-500 font-bold uppercase">Review Capstone</span>
                <div class="text-2xl font-black text-purple-900 mt-1"><?= number_format($pendingCapstones) ?></div>
                <div class="text-[11px] text-slate-400 mt-0.5">Menunggu penilaian guru</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-list-check"></i>
            </div>
        </div>
    </div>

    <!-- Grid Distribusi Rombel & Aktivitas Terkini -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Tabel Rombel Aktif (lg:col-span-7) -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-black uppercase tracking-wider text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chalkboard-user text-blue-600"></i> Kelompok Rombel Terdaftar
                </h2>
                <a href="<?= base_url('teacher/gradebook.php') ?>" class="text-xs font-bold text-blue-600 hover:text-blue-700">Lihat Buku Nilai &rarr;</a>
            </div>

            <?php if (empty($rombelList)): ?>
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-xs text-slate-500">
                    Belum ada rombel terdaftar. Silakan <a href="<?= base_url('teacher/import-csv.php') ?>" class="text-blue-600 font-bold underline">import data siswa dari file CSV</a>.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                                <th class="py-2.5 px-3">Nama Rombel</th>
                                <th class="py-2.5 px-3 text-center">Jumlah Siswa</th>
                                <th class="py-2.5 px-3 text-center">Rata-rata XP</th>
                                <th class="py-2.5 px-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($rombelList as $rb): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3 px-3 font-bold text-slate-800 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <?= sanitize($rb['rombel']) ?>
                                </td>
                                <td class="py-3 px-3 text-center font-semibold text-slate-600">
                                    <?= $rb['student_count'] ?> Siswa
                                </td>
                                <td class="py-3 px-3 text-center text-amber-700 font-bold">
                                    <?= number_format($rb['avg_xp']) ?> XP
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="<?= base_url('teacher/gradebook.php?rombel=' . urlencode($rb['rombel'])) ?>" class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 font-bold text-[11px] transition">
                                        Buka Nilai
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Aktivitas Belajar Terbaru (lg:col-span-5) -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-800 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i> Progres Siswa Terkini
            </h2>

            <?php if (empty($recentActivities)): ?>
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-xs text-slate-500">
                    Belum ada log penyelesaian materi oleh siswa.
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($recentActivities as $act): ?>
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-800"><?= sanitize($act['full_name']) ?></div>
                            <div class="text-[11px] text-slate-500"><?= sanitize($act['rombel'] ?? '-') ?> &bull; <?= sanitize($act['topic_title']) ?></div>
                        </div>
                        <div class="text-right">
                            <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 text-[10px]">
                                Tuntas
                            </span>
                            <div class="text-[10px] text-slate-400 mt-0.5"><?= date('H:i, d M', strtotime($act['completed_at'])) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
