<?php
/**
 * Export clean database.sql with updated 40 topics and 120 quizzes
 */
require_once __DIR__ . '/../includes/db.php';

$db = get_db();

$out = "-- =====================================================================\n";
$out .= "-- PyLMS Database Schema & 40 Sub-Topik Kurikulum Python Harian\n";
$out .= "-- Dilengkapi Materi 5-Blok Edukatif & 120 Variasi Soal Kuis Interaktif\n";
$out .= "-- =====================================================================\n";
$out .= "SET FOREIGN_KEY_CHECKS = 0;\n";
$out .= "DROP TABLE IF EXISTS capstone_submissions;\n";
$out .= "DROP TABLE IF EXISTS student_badges;\n";
$out .= "DROP TABLE IF EXISTS student_progress;\n";
$out .= "DROP TABLE IF EXISTS challenges;\n";
$out .= "DROP TABLE IF EXISTS quizzes;\n";
$out .= "DROP TABLE IF EXISTS topics;\n";
$out .= "DROP TABLE IF EXISTS modules;\n";
$out .= "DROP TABLE IF EXISTS users;\n";
$out .= "SET FOREIGN_KEY_CHECKS = 1;\n\n";

$out .= "CREATE TABLE users (\n";
$out .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$out .= "    nis VARCHAR(30) UNIQUE NULL,\n";
$out .= "    username VARCHAR(50) UNIQUE NOT NULL,\n";
$out .= "    password_hash VARCHAR(255) NOT NULL,\n";
$out .= "    full_name VARCHAR(100) NOT NULL,\n";
$out .= "    nickname VARCHAR(50) NULL,\n";
$out .= "    role ENUM('guru', 'siswa') NOT NULL DEFAULT 'siswa',\n";
$out .= "    rombel VARCHAR(50) NULL,\n";
$out .= "    rayon VARCHAR(50) NULL,\n";
$out .= "    avatar VARCHAR(100) DEFAULT 'default.png',\n";
$out .= "    xp INT DEFAULT 0,\n";
$out .= "    current_part INT DEFAULT 1,\n";
$out .= "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
$out .= "    INDEX idx_rombel (rombel),\n";
$out .= "    INDEX idx_xp (xp DESC)\n";
$out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$out .= "CREATE TABLE modules (\n";
$out .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$out .= "    part_number INT NOT NULL UNIQUE,\n";
$out .= "    title VARCHAR(150) NOT NULL,\n";
$out .= "    slug VARCHAR(150) NOT NULL,\n";
$out .= "    description TEXT,\n";
$out .= "    badge_name VARCHAR(100),\n";
$out .= "    badge_icon VARCHAR(50),\n";
$out .= "    order_index INT NOT NULL\n";
$out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$out .= "CREATE TABLE topics (\n";
$out .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$out .= "    module_id INT NOT NULL,\n";
$out .= "    sub_code VARCHAR(10) NOT NULL,\n";
$out .= "    title VARCHAR(150) NOT NULL,\n";
$out .= "    slug VARCHAR(150) NOT NULL,\n";
$out .= "    content_html LONGTEXT NOT NULL,\n";
$out .= "    starter_code TEXT,\n";
$out .= "    order_index INT NOT NULL,\n";
$out .= "    xp_reward INT DEFAULT 20,\n";
$out .= "    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,\n";
$out .= "    INDEX idx_subcode (sub_code)\n";
$out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$out .= "CREATE TABLE quizzes (\n";
$out .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$out .= "    topic_id INT NOT NULL,\n";
$out .= "    question TEXT NOT NULL,\n";
$out .= "    option_a VARCHAR(255) NOT NULL,\n";
$out .= "    option_b VARCHAR(255) NOT NULL,\n";
$out .= "    option_c VARCHAR(255) NOT NULL,\n";
$out .= "    option_d VARCHAR(255) NOT NULL,\n";
$out .= "    correct_option CHAR(1) NOT NULL,\n";
$out .= "    explanation TEXT,\n";
$out .= "    xp_reward INT DEFAULT 15,\n";
$out .= "    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE\n";
$out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$out .= "CREATE TABLE challenges (\n";
$out .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$out .= "    topic_id INT NOT NULL,\n";
$out .= "    title VARCHAR(150) NOT NULL,\n";
$out .= "    instructions TEXT NOT NULL,\n";
$out .= "    starter_code TEXT,\n";
$out .= "    test_cases_json TEXT NOT NULL,\n";
$out .= "    hint TEXT,\n";
$out .= "    xp_reward INT DEFAULT 50,\n";
$out .= "    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE\n";
$out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$out .= "CREATE TABLE student_progress (\n";
$out .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$out .= "    user_id INT NOT NULL,\n";
$out .= "    topic_id INT NOT NULL,\n";
$out .= "    learn_completed TINYINT(1) DEFAULT 0,\n";
$out .= "    quiz_completed TINYINT(1) DEFAULT 0,\n";
$out .= "    quiz_score INT DEFAULT 0,\n";
$out .= "    challenge_completed TINYINT(1) DEFAULT 0,\n";
$out .= "    submitted_code TEXT,\n";
$out .= "    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n";
$out .= "    UNIQUE KEY uq_user_topic (user_id, topic_id),\n";
$out .= "    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,\n";
$out .= "    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE\n";
$out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$out .= "CREATE TABLE student_badges (\n";
$out .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$out .= "    user_id INT NOT NULL,\n";
$out .= "    badge_name VARCHAR(100) NOT NULL,\n";
$out .= "    badge_icon VARCHAR(50) NOT NULL,\n";
$out .= "    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
$out .= "    UNIQUE KEY uq_user_badge (user_id, badge_name),\n";
$out .= "    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE\n";
$out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$out .= "CREATE TABLE capstone_submissions (\n";
$out .= "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
$out .= "    user_id INT NOT NULL UNIQUE,\n";
$out .= "    track_choice VARCHAR(100) NOT NULL,\n";
$out .= "    source_code TEXT NOT NULL,\n";
$out .= "    notes TEXT,\n";
$out .= "    teacher_score INT NULL,\n";
$out .= "    teacher_feedback TEXT NULL,\n";
$out .= "    status ENUM('pending', 'approved', 'revision') DEFAULT 'pending',\n";
$out .= "    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
$out .= "    reviewed_at TIMESTAMP NULL,\n";
$out .= "    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE\n";
$out .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

