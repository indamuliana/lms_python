<?php
/**
 * Dual-View Leaderboard (Peringkat Rombel vs Global) - Akses Publik Tanpa Login
 * Tema Esports Gaming Arena
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
    $leaderboardTitle = 'Peringkat Kelas ' . sanitize($selectedRombel);
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

// Pisahkan Top 3 untuk Podium Juara
$top1 = $rankings[0] ?? null;
$top2 = $rankings[1] ?? null;
$top3 = $rankings[2] ?? null;
$restRankings = array_slice($rankings, 3);

$isGamingTheme = true;
$pageTitle = 'Papan Peringkat Juara - Hall of Fame';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Header Title & Ambient Glow -->
    <div class="text-center max-w-2xl mx-auto mb-10 relative">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-black tracking-wider uppercase mb-3 shadow-lg shadow-amber-500/10">
            <i class="fa-solid fa-fire text-amber-400 animate-pulse"></i>
            <span>HALL OF FAME &bull; PAPAN PERINGKAT JUARA</span>
            <i class="fa-solid fa-fire text-amber-400 animate-pulse"></i>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight">
            Papan Peringkat <span class="bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500 bg-clip-text text-transparent">Juara</span>
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-2">
            Kumpulkan XP dengan membaca materi, kuis, dan menuntaskan tantangan koding untuk push rank menuju <strong class="text-amber-300">Mythical Honor</strong>!
        </p>
    </div>

    <!-- Dual-View Tabs Switcher & Filter Kelas (Esports Tab Style) -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-10">
        <div class="inline-flex p-1.5 bg-slate-900/90 border border-slate-800 rounded-2xl shadow-xl backdrop-blur-sm">
            <a href="?view=global" class="px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all flex items-center gap-2 <?= $view === 'global' ? 'bg-gradient-to-r from-amber-500 to-yellow-600 text-slate-950 shadow-md shadow-amber-500/25' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                <i class="fa-solid fa-globe <?= $view === 'global' ? 'text-slate-950' : 'text-indigo-400' ?>"></i>
                <span>Peringkat Global (Sekolah)</span>
            </a>
            <a href="?view=rombel<?= !empty($selectedRombel) ? '&rombel=' . urlencode($selectedRombel) : '' ?>" class="px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all flex items-center gap-2 <?= $view === 'rombel' ? 'bg-gradient-to-r from-amber-500 to-yellow-600 text-slate-950 shadow-md shadow-amber-500/25' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' ?>">
                <i class="fa-solid fa-users <?= $view === 'rombel' ? 'text-slate-950' : 'text-sky-400' ?>"></i>
                <span>Peringkat per Kelas</span>
            </a>
        </div>

        <?php if ($view === 'rombel' && !empty($rombelOptions)): ?>
        <form method="GET" action="" class="inline-flex items-center gap-1.5">
            <input type="hidden" name="view" value="rombel">
            <select name="rombel" onchange="this.form.submit()" class="text-xs font-bold bg-slate-900/90 border border-slate-700/80 rounded-2xl px-4 py-2.5 text-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-xl cursor-pointer">
                <?php foreach ($rombelOptions as $rb): ?>
                    <option value="<?= sanitize($rb) ?>" <?= $selectedRombel === $rb ? 'selected' : '' ?>>
                        Kelas <?= sanitize($rb) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php endif; ?>
    </div>

    <?php if (empty($rankings)): ?>
        <!-- State Kosong -->
        <div class="bg-slate-900/70 rounded-3xl border border-slate-800 p-12 text-center text-slate-400 shadow-2xl">
            <div class="w-16 h-16 rounded-2xl bg-slate-800 text-amber-400 flex items-center justify-center text-2xl mx-auto mb-4 border border-slate-700">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Belum Ada Petarung</h3>
            <p class="text-xs text-slate-500 mt-1">Belum ada data siswa di kelompok ini. Mulai kerjakan tantangan koding untuk tampil di sini!</p>
        </div>
    <?php else: ?>

        <!-- PODIUM 3D JUARA 1, 2, 3 -->
        <div class="mb-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end max-w-4xl mx-auto pt-8">
                
                <!-- JUARA 2 (PERAK - KIRI) -->
                <?php if ($top2): 
                    $rInfo2 = get_rank_info($top2['xp']);
                    $lbAvatar2 = get_user_avatar($top2['avatar'] ?? '');
                    $isMe2 = ($user && isset($user['id']) && $top2['id'] == $user['id']);
                ?>
                <div class="order-2 md:order-1 flex flex-col items-center">
                    <div class="relative mb-3 flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-slate-300 text-slate-900 flex items-center justify-center font-black text-xs shadow-md mb-2">
                            <i class="fa-solid fa-medal"></i>
                        </div>
                        <?php if ($lbAvatar2): ?>
                            <img src="<?= $lbAvatar2 ?>" alt="Avatar" class="w-20 h-20 rounded-2xl object-cover border-4 border-slate-300 shadow-xl shadow-slate-400/20">
                        <?php else: ?>
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 text-slate-200 border-4 border-slate-300 flex items-center justify-center text-2xl font-black shadow-xl">
                                <?= strtoupper(substr($top2['nickname'] ?: $top2['full_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-center w-full bg-gradient-to-b from-slate-900/90 to-slate-950 p-5 rounded-3xl border-2 border-slate-700/80 shadow-xl relative overflow-hidden">
                        <div class="absolute -top-6 -right-6 w-20 h-20 bg-slate-400/10 rounded-full blur-xl pointer-events-none"></div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-300 text-[10px] font-black uppercase tracking-wider mb-1.5 border border-slate-700">
                            JUARA 2
                        </span>
                        <h3 class="text-sm font-black text-white truncate px-2" title="<?= sanitize($top2['full_name']) ?>">
                            <?= sanitize($top2['nickname'] ?: $top2['full_name']) ?>
                            <?php if ($isMe2): ?><span class="text-[10px] bg-blue-600 text-white px-1.5 py-0.2 rounded-full font-bold ml-1">Anda</span><?php endif; ?>
                        </h3>
                        <div class="text-[11px] text-slate-400 mt-0.5 truncate"><?= sanitize($top2['rombel'] ?? '-') ?></div>
                        
                        <div class="mt-2.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black border <?= $rInfo2['badge_class'] ?>">
                                <i class="fa-solid <?= $rInfo2['icon'] ?>"></i> <?= $rInfo2['name'] ?>
                            </span>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-800 text-slate-200 font-black text-sm flex items-center justify-center gap-1 text-amber-400">
                            <i class="fa-solid fa-bolt"></i> <?= number_format($top2['xp']) ?> XP
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- JUARA 1 (EMAS - TENGAH / PALING TINGGI) -->
                <?php if ($top1): 
                    $rInfo1 = get_rank_info($top1['xp']);
                    $lbAvatar1 = get_user_avatar($top1['avatar'] ?? '');
                    $isMe1 = ($user && isset($user['id']) && $top1['id'] == $user['id']);
                ?>
                <div class="order-1 md:order-2 flex flex-col items-center -mt-6">
                    <div class="relative mb-3 flex flex-col items-center">
                        <div class="text-3xl text-amber-400 animate-bounce mb-1 drop-shadow-[0_4px_8px_rgba(251,191,36,0.5)]">
                            <i class="fa-solid fa-crown"></i>
                        </div>
                        <?php if ($lbAvatar1): ?>
                            <img src="<?= $lbAvatar1 ?>" alt="Avatar" class="w-24 h-24 rounded-3xl object-cover border-4 border-amber-400 shadow-2xl shadow-amber-500/40 ring-4 ring-amber-500/20">
                        <?php else: ?>
                            <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-amber-500 to-yellow-600 text-slate-950 border-4 border-amber-300 flex items-center justify-center text-3xl font-black shadow-2xl ring-4 ring-amber-500/20">
                                <?= strtoupper(substr($top1['nickname'] ?: $top1['full_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-center w-full bg-gradient-to-b from-amber-950/40 via-slate-900 to-slate-950 p-6 rounded-3xl border-2 border-amber-500/70 shadow-2xl shadow-amber-500/20 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-28 h-28 bg-amber-500/20 rounded-full blur-2xl pointer-events-none"></div>
                        <span class="inline-block px-3 py-1 rounded-full bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950 text-xs font-black uppercase tracking-wider mb-2 shadow-md">
                            👑 JUARA 1
                        </span>
                        <h3 class="text-base font-black text-white truncate px-2" title="<?= sanitize($top1['full_name']) ?>">
                            <?= sanitize($top1['nickname'] ?: $top1['full_name']) ?>
                            <?php if ($isMe1): ?><span class="text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded-full font-bold ml-1">Anda</span><?php endif; ?>
                        </h3>
                        <div class="text-xs text-amber-200/80 mt-0.5 truncate"><?= sanitize($top1['rombel'] ?? '-') ?></div>
                        
                        <div class="mt-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black border <?= $rInfo1['badge_class'] ?>">
                                <i class="fa-solid <?= $rInfo1['icon'] ?>"></i> <?= $rInfo1['name'] ?>
                            </span>
                        </div>

                        <div class="mt-4 pt-3 border-t border-amber-500/20 font-black text-lg flex items-center justify-center gap-1.5 text-amber-300 drop-shadow-[0_2px_4px_rgba(251,191,36,0.3)]">
                            <i class="fa-solid fa-bolt text-amber-400"></i> <?= number_format($top1['xp']) ?> XP
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- JUARA 3 (PERUNGGU - KANAN) -->
                <?php if ($top3): 
                    $rInfo3 = get_rank_info($top3['xp']);
                    $lbAvatar3 = get_user_avatar($top3['avatar'] ?? '');
                    $isMe3 = ($user && isset($user['id']) && $top3['id'] == $user['id']);
                ?>
                <div class="order-3 flex flex-col items-center">
                    <div class="relative mb-3 flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-amber-700 text-white flex items-center justify-center font-black text-xs shadow-md mb-2">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <?php if ($lbAvatar3): ?>
                            <img src="<?= $lbAvatar3 ?>" alt="Avatar" class="w-20 h-20 rounded-2xl object-cover border-4 border-amber-700 shadow-xl shadow-amber-800/20">
                        <?php else: ?>
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-900 to-slate-900 text-amber-200 border-4 border-amber-700 flex items-center justify-center text-2xl font-black shadow-xl">
                                <?= strtoupper(substr($top3['nickname'] ?: $top3['full_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-center w-full bg-gradient-to-b from-slate-900/90 to-slate-950 p-5 rounded-3xl border-2 border-amber-800/60 shadow-xl relative overflow-hidden">
                        <div class="absolute -top-6 -left-6 w-20 h-20 bg-amber-700/10 rounded-full blur-xl pointer-events-none"></div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-950 text-amber-300 text-[10px] font-black uppercase tracking-wider mb-1.5 border border-amber-800">
                            JUARA 3
                        </span>
                        <h3 class="text-sm font-black text-white truncate px-2" title="<?= sanitize($top3['full_name']) ?>">
                            <?= sanitize($top3['nickname'] ?: $top3['full_name']) ?>
                            <?php if ($isMe3): ?><span class="text-[10px] bg-blue-600 text-white px-1.5 py-0.2 rounded-full font-bold ml-1">Anda</span><?php endif; ?>
                        </h3>
                        <div class="text-[11px] text-slate-400 mt-0.5 truncate"><?= sanitize($top3['rombel'] ?? '-') ?></div>
                        
                        <div class="mt-2.5">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black border <?= $rInfo3['badge_class'] ?>">
                                <i class="fa-solid <?= $rInfo3['icon'] ?>"></i> <?= $rInfo3['name'] ?>
                            </span>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-800 text-slate-200 font-black text-sm flex items-center justify-center gap-1 text-amber-400">
                            <i class="fa-solid fa-bolt"></i> <?= number_format($top3['xp']) ?> XP
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- TABEL PERINGKAT (PERINGKAT 4 KE BAWAH) -->
        <div class="bg-slate-900/80 rounded-3xl border border-slate-800 shadow-2xl overflow-hidden backdrop-blur-sm">
            <div class="p-4 sm:p-6 bg-slate-950/80 border-b border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                <h2 class="text-sm sm:text-base font-black text-white flex items-center gap-2.5">
                    <i class="fa-solid fa-ranking-star text-amber-400"></i>
                    <span><?= $leaderboardTitle ?></span>
                </h2>
                <span class="text-xs font-bold text-slate-400 bg-slate-900 px-3 py-1 rounded-full border border-slate-800">
                    <?= count($rankings) ?> Siswa Terdaftar
                </span>
            </div>

            <?php if (empty($restRankings)): ?>
                <div class="p-8 text-center text-slate-500 text-xs sm:text-sm">
                    Seluruh peserta saat ini telah ditampilkan di Podium Juara di atas.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[11px] font-black border-b border-slate-800">
                                <th class="py-3.5 px-4 text-center w-16">Rank</th>
                                <th class="py-3.5 px-4">Nama Siswa &amp; Pangkat</th>
                                <th class="py-3.5 px-4 hidden sm:table-cell">Rombel</th>
                                <th class="py-3.5 px-4 text-center hidden md:table-cell">Badge</th>
                                <th class="py-3.5 px-4 text-right">Total XP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-slate-200">
                            <?php 
                            $rank = 4;
                            foreach ($restRankings as $row): 
                                $isMe = ($user && isset($user['id']) && $row['id'] == $user['id']);
                                $rInfo = get_rank_info($row['xp']);
                                $lbAvatar = get_user_avatar($row['avatar'] ?? '');
                            ?>
                            <tr class="<?= $isMe ? 'bg-blue-950/40 border-l-4 border-blue-500 font-semibold text-white' : 'hover:bg-slate-800/40' ?> transition">
                                <!-- Rank Number -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex w-7 h-7 rounded-xl bg-slate-800 text-slate-300 items-center justify-center font-black text-xs border border-slate-700">
                                        #<?= $rank ?>
                                    </span>
                                </td>

                                <!-- Nama Siswa & Level -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <?php if ($lbAvatar): ?>
                                            <img src="<?= $lbAvatar ?>" alt="Avatar" class="w-9 h-9 rounded-xl object-cover border border-slate-700 shadow-sm flex-shrink-0">
                                        <?php else: ?>
                                            <div class="w-9 h-9 rounded-xl <?= $isMe ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-300' ?> flex items-center justify-center text-xs font-black flex-shrink-0 border border-slate-700">
                                                <?= strtoupper(substr($row['nickname'] ?: $row['full_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="text-white font-black flex flex-wrap items-center gap-1.5 leading-snug">
                                                <?= sanitize($row['full_name']) ?>
                                                <?php if (!empty($row['nickname'])): ?>
                                                    <span class="text-[11px] text-amber-400 font-bold">(<?= sanitize($row['nickname']) ?>)</span>
                                                <?php endif; ?>
                                                <?php if ($isMe): ?>
                                                    <span class="bg-blue-600 text-white text-[10px] px-2 py-0.5 rounded-full font-black">Anda</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-[11px] text-slate-400 flex flex-wrap items-center gap-2 mt-1">
                                                <span>NIS: <?= sanitize($row['nis'] ?? '-') ?></span>
                                                <span>&bull;</span>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black border <?= $rInfo['badge_class'] ?>">
                                                    <i class="fa-solid <?= $rInfo['icon'] ?>"></i> <?= $rInfo['name'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Rombel -->
                                <td class="py-3.5 px-4 hidden sm:table-cell text-slate-300 text-xs">
                                    <span class="bg-slate-800/80 px-2.5 py-1 rounded-lg border border-slate-700 font-bold">
                                        <?= sanitize($row['rombel'] ?? '-') ?>
                                    </span>
                                </td>

                                <!-- Badge Count -->
                                <td class="py-3.5 px-4 text-center hidden md:table-cell">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-full border border-amber-500/20">
                                        <i class="fa-solid fa-medal"></i> <?= (int)$row['badge_count'] ?>
                                    </span>
                                </td>

                                <!-- Total XP -->
                                <td class="py-3.5 px-4 text-right">
                                    <span class="text-amber-400 font-black text-sm flex items-center justify-end gap-1">
                                        <i class="fa-solid fa-bolt text-xs"></i>
                                        <?= number_format($row['xp']) ?>
                                    </span>
                                    <span class="text-[10px] text-slate-500 block">XP</span>
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
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
