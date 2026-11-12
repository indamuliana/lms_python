<?php
/**
 * API Endpoint: Submit Quiz Response
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
    echo json_encode(['status' => 'error', 'message' => 'Input tidak valid.']);
    exit;
}

$csrf = $input['csrf_token'] ?? '';
if (!verify_csrf($csrf)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'CSRF Token tidak valid.']);
    exit;
}

$quizId = (int)($input['quiz_id'] ?? 0);
$selected = strtoupper(trim($input['answer'] ?? ''));

$db = get_db();
$stmt = $db->prepare("
    SELECT q.*, t.module_id, m.part_number, m.badge_name, m.badge_icon
    FROM quizzes q
    JOIN topics t ON t.id = q.topic_id
    JOIN modules m ON m.id = t.module_id
    WHERE q.id = ?
    LIMIT 1
");
$stmt->execute([$quizId]);
$quiz = $stmt->fetch();

if (!$quiz) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Kuis tidak ditemukan.']);
    exit;
}

if ($selected === $quiz['correct_option']) {
    $stmtCheck = $db->prepare("SELECT quiz_score FROM student_progress WHERE user_id = ? AND topic_id = ?");
    $stmtCheck->execute([$user['id'], $quiz['topic_id']]);
    $prog = $stmtCheck->fetch();

    $isFirst = !($prog && $prog['quiz_score'] > 0);
    $xp = $isFirst ? (int)$quiz['xp_reward'] : 0;

    if ($isFirst) {
        add_user_xp($user['id'], $xp);
    }

    $stmtUpsert = $db->prepare("
        INSERT INTO student_progress (user_id, topic_id, is_completed, quiz_score)
        VALUES (?, ?, 1, 100)
        ON DUPLICATE KEY UPDATE is_completed = 1, quiz_score = 100
    ");
    $stmtUpsert->execute([$user['id'], $quiz['topic_id']]);

    award_badge($user['id'], $quiz['badge_name'], $quiz['badge_icon']);

    echo json_encode([
        'status' => 'correct',
        'message' => 'Jawaban Benar!',
        'explanation' => $quiz['explanation'],
        'xp_awarded' => $xp
    ]);
} else {
    echo json_encode([
        'status' => 'incorrect',
        'message' => 'Jawaban belum tepat, silakan pelajari kembali materi dan coba lagi.',
        'explanation' => null,
        'xp_awarded' => 0
    ]);
}
