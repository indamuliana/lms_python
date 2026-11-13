<?php
/**
 * Utilitas Normalisasi & Perbaikan test_cases_json di Tabel challenges
 * Mengonversi baris baru mentah menjadi format JSON standar RFC 8259 (\n)
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/db.php';

$db = get_db();
$rows = $db->query("SELECT id, topic_id, test_cases_json FROM challenges ORDER BY id ASC")->fetchAll();

$fixedCount = 0;
echo "Memulai pemeriksaan 40 tantangan koding...\n";

foreach ($rows as $r) {
    $raw = $r['test_cases_json'];
    $decoded = json_decode($raw, true);

    if ($decoded === null) {
        // Perbaiki string unescaped newline di dalam kutip JSON
        $fixed = preg_replace_callback('/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/s', function($m) {
            return '"' . str_replace(["\r\n", "\r", "\n"], "\\n", $m[1]) . '"';
        }, $raw);

        $parsed = json_decode($fixed, true);
        if ($parsed !== null) {
            $cleanJson = json_encode($parsed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $stmt = $db->prepare("UPDATE challenges SET test_cases_json = ? WHERE id = ?");
            $stmt->execute([$cleanJson, $r['id']]);
            $fixedCount++;
            echo "✓ Challenge ID {$r['id']} (Topic {$r['topic_id']}): Berhasil dinormalisasi.\n";
        } else {
            echo "✗ Challenge ID {$r['id']}: Gagal dinormalisasi - " . json_last_error_msg() . "\n";
        }
    }
}

echo "\nSelesai! Total {$fixedCount} tantangan berhasil diperbaiki.\n";
