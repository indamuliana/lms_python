<?php
/**
 * Halaman Logout Sesi
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth.php';

logout();
set_flash('info', 'Anda telah berhasil keluar dari akun.');
header('Location: ' . base_url('login.php'));
exit;
