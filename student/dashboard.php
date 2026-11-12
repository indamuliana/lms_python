<?php
/**
 * Student Dashboard - Roadmap Belajar & Progres 40 Sub-Part
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$user = current_user();

if (!$user) {
    header('Location: ' . base_url('login.php'));
    exit;
}


$db = get_db();

// Ambil daftar seluruh 8 Modul
$stmt = $db->query("SELECT * FROM modules ORDER BY part_number ASC");
$modules = $stmt->fetchAll();

// Ambil semua topik dan progres siswa
$stmtProg = $db->prepare("
    SELECT t.id as topic_id, t.module_id, t.sub_code, t.order_index,
           COALESCE(sp.is_completed, 0) as is_completed,
           COALESCE(sp.challenge_completed, 0) as challenge_completed,
           COALESCE(sp.quiz_score, 0) as quiz_score
    FROM topics t
    LEFT JOIN student_progress sp ON sp.topic_id = t.id AND sp.user_id = ?
    ORDER BY t.module_id ASC, t.order_index ASC
");
$stmtProg->execute([$user['id']]);
$allTopics = $stmtProg->fetchAll();

// Petakan per modul
$moduleTopicsMap = [];
$moduleStats = [];

foreach ($allTopics as $top) {
    $mid = $top['module_id'];
    $moduleTopicsMap[$mid][] = $top;
    if (!isset($moduleStats[$mid])) {
        $moduleStats[$mid] = ['total' => 0, 'completed' => 0];
    }
    $moduleStats[$mid]['total']++;
    if ($top['is_completed']) {
        $moduleStats[$mid]['completed']++;
    }
}

// Ambil daftar badge siswa
$userBadges = get_user_badges($user['id']);

$pageTitle = 'Roadmap Belajar';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Profil & Gamifikasi Siswa -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-sky-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-8 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <!-- Info Siswa -->
            <div class="flex items-center gap-4">
                <a href="<?= base_url('student/profile.php') ?>" class="relative group" title="Ubah Foto Profil">
                    <?php $dashAvatar = get_user_avatar($user['avatar'] ?? ''); ?>
                    <?php if ($dashAvatar): ?>
                        <img src="<?= $dashAvatar ?>" alt="Foto Profil" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-white/60 shadow-lg">
                    <?php else: ?>
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-3xl sm:text-4xl shadow-inner">
                            <i class="fa-solid fa-user-astronaut text-yellow-300"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-xs font-bold text-white">
                        <i class="fa-solid fa-camera mr-1"></i> Ubah
                    </div>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight">
                            <?= sanitize($user['full_name']) ?>
                            <?php if (!empty($user['nickname'])): ?>
                                <span class="text-xs font-bold text-yellow-300 bg-white/10 px-2 py-0.5 rounded-full border border-white/20 ml-1">
                                    "<?= sanitize($user['nickname']) ?>"
                                </span>
                            <?php endif; ?>
                        </h1>
                        <span class="inline-flex items-center gap-1.5 font-black text-xs px-3.5 py-1 rounded-full border shadow-md tracking-wide <?= $user['rank_badge'] ?? 'bg-amber-700 text-white' ?>">
                            <i class="fa-solid <?= $user['rank_icon'] ?? 'fa-shield-halved' ?>"></i> <?= $user['level'] ?>
                        </span>
                    </div>
                    <div class="text-xs sm:text-sm text-blue-100 mt-1 flex flex-wrap items-center gap-3">
                        <span><i class="fa-solid fa-id-card opacity-80 mr-1"></i> NIS: <?= sanitize($user['nis'] ?? '-') ?></span>
                        <span><i class="fa-solid fa-users opacity-80 mr-1"></i> Rombel: <?= sanitize($user['rombel'] ?? '-') ?></span>
                        <span><i class="fa-solid fa-location-dot opacity-80 mr-1"></i> Rayon: <?= sanitize($user['rayon'] ?? '-') ?></span>
                        <a href="<?= base_url('student/profile.php') ?>" class="text-[11px] font-bold text-yellow-300 hover:text-white bg-black/20 hover:bg-black/30 px-2 py-0.5 rounded-md border border-white/20 transition flex items-center gap-1">
                            <i class="fa-solid fa-user-gear"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kartu XP & Progress Rank Mobile Legends -->
            <div class="bg-black/30 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20 min-w-[260px]">
                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                    <span class="flex items-center text-yellow-300 font-black"><i class="fa-solid fa-bolt mr-1.5 text-yellow-400"></i> <?= number_format($user['xp']) ?> XP</span>
                    <span class="text-white font-black text-xs flex items-center gap-1 bg-white/15 px-2.5 py-0.5 rounded-lg border border-white/20 shadow-sm">
                        <?= !empty($user['is_max_rank']) ? 'Rank Tertinggi ⭐' : 'Menuju ' . sanitize($user['next_rank_name']) ?>
                    </span>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-white/20 h-2.5 rounded-full overflow-hidden shadow-inner">
                    <div class="bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-400 h-full rounded-full transition-all duration-500 shadow-sm" style="width: <?= $user['level_progress'] ?>%"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-white/90 font-medium mt-1.5">
                    <span><?= $user['level_progress'] ?>% tercapai</span>
                    <span class="text-yellow-300 font-bold"><?= !empty($user['is_max_rank']) ? 'Mythical Honor' : 'Kurang ' . number_format($user['xp_needed']) . ' XP' ?></span>
                </div>
            </div>
        </div>

        <!-- Koleksi Badge Showcase -->
        <?php if (!empty($userBadges)): ?>
        <div class="mt-6 pt-5 border-t border-white/15 flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-yellow-200 mr-2 flex items-center">
                <i class="fa-solid fa-medal mr-1.5 text-yellow-300"></i> Lencana Diperoleh (<?= count($userBadges) ?>):
            </span>
            <?php foreach ($userBadges as $b): ?>
                <span class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm border border-white/20 transition">
                    <i class="fa-solid <?= sanitize($b['badge_icon']) ?> text-yellow-300"></i>
                    <span><?= sanitize($b['badge_name']) ?></span>
                </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Judul Section Roadmap -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Roadmap 8 Part (40 Sub-Materi Harian)</h2>
            <p class="text-xs sm:text-sm text-slate-500">Tiap part memiliki 5 sub-materi dan 5 tantangan koding. Selesaikan seluruhnya untuk membuka part berikutnya.</p>
        </div>
        <a href="<?= base_url('student/leaderboard.php') ?>" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold hover:bg-amber-100 transition">
            <i class="fa-solid fa-trophy text-amber-500"></i> Peringkat Kelas
        </a>
    </div>

    <!-- Grid Kartu Roadmap 8 Part -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <?php foreach ($modules as $m): 
            $isUnlocked = is_part_unlocked($user['id'], $m['part_number']);
            $stat = $moduleStats[$m['id']] ?? ['total' => 5, 'completed' => 0];
            $isCompleted = ($stat['completed'] >= $stat['total'] && $stat['total'] > 0);
            $subList = $moduleTopicsMap[$m['id']] ?? [];
            $percent = $stat['total'] > 0 ? round(($stat['completed'] / $stat['total']) * 100) : 0;
            
            // Cari sub-part pertama yang belum selesai
            $targetSub = '1';
            foreach ($subList as $st) {
                if (!$st['is_completed']) {
                    $targetSub = $st['sub_code'];
                    break;
                }
            }
        ?>
        <div class="bg-white rounded-2xl border <?= $isUnlocked ? ($isCompleted ? 'border-emerald-300 shadow-sm' : 'border-blue-300 ring-2 ring-blue-500/10 shadow-md') : 'border-slate-200 opacity-70 bg-slate-50/70' ?> p-5 flex flex-col justify-between transition hover:-translate-y-0.5 duration-200">
            <div>
                <!-- Header Part Card -->
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-black tracking-wider <?= $isUnlocked ? 'text-blue-600' : 'text-slate-400' ?>">
                        PART <?= $m['part_number'] ?>
                    </span>
                    <span class="w-8 h-8 rounded-xl flex items-center justify-center text-sm <?= $isUnlocked ? ($isCompleted ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600') : 'bg-slate-200 text-slate-400' ?>">
                        <i class="fa-solid <?= $m['badge_icon'] ?? 'fa-book' ?>"></i>
                    </span>
                </div>

                <h3 class="font-bold text-slate-800 text-base mb-1.5 leading-snug">
                    <?= sanitize($m['title']) ?>
                </h3>
                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                    <?= sanitize($m['description']) ?>
                </p>

                <!-- Sub-part Micro Dots (5 Hari / 5 Sub-part) -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center justify-between text-[11px] font-semibold text-slate-500">
                        <span>Progres 5 Sub-Part:</span>
                        <span class="font-bold <?= $isCompleted ? 'text-emerald-600' : 'text-blue-600' ?>">
                            <?= $stat['completed'] ?> / <?= $stat['total'] ?> (<?= $percent ?>%)
                        </span>
                    </div>

                    <!-- Micro Pill Bar -->
                    <div class="grid grid-cols-5 gap-1.5">
                        <?php foreach ($subList as $st): ?>
                            <div class="h-2 rounded-full transition <?= $st['is_completed'] ? 'bg-emerald-500' : ($isUnlocked ? 'bg-blue-200' : 'bg-slate-200') ?>" title="<?= sanitize($st['sub_code']) ?>: <?= $st['is_completed'] ? 'Tuntas' : 'Belum' ?>"></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Footer Card & Action Button -->
            <div class="pt-4 border-t border-slate-100">
                <?php if ($isUnlocked): ?>
                    <div class="flex items-center justify-between gap-2">
                        <a href="<?= base_url('student/learn.php?part=' . $m['part_number'] . '&sub=' . $targetSub) ?>" class="flex-1 py-2 px-3 rounded-xl <?= $isCompleted ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-blue-600 hover:bg-blue-700 text-white' ?> text-xs font-bold text-center transition flex items-center justify-center gap-1.5 shadow-sm">
                            <?php if ($isCompleted): ?>
                                <i class="fa-solid fa-circle-check text-emerald-500"></i> Ulas Materi
                            <?php else: ?>
                                <i class="fa-solid fa-play text-[10px]"></i> Lanjut Sub <?= sanitize($targetSub) ?>
                            <?php endif; ?>
                        </a>
                        <a href="<?= base_url('student/challenge.php?part=' . $m['part_number'] . '&sub=' . $targetSub) ?>" title="Tantangan Koding" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition">
                            <i class="fa-solid fa-code"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center py-2 px-3 rounded-xl bg-slate-100 text-slate-400 text-xs font-bold gap-2 cursor-not-allowed">
                        <i class="fa-solid fa-lock text-[11px]"></i> Selesaikan Part <?= $m['part_number'] - 1 ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
