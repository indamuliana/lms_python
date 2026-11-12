<?php
/**
 * Portal Pengumpulan Proyek Akhir (Capstone Project - Part 8)
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

if (!is_part_unlocked($user['id'], 8)) {
    set_flash('warning', 'Part 8 Capstone Project masih terkunci. Harap selesaikan Part 1 s/d 7 terlebih dahulu.');
    header('Location: ' . base_url('student/dashboard.php'));
    exit;
}

// Cek apakah sudah pernah mengirim submission
$stmtSub = $db->prepare("SELECT * FROM capstone_submissions WHERE user_id = ? LIMIT 1");
$stmtSub->execute([$user['id']]);
$submission = $stmtSub->fetch();

$feedbackMsg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $feedbackMsg = ['type' => 'danger', 'text' => 'Sesi kedaluwarsa.'];
    } else {
        $trackChoice = trim($_POST['track_choice'] ?? '');
        $sourceCode = trim($_POST['source_code'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if (empty($trackChoice) || empty($sourceCode)) {
            $feedbackMsg = ['type' => 'warning', 'text' => 'Pilih jalur proyek dan masukkan kode program lengkap Anda.'];
        } else {
            if ($submission) {
                // Update submission yang ada
                $stmtUp = $db->prepare("
                    UPDATE capstone_submissions 
                    SET track_choice = ?, source_code = ?, notes = ?, status = 'pending', submitted_at = NOW()
                    WHERE user_id = ?
                ");
                $stmtUp->execute([$trackChoice, $sourceCode, $notes, $user['id']]);
            } else {
                // Insert baru
                $stmtIns = $db->prepare("
                    INSERT INTO capstone_submissions (user_id, track_choice, source_code, notes, status)
                    VALUES (?, ?, ?, ?, 'pending')
                ");
                $stmtIns->execute([$user['id'], $trackChoice, $sourceCode, $notes]);
                // Beri XP awal pengumpulan
                add_user_xp($user['id'], 50);
            }

            // Refresh data submission
            $stmtSub->execute([$user['id']]);
            $submission = $stmtSub->fetch();

            $feedbackMsg = ['type' => 'success', 'text' => 'Proyek akhir Anda berhasil dikirim ke Guru untuk dinilai!'];
        }
    }
}

// Template starter per jalur
$templates = [
    'game' => "# JALUR 1: MINI GAME TEKS EDUKASI\n\ndef main():\n    print('=== PETUALANGAN KUIS MATEMATIKA ===')\n    skor = 0\n    jawaban = input('Berapa 12 x 8? ')\n    if jawaban == '96':\n        print('Hebat! Jawaban Anda benar.')\n        skor += 10\n    else:\n        print('Sayang sekali, masih keliru.')\n    print(f'Skor Akhir: {skor}')\n\nif __name__ == '__main__':\n    main()\n",
    'kasir' => "# JALUR 2: KASIR TOKO & SIMPAN STRUK\n\nmenu = {'1': ('Kopi', 10000), '2': ('Roti', 8000)}\n\ndef hitung_total(pilihan, qty):\n    return menu[pilihan][1] * qty\n\ndef main():\n    print('=== KASIR KAFE ===')\n    pilihan = input('Pilih menu [1/2]: ')\n    qty = int(input('Jumlah: '))\n    total = hitung_total(pilihan, qty)\n    print(f'Total Bayar: Rp {total}')\n\nif __name__ == '__main__':\n    main()\n",
    'nilai' => "# JALUR 3: SISTEM BUKU NILAI MINI\n\ndef hitung_rata_rata(daftar_nilai):\n    return sum(daftar_nilai) / len(daftar_nilai)\n\ndef main():\n    print('=== BUKU NILAI MINI ===')\n    nilai = [85, 90, 78, 92]\n    print(f'Rata-rata kelas: {hitung_rata_rata(nilai):.2f}')\n\nif __name__ == '__main__':\n    main()\n"
];

$currentTrack = $submission['track_choice'] ?? 'kasir';
$currentCode = $submission['source_code'] ?? ($templates[$currentTrack] ?? $templates['kasir']);

$pageTitle = 'Proyek Akhir (Capstone)';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-8">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl text-yellow-300">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-200">Part 8 — Puncak Pembelajaran</span>
                <h1 class="text-2xl sm:text-3xl font-black">Proyek Akhir (Capstone Project)</h1>
                <p class="text-xs sm:text-sm text-purple-100 mt-1">Buat satu proyek utuh dengan memilih salah satu dari 3 jalur di bawah ini.</p>
            </div>
        </div>
    </div>

    <!-- Status Pengumpulan Jika Ada -->
    <?php if ($submission): ?>
        <?php
        $statusColors = [
            'pending' => 'bg-amber-50 border-amber-300 text-amber-900',
            'approved' => 'bg-emerald-50 border-emerald-300 text-emerald-900',
            'revision' => 'bg-rose-50 border-rose-300 text-rose-900',
        ];
        $statusLabels = [
            'pending' => '<i class="fa-solid fa-clock text-amber-500 mr-1.5"></i> Menunggu Penilaian Guru',
            'approved' => '<i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i> LULUS & DISETUJUI',
            'revision' => '<i class="fa-solid fa-circle-exclamation text-rose-500 mr-1.5"></i> Perlu Revisi',
        ];
        ?>
        <div class="mb-6 p-5 rounded-2xl border <?= $statusColors[$submission['status']] ?? 'bg-slate-50 border-slate-200' ?> shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider">Status Pengumpulan:</span>
                    <div class="text-base font-extrabold flex items-center mt-0.5">
                        <?= $statusLabels[$submission['status']] ?? $submission['status'] ?>
                    </div>
                </div>
                <?php if ($submission['teacher_score'] !== null): ?>
                    <div class="sm:text-right">
                        <span class="text-xs text-slate-500 font-bold">Nilai dari Guru:</span>
                        <div class="text-2xl font-black text-slate-900"><?= $submission['teacher_score'] ?> / 100</div>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($submission['teacher_feedback'])): ?>
                <div class="mt-3 pt-3 border-t border-black/10 text-xs sm:text-sm">
                    <strong>Catatan Guru:</strong> <?= nl2br(sanitize($submission['teacher_feedback'])) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($feedbackMsg): ?>
        <div class="mb-6 p-4 rounded-xl text-sm font-semibold border <?= $feedbackMsg['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-rose-50 border-rose-200 text-rose-800' ?>">
            <?= sanitize($feedbackMsg['text']) ?>
        </div>
    <?php endif; ?>

    <!-- Form Capstone -->
    <form method="POST" action="<?= base_url('student/capstone.php') ?>" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <!-- Pilihan Jalur Proyek -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fa-solid fa-route text-blue-600"></i> Pilih Jalur Proyek Anda
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex flex-col justify-between hover:border-purple-400 has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50/40">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900 text-sm">Jalur 1: Game Teks</span>
                            <input type="radio" name="track_choice" value="game" <?= $currentTrack === 'game' ? 'checked' : '' ?> onchange="updateTemplate('game')" class="text-purple-600 focus:ring-purple-500">
                        </div>
                        <p class="text-xs text-slate-500">Buat game RPG bertema tebak angka, pertarungan kuis, atau petualangan teks dengan sistem skor.</p>
                    </div>
                </label>

                <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex flex-col justify-between hover:border-emerald-400 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900 text-sm">Jalur 2: Kasir Toko</span>
                            <input type="radio" name="track_choice" value="kasir" <?= $currentTrack === 'kasir' ? 'checked' : '' ?> onchange="updateTemplate('kasir')" class="text-emerald-600 focus:ring-emerald-500">
                        </div>
                        <p class="text-xs text-slate-500">Aplikasi kasir menu kafe: hitung total, diskon member, dan cetak struk transaksi.</p>
                    </div>
                </label>

                <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex flex-col justify-between hover:border-blue-400 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/40">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-900 text-sm">Jalur 3: Buku Nilai CLI</span>
                            <input type="radio" name="track_choice" value="nilai" <?= $currentTrack === 'nilai' ? 'checked' : '' ?> onchange="updateTemplate('nilai')" class="text-blue-600 focus:ring-blue-500">
                        </div>
                        <p class="text-xs text-slate-500">Aplikasi input data nilai siswa rombel, cari nilai tertinggi, rata-rata, dan status kelulusan.</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Editor Proyek Akhir + Mobile Shortcut Toolbar -->
        <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
            <div class="bg-slate-950 px-4 py-3 border-b border-slate-800 flex items-center justify-between">
                <span class="text-xs font-mono text-slate-300 font-semibold flex items-center gap-2">
                    <i class="fa-brands fa-python text-yellow-400"></i> capstone_project.py
                </span>
                <button type="button" onclick="runStudentPythonCode('capstoneEditor', 'capstoneTerminalOutput')" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition flex items-center gap-1.5">
                    <i class="fa-solid fa-play text-[10px]"></i> Jalankan Program
                </button>
            </div>

            <!-- Sticky Mobile Toolbar -->
            <div class="bg-slate-800/90 p-2 border-b border-slate-700/80 mobile-symbol-toolbar gap-1.5">
                <button type="button" onclick="insertSymbol('capstoneEditor', '    ', 0)" class="symbol-btn">Tab ⇥</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', ':', 0)" class="symbol-btn font-bold">:</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', '()', 1)" class="symbol-btn font-bold">( )</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', '&quot;&quot;', 1)" class="symbol-btn font-bold">&quot; &quot;</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', '\'\'', 1)" class="symbol-btn font-bold">&#039; &#039;</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', '[]', 1)" class="symbol-btn font-bold">[ ]</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', '{}', 1)" class="symbol-btn font-bold">{ }</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', ' = ', 0)" class="symbol-btn">=</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', ' == ', 0)" class="symbol-btn font-bold text-blue-600">==</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', ' + ', 0)" class="symbol-btn">+</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', ' - ', 0)" class="symbol-btn">-</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', ' * ', 0)" class="symbol-btn">*</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', ' / ', 0)" class="symbol-btn">/</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', '_', 0)" class="symbol-btn">_</button>
                <button type="button" onclick="insertSymbol('capstoneEditor', '# ', 0)" class="symbol-btn text-emerald-600">#</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-px bg-slate-800">
                <div class="p-3 bg-slate-900">
                    <textarea id="capstoneEditor" name="source_code" spellcheck="false" required
                        class="code-editor-area code-font w-full h-[380px] p-3 bg-slate-950 text-slate-100 rounded-xl border border-slate-800 focus:outline-none focus:ring-1 focus:ring-purple-500 text-xs sm:text-sm font-mono resize-none"
                    ><?= htmlspecialchars($currentCode) ?></textarea>
                </div>
                <div class="p-3 bg-slate-950 flex flex-col">
                    <div class="text-xs text-slate-400 font-mono mb-2 flex items-center justify-between">
                        <span><i class="fa-solid fa-terminal text-emerald-400 mr-1"></i> Console Uji Coba:</span>
                        <button type="button" onclick="document.getElementById('capstoneTerminalOutput').innerHTML='<div class=\'text-slate-500 italic\'>Terminal siap.</div>';" class="text-[11px] hover:text-slate-200">Clear</button>
                    </div>
                    <div id="capstoneTerminalOutput" class="code-terminal flex-1 p-3 bg-black/60 rounded-xl border border-slate-900 text-xs sm:text-sm overflow-y-auto font-mono text-emerald-400 min-h-[340px]">
                        <div class="text-slate-500 italic">Tekan "Jalankan Program" untuk menguji kelancaran aplikasi Anda.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan & Dokumentasi Singkat -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                Catatan / Cara Penggunaan untuk Guru Pembimbing (Opsional)
            </label>
            <textarea id="notes" name="notes" rows="3"
                class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white transition"
                placeholder="Jelaskan fitur-fitur yang Anda buat dan cara menjalankannya..."
            ><?= sanitize($submission['notes'] ?? '') ?></textarea>
        </div>

        <!-- Tombol Submit -->
        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm shadow-lg shadow-purple-900/25 transition flex items-center gap-2">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <?= $submission ? 'Kirim Pembaruan Proyek' : 'Kumpulkan Proyek Akhir (+50 XP)' ?>
            </button>
        </div>
    </form>
</div>

<script src="<?= base_url('assets/js/pyodide-engine.js') ?>"></script>
<script>
    const templates = <?= json_encode($templates) ?>;
    function updateTemplate(track) {
        const editor = document.getElementById('capstoneEditor');
        if (confirm('Ubah template kode sesuai jalur ' + track + '? (Kode yang belum tersimpan akan diganti)')) {
            editor.value = templates[track] || '';
        }
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
