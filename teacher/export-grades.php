<?php
/**
 * Export Buku Nilai Siswa ke File CSV (Excel Compatible) - 40 Sub-Part
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$db = get_db();

$selectedRombel = trim($_GET['rombel'] ?? '');
$selectedRayon = trim($_GET['rayon'] ?? '');
$searchKeyword = trim($_GET['q'] ?? '');

$where = ["role = 'siswa'"];
$params = [];

if (!empty($selectedRombel)) {
    $where[] = "rombel = ?";
    $params[] = $selectedRombel;
}
if (!empty($selectedRayon)) {
    $where[] = "rayon = ?";
    $params[] = $selectedRayon;
}
if (!empty($searchKeyword)) {
    $where[] = "(full_name LIKE ? OR nis LIKE ?)";
    $params[] = "%{$searchKeyword}%";
    $params[] = "%{$searchKeyword}%";
}

$whereSql = implode(' AND ', $where);
$stmtStudents = $db->prepare("SELECT * FROM users WHERE {$whereSql} ORDER BY rombel ASC, full_name ASC");
$stmtStudents->execute($params);
$students = $stmtStudents->fetchAll();

// Ambil data progres sub-part
$allProgress = [];
$capstoneMap = [];

if (!empty($students)) {
    $studentIds = array_column($students, 'id');
    $inClause = implode(',', array_fill(0, count($studentIds), '?'));
    
    $stmtProg = $db->prepare("
        SELECT sp.user_id, m.part_number, 
               COUNT(sp.id) as completed_subparts
        FROM student_progress sp
        JOIN topics t ON t.id = sp.topic_id
        JOIN modules m ON m.id = t.module_id
        WHERE sp.user_id IN ({$inClause}) AND sp.is_completed = 1
        GROUP BY sp.user_id, m.part_number
    ");
    $stmtProg->execute($studentIds);
    $rows = $stmtProg->fetchAll();
    
    foreach ($rows as $r) {
        $allProgress[$r['user_id']][$r['part_number']] = $r['completed_subparts'];
    }

    $stmtCap = $db->prepare("SELECT user_id, status, teacher_score FROM capstone_submissions WHERE user_id IN ({$inClause})");
    $stmtCap->execute($studentIds);
    foreach ($stmtCap->fetchAll() as $c) {
        $capstoneMap[$c['user_id']] = $c;
    }
}

$filename = "Buku_Nilai_Python_40SubPart_" . (!empty($selectedRombel) ? preg_replace('/[^A-Za-z0-9_-]/', '_', $selectedRombel) : "Semua_Rombel") . "_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($output, [
    'No',
    'NIS',
    'Nama Siswa',
    'Rombel',
    'Rayon',
    'Part 1 (Pengenalan)',
    'Part 2 (Operator)',
    'Part 3 (Percabangan)',
    'Part 4 (Perulangan)',
    'Part 5 (Struktur Data)',
    'Part 6 (Fungsi)',
    'Part 7 (File & Error)',
    'Part 8 (Capstone Status)',
    'Nilai Capstone',
    'Total XP',
    'Rank Siswa (ML)'
]);

$no = 1;
foreach ($students as $st) {
    $stProg = $allProgress[$st['id']] ?? [];
    $cap = $capstoneMap[$st['id']] ?? null;

    $row = [
        $no++,
        $st['nis'] ?? '',
        $st['full_name'],
        $st['rombel'] ?? '',
        $st['rayon'] ?? '',
        isset($stProg[1]) ? $stProg[1] . '/5 Tuntas' : '0/5',
        isset($stProg[2]) ? $stProg[2] . '/5 Tuntas' : '0/5',
        isset($stProg[3]) ? $stProg[3] . '/5 Tuntas' : '0/5',
        isset($stProg[4]) ? $stProg[4] . '/5 Tuntas' : '0/5',
        isset($stProg[5]) ? $stProg[5] . '/5 Tuntas' : '0/5',
        isset($stProg[6]) ? $stProg[6] . '/5 Tuntas' : '0/5',
        isset($stProg[7]) ? $stProg[7] . '/5 Tuntas' : '0/5',
        $cap ? strtoupper($cap['status']) : 'BELUM SUBMIT',
        $cap && $cap['teacher_score'] !== null ? $cap['teacher_score'] : '-',
        $st['xp'],
        calculate_level($st['xp'])
    ];

    fputcsv($output, $row);
}

fclose($output);
exit;
