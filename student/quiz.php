<?php
/**
 * Antarmuka Kuis Pemahaman Bertahap (Multi-Soal Step-by-Step)
 * Mendukung 3 Variasi Soal: Konsep, Tebak Output, dan Analisis Bug
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$user = current_user();
$db = get_db();

$partNumber = isset($_GET['part']) ? (int)$_GET['part'] : 1;
if ($partNumber < 1 || $partNumber > 8) $partNumber = 1;

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

// Temukan Topic yang ditarget
if ($targetTopicId > 0) {
    $stmtTop = $db->prepare("SELECT * FROM topics WHERE id = ? AND module_id = ? LIMIT 1");
    $stmtTop->execute([$targetTopicId, $module['id']]);
} elseif (!empty($targetSub)) {
    $stmtTop = $db->prepare("SELECT * FROM topics WHERE module_id = ? AND sub_code = ? LIMIT 1");
    $stmtTop->execute([$module['id'], $targetSub]);
} else {
    $stmtTop = $db->prepare("SELECT * FROM topics WHERE module_id = ? ORDER BY order_index ASC LIMIT 1");
    $stmtTop->execute([$module['id']]);
}
$topic = $stmtTop->fetch();

if (!$topic) {
    set_flash('info', 'Materi topik tidak ditemukan.');
    header('Location: ' . base_url('student/learn.php?part=' . $partNumber));
    exit;
}

// Ambil Seluruh Soal Kuis untuk Topik ini (Minimal 1, normalnya 3 variasi soal)
$stmtQuizzes = $db->prepare("SELECT * FROM quizzes WHERE topic_id = ? ORDER BY id ASC");
$stmtQuizzes->execute([$topic['id']]);
$quizzes = $stmtQuizzes->fetchAll();

if (empty($quizzes)) {
    set_flash('info', 'Soal kuis untuk topik ini belum tersedia.');
    header('Location: ' . base_url('student/learn.php?part=' . $partNumber . '&sub=' . $topic['sub_code']));
    exit;
}

// Cek progres pengerjaan sebelumnya
$isTeacher = ($user['role'] === 'guru');
$stmtProg = $db->prepare("SELECT quiz_score, is_completed FROM student_progress WHERE user_id = ? AND topic_id = ?");
$stmtProg->execute([$user['id'], $topic['id']]);
$prog = $stmtProg->fetch();
$previousScore = $prog ? (int)$prog['quiz_score'] : null;
$hasPassed = ($previousScore !== null && $previousScore >= (defined('PASSING_SCORE_QUIZ') ? PASSING_SCORE_QUIZ : 80));

// Label Variasi Soal Otomatis (Konsep, Tebak Output, Analisis Bug)
$quizTypes = [
    0 => ['label' => 'Konsep & Logika', 'icon' => 'fa-lightbulb', 'color' => 'bg-amber-50 text-amber-700 border-amber-200'],
    1 => ['label' => 'Tebak Output Kode', 'icon' => 'fa-code', 'color' => 'bg-blue-50 text-blue-700 border-blue-200'],
    2 => ['label' => 'Analisis Error & Bug', 'icon' => 'fa-bug', 'color' => 'bg-rose-50 text-rose-700 border-rose-200'],
];

$pageTitle = 'Kuis ' . $topic['sub_code'] . ': ' . $topic['title'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigasi -->
    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-6">
        <a href="<?= base_url('student/dashboard.php') ?>" class="hover:text-blue-600"><i class="fa-solid fa-house"></i> Roadmap</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="<?= base_url('student/learn.php?part=' . $partNumber . '&sub=' . $topic['sub_code']) ?>" class="hover:text-blue-600">Part <?= $partNumber ?> (<?= sanitize($topic['sub_code']) ?>)</a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800 font-bold">Kuis Pemahaman</span>
    </div>

    <!-- Kotak Mode Demo Khusus Guru -->
    <?php if ($isTeacher): ?>
    <div class="mb-6 p-4 bg-amber-50 border border-amber-300 rounded-2xl flex flex-wrap items-center justify-between gap-3 text-xs text-amber-900 shadow-sm">
        <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl bg-amber-200 text-amber-800 flex items-center justify-center font-bold text-sm">
                <i class="fa-solid fa-chalkboard-user"></i>
            </span>
            <div>
                <span class="font-extrabold text-amber-950">Mode Demonstrasi Guru:</span>
                <span class="text-amber-800 ml-1">Kunci jawaban dan penjelasan otomatis terlihat untuk kemudahan demonstrasi materi di depan kelas.</span>
            </div>
        </div>
        <span class="bg-amber-200/90 text-amber-900 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
            Akses Guru
        </span>
    </div>
    <?php endif; ?>

    <!-- Kartu Utama Kuis -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8" id="quizContainer">
        <!-- Header Info Kuis -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-slate-100 gap-3 mb-6">
            <div>
                <span class="text-xs font-black uppercase tracking-wider text-amber-700 bg-amber-50 border border-amber-200/80 px-3 py-1 rounded-xl inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-graduation-cap text-amber-600"></i> Kuis Sub-Part <?= sanitize($topic['sub_code']) ?>
                </span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-2">
                    <?= sanitize($topic['title']) ?>
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-black text-amber-700 bg-amber-50 border border-amber-200 px-3.5 py-2 rounded-2xl flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-bolt text-amber-500"></i> +<?= (int)$topic['xp_reward'] ?> XP
                </span>
                <?php if ($hasPassed): ?>
                <span class="text-xs font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-2xl flex items-center gap-1.5 shadow-sm" title="Sudah Lulus Sebelumnya">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i> Lulus (<?= $previousScore ?>)
                </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stepper Indikator Soal (Step Tracker) -->
        <div class="mb-6" id="stepperSection">
            <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-2">
                <span id="stepCounterText">Soal 1 dari <?= count($quizzes) ?></span>
                <span class="text-[11px] text-slate-400">KKM Kelulusan: <?= defined('PASSING_SCORE_QUIZ') ? PASSING_SCORE_QUIZ : 80 ?>%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden flex">
                <?php foreach ($quizzes as $idx => $q): ?>
                    <div id="stepProgress_<?= $idx ?>" class="h-full flex-1 transition-all duration-300 <?= $idx === 0 ? 'bg-blue-600' : 'bg-slate-200' ?> border-r border-white/50 last:border-0"></div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Wadah Soal-Soal Bertahap -->
        <form id="quizForm" onsubmit="event.preventDefault(); submitAllQuiz();">
            <input type="hidden" id="quizCsrf" value="<?= csrf_token() ?>">
            <input type="hidden" id="quizTopicId" value="<?= (int)$topic['id'] ?>">

            <?php foreach ($quizzes as $idx => $q): 
                $qMeta = $quizTypes[$idx % 3] ?? $quizTypes[0];
            ?>
            <div id="questionStep_<?= $idx ?>" class="quiz-step-pane <?= $idx !== 0 ? 'hidden' : '' ?> space-y-6">
                <!-- Badge Kategori Soal -->
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-xl border <?= $qMeta['color'] ?>">
                        <i class="fa-solid <?= $qMeta['icon'] ?>"></i> <?= $qMeta['label'] ?>
                    </span>
                    <span class="text-xs font-bold text-slate-400">
                        Bobot: <?= (int)$q['xp_reward'] ?> XP
                    </span>
                </div>

                <!-- Teks Pertanyaan -->
                <div class="p-4 sm:p-5 bg-slate-50 border border-slate-200 rounded-2xl">
                    <p class="text-sm sm:text-base font-bold text-slate-800 leading-relaxed">
                        <?= nl2br(sanitize($q['question'])) ?>
                    </p>
                </div>

                <!-- Kunci Jawaban Khusus Guru -->
                <?php if ($isTeacher): ?>
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-key text-amber-600 font-bold"></i>
                        <span>Kunci Jawaban Guru: <strong>Opsi <?= $q['correct_option'] ?></strong></span>
                        <?php if (!empty($q['explanation'])): ?>
                            <span class="text-slate-500 hidden sm:inline">&bull; <?= sanitize($q['explanation']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Pilihan Jawaban A, B, C, D -->
                <div class="space-y-3">
                    <?php 
                    $options = [
                        'A' => $q['option_a'],
                        'B' => $q['option_b'],
                        'C' => $q['option_c'],
                        'D' => $q['option_d'],
                    ];
                    foreach ($options as $key => $optVal): 
                        if (empty($optVal)) continue;
                    ?>
                    <label class="option-label flex items-start gap-3.5 p-3.5 sm:p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-400 hover:bg-blue-50/40 cursor-pointer transition-all duration-200"
                           id="label_<?= $q['id'] ?>_<?= $key ?>"
                           onclick="selectOption(<?= $idx ?>, <?= $q['id'] ?>, '<?= $key ?>')">
                        <input type="radio" name="answer_<?= $q['id'] ?>" value="<?= $key ?>" class="hidden">
                        <div class="option-pill w-8 h-8 rounded-xl bg-slate-100 text-slate-700 font-black text-xs flex items-center justify-center border border-slate-300 flex-shrink-0 transition-colors">
                            <?= $key ?>
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-800 pt-1 leading-snug flex-1">
                            <?= sanitize($optVal) ?>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>

                <!-- Tombol Navigasi Stepper -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                    <?php if ($idx > 0): ?>
                        <button type="button" onclick="goToStep(<?= $idx - 1 ?>)" class="px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-left"></i> Sebelumnya
                        </button>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>

                    <?php if ($idx < count($quizzes) - 1): ?>
                        <button type="button" onclick="goToStep(<?= $idx + 1 ?>)" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition flex items-center gap-1.5">
                            <span>Selanjutnya</span> <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    <?php else: ?>
                        <button type="button" onclick="submitAllQuiz()" id="btnSubmitQuiz" class="px-7 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold text-xs shadow-lg shadow-emerald-500/25 transition flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim Jawaban Kuis</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </form>

        <!-- LAYAR RANGKUMAN HASIL KUIS (Akan Ditampilkan Setelah Submit) -->
        <div id="quizResultScreen" class="hidden space-y-6 pt-2">
            <div id="resultHeroCard" class="p-6 rounded-3xl text-center border relative overflow-hidden">
                <!-- Diisi via JavaScript -->
            </div>

            <!-- Detail Ulasan Jawaban & Pembahasan -->
            <div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-book-open text-blue-600"></i> Pembahasan Soal
                </h3>
                <div id="resultReviewList" class="space-y-3">
                    <!-- Diisi via JavaScript -->
                </div>
            </div>

            <!-- Tombol Aksi Kelanjutan -->
            <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <button type="button" onclick="restartQuiz()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-rotate-left"></i> Ulangi Kuis
                </button>
                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <a href="<?= base_url('student/learn.php?part=' . $partNumber . '&sub=' . $topic['sub_code']) ?>" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition text-center">
                        <i class="fa-solid fa-book mr-1"></i> Materi
                    </a>
                    <a href="<?= base_url('student/challenge.php?part=' . $partNumber . '&sub=' . $topic['sub_code']) ?>" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md shadow-blue-500/25 transition text-center flex items-center justify-center gap-1.5">
                        <span>Tantangan Kode</span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const TOTAL_QUESTIONS = <?= count($quizzes) ?>;
let currentStep = 0;
const userAnswers = {}; // Format: { quiz_id: 'A' }

/**
 * Memilih opsi jawaban dengan efek sorotan visual
 */
