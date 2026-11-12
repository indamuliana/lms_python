<?php
/**
 * Review & Penilaian Proyek Akhir (Capstone) oleh Guru
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$user = current_user();
$db = get_db();

$filterStatus = trim($_GET['status'] ?? '');
$targetStudentId = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

// Handle Penilaian Guru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'grade_capstone') {
    if (verify_csrf($_POST['csrf_token'] ?? '')) {
        $subId = (int)$_POST['submission_id'];
        $score = (int)$_POST['teacher_score'];
        $feedback = trim($_POST['teacher_feedback'] ?? '');
        $status = $_POST['status'] === 'approved' ? 'approved' : 'revision';

        $stmtSub = $db->prepare("SELECT user_id FROM capstone_submissions WHERE id = ?");
        $stmtSub->execute([$subId]);
        $sub = $stmtSub->fetch();

        if ($sub) {
            $stmtUp = $db->prepare("
                UPDATE capstone_submissions 
                SET teacher_score = ?, teacher_feedback = ?, status = ?, reviewed_at = NOW()
                WHERE id = ?
            ");
            $stmtUp->execute([$score, $feedback, $status, $subId]);

            // Jika approved, beri XP kelulusan & Lencana Kelulusan
            if ($status === 'approved') {
                add_user_xp($sub['user_id'], 200);
                award_badge($sub['user_id'], 'Python Junior Developer', 'fa-trophy');
            }

            set_flash('success', 'Penilaian dan catatan feedback untuk siswa berhasil disimpan.');
        }
    }
    header('Location: ' . base_url('teacher/capstone-review.php'));
    exit;
}

// Query Submissions
$where = ["1=1"];
$params = [];

if (!empty($filterStatus)) {
    $where[] = "cs.status = ?";
    $params[] = $filterStatus;
}
if ($targetStudentId > 0) {
    $where[] = "cs.user_id = ?";
    $params[] = $targetStudentId;
}

$whereSql = implode(' AND ', $where);
$stmtSubs = $db->prepare("
    SELECT cs.*, u.full_name, u.nis, u.rombel, u.rayon
    FROM capstone_submissions cs
    JOIN users u ON u.id = cs.user_id
    WHERE {$whereSql}
    ORDER BY CASE WHEN cs.status = 'pending' THEN 0 ELSE 1 END, cs.submitted_at DESC
");
$stmtSubs->execute($params);
$submissions = $stmtSubs->fetchAll();

$pageTitle = 'Review Capstone Siswa';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1">
                <a href="<?= base_url('teacher/dashboard.php') ?>" class="hover:text-blue-600">Dashboard Guru</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-800 font-bold">Review Capstone</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Review Proyek Akhir (Capstone)</h1>
            <p class="text-xs sm:text-sm text-slate-500">Evaluasi kode program siswa, berikan nilai, dan tulis feedback bimbingan.</p>
        </div>

        <!-- Filter Status Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl text-xs font-bold">
            <a href="<?= base_url('teacher/capstone-review.php') ?>" class="px-3 py-1.5 rounded-xl transition <?= empty($filterStatus) ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">Semua</a>
            <a href="<?= base_url('teacher/capstone-review.php?status=pending') ?>" class="px-3 py-1.5 rounded-xl transition <?= $filterStatus === 'pending' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">Menunggu Review</a>
            <a href="<?= base_url('teacher/capstone-review.php?status=approved') ?>" class="px-3 py-1.5 rounded-xl transition <?= $filterStatus === 'approved' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">Disetujui</a>
        </div>
    </div>

    <?php if (empty($submissions)): ?>
        <div class="p-16 text-center bg-white rounded-3xl border border-slate-200 shadow-sm text-slate-400 text-xs sm:text-sm">
            <i class="fa-solid fa-clipboard-check text-4xl text-slate-300 mb-3 block"></i>
            Tidak ada proyek akhir yang perlu dinilai pada filter ini.
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($submissions as $sub): ?>
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-md">
                            <?= strtoupper(substr($sub['full_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-slate-900"><?= sanitize($sub['full_name']) ?></h3>
                                <span class="bg-purple-100 text-purple-800 text-[10px] font-bold px-2 py-0.5 rounded-full capitalize">
                                    <?= sanitize($sub['track_choice']) ?>
                                </span>
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">
                                NIS: <?= sanitize($sub['nis'] ?? '-') ?> &bull; Rombel: <?= sanitize($sub['rombel'] ?? '-') ?> &bull; Dikirim: <?= date('d M Y, H:i', strtotime($sub['submitted_at'])) ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <?php if ($sub['status'] === 'approved'): ?>
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-extrabold">
                                <i class="fa-solid fa-circle-check mr-1"></i> Nilai: <?= $sub['teacher_score'] ?> / 100
                            </span>
                        <?php elseif ($sub['status'] === 'pending'): ?>
                            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">
                                <i class="fa-solid fa-clock mr-1"></i> Perlu Dinilai
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-xs font-bold">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> Revisi
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Catatan Siswa Jika Ada -->
                <?php if (!empty($sub['notes'])): ?>
                <div class="mb-4 p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-700">
                    <span class="font-bold text-slate-800">Catatan dari Siswa:</span> <?= nl2br(sanitize($sub['notes'])) ?>
                </div>
                <?php endif; ?>

                <!-- Tampilan Kode Sumber Python Siswa -->
                <div class="mb-5 bg-slate-950 rounded-2xl border border-slate-800 p-4 overflow-x-auto text-xs font-mono text-emerald-400 max-h-[300px]">
                    <div class="text-[11px] text-slate-500 pb-2 border-b border-slate-800 mb-2 font-sans flex items-center justify-between">
                        <span>Kode Program Python Siswa:</span>
                        <span class="text-slate-400"><?= count(explode("\n", $sub['source_code'])) ?> baris</span>
                    </div>
                    <pre class="whitespace-pre-wrap"><?= htmlspecialchars($sub['source_code']) ?></pre>
                </div>

                <!-- Form Penilaian Guru -->
                <form method="POST" action="" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="grade_capstone">
                    <input type="hidden" name="submission_id" value="<?= $sub['id'] ?>">

                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Nilai (0-100)</label>
                        <input type="number" name="teacher_score" min="0" max="100" required value="<?= $sub['teacher_score'] ?? 85 ?>"
                            class="w-full text-xs font-black bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Keputusan Status</label>
                        <select name="status" class="w-full text-xs font-bold bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="approved" <?= $sub['status'] === 'approved' ? 'selected' : '' ?>>Disetujui (Lulus Capstone)</option>
                            <option value="revision" <?= $sub['status'] === 'revision' ? 'selected' : '' ?>>Minta Revisi</option>
                        </select>
                    </div>

                    <div class="md:col-span-5">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Feedback / Catatan Evaluasi</label>
                        <input type="text" name="teacher_feedback" value="<?= sanitize($sub['teacher_feedback'] ?? '') ?>" placeholder="Komentar untuk perbaikan atau pujian..."
                            class="w-full text-xs bg-white border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="w-full py-2 px-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-floppy-disk text-[11px]"></i> Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
