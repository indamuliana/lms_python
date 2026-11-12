<?php
/**
 * Profil Guru - Pengaturan Akun, Foto Profil (Maks 1 MB), & Ganti Password
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$user = current_user();
if (!$user) {
    header('Location: ' . base_url('login.php'));
    exit;
}

$db = get_db();
$userId = (int)$user['id'];

// 1. Handle Update Profil (Nama Lengkap, Username, & Foto Profil)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa. Silakan ulangi.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');

    if (empty($fullName)) {
        set_flash('danger', 'Nama lengkap tidak boleh kosong.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    if (empty($username) || strlen($username) < 3) {
        set_flash('danger', 'Username minimal 3 karakter.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
        set_flash('danger', 'Username hanya boleh berisi huruf, angka, garis bawah (_), titik (.), dan tanda hubung (-).');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    // Cek duplikasi username di database
    $stmtCheck = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmtCheck->execute([$username, $userId]);
    if ($stmtCheck->fetch()) {
        set_flash('danger', 'Username "' . sanitize($username) . '" sudah digunakan oleh akun lain. Silakan pilih username lain.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    $newAvatarFilename = null;

    // Cek apakah ada file foto yang diunggah
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['avatar'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            set_flash('danger', 'Gagal mengunggah foto profil (Kode error: ' . $file['error'] . ').');
            header('Location: ' . base_url('teacher/profile.php'));
            exit;
        }

        // Validasi ukuran maksimal 1 MB (1.048.576 bytes)
        $maxBytes = 1048576;
        if ($file['size'] > $maxBytes) {
            set_flash('danger', 'Ukuran foto melebihi batas maksimal 1 MB! Ukuran file Anda: ' . round($file['size'] / 1024 / 1024, 2) . ' MB.');
            header('Location: ' . base_url('teacher/profile.php'));
            exit;
        }

        // Validasi tipe gambar
        $imageInfo = @getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            set_flash('danger', 'File yang diunggah bukan gambar valid.');
            header('Location: ' . base_url('teacher/profile.php'));
            exit;
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($imageInfo['mime'], $allowedMimes)) {
            set_flash('danger', 'Format foto tidak didukung. Harap gunakan format JPG, PNG, atau WEBP.');
            header('Location: ' . base_url('teacher/profile.php'));
            exit;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($extension === 'jpeg') $extension = 'jpg';
        if (!in_array($extension, ['jpg', 'png', 'webp', 'gif'])) {
            $extension = 'jpg';
        }

        $uploadDir = __DIR__ . '/../uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newAvatarFilename = 'avatar_guru_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $destPath = $uploadDir . $newAvatarFilename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            if (!empty($user['avatar']) && $user['avatar'] !== 'default.png') {
                $oldPath = $uploadDir . $user['avatar'];
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
        } else {
            set_flash('danger', 'Gagal memindahkan file foto ke direktori penyimpanan.');
            header('Location: ' . base_url('teacher/profile.php'));
            exit;
        }
    }

    // Update database
    if ($newAvatarFilename !== null) {
        $stmtUp = $db->prepare("UPDATE users SET full_name = ?, username = ?, avatar = ? WHERE id = ?");
        $stmtUp->execute([$fullName, $username, $newAvatarFilename, $userId]);
    } else {
        $stmtUp = $db->prepare("UPDATE users SET full_name = ?, username = ? WHERE id = ?");
        $stmtUp->execute([$fullName, $username, $userId]);
    }

    set_flash('success', 'Profil Guru berhasil diperbarui.');
    header('Location: ' . base_url('teacher/profile.php'));
    exit;
}

// 2. Handle Ganti Password
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa. Silakan ulangi.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    $oldPassword = (string)($_POST['old_password'] ?? '');
    $newPassword = (string)($_POST['new_password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    // Verifikasi password lama
    if (!password_verify($oldPassword, $user['password_hash']) && $user['password_hash'] !== $oldPassword) {
        set_flash('danger', 'Kata sandi saat ini (lama) yang Anda masukkan salah.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    // Validasi password baru
    if (strlen($newPassword) < 6) {
        set_flash('danger', 'Kata sandi baru minimal harus terdiri dari 6 karakter.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        set_flash('danger', 'Konfirmasi kata sandi baru tidak cocok. Harap periksa kembali.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    // Hash dan simpan kata sandi baru
    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmtUpPass = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmtUpPass->execute([$newHash, $userId]);

    set_flash('success', 'Kata sandi Anda berhasil diperbarui! Sesi login Anda tetap aktif.');
    header('Location: ' . base_url('teacher/profile.php'));
    exit;
}

// 3. Handle Hapus Foto Profil (Reset ke Default)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_avatar') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa.');
        header('Location: ' . base_url('teacher/profile.php'));
        exit;
    }

    if (!empty($user['avatar']) && $user['avatar'] !== 'default.png') {
        $uploadDir = __DIR__ . '/../uploads/avatars/';
        $oldPath = $uploadDir . $user['avatar'];
        if (file_exists($oldPath)) {
            @unlink($oldPath);
        }
    }

    $stmtReset = $db->prepare("UPDATE users SET avatar = 'default.png' WHERE id = ?");
    $stmtReset->execute([$userId]);

    set_flash('success', 'Foto profil berhasil dihapus dan dikembalikan ke bawaan.');
    header('Location: ' . base_url('teacher/profile.php'));
    exit;
}

// Ambil profil terbaru
$user = get_user_profile($userId);
$avatarUrl = get_user_avatar($user['avatar']);

$pageTitle = 'Profil & Pengaturan Akun Guru';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-6">
        <a href="<?= base_url('teacher/dashboard.php') ?>" class="hover:text-blue-600 flex items-center gap-1.5">
            <i class="fa-solid fa-gauge"></i> Dashboard Guru
        </a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800 font-bold">Profil &amp; Keamanan Akun</span>
    </div>

    <!-- Header Profil Card -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center gap-5 relative z-10">
            <div class="relative">
                <?php if ($avatarUrl): ?>
                    <img src="<?= $avatarUrl ?>" alt="Avatar" class="w-20 h-20 rounded-2xl object-cover border-2 border-white/60 shadow-lg">
                <?php else: ?>
                    <div class="w-20 h-20 rounded-2xl bg-amber-500/20 backdrop-blur-md border border-amber-400/40 flex items-center justify-center text-3xl font-black text-amber-300 shadow-inner">
                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight"><?= sanitize($user['full_name']) ?></h1>
                    <span class="inline-flex items-center gap-1 font-bold text-xs px-3 py-0.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/30 shadow-sm">
                        <i class="fa-solid fa-chalkboard-user"></i> Guru Pembimbing
                    </span>
                </div>
                <div class="text-xs sm:text-sm text-slate-300 mt-1 flex flex-wrap items-center gap-3">
                    <span><i class="fa-solid fa-user opacity-80 mr-1 text-blue-300"></i> Username: <strong><?= sanitize($user['username']) ?></strong></span>
                    <span><i class="fa-solid fa-shield-halved opacity-80 mr-1 text-emerald-300"></i> Hak Akses: Administrator LMS</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Kolom Kiri: Form Data Profil & Foto (lg:col-span-7) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7">
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Data Profil &amp; Foto</h2>
                        <p class="text-xs text-slate-500">Ubah nama lengkap, username login, dan foto profil Anda.</p>
                    </div>
                </div>

                <form method="POST" action="" enctype="multipart/form-data" class="space-y-5" onsubmit="return validateBeforeSubmit()">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="update_profile">

                    <!-- Bagian Foto Profil -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Foto Profil (Maksimal 1 MB)</label>
                        <div class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                            <!-- Preview Box -->
                            <div class="relative flex-shrink-0">
                                <img id="avatarPreview"
                                     data-original-src="<?= $avatarUrl ?: '' ?>"
                                     src="<?= $avatarUrl ?: 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'64\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2394a3b8\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path d=\'M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2\'></path><circle cx=\'12\' cy=\'7\' r=\'4\'></circle></svg>' ?>"
                                     class="w-16 h-16 rounded-2xl object-cover border-2 border-slate-200 shadow-sm bg-white p-0.5">
                            </div>

                            <div class="flex-1 space-y-1.5">
                                <input type="file" name="avatar" id="avatarInput" accept="image/png, image/jpeg, image/jpg, image/webp"
                                       onchange="handleAvatarSelection(this)"
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                                <div id="fileSizeInfo" class="text-[11px] text-slate-400">
                                    <i class="fa-solid fa-circle-info mr-1 text-slate-400"></i> Format didukung: JPG, PNG, WEBP. Maks 1 MB.
                                </div>
                            </div>
                        </div>

                        <?php if ($avatarUrl): ?>
                        <div class="mt-2 text-right">
                            <button type="submit" form="formDeleteAvatar" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition inline-flex items-center gap-1">
                                <i class="fa-solid fa-trash-can"></i> Hapus Foto (Gunakan Bawaan)
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Input Nama Lengkap -->
                    <div>
                        <label for="full_name" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Nama Lengkap &amp; Gelar <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs">
                                <i class="fa-solid fa-user-tie"></i>
                            </span>
                            <input type="text" id="full_name" name="full_name" required maxlength="100"
                                   value="<?= sanitize($user['full_name']) ?>"
                                   placeholder="Contoh: Budi Santoso, S.Kom."
                                   class="w-full text-xs sm:text-sm pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>

                    <!-- Input Username Login -->
                    <div>
                        <label for="username" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Username Login Guru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs">
                                <i class="fa-solid fa-at"></i>
                            </span>
                            <input type="text" id="username" name="username" required minlength="3" maxlength="50"
                                   value="<?= sanitize($user['username']) ?>"
                                   placeholder="Username untuk login"
                                   class="w-full text-xs sm:text-sm pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Username digunakan untuk masuk ke dashboard Guru.</p>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>

                <!-- Hidden form for deleting avatar -->
                <?php if ($avatarUrl): ?>
                <form id="formDeleteAvatar" method="POST" action="" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="delete_avatar">
                </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Kolom Kanan: Form Ganti Password (lg:col-span-5) -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7">
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Ganti Kata Sandi</h2>
                        <p class="text-xs text-slate-500">Perbarui kata sandi akun Guru secara berkala.</p>
                    </div>
                </div>

                <form method="POST" action="" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="change_password">

                    <div>
                        <label for="old_password" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" id="old_password" name="old_password" required placeholder="Masukkan kata sandi lama"
                                   class="w-full text-xs sm:text-sm pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <button type="button" onclick="togglePassVisibility('old_password')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs">
                                <i class="fa-solid fa-shield-halved"></i>
                            </span>
                            <input type="password" id="new_password" name="new_password" required minlength="6" placeholder="Minimal 6 karakter"
                                   class="w-full text-xs sm:text-sm pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <button type="button" onclick="togglePassVisibility('new_password')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Ulangi Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs">
                                <i class="fa-solid fa-check-double"></i>
                            </span>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Ketik ulang kata sandi baru"
                                   class="w-full text-xs sm:text-sm pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <button type="button" onclick="togglePassVisibility('confirm_password')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-shield-halved"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Validasi Ukuran File Foto Profil Langsung di Browser (Maks 1 MB)
 */
