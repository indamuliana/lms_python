#!/bin/bash
# PyLMS Local Server Runner
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR"

# Pastikan path PHP FlyEnv terbaca
export PATH="$HOME/Library/FlyEnv/app/static-php-8.4.23/bin:$PATH"

LOCAL_IP=$(ipconfig getifaddr en0 2>/dev/null || ipconfig getifaddr en1 2>/dev/null || echo "localhost")

echo "=========================================================="
echo "    PyLMS - Platform Belajar Python Interaktif Siswa"
echo "=========================================================="
echo " Server lokal aktif:"
echo " 💻 Akses di Laptop       : http://localhost:8000"
echo " 📱 Akses di HP (1 Wi-Fi) : http://${LOCAL_IP}:8000"
echo "----------------------------------------------------------"
echo " 🔑 Akun Guru Default     : User: guru | Password: password123"
echo " 💾 Database MySQL        : lms_python (Aktif)"
echo " Tekan Ctrl + C untuk mematikan server."
echo "=========================================================="

php -S 0.0.0.0:8000
