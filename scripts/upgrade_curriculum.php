<?php
/**
 * Script Upgrade Kurikulum PyLMS
 * Mengunggah Materi 5-Blok Edukatif, 120 Soal Kuis (3 Soal per Sub-Topik),
 * dan Normalisasi 40 Tantangan Koding Multi-Baris.
 * 
 * 100% AMAN UNTUK DATABASE HOSTING BERJALAN:
 * - TIDAK MENGHAPUS / MERUBAH TABEL users (Akun siswa, nis, password, xp aman).
 * - TIDAK MENGHAPUS / MERUBAH TABEL student_progress (Progres belajar & nilai siswa aman).
 * - TIDAK MENGHAPUS / MERUBAH TABEL student_badges (Lencana siswa aman).
 * - TIDAK MENGHAPUS / MERUBAH TABEL capstone_submissions (Proyek akhir siswa aman).
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Jika diakses melalui Web Browser, pastikan hanya Guru yang login yang bisa menjalankan
$isWeb = (php_sapi_name() !== 'cli');
if ($isWeb) {
    require_teacher();
    echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><title>Upgrade Kurikulum Aman - PyLMS</title>";
    echo "<style>body{background:#0b0f19;color:#e2e8f0;font-family:ui-monospace,Menlo,monospace;padding:24px;} pre{background:#020617;padding:20px;border-radius:16px;border:1px solid #1e293b;overflow-x:auto;line-height:1.6;font-size:13px;color:#38bdf8;} .btn{display:inline-block;margin-top:20px;padding:10px 20px;background:#f59e0b;color:#0f172a;text-decoration:none;border-radius:10px;font-weight:bold;font-size:13px;}</style></head><body>";
    echo "<pre>";
}

echo "========================================================\n";
echo "       MEMULAI UPGRADE KURIKULUM & KUIS PYLMS           \n";
echo "  (Mode Pembaruan Aman: Data Siswa & Progres Tetap Utuh)\n";
echo "========================================================\n";

$db = get_db();
$db->beginTransaction();

try {
    $totalTopicsUpdated = 0;
    $totalQuizzesInserted = 0;
    $totalChallengesFixed = 0;

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

    // 1. UPDATE 40 SUB-TOPIK MATERI & 120 KUIS
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

            // Update konten materi (ID topik tidak berubah, progres siswa aman)
            $stmtUpdateTopic->execute([
                ':title' => trim($topicInfo['title']),
                ':content_html' => trim($topicInfo['content_html']),
                ':sub_code' => $subCode
            ]);
            $totalTopicsUpdated++;

            // Refresh kuis untuk topik ini
            $stmtDeleteQuizzes->execute([':topic_id' => $topicId]);

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

    // 2. NORMALISASI & UPDATE TEST CASES PADA TABEL CHALLENGES
    echo "\n[+] Memeriksa & Menormalisasi Tantangan Koding Multi-Baris...\n";
    $chRows = $db->query("SELECT id, topic_id, test_cases_json FROM challenges ORDER BY id ASC")->fetchAll();
    $stmtUpCh = $db->prepare("UPDATE challenges SET test_cases_json = ? WHERE id = ?");

    foreach ($chRows as $r) {
        $raw = $r['test_cases_json'];
        $decoded = json_decode($raw, true);

        if ($decoded === null) {
            $fixed = preg_replace_callback('/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/s', function($m) {
                return '"' . str_replace(["\r\n", "\r", "\n"], "\\n", $m[1]) . '"';
            }, $raw);

            $parsed = json_decode($fixed, true);
            if ($parsed !== null) {
                $cleanJson = json_encode($parsed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $stmtUpCh->execute([$cleanJson, $r['id']]);
                $totalChallengesFixed++;
                echo "  - Challenge ID {$r['id']} (Topik {$r['topic_id']}): Diperbaiki ke JSON valid.\n";
            }
        }
    }

    $db->commit();
    echo "\n========================================================\n";
    echo "  UPGRADE KURIKULUM BERHASIL TOTAL!\n";
    echo "  - Total Sub-Topik Diupdate : {$totalTopicsUpdated} dari 40\n";
    echo "  - Total Soal Kuis Terpasang: {$totalQuizzesInserted} dari 120\n";
    echo "  - Tantangan Dinormalisasi  : {$totalChallengesFixed} tantangan\n";
    echo "  - Data Siswa & Progres     : 100% AMAN (Tidak disentuh)\n";
    echo "========================================================\n";

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "\n[ERROR FATAL] " . $e->getMessage() . "\n";
    if ($isWeb) {
        echo "</pre></body></html>";
    }
    exit(1);
}

if ($isWeb) {
    echo "</pre>";
    echo "<a href='" . base_url('teacher/curriculum.php') . "' class='btn'>← Kembali ke Panel Manajemen Kurikulum</a>";
    echo "</body></html>";
}