function handleAvatarSelection(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    const maxSizeBytes = 1048576; // Tepat 1 MB
    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
    const info = document.getElementById('fileSizeInfo');
    const preview = document.getElementById('avatarPreview');

    // Peringatan jika melebihi 1 MB
    if (file.size > maxSizeBytes) {
        alert('⚠️ PERINGATAN UKURAN FILE:\n\nUkuran foto yang Anda pilih (' + fileSizeMB + ' MB) melebihi batas maksimal 1 MB!\n\nSilakan pilih file foto lain yang berukuran lebih kecil dari 1 MB.');
        input.value = '';
        if (info) {
            info.innerHTML = '<span class="text-rose-600 font-bold"><i class="fa-solid fa-triangle-exclamation"></i> Ditolak: Ukuran foto ' + fileSizeMB + ' MB melebihi batas 1 MB.</span>';
        }
        if (preview) {
            const orig = preview.getAttribute('data-original-src');
            if (orig) preview.src = orig;
        }
        return;
    }

    // Tampilkan Preview & Info Valid
    const reader = new FileReader();
    reader.onload = function(e) {
        if (preview) preview.src = e.target.result;
        if (info) {
            info.innerHTML = '<span class="text-emerald-600 font-bold"><i class="fa-solid fa-circle-check"></i> Siap diupload &bull; Ukuran: ' + fileSizeMB + ' MB (Sesuai batas 1 MB)</span>';
        }
    };
    reader.readAsDataURL(file);
}

function validateBeforeSubmit() {
    const input = document.getElementById('avatarInput');
    if (input && input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 1048576) {
            alert('⚠️ Ukuran foto melebihi batas maksimal 1 MB! Harap pilih foto lain.');
            return false;
        }
    }
    return true;
}

function togglePassVisibility(inputId) {
    const el = document.getElementById(inputId);
    if (!el) return;
    el.type = el.type === 'password' ? 'text' : 'password';
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
