<?php
/**
 * Halaman Tantangan Koding dengan 5 Tingkat Tantangan per Part (Auto-Grader)
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
    set_flash('warning', 'Part ' . $partNumber . ' masih terkunci. Harap selesaikan seluruh sub-part pada Part sebelumnya.');
    header('Location: ' . base_url('student/dashboard.php'));
    exit;
}

$stmtMod = $db->prepare("SELECT * FROM modules WHERE part_number = ? LIMIT 1");
$stmtMod->execute([$partNumber]);
$module = $stmtMod->fetch();

// Ambil semua tantangan dalam part ini
$stmtChals = $db->prepare("
    SELECT c.*, t.sub_code, t.title as topic_title, t.order_index,
           COALESCE(sp.challenge_completed, 0) as is_completed,
           COALESCE(sp.hint_used, 0) as hint_used,
           sp.last_submitted_code
    FROM challenges c
    JOIN topics t ON t.id = c.topic_id
    LEFT JOIN student_progress sp ON sp.topic_id = t.id AND sp.user_id = ?
    WHERE t.module_id = ?
    ORDER BY t.order_index ASC
");
$stmtChals->execute([$user['id'], $module['id']]);
$challengesList = $stmtChals->fetchAll();

// Tentukan tantangan aktif
$activeSubCode = trim($_GET['sub'] ?? '');
$targetChalId = isset($_GET['challenge_id']) ? (int)$_GET['challenge_id'] : 0;
$activeChallenge = null;

if (!empty($activeSubCode)) {
    foreach ($challengesList as $ch) {
        if ($ch['sub_code'] === $activeSubCode) {
            $activeChallenge = $ch;
            break;
        }
    }
} elseif ($targetChalId > 0) {
    foreach ($challengesList as $ch) {
        if ($ch['id'] == $targetChalId) {
            $activeChallenge = $ch;
            break;
        }
    }
}

// Default: pilih tantangan pertama yang belum selesai, atau tantangan pertama
if (!$activeChallenge) {
    foreach ($challengesList as $ch) {
        if (!$ch['is_completed']) {
            $activeChallenge = $ch;
            break;
        }
    }
    if (!$activeChallenge && !empty($challengesList)) {
        $activeChallenge = $challengesList[0];
    }
}

$initialCode = (!empty($activeChallenge['last_submitted_code'])) ? $activeChallenge['last_submitted_code'] : $activeChallenge['starter_code'];

$isTeacher = ($user['role'] === 'guru');
$baseXp = (int)$activeChallenge['xp_reward'];
$hintUsed = !empty($activeChallenge['hint_used']);
$currentReward = ($hintUsed && !$isTeacher) ? max(0, $baseXp - 3) : $baseXp;

$pageTitle = 'Tantangan ' . $activeChallenge['sub_code'] . ': ' . $activeChallenge['title'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Top Breadcrumb -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
            <a href="<?= base_url('student/dashboard.php') ?>" class="hover:text-blue-600"><i class="fa-solid fa-house"></i> Roadmap</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="<?= base_url('student/learn.php?part=' . $partNumber) ?>" class="hover:text-blue-600">Part <?= $partNumber ?></a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-800 font-bold"><?= sanitize($activeChallenge['sub_code']) ?>: <?= sanitize($activeChallenge['title']) ?></span>
        </div>
        <a href="<?= base_url('student/learn.php?part=' . $partNumber . '&sub=' . $activeChallenge['sub_code']) ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
            <i class="fa-solid fa-book-open text-blue-500"></i> Buka Materi Sub-Part
        </a>
    </div>

    <!-- DAY TABS: 5 TANTANGAN KODING DALAM PART INI -->
    <div class="mb-6 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
        <div class="mobile-symbol-toolbar gap-2 py-1 px-1">
            <?php foreach ($challengesList as $ch): 
                $isActive = ($ch['id'] == $activeChallenge['id']);
                $isDone = $ch['is_completed'];
                $isBoss = ($ch['order_index'] == 5);
            ?>
            <a href="<?= base_url('student/challenge.php?part=' . $module['part_number'] . '&sub=' . $ch['sub_code']) ?>"
                class="flex-shrink-0 px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 border <?= $isActive ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-500/20' : ($isDone ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100') ?>">
                <?php if ($isDone): ?>
                    <i class="fa-solid fa-circle-check <?= $isActive ? 'text-white' : 'text-emerald-500' ?> text-xs"></i>
                <?php elseif ($isBoss): ?>
                    <i class="fa-solid fa-crown <?= $isActive ? 'text-yellow-300' : 'text-amber-500' ?> text-xs"></i>
                <?php else: ?>
                    <span class="w-2 h-2 rounded-full <?= $isActive ? 'bg-yellow-300' : 'bg-slate-300' ?>"></span>
                <?php endif; ?>
                <span>Tantangan <?= sanitize($ch['sub_code']) ?> <?= $isBoss ? '(Boss)' : '' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Kolom Kiri: Instruksi Tantangan (lg:col-span-5) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">
                        Tantangan <?= sanitize($activeChallenge['sub_code']) ?>
                    </span>
                    <span id="rewardPill" class="text-xs font-bold text-amber-600 flex items-center gap-1.5 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                        <i class="fa-solid fa-bolt text-amber-500"></i> +<span id="rewardXpVal"><?= $currentReward ?></span> XP
                        <?php if ($hintUsed && !$isTeacher): ?>
                            <span class="text-[10px] text-rose-700 bg-rose-100/90 px-1.5 py-0.2 rounded font-black">-3 Hint</span>
                        <?php endif; ?>
                    </span>
                </div>

                <h1 class="text-lg sm:text-xl font-black text-slate-900 mb-3 leading-snug">
                    <?= sanitize($activeChallenge['title']) ?>
                </h1>

                <div class="text-sm text-slate-700 leading-relaxed mb-4 whitespace-pre-line">
                    <?= sanitize($activeChallenge['instructions']) ?>
                </div>

                <?php if ($isTeacher): 
                    $decodedDemoCases = json_decode($activeChallenge['test_cases_json'], true);
                ?>
                <div class="p-4 bg-amber-50/90 border border-amber-300 rounded-2xl text-xs text-amber-950 mb-5 shadow-sm">
                    <div class="font-black flex flex-wrap items-center justify-between gap-2 mb-3 text-amber-900">
                        <span class="flex items-center gap-2 text-xs sm:text-sm">
                            <i class="fa-solid fa-key text-amber-600"></i> Kunci Jawaban &amp; Kriteria Auto-Grader Guru
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="bg-amber-200 text-amber-900 text-[10px] font-black uppercase px-2 py-0.5 rounded-full">Mode Guru</span>
                            <a href="<?= base_url('teacher/topic-edit.php?id=' . (int)$activeChallenge['topic_id'] . '&tab=tantangan') ?>" target="_blank" class="px-2.5 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-[11px] shadow-xs transition inline-flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Tantangan
                            </a>
                        </div>
                    </div>

                    <?php if (is_array($decodedDemoCases) && !empty($decodedDemoCases)): ?>
                        <div class="space-y-3">
                            <?php foreach ($decodedDemoCases as $cIdx => $demoTc): ?>
                            <div class="bg-white p-3 rounded-xl border border-amber-200 shadow-xs">
                                <div class="flex items-center justify-between mb-1.5 font-bold text-slate-800 text-[11px]">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-vial-circle-check text-indigo-500"></i>
                                        <?= htmlspecialchars($demoTc['name'] ?? "Pengujian #" . ($cIdx + 1)) ?>
                                    </span>
                                    <?php if (!empty($demoTc['inputs'])): ?>
                                        <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono text-[10px]">
                                            Input: <?= htmlspecialchars(implode(', ', (array)$demoTc['inputs'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if (isset($demoTc['expected_output'])): ?>
                                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Output yang Diharapkan (Terminal):</div>
                                    <pre class="bg-slate-900 text-emerald-400 font-mono text-xs p-3 rounded-xl overflow-x-auto whitespace-pre-wrap leading-relaxed border border-slate-800 shadow-inner"><?= htmlspecialchars($demoTc['expected_output']) ?></pre>
                                <?php elseif (isset($demoTc['assertion'])): ?>
                                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Assertion:</div>
                                    <pre class="bg-slate-900 text-amber-300 font-mono text-xs p-2 rounded-lg border border-slate-800"><?= htmlspecialchars($demoTc['assertion']) ?></pre>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <pre class="font-mono text-[11px] bg-white p-2.5 rounded-xl border border-amber-200 text-slate-800 overflow-x-auto whitespace-pre-wrap"><?= htmlspecialchars($activeChallenge['test_cases_json']) ?></pre>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($activeChallenge['hint'])): ?>
                <div id="hintBoxContainer">
                    <?php if ($isTeacher || $hintUsed): ?>
                    <!-- Petunjuk Terbuka -->
                    <div class="p-3.5 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900">
                        <div class="font-bold flex items-center justify-between mb-1.5 text-blue-800">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-lightbulb text-amber-500"></i> Petunjuk:</span>
                            <?php if (!$isTeacher): ?>
                                <span class="text-[10px] font-black text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">
                                    Hadiah Dipotong 3 XP (<?= $currentReward ?> XP)
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="text-slate-800 leading-relaxed"><?= sanitize($activeChallenge['hint']) ?></div>
                    </div>
                    <?php else: ?>
                    <!-- Petunjuk Terkunci (Butuh Konfirmasi Buka & Potong 3 XP Hadiah) -->
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-extrabold text-slate-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-lightbulb text-amber-500"></i> Butuh Bantuan Petunjuk?
                            </span>
                            <span class="text-[10px] font-black text-amber-700 bg-amber-100 px-2.5 py-0.5 rounded-full border border-amber-200">
                                Penalti -3 XP Hadiah
                            </span>
                        </div>
                        <p class="text-slate-500 text-[11px] mb-3 leading-relaxed">
                            Membuka petunjuk akan memotong hadiah penyelesaian tantangan ini sebesar <strong>3 XP</strong> (dari <?= $baseXp ?> XP menjadi <strong><?= max(0, $baseXp - 3) ?> XP</strong>).
                        </p>
                        <button type="button" onclick="openHintModal()" class="w-full sm:w-auto px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-key text-[11px]"></i> Buka Petunjuk (-3 XP Hadiah)
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Panel Hasil Pengujian Test Cases -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-vial-circle-check text-blue-500"></i> Status Test Cases
                </h3>
                <div id="testCaseResults">
                    <div class="p-4 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-center text-xs text-slate-400">
                        Ketik kode solusi Anda di editor sebelah kanan, lalu tekan tombol <strong>"Uji Kode Saya"</strong>.
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Editor Solusi & Sticky Quick Mobile Toolbar (lg:col-span-7) -->
        <div class="lg:col-span-7 flex flex-col">
            <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 flex flex-col h-full overflow-hidden">
                <!-- Bar Atas Editor -->
                <div class="bg-slate-950 px-4 py-3 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        <span class="text-xs font-mono text-slate-300 font-semibold ml-1">solusi_<?= str_replace('.', '_', $activeChallenge['sub_code']) ?>.py</span>
                    </div>
                    <div id="challengeEngineStatus" class="text-[11px] font-mono text-slate-400">
                        <i class="fa-brands fa-python text-yellow-400 mr-1"></i> Pyodide Ready
                    </div>
                </div>

                <!-- STICKY MOBILE QUICK-SYMBOL TOOLBAR -->
                <div class="bg-slate-800/90 p-2 border-b border-slate-700/80 mobile-symbol-toolbar gap-1.5">
                    <button type="button" onclick="insertSymbol('challengeEditor', '    ', 0)" class="symbol-btn">Tab ⇥</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', ':', 0)" class="symbol-btn font-bold">:</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', '()', 1)" class="symbol-btn font-bold">( )</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', '&quot;&quot;', 1)" class="symbol-btn font-bold">&quot; &quot;</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', '\'\'', 1)" class="symbol-btn font-bold">&#039; &#039;</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', '[]', 1)" class="symbol-btn font-bold">[ ]</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', '{}', 1)" class="symbol-btn font-bold">{ }</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', ' = ', 0)" class="symbol-btn">=</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', ' == ', 0)" class="symbol-btn font-bold text-blue-600">==</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', ' + ', 0)" class="symbol-btn">+</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', ' - ', 0)" class="symbol-btn">-</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', ' * ', 0)" class="symbol-btn">*</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', ' / ', 0)" class="symbol-btn">/</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', ' % ', 0)" class="symbol-btn">%</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', '_', 0)" class="symbol-btn">_</button>
                    <button type="button" onclick="insertSymbol('challengeEditor', '# ', 0)" class="symbol-btn text-emerald-600">#</button>
                    <button type="button" onclick="editorUndo('challengeEditor')" class="symbol-btn"><i class="fa-solid fa-rotate-left"></i></button>
                    <button type="button" onclick="editorRedo('challengeEditor')" class="symbol-btn"><i class="fa-solid fa-rotate-right"></i></button>
                </div>

                <!-- Textarea Editor -->
                <div class="flex-1 p-3 bg-slate-900 flex flex-col min-h-[380px]">
                    <textarea id="challengeEditor" spellcheck="false"
                        class="code-editor-area code-font w-full flex-1 p-3 bg-slate-950 text-slate-100 rounded-xl border border-slate-800 focus:outline-none focus:ring-1 focus:ring-indigo-500 text-xs sm:text-sm resize-none font-mono min-h-[360px]"
                    ><?= htmlspecialchars($initialCode) ?></textarea>
                </div>

                <!-- Tombol Eksekusi & Evaluasi Auto-Check -->
                <div class="bg-slate-950 px-4 py-3 border-t border-slate-800 flex items-center justify-between">
                    <button type="button" onclick="document.getElementById('challengeEditor').value = <?= json_encode($activeChallenge['starter_code']) ?>;" class="text-xs text-slate-400 hover:text-slate-200">
                        <i class="fa-solid fa-arrow-rotate-left mr-1"></i> Reset Starter
                    </button>
                    <button type="button" id="btnRunTest" onclick="startChallengeEvaluation()" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-900/30 transition flex items-center gap-2">
                        <i class="fa-solid fa-play text-xs text-indigo-200"></i> Uji Kode Saya (Auto-Check)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Buka Petunjuk (Penalti -3 XP Hadiah) -->
<div id="hintConfirmModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 transform transition-all text-center">
        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-3 text-2xl shadow-inner">
            <i class="fa-solid fa-lightbulb"></i>
        </div>
        <h3 class="text-base font-black text-slate-900 mb-1.5">Buka Bantuan Petunjuk?</h3>
        <p class="text-xs text-slate-500 mb-5 leading-relaxed">
            Hadiah XP tantangan ini akan berkurang sebesar <strong class="text-rose-600 font-extrabold">3 XP</strong> (dari <?= $baseXp ?> XP menjadi <strong class="text-slate-800"><?= max(0, $baseXp - 3) ?> XP</strong>) saat berhasil diselesaikan. Yakin ingin membuka?
        </p>
        <div class="flex gap-2">
            <button type="button" onclick="closeHintModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition">
                Batal
            </button>
            <button type="button" id="btnConfirmUnlockHint" onclick="confirmUnlockHint(<?= (int)$activeChallenge['topic_id'] ?>)" class="flex-1 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-black text-xs shadow-md transition flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-check"></i> Ya, Buka
            </button>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/pyodide-engine.js?v=' . (file_exists(__DIR__ . '/../assets/js/pyodide-engine.js') ? filemtime(__DIR__ . '/../assets/js/pyodide-engine.js') : '2.0')) ?>"></script>
<script>
    const testCasesRaw = <?= json_encode($activeChallenge['test_cases_json']) ?>;
    const challengeId = <?= (int)$activeChallenge['id'] ?>;
    const apiUrl = '<?= base_url('api/submit_challenge.php') ?>';
    const csrfToken = '<?= csrf_token() ?>';

    function openHintModal() {
        document.getElementById('hintConfirmModal').classList.remove('hidden');
    }

    function closeHintModal() {
        document.getElementById('hintConfirmModal').classList.add('hidden');
    }

    async function confirmUnlockHint(topicId) {
        const btn = document.getElementById('btnConfirmUnlockHint');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Membuka...';
        
        try {
            const res = await fetch('<?= base_url('api/unlock_hint.php') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    topic_id: topicId,
                    csrf_token: csrfToken
                })
            });
            const data = await res.json();
            if (data.status === 'success') {
                closeHintModal();
                // Perbarui tampilan reward pill
                const xpSpan = document.getElementById('rewardXpVal');
                if (xpSpan) xpSpan.innerText = data.adjusted_xp;
                
                const pill = document.getElementById('rewardPill');
                if (pill && !pill.innerHTML.includes('-3 Hint')) {
                    pill.innerHTML += ' <span class="text-[10px] text-rose-700 bg-rose-100/90 px-1.5 py-0.2 rounded font-black">-3 Hint</span>';
                }

                // Tampilkan petunjuk terbuka
                const container = document.getElementById('hintBoxContainer');
                if (container) {
                    container.innerHTML = `
                        <div class="p-3.5 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900">
                            <div class="font-bold flex items-center justify-between mb-1.5 text-blue-800">
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-lightbulb text-amber-500"></i> Petunjuk:</span>
                                <span class="text-[10px] font-black text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">
                                    Hadiah Dipotong 3 XP (${data.adjusted_xp} XP)
                                </span>
                            </div>
                            <div class="text-slate-800 leading-relaxed">${data.hint}</div>
                        </div>
                    `;
                }
            } else {
                alert(data.message || 'Gagal membuka petunjuk.');
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan jaringan.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Ya, Buka';
        }
    }

    async function startChallengeEvaluation() {
        const btn = document.getElementById('btnRunTest');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Menguji...';
        
        try {
            await evaluateChallengeCode('challengeEditor', testCasesRaw, challengeId, apiUrl, csrfToken, 'testCaseResults');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-play text-xs text-indigo-200 mr-1"></i> Uji Kode Saya (Auto-Check)';
        }
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
