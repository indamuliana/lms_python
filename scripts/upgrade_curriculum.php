<?php
/**
 * Script Upgrade Kurikulum PyLMS
 * Mengunggah Materi 5-Blok Edukatif dan 120 Soal Kuis (3 Soal per Sub-Topik)
 * untuk seluruh 40 Topik (Part 1 s/d Part 8).
 */

require_once __DIR__ . '/../includes/db.php';

echo "========================================================\n";
echo "       MEMULAI UPGRADE KURIKULUM & KUIS PYLMS           \n";
echo "========================================================\n";

$db = get_db();
$db->beginTransaction();

try {
    $totalTopicsUpdated = 0;
    $totalQuizzesInserted = 0;

    $stmtUpdateTopic = $db->prepare("
        UPDATE topics 
        SET title = :title, content_html = :content_html 
        WHERE sub_code = :sub_code
    ");

    $stmtFindTopic = $db->prepare("SELECT id FROM topics WHERE sub_code = :sub_code LIMIT 1");
    $stmtDeleteQuizzes = $db->prepare("DELETE FROM quizzes WHERE topic_id = :topic_id");
    
    $stmtInsertQuiz = $db->prepare("
        INSERT INTO quizzes 
        (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward)
        VALUES 
        (:topic_id, :question, :option_a, :option_b, :option_c, :option_d, :correct_option, :explanation, :xp_reward)
    ");

    for ($part = 1; $part <= 8; $part++) {
        $dataFile = __DIR__ . "/data_part{$part}.php";
        if (!file_exists($dataFile)) {
            throw new Exception("File data tidak ditemukan: data_part{$part}.php");
        }

        $partData = require $dataFile;
        echo "\n[+] Memproses PART {$part} (" . count($partData) . " Sub-Topik)...\n";

        foreach ($partData as $subCode => $topicInfo) {
            $stmtFindTopic->execute([':sub_code' => $subCode]);
            $topic = $stmtFindTopic->fetch();

            if (!$topic) {
                throw new Exception("Sub-topik {$subCode} tidak ditemukan di tabel topics!");
            }

            $topicId = (int)$topic['id'];

            // Update konten materi
            $stmtUpdateTopic->execute([
                ':title' => trim($topicInfo['title']),
                ':content_html' => trim($topicInfo['content_html']),
                ':sub_code' => $subCode
            ]);
            $totalTopicsUpdated++;

            // Hapus kuis lama untuk topik ini
            $stmtDeleteQuizzes->execute([':topic_id' => $topicId]);

            // Masukkan kuis baru
            $insertedForTopic = 0;
            foreach ($topicInfo['quizzes'] as $q) {
                $stmtInsertQuiz->execute([
                    ':topic_id' => $topicId,
                    ':question' => trim($q['question']),
                    ':option_a' => trim($q['option_a']),
                    ':option_b' => trim($q['option_b']),
                    ':option_c' => trim($q['option_c']),
                    ':option_d' => trim($q['option_d']),
                    ':correct_option' => strtoupper(trim($q['correct_option'])),
                    ':explanation' => trim($q['explanation']),
                    ':xp_reward' => (int)($q['xp_reward'] ?? 15)
                ]);
                $insertedForTopic++;
                $totalQuizzesInserted++;
            }

            echo "  - Sub-topik {$subCode}: Materi diupdate, {$insertedForTopic} soal kuis dimasukkan.\n";
        }
    }

    $db->commit();
    echo "\n========================================================\n";
    echo "  UPGRADE SUKSES!\n";
    echo "  - Total Sub-Topik Diupdate : {$totalTopicsUpdated} dari 40\n";
    echo "  - Total Soal Kuis Terpasang: {$totalQuizzesInserted} dari 120\n";
    echo "========================================================\n";

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "\n[ERROR FATAL] " . $e->getMessage() . "\n";
    exit(1);
}
