<?php
/**
 * Halaman Kuis Interaktif Pilihan Ganda (per Sub-Part)
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

$partNumber = isset($_GET['part']) ? (int)$_GET['part'] : 1;
if ($partNumber < 1) $partNumber = 1;
if ($partNumber > 8) $partNumber = 8;

if (!is_part_unlocked($user['id'], $partNumber)) {
    set_flash('warning', 'Part ' . $partNumber . ' masih terkunci.');
    header('Location: ' . base_url('student/dashboard.php'));
    exit;
}

$stmtMod = $db->prepare("SELECT * FROM modules WHERE part_number = ? LIMIT 1");
$stmtMod->execute([$partNumber]);
$module = $stmtMod->fetch();

$targetTopicId = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : 0;
$targetSub = trim($_GET['sub'] ?? '');

if ($targetTopicId > 0) {
    $stmtQuiz = $db->prepare("
        SELECT q.*, t.sub_code, t.title as topic_title
        FROM quizzes q
        JOIN topics t ON t.id = q.topic_id
        WHERE q.topic_id = ?
        LIMIT 1
    ");
    $stmtQuiz->execute([$targetTopicId]);
} elseif (!empty($targetSub)) {
    $stmtQuiz = $db->prepare("
        SELECT q.*, t.sub_code, t.title as topic_title
        FROM quizzes q
        JOIN topics t ON t.id = q.topic_id
        WHERE t.module_id = ? AND t.sub_code = ?
        LIMIT 1
    ");
    $stmtQuiz->execute([$module['id'], $targetSub]);
} else {
    $stmtQuiz = $db->prepare("
        SELECT q.*, t.sub_code, t.title as topic_title
        FROM quizzes q
        JOIN topics t ON t.id = q.topic_id
        WHERE t.module_id = ?
        ORDER BY t.order_index ASC
        LIMIT 1
    ");
    $stmtQuiz->execute([$module['id']]);
}
$quiz = $stmtQuiz->fetch();

if (!$quiz) {
    set_flash('info', 'Kuis belum tersedia.');
    header('Location: ' . base_url('student/learn.php?part=' . $partNumber));
    exit;
}

// Cek apakah sudah pernah dijawab (Guru selalu diizinkan menjawab berulang kali)
$isTeacher = ($user['role'] === 'guru');
$stmtProg = $db->prepare("SELECT quiz_score FROM student_progress WHERE user_id = ? AND topic_id = ?");
$stmtProg->execute([$user['id'], $quiz['topic_id']]);
$prog = $stmtProg->fetch();
$hasAnswered = !$isTeacher && $prog && $prog['quiz_score'] > 0;

$feedback = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $selected = strtoupper(trim($_POST['answer'] ?? ''));
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $feedback = ['type' => 'danger', 'msg' => 'Sesi kedaluwarsa.'];
    } elseif (empty($selected)) {
        $feedback = ['type' => 'warning', 'msg' => 'Silakan pilih salah satu opsi jawaban.'];
    } else {
        if ($selected === $quiz['correct_option']) {
            if (!$isTeacher) {
                if (!$hasAnswered) {
                    add_user_xp($user['id'], $quiz['xp_reward']);
                    
                    $upsert = $db->prepare("
                        INSERT INTO student_progress (user_id, topic_id, quiz_score, is_completed)
                        VALUES (?, ?, 100, 1)
                        ON DUPLICATE KEY UPDATE quiz_score = 100, is_completed = 1
                    ");
                    $upsert->execute([$user['id'], $quiz['topic_id']]);

                    // Periksa apakah seluruh sub-part di modul ini sudah komplit, jika ya berikan badge
                    $stmtCheckAll = $db->prepare("
                        SELECT COUNT(t.id) as total, SUM(CASE WHEN sp.is_completed = 1 THEN 1 ELSE 0 END) as done
                        FROM topics t
                        LEFT JOIN student_progress sp ON sp.topic_id = t.id AND sp.user_id = ?
                        WHERE t.module_id = ?
                    ");
                    $stmtCheckAll->execute([$user['id'], $module['id']]);
                    $chk = $stmtCheckAll->fetch();
                    if ($chk && $chk['total'] > 0 && $chk['done'] >= $chk['total']) {
                        award_badge($user['id'], $module['badge_name'], $module['badge_icon']);
                    }
                }
                $hasAnswered = true;
                $msg = 'Jawaban Anda TEPAT! Selamat, Anda mendapatkan +' . $quiz['xp_reward'] . ' XP.';
            } else {
                $msg = 'Jawaban Anda TEPAT! (Mode Demo Guru: Kunci Opsi ' . $quiz['correct_option'] . ')';
            }
            $feedback = [
                'type' => 'success',
                'msg' => $msg,
                'explanation' => $quiz['explanation']
            ];
        } else {
            $feedback = [
                'type' => 'danger',
                'msg' => $isTeacher 
                    ? 'Jawaban belum tepat (Dipilih: ' . $selected . '). Kunci Jawaban: Opsi ' . $quiz['correct_option'] . '.' 
                    : 'Jawaban Anda belum tepat. Silakan pelajari kembali materi dan coba lagi!',
                'explanation' => $isTeacher ? $quiz['explanation'] : null
            ];
        }
    }
}

$pageTitle = 'Kuis ' . $quiz['sub_code'] . ': ' . $quiz['topic_title'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-6">
        <a href="<?= base_url('student/dashboard.php') ?>" class="hover:text-blue-600"><i class="fa-solid fa-house"></i> Roadmap</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="<?= base_url('student/learn.php?part=' . $partNumber . '&sub=' . $quiz['sub_code']) ?>" class="hover:text-blue-600">Part <?= $partNumber ?> (<?= sanitize($quiz['sub_code']) ?>)</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800 font-bold">Kuis Pemahaman</span>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <!-- Header Kuis -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-wider text-amber-600 bg-amber-50 px-2.5 py-1 rounded-md">
                    Kuis Sub-Part <?= sanitize($quiz['sub_code']) ?>
                </span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-2">
                    <?= sanitize($quiz['topic_title']) ?>
                </h1>
            </div>
            <div class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                <i class="fa-solid fa-bolt text-amber-500"></i> +<?= $quiz['xp_reward'] ?> XP
            </div>
        </div>

        <?php if ($feedback): ?>
            <div class="mb-6 p-4 rounded-2xl text-sm font-medium border <?= $feedback['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-rose-50 border-rose-200 text-rose-800' ?>">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid <?= $feedback['type'] === 'success' ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-rose-600' ?> text-lg"></i>
                    <span><?= sanitize($feedback['msg']) ?></span>
                </div>
                <?php if (!empty($feedback['explanation'])): ?>
                    <div class="mt-2 text-xs text-slate-600 bg-white/70 p-3 rounded-xl border border-slate-200">
                        <strong>Penjelasan:</strong> <?= sanitize($feedback['explanation']) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($isTeacher): ?>
        <!-- Kotak Kunci Jawaban Khusus Demonstrasi Guru -->
        <div class="mb-6 p-4 bg-amber-50 border border-amber-300 rounded-2xl flex flex-wrap items-center justify-between gap-3 text-xs text-amber-900 shadow-sm">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-lg bg-amber-200 text-amber-800 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-key"></i>
                </span>
                <div>
                    <span class="font-extrabold text-amber-950">Kunci Jawaban:</span>
                    <span class="bg-amber-600 text-white font-black px-2 py-0.5 rounded text-xs ml-1">Opsi <?= $quiz['correct_option'] ?></span>
                    <?php if (!empty($quiz['explanation'])): ?>
                        <span class="text-amber-800 ml-2 hidden sm:inline">&bull; <?= sanitize($quiz['explanation']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <span class="bg-amber-200/90 text-amber-900 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                Khusus Guru
            </span>
        </div>
        <?php endif; ?>

        <!-- Pertanyaan -->
        <div class="text-base sm:text-lg font-bold text-slate-800 mb-6 leading-relaxed">
            <?= sanitize($quiz['question']) ?>
        </div>

        <!-- Form Opsi Pilihan Ganda -->
        <form method="POST" action="" class="space-y-3">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <?php
            $options = [
                'A' => $quiz['option_a'],
                'B' => $quiz['option_b'],
                'C' => $quiz['option_c'],
                'D' => $quiz['option_d'],
            ];
            foreach ($options as $key => $label):
            ?>
            <label class="flex items-center p-4 rounded-2xl border border-slate-200 hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer transition">
                <input type="radio" name="answer" value="<?= $key ?>" required class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                <span class="ml-3 font-bold text-slate-700 text-sm"><?= $key ?>.</span>
                <span class="ml-2 text-sm text-slate-800"><?= sanitize($label) ?></span>
            </label>
            <?php endforeach; ?>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="<?= base_url('student/learn.php?part=' . $partNumber . '&sub=' . $quiz['sub_code']) ?>" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Materi
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm shadow-md transition flex items-center gap-2">
                    Kirim Jawaban <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </div>
        </form>

        <?php if ($hasAnswered): ?>
        <div class="mt-8 p-4 bg-emerald-50 rounded-2xl border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-xs text-emerald-800 font-semibold">
                Luar biasa! Lanjutkan ke tantangan koding sub-part ini.
            </div>
            <a href="<?= base_url('student/challenge.php?part=' . $partNumber . '&sub=' . $quiz['sub_code']) ?>" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                <i class="fa-solid fa-code"></i> Kerjakan Tantangan &rarr;
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
