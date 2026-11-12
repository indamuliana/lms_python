<?php
/**
 * Panel Manajemen Kurikulum & Konten Pembelajaran - Dashboard Guru
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$user = current_user();
$db = get_db();

// Ambil seluruh modul beserta sub-topik, jumlah kuis, dan status tantangannya
$stmtModules = $db->query("SELECT * FROM modules ORDER BY order_index ASC");
$modules = $stmtModules->fetchAll();

$stmtTopics = $db->query("
    SELECT t.id, t.module_id, t.sub_code, t.title, t.slug, t.xp_reward, t.content_html,
           m.part_number, m.title as module_title, m.badge_name, m.badge_icon,
           (SELECT COUNT(*) FROM quizzes q WHERE q.topic_id = t.id) as quiz_count,
           (SELECT COUNT(*) FROM challenges c WHERE c.topic_id = t.id) as challenge_count
    FROM topics t
    JOIN modules m ON m.id = t.module_id
    ORDER BY m.part_number ASC, t.order_index ASC
");
$allTopics = $stmtTopics->fetchAll();

// Kelompokkan sub-topik berdasarkan module_id
$topicsByModule = [];
$totalQuizzes = 0;
$totalChallenges = 0;
foreach ($allTopics as $t) {
    $topicsByModule[$t['module_id']][] = $t;
    $totalQuizzes += (int)$t['quiz_count'];
    $totalChallenges += (int)$t['challenge_count'];
}

$selectedPart = isset($_GET['part']) && is_numeric($_GET['part']) ? (int)$_GET['part'] : null;

$pageTitle = 'Manajemen Kurikulum & Materi';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 border border-slate-800">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold uppercase tracking-wider mb-2">
                <i class="fa-solid fa-book-bookmark"></i> Pusat Konten Pembelajaran
            </div>
            <h1 class="text-2xl sm:text-3xl font-black">Manajemen Kurikulum &amp; Materi</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl">
                Kelola 40 sub-topik kurikulum Python, sesuaikan pembahasan 5 Blok Edukatif, atur butir soal kuis interaktif, dan kalibrasi kriteria tantangan koding mandiri.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="<?= base_url('student/dashboard.php') ?>" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-chalkboard-user"></i> Mode Tampilan Siswa
            </a>
            <a href="<?= base_url('teacher/dashboard.php') ?>" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs shadow-md transition flex items-center gap-2 border border-slate-700">
                <i class="fa-solid fa-arrow-left"></i> Dashboard Guru
            </a>
        </div>
    </div>

    <!-- 4 Metrik Ringkasan Kurikulum -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Total Modul</span>
                <div class="text-2xl font-black text-slate-900 mt-0.5"><?= count($modules) ?> Part</div>
                <div class="text-[11px] text-indigo-600 font-bold mt-0.5">Part 1 s/d Part 8</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Sub-Materi</span>
                <div class="text-2xl font-black text-slate-900 mt-0.5"><?= count($allTopics) ?> Topik</div>
                <div class="text-[11px] text-emerald-600 font-bold mt-0.5">Struktur 5 Blok Edukatif</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fa-solid fa-book-open"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Soal Kuis</span>
                <div class="text-2xl font-black text-slate-900 mt-0.5"><?= $totalQuizzes ?> Soal</div>
                <div class="text-[11px] text-amber-600 font-bold mt-0.5">3 Variasi Tiap Topik</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fa-solid fa-list-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Tantangan Koding</span>
                <div class="text-2xl font-black text-slate-900 mt-0.5"><?= $totalChallenges ?> Uji Kasus</div>
                <div class="text-[11px] text-purple-600 font-bold mt-0.5">Auto-Grader WebAssembly</div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-sm">
                <i class="fa-solid fa-code"></i>
            </div>
        </div>
    </div>

    <!-- Filter Bar & Search -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Filter Part Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-2 md:pb-0 scrollbar-none">
            <a href="?part=" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap <?= $selectedPart === null ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                Semua Part (1–8)
            </a>
            <?php foreach ($modules as $m): ?>
                <a href="?part=<?= $m['part_number'] ?>" class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap <?= $selectedPart === (int)$m['part_number'] ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                    Part <?= $m['part_number'] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Live Instant Search Input -->
        <div class="relative w-full md:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" id="curriculumSearch" placeholder="Cari nama materi atau sub-kode..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition">
        </div>
    </div>

    <!-- Daftar Modul & Sub-Topik -->
    <div class="space-y-6" id="moduleContainer">
        <?php foreach ($modules as $m): 
            if ($selectedPart !== null && (int)$m['part_number'] !== $selectedPart) {
                continue;
            }
            $moduleTopics = $topicsByModule[$m['id']] ?? [];
        ?>
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden module-card" data-part="<?= $m['part_number'] ?>">
            <!-- Header Modul -->
            <div class="p-5 sm:p-6 bg-slate-50/80 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center text-xl shadow-md shrink-0">
                        <i class="fa-solid <?= sanitize($m['badge_icon'] ?: 'fa-book') ?>"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-[10px] font-black uppercase tracking-wider">
                                Part <?= $m['part_number'] ?>
                            </span>
                            <span class="text-xs text-slate-500 font-semibold">&bull; Lencana: <?= sanitize($m['badge_name']) ?></span>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 mt-0.5">
                            <?= sanitize($m['title']) ?>
                        </h2>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 self-end sm:self-center">
                    <span class="bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-xs">
                        <i class="fa-solid fa-list-ol text-indigo-500 mr-1"></i> <?= count($moduleTopics) ?> Sub-Materi
                    </span>
                </div>
            </div>

            <!-- Tabel Sub-Topik -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-white text-slate-400 uppercase tracking-wider text-[11px] font-bold border-b border-slate-100">
                            <th class="py-3 px-4 w-16 text-center">Kode</th>
                            <th class="py-3 px-4">Judul Sub-Materi</th>
                            <th class="py-3 px-4 hidden md:table-cell">Kelengkapan Konten</th>
                            <th class="py-3 px-4 text-center">XP Reward</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($moduleTopics as $t): ?>
                        <tr class="hover:bg-slate-50/70 transition topic-row" data-title="<?= strtolower(sanitize($t['title'])) ?>" data-code="<?= sanitize($t['sub_code']) ?>">
                            <!-- Sub Code -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex w-9 h-7 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 font-black items-center justify-center text-xs">
                                    <?= sanitize($t['sub_code']) ?>
                                </span>
                            </td>

                            <!-- Title -->
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                <div class="flex flex-col">
                                    <span class="text-slate-900"><?= sanitize($t['title']) ?></span>
                                    <span class="text-[11px] text-slate-400 font-normal">Slug: <?= sanitize($t['slug']) ?></span>
                                </div>
                            </td>

                            <!-- Status Kelengkapan Konten -->
                            <td class="py-3.5 px-4 hidden md:table-cell">
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Materi 5 Blok -->
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[9px]"></i> 5 Blok Edukatif
                                    </span>

                                    <!-- Kuis -->
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $t['quiz_count'] >= 3 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200' ?>">
                                        <i class="fa-solid fa-list-check text-[9px]"></i> <?= (int)$t['quiz_count'] ?> Soal Kuis
                                    </span>

                                    <!-- Tantangan -->
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold <?= $t['challenge_count'] > 0 ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-600' ?>">
                                        <i class="fa-solid fa-code text-[9px]"></i> <?= (int)$t['challenge_count'] ?> Tantangan
                                    </span>
                                </div>
                            </td>

                            <!-- XP Reward -->
                            <td class="py-3.5 px-4 text-center font-bold text-amber-700">
                                <span class="bg-amber-50/80 px-2.5 py-1 rounded-lg border border-amber-200/80 text-xs">
                                    <i class="fa-solid fa-bolt text-amber-500"></i> +<?= (int)$t['xp_reward'] ?> XP
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= base_url('student/learn.php?part=' . $m['part_number'] . '&sub=' . urlencode($t['sub_code'])) ?>" target="_blank" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition inline-flex items-center gap-1" title="Lihat Tampilan Siswa">
                                        <i class="fa-solid fa-eye"></i> <span class="hidden sm:inline">Preview</span>
                                    </a>
                                    <a href="<?= base_url('teacher/topic-edit.php?id=' . $t['id']) ?>" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs shadow-sm transition inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-pen-to-square"></i> <span>Edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Filter pencarian live topik kurikulum
document.getElementById('curriculumSearch')?.addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.topic-row');
    const modules = document.querySelectorAll('.module-card');

    rows.forEach(r => {
        const title = r.getAttribute('data-title') || '';
        const code = r.getAttribute('data-code') || '';
        if (title.includes(q) || code.includes(q)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });

    // Sembunyikan modul jika seluruh sub-topiknya terfilter
    modules.forEach(m => {
        const visibleRows = m.querySelectorAll('.topic-row:not([style*="display: none"])');
        m.style.display = (visibleRows.length > 0) ? '' : 'none';
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
