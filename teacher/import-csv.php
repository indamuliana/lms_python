<?php
/**
 * Import Data Siswa dari File CSV & Otomasi Pengelompokan Rombel
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

require_teacher();
$user = current_user();
$db = get_db();

$report = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        set_flash('danger', 'Sesi kedaluwarsa. Silakan coba lagi.');
        header('Location: ' . base_url('teacher/import-csv.php'));
        exit;
    }

    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        set_flash('danger', 'Harap pilih file CSV yang valid untuk diunggah.');
        header('Location: ' . base_url('teacher/import-csv.php'));
        exit;
    }

    $fileTmp = $_FILES['csv_file']['tmp_name'];
    $fileName = $_FILES['csv_file']['name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($ext !== 'csv' && $ext !== 'txt') {
        set_flash('danger', 'Format file harus berekstensi .csv');
        header('Location: ' . base_url('teacher/import-csv.php'));
        exit;
    }

    $handle = fopen($fileTmp, 'r');
    if (!$handle) {
        set_flash('danger', 'Gagal membaca file CSV.');
        header('Location: ' . base_url('teacher/import-csv.php'));
        exit;
    }

    // Deteksi separator (koma atau titik koma)
    $firstLine = fgets($handle);
    $separator = (strpos($firstLine, ';') !== false && strpos($firstLine, ',') === false) ? ';' : ',';
    rewind($handle);

    // Baca header
    $headers = fgetcsv($handle, 2000, $separator);
    if (!$headers) {
        fclose($handle);
        set_flash('danger', 'File CSV kosong.');
        header('Location: ' . base_url('teacher/import-csv.php'));
        exit;
    }

    // Normalisasi index header
    $colMap = [];
    foreach ($headers as $idx => $col) {
        $cleanCol = strtolower(trim($col));
        // Hilangkan BOM jika ada
        $cleanCol = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cleanCol);

        if (in_array($cleanCol, ['nis', 'nomor_induk'])) $colMap['nis'] = $idx;
        elseif (in_array($cleanCol, ['nama', 'full_name', 'nama_lengkap', 'nama_siswa'])) $colMap['nama'] = $idx;
        elseif (in_array($cleanCol, ['rombel', 'kelas', 'class'])) $colMap['rombel'] = $idx;
        elseif (in_array($cleanCol, ['rayon', 'wilayah'])) $colMap['rayon'] = $idx;
        elseif (in_array($cleanCol, ['user', 'username'])) $colMap['username'] = $idx;
        elseif (in_array($cleanCol, ['password', 'pass', 'kata_sandi'])) $colMap['password'] = $idx;
    }

    // Validasi kolom wajib
    $requiredCols = ['nis', 'nama', 'username', 'password'];
    $missing = [];
    foreach ($requiredCols as $req) {
        if (!isset($colMap[$req])) {
            $missing[] = $req;
        }
    }

    if (!empty($missing)) {
        fclose($handle);
        set_flash('danger', 'Format kolom CSV belum sesuai. Kolom yang tidak ditemukan: ' . implode(', ', $missing) . '. Pastikan kolom sesuai petunjuk.');
        header('Location: ' . base_url('teacher/import-csv.php'));
        exit;
    }

    $inserted = 0;
    $updated = 0;
    $skipped = 0;
    $rombelsDetected = [];

    // Siapkan query upsert
    $stmtCheck = $db->prepare("SELECT id FROM users WHERE nis = ? OR username = ? LIMIT 1");
    $stmtInsert = $db->prepare("
        INSERT INTO users (nis, username, password_hash, full_name, role, rombel, rayon, xp, current_part)
        VALUES (?, ?, ?, ?, 'siswa', ?, ?, 0, 1)
    ");
    $stmtUpdate = $db->prepare("
        UPDATE users 
        SET full_name = ?, rombel = ?, rayon = ?, password_hash = ?
        WHERE id = ?
    ");

    $lineNum = 1;
    while (($row = fgetcsv($handle, 2000, $separator)) !== false) {
        $lineNum++;
        if (count($row) < 2) continue;

        $nis = trim($row[$colMap['nis']] ?? '');
        $nama = trim($row[$colMap['nama']] ?? '');
        $username = trim($row[$colMap['username']] ?? '');
        $rawPass = trim($row[$colMap['password']] ?? '');
        $rombel = isset($colMap['rombel']) ? trim($row[$colMap['rombel']] ?? '') : null;
        $rayon = isset($colMap['rayon']) ? trim($row[$colMap['rayon']] ?? '') : null;

        if (empty($nis) || empty($nama) || empty($username) || empty($rawPass)) {
            $skipped++;
            continue;
        }

        if (!empty($rombel)) {
            $rombelsDetected[$rombel] = true;
        }

        $hash = password_hash($rawPass, PASSWORD_BCRYPT);

        // Cek duplikasi
        $stmtCheck->execute([$nis, $username]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            $stmtUpdate->execute([$nama, $rombel, $rayon, $hash, $existing['id']]);
            $updated++;
        } else {
            $stmtInsert->execute([$nis, $username, $hash, $nama, $rombel, $rayon]);
            $inserted++;
        }
    }
    fclose($handle);

    $report = [
        'inserted' => $inserted,
        'updated' => $updated,
        'skipped' => $skipped,
        'rombels' => array_keys($rombelsDetected)
    ];

    set_flash('success', "Proses import selesai: {$inserted} siswa baru ditambahkan, {$updated} siswa diperbarui.");
}

$pageTitle = 'Import Siswa via CSV';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1">
                <a href="<?= base_url('teacher/dashboard.php') ?>" class="hover:text-blue-600">Dashboard Guru</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                <span class="text-slate-800 font-bold">Import Siswa CSV</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Import Data Siswa & Rombel</h1>
            <p class="text-xs sm:text-sm text-slate-500">Unggah file CSV untuk mendaftarkan akun siswa sekaligus mengelompokkan Rombel secara otomatis.</p>
        </div>
        <a href="<?= base_url('teacher/sample_students.csv') ?>" download class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-300 transition flex items-center gap-2">
            <i class="fa-solid fa-download text-slate-500"></i> Unduh Format Contoh CSV
        </a>
    </div>

    <!-- Hasil Import Report Jika Ada -->
    <?php if ($report): ?>
    <div class="mb-6 p-5 bg-emerald-50 border border-emerald-200 rounded-3xl text-xs sm:text-sm text-emerald-900 shadow-sm">
        <h3 class="font-extrabold text-base flex items-center gap-2 mb-2 text-emerald-800">
            <i class="fa-solid fa-circle-check text-emerald-600"></i> Laporan Import Berhasil
        </h3>
        <ul class="list-disc pl-5 space-y-1 mb-3">
            <li><strong>Siswa Baru Didaftarkan:</strong> <?= $report['inserted'] ?> orang</li>
            <li><strong>Siswa Diperbarui (Update):</strong> <?= $report['updated'] ?> orang</li>
            <li><strong>Baris Dilewati (Kosong):</strong> <?= $report['skipped'] ?> baris</li>
            <li><strong>Rombel Terdeteksi:</strong> <?= !empty($report['rombels']) ? implode(', ', array_map('sanitize', $report['rombels'])) : '-' ?></li>
        </ul>
        <a href="<?= base_url('teacher/gradebook.php') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 shadow-sm transition">
            <i class="fa-solid fa-book-open"></i> Lihat Data di Buku Nilai
        </a>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Kolom Form Upload (md:col-span-7) -->
        <div class="md:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <form action="<?= base_url('teacher/import-csv.php') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih File CSV Siswa</label>
                    <div class="border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-2xl p-6 text-center cursor-pointer transition bg-slate-50 hover:bg-blue-50/30" onclick="document.getElementById('csv_file').click()">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-2"></i>
                        <div class="text-xs font-bold text-slate-700" id="fileLabel">Klik untuk memilih file .csv</div>
                        <div class="text-[11px] text-slate-400 mt-1">Maksimal 5MB &bull; Separator koma (,) atau titik koma (;)</div>
                        <input type="file" id="csv_file" name="csv_file" accept=".csv, .txt" required class="hidden" onchange="document.getElementById('fileLabel').innerText = this.files[0]?.name || 'Pilih file .csv';">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-file-import"></i> Proses & Daftarkan Siswa
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Petunjuk Format (md:col-span-5) -->
        <div class="md:col-span-5 bg-slate-50 rounded-3xl border border-slate-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-blue-500"></i> Petunjuk Kolom CSV
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-3">
                    Baris pertama file CSV harus berupa header nama kolom persis atau mirip dengan format berikut:
                </p>

                <div class="bg-white p-3 rounded-xl border border-slate-200 font-mono text-[11px] text-slate-700 overflow-x-auto mb-3">
                    <code>nis,nama,rombel,rayon,username,password</code>
                </div>

                <ul class="text-xs text-slate-600 space-y-1.5 pl-4 list-disc">
                    <li><strong>nis:</strong> Nomor Induk Siswa (unik).</li>
                    <li><strong>nama:</strong> Nama lengkap siswa.</li>
                    <li><strong>rombel:</strong> Nama kelas (misal: <code>PPLG X-1</code>).</li>
                    <li><strong>rayon:</strong> Rayon/kelompok daerah (misal: <code>Ciawi 1</code>).</li>
                    <li><strong>username:</strong> Username untuk login.</li>
                    <li><strong>password:</strong> Kata sandi awal siswa (akan otomatis di-hash Bcrypt oleh sistem).</li>
                </ul>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-200">
                <a href="<?= base_url('teacher/sample_students.csv') ?>" download class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <i class="fa-solid fa-file-arrow-down"></i> Unduh File Template CSV
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
