<?php
/**
 * API Endpoint: Unlock Challenge Hint (Penalti -3 XP Hadiah Tantangan)
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

$topicId = (int)($input['topic_id'] ?? 0);
if ($topicId <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Topic ID tidak valid.']);
    exit;
}

$db = get_db();

// Ambil data tantangan & petunjuk
$stmtChal = $db->prepare("SELECT id, topic_id, hint, xp_reward FROM challenges WHERE topic_id = ? LIMIT 1");
$stmtChal->execute([$topicId]);
$chal = $stmtChal->fetch();

if (!$chal || empty($chal['hint'])) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Petunjuk tidak tersedia untuk tantangan ini.']);
    exit;
}

$baseXp = (int)$chal['xp_reward'];
$penalty = 3;
$adjustedXp = max(0, $baseXp - $penalty);

// Jika siswa, simpan status hint_used = 1 ke database
if ($user['role'] !== 'guru') {
    $stmtUpsert = $db->prepare("
        INSERT INTO student_progress (user_id, topic_id, hint_used)
        VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE hint_used = 1
    ");
    $stmtUpsert->execute([$user['id'], $topicId]);
}

echo json_encode([
    'status' => 'success',
    'hint' => $chal['hint'],
    'base_xp' => $baseXp,
    'adjusted_xp' => $adjustedXp,
    'penalty' => $penalty,
    'is_teacher' => ($user['role'] === 'guru')
]);
