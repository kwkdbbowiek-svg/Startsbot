# Xavfsizlik Tekshiruvi Skripti

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Telegram Stars Bot - Security Check" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

$errors = 0
$warnings = 0

# 1. .env fayl borligini tekshirish
Write-Host "[1/7] .env fayl tekshirilmoqda..." -ForegroundColor Yellow

if (Test-Path ".env") {
    Write-Host "  ✓ .env fayl mavjud" -ForegroundColor Green
} else {
    Write-Host "  ✗ .env fayl topilmadi!" -ForegroundColor Red
    Write-Host "    Yaratish: copy .env.example .env" -ForegroundColor Gray
    $errors++
}

# 2. .env.example borligini tekshirish
Write-Host "[2/7] .env.example tekshirilmoqda..." -ForegroundColor Yellow

if (Test-Path ".env.example") {
    Write-Host "  ✓ .env.example mavjud" -ForegroundColor Green
} else {
    Write-Host "  ✗ .env.example topilmadi!" -ForegroundColor Red
    $errors++
}

# 3. .gitignore da .env borligini tekshirish
Write-Host "[3/7] .gitignore tekshirilmoqda..." -ForegroundColor Yellow

if (Test-Path ".gitignore") {
    $gitignoreContent = Get-Content ".gitignore" -Raw
    if ($gitignoreContent -match "\.env") {
        Write-Host "  ✓ .env .gitignore da bor" -ForegroundColor Green
    } else {
        Write-Host "  ✗ .env .gitignore da yo'q!" -ForegroundColor Red
        Write-Host "    Qo'shish: echo .env >> .gitignore" -ForegroundColor Gray
        $errors++
    }
} else {
    Write-Host "  ✗ .gitignore topilmadi!" -ForegroundColor Red
    $errors++
}

# 4. config.secure.php borligini tekshirish
Write-Host "[4/7] config.secure.php tekshirilmoqda..." -ForegroundColor Yellow

if (Test-Path "bot/config.secure.php") {
    Write-Host "  ✓ bot/config.secure.php mavjud" -ForegroundColor Green
} else {
    Write-Host "  ✗ bot/config.secure.php topilmadi!" -ForegroundColor Red
    $errors++
}

# 5. Sensitive fayllarni tekshirish
Write-Host "[5/7] Sensitive fayllar tekshirilmoqda..." -ForegroundColor Yellow

$sensitiveFiles = @(
    "bot/config.php",
    ".env"
)

$foundSensitive = @()

foreach ($file in $sensitiveFiles) {
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        if ($content -match "BOT_TOKEN.*=.*[0-9]{8,}:") {
            $foundSensitive += $file
        }
    }
}

if ($foundSensitive.Count -gt 0) {
    Write-Host "  ⚠ Quyidagi faylarda hardcoded token topildi:" -ForegroundColor Yellow
    foreach ($file in $foundSensitive) {
        Write-Host "    - $file" -ForegroundColor Gray
    }
    Write-Host "    Bu fayllar GitHub ga yuklanmasin!" -ForegroundColor Gray
    $warnings++
} else {
    Write-Host "  ✓ Hardcoded token topilmadi" -ForegroundColor Green
}

# 6. Git initialized ekanligini tekshirish
Write-Host "[6/7] Git repository tekshirilmoqda..." -ForegroundColor Yellow

if (Test-Path ".git") {
    Write-Host "  ✓ Git initialized" -ForegroundColor Green
    
    # Git status tekshirish
    $gitStatus = git status --porcelain 2>&1
    
    if ($gitStatus -match "\.env$") {
        Write-Host "  ✗ .env Git ga qo'shilgan!" -ForegroundColor Red
        Write-Host "    Olib tashlash: git rm --cached .env" -ForegroundColor Gray
        $errors++
    } else {
        Write-Host "  ✓ .env Git ga qo'shilmagan" -ForegroundColor Green
    }
} else {
    Write-Host "  - Git hali initialized emas" -ForegroundColor Gray
}

# 7. README.md borligini tekshirish
Write-Host "[7/7] README.md tekshirilmoqda..." -ForegroundColor Yellow

if (Test-Path "README.md") {
    Write-Host "  ✓ README.md mavjud" -ForegroundColor Green
} else {
    Write-Host "  ⚠ README.md topilmadi" -ForegroundColor Yellow
    $warnings++
}

# Natijalar
Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  Natijalar" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan

if ($errors -eq 0 -and $warnings -eq 0) {
    Write-Host "✓ Barcha tekshiruvlar muvaffaqiyatli!" -ForegroundColor Green
    Write-Host ""
    Write-Host "GitHub ga yuklash uchun tayyor!" -ForegroundColor Green
} else {
    if ($errors -gt 0) {
        Write-Host "✗ $errors ta xato topildi!" -ForegroundColor Red
    }
    if ($warnings -gt 0) {
        Write-Host "⚠ $warnings ta ogohlantirish!" -ForegroundColor Yellow
    }
    Write-Host ""
    Write-Host "Xatolarni tuzatib, qaytadan tekshiring." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Keyingi qadamlar:" -ForegroundColor Cyan
Write-Host "1. .env faylni yarating va to'ldiring" -ForegroundColor Gray
Write-Host "2. git add . va git commit -m 'Initial commit'" -ForegroundColor Gray
Write-Host "3. git push origin main" -ForegroundColor Gray
Write-Host ""

Read-Host "Davom etish uchun Enter bosing..."
