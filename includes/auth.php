<?php
/**
 * Autentikasi & Otorisasi Pengguna
 */
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function current_user() {
    static $cachedUser = null;
    if ($cachedUser !== null) {
        return $cachedUser;
    }
    if (!empty($_SESSION['user_id'])) {
        $profile = get_user_profile($_SESSION['user_id']);
        if ($profile) {
            $cachedUser = $profile;
            return $profile;
        } else {
            // Sesi menyimpan user_id yang sudah tidak ada di database (misal DB di-reset)
            logout();
        }
    }
    return null;
}

function is_logged_in() {
    return current_user() !== null;
}

function require_login() {
    if (!is_logged_in()) {
        set_flash('warning', 'Sesi telah berakhir atau belum login. Silakan login terlebih dahulu.');
        header('Location: ' . base_url('login.php'));
        exit;
    }
}

function require_teacher() {
    require_login();
    $user = current_user();
    if (!$user || $user['role'] !== 'guru') {
        set_flash('danger', 'Akses ditolak. Halaman ini hanya untuk Guru / Administrator.');
        header('Location: ' . base_url('student/dashboard.php'));
        exit;
    }
}

function require_student() {
    require_login();
    $user = current_user();
    if (!$user || $user['role'] !== 'siswa') {
        if ($user && $user['role'] === 'guru') {
            header('Location: ' . base_url('teacher/dashboard.php'));
            exit;
        }
    }
}

/**
 * Autentikasi Pengguna: Siswa bisa login via NIS atau Username, Guru via Username
 */
function attempt_login($identifier, $password) {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR nis = ? LIMIT 1");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();
    
    if (!$user) {
        return false;
    }
    
    // Verifikasi password hash bcrypt, atau fallback untuk demo/seed awal
    $valid = password_verify($password, $user['password_hash']);
    if (!$valid && $user['password_hash'] === $password) {
        // Otomatis upgrade password raw/demo ke hash bcrypt yang aman
        $newHash = password_hash($password, PASSWORD_BCRYPT);
        $up = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $up->execute([$newHash, $user['id']]);
        $valid = true;
    }
    
    if ($valid) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        return $user;
    }
    
    return false;
}

/**
 * CSRF Protection
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}

function logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies") && !headers_sent()) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
