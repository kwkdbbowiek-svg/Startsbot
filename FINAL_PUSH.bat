@echo off
echo ╔════════════════════════════════════════╗
echo ║  FINAL FIX - Apache MPM               ║
echo ╚════════════════════════════════════════╝
echo.

git add .
git commit -m "Final Fix: Apache MPM - Direct load mpm_prefork"
git push origin main

if %errorlevel% equ 0 (
    echo.
    echo ✓ Pushed!
    echo Railway avtomatik redeploy qilmoqda...
    echo 3 daqiqa kuting.
) else (
    echo.
    echo ✗ Push xatosi!
)

pause
