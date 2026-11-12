/**
 * PyLMS Mobile Toolbar & Editor Utility
 * Menyediakan pintasan keyboard HP untuk simbol pemrograman Python
 */

(function() {
    'use strict';

    window.insertSymbol = function(targetTextareaId, symbol, pairLength = 0) {
        const textarea = document.getElementById(targetTextareaId);
        if (!textarea) return;

        textarea.focus();
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;

        // Jika ada teks yang diblok dan ini adalah kurung/tanda kutip, bungkus teks tersebut
        if (start !== end && pairLength > 0) {
            const selectedText = text.substring(start, end);
            const openChar = symbol.charAt(0);
            const closeChar = symbol.charAt(1) || openChar;
            const replacement = openChar + selectedText + closeChar;
            
            textarea.value = text.substring(0, start) + replacement + text.substring(end);
            textarea.selectionStart = start + 1;
            textarea.selectionEnd = end + 1;
        } else {
            // Sisipkan karakter biasa
            textarea.value = text.substring(0, start) + symbol + text.substring(end);
            
            // Atur posisi kursor
            if (pairLength > 0) {
                // Taruh kursor di tengah pasangan kurung/tanda kutip
                const newPos = start + pairLength;
                textarea.selectionStart = newPos;
                textarea.selectionEnd = newPos;
            } else {
                const newPos = start + symbol.length;
                textarea.selectionStart = newPos;
                textarea.selectionEnd = newPos;
            }
        }

        // Trigger input event agar jika ada auto-save / state tracker mendeteksi perubahan
        textarea.dispatchEvent(new Event('input', { bubbles: true }));
    };

    // Helper untuk Undo / Redo
    window.editorUndo = function(targetTextareaId) {
        const textarea = document.getElementById(targetTextareaId);
        if (textarea) {
            textarea.focus();
            document.execCommand('undo');
        }
    };

    window.editorRedo = function(targetTextareaId) {
        const textarea = document.getElementById(targetTextareaId);
        if (textarea) {
            textarea.focus();
            document.execCommand('redo');
        }
    };

    // Helper untuk Mobile Tab Switching (Editor vs Terminal)
    window.switchMobileTab = function(tabName) {
        const editorPane = document.getElementById('editorPane');
        const outputPane = document.getElementById('outputPane');
        const tabEditorBtn = document.getElementById('tabEditorBtn');
        const tabOutputBtn = document.getElementById('tabOutputBtn');

        if (!editorPane || !outputPane) return;

        if (tabName === 'editor') {
            editorPane.classList.remove('hidden');
            outputPane.classList.add('hidden');
            if (tabEditorBtn) {
                tabEditorBtn.className = "flex-1 py-2 text-center text-xs font-bold border-b-2 border-blue-600 text-blue-600 bg-blue-50/50";
            }
            if (tabOutputBtn) {
                tabOutputBtn.className = "flex-1 py-2 text-center text-xs font-semibold text-slate-500 hover:text-slate-800";
            }
        } else {
            editorPane.classList.add('hidden');
            outputPane.classList.remove('hidden');
            if (tabOutputBtn) {
                tabOutputBtn.className = "flex-1 py-2 text-center text-xs font-bold border-b-2 border-blue-600 text-blue-600 bg-blue-50/50";
            }
            if (tabEditorBtn) {
                tabEditorBtn.className = "flex-1 py-2 text-center text-xs font-semibold text-slate-500 hover:text-slate-800";
            }
        }
    };

    // Tangani indentasi tombol fisik Keyboard Tab di textarea
    document.addEventListener('DOMContentLoaded', function() {
        const codeEditors = document.querySelectorAll('.code-editor-area');
        codeEditors.forEach(function(editor) {
            editor.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    e.preventDefault();
                    window.insertSymbol(this.id, '    ', 0);
                }
            });
        });

        // Proteksi Anti Copy-Paste & Klik Kanan di Halaman Siswa
        if (document.body.classList.contains('no-copy-student')) {
            // Blokir seleksi teks selain di textarea / input
            document.addEventListener('selectstart', function(e) {
                if (e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'INPUT') {
                    e.preventDefault();
                    return false;
                }
            });

            // Blokir event copy & cut di seluruh halaman
            document.addEventListener('copy', function(e) {
                e.preventDefault();
            });
            document.addEventListener('cut', function(e) {
                e.preventDefault();
            });

            // Blokir paste ke dalam editor latihan agar siswa wajib mengetik sendiri
            codeEditors.forEach(function(editor) {
                editor.addEventListener('paste', function(e) {
                    e.preventDefault();
                    alert('Fitur copy-paste dinonaktifkan. Silakan ketik kode Anda sendiri untuk melatih kemampuan.');
                });
            });

            // Blokir klik kanan / context menu selain di editor
            document.addEventListener('contextmenu', function(e) {
                if (e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'INPUT') {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });

})();
