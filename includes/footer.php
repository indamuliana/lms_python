<?php
/**
 * Global Footer Template
 */
?>
    </main>

    <!-- Footer Bawah -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-1.5 font-medium">
                <i class="fa-brands fa-python text-blue-600 text-sm"></i>
                <span><?= APP_NAME ?> &copy; <?= date('Y') ?>. Didesain untuk kemudahan belajar Python di HP.</span>
            </div>
            <div class="text-slate-400 text-[11px]">
                <span>Shared Hosting cPanel Ready &bull; Client-Side WebAssembly</span>
            </div>
        </div>
    </footer>

    <!-- Global App JS -->
    <script src="<?= base_url('assets/js/mobile-toolbar.js') ?>"></script>
</body>
</html>
