<?php
/**
 * API Endpoint: Submit Coding Challenge Completion (per Sub-Part)
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.']);
    exit;
}

$user = current_user();
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Data input tidak valid.']);
    exit;
}

$csrf = $input['csrf_token'] ?? '';
if (!verify_csrf($csrf)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'CSRF Token tidak valid.']);
    exit;
}

$challengeId = (int)($input['challenge_id'] ?? 0);
$studentCode = trim($input['student_code'] ?? '');

$db = get_db();
$stmt = $db->prepare("
    SELECT c.*, t.module_id, t.sub_code, t.order_index, m.part_number, m.badge_name, m.badge_icon
    FROM challenges c
    JOIN topics t ON t.id = c.topic_id
    JOIN modules m ON m.id = t.module_id
    WHERE c.id = ?
    LIMIT 1
");
$stmt->execute([$challengeId]);
$chal = $stmt->fetch();

if (!$chal) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Tantangan tidak ditemukan.']);
    exit;
}

// Mode demonstrasi guru: evaluasi berhasil tanpa mencemari database nilai siswa
if ($user['role'] === 'guru') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Luar biasa! Seluruh test case terpenuhi (Mode Demonstrasi Guru).',
        'xp_awarded' => 0,
        'is_first' => false,
        'badge_earned' => null,
        'next_part_unlocked' => false
    ]);
    exit;
}

// Cek apakah sudah pernah menyelesaikan tantangan ini & apakah menggunakan petunjuk
$stmtCheck = $db->prepare("SELECT challenge_completed, hint_used FROM student_progress WHERE user_id = ? AND topic_id = ?");
$stmtCheck->execute([$user['id'], $chal['topic_id']]);
$prog = $stmtCheck->fetch();

$isFirstCompletion = !($prog && $prog['challenge_completed']);
$baseReward = (int)$chal['xp_reward'];
$hintUsed = ($prog && !empty($prog['hint_used']));
$penalty = $hintUsed ? 3 : 0;
$actualReward = max(0, $baseReward - $penalty);

$xpAwarded = $isFirstCompletion ? $actualReward : 0;

if ($isFirstCompletion && $xpAwarded > 0) {
    add_user_xp($user['id'], $xpAwarded);
}

// Update / Insert student_progress
$stmtUpsert = $db->prepare("
    INSERT INTO student_progress (user_id, topic_id, is_completed, challenge_completed, last_submitted_code)
    VALUES (?, ?, 1, 1, ?)
    ON DUPLICATE KEY UPDATE is_completed = 1, challenge_completed = 1, last_submitted_code = VALUES(last_submitted_code)
");
$stmtUpsert->execute([$user['id'], $chal['topic_id'], $studentCode]);

// Cek apakah seluruh sub-topik di modul ini sudah komplit 100%
$stmtModuleProg = $db->prepare("
    SELECT COUNT(t.id) as total_topics,
           SUM(CASE WHEN sp.is_completed = 1 THEN 1 ELSE 0 END) as completed_topics
    FROM topics t
    LEFT JOIN student_progress sp ON sp.topic_id = t.id AND sp.user_id = ?
    WHERE t.module_id = ?
");
$stmtModuleProg->execute([$user['id'], $chal['module_id']]);
$modStat = $stmtModuleProg->fetch();
$isModuleCompleted = ($modStat && $modStat['total_topics'] > 0 && $modStat['completed_topics'] >= $modStat['total_topics']);

if ($isModuleCompleted) {
    // Berikan Badge Modul
    if (!empty($chal['badge_name'])) {
        award_badge($user['id'], $chal['badge_name'], $chal['badge_icon']);
    }
    // Buka kunci part selanjutnya
    $nextPart = $chal['part_number'] + 1;
    if ($nextPart <= 8) {
        $upUser = $db->prepare("UPDATE users SET current_part = GREATEST(current_part, ?) WHERE id = ?");
        $upUser->execute([$nextPart, $user['id']]);
    }
}

// Cari sub-topik berikutnya untuk navigasi otomatis
$stmtNextSub = $db->prepare("
    SELECT sub_code FROM topics 
    WHERE module_id = ? AND order_index > ? 
    ORDER BY order_index ASC LIMIT 1
");
$stmtNextSub->execute([$chal['module_id'], $chal['order_index']]);
$nextSub = $stmtNextSub->fetch();

if ($nextSub) {
    $nextUrl = base_url('student/learn.php?part=' . $chal['part_number'] . '&sub=' . $nextSub['sub_code']);
} else {
    // Jika sub terakhir di part ini
    $nextPartNum = $chal['part_number'] + 1;
    if ($nextPartNum <= 8) {
        $nextUrl = base_url('student/learn.php?part=' . $nextPartNum);
    } else {
        $nextUrl = base_url('student/capstone.php');
    }
}

echo json_encode([
    'status' => 'success',
    'message' => 'Tantangan ' . $chal['sub_code'] . ' berhasil diselesaikan!',
    'xp_awarded' => $xpAwarded,
    'module_completed' => $isModuleCompleted,
    'next_url' => $nextUrl
]);
