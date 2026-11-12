<?php
/**
 * Editor Terpadu Kurikulum & Materi - Dashboard Guru
 * Tab 1: Materi Edukatif (5 Blok / Raw HTML)
 * Tab 2: Soal Kuis Interaktif (3 Variasi Soal)
 * Tab 3: Tantangan Koding Praktik (Auto-Grader Builder)
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$user = current_user();
$db = get_db();

// Ambil ID atau sub_code dari query string
$topicId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$subCode = trim($_GET['sub'] ?? '');

if ($topicId > 0) {
    $stmt = $db->prepare("
        SELECT t.*, m.part_number, m.title as module_title, m.badge_name, m.badge_icon
        FROM topics t
        JOIN modules m ON m.id = t.module_id
        WHERE t.id = ?
        LIMIT 1
    ");
    $stmt->execute([$topicId]);
} elseif (!empty($subCode)) {
    $stmt = $db->prepare("
        SELECT t.*, m.part_number, m.title as module_title, m.badge_name, m.badge_icon
        FROM topics t
        JOIN modules m ON m.id = t.module_id
        WHERE t.sub_code = ?
        LIMIT 1
    ");
    $stmt->execute([$subCode]);
} else {
    set_flash('danger', 'Sub-topik materi tidak ditentukan.');
    header('Location: ' . base_url('teacher/curriculum.php'));
    exit;
}

$topic = $stmt->fetch();
if (!$topic) {
    set_flash('danger', 'Sub-topik tidak ditemukan dalam database.');
    header('Location: ' . base_url('teacher/curriculum.php'));
    exit;
}

$topicId = (int)$topic['id'];
$activeTab = $_GET['tab'] ?? ($_POST['active_tab'] ?? 'materi');
if (!in_array($activeTab, ['materi', 'kuis', 'tantangan'])) {
    $activeTab = 'materi';
}

// ============================================================================
// PROSES POST FORM
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($csrf)) {
        set_flash('danger', 'Sesi telah kedaluwarsa. Silakan refresh halaman dan coba lagi.');
        header('Location: ' . base_url("teacher/topic-edit.php?id={$topicId}&tab={$activeTab}"));
        exit;
    }

    $action = $_POST['action'] ?? '';

    // ------------------------------------------------------------------------
    // AKSI 1: RESET KE STANDAR KURIKULUM BAWAAN
    // ------------------------------------------------------------------------
    if ($action === 'reset_standard') {
        $partNumber = (int)$topic['part_number'];
        $dataFile = __DIR__ . "/../scripts/data_part{$partNumber}.php";

        if (!file_exists($dataFile)) {
            set_flash('danger', "File data kurikulum bawaan (data_part{$partNumber}.php) tidak ditemukan.");
        } else {
            $partData = require $dataFile;
            $codeKey = $topic['sub_code'];

            if (!isset($partData[$codeKey])) {
                set_flash('danger', "Data sub-topik {$codeKey} tidak ditemukan di template bawaan.");
            } else {
                $stdTopic = $partData[$codeKey];
                $db->beginTransaction();
                try {
                    // Reset Materi
                    $upTopic = $db->prepare("UPDATE topics SET title = ?, content_html = ? WHERE id = ?");
                    $upTopic->execute([trim($stdTopic['title']), trim($stdTopic['content_html']), $topicId]);

                    // Reset Kuis
                    $delQ = $db->prepare("DELETE FROM quizzes WHERE topic_id = ?");
                    $delQ->execute([$topicId]);

                    $insQ = $db->prepare("
                        INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    foreach ($stdTopic['quizzes'] as $q) {
                        $insQ->execute([
                            $topicId,
                            trim($q['question']),
                            trim($q['option_a']),
                            trim($q['option_b']),
                            trim($q['option_c']),
                            trim($q['option_d']),
                            strtoupper(trim($q['correct_option'])),
                            trim($q['explanation']),
                            (int)($q['xp_reward'] ?? 15)
                        ]);
                    }

                    $db->commit();
                    set_flash('success', "Materi dan kuis untuk {$codeKey} berhasil direset ke standar kurikulum bawaan!");
                } catch (Exception $e) {
                    $db->rollBack();
                    set_flash('danger', "Gagal mereset konten: " . $e->getMessage());
                }
            }
        }
        header('Location: ' . base_url("teacher/topic-edit.php?id={$topicId}&tab={$activeTab}"));
        exit;
    }

    // ------------------------------------------------------------------------
    // AKSI 2: SIMPAN MATERI PEMBELAJARAN (TAB 1)
    // ------------------------------------------------------------------------
    if ($action === 'save_materi') {
        $newTitle = trim($_POST['title'] ?? $topic['title']);
        $editorMode = $_POST['editor_mode'] ?? 'raw';
        $finalHtml = '';

        if ($editorMode === 'blocks') {
            // Kompilasi 5 blok terstruktur menjadi format HTML konsisten PyLMS
            $b1_analogi = trim($_POST['b1_analogi'] ?? '');
            $b2_konsep = trim($_POST['b2_konsep'] ?? '');
            $b3_kode = trim($_POST['b3_kode'] ?? '');
            $b3_penjelasan = trim($_POST['b3_penjelasan'] ?? '');
            $b4_error = trim($_POST['b4_error'] ?? '');
            $b5_tips = trim($_POST['b5_tips'] ?? '');

            $finalHtml = '<div class="space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">' . "\n";
            
            // Blok 1: Analogi
            if (!empty($b1_analogi)) {
                $finalHtml .= '    <div class="p-4 bg-amber-50/80 border-l-4 border-amber-500 rounded-2xl text-slate-800 shadow-sm">' . "\n";
                $finalHtml .= '        <div class="font-black text-amber-900 flex items-center gap-2 mb-1.5 text-xs sm:text-sm">' . "\n";
                $finalHtml .= '            <i class="fa-solid fa-lightbulb text-amber-600"></i> 1. Analogi Dunia Nyata' . "\n";
                $finalHtml .= '        </div>' . "\n";
                $finalHtml .= '        <p>' . nl2br(htmlspecialchars($b1_analogi)) . '</p>' . "\n";
                $finalHtml .= '    </div>' . "\n\n";
            }

            // Blok 2: Konsep Inti
            if (!empty($b2_konsep)) {
                $finalHtml .= '    <div>' . "\n";
                $finalHtml .= '        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">' . "\n";
                $finalHtml .= '            <i class="fa-solid fa-gears text-blue-600"></i> 2. Konsep Inti &amp; Cara Kerja Interpreter' . "\n";
                $finalHtml .= '        </h4>' . "\n";
                $finalHtml .= '        <p>' . nl2br(htmlspecialchars($b2_konsep)) . '</p>' . "\n";
                $finalHtml .= '    </div>' . "\n\n";
            }

            // Blok 3: Bedah Contoh Kode
            if (!empty($b3_kode)) {
                $finalHtml .= '    <div>' . "\n";
                $finalHtml .= '        <h4 class="text-sm sm:text-base font-black text-slate-900 mb-2 flex items-center gap-2">' . "\n";
                $finalHtml .= '            <i class="fa-solid fa-code text-indigo-600"></i> 3. Bedah Contoh Kode Nyata' . "\n";
                $finalHtml .= '        </h4>' . "\n";
                $finalHtml .= '        <pre class="bg-slate-900 text-slate-100 p-4 rounded-2xl text-xs font-mono overflow-x-auto border border-slate-800 shadow-inner"><code>' . htmlspecialchars($b3_kode) . '</code></pre>' . "\n";
                if (!empty($b3_penjelasan)) {
                    $finalHtml .= '        <p class="mt-2 text-slate-600">' . nl2br(htmlspecialchars($b3_penjelasan)) . '</p>' . "\n";
                }
                $finalHtml .= '    </div>' . "\n\n";
            }

            // Blok 4: Jebakan Error
            if (!empty($b4_error)) {
                $finalHtml .= '    <div class="p-4 bg-rose-50/80 border-l-4 border-rose-500 rounded-2xl text-slate-800 shadow-sm">' . "\n";
                $finalHtml .= '        <div class="font-black text-rose-900 flex items-center gap-2 mb-1 text-xs sm:text-sm">' . "\n";
                $finalHtml .= '            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> 4. Jebakan Error Pemula &amp; Solusi' . "\n";
                $finalHtml .= '        </div>' . "\n";
                $finalHtml .= '        <p>' . nl2br(htmlspecialchars($b4_error)) . '</p>' . "\n";
                $finalHtml .= '    </div>' . "\n\n";
            }

            // Blok 5: Tips HP
            if (!empty($b5_tips)) {
                $finalHtml .= '    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-950 flex items-center gap-3">' . "\n";
                $finalHtml .= '        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">' . "\n";
                $finalHtml .= '            <i class="fa-solid fa-mobile-screen-button"></i>' . "\n";
                $finalHtml .= '        </div>' . "\n";
                $finalHtml .= '        <div>' . "\n";
                $finalHtml .= '            <span class="font-bold text-emerald-900 text-xs sm:text-sm">Tips Coding HP:</span>' . "\n";
                $finalHtml .= '            <p class="text-xs text-emerald-800 mt-0.5">' . nl2br(htmlspecialchars($b5_tips)) . '</p>' . "\n";
                $finalHtml .= '        </div>' . "\n";
                $finalHtml .= '    </div>' . "\n";
            }

            $finalHtml .= '</div>';
        } else {
            // Mode Raw HTML langsung dari textarea
            $finalHtml = trim($_POST['content_html'] ?? '');
        }

        $up = $db->prepare("UPDATE topics SET title = ?, content_html = ? WHERE id = ?");
        $up->execute([$newTitle, $finalHtml, $topicId]);

        set_flash('success', 'Materi pembelajaran berhasil disimpan dan diperbarui!');
        header('Location: ' . base_url("teacher/topic-edit.php?id={$topicId}&tab=materi"));
        exit;
    }

    // ------------------------------------------------------------------------
    // AKSI 3: SIMPAN SOAL KUIS (TAB 2)
    // ------------------------------------------------------------------------
    if ($action === 'save_kuis') {
        $rawQuizzes = $_POST['quizzes'] ?? [];
        $validQuizzes = [];

        foreach ($rawQuizzes as $q) {
            $question = trim($q['question'] ?? '');
            $optA = trim($q['option_a'] ?? '');
            $optB = trim($q['option_b'] ?? '');
            $optC = trim($q['option_c'] ?? '');
            $optD = trim($q['option_d'] ?? '');
            $correct = strtoupper(trim($q['correct_option'] ?? 'A'));
            $expl = trim($q['explanation'] ?? '');
            $xp = (int)($q['xp_reward'] ?? 15);

            if (!empty($question) && !empty($optA) && !empty($optB)) {
                $validQuizzes[] = [
                    'question' => $question,
                    'option_a' => $optA,
                    'option_b' => $optB,
                    'option_c' => $optC,
                    'option_d' => $optD,
                    'correct_option' => in_array($correct, ['A', 'B', 'C', 'D']) ? $correct : 'A',
                    'explanation' => $expl,
                    'xp_reward' => $xp > 0 ? $xp : 15
                ];
            }
        }

        if (empty($validQuizzes)) {
            set_flash('warning', 'Harap isi minimal 1 butir soal kuis dengan lengkap.');
        } else {
            $db->beginTransaction();
            try {
                $delQ = $db->prepare("DELETE FROM quizzes WHERE topic_id = ?");
                $delQ->execute([$topicId]);

                $insQ = $db->prepare("
                    INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                foreach ($validQuizzes as $vq) {
                    $insQ->execute([
                        $topicId,
                        $vq['question'],
                        $vq['option_a'],
                        $vq['option_b'],
                        $vq['option_c'],
                        $vq['option_d'],
                        $vq['correct_option'],
                        $vq['explanation'],
                        $vq['xp_reward']
                    ]);
                }
                $db->commit();
                set_flash('success', count($validQuizzes) . ' butir soal kuis berhasil disimpan dan siap diuji siswa!');
            } catch (Exception $e) {
                $db->rollBack();
                set_flash('danger', 'Gagal menyimpan soal kuis: ' . $e->getMessage());
            }
        }
        header('Location: ' . base_url("teacher/topic-edit.php?id={$topicId}&tab=kuis"));
        exit;
    }

    // ------------------------------------------------------------------------
    // AKSI 4: SIMPAN TANTANGAN KODING (TAB 3)
    // ------------------------------------------------------------------------
    if ($action === 'save_tantangan') {
        $chTitle = trim($_POST['challenge_title'] ?? "Tantangan {$topic['sub_code']}");
        $chInstructions = trim($_POST['challenge_instructions'] ?? '');
        $chStarter = trim($_POST['starter_code'] ?? '');
        $chHint = trim($_POST['challenge_hint'] ?? '');
        $chXp = (int)($_POST['challenge_xp'] ?? 30);

        // Kumpulkan Kasus Uji (Test Cases Builder)
        $tcNames = $_POST['tc_name'] ?? [];
        $tcExpected = $_POST['tc_expected'] ?? [];
        $testCases = [];

        foreach ($tcNames as $idx => $name) {
            $cName = trim($name);
            $cExp = trim($tcExpected[$idx] ?? '');
            if (!empty($cName) || !empty($cExp)) {
                $testCases[] = [
                    'name' => $cName ?: "Pengujian " . ($idx + 1),
                    'expected_output' => $cExp
                ];
            }
        }

        $testCasesJson = json_encode($testCases, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Cek apakah challenge sudah ada
        $chk = $db->prepare("SELECT id FROM challenges WHERE topic_id = ? LIMIT 1");
        $chk->execute([$topicId]);
        $existingChId = $chk->fetchColumn();

        if ($existingChId) {
            $upCh = $db->prepare("
                UPDATE challenges 
                SET title = ?, instructions = ?, starter_code = ?, test_cases_json = ?, hint = ?, xp_reward = ?
                WHERE id = ?
            ");
            $upCh->execute([$chTitle, $chInstructions, $chStarter, $testCasesJson, $chHint, $chXp, $existingChId]);
        } else {
            $insCh = $db->prepare("
                INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $insCh->execute([$topicId, $chTitle, $chInstructions, $chStarter, $testCasesJson, $chHint, $chXp]);
        }

        // Juga update starter_code di tabel topics agar sinkron
        $upT = $db->prepare("UPDATE topics SET starter_code = ? WHERE id = ?");
        $upT->execute([$chStarter, $topicId]);

        set_flash('success', 'Tantangan koding dan kasus uji auto-grader berhasil diperbarui!');
        header('Location: ' . base_url("teacher/topic-edit.php?id={$topicId}&tab=tantangan"));
        exit;
    }
}

// Ambil Kuis Aktif untuk Topic Ini
$stmtQuizzes = $db->prepare("SELECT * FROM quizzes WHERE topic_id = ? ORDER BY id ASC");
$stmtQuizzes->execute([$topicId]);
$quizzes = $stmtQuizzes->fetchAll();

// Ambil Challenge Aktif untuk Topic Ini
$stmtCh = $db->prepare("SELECT * FROM challenges WHERE topic_id = ? LIMIT 1");
$stmtCh->execute([$topicId]);
$challenge = $stmtCh->fetch();

// Parse test cases JSON
$testCases = [];
if (!empty($challenge['test_cases_json'])) {
    $decoded = json_decode($challenge['test_cases_json'], true);
    if (is_array($decoded)) {
        $testCases = $decoded;
    }
}
if (empty($testCases)) {
    $testCases = [['name' => 'Pengujian Output Program', 'expected_output' => '']];
}

$pageTitle = "Edit Sub-Materi " . $topic['sub_code'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigasi -->
    <nav class="flex items-center gap-2 text-xs text-slate-500 mb-4 font-semibold">
        <a href="<?= base_url('teacher/dashboard.php') ?>" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <a href="<?= base_url('teacher/curriculum.php') ?>" class="hover:text-blue-600">Kurikulum</a>
        <span>/</span>
        <a href="<?= base_url('teacher/curriculum.php?part=' . $topic['part_number']) ?>" class="hover:text-blue-600">Part <?= $topic['part_number'] ?></a>
        <span>/</span>
        <span class="text-slate-800 font-bold"><?= sanitize($topic['sub_code']) ?></span>
    </nav>

    <!-- Header Action Bar Sub-Topik -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-sm shadow-md shrink-0">
                <?= sanitize($topic['sub_code']) ?>
            </span>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">
                        Part <?= $topic['part_number'] ?> &bull; <?= sanitize($topic['module_title']) ?>
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">
                    <?= sanitize($topic['title']) ?>
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap self-end md:self-center">
            <!-- Tombol Pratinjau Siswa -->
            <a href="<?= base_url('student/learn.php?part=' . $topic['part_number'] . '&sub=' . urlencode($topic['sub_code'])) ?>" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs shadow-xs transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square text-slate-500"></i> Pratinjau Siswa
            </a>

            <!-- Tombol Reset ke Kurikulum Bawaan -->
            <button type="button" onclick="confirmResetStandard()" class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition flex items-center gap-2">
                <i class="fa-solid fa-rotate-left"></i> Reset ke Standar
            </button>
        </div>
    </div>

    <!-- Hidden Reset Form -->
    <form id="resetStandardForm" action="<?= base_url("teacher/topic-edit.php?id={$topicId}") ?>" method="POST" style="display:none;">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="action" value="reset_standard">
        <input type="hidden" name="active_tab" value="<?= htmlspecialchars($activeTab) ?>">
    </form>

    <!-- 3-Tab Navigasi Editor -->
    <div class="flex border-b border-slate-200 mb-6 gap-2">
        <a href="?id=<?= $topicId ?>&tab=materi" class="px-5 py-3 font-bold text-xs sm:text-sm flex items-center gap-2 border-b-2 transition <?= $activeTab === 'materi' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50 rounded-t-2xl' : 'border-transparent text-slate-500 hover:text-slate-800' ?>">
            <i class="fa-solid fa-book-open"></i>
            <span>1. Materi 5-Blok</span>
        </a>
        <a href="?id=<?= $topicId ?>&tab=kuis" class="px-5 py-3 font-bold text-xs sm:text-sm flex items-center gap-2 border-b-2 transition <?= $activeTab === 'kuis' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50 rounded-t-2xl' : 'border-transparent text-slate-500 hover:text-slate-800' ?>">
            <i class="fa-solid fa-list-check"></i>
            <span>2. Soal Kuis (<?= count($quizzes) ?>)</span>
        </a>
        <a href="?id=<?= $topicId ?>&tab=tantangan" class="px-5 py-3 font-bold text-xs sm:text-sm flex items-center gap-2 border-b-2 transition <?= $activeTab === 'tantangan' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50 rounded-t-2xl' : 'border-transparent text-slate-500 hover:text-slate-800' ?>">
            <i class="fa-solid fa-code"></i>
            <span>3. Tantangan Koding</span>
        </a>
    </div>

    <!-- ================================================================== -->
    <!-- TAB 1: FORM MATERI PEMBELAJARAN                                    -->
    <!-- ================================================================== -->
    <?php if ($activeTab === 'materi'): ?>
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <form action="<?= base_url("teacher/topic-edit.php?id={$topicId}") ?>" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="save_materi">
            <input type="hidden" name="active_tab" value="materi">
            <input type="hidden" name="editor_mode" id="editorModeInput" value="raw">

            <!-- Judul Sub-Materi -->
            <div>
                <label for="topicTitle" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Judul Sub-Materi
                </label>
                <input type="text" name="title" id="topicTitle" value="<?= htmlspecialchars($topic['title']) ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none transition" required>
            </div>

            <!-- Mode Selector Switcher -->
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl border border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-700">Mode Editor Materi:</span>
                    <span class="text-[11px] text-slate-500 hidden sm:inline">(Pilih format yang paling nyaman untuk Anda)</span>
                </div>
                <div class="inline-flex p-1 bg-white border border-slate-200 rounded-xl text-xs font-bold">
                    <button type="button" id="btnModeRaw" onclick="switchEditorMode('raw')" class="px-3 py-1 rounded-lg bg-indigo-600 text-white shadow-xs">
                        Raw HTML
                    </button>
                    <button type="button" id="btnModeBlocks" onclick="switchEditorMode('blocks')" class="px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900">
                        Form 5-Blok
                    </button>
                </div>
            </div>

            <!-- Editor Container: Mode Raw HTML -->
            <div id="containerRaw">
                <div class="flex items-center justify-between mb-2">
                    <label for="contentHtml" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Kode Konten Pembelajaran (HTML Responsive)
                    </label>
                    <span class="text-[11px] text-indigo-600 font-semibold">
                        <i class="fa-solid fa-code"></i> Mendukung tag Tailwind & FontAwesome
                    </span>
                </div>
                <textarea name="content_html" id="contentHtml" rows="18" class="w-full p-4 bg-slate-900 text-slate-100 rounded-2xl font-mono text-xs leading-relaxed border border-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-inner"><?= htmlspecialchars($topic['content_html']) ?></textarea>
            </div>

            <!-- Editor Container: Mode Form 5-Blok Terstruktur -->
            <div id="containerBlocks" style="display:none;" class="space-y-5">
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-900">
                    <i class="fa-solid fa-circle-info text-amber-600"></i> Mode 5-Blok memudahkan Anda mengisi materi per bagian. Saat disimpan, teks Anda akan otomatis dibungkus dengan kartu desain responsif bawaan PyLMS.
                </div>

                <!-- Blok 1 -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <label class="block text-xs font-black text-amber-900 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-600"></i> Blok 1: Analogi Dunia Nyata
                    </label>
                    <textarea name="b1_analogi" rows="3" placeholder="Ceritakan perumpamaan praktis yang mudah diingat siswa..." class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <!-- Blok 2 -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <label class="block text-xs font-black text-blue-900 flex items-center gap-2">
                        <i class="fa-solid fa-gears text-blue-600"></i> Blok 2: Konsep Inti &amp; Cara Kerja Interpreter
                    </label>
                    <textarea name="b2_konsep" rows="4" placeholder="Jelaskan konsep inti pemrograman dan cara Python mengeksekusinya..." class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <!-- Blok 3 -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <label class="block text-xs font-black text-indigo-900 flex items-center gap-2">
                        <i class="fa-solid fa-code text-indigo-600"></i> Blok 3: Bedah Contoh Kode Nyata
                    </label>
                    <textarea name="b3_kode" rows="5" placeholder="# Ketik contoh kode Python di sini..." class="w-full p-3 bg-slate-900 text-slate-100 font-mono border border-slate-800 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                    <textarea name="b3_penjelasan" rows="2" placeholder="Penjelasan singkat alur contoh kode di atas (opsional)..." class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <!-- Blok 4 -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <label class="block text-xs font-black text-rose-900 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Blok 4: Jebakan Error Pemula &amp; Solusi
                    </label>
                    <textarea name="b4_error" rows="3" placeholder="Sebutkan kesalahan umum yang sering dialami pemula dan cara memperbaikinya..." class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>

                <!-- Blok 5 -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                    <label class="block text-xs font-black text-emerald-900 flex items-center gap-2">
                        <i class="fa-solid fa-mobile-screen-button text-emerald-600"></i> Blok 5: Tips Cepat Mengetik di HP
                    </label>
                    <textarea name="b5_tips" rows="2" placeholder="Tips mengetik praktis memanfaatkan bilah simbol keyboard emulator HP..." class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                <button type="button" onclick="toggleLivePreview()" class="text-xs font-bold text-slate-600 hover:text-indigo-600 flex items-center gap-1.5">
                    <i class="fa-solid fa-eye"></i> <span id="textTogglePreview">Tampilkan Pratinjau Cepat</span>
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Materi Pembelajaran
                </button>
            </div>
        </form>

        <!-- Live Preview Box -->
        <div id="livePreviewBox" style="display:none;" class="mt-8 pt-6 border-t-2 border-dashed border-slate-200">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-desktop text-indigo-500"></i> Pratinjau Tampilan Pembelajaran:
            </div>
            <div id="previewContent" class="p-5 bg-slate-50 rounded-3xl border border-slate-200">
                <?= $topic['content_html'] ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ================================================================== -->
    <!-- TAB 2: FORM SOAL KUIS INTERAKTIF                                   -->
    <!-- ================================================================== -->
    <?php if ($activeTab === 'kuis'): ?>
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <form action="<?= base_url("teacher/topic-edit.php?id={$topicId}") ?>" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="save_kuis">
            <input type="hidden" name="active_tab" value="kuis">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-2">
                <div>
                    <h3 class="text-base font-black text-slate-900">Kelola Butir Soal Kuis</h3>
                    <p class="text-xs text-slate-500">Standar kurikulum menetapkan 3 variasi soal (Konsep, Tebak Output, Analisis Bug) dengan KKM 80%.</p>
                </div>
                <button type="button" onclick="addNewQuizCard()" class="px-3.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-xs transition self-start sm:self-auto flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Tambah Soal
                </button>
            </div>

            <!-- Daftar Kartu Soal Kuis -->
            <div id="quizCardsContainer" class="space-y-6">
                <?php 
                $quizCategories = [
                    0 => ['title' => 'Soal 1: Konsep & Logika Dasar', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                    1 => ['title' => 'Soal 2: Tebak Output Kode', 'badge' => 'bg-purple-50 text-purple-700 border-purple-200'],
                    2 => ['title' => 'Soal 3: Analisis Bug / Troubleshooting', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200']
                ];

                foreach ($quizzes as $idx => $q): 
                    $cat = $quizCategories[$idx] ?? ['title' => 'Soal ' . ($idx + 1), 'badge' => 'bg-slate-100 text-slate-700 border-slate-200'];
                ?>
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/60 shadow-xs quiz-card relative" data-index="<?= $idx ?>">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black border <?= $cat['badge'] ?>">
                            <?= $cat['title'] ?>
                        </span>
                        <?php if ($idx >= 3): ?>
                        <button type="button" onclick="this.closest('.quiz-card').remove()" class="text-rose-600 hover:text-rose-700 text-xs font-bold">
                            <i class="fa-solid fa-trash-can"></i> Hapus Soal
                        </button>
                        <?php endif; ?>
                    </div>

                    <!-- Pertanyaan -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pertanyaan Soal</label>
                        <textarea name="quizzes[<?= $idx ?>][question]" rows="3" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:outline-none" required><?= htmlspecialchars($q['question']) ?></textarea>
                    </div>

                    <!-- Opsi Jawaban A, B, C, D dengan Radio Kunci Jawaban -->
                    <div class="space-y-2 mb-4">
                        <span class="block text-xs font-bold text-slate-700 mb-1">Pilihan Jawaban &amp; Kunci Jawaban Benar (Pilih satu):</span>
                        
                        <?php foreach (['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $optLetter => $optField): ?>
                        <div class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-200">
                            <label class="flex items-center gap-1.5 cursor-pointer shrink-0 pl-2">
                                <input type="radio" name="quizzes[<?= $idx ?>][correct_option]" value="<?= $optLetter ?>" <?= $q['correct_option'] === $optLetter ? 'checked' : '' ?> class="w-4 h-4 text-emerald-600 focus:ring-emerald-500" required>
                                <span class="font-black text-xs text-slate-800">Opsi <?= $optLetter ?></span>
                            </label>
                            <input type="text" name="quizzes[<?= $idx ?>][<?= $optField ?>]" value="<?= htmlspecialchars($q[$optField]) ?>" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none" placeholder="Teks pilihan jawaban <?= $optLetter ?>" required>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Penjelasan Ilmiah & XP Reward -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kotak Penjelasan / Pembahasan Soal</label>
                            <input type="text" name="quizzes[<?= $idx ?>][explanation]" value="<?= htmlspecialchars($q['explanation']) ?>" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Jelaskan alasan mengapa kunci jawaban tersebut benar..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">XP Reward</label>
                            <input type="number" name="quizzes[<?= $idx ?>][xp_reward]" value="<?= (int)$q['xp_reward'] ?>" min="5" max="100" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-amber-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Tombol Simpan Kuis -->
            <div class="pt-4 border-t border-slate-200 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Seluruh Soal Kuis
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- ================================================================== -->
    <!-- TAB 3: FORM TANTANGAN KODING (AUTO-GRADER)                         -->
    <!-- ================================================================== -->
    <?php if ($activeTab === 'tantangan'): ?>
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <form action="<?= base_url("teacher/topic-edit.php?id={$topicId}") ?>" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="save_tantangan">
            <input type="hidden" name="active_tab" value="tantangan">

            <!-- Judul & XP -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="sm:col-span-3">
                    <label for="chTitle" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Judul Tantangan Koding
                    </label>
                    <input type="text" name="challenge_title" id="chTitle" value="<?= htmlspecialchars($challenge['title'] ?? "Tantangan {$topic['sub_code']}") ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none" required>
                </div>
                <div>
                    <label for="chXp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Reward XP
                    </label>
                    <input type="number" name="challenge_xp" id="chXp" value="<?= (int)($challenge['xp_reward'] ?? 30) ?>" min="10" max="200" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-amber-700 focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none" required>
                </div>
            </div>

            <!-- Instruksi Tantangan -->
            <div>
                <label for="chInstructions" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Instruksi Tugas Siswa
                </label>
                <textarea name="challenge_instructions" id="chInstructions" rows="4" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none" placeholder="Tuliskan instruksi apa yang harus diketik atau dihasilkan siswa..." required><?= htmlspecialchars($challenge['instructions'] ?? '') ?></textarea>
            </div>

            <!-- Starter Code Python -->
            <div>
                <label for="starterCode" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Kode Awal Siswa (Starter Code)
                </label>
                <textarea name="starter_code" id="starterCode" rows="5" class="w-full p-4 bg-slate-900 text-slate-100 font-mono text-xs border border-slate-800 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-inner"><?= htmlspecialchars($challenge['starter_code'] ?? ($topic['starter_code'] ?? '')) ?></textarea>
            </div>

            <!-- Test Cases Builder Interaktif -->
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                            <i class="fa-solid fa-vial-circle-check text-purple-600"></i> Kriteria Pengujian (Test Cases Auto-Grader)
                        </h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Sistem WebAssembly akan membandingkan output kode siswa dengan output yang diharapkan.</p>
                    </div>
                    <button type="button" onclick="addNewTestCaseRow()" class="px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-xs transition flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Tambah Uji Kasus
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs" id="testCaseTable">
                        <thead>
                            <tr class="text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                                <th class="pb-2">Nama Pengujian</th>
                                <th class="pb-2">Output yang Diharapkan (Expected Output)</th>
                                <th class="pb-2 w-16 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="testCaseTbody">
                            <?php foreach ($testCases as $idx => $tc): ?>
                            <tr class="tc-row">
                                <td class="py-2.5 pr-2">
                                    <input type="text" name="tc_name[]" value="<?= htmlspecialchars($tc['name'] ?? '') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Misal: Cek Sambutan" required>
                                </td>
                                <td class="py-2.5 pr-2">
                                    <input type="text" name="tc_expected[]" value="<?= htmlspecialchars($tc['expected_output'] ?? '') ?>" class="w-full px-3 py-1.5 bg-white font-mono border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Teks persis output Python yang diharapkan" required>
                                </td>
                                <td class="py-2.5 text-right">
                                    <button type="button" onclick="this.closest('.tc-row').remove()" class="text-rose-600 hover:text-rose-800 text-xs px-2 py-1">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Petunjuk Bantuan (Hint) -->
            <div>
                <label for="chHint" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Petunjuk Bantuan Siswa (Hint - Muncul jika siswa kesulitan)
                </label>
                <input type="text" name="challenge_hint" id="chHint" value="<?= htmlspecialchars($challenge['hint'] ?? '') ?>" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:outline-none" placeholder="Contoh: Gunakan fungsi print(...)">
            </div>

            <!-- Tombol Simpan Tantangan -->
            <div class="pt-4 border-t border-slate-200 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Tantangan Koding
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
// Switch Editor Mode: Raw HTML vs Form 5-Blok
function switchEditorMode(mode) {
    const rawBox = document.getElementById('containerRaw');
    const blocksBox = document.getElementById('containerBlocks');
    const modeInput = document.getElementById('editorModeInput');
    const btnRaw = document.getElementById('btnModeRaw');
    const btnBlocks = document.getElementById('btnModeBlocks');

    if (mode === 'blocks') {
        rawBox.style.display = 'none';
        blocksBox.style.display = 'block';
        modeInput.value = 'blocks';
        btnBlocks.className = 'px-3 py-1 rounded-lg bg-indigo-600 text-white shadow-xs';
        btnRaw.className = 'px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900';
    } else {
        rawBox.style.display = 'block';
        blocksBox.style.display = 'none';
        modeInput.value = 'raw';
        btnRaw.className = 'px-3 py-1 rounded-lg bg-indigo-600 text-white shadow-xs';
        btnBlocks.className = 'px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900';
    }
}

// Toggle Live Preview Panel
function toggleLivePreview() {
    const box = document.getElementById('livePreviewBox');
    const txt = document.getElementById('textTogglePreview');
    if (box.style.display === 'none') {
        // Update isi preview dengan konten terkini
        const rawContent = document.getElementById('contentHtml')?.value || '';
        document.getElementById('previewContent').innerHTML = rawContent;
        box.style.display = 'block';
        txt.textContent = 'Sembunyikan Pratinjau Cepat';
    } else {
        box.style.display = 'none';
        txt.textContent = 'Tampilkan Pratinjau Cepat';
    }
}

// Dialog Konfirmasi Reset ke Kurikulum Standar
function confirmResetStandard() {
    if (confirm("PERINGATAN:\nApakah Anda yakin ingin mengembalikan materi dan soal kuis sub-topik ini ke versi kurikulum standar bawaan?\n\nSeluruh perubahan kustom yang pernah Anda buat akan ditimpa.")) {
        document.getElementById('resetStandardForm').submit();
    }
}

// Tambah Baris Kasus Uji (Test Case Builder)
function addNewTestCaseRow() {
    const tbody = document.getElementById('testCaseTbody');
    const tr = document.createElement('tr');
    tr.className = 'tc-row';
    tr.innerHTML = `
        <td class="py-2.5 pr-2">
            <input type="text" name="tc_name[]" value="Kasus Uji Tambahan" class="w-full px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" required>
        </td>
        <td class="py-2.5 pr-2">
            <input type="text" name="tc_expected[]" value="" class="w-full px-3 py-1.5 bg-white font-mono border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Teks persis output yang diharapkan" required>
        </td>
        <td class="py-2.5 text-right">
            <button type="button" onclick="this.closest('.tc-row').remove()" class="text-rose-600 hover:text-rose-800 text-xs px-2 py-1">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

// Tambah Kartu Soal Kuis Baru
function addNewQuizCard() {
    const container = document.getElementById('quizCardsContainer');
    const existingCards = container.querySelectorAll('.quiz-card');
    const nextIdx = existingCards.length;

    const div = document.createElement('div');
    div.className = 'p-5 rounded-2xl border border-slate-200 bg-slate-50/60 shadow-xs quiz-card relative';
    div.setAttribute('data-index', nextIdx);
    div.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black border bg-emerald-50 text-emerald-700 border-emerald-200">
                Soal Tambahan #${nextIdx + 1}
            </span>
            <button type="button" onclick="this.closest('.quiz-card').remove()" class="text-rose-600 hover:text-rose-700 text-xs font-bold">
                <i class="fa-solid fa-trash-can"></i> Hapus Soal
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-xs font-bold text-slate-700 mb-1">Pertanyaan Soal</label>
            <textarea name="quizzes[${nextIdx}][question]" rows="3" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Ketik pertanyaan kuis..." required></textarea>
        </div>

        <div class="space-y-2 mb-4">
            <span class="block text-xs font-bold text-slate-700 mb-1">Pilihan Jawaban & Kunci Jawaban Benar:</span>
            
            <div class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-200">
                <label class="flex items-center gap-1.5 cursor-pointer shrink-0 pl-2">
                    <input type="radio" name="quizzes[${nextIdx}][correct_option]" value="A" checked class="w-4 h-4 text-emerald-600">
                    <span class="font-black text-xs text-slate-800">Opsi A</span>
                </label>
                <input type="text" name="quizzes[${nextIdx}][option_a]" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs" placeholder="Teks pilihan jawaban A" required>
            </div>

            <div class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-200">
                <label class="flex items-center gap-1.5 cursor-pointer shrink-0 pl-2">
                    <input type="radio" name="quizzes[${nextIdx}][correct_option]" value="B" class="w-4 h-4 text-emerald-600">
                    <span class="font-black text-xs text-slate-800">Opsi B</span>
                </label>
                <input type="text" name="quizzes[${nextIdx}][option_b]" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs" placeholder="Teks pilihan jawaban B" required>
            </div>

            <div class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-200">
                <label class="flex items-center gap-1.5 cursor-pointer shrink-0 pl-2">
                    <input type="radio" name="quizzes[${nextIdx}][correct_option]" value="C" class="w-4 h-4 text-emerald-600">
                    <span class="font-black text-xs text-slate-800">Opsi C</span>
                </label>
                <input type="text" name="quizzes[${nextIdx}][option_c]" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs" placeholder="Teks pilihan jawaban C" required>
            </div>

            <div class="flex items-center gap-3 p-2 bg-white rounded-xl border border-slate-200">
                <label class="flex items-center gap-1.5 cursor-pointer shrink-0 pl-2">
                    <input type="radio" name="quizzes[${nextIdx}][correct_option]" value="D" class="w-4 h-4 text-emerald-600">
                    <span class="font-black text-xs text-slate-800">Opsi D</span>
                </label>
                <input type="text" name="quizzes[${nextIdx}][option_d]" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs" placeholder="Teks pilihan jawaban D" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-700 mb-1">Kotak Penjelasan / Pembahasan Soal</label>
                <input type="text" name="quizzes[${nextIdx}][explanation]" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs" placeholder="Jelaskan alasan kunci jawaban..." required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">XP Reward</label>
                <input type="number" name="quizzes[${nextIdx}][xp_reward]" value="15" min="5" max="100" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-amber-700" required>
            </div>
        </div>
    `;
    container.appendChild(div);
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