function selectOption(stepIndex, quizId, optionKey) {
    userAnswers[quizId] = optionKey;

    // Reset seluruh label di step ini
    const pane = document.getElementById('questionStep_' + stepIndex);
    if (!pane) return;

    pane.querySelectorAll('.option-label').forEach(label => {
        label.classList.remove('border-blue-600', 'bg-blue-50', 'ring-2', 'ring-blue-500/20');
        label.classList.add('border-slate-200');
        const pill = label.querySelector('.option-pill');
        if (pill) {
            pill.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            pill.classList.add('bg-slate-100', 'text-slate-700', 'border-slate-300');
        }
    });

    // Tandai opsi yang dipilih
    const selectedLabel = document.getElementById('label_' + quizId + '_' + optionKey);
    if (selectedLabel) {
        selectedLabel.classList.remove('border-slate-200');
        selectedLabel.classList.add('border-blue-600', 'bg-blue-50', 'ring-2', 'ring-blue-500/20');
        const pill = selectedLabel.querySelector('.option-pill');
        if (pill) {
            pill.classList.remove('bg-slate-100', 'text-slate-700', 'border-slate-300');
            pill.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
        }
    }
}

/**
 * Pindah langkah ke soal tertentu
 */
function goToStep(targetStep) {
    if (targetStep < 0 || targetStep >= TOTAL_QUESTIONS) return;

    // Sembunyikan step lama
    const oldPane = document.getElementById('questionStep_' + currentStep);
    if (oldPane) oldPane.classList.add('hidden');

    // Tampilkan step baru
    currentStep = targetStep;
    const newPane = document.getElementById('questionStep_' + currentStep);
    if (newPane) newPane.classList.remove('hidden');

    // Update Counter Text & Progress Bar
    const counterText = document.getElementById('stepCounterText');
    if (counterText) {
        counterText.innerText = 'Soal ' + (currentStep + 1) + ' dari ' + TOTAL_QUESTIONS;
    }

    for (let i = 0; i < TOTAL_QUESTIONS; i++) {
        const bar = document.getElementById('stepProgress_' + i);
        if (bar) {
            if (i <= currentStep) {
                bar.classList.remove('bg-slate-200');
                bar.classList.add('bg-blue-600');
            } else {
                bar.classList.remove('bg-blue-600');
                bar.classList.add('bg-slate-200');
            }
        }
    }
}