$out .= "-- User Guru & Siswa Awal\n";
$out .= "INSERT INTO users (nis, username, password_hash, full_name, role, rombel, rayon, xp, current_part)\n";
$out .= "VALUES (NULL, 'guru', 'password123', 'Guru Pembimbing Python', 'guru', NULL, NULL, 500, 8);\n\n";

// Loop modules
$modules = $db->query("SELECT * FROM modules ORDER BY order_index ASC")->fetchAll();
foreach ($modules as $m) {
    $out .= "-- ---------------------------------------------------------------------\n";
    $out .= "-- MODULE {$m['part_number']}: {$m['title']}\n";
    $out .= "-- ---------------------------------------------------------------------\n";
    $descEsc = $db->quote($m['description']);
    $titleEsc = $db->quote($m['title']);
    $badgeNameEsc = $db->quote($m['badge_name']);
    $badgeIconEsc = $db->quote($m['badge_icon']);
    $out .= "INSERT INTO modules (part_number, title, slug, description, badge_name, badge_icon, order_index) VALUES ({$m['part_number']}, {$titleEsc}, '{$m['slug']}', {$descEsc}, {$badgeNameEsc}, {$badgeIconEsc}, {$m['order_index']});\n";

    // Topics for this module
    $topics = $db->prepare("SELECT * FROM topics WHERE module_id = ? ORDER BY order_index ASC");
    $topics->execute([$m['id']]);
    $topicRows = $topics->fetchAll();

    foreach ($topicRows as $t) {
        $tTitleEsc = $db->quote($t['title']);
        $tContentEsc = $db->quote($t['content_html']);
        $tStarterEsc = $db->quote($t['starter_code']);
        
        $out .= "INSERT INTO topics (id, module_id, sub_code, title, slug, content_html, starter_code, order_index, xp_reward) VALUES ({$t['id']}, {$t['module_id']}, '{$t['sub_code']}', {$tTitleEsc}, '{$t['slug']}', {$tContentEsc}, {$tStarterEsc}, {$t['order_index']}, {$t['xp_reward']});\n";

        // Quizzes for this topic
        $quizzes = $db->prepare("SELECT * FROM quizzes WHERE topic_id = ? ORDER BY id ASC");
        $quizzes->execute([$t['id']]);
        $quizRows = $quizzes->fetchAll();

        foreach ($quizRows as $q) {
            $qQuestionEsc = $db->quote($q['question']);
            $qOptAEsc = $db->quote($q['option_a']);
            $qOptBEsc = $db->quote($q['option_b']);
            $qOptCEsc = $db->quote($q['option_c']);
            $qOptDEsc = $db->quote($q['option_d']);
            $qExplEsc = $db->quote($q['explanation']);
            $out .= "INSERT INTO quizzes (topic_id, question, option_a, option_b, option_c, option_d, correct_option, explanation, xp_reward) VALUES ({$t['id']}, {$qQuestionEsc}, {$qOptAEsc}, {$qOptBEsc}, {$qOptCEsc}, {$qOptDEsc}, '{$q['correct_option']}', {$qExplEsc}, {$q['xp_reward']});\n";
        }

        // Challenges for this topic
        $challenges = $db->prepare("SELECT * FROM challenges WHERE topic_id = ? ORDER BY id ASC");
        $challenges->execute([$t['id']]);
        $chRows = $challenges->fetchAll();

        foreach ($chRows as $c) {
            $cTitleEsc = $db->quote($c['title']);
            $cInstEsc = $db->quote($c['instructions']);
            $cStarterEsc = $db->quote($c['starter_code']);
            $cJsonEsc = $db->quote($c['test_cases_json']);
            $cHintEsc = $db->quote($c['hint']);
            $out .= "INSERT INTO challenges (topic_id, title, instructions, starter_code, test_cases_json, hint, xp_reward) VALUES ({$t['id']}, {$cTitleEsc}, {$cInstEsc}, {$cStarterEsc}, {$cJsonEsc}, {$cHintEsc}, {$c['xp_reward']});\n";
        }
    }
    $out .= "\n";
}

file_put_contents(__DIR__ . '/../database.sql', $out);
echo "Berhasil mengekspor database.sql (" . strlen($out) . " bytes).\n";
