<?php
/**
 * Manajemen Data Siswa (Tambah Manual, Edit, Hapus, Filter, Reset Sandi)
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$user = current_user();
$db = get_db();

// 1. Aksi Tambah Siswa Manual
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_student') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa. Silakan ulangi.');
    } else {
        $nis = trim($_POST['nis'] ?? '');
        $nama = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rombel = trim($_POST['rombel'] ?? '');
        $rayon = trim($_POST['rayon'] ?? '');

        if (empty($nis) || empty($nama) || empty($username) || empty($password)) {
            set_flash('warning', 'Harap lengkapi NIS, Nama, Username, dan Kata Sandi.');
        } else {
            // Cek duplikasi NIS atau Username
            $stmtCheck = $db->prepare("SELECT id, nis, username FROM users WHERE nis = ? OR username = ? LIMIT 1");
            $stmtCheck->execute([$nis, $username]);
            $existing = $stmtCheck->fetch();

            if ($existing) {
                if ($existing['nis'] === $nis) {
                    set_flash('danger', "NIS '{$nis}' sudah terdaftar pada siswa lain.");
                } else {
                    set_flash('danger', "Username '{$username}' sudah digunakan. Pilih username lain.");
                }
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmtIns = $db->prepare("
                    INSERT INTO users (nis, username, password_hash, full_name, role, rombel, rayon, xp, current_part)
                    VALUES (?, ?, ?, ?, 'siswa', ?, ?, 0, 1)
                ");
                $stmtIns->execute([$nis, $username, $hash, $nama, $rombel, $rayon]);
                set_flash('success', "Siswa '{$nama}' berhasil ditambahkan ke Rombel '{$rombel}'.");
            }
        }
    }
    header('Location: ' . base_url('teacher/students.php?' . http_build_query($_GET)));
    exit;
}

// 2. Aksi Edit Siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_student') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa. Silakan ulangi.');
    } else {
        $studentId = (int)$_POST['student_id'];
        $nis = trim($_POST['nis'] ?? '');
        $nama = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rombel = trim($_POST['rombel'] ?? '');
        $rayon = trim($_POST['rayon'] ?? '');
        $currentPart = max(1, min(8, (int)($_POST['current_part'] ?? 1)));

        if (empty($nis) || empty($nama) || empty($username)) {
            set_flash('warning', 'NIS, Nama, dan Username tidak boleh kosong.');
        } else {
            // Cek apakah NIS atau username dipakai siswa LAIN
            $stmtCheck = $db->prepare("SELECT id FROM users WHERE (nis = ? OR username = ?) AND id != ? LIMIT 1");
            $stmtCheck->execute([$nis, $username, $studentId]);
            if ($stmtCheck->fetch()) {
                set_flash('danger', 'NIS atau Username sudah digunakan oleh siswa lain.');
            } else {
                if (!empty($password)) {
                    // Update dengan password baru
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmtUp = $db->prepare("
                        UPDATE users 
                        SET nis = ?, full_name = ?, username = ?, password_hash = ?, rombel = ?, rayon = ?, current_part = ?
                        WHERE id = ? AND role = 'siswa'
                    ");
                    $stmtUp->execute([$nis, $nama, $username, $hash, $rombel, $rayon, $currentPart, $studentId]);
                } else {
                    // Update tanpa ubah password
                    $stmtUp = $db->prepare("
                        UPDATE users 
                        SET nis = ?, full_name = ?, username = ?, rombel = ?, rayon = ?, current_part = ?
                        WHERE id = ? AND role = 'siswa'
                    ");
                    $stmtUp->execute([$nis, $nama, $username, $rombel, $rayon, $currentPart, $studentId]);
                }
                set_flash('success', "Data siswa '{$nama}' berhasil diperbarui.");
            }
        }
    }
    header('Location: ' . base_url('teacher/students.php?' . http_build_query($_GET)));
    exit;
}

// 3. Aksi Reset Password ke Default (siswa123)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_pass') {
    if (verify_csrf($_POST['csrf_token'] ?? '')) {
        $studentId = (int)$_POST['student_id'];
        $newPass = 'siswa123';
        $newHash = password_hash($newPass, PASSWORD_BCRYPT);
        
        $stmtReset = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ? AND role = 'siswa'");
        $stmtReset->execute([$newHash, $studentId]);
        set_flash('success', "Kata sandi siswa berhasil direset menjadi 'siswa123'.");
    }
    header('Location: ' . base_url('teacher/students.php?' . http_build_query($_GET)));
    exit;
}

// 4. Aksi Hapus Siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_student') {
    if (verify_csrf($_POST['csrf_token'] ?? '')) {
        $studentId = (int)$_POST['student_id'];
        $stmtDel = $db->prepare("DELETE FROM users WHERE id = ? AND role = 'siswa'");
        $stmtDel->execute([$studentId]);
        set_flash('info', 'Data siswa berhasil dihapus.');
    }
    header('Location: ' . base_url('teacher/students.php?' . http_build_query($_GET)));
    exit;
}

// Filter Rombel, Rayon, dan Pencarian
$selectedRombel = trim($_GET['rombel'] ?? '');
$selectedRayon = trim($_GET['rayon'] ?? '');
$searchKeyword = trim($_GET['q'] ?? '');

$rombelOptions = $db->query("SELECT DISTINCT rombel FROM users WHERE role = 'siswa' AND rombel IS NOT NULL AND rombel != '' ORDER BY rombel ASC")->fetchAll(PDO::FETCH_COLUMN);
$rayonOptions = $db->query("SELECT DISTINCT rayon FROM users WHERE role = 'siswa' AND rayon IS NOT NULL AND rayon != '' ORDER BY rayon ASC")->fetchAll(PDO::FETCH_COLUMN);

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
    $where[] = "(full_name LIKE ? OR nis LIKE ? OR username LIKE ?)";
    $params[] = "%{$searchKeyword}%";
    $params[] = "%{$searchKeyword}%";
    $params[] = "%{$searchKeyword}%";
}

$whereSql = implode(' AND ', $where);
$stmtStudents = $db->prepare("SELECT * FROM users WHERE {$whereSql} ORDER BY rombel ASC, full_name ASC");
$stmtStudents->execute($params);
$students = $stmtStudents->fetchAll();

$pageTitle = 'Kelola Siswa';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Page & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1">
                <a href="<?= base_url('teacher/dashboard.php') ?>" class="hover:text-blue-600">Dashboard Guru</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-800 font-bold">Data Siswa</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Data Siswa</h1>
            <p class="text-xs sm:text-sm text-slate-500">Tambah manual, edit data, reset kata sandi, dan hapus akun siswa.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="openAddModal()" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                <i class="fa-solid fa-user-plus text-xs"></i> Tambah Siswa Manual
            </button>
            <a href="<?= base_url('teacher/import-csv.php') ?>" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-300 transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-csv text-cyan-600"></i> Import Siswa CSV
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm mb-6">
        <form method="GET" action="<?= base_url('teacher/students.php') ?>" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Rombel</label>
                <select name="rombel" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Rombel --</option>
                    <?php foreach ($rombelOptions as $rb): ?>
                        <option value="<?= sanitize($rb) ?>" <?= $selectedRombel === $rb ? 'selected' : '' ?>><?= sanitize($rb) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Rayon</label>
                <select name="rayon" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Rayon --</option>
                    <?php foreach ($rayonOptions as $ry): ?>
                        <option value="<?= sanitize($ry) ?>" <?= $selectedRayon === $ry ? 'selected' : '' ?>><?= sanitize($ry) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Siswa</label>
                <input type="text" name="q" value="<?= sanitize($searchKeyword) ?>" placeholder="Nama, NIS, atau Username..."
                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="sm:col-span-2 flex gap-1.5">
                <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-magnifying-glass text-[10px]"></i> Cari
                </button>
                <a href="<?= base_url('teacher/students.php') ?>" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs flex items-center justify-center" title="Reset">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Data Siswa -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between text-xs text-slate-600 font-semibold">
            <span>Total Siswa: <?= count($students) ?> orang</span>
            <span class="text-slate-400 text-[11px]">Gunakan tombol aksi di sebelah kanan untuk Edit atau Hapus</span>
        </div>

        <?php if (empty($students)): ?>
            <div class="p-12 text-center text-slate-400 text-xs sm:text-sm">
                Belum ada data siswa ditemukan. Silakan tambahkan manual atau import CSV.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4">NIS</th>
                            <th class="py-3 px-4">Username</th>
                            <th class="py-3 px-4">Rombel</th>
                            <th class="py-3 px-4">Rayon</th>
                            <th class="py-3 px-4 text-center">Part Aktif</th>
                            <th class="py-3 px-4 text-right">XP</th>
                            <th class="py-3 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($students as $st): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-bold text-slate-800">
                                <?= sanitize($st['full_name']) ?>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-500 text-xs">
                                <?= sanitize($st['nis'] ?? '-') ?>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-500 text-xs">
                                <?= sanitize($st['username']) ?>
                            </td>
                            <td class="py-3 px-4">
                                <span class="bg-slate-100 px-2 py-0.5 rounded text-xs font-semibold text-slate-700">
                                    <?= sanitize($st['rombel'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 text-xs">
                                <?= sanitize($st['rayon'] ?? '-') ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-blue-50 text-blue-700 px-2.5 py-0.5 rounded-full text-xs font-bold border border-blue-200">
                                    Part <?= $st['current_part'] ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="font-black text-amber-600"><?= number_format($st['xp']) ?> XP</div>
                                <?php $rInfo = get_rank_info($st['xp']); ?>
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold border <?= $rInfo['badge_class'] ?> mt-0.5">
                                    <i class="fa-solid <?= $rInfo['icon'] ?>"></i> <?= $rInfo['name'] ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Tombol Edit Siswa -->
                                    <button type="button" onclick='openEditModal(<?= json_encode($st) ?>)' class="px-2 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-[11px] transition" title="Edit Data Siswa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Reset Password -->
                                    <form method="POST" action="" onsubmit="return confirm('Reset kata sandi siswa \'<?= sanitize($st['full_name']) ?>\' menjadi siswa123?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="action" value="reset_pass">
                                        <input type="hidden" name="student_id" value="<?= $st['id'] ?>">
                                        <button type="submit" class="px-2 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-[11px] transition" title="Reset Kata Sandi (siswa123)">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                    </form>

                                    <!-- Hapus Siswa -->
                                    <form method="POST" action="" onsubmit="return confirm('Hapus siswa \'<?= sanitize($st['full_name']) ?>\'? Seluruh nilai dan progres belajarnya akan ikut terhapus.');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="action" value="delete_student">
                                        <input type="hidden" name="student_id" value="<?= $st['id'] ?>">
                                        <button type="submit" class="px-2 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] transition" title="Hapus Akun">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ===================================================================== -->
<!-- MODAL 1: TAMBAH SISWA MANUAL                                          -->
<!-- ===================================================================== -->
<div id="addStudentModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-lg w-full p-6 sm:p-8 relative">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-blue-600"></i> Tambah Siswa Baru
            </h3>
            <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 text-sm">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form method="POST" action="" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="add_student">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIS <span class="text-rose-500">*</span></label>
                    <input type="text" name="nis" required placeholder="Contoh: 12301015"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Username <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" required placeholder="Contoh: ahmad_f"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                <input type="text" name="full_name" required placeholder="Nama lengkap siswa"
                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rombel / Kelas</label>
                    <input type="text" name="rombel" placeholder="Misal: PPLG X-1" list="rombelList"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rayon</label>
                    <input type="text" name="rayon" placeholder="Misal: Ciawi 1" list="rayonList"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kata Sandi Awal <span class="text-rose-500">*</span></label>
                <input type="text" name="password" required value="siswa123"
                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span class="text-[10px] text-slate-400">Default: <code>siswa123</code> (akan otomatis di-hash Bcrypt).</span>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-save text-xs"></i> Simpan Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===================================================================== -->
<!-- MODAL 2: EDIT DATA SISWA                                              -->
<!-- ===================================================================== -->
<div id="editStudentModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-lg w-full p-6 sm:p-8 relative">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-blue-600"></i> Edit Data Siswa
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-sm">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form method="POST" action="" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="edit_student">
            <input type="hidden" id="edit_student_id" name="student_id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIS <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_nis" name="nis" required
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Username <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_username" name="username" required
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_full_name" name="full_name" required
                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rombel</label>
                    <input type="text" id="edit_rombel" name="rombel" list="rombelList"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rayon</label>
                    <input type="text" id="edit_rayon" name="rayon" list="rayonList"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ganti Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                        class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Batas Part Aktif</label>
                    <select id="edit_current_part" name="current_part" class="w-full text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php for ($p = 1; $p <= 8; $p++): ?>
                            <option value="<?= $p ?>">Part <?= $p ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-xs"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Datalist Autocomplete Rombel & Rayon -->
<datalist id="rombelList">
    <?php foreach ($rombelOptions as $rb): ?>
        <option value="<?= sanitize($rb) ?>">
    <?php endforeach; ?>
</datalist>
<datalist id="rayonList">
    <?php foreach ($rayonOptions as $ry): ?>
        <option value="<?= sanitize($ry) ?>">
    <?php endforeach; ?>
</datalist>

<script>
    function openAddModal() {
        document.getElementById('addStudentModal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('addStudentModal').classList.add('hidden');
    }

    function openEditModal(st) {
        document.getElementById('edit_student_id').value = st.id;
        document.getElementById('edit_nis').value = st.nis || '';
        document.getElementById('edit_full_name').value = st.full_name || '';
        document.getElementById('edit_username').value = st.username || '';
        document.getElementById('edit_rombel').value = st.rombel || '';
        document.getElementById('edit_rayon').value = st.rayon || '';
        document.getElementById('edit_current_part').value = st.current_part || 1;
        document.getElementById('editStudentModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editStudentModal').classList.add('hidden');
    }

    // Tutup modal jika klik di luar box
    window.addEventListener('click', function(e) {
        const addModal = document.getElementById('addStudentModal');
        const editModal = document.getElementById('editStudentModal');
        if (e.target === addModal) closeAddModal();
        if (e.target === editModal) closeEditModal();
    });

    // Tutup modal dengan tombol Escape
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