/**
 * Mengirim seluruh jawaban ke API
 */
async function submitAllQuiz() {
    // Validasi apakah seluruh soal sudah dijawab
    const answeredCount = Object.keys(userAnswers).length;
    if (answeredCount < TOTAL_QUESTIONS) {
        if (!confirm('Anda baru menjawab ' + answeredCount + ' dari ' + TOTAL_QUESTIONS + ' soal.\n\nApakah Anda yakin ingin mengirim sekarang? Soal yang belum dijawab akan dihitung salah.')) {
            return;
        }
    }

    const btnSubmit = document.getElementById('btnSubmitQuiz');
    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Memeriksa Jawaban...';
    }

    const csrf = document.getElementById('quizCsrf').value;
    const topicId = document.getElementById('quizTopicId').value;

    try {
        const response = await fetch('<?= base_url('api/submit_quiz.php') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                csrf_token: csrf,
                topic_id: parseInt(topicId),
                answers: userAnswers
            })
        });

        const data = await response.json();
        renderQuizResults(data);
    } catch (err) {
        alert('Gagal mengirim jawaban kuis. Harap periksa koneksi internet Anda.');
        if (btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim Jawaban Kuis';
        }
    }
}

/**
 * Menampilkan hasil penilaian kuis dan pembahasan
 */
