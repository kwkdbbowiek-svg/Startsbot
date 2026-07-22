@echo off
echo ========================================
echo Telegram Stars Bot - GitHub Upload
echo ========================================
echo.

REM Tekshiruvni ishga tushirish
echo [1/5] Xavfsizlik tekshirilmoqda...
powershell -ExecutionPolicy Bypass -File check_security.ps1

echo.
echo [2/5] .env faylini tekshirish...
if not exist .env (
    echo ✗ .env fayl topilmadi!
    echo.
    echo .env yaratish:
    echo   1. copy .env.example .env
    echo   2. notepad .env
    echo   3. Haqiqiy tokenlarni kiriting
    echo.
    pause
    exit /b 1
)

echo ✓ .env mavjud
echo.

echo [3/5] Git initialized?
if not exist .git (
    echo Git initialized emas. Initialization...
    git init
    echo ✓ Git initialized
) else (
    echo ✓ Git allaqachon initialized
)

echo.
echo [4/5] Fayllarni staging...
git add .

echo.
echo [5/5] .env ignore qilinganligini tekshirish...
git status | findstr ".env$" > nul
if %errorlevel% equ 0 (
    echo ✗ XATO: .env Git ga qo'shilgan!
    echo.
    echo Tuzatish:
    echo   git rm --cached .env
    echo.
    pause
    exit /b 1
) else (
    echo ✓ .env ignore qilingan
)

echo.
echo ========================================
echo GitHub ga Yuklash Tayyor!
echo ========================================
echo.
echo Keyingi qadamlar:
echo.
echo 1. git commit -m "Initial commit - Secure version"
echo 2. git branch -M main
echo 3. git remote add origin https://github.com/kwkdbbowiek-svg/Startsbot.git
echo 4. git push -u origin main
echo.
echo ⚠️ ESLATMA: .env GitHub ga yuklanmaydi!
echo.
pause

REM Avtomatik commit va push (ixtiyoriy)
set /p AUTOPUSH="Avtomatik push qilishni xohlaysizmi? (y/n): "
if /i "%AUTOPUSH%"=="y" (
    echo.
    echo Committing...
    git commit -m "Initial commit - Telegram Stars Bot (Secure)"
    
    echo.
    echo Branching...
    git branch -M main
    
    echo.
    echo Adding remote...
    git remote add origin https://github.com/kwkdbbowiek-svg/Startsbot.git 2>nul
    
    echo.
    echo Pushing...
    git push -u origin main
    
    if %errorlevel% equ 0 (
        echo.
        echo ========================================
        echo ✓ Muvaffaqiyatli yuklandi!
        echo ========================================
        echo.
        echo Repository: https://github.com/kwkdbbowiek-svg/Startsbot
        echo.
    ) else (
        echo.
        echo ✗ Push xatosi!
        echo.
        echo Sabablari:
        echo - GitHub authentication kerak
        echo - Repository allaqachon mavjud
        echo - Internet aloqa yo'q
        echo.
    )
) else (
    echo.
    echo Manual ravishda yuklang:
    echo   git commit -m "Initial commit"
    echo   git branch -M main
    echo   git remote add origin https://github.com/kwkdbbowiek-svg/Startsbot.git
    echo   git push -u origin main
    echo.
)

pause
