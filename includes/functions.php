<?php
/**
 * Helper Functions - PyLMS
 */
require_once __DIR__ . '/db.php';

/**
 * Sanitasi string input HTML
 */
function sanitize($data) {
    return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Konfigurasi 8 Kategori Rank Mobile Legends & Ambang Batas XP
 */
function get_ml_ranks() {
    return [
        ['tier' => 1, 'name' => 'Warrior',        'min_xp' => 0,    'icon' => 'fa-shield-halved', 'badge_class' => 'bg-gradient-to-r from-amber-700 via-amber-800 to-amber-900 text-white border-amber-500/60 shadow-sm font-black'],
        ['tier' => 2, 'name' => 'Elite',          'min_xp' => 200,  'icon' => 'fa-shield',        'badge_class' => 'bg-gradient-to-r from-sky-500 to-blue-600 text-white border-sky-300 shadow-sm font-black'],
        ['tier' => 3, 'name' => 'Master',         'min_xp' => 450,  'icon' => 'fa-award',         'badge_class' => 'bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 text-slate-950 border-yellow-200 shadow-sm font-black'],
        ['tier' => 4, 'name' => 'Grandmaster',    'min_xp' => 750,  'icon' => 'fa-gem',           'badge_class' => 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white border-emerald-300 shadow-sm font-black'],
        ['tier' => 5, 'name' => 'Epic',           'min_xp' => 1100, 'icon' => 'fa-chess-rook',   'badge_class' => 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white border-purple-300 shadow-sm font-black'],
        ['tier' => 6, 'name' => 'Legend',         'min_xp' => 1500, 'icon' => 'fa-fire',         'badge_class' => 'bg-gradient-to-r from-rose-600 to-red-600 text-white border-rose-300 shadow-sm font-black'],
        ['tier' => 7, 'name' => 'Mythic',         'min_xp' => 1850, 'icon' => 'fa-crown',        'badge_class' => 'bg-gradient-to-r from-orange-500 via-amber-500 to-orange-600 text-white border-amber-200 shadow-md font-black'],
        ['tier' => 8, 'name' => 'Mythical Honor', 'min_xp' => 2100, 'icon' => 'fa-trophy',       'badge_class' => 'bg-gradient-to-r from-red-600 via-rose-600 to-amber-400 text-white border-amber-200 shadow-lg font-black tracking-wide'],
    ];
}

/**
 * Dapatkan informasi lengkap Rank ML berdasarkan akumulasi XP
 */
function get_rank_info($xp) {
    $xp = max(0, (int)$xp);
    $ranks = get_ml_ranks();
    $currentRank = $ranks[0];
    $nextRank = $ranks[1] ?? null;

    for ($i = count($ranks) - 1; $i >= 0; $i--) {
        if ($xp >= $ranks[$i]['min_xp']) {
            $currentRank = $ranks[$i];
            $nextRank = $ranks[$i + 1] ?? null;
            break;
        }
    }

    if ($nextRank) {
        $span = max(1, $nextRank['min_xp'] - $currentRank['min_xp']);
        $progress = max(0, min(100, round((($xp - $currentRank['min_xp']) / $span) * 100)));
        $nextRankName = $nextRank['name'];
        $xpNeeded = $nextRank['min_xp'] - $xp;
        $targetXp = $nextRank['min_xp'];
    } else {
        $progress = 100;
        $nextRankName = null;
        $xpNeeded = 0;
        $targetXp = $currentRank['min_xp'];
    }

    return array_merge($currentRank, [
        'xp' => $xp,
        'next_rank_name' => $nextRankName,
        'next_rank_xp' => $targetXp,
        'xp_needed' => $xpNeeded,
        'progress' => $progress,
        'is_max' => ($nextRank === null)
    ]);
}

/**
 * Hitung Kategori Rank Mobile Legends berdasarkan akumulasi XP
 */
function calculate_level($xp) {
    $info = get_rank_info($xp);
    return $info['name'];
}

function xp_for_next_level($current_level) {
    return 0;
}

/**
 * Ambil data profil lengkap pengguna beserta XP dan Rank Mobile Legends
 */
function get_user_profile($user_id) {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if ($user) {
        $rankInfo = get_rank_info($user['xp']);
        $user['level'] = $rankInfo['name'];
        $user['tier'] = $rankInfo['tier'];
        $user['rank_icon'] = $rankInfo['icon'];
        $user['rank_badge'] = $rankInfo['badge_class'];
        $user['next_rank_name'] = $rankInfo['next_rank_name'];
        $user['next_level_xp'] = $rankInfo['next_rank_xp'];
        $user['level_progress'] = $rankInfo['progress'];
        $user['is_max_rank'] = $rankInfo['is_max'];
        $user['xp_needed'] = $rankInfo['xp_needed'];
    }
    return $user;
}

/**
 * Tambah XP ke user dan cek kenaikan level
 */
function add_user_xp($user_id, $xp_amount) {
    $db = get_db();
    $stmt = $db->prepare("UPDATE users SET xp = xp + ? WHERE id = ?");
    $stmt->execute([$xp_amount, $user_id]);
}

/**
 * Berikan badge jika belum dimiliki
 */
function award_badge($user_id, $badge_name, $badge_icon) {
    $db = get_db();
    $check = $db->prepare("SELECT id FROM student_badges WHERE user_id = ? AND badge_name = ?");
    $check->execute([$user_id, $badge_name]);
    if (!$check->fetch()) {
        $stmt = $db->prepare("INSERT INTO student_badges (user_id, badge_name, badge_icon) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $badge_name, $badge_icon]);
        return true;
    }
    return false;
}

/**
 * Ambil daftar badge siswa
 */
function get_user_badges($user_id) {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM student_badges WHERE user_id = ? ORDER BY earned_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

/**
 * Ambil semua sub-topik dalam sebuah module beserta status pengerjaan siswa
 */
function get_module_subtopics($module_id, $user_id) {
    $db = get_db();
    $stmt = $db->prepare("
        SELECT t.*, 
               COALESCE(sp.is_completed, 0) as is_completed,
               COALESCE(sp.challenge_completed, 0) as challenge_completed,
               COALESCE(sp.quiz_score, 0) as quiz_score,
               sp.last_submitted_code
        FROM topics t
        LEFT JOIN student_progress sp ON sp.topic_id = t.id AND sp.user_id = ?
        WHERE t.module_id = ?
        ORDER BY t.order_index ASC
    ");
    $stmt->execute([$user_id, $module_id]);
    return $stmt->fetchAll();
}

/**
 * Cek apakah sebuah Part kurikulum terbuka untuk siswa tertentu.
 * Aturan:
 * Part 1 selalu terbuka.
 * Part N terbuka jika seluruh sub-topik Part N-1 sudah 100% selesai
 * (atau jika Guru telah membuka kuncinya secara manual di users.current_part).
 */
function is_part_unlocked($user_id, $part_number) {
    if ($part_number <= 1) return true;
    
    $db = get_db();
    
    // Cek role user atau override di profil user (Guru otomatis terbuka semua part)
    $stmtUser = $db->prepare("SELECT role, current_part FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $user = $stmtUser->fetch();
    if ($user && ($user['role'] === 'guru' || $user['current_part'] >= $part_number)) {
        return true;
    }
    
    // Cek apakah Part sebelumnya sudah komplit seluruh sub-topiknya
    $prevPart = $part_number - 1;
    $stmtPrev = $db->prepare("
        SELECT COUNT(t.id) as total_topics,
               SUM(CASE WHEN sp.is_completed = 1 THEN 1 ELSE 0 END) as completed_topics
        FROM modules m
        JOIN topics t ON t.module_id = m.id
        LEFT JOIN student_progress sp ON sp.topic_id = t.id AND sp.user_id = ?
        WHERE m.part_number = ?
    ");
    $stmtPrev->execute([$user_id, $prevPart]);
    $res = $stmtPrev->fetch();
    
    if ($res && $res['total_topics'] > 0 && $res['completed_topics'] >= $res['total_topics']) {
        // Auto-update current_part user jika memenuhi syarat 100% tuntas
        $up = $db->prepare("UPDATE users SET current_part = GREATEST(current_part, ?) WHERE id = ?");
        $up->execute([$part_number, $user_id]);
        return true;
    }
    
    return false;
}

/**
 * Dapatkan URL foto avatar pengguna (atau null jika memakai avatar bawaan)
 */
function get_user_avatar($avatarFilename) {
    if (!empty($avatarFilename) && $avatarFilename !== 'default.png') {
        $path = __DIR__ . '/../uploads/avatars/' . $avatarFilename;
        if (file_exists($path)) {
            return base_url('uploads/avatars/' . $avatarFilename);
        }
    }
    return null;
}

/**
 * Flash message helper untuk notifikasi UX
 */
function set_flash($type, $message) {
    $_SESSION['flash_msg'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash() {
    if (isset($_SESSION['flash_msg'])) {
        $msg = $_SESSION['flash_msg'];
        unset($_SESSION['flash_msg']);
        return $msg;
    }
    return null;
}
