@echo off
REM ===============================================
REM Firebase Google Auth - Quick Setup Script (Windows)
REM ===============================================
REM Jalankan: firebase_setup.bat

echo.
echo ========================================
echo Firebase Google Auth Setup untuk Laravel
echo ========================================
echo.

REM Step 1: Buat folder storage
echo [1/5] Membuat folder storage untuk Firebase credentials...
if not exist "storage\firebase" mkdir storage\firebase
echo ✓ Folder dibuat: storage\firebase
echo.

REM Step 2: Install dependencies
echo [2/5] Installing Composer dependencies...
call composer install
if errorlevel 1 (
    echo ✗ Composer install failed!
    pause
    exit /b 1
)
echo ✓ Dependencies installed
echo.

REM Step 3: Generate app key
echo [3/5] Generating APP_KEY...
if not exist ".env" (
    copy .env.example .env
    echo ✓ File .env dibuat dari .env.example
) else (
    echo ✓ File .env sudah ada
)

php artisan key:generate --force
echo ✓ APP_KEY generated
echo.

REM Step 4: Run migrations
echo [4/5] Running database migrations...
php artisan migrate --force
echo ✓ Migrations completed
echo.

REM Step 5: Instructions
echo [5/5] Setup Complete!
echo.
echo ========================================
echo Next Steps:
echo ========================================
echo.
echo 1. Download serviceAccountKey.json dari Firebase Console:
echo    - Buka: https://console.firebase.google.com
echo    - Pilih project Anda
echo    - Settings (⚙️) > Service Accounts > Generate New Private Key
echo    - Simpan ke: storage\firebase\serviceAccountKey.json
echo.
echo 2. Update .env file:
echo    - Open: .env
echo    - Set FIREBASE_CREDENTIALS ke path lengkap file JSON
echo    - Contoh: FIREBASE_CREDENTIALS=C:\xampp\htdocs\Backend\storage\firebase\serviceAccountKey.json
echo.
echo 3. Start Laravel server:
echo    - php artisan serve
echo.
echo 4. Test API:
echo    - Buka: http://localhost:8000/api/auth/google-users
echo    - Seharusnya return empty list (belum ada users)
echo.
echo 5. Setup Flutter app:
echo    - Copy file Dart ke lib/ folder Flutter project
echo    - Update API_URL di main.dart
echo    - Run: flutter run
echo.
echo ========================================
echo Lihat FIREBASE_GOOGLE_AUTH_GUIDE.md untuk dokumentasi lengkap
echo ========================================
echo.

pause
