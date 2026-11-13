<?php
/**
 * PyLMS - Konfigurasi Utama Sistem
 * Cocok untuk Shared Hosting cPanel tanpa symbolic link.
 */

// Konfigurasi Database MySQL (Sesuaikan dengan cPanel MySQL Database Anda)
define('DB_HOST', 'localhost');
define('DB_NAME', 'lms_python');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Konfigurasi Aplikasi
define('APP_NAME', 'PyLMS Python Academy');
define('APP_TAGLINE', 'Platform Belajar Python Interaktif Ramah HP');
define('PASSING_SCORE_QUIZ', 80); // Nilai minimal kuis untuk buka part berikutnya

// Mulai sesi PHP jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    // Pengaturan cookie sesi yang aman
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();
}

/**
 * Helper untuk menentukan Base URL aplikasi secara dinamis
 */
function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Tentukan direktori root relatif dari dokumen root
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    
    // Normalisasi jika subfolder (misal /student, /teacher, /scripts, dll)
    $cleanPath = preg_replace('/(\/(student|teacher|api|includes|assets.*|scripts))$/', '', $scriptDir);
    $baseUrl = rtrim($protocol . $host . $cleanPath, '/') . '/';
    
    return $baseUrl . ltrim($path, '/');
}
