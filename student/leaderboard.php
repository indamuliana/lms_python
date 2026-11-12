<?php
/**
 * Dual-View Leaderboard (Peringkat Rombel vs Global) - Akses Publik Tanpa Login
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

// Publik: Pengunjung dapat melihat tanpa login
$user = current_user();
$db = get_db();

// Ambil daftar seluruh rombel untuk filter publik
$rombelOptions = $db->query("SELECT DISTINCT rombel FROM users WHERE role = 'siswa' AND rombel IS NOT NULL AND rombel != '' ORDER BY rombel ASC")->fetchAll(PDO::FETCH_COLUMN);

$view = isset($_GET['view']) && $_GET['view'] === 'rombel' ? 'rombel' : 'global';
$selectedRombel = trim($_GET['rombel'] ?? ($user['rombel'] ?? ($rombelOptions[0] ?? '')));

// Query data siswa berdasarkan view
if ($view === 'rombel' && !empty($selectedRombel)) {
    $stmt = $db->prepare("
        SELECT u.id, u.nis, u.full_name, u.nickname, u.avatar, u.rombel, u.rayon, u.xp,
               COUNT(DISTINCT sb.id) as badge_count,
               COUNT(DISTINCT sp.topic_id) as topics_completed
        FROM users u
        LEFT JOIN student_badges sb ON sb.user_id = u.id
        LEFT JOIN student_progress sp ON sp.user_id = u.id AND sp.is_completed = 1
        WHERE u.role = 'siswa' AND u.rombel = ?
        GROUP BY u.id
        ORDER BY u.xp DESC, badge_count DESC, u.full_name ASC
    ");
    $stmt->execute([$selectedRombel]);
    $rankings = $stmt->fetchAll();
    $leaderboardTitle = 'Peringkat Kelas: ' . sanitize($selectedRombel);
} else {
    $stmt = $db->query("
        SELECT u.id, u.nis, u.full_name, u.nickname, u.avatar, u.rombel, u.rayon, u.xp,
               COUNT(DISTINCT sb.id) as badge_count,
               COUNT(DISTINCT sp.topic_id) as topics_completed
        FROM users u
        LEFT JOIN student_badges sb ON sb.user_id = u.id
        LEFT JOIN student_progress sp ON sp.user_id = u.id AND sp.is_completed = 1
        WHERE u.role = 'siswa'
        GROUP BY u.id
        ORDER BY u.xp DESC, badge_count DESC, u.full_name ASC
        LIMIT 50
    ");
    $rankings = $stmt->fetchAll();
    $leaderboardTitle = 'Peringkat Global (Seluruh Sekolah)';
}

$pageTitle = 'Papan Peringkat Siswa';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Title & Gamification Icon -->
    <div class="text-center max-w-xl mx-auto mb-8">
        <div class="inline-flex w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 items-center justify-center text-3xl shadow-md mb-3">
            <i class="fa-solid fa-trophy text-amber-500"></i>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Papan Peringkat Siswa</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Kumpulkan XP dengan membaca materi, menjawab kuis, dan menyelesaikan tantangan kode.</p>
    </div>

    <!-- Dual-View Tabs Switcher & Filter Kelas -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-6">
        <div class="inline-flex p-1 bg-slate-200/80 rounded-2xl shadow-inner">
            <a href="?view=global" class="px-5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 <?= $view === 'global' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">
                <i class="fa-solid fa-globe text-indigo-500"></i>
                <span>Peringkat Global (Sekolah)</span>
            </a>
            <a href="?view=rombel<?= !empty($selectedRombel) ? '&rombel=' . urlencode($selectedRombel) : '' ?>" class="px-5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 <?= $view === 'rombel' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">
                <i class="fa-solid fa-users text-blue-500"></i>
                <span>Peringkat per Kelas</span>
            </a>
        </div>

        <?php if ($view === 'rombel' && !empty($rombelOptions)): ?>
        <form method="GET" action="" class="inline-flex items-center gap-1.5">
            <input type="hidden" name="view" value="rombel">
            <select name="rombel" onchange="this.form.submit()" class="text-xs font-bold bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                <?php foreach ($rombelOptions as $rb): ?>
                    <option value="<?= sanitize($rb) ?>" <?= $selectedRombel === $rb ? 'selected' : '' ?>>
                        Kelas <?= sanitize($rb) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php endif; ?>
    </div>

    <!-- Tabel Peringkat -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xs sm:text-sm font-bold text-slate-700 flex items-center gap-2">
                <i class="fa-solid fa-award text-amber-500"></i> <?= $leaderboardTitle ?>
            </h2>
            <span class="text-xs text-slate-500"><?= count($rankings) ?> Siswa Terdaftar</span>
        </div>

        <?php if (empty($rankings)): ?>
            <div class="p-8 text-center text-slate-400 text-sm">
                Belum ada data siswa di kelompok ini.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-200">
                            <th class="py-3 px-4 text-center w-14">Rank</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4 hidden sm:table-cell">Rombel</th>
                            <th class="py-3 px-4 text-center">Lencana</th>
                            <th class="py-3 px-4 text-right">Total XP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php 
                        $rank = 1;
                        foreach ($rankings as $row): 
                            $isMe = ($user && isset($user['id']) && $row['id'] == $user['id']);
                        ?>
                        <tr class="<?= $isMe ? 'bg-blue-50/70 font-semibold' : 'hover:bg-slate-50/80' ?> transition">
                            <!-- Rank Icon / Number -->
                            <td class="py-3.5 px-4 text-center">
                                <?php if ($rank === 1): ?>
                                    <span class="inline-flex w-7 h-7 rounded-full bg-amber-400 text-slate-900 items-center justify-center font-black text-xs shadow-sm">
                                        <i class="fa-solid fa-crown text-[11px]"></i>
                                    </span>
                                <?php elseif ($rank === 2): ?>
                                    <span class="inline-flex w-7 h-7 rounded-full bg-slate-300 text-slate-800 items-center justify-center font-black text-xs shadow-sm">
                                        2
                                    </span>
                                <?php elseif ($rank === 3): ?>
                                    <span class="inline-flex w-7 h-7 rounded-full bg-amber-600 text-white items-center justify-center font-black text-xs shadow-sm">
                                        3
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs font-bold text-slate-400">#<?= $rank ?></span>
                                <?php endif; ?>
                            </td>

                            <!-- Nama Siswa & Level -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <?php $lbAvatar = get_user_avatar($row['avatar'] ?? ''); ?>
                                    <?php if ($lbAvatar): ?>
                                        <img src="<?= $lbAvatar ?>" alt="Avatar" class="w-8 h-8 rounded-xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-8 h-8 rounded-xl <?= $isMe ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' ?> flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            <?= strtoupper(substr($row['nickname'] ?: $row['full_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="text-slate-900 font-bold flex flex-wrap items-center gap-1.5 leading-snug">
                                            <?= sanitize($row['full_name']) ?>
                                            <?php if (!empty($row['nickname'])): ?>
                                                <span class="text-[11px] text-blue-600 font-bold">(<?= sanitize($row['nickname']) ?>)</span>
                                            <?php endif; ?>
                                            <?php if ($isMe): ?>
                                                <span class="bg-blue-600 text-white text-[10px] px-2 py-0.2 rounded-full font-extrabold">Anda</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php $rInfo = get_rank_info($row['xp']); ?>
                                        <div class="text-[11px] text-slate-500 font-normal flex flex-wrap items-center gap-1.5 mt-0.5">
                                            <span>NIS: <?= sanitize($row['nis'] ?? '-') ?></span>
                                            <span>&bull;</span>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border <?= $rInfo['badge_class'] ?>">
                                                <i class="fa-solid <?= $rInfo['icon'] ?>"></i> <?= $rInfo['name'] ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Rombel -->
                            <td class="py-3.5 px-4 hidden sm:table-cell text-slate-600 text-xs">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200 font-medium">
                                    <?= sanitize($row['rombel'] ?? '-') ?>
                                </span>
                            </td>

                            <!-- Lencana -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                                    <i class="fa-solid fa-medal text-amber-500"></i> <?= $row['badge_count'] ?>
                                </span>
                            </td>

                            <!-- Total XP -->
                            <td class="py-3.5 px-4 text-right">
                                <span class="font-black text-blue-600 flex items-center justify-end gap-1">
                                    <i class="fa-solid fa-bolt text-yellow-500 text-xs"></i>
                                    <?= number_format($row['xp']) ?> <span class="text-[10px] text-slate-400 font-normal">XP</span>
                                </span>
                            </td>
                        </tr>
                        <?php 
                        $rank++;
                        endforeach; 
                        ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
