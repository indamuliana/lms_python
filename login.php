<?php
/**
 * Halaman Login Siswa & Guru
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/auth.php';

// Jika sudah login, langsung arahkan ke dashboard masing-masing
if (is_logged_in()) {
    $user = current_user();
    if ($user && $user['role'] === 'guru') {
        header('Location: ' . base_url('teacher/dashboard.php'));
    } else {
        header('Location: ' . base_url('student/dashboard.php'));
    }
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verify_csrf($csrf)) {
        $error = 'Sesi telah kedaluwarsa. Silakan refresh halaman dan coba lagi.';
    } else {
        $identifier = trim($_POST['identifier'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($identifier) || empty($password)) {
            $error = 'Harap isi NIS / Username dan Kata Sandi.';
        } else {
            $user = attempt_login($identifier, $password);
            if ($user) {
                if ($user['role'] === 'guru') {
                    header('Location: ' . base_url('teacher/dashboard.php'));
                } else {
                    header('Location: ' . base_url('student/dashboard.php'));
                }
                exit;
            } else {
                $error = 'NIS / Username atau Kata Sandi salah. Hubungi Guru jika lupa sandi.';
            }
        }
    }
}

$pageTitle = 'Masuk Akun';
require_once __DIR__ . '/includes/header.php';
?>

<div class="min-h-[80vh] flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <!-- Logo & Judul -->
        <div class="text-center">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white shadow-md shadow-blue-500/20 mb-3">
                <i class="fa-brands fa-python text-3xl text-yellow-300"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Masuk ke PyLMS</h2>
            <p class="mt-1 text-xs text-slate-500">Platform Belajar Python Interaktif Berbasis HP</p>
        </div>

        <?php if ($error): ?>
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs sm:text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500 text-base"></i>
            <span><?= sanitize($error) ?></span>
        </div>
        <?php endif; ?>

        <!-- Form Login -->
        <form class="space-y-4" action="<?= base_url('login.php') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div>
                <label for="identifier" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NIS atau Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-user text-sm"></i>
                    </div>
                    <input id="identifier" name="identifier" type="text" required autofocus autocomplete="username"
                        class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="Masukkan NIS siswa atau username guru" value="<?= isset($_POST['identifier']) ? sanitize($_POST['identifier']) : '' ?>">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="••••••••">
                    <button type="button" onclick="const p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password';" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                Masuk Sekarang
            </button>
        </form>

        <!-- Informasi Bantuan / Demo Akun -->
        <div class="border-t border-slate-100 pt-4">
            <div class="text-xs text-slate-500 text-center mb-2">Akun Default Guru:</div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs text-slate-600 flex justify-between items-center">
                <div>
                    <div><strong>User Guru:</strong> <code>guru</code></div>
                    <div><strong>Password:</strong> <code>password123</code></div>
                </div>
                <button type="button" onclick="document.getElementById('identifier').value='guru'; document.getElementById('password').value='password123';" class="px-2.5 py-1 text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100">
                    Isi Otomatis
                </button>
            </div>
            <div class="text-[11px] text-slate-400 text-center mt-3">
                Siswa didaftarkan oleh Guru melalui fitur Import CSV.
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
