@echo off
echo ========================================
echo Telegram Stars Bot - Deployment Script
echo ========================================
echo.

REM Kompyuter papkasini ZIP ga o'girish
echo [1/3] ZIP fayl yaratilmoqda...
powershell -command "Compress-Archive -Path '.\bot', '.\webapp', '.\admin', '.\database', '.\setup_webhook.php', '.\.htaccess' -DestinationPath '.\telegram_stars_bot.zip' -Force"

if exist telegram_stars_bot.zip (
    echo ✓ ZIP fayl yaratildi: telegram_stars_bot.zip
    echo.
    echo [2/3] Faylni serverga yuklang:
    echo.
    echo Option 1: FileZilla
    echo   - Host: f0323.5fh.ru
    echo   - Username: f0323
    echo   - Password: 8c6664bba2ea682f
    echo   - telegram_stars_bot.zip ni yuklang
    echo.
    echo Option 2: FastPanel File Manager
    echo   - https://panel.5fh.ru/
    echo   - File Manager ^> Upload ^> telegram_stars_bot.zip
    echo.
    echo [3/3] Serverda ZIP ni ochinglz:
    echo   SSH: unzip telegram_stars_bot.zip
    echo   yoki FastPanel da Extract
    echo.
    echo ========================================
    echo Tayyor! ZIP fayl yaratildi.
    echo ========================================
    pause
) else (
    echo ✗ Xatolik: ZIP fayl yaratilmadi!
    pause
)
