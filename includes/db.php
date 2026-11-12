<?php
/**
 * Koneksi Database Menggunakan PDO MySQL
 */
require_once __DIR__ . '/../config.php';

function get_db() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Fallback fleksibel untuk lokal dev (coba alternatif password 'root' atau '')
            $altPass = (DB_PASS === '') ? 'root' : '';
            try {
                $pdo = new PDO($dsn, DB_USER, $altPass, $options);
            } catch (PDOException $e2) {
                // Tampilan ramah bila database belum disetup di cPanel / lokal
                die('<div style="font-family:sans-serif;padding:30px;max-width:600px;margin:40px auto;border:1px solid #f87171;background:#fef2f2;border-radius:12px;color:#991b1b;">'
                    . '<h2 style="margin-top:0;">Gagal Terhubung ke Database MySQL</h2>'
                    . '<p>Pastikan MySQL aktif dan database <code>' . DB_NAME . '</code> sudah dibuat dengan mengimpor <code>database.sql</code>.</p>'
                    . '<p style="font-size:13px;color:#7f1d1d;"><strong>Pesan Error:</strong> ' . htmlspecialchars($e2->getMessage()) . '</p>'
                    . '</div>');
            }
        }
    }
    return $pdo;
}
