/**
 * PyLMS Pyodide WebAssembly Engine
 * Menjalankan kode Python siswa secara client-side di browser HP/Laptop.
 */

let pyodideInstance = null;
let isPyodideLoading = false;
let pyodideLoadPromise = null;

/**
 * Load Pyodide runtime dari CDN
 */
async function getPyodide(statusElementId = null) {
    if (pyodideInstance) return pyodideInstance;

    if (isPyodideLoading) {
        return pyodideLoadPromise;
    }

    isPyodideLoading = true;
    pyodideLoadPromise = new Promise(async (resolve, reject) => {
        try {
            if (statusElementId) {
                const el = document.getElementById(statusElementId);
                if (el) el.innerHTML = '<span class="text-amber-500"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Memuat Mesin Python (WebAssembly)...</span>';
            }

            // Load Pyodide script jika belum ada di DOM
            if (typeof loadPyodide === 'undefined') {
                await new Promise((res, rej) => {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/pyodide/v0.25.1/full/pyodide.js';
                    script.onload = res;
                    script.onerror = rej;
                    document.head.appendChild(script);
                });
            }

            // Inisialisasi Pyodide
            pyodideInstance = await loadPyodide({
                indexURL: 'https://cdn.jsdelivr.net/pyodide/v0.25.1/full/'
            });

            // Patch input() agar menggunakan dialog bawaan browser yang aman di HP
            await pyodideInstance.runPythonAsync(`
import sys
import js
import builtins

def _custom_input(prompt_msg=""):
    # Tampilkan prompt dialog di browser
    res = js.prompt(str(prompt_msg))
    if res is None:
        return ""
    # Tampilkan juga input siswa ke terminal agar mirip console asli
    sys.stdout.write(f"{prompt_msg}{res}\\n")
    return str(res)

builtins.input = _custom_input
`);

            if (statusElementId) {
                const el = document.getElementById(statusElementId);
                if (el) el.innerHTML = '<span class="text-emerald-500"><i class="fa-solid fa-check-circle mr-1"></i> Python 3 Siap!</span>';
            }

            isPyodideLoading = false;
            resolve(pyodideInstance);
        } catch (err) {
            isPyodideLoading = false;
            if (statusElementId) {
                const el = document.getElementById(statusElementId);
                if (el) el.innerHTML = '<span class="text-rose-500"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Gagal memuat Python</span>';
            }
            reject(err);
        }
    });

    return pyodideLoadPromise;
}

/**
 * Jalankan kode Python bebas dari textarea editor
 */
async function runStudentPythonCode(editorId, terminalOutputId, statusElementId = null) {
    const editor = document.getElementById(editorId);
    const terminal = document.getElementById(terminalOutputId);
    if (!editor || !terminal) return;

    const code = editor.value;

    // Di HP, otomatis alihkan tampilan ke tab hasil/terminal
    if (window.innerWidth < 768 && typeof window.switchMobileTab === 'function') {
        window.switchMobileTab('output');
    }

    terminal.innerHTML = '<div class="text-slate-400 py-1"><i class="fa-solid fa-play fa-fade text-blue-400 mr-2"></i>Menjalankan program...</div>';

    try {
        const pyodide = await getPyodide(statusElementId);

        // Buat buffer penangkap stdout & stderr
        let outputBuffer = "";
        pyodide.setStdout({
            batched: (text) => {
                outputBuffer += text + "\n";
            }
        });
        pyodide.setStderr({
            batched: (text) => {
                outputBuffer += "[Error] " + text + "\n";
            }
        });

        const startTime = performance.now();
        await pyodide.runPythonAsync(code);
        const duration = ((performance.now() - startTime) / 1000).toFixed(2);

        if (outputBuffer.trim() === "") {
            terminal.innerHTML = `<div class="text-slate-400 italic">Program selesai dieksekusi dalam ${duration} detik (tanpa teks output).</div>`;
        } else {
            terminal.innerHTML = `<pre class="text-emerald-400 font-mono text-xs sm:text-sm whitespace-pre-wrap">${escapeHtml(outputBuffer)}</pre>`
                + `<div class="text-[11px] text-slate-500 border-t border-slate-800 mt-3 pt-2">Selesai dalam ${duration}s &bull; Return code 0</div>`;
        }
    } catch (err) {
        terminal.innerHTML = `<pre class="text-rose-400 font-mono text-xs sm:text-sm whitespace-pre-wrap">${escapeHtml(err.message)}</pre>`
            + `<div class="text-[11px] text-rose-500/80 border-t border-slate-800 mt-3 pt-2">Terjadi error pada sintaks atau logika kode di atas.</div>`;
    }
}

/**
 * Auto-grader untuk tantangan kode (Coding Challenges)
 */