function renderQuizResults(data) {
    document.getElementById('quizForm').classList.add('hidden');
    document.getElementById('stepperSection').classList.add('hidden');
    const resultScreen = document.getElementById('quizResultScreen');
    resultScreen.classList.remove('hidden');

    const hero = document.getElementById('resultHeroCard');
    const passed = data.passed;
    const score = data.score || 0;
    const xp = data.xp_awarded || 0;

    if (passed) {
        hero.className = 'p-6 sm:p-8 rounded-3xl text-center border-2 border-emerald-400 bg-gradient-to-b from-emerald-50 to-white text-emerald-950 shadow-lg';
        hero.innerHTML = `
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-3 shadow-md">
                <i class="fa-solid fa-trophy text-amber-500"></i>
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-600 text-white text-xs font-black uppercase tracking-wider mb-2">
                LUAR BIASA &bull; LULUS KUIS
            </span>
            <h2 class="text-3xl font-black text-slate-900">Skor Anda: ${score} / 100</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-md mx-auto">
                Selamat! Anda menjawab ${data.correct_count} dari ${data.total_questions} soal dengan tepat. Batas KKM (${data.passing_score}%) berhasil dilampaui!
            </p>
            ${xp > 0 ? `
            <div class="mt-4 inline-flex items-center gap-1.5 bg-amber-400 text-slate-950 font-black text-xs px-4 py-1.5 rounded-full shadow-md">
                <i class="fa-solid fa-bolt"></i> +${xp} XP DITAMBAHKAN KE AKUN ANDA
            </div>` : ''}
        `;
    } else {
        hero.className = 'p-6 sm:p-8 rounded-3xl text-center border-2 border-rose-300 bg-gradient-to-b from-rose-50 to-white text-rose-950 shadow-lg';
        hero.innerHTML = `
            <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-3xl mx-auto mb-3 shadow-md">
                <i class="fa-solid fa-shield-halved text-rose-500"></i>
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-rose-600 text-white text-xs font-black uppercase tracking-wider mb-2">
                BELUM MENCAPAI KKM
            </span>
            <h2 class="text-3xl font-black text-slate-900">Skor Anda: ${score} / 100</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-md mx-auto">
                Anda menjawab ${data.correct_count} dari ${data.total_questions} soal dengan benar. Minimal skor untuk lulus adalah ${data.passing_score}%. Silakan baca pembahasan di bawah dan coba lagi!
            </p>
        `;
    }

    // Render Ulasan Tiap Soal
    const reviewList = document.getElementById('resultReviewList');
    reviewList.innerHTML = '';

    (data.results || []).forEach((res, i) => {
        const item = document.createElement('div');
        item.className = 'p-4 rounded-2xl border ' + (res.is_correct ? 'bg-emerald-50/60 border-emerald-200' : 'bg-rose-50/60 border-rose-200');
        item.innerHTML = `
            <div class="flex items-start justify-between gap-2 mb-2">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg ${res.is_correct ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'} flex items-center justify-center text-xs font-black">
                        ${i + 1}
                    </span>
                    <span class="text-xs font-bold ${res.is_correct ? 'text-emerald-700' : 'text-rose-700'}">
                        ${res.is_correct ? 'Jawaban Anda Tepat' : 'Jawaban Anda Belum Tepat'}
                    </span>
                </div>
                <span class="text-xs font-bold text-slate-600">
                    Dipilih: <strong>${res.user_answer || '-'}</strong> | Kunci: <strong>${res.correct_option}</strong>
                </span>
            </div>
            <p class="text-xs sm:text-sm font-semibold text-slate-800 mb-2">${res.question}</p>
            ${res.explanation ? `
            <div class="text-xs text-slate-600 bg-white/80 p-3 rounded-xl border border-slate-200/80 leading-relaxed">
                <strong>Pembahasan:</strong> ${res.explanation}
            </div>` : ''}
        `;
        reviewList.appendChild(item);
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/**
 * Mengulang kembali kuis
 */
function restartQuiz() {
    for (let k in userAnswers) delete userAnswers[k];
    document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
    document.querySelectorAll('.option-label').forEach(label => {
        label.classList.remove('border-blue-600', 'bg-blue-50', 'ring-2', 'ring-blue-500/20');
        label.classList.add('border-slate-200');
        const pill = label.querySelector('.option-pill');
        if (pill) {
            pill.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            pill.classList.add('bg-slate-100', 'text-slate-700', 'border-slate-300');
        }
    });

    document.getElementById('quizResultScreen').classList.add('hidden');
    document.getElementById('quizForm').classList.remove('hidden');
    document.getElementById('stepperSection').classList.remove('hidden');
    const btnSubmit = document.getElementById('btnSubmitQuiz');
    if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim Jawaban Kuis';
    }
    goToStep(0);
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
