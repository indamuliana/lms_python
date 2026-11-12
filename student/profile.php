<?php
/**
 * Profil Siswa - Pengaturan Foto Profil (Maks 1 MB), Nickname & Ganti Password
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_login();
$user = current_user();
if (!$user) {
    header('Location: ' . base_url('login.php'));
    exit;
}

$db = get_db();
$userId = (int)$user['id'];

// 1. Handle Update Profil (Foto & Nickname)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa. Silakan ulangi.');
        header('Location: ' . base_url('student/profile.php'));
        exit;
    }

    $nickname = trim($_POST['nickname'] ?? '');
    if (mb_strlen($nickname) > 50) {
        $nickname = mb_substr($nickname, 0, 50);
    }

    $newAvatarFilename = null;

    // Cek apakah ada file foto yang diunggah
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['avatar'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            set_flash('danger', 'Gagal mengunggah file foto (Kode error: ' . $file['error'] . ').');
            header('Location: ' . base_url('student/profile.php'));
            exit;
        }

        // Validasi ukuran maksimal 1 MB (1.048.576 bytes)
        $maxBytes = 1048576;
        if ($file['size'] > $maxBytes) {
            set_flash('danger', 'Ukuran foto melebihi batas maksimal 1 MB! Ukuran file Anda: ' . round($file['size'] / 1024 / 1024, 2) . ' MB.');
            header('Location: ' . base_url('student/profile.php'));
            exit;
        }

        // Validasi tipe file / MIME image
        $imageInfo = @getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            set_flash('danger', 'File yang diunggah bukan gambar valid.');
            header('Location: ' . base_url('student/profile.php'));
            exit;
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($imageInfo['mime'], $allowedMimes)) {
            set_flash('danger', 'Format foto tidak didukung. Harap gunakan format JPG, PNG, atau WEBP.');
            header('Location: ' . base_url('student/profile.php'));
            exit;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($extension === 'jpeg') $extension = 'jpg';
        if (!in_array($extension, ['jpg', 'png', 'webp', 'gif'])) {
            $extension = 'jpg';
        }

        // Buat nama file acak yang aman
        $uploadDir = __DIR__ . '/../uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newAvatarFilename = 'avatar_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $destPath = $uploadDir . $newAvatarFilename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            // Hapus file avatar lama jika bukan default.png
            if (!empty($user['avatar']) && $user['avatar'] !== 'default.png') {
                $oldPath = $uploadDir . $user['avatar'];
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
        } else {
            set_flash('danger', 'Gagal memindahkan file foto ke direktori penyimpanan.');
            header('Location: ' . base_url('student/profile.php'));
            exit;
        }
    }

    // Simpan ke database
    if ($newAvatarFilename !== null) {
        $stmtUp = $db->prepare("UPDATE users SET nickname = ?, avatar = ? WHERE id = ?");
        $stmtUp->execute([$nickname, $newAvatarFilename, $userId]);
    } else {
        $stmtUp = $db->prepare("UPDATE users SET nickname = ? WHERE id = ?");
        $stmtUp->execute([$nickname, $userId]);
    }

    set_flash('success', 'Profil dan nama panggilan berhasil diperbarui.');
    header('Location: ' . base_url('student/profile.php'));
    exit;
}

// 2. Handle Ganti Password
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa. Silakan ulangi.');
        header('Location: ' . base_url('student/profile.php'));
        exit;
    }

    $oldPassword = (string)($_POST['old_password'] ?? '');
    $newPassword = (string)($_POST['new_password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    // Verifikasi password lama
    if (!password_verify($oldPassword, $user['password_hash']) && $user['password_hash'] !== $oldPassword) {
        set_flash('danger', 'Kata sandi saat ini (lama) yang Anda masukkan salah.');
        header('Location: ' . base_url('student/profile.php'));
        exit;
    }

    // Validasi password baru
    if (strlen($newPassword) < 6) {
        set_flash('danger', 'Kata sandi baru minimal harus terdiri dari 6 karakter.');
        header('Location: ' . base_url('student/profile.php'));
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        set_flash('danger', 'Konfirmasi kata sandi baru tidak cocok. Harap periksa kembali.');
        header('Location: ' . base_url('student/profile.php'));
        exit;
    }

    // Hash dan simpan kata sandi baru
    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmtUpPass = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmtUpPass->execute([$newHash, $userId]);

    set_flash('success', 'Kata sandi Anda berhasil diperbarui! Gunakan kata sandi baru saat login berikutnya.');
    header('Location: ' . base_url('student/profile.php'));
    exit;
}

// 3. Handle Hapus Foto Profil (Reset ke Default)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_avatar') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa.');
        header('Location: ' . base_url('student/profile.php'));
        exit;
    }

    if (!empty($user['avatar']) && $user['avatar'] !== 'default.png') {
        $oldPath = __DIR__ . '/../uploads/avatars/' . $user['avatar'];
        if (file_exists($oldPath)) {
            @unlink($oldPath);
        }
        $stmtDel = $db->prepare("UPDATE users SET avatar = 'default.png' WHERE id = ?");
        $stmtDel->execute([$userId]);
    }

    set_flash('info', 'Foto profil telah dihapus dan dikembalikan ke avatar bawaan.');
    header('Location: ' . base_url('student/profile.php'));
    exit;
}

// Ambil profil terbaru
$user = get_user_profile($userId);
$avatarUrl = get_user_avatar($user['avatar']);

$pageTitle = 'Pengaturan Profil & Keamanan Akun';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-6">
        <a href="<?= base_url('student/dashboard.php') ?>" class="hover:text-blue-600 flex items-center gap-1.5">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>
        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-800 font-bold">Profil &amp; Keamanan Akun</span>
    </div>

    <!-- Header Profil Card -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-sky-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center gap-4 relative z-10">
            <!-- Avatar Display -->
            <div class="relative">
                <?php if ($avatarUrl): ?>
                    <img src="<?= $avatarUrl ?>" alt="Avatar" class="w-20 h-20 rounded-2xl object-cover border-2 border-white/60 shadow-lg">
                <?php else: ?>
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-3xl font-black shadow-inner">
                        <i class="fa-solid fa-user-astronaut text-yellow-300"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight"><?= sanitize($user['full_name']) ?></h1>
                    <span class="inline-flex items-center gap-1 font-black text-xs px-3 py-0.5 rounded-full border shadow-sm <?= $user['rank_badge'] ?? 'bg-amber-700 text-white' ?>">
                        <i class="fa-solid <?= $user['rank_icon'] ?? 'fa-shield-halved' ?>"></i> <?= $user['level'] ?>
                    </span>
                </div>
                <div class="text-xs sm:text-sm text-blue-100 mt-1 flex flex-wrap items-center gap-3">
                    <?php if (!empty($user['nickname'])): ?>
                        <span><i class="fa-solid fa-tag opacity-80 mr-1 text-yellow-300"></i> Panggilan: <strong><?= sanitize($user['nickname']) ?></strong></span>
                    <?php endif; ?>
                    <span><i class="fa-solid fa-id-card opacity-80 mr-1"></i> NIS: <?= sanitize($user['nis'] ?? '-') ?></span>
                    <span><i class="fa-solid fa-users opacity-80 mr-1"></i> Rombel: <?= sanitize($user['rombel'] ?? '-') ?></span>
                    <span><i class="fa-solid fa-bolt opacity-80 mr-1 text-yellow-300"></i> <?= number_format($user['xp']) ?> XP</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Kolom Kiri: Form Foto & Nickname (lg:col-span-7) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7">
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Foto Profil &amp; Nama Panggilan</h2>
                        <p class="text-xs text-slate-500">Sesuaikan avatar dan nama panggilan yang ingin ditampilkan.</p>
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

                    <!-- Input Nama Panggilan (Nickname) -->
                    <div>
                        <label for="nickname" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Nama Panggilan (Nickname)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-tag text-xs"></i>
                            </div>
                            <input type="text" id="nickname" name="nickname" maxlength="50"
                                   value="<?= sanitize($user['nickname'] ?? '') ?>"
                                   placeholder="Contoh: Fadhil / Ahmad"
                                   class="w-full text-xs font-semibold bg-white border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Nama panggilan yang akan ditampilkan di header dan profil Anda.</p>
                    </div>

                    <!-- Info Siswa Readonly -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase">Nama Lengkap (Sesuai Dapodik)</label>
                            <input type="text" disabled value="<?= sanitize($user['full_name']) ?>"
                                   class="w-full text-xs bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-slate-600 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase">NIS / Username</label>
                            <input type="text" disabled value="<?= sanitize($user['nis'] ?? $user['username']) ?>"
                                   class="w-full text-xs bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-slate-600 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Profil &amp; Foto
                        </button>
                    </div>
                </form>

                <?php if ($avatarUrl): ?>
                <form id="formDeleteAvatar" method="POST" action="" onsubmit="return confirm('Yakin ingin menghapus foto profil dan kembali ke avatar bawaan?');">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="delete_avatar">
                </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Kolom Kanan: Form Ganti Kata Sandi (lg:col-span-5) -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7">
                <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Ganti Kata Sandi</h2>
                        <p class="text-xs text-slate-500">Amankan akun Anda dengan kata sandi baru.</p>
                    </div>
                </div>

                <form method="POST" action="" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="change_password">

                    <div>
                        <label for="old_password" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Kata Sandi Saat Ini (Lama)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" id="old_password" name="old_password" required placeholder="Masukkan kata sandi lama"
                                   class="w-full text-xs font-semibold bg-white border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <div>
                        <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Kata Sandi Baru (Min. 6 Karakter)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-key text-xs"></i>
                            </div>
                            <input type="password" id="new_password" name="new_password" required minlength="6" placeholder="Minimal 6 karakter"
                                   class="w-full text-xs font-semibold bg-white border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-shield-check text-xs"></i>
                            </div>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="6" placeholder="Ketik ulang kata sandi baru"
                                   class="w-full text-xs font-semibold bg-white border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-900 rounded-xl text-xs font-extrabold shadow-md transition flex items-center gap-2">
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

    // 1. Peringatan jika melebihi 1 MB
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

    // 2. Tampilkan Preview & Info Valid
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
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
