<?php
/**
 * Halaman Belajar Interaktif Gaya W3Schools dengan 5 Sub-Part Harian & Live Python Emulator
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

// Cek apakah Part ini sudah terbuka untuk siswa
if (!is_part_unlocked($user['id'], $partNumber)) {
    set_flash('warning', 'Part ' . $partNumber . ' masih terkunci. Harap selesaikan seluruh sub-part pada Part sebelumnya.');
    header('Location: ' . base_url('student/dashboard.php'));
    exit;
}

// Ambil modul
$stmtMod = $db->prepare("SELECT * FROM modules WHERE part_number = ? LIMIT 1");
$stmtMod->execute([$partNumber]);
$module = $stmtMod->fetch();

if (!$module) {
    set_flash('danger', 'Materi tidak ditemukan.');
    header('Location: ' . base_url('student/dashboard.php'));
    exit;
}

// Ambil seluruh 5 sub-topik dalam part ini beserta status pengerjaan
$subtopics = get_module_subtopics($module['id'], $user['id']);

// Tentukan sub-topik aktif
$activeSubCode = trim($_GET['sub'] ?? '');
$targetTopicId = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : 0;
$activeTopic = null;

if (!empty($activeSubCode)) {
    foreach ($subtopics as $st) {
        if ($st['sub_code'] === $activeSubCode) {
            $activeTopic = $st;
            break;
        }
    }
} elseif ($targetTopicId > 0) {
    foreach ($subtopics as $st) {
        if ($st['id'] == $targetTopicId) {
            $activeTopic = $st;
            break;
        }
    }
}

// Jika belum terpilih, default ke sub-topik pertama yang belum selesai, atau sub 1
if (!$activeTopic) {
    foreach ($subtopics as $st) {
        if (!$st['is_completed']) {
            $activeTopic = $st;
            break;
        }
    }
    if (!$activeTopic && !empty($subtopics)) {
        $activeTopic = $subtopics[0];
    }
}

// Tentukan prev & next sub-topik untuk navigasi footer
$currentIndex = 0;
foreach ($subtopics as $idx => $st) {
    if ($st['id'] == $activeTopic['id']) {
        $currentIndex = $idx;
        break;
    }
}
$prevSubtopic = $subtopics[$currentIndex - 1] ?? null;
$nextSubtopic = $subtopics[$currentIndex + 1] ?? null;

// Cek kuis dan tantangan untuk sub-topik ini
$stmtQuiz = $db->prepare("SELECT id FROM quizzes WHERE topic_id = ? LIMIT 1");
$stmtQuiz->execute([$activeTopic['id']]);
$quiz = $stmtQuiz->fetch();

$stmtChal = $db->prepare("SELECT id FROM challenges WHERE topic_id = ? LIMIT 1");
$stmtChal->execute([$activeTopic['id']]);
$challenge = $stmtChal->fetch();

// Hitung total sub-topik yang sudah selesai di part ini
$completedCount = 0;
foreach ($subtopics as $st) {
    if ($st['is_completed']) $completedCount++;
}
$partPercent = count($subtopics) > 0 ? round(($completedCount / count($subtopics)) * 100) : 0;

$pageTitle = 'Part ' . $module['part_number'] . ' - ' . $activeTopic['title'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Top Breadcrumb & Progress -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
            <a href="<?= base_url('student/dashboard.php') ?>" class="hover:text-blue-600"><i class="fa-solid fa-house"></i> Roadmap</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-800 font-bold">Part <?= $module['part_number'] ?>: <?= sanitize($module['title']) ?></span>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-blue-600 font-bold"><?= sanitize($activeTopic['sub_code']) ?></span>
        </div>
        
        <!-- Progress Part Badge -->
        <div class="flex items-center gap-3">
            <div class="text-xs font-bold text-slate-600 flex items-center gap-2">
                <span>Progres Part:</span>
                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg border border-blue-200">
                    <?= $completedCount ?> / <?= count($subtopics) ?> Selesai (<?= $partPercent ?>%)
                </span>
            </div>
            <a href="<?= base_url('student/challenge.php?part=' . $module['part_number'] . '&sub=' . $activeTopic['sub_code']) ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold hover:bg-indigo-100 transition">
                <i class="fa-solid fa-code text-indigo-500"></i> Tantangan Koding
            </a>
            <a href="<?= base_url('student/quiz.php?part=' . $module['part_number'] . '&topic_id=' . $activeTopic['id']) ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold hover:bg-amber-100 transition">
                <i class="fa-solid fa-circle-question text-amber-500"></i> Kuis Sub-Part
            </a>
        </div>
    </div>

    <!-- HORIZONTAL DAY PILLS / SUB-PART NAVIGATION BAR -->
    <div class="mb-6 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
        <div class="mobile-symbol-toolbar gap-2 py-1 px-1">
            <?php foreach ($subtopics as $st): 
                $isActive = ($st['id'] == $activeTopic['id']);
                $isDone = $st['is_completed'];
            ?>
            <a href="<?= base_url('student/learn.php?part=' . $module['part_number'] . '&sub=' . $st['sub_code']) ?>"
                class="flex-shrink-0 px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 border <?= $isActive ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-500/20' : ($isDone ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100') ?>">
                <?php if ($isDone): ?>
                    <i class="fa-solid fa-circle-check <?= $isActive ? 'text-white' : 'text-emerald-500' ?> text-xs"></i>
                <?php else: ?>
                    <span class="w-2 h-2 rounded-full <?= $isActive ? 'bg-yellow-300' : 'bg-slate-300' ?>"></span>
                <?php endif; ?>
                <span><?= sanitize($st['title']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main Grid: Materi (Kiri) & Live Emulator HP (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Kolom Kiri: Penjelasan Materi Teori & Latihan (lg:col-span-6) -->
        <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col justify-between">
            <div class="prose max-w-none text-slate-800 leading-relaxed text-sm">
                <!-- Header Topik Aktif -->
                <div class="pb-4 mb-6 border-b border-slate-100 flex items-start justify-between gap-3">
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md">
                            Sub-Part <?= sanitize($activeTopic['sub_code']) ?> &bull; Part <?= $module['part_number'] ?>
                        </span>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-2 mb-1">
                            <?= sanitize($activeTopic['title']) ?>
                        </h1>
                    </div>
                    <?php if ($activeTopic['is_completed']): ?>
                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-xs font-bold">
                            <i class="fa-solid fa-check"></i> Tuntas
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Konten Materi HTML Lengkap -->
                <div class="space-y-4">
                    <?= $activeTopic['content_html'] ?>
                </div>
            </div>

            <!-- Bagian Bawah: Navigasi Prev/Next & Tombol Ujian -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <?php if ($prevSubtopic): ?>
                        <a href="<?= base_url('student/learn.php?part=' . $module['part_number'] . '&sub=' . $prevSubtopic['sub_code']) ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800">
                            <i class="fa-solid fa-arrow-left"></i> <?= sanitize($prevSubtopic['sub_code']) ?> Sebelumnya
                        </a>
                    <?php endif; ?>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="<?= base_url('student/quiz.php?part=' . $module['part_number'] . '&topic_id=' . $activeTopic['id']) ?>" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-sm transition flex items-center gap-1">
                        <i class="fa-solid fa-circle-question"></i> Kuis (+15 XP)
                    </a>
                    <a href="<?= base_url('student/challenge.php?part=' . $module['part_number'] . '&sub=' . $activeTopic['sub_code']) ?>" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1">
                        <i class="fa-solid fa-code"></i> Tantangan (+<?= $activeTopic['xp_reward'] ?> XP)
                    </a>
                    <?php if ($nextSubtopic): ?>
                        <a href="<?= base_url('student/learn.php?part=' . $module['part_number'] . '&sub=' . $nextSubtopic['sub_code']) ?>" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-1">
                            Lanjut <?= sanitize($nextSubtopic['sub_code']) ?> <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    <?php elseif ($partNumber < 8): ?>
                        <a href="<?= base_url('student/learn.php?part=' . ($partNumber + 1)) ?>" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1">
                            Lanjut Part <?= $partNumber + 1 ?> <i class="fa-solid fa-forward-step"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('student/capstone.php') ?>" class="px-3.5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1">
                            Kumpul Capstone <i class="fa-solid fa-trophy"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Live Python Emulator & Mobile Quick-Symbol Toolbar (lg:col-span-6) -->
        <div class="lg:col-span-6 flex flex-col">
            <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 flex flex-col h-full overflow-hidden">
                
                <!-- Emulator Header & Python Status Indicator -->
                <div class="bg-slate-950 px-4 py-3 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        <span class="text-xs font-mono text-slate-300 font-semibold ml-1">Latihan_<?= str_replace('.', '_', $activeTopic['sub_code']) ?>.py</span>
                    </div>
                    <div id="pyEngineStatus" class="text-[11px] font-mono text-slate-400">
                        <i class="fa-brands fa-python text-yellow-400 mr-1"></i> Python 3 Siap
                    </div>
                </div>

                <!-- Tab Navigasi Khusus Layar HP (Editor vs Hasil) -->
                <div class="md:hidden flex border-b border-slate-800 bg-slate-950">
                    <button type="button" id="tabEditorBtn" onclick="switchMobileTab('editor')" class="flex-1 py-2 text-center text-xs font-bold border-b-2 border-blue-600 text-blue-500 bg-blue-900/20">
                        <i class="fa-solid fa-code mr-1"></i> Ketik Kode
                    </button>
                    <button type="button" id="tabOutputBtn" onclick="switchMobileTab('output')" class="flex-1 py-2 text-center text-xs font-semibold text-slate-400 hover:text-slate-200">
                        <i class="fa-solid fa-terminal mr-1"></i> Terminal Hasil
                    </button>
                </div>

                <!-- STICKY MOBILE QUICK-SYMBOL TOOLBAR -->
                <div class="bg-slate-800/90 p-2 border-b border-slate-700/80 mobile-symbol-toolbar gap-1.5 shadow-inner">
                    <button type="button" onclick="insertSymbol('codeEditor', '    ', 0)" class="symbol-btn" title="Indentasi 4 Spasi">Tab ⇥</button>
                    <button type="button" onclick="insertSymbol('codeEditor', ':', 0)" class="symbol-btn font-bold">:</button>
                    <button type="button" onclick="insertSymbol('codeEditor', '()', 1)" class="symbol-btn font-bold">( )</button>
                    <button type="button" onclick="insertSymbol('codeEditor', '&quot;&quot;', 1)" class="symbol-btn font-bold">&quot; &quot;</button>
                    <button type="button" onclick="insertSymbol('codeEditor', '\'\'', 1)" class="symbol-btn font-bold">&#039; &#039;</button>
                    <button type="button" onclick="insertSymbol('codeEditor', '[]', 1)" class="symbol-btn font-bold">[ ]</button>
                    <button type="button" onclick="insertSymbol('codeEditor', '{}', 1)" class="symbol-btn font-bold">{ }</button>
                    <button type="button" onclick="insertSymbol('codeEditor', ' = ', 0)" class="symbol-btn">=</button>
                    <button type="button" onclick="insertSymbol('codeEditor', ' == ', 0)" class="symbol-btn font-bold text-blue-600">==</button>
                    <button type="button" onclick="insertSymbol('codeEditor', ' + ', 0)" class="symbol-btn">+</button>
                    <button type="button" onclick="insertSymbol('codeEditor', ' - ', 0)" class="symbol-btn">-</button>
                    <button type="button" onclick="insertSymbol('codeEditor', ' * ', 0)" class="symbol-btn">*</button>
                    <button type="button" onclick="insertSymbol('codeEditor', ' / ', 0)" class="symbol-btn">/</button>
                    <button type="button" onclick="insertSymbol('codeEditor', ' % ', 0)" class="symbol-btn">%</button>
                    <button type="button" onclick="insertSymbol('codeEditor', '_', 0)" class="symbol-btn">_</button>
                    <button type="button" onclick="insertSymbol('codeEditor', '# ', 0)" class="symbol-btn text-emerald-600">#</button>
                    <button type="button" onclick="editorUndo('codeEditor')" class="symbol-btn" title="Undo"><i class="fa-solid fa-rotate-left"></i></button>
                    <button type="button" onclick="editorRedo('codeEditor')" class="symbol-btn" title="Redo"><i class="fa-solid fa-rotate-right"></i></button>
                </div>

                <!-- Main Content Panes (Editor & Terminal) -->
                <div class="flex-1 flex flex-col md:grid md:grid-rows-2 h-full min-h-[460px]">
                    <!-- Editor Pane -->
                    <div id="editorPane" class="flex-1 flex flex-col p-3 bg-slate-900 border-b border-slate-800">
                        <!-- Kotak Contoh Kode Acuan (Di atas Editor, Read-Only, Tanpa Tombol Salin) -->
                        <?php if (!empty($activeTopic['starter_code'])): ?>
                        <div class="mb-3 bg-slate-950/90 rounded-xl border border-slate-800 p-3 shadow-inner">
                            <div class="flex items-center justify-between text-xs mb-1.5 pb-1 border-b border-slate-800/80">
                                <span class="font-bold text-amber-400 flex items-center gap-1.5 text-[11px]">
                                    <i class="fa-solid fa-code"></i> Contoh Kode Acuan:
                                </span>
                                <span class="text-[10px] text-slate-400 bg-slate-800/80 px-2 py-0.5 rounded font-mono">
                                    Lihat &amp; Ketik Ulang di Bawah
                                </span>
                            </div>
                            <pre class="text-xs sm:text-sm font-mono text-emerald-300 overflow-x-auto whitespace-pre-wrap leading-relaxed select-none pointer-events-none"><?= htmlspecialchars($activeTopic['starter_code']) ?></pre>
                        </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                            <span class="text-slate-300 font-semibold flex items-center gap-1.5">
                                <i class="fa-solid fa-keyboard text-blue-400"></i> Kotak Latihan Mengetik:
                            </span>
                            <button type="button" onclick="document.getElementById('codeEditor').value = ''; document.getElementById('codeEditor').focus();" class="hover:text-rose-400 text-[11px] text-slate-400 flex items-center gap-1 transition">
                                <i class="fa-solid fa-eraser"></i> Bersihkan Editor
                            </button>
                        </div>
                        <textarea id="codeEditor" spellcheck="false" placeholder="# Ketik ulang contoh kode di atas untuk melatih ketangkasan Anda...&#10;"
                            class="code-editor-area code-font w-full flex-1 p-3 bg-slate-950 text-slate-100 rounded-xl border border-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs sm:text-sm resize-none font-mono min-h-[180px]"
                        ></textarea>
                    </div>

                    <!-- Output Terminal Pane -->
                    <div id="outputPane" class="flex-1 hidden md:flex flex-col p-3 bg-slate-950">
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                            <span class="flex items-center gap-1.5 font-mono text-[11px] text-emerald-400">
                                <i class="fa-solid fa-terminal"></i> Terminal Output
                            </span>
                            <button type="button" onclick="document.getElementById('terminalOutput').innerHTML = '<div class=\'text-slate-500 italic\'>Terminal bersih.</div>';" class="hover:text-slate-300 text-[11px]">
                                <i class="fa-solid fa-trash-can mr-1"></i> Bersihkan
                            </button>
                        </div>
                        <div id="terminalOutput" class="code-terminal flex-1 p-3 bg-black/60 rounded-xl border border-slate-900 text-xs sm:text-sm overflow-y-auto min-h-[160px] font-mono">
                            <div class="text-slate-500 italic">Tekan tombol hijau "Jalankan Kode" di bawah untuk melihat output program.</div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar Bawah (Tombol Run ▶) -->
                <div class="bg-slate-950 px-4 py-3 border-t border-slate-800 flex items-center justify-between">
                    <span class="text-[11px] text-slate-500 hidden sm:inline">
                        <i class="fa-solid fa-circle-info mr-1 text-slate-400"></i> Mendukung <code>input()</code> langsung di HP.
                    </span>
                    <button type="button" onclick="runStudentPythonCode('codeEditor', 'terminalOutput', 'pyEngineStatus')" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-900/30 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-play text-xs text-emerald-200"></i> Jalankan Kode (Run)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pyodide Engine JS -->
<script src="<?= base_url('assets/js/pyodide-engine.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        getPyodide('pyEngineStatus').catch(e => console.log('Wasm readying...', e));
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
