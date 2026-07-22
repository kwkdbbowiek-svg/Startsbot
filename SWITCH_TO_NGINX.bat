@echo off
cls
echo ╔════════════════════════════════════════╗
echo ║  SWITCH TO NGINX + PHP-FPM            ║
echo ║  Apache muammosiz!                     ║
echo ╚════════════════════════════════════════╝
echo.

echo Apache bilan muammo ko'p...
echo Nginx + PHP-FPM ga o'tamiz (100%% ishlar!)
echo.

git add .
git commit -m "Switch to Nginx + PHP-FPM (Apache MPM issues)"
git push origin main

if %errorlevel% equ 0 (
    echo.
    echo ╔════════════════════════════════════════╗
    echo ║  ✓ PUSHED TO GITHUB!                   ║
    echo ╚════════════════════════════════════════╝
    echo.
    echo Railway deploy qilmoqda...
    echo 3-4 daqiqa kuting (Nginx o'rnatiladi)
    echo.
    echo Deploy Logs: Railway Dashboard
    echo.
    echo Kutilayotgan:
    echo ✓ Installing nginx
    echo ✓ Installing supervisor
    echo ✓ Starting Container
    echo ✓ Starting supervisord
    echo.
) else (
    echo.
    echo ✗ Push xatosi!
)

pause
