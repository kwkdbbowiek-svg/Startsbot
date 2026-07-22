@echo off
chcp 65001 >nul
cls
echo ╔════════════════════════════════════════╗
echo ║  GitHub Push - Tezkor                  ║
echo ╚════════════════════════════════════════╝
echo.

cd /d "%~dp0"

echo [1/4] Git status...
git status

echo.
echo [2/4] Git add...
git add .

echo.
echo [3/4] Git commit...
git commit -m "Fix: Add libpq-dev for PostgreSQL support"

echo.
echo [4/4] Git push...
git push origin main

if %errorlevel% equ 0 (
    echo.
    echo ╔════════════════════════════════════════╗
    echo ║  ✓ MUVAFFAQIYATLI!                     ║
    echo ╚════════════════════════════════════════╝
    echo.
    echo Railway avtomatik redeploy qiladi...
    echo 2-3 daqiqa kuting.
    echo.
    echo Logs: Railway Dashboard → Deploy Logs
    echo.
) else (
    echo.
    echo ╔════════════════════════════════════════╗
    echo ║  ✗ PUSH XATOSI!                        ║
    echo ╚════════════════════════════════════════╝
    echo.
    echo Ehtimol:
    echo - GitHub authentication kerak
    echo - Internet bilan muammo
    echo.
    echo Manual push:
    echo   git push origin main
    echo.
)

pause
