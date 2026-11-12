<?php
/**
 * Buku Nilai Guru (Gradebook Matrix & Monitoring 40 Sub-Part)
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$user = current_user();
$db = get_db();

// Filter Rombel & Rayon
$selectedRombel = trim($_GET['rombel'] ?? '');
$selectedRayon = trim($_GET['rayon'] ?? '');
$searchKeyword = trim($_GET['q'] ?? '');

$rombelOptions = $db->query("SELECT DISTINCT rombel FROM users WHERE role = 'siswa' AND rombel IS NOT NULL AND rombel != '' ORDER BY rombel ASC")->fetchAll(PDO::FETCH_COLUMN);
$rayonOptions = $db->query("SELECT DISTINCT rayon FROM users WHERE role = 'siswa' AND rayon IS NOT NULL AND rayon != '' ORDER BY rayon ASC")->fetchAll(PDO::FETCH_COLUMN);

// Handle manual override unlock part
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'unlock_part') {
    if (verify_csrf($_POST['csrf_token'] ?? '')) {
        $targetUserId = (int)$_POST['target_user_id'];
        $targetPart = (int)$_POST['target_part'];
        if ($targetPart >= 1 && $targetPart <= 8) {
            $stmtUp = $db->prepare("UPDATE users SET current_part = ? WHERE id = ?");
            $stmtUp->execute([$targetPart, $targetUserId]);
            set_flash('success', "Akses part siswa berhasil diperbarui ke Part {$targetPart}.");
        }
    }
    header('Location: ' . base_url('teacher/gradebook.php?' . http_build_query($_GET)));
    exit;
}

// Bangun query siswa dengan filter
$where = ["role = 'siswa'"];
$params = [];

if (!empty($selectedRombel)) {
    $where[] = "rombel = ?";
    $params[] = $selectedRombel;
}
if (!empty($selectedRayon)) {
    $where[] = "rayon = ?";
    $params[] = $selectedRayon;
}
if (!empty($searchKeyword)) {
    $where[] = "(full_name LIKE ? OR nis LIKE ?)";
    $params[] = "%{$searchKeyword}%";
    $params[] = "%{$searchKeyword}%";
}

$whereSql = implode(' AND ', $where);
$stmtStudents = $db->prepare("SELECT * FROM users WHERE {$whereSql} ORDER BY rombel ASC, full_name ASC");
$stmtStudents->execute($params);
$students = $stmtStudents->fetchAll();

// Ambil matriks progres 5 sub-topik per part per siswa
$allProgress = [];
$capstoneMap = [];

if (!empty($students)) {
    $studentIds = array_column($students, 'id');
    $inClause = implode(',', array_fill(0, count($studentIds), '?'));
    
    $stmtProg = $db->prepare("
        SELECT sp.user_id, m.part_number, 
               COUNT(sp.id) as completed_subparts,
               AVG(sp.quiz_score) as avg_quiz
        FROM student_progress sp
        JOIN topics t ON t.id = sp.topic_id
        JOIN modules m ON m.id = t.module_id
        WHERE sp.user_id IN ({$inClause}) AND sp.is_completed = 1
        GROUP BY sp.user_id, m.part_number
    ");
    $stmtProg->execute($studentIds);
    $rows = $stmtProg->fetchAll();
    
    foreach ($rows as $r) {
        $allProgress[$r['user_id']][$r['part_number']] = $r;
    }

    // Ambil data capstone
    $stmtCap = $db->prepare("SELECT user_id, status, teacher_score FROM capstone_submissions WHERE user_id IN ({$inClause})");
    $stmtCap->execute($studentIds);
    foreach ($stmtCap->fetchAll() as $c) {
        $capstoneMap[$c['user_id']] = $c;
    }
}

$pageTitle = 'Buku Nilai Siswa (40 Sub-Part)';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Page & Export Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1">
                <a href="<?= base_url('teacher/dashboard.php') ?>" class="hover:text-blue-600">Dashboard Guru</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-800 font-bold">Buku Nilai</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Buku Nilai & Monitoring Progres Harian</h1>
            <p class="text-xs sm:text-sm text-slate-500">Pantau ketuntasan 5 sub-topik dan tantangan koding pada setiap part.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('teacher/export-grades.php?' . http_build_query($_GET)) ?>" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export Nilai (CSV / Excel)
            </a>
        </div>
    </div>

    <!-- Filter Bar Interaktif -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm mb-6">
        <form method="GET" action="<?= base_url('teacher/gradebook.php') ?>" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Filter Rombel</label>
                <select name="rombel" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Rombel --</option>
                    <?php foreach ($rombelOptions as $rb): ?>
                        <option value="<?= sanitize($rb) ?>" <?= $selectedRombel === $rb ? 'selected' : '' ?>><?= sanitize($rb) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Filter Rayon</label>
                <select name="rayon" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Rayon --</option>
                    <?php foreach ($rayonOptions as $ry): ?>
                        <option value="<?= sanitize($ry) ?>" <?= $selectedRayon === $ry ? 'selected' : '' ?>><?= sanitize($ry) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Nama / NIS</label>
                <input type="text" name="q" value="<?= sanitize($searchKeyword) ?>" placeholder="Ketik nama atau NIS..."
                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="sm:col-span-2 flex gap-1.5">
                <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-filter text-[10px]"></i> Terapkan
                </button>
                <a href="<?= base_url('teacher/gradebook.php') ?>" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs flex items-center justify-center" title="Reset">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Matriks Tabel Buku Nilai -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between text-xs text-slate-600 font-semibold">
            <span>Total Siswa Terdata: <?= count($students) ?> orang</span>
            <div class="flex items-center gap-3 text-[11px]">
                <span class="flex items-center gap-1"><span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 font-bold">5/5</span> Tuntas Penuh</span>
                <span class="flex items-center gap-1"><span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold">X/5</span> Sedang Berjalan</span>
                <span class="flex items-center gap-1"><span class="text-slate-300">-</span> Belum Mulai</span>
            </div>
        </div>

        <?php if (empty($students)): ?>
            <div class="p-12 text-center text-slate-400 text-xs sm:text-sm">
                Tidak ada data siswa yang cocok dengan filter yang dipilih.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 font-bold border-b border-slate-200 uppercase text-[10px] tracking-wider text-center">
                            <th class="py-3 px-3 text-left w-10">No</th>
                            <th class="py-3 px-3 text-left min-w-[170px]">Nama & NIS</th>
                            <th class="py-3 px-2 text-left">Rombel</th>
                            <th class="py-3 px-2 text-left">Rayon</th>
                            <th class="py-3 px-2 w-14" title="Part 1: Pengenalan (5 Sub)">P1</th>
                            <th class="py-3 px-2 w-14" title="Part 2: Operator (5 Sub)">P2</th>
                            <th class="py-3 px-2 w-14" title="Part 3: Percabangan (5 Sub)">P3</th>
                            <th class="py-3 px-2 w-14" title="Part 4: Perulangan (5 Sub)">P4</th>
                            <th class="py-3 px-2 w-14" title="Part 5: Struktur Data (5 Sub)">P5</th>
                            <th class="py-3 px-2 w-14" title="Part 6: Fungsi (5 Sub)">P6</th>
                            <th class="py-3 px-2 w-14" title="Part 7: File & Error (5 Sub)">P7</th>
                            <th class="py-3 px-2 w-16" title="Part 8: Capstone Project">Capstone</th>
                            <th class="py-3 px-3 text-right">Total XP</th>
                            <th class="py-3 px-3 text-center">Buka Part</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php 
                        $no = 1;
                        foreach ($students as $st): 
                            $stProg = $allProgress[$st['id']] ?? [];
                            $cap = $capstoneMap[$st['id']] ?? null;
                        ?>
                        <tr class="hover:bg-slate-50/80 transition text-center">
                            <td class="py-3 px-3 text-left text-slate-400 font-medium"><?= $no++ ?></td>
                            
                            <td class="py-3 px-3 text-left">
                                <div class="font-bold text-slate-800 leading-tight"><?= sanitize($st['full_name']) ?></div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">NIS: <?= sanitize($st['nis'] ?? '-') ?></div>
                            </td>

                            <td class="py-3 px-2 text-left">
                                <span class="bg-slate-100 px-2 py-0.5 rounded text-[11px] font-semibold text-slate-700">
                                    <?= sanitize($st['rombel'] ?? '-') ?>
                                </span>
                            </td>

                            <td class="py-3 px-2 text-left text-[11px] text-slate-500">
                                <?= sanitize($st['rayon'] ?? '-') ?>
                            </td>

                            <!-- Matriks Part 1 s/d 7 (Masing-masing 5 Sub-part) -->
                            <?php for ($p = 1; $p <= 7; $p++): 
                                $doneSub = $stProg[$p]['completed_subparts'] ?? 0;
                            ?>
                                <td class="py-3 px-2">
                                    <?php if ($doneSub >= 5): ?>
                                        <span class="inline-flex px-1.5 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold text-[10px]" title="Tuntas 5 dari 5 sub-topik">
                                            5/5 ✓
                                        </span>
                                    <?php elseif ($doneSub > 0): ?>
                                        <span class="inline-flex px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-800 font-bold text-[10px]" title="<?= $doneSub ?> dari 5 sub-topik selesai">
                                            <?= $doneSub ?>/5
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-300 text-[10px]">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>

                            <!-- Capstone (Part 8) -->
                            <td class="py-3 px-2">
                                <?php if ($cap): ?>
                                    <?php if ($cap['status'] === 'approved'): ?>
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-[10px]">
                                            <?= $cap['teacher_score'] ?? 'Lulus' ?>
                                        </span>
                                    <?php elseif ($cap['status'] === 'pending'): ?>
                                        <a href="<?= base_url('teacher/capstone-review.php?student_id=' . $st['id']) ?>" class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] hover:underline" title="Klik untuk nilai">
                                            Review
                                        </a>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold text-[10px]">Revisi</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-slate-300 text-[10px]">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Total XP & Rank ML -->
                            <td class="py-3 px-3 text-right">
                                <div class="font-black text-blue-600"><?= number_format($st['xp']) ?> XP</div>
                                <?php $rInfo = get_rank_info($st['xp']); ?>
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold border <?= $rInfo['badge_class'] ?> mt-0.5">
                                    <i class="fa-solid <?= $rInfo['icon'] ?>"></i> <?= $rInfo['name'] ?>
                                </span>
                            </td>

                            <!-- Override Buka Kunci Part -->
                            <td class="py-3 px-3 text-center">
                                <form method="POST" action="" class="inline-flex items-center gap-1">
                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                    <input type="hidden" name="action" value="unlock_part">
                                    <input type="hidden" name="target_user_id" value="<?= $st['id'] ?>">
                                    <select name="target_part" onchange="this.form.submit()" class="text-[11px] font-bold py-1 px-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:border-blue-400 cursor-pointer">
                                        <?php for ($opt = 1; $opt <= 8; $opt++): ?>
                                            <option value="<?= $opt ?>" <?= $st['current_part'] == $opt ? 'selected' : '' ?>>P<?= $opt ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