async function evaluateChallengeCode(editorId, testCasesJson, challengeId, apiUrl, csrfToken, resultsContainerId) {
    const editor = document.getElementById(editorId);
    const container = document.getElementById(resultsContainerId);
    if (!editor || !container) return;

    const studentCode = editor.value;
    container.innerHTML = '<div class="p-4 bg-slate-900 text-slate-300 rounded-xl text-center"><i class="fa-solid fa-spinner fa-spin text-blue-400 text-xl mb-2"></i><br>Sedang menguji kode Anda terhadap seluruh test case...</div>';

    try {
        const pyodide = await getPyodide();
        const testCases = JSON.parse(testCasesJson);
        let allPassed = true;
        let testResults = [];

        for (let i = 0; i < testCases.length; i++) {
            const tc = testCases[i];
            let passed = false;
            let actualOutput = "";
            let errorMsg = "";

            try {
                // Tangkap output
                let runOutput = "";
                pyodide.setStdout({ batched: (t) => runOutput += t + "\n" });
                pyodide.setStderr({ batched: (t) => runOutput += t + "\n" });

                // Jika test case menyediakan mock input
                let testCode = "";
                if (tc.inputs && Array.isArray(tc.inputs)) {
                    testCode += `
_mock_inputs = ${JSON.stringify(tc.inputs)}
_mock_idx = 0
def _test_input(p=""):
    global _mock_idx
    if _mock_idx < len(_mock_inputs):
        val = str(_mock_inputs[_mock_idx])
        _mock_idx += 1
        return val
    return ""
builtins.input = _test_input
`;
                }

                // Tambahkan kode siswa
                testCode += "\n" + studentCode + "\n";

                // Jika ada assertion check
                if (tc.assertion) {
                    testCode += "\nassert " + tc.assertion + ", 'Assertion failed'\n";
                }

                await pyodide.runPythonAsync(testCode);

                actualOutput = runOutput.trim();

                // Evaluasi kebenaran
                if (tc.expected_output !== undefined) {
                    // Normalisasi pembanding string: toleran line-ending dan trailing whitespace tiap baris
                    const cleanActual = normalizeOutput(actualOutput);
                    const cleanExpected = normalizeOutput(tc.expected_output);
                    passed = (cleanActual === cleanExpected);
                } else {
                    // Berhasil jika tidak throw assertion error
                    passed = true;
                }

            } catch (tcErr) {
                passed = false;
                errorMsg = tcErr.message;
            }

            if (!passed) allPassed = false;

            testResults.push({
                testIndex: i + 1,
                name: tc.name || `Uji Kasus #${i + 1}`,
                passed: passed,
                expected: tc.expected_output || tc.assertion || '-',
                actual: actualOutput || errorMsg
            });
        }

        // Render Hasil Uji
        let html = '<div class="space-y-3">';
        testResults.forEach(r => {
            const statusClass = r.passed ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-rose-500/10 border-rose-500/30 text-rose-400';
            const icon = r.passed ? '<i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>' : '<i class="fa-solid fa-circle-xmark text-rose-400 text-lg"></i>';

            html += `
            <div class="border rounded-2xl p-4 ${statusClass} flex flex-col gap-2 text-xs sm:text-sm">
                <div class="flex items-center justify-between font-bold">
                    <span class="flex items-center gap-2">${icon} ${escapeHtml(r.name)}</span>
                    <span class="text-xs uppercase px-2.5 py-0.5 rounded-full ${r.passed ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300 font-extrabold'}">${r.passed ? 'LOLOS (PASSED)' : 'BELUM SESUAI'}</span>
                </div>
                ${!r.passed ? `
                <div class="bg-black/60 border border-slate-700/60 rounded-xl p-3 font-mono text-[11px] sm:text-xs text-slate-200 mt-1 space-y-2.5">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-bullseye text-blue-400"></i> Output yang Diharapkan:
                        </div>
                        <pre class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 text-emerald-400 whitespace-pre-wrap leading-relaxed overflow-x-auto shadow-inner">${escapeHtml(r.expected)}</pre>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-rose-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-triangle-exclamation text-rose-400"></i> Hasil Output Anda:
                        </div>
                        <pre class="bg-slate-950 p-2.5 rounded-lg border border-rose-900/40 text-rose-300 whitespace-pre-wrap leading-relaxed overflow-x-auto shadow-inner">${escapeHtml(r.actual)}</pre>
                    </div>
                </div>` : ''}
            </div>`;
        });
        html += '</div>';

        // Jika semua lolos, kirim hasil ke backend API
        if (allPassed) {
            html += `
            <div class="mt-4 p-4 bg-gradient-to-r from-emerald-600 to-green-600 rounded-xl text-white text-center shadow-lg">
                <i class="fa-solid fa-trophy text-3xl text-yellow-300 mb-2"></i>
                <h4 class="font-bold text-base">Hebat! Semua Test Case Berhasil!</h4>
                <p class="text-xs text-emerald-100 mb-3">Tantangan ini telah terselesaikan dan nilai Anda telah tercatat.</p>
                <div id="saveProgressStatus" class="text-xs font-semibold"></div>
            </div>`;

            // AJAX submit
            fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    challenge_id: challengeId,
                    student_code: studentCode,
                    csrf_token: csrfToken
                })
            })
            .then(res => res.json())
            .then(data => {
                const stat = document.getElementById('saveProgressStatus');
                if (data.status === 'success') {
                    if (stat) stat.innerHTML = `<span class="bg-white/20 px-3 py-1 rounded-full"><i class="fa-solid fa-star text-yellow-300 mr-1"></i> +${data.xp_awarded} XP Berhasil Ditambahkan!</span>`;
                    setTimeout(() => {
                        if (data.next_url) {
                            window.location.href = data.next_url;
                        }
                    }, 2000);
                } else {
                    if (stat) stat.innerHTML = `<span class="text-rose-200">${escapeHtml(data.message)}</span>`;
                }
            })
            .catch(e => {
                console.error(e);
            });
        }

        container.innerHTML = html;

    } catch (generalErr) {
        container.innerHTML = `<div class="p-4 bg-rose-900/40 border border-rose-500/40 text-rose-300 rounded-xl text-xs sm:text-sm">Gagal menjalankan pengujian: ${escapeHtml(generalErr.message)}</div>`;
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function normalizeOutput(str) {
    if (typeof str !== 'string') return '';
    return str
        .replace(/\r\n/g, "\n")
        .replace(/\r/g, "\n")
        .split("\n")
        .map(line => line.trimEnd())
        .join("\n")
        .trim();
}
