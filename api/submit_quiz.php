<?php
/**
 * API Endpoint: Submit Quiz Response (Mendukung Multi-Soal & Single-Soal)
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

$db = get_db();
$passingScore = defined('PASSING_SCORE_QUIZ') ? PASSING_SCORE_QUIZ : 80;

// MODE A: Multi-Soal Bertahap (Array of Answers per Topic)
if (!empty($input['topic_id']) && isset($input['answers']) && is_array($input['answers'])) {
    $topicId = (int)$input['topic_id'];
    $answers = $input['answers']; // Format: [quiz_id => 'A', ...]

    $stmt = $db->prepare("
        SELECT q.*, t.module_id, t.title as topic_title, t.xp_reward as topic_xp,
               m.part_number, m.badge_name, m.badge_icon
        FROM quizzes q
        JOIN topics t ON t.id = q.topic_id
        JOIN modules m ON m.id = t.module_id
        WHERE q.topic_id = ?
        ORDER BY q.id ASC
    ");
    $stmt->execute([$topicId]);
    $quizzes = $stmt->fetchAll();

    if (empty($quizzes)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Soal kuis tidak ditemukan untuk topik ini.']);
        exit;
    }

    $totalQuestions = count($quizzes);
    $correctCount = 0;
    $results = [];

    foreach ($quizzes as $q) {
        $qId = (int)$q['id'];
        $userAns = strtoupper(trim((string)($answers[$qId] ?? '')));
        $isCorrect = ($userAns !== '' && $userAns === strtoupper($q['correct_option']));

        if ($isCorrect) {
            $correctCount++;
        }

        $results[] = [
            'quiz_id' => $qId,
            'question' => $q['question'],
            'user_answer' => $userAns,
            'correct_option' => $q['correct_option'],
            'is_correct' => $isCorrect,
            'explanation' => $q['explanation']
        ];
    }

    $score = $totalQuestions > 0 ? (int)round(($correctCount / $totalQuestions) * 100) : 0;
    $passed = ($score >= $passingScore);
    $xpAwarded = 0;

    if ($passed) {
        // Cek apakah baru pertama kali lulus kuis ini
        $stmtCheck = $db->prepare("SELECT quiz_score FROM student_progress WHERE user_id = ? AND topic_id = ?");
        $stmtCheck->execute([$user['id'], $topicId]);
        $prog = $stmtCheck->fetch();

        $isFirst = !($prog && $prog['quiz_score'] >= $passingScore);
        $xpAwarded = $isFirst ? (int)($quizzes[0]['topic_xp'] ?: 25) : 0;

        if ($isFirst && $xpAwarded > 0) {
            add_user_xp($user['id'], $xpAwarded);
        }

        $stmtUpsert = $db->prepare("
            INSERT INTO student_progress (user_id, topic_id, is_completed, quiz_score)
            VALUES (?, ?, 1, ?)
            ON DUPLICATE KEY UPDATE is_completed = 1, quiz_score = GREATEST(quiz_score, ?)
        ");
        $stmtUpsert->execute([$user['id'], $topicId, $score, $score]);

        if (!empty($quizzes[0]['badge_name'])) {
            award_badge($user['id'], $quizzes[0]['badge_name'], $quizzes[0]['badge_icon'] ?? 'fa-award');
        }
    }

    echo json_encode([
        'status' => 'completed',
        'passed' => $passed,
        'score' => $score,
        'passing_score' => $passingScore,
        'correct_count' => $correctCount,
        'total_questions' => $totalQuestions,
        'xp_awarded' => $xpAwarded,
        'results' => $results,
        'message' => $passed 
            ? "Luar biasa! Anda lulus kuis dengan skor {$score}/100." 
            : "Skor Anda {$score}/100 belum mencapai batas KKM ({$passingScore}). Pelajari kembali materi dan coba lagi!"
    ]);
    exit;
}

// MODE B: Single Soal Fallback (Kompatibilitas Sistem Lama)
$quizId = (int)($input['quiz_id'] ?? 0);
$selected = strtoupper(trim($input['answer'] ?? ''));

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
        'passed' => true,
        'score' => 100,
        'message' => 'Jawaban Benar!',
        'explanation' => $quiz['explanation'],
        'xp_awarded' => $xp
    ]);
} else {
    echo json_encode([
        'status' => 'incorrect',
        'passed' => false,
        'score' => 0,
        'message' => 'Jawaban belum tepat, silakan pelajari kembali materi dan coba lagi.',
        'explanation' => null,
        'xp_awarded' => 0
    ]);
}
