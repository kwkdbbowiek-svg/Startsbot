@echo off
chcp 65001 >nul
echo ╔════════════════════════════════════════╗
echo ║  GitHub ga Yuklash - Avtomatik         ║
echo ╚════════════════════════════════════════╝
echo.

REM 1. .env mavjudligini tekshirish
if not exist .env (
    echo ⚠️  .env fayl yo'q!
    echo.
    echo Yaratish:
    copy .env.example .env
    echo ✓ .env yaratildi
    echo.
    echo Iltimos .env ni tahrirlang va haqiqiy tokenlarni kiriting:
    notepad .env
    echo.
    pause
)

echo [1/6] Git initialized?
if exist .git (
    echo ✓ Git mavjud
) else (
    echo → Git init...
    git init
    echo ✓ Initialized
)

echo.
echo [2/6] .gitignore tekshirilmoqda...
findstr /C:".env" .gitignore >nul 2>&1
if %errorlevel% equ 0 (
    echo ✓ .env ignore qilingan
) else (
    echo → .env ni .gitignore ga qo'shish...
    echo .env>> .gitignore
    echo bot/config.php>> .gitignore
    echo ✓ Qo'shildi
)

echo.
echo [3/6] Fayllar staging...
git add .
echo ✓ Staged

echo.
echo [4/6] .env yuklanmaganligini tekshirish...
git ls-files | findstr /C:".env" >nul 2>&1
if %errorlevel% equ 0 (
    echo ✗ XATO: .env Git cache da!
    echo → Olib tashlanmoqda...
    git rm --cached .env
    git rm --cached bot/config.php 2>nul
    echo ✓ Olib tashlandi
) else (
    echo ✓ .env xavfsiz
)

echo.
echo [5/6] Commit...
git commit -m "Initial commit - Telegram Stars Bot (Secure)" 2>nul
if %errorlevel% equ 0 (
    echo ✓ Committed
) else (
    echo → Allaqachon commit qilingan yoki o'zgarishlar yo'q
)

echo.
echo [6/6] Branch sozlash...
git branch -M main
echo ✓ Branch: main

echo.
echo ╔════════════════════════════════════════╗
echo ║  TAYYOR! Endi Push Qilish              ║
echo ╚════════════════════════════════════════╝
echo.

REM Remote repository tekshirish
git remote -v | findstr origin >nul 2>&1
if %errorlevel% neq 0 (
    echo → Remote qo'shilmoqda...
    git remote add origin https://github.com/kwkdbbowiek-svg/Startsbot.git
    echo ✓ Remote qo'shildi
) else (
    echo ✓ Remote mavjud
)

echo.
echo ════════════════════════════════════════
echo.
set /p PUSH="Push qilishni xohlaysizmi? (y/n): "

if /i "%PUSH%"=="y" (
    echo.
    echo → Pushing to GitHub...
    echo.
    
    git push -u origin main
    
    if %errorlevel% equ 0 (
        echo.
        echo ╔════════════════════════════════════════╗
        echo ║  ✓ MUVAFFAQIYATLI YUKLANDI!            ║
        echo ╚════════════════════════════════════════╝
        echo.
        echo Repository: https://github.com/kwkdbbowiek-svg/Startsbot
        echo.
        echo Keyingi qadamlar:
        echo 1. GitHub repository ni oching
        echo 2. .env.example borligini tekshiring
        echo 3. .env yo'qligini tekshiring
        echo.
    ) else (
        echo.
        echo ╔════════════════════════════════════════╗
        echo ║  ✗ PUSH XATOSI!                        ║
        echo ╚════════════════════════════════════════╝
        echo.
        echo Ehtimol:
        echo 1. GitHub authentication kerak
        echo 2. Repository allaqachon mavjud
        echo 3. Internet bilan muammo
        echo.
        echo Manual push:
        echo   git push -u origin main
        echo.
    )
) else (
    echo.
    echo Manual push uchun:
    echo   git push -u origin main
    echo.
)

echo.
pause
