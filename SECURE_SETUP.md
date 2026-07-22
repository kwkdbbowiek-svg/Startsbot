# 🔐 Xavfsiz GitHub ga Yuklash

## ⚠️ MUHIM: Token va Parollarni GitHub ga Yuklash XAVFLI!

Men sizga **xavfsiz versiya** tayyorladim:

---

## ✅ Yaratilgan Xavfsiz Fayllar:

1. **`.env.example`** - Namuna (GitHub ga yuklanadi)
2. **`.env`** - Haqiqiy ma'lumotlar (GitHub ga YUKLANMAYDI)
3. **`bot/config.secure.php`** - .env dan o'qiydi
4. **`.gitignore`** - .env ni ignore qiladi

---

## 🚀 Xavfsiz GitHub ga Yuklash (5 Qadam)

### 1️⃣ .env Faylni Yaratish

```bash
# Kompyuteringizda
cd C:\Users\ISHONCH\OneDrive\Desktop\startsbot

# .env.example dan .env yaratish
copy .env.example .env

# .env ni tahrirlash (Notepad yoki VS Code da)
notepad .env
```

**`.env` faylga haqiqiy ma'lumotlarni kiriting:**

```env
# Telegram Bot Configuration
BOT_TOKEN=1234567890:ABCdefGHIjklMNOpqrsTUVwxyz
BOT_USERNAME=@YourBotUsername

# Database Configuration
DB_HOST=localhost
DB_NAME=telegram_stars_bot
DB_USER=root
DB_PASS=your_strong_password_123

# Web App URL
WEBAPP_URL=https://f0323.5fh.ru/webapp/index.html

# API Keys
FRAGMENT_JWT_TOKEN=your_actual_jwt_token_here
SMMUPPER_API_KEY=9e7cf27c6922dc2

# Channel Settings
CHANNEL_USERNAME=@yourchannel
CHANNEL_ID=-1001234567890

# Manual Payment
MANUAL_CARD_NUMBER=8600 1234 5678 9012
MANUAL_CARD_HOLDER=ABDULLAYEV SARDOR

# Admin User ID
ADMIN_USER_ID=123456789

# Environment
APP_ENV=production
APP_DEBUG=false
```

### 2️⃣ config.php ni O'zgartirish

Barcha faylarda `config.php` ni `config.secure.php` ga o'zgartirish kerak:

**Avtomatik o'zgartirish (PowerShell):**

```powershell
# PowerShell da ishga tushiring
cd C:\Users\ISHONCH\OneDrive\Desktop\startsbot

# Barcha PHP fayllarida config.php ni config.secure.php ga almashtirish
Get-ChildItem -Recurse -Filter *.php | ForEach-Object {
    (Get-Content $_.FullName) -replace "require_once 'config.php';", "require_once 'config.secure.php';" | Set-Content $_.FullName
    (Get-Content $_.FullName) -replace 'require_once "config.php";', 'require_once "config.secure.php";' | Set-Content $_.FullName
    (Get-Content $_.FullName) -replace "require_once '../bot/config.php';", "require_once '../bot/config.secure.php';" | Set-Content $_.FullName
    (Get-Content $_.FullName) -replace "require_once '../../bot/config.php';", "require_once '../../bot/config.secure.php';" | Set-Content $_.FullName
    (Get-Content $_.FullName) -replace "require_once '../../../bot/config.php';", "require_once '../../../bot/config.secure.php';" | Set-Content $_.FullName
}

Write-Host "✅ Barcha fayllar yangilandi!" -ForegroundColor Green
```

### 3️⃣ .gitignore Tekshirish

`.gitignore` da quyidagilar borligini tekshiring:

```
# Environment Configuration (MUHIM!)
.env
bot/config.php

# Logs
bot/logs/*.log
*.log
```

### 4️⃣ GitHub ga Yuklash (XAVFSIZ)

```bash
cd C:\Users\ISHONCH\OneDrive\Desktop\startsbot

# Git init
git init

# Barcha fayllarni qo'shish
git add .

# .env yuklanmaganligini tekshirish (MUHIM!)
git status

# Agar .env ko'rinsa, uni ignore qiling:
echo .env >> .gitignore
git add .gitignore

# Commit
git commit -m "Initial commit - Telegram Stars Bot (Secure)"

# GitHub repository ulash
git branch -M main
git remote add origin https://github.com/kwkdbbowiek-svg/Startsbot.git

# Push
git push -u origin main
```

### 5️⃣ Serverda .env Yaratish

**5fh.ru serverda (SSH):**

```bash
cd /var/www/f0323/data/www/f0323.5fh.ru

# .env yaratish
nano .env

# Ichiga .env.example dan nusxalangan ma'lumotlarni kiriting
# Ctrl+O → Enter → Ctrl+X

# Ruxsatlarni sozlash
chmod 600 .env
```

**Railway da:**

Environment Variables da:
- Railway dashboard → Variables
- `.env` dagi barcha qiymatlarni qo'shing

---

## 🔍 Xavfsizlik Tekshiruvi

### GitHub da .env bor yoki yo'qligini tekshirish:

```bash
# Local da
git ls-files | grep .env

# Natija: .env.example (✅ to'g'ri)
# Agar .env ko'rinsa ❌ XATO!
```

### Agar .env yuklangan bo'lsa (tuzatish):

```bash
# Git cache dan olib tashlash
git rm --cached .env

# Commit
git commit -m "Remove .env from repository"

# Push
git push origin main

# GitHub da eski commitlarni tozalash (agar token expose bo'lgan bo'lsa)
# BFG Repo-Cleaner ishlatish: https://rtyley.github.io/bfg-repo-cleaner/
```

---

## 📝 Qisqa Yo'riqnoma (Copy-Paste)

```bash
# 1. Papkaga o'tish
cd C:\Users\ISHONCH\OneDrive\Desktop\startsbot

# 2. .env yaratish
copy .env.example .env
notepad .env
# Haqiqiy ma'lumotlarni kiriting va saqlang

# 3. Git init
git init
git add .

# 4. .env ignore qilinganligini tekshirish
git status | findstr .env
# .env.example ko'rinishi kerak, .env yo'q

# 5. Commit
git commit -m "Initial commit - Secure version"

# 6. GitHub ga push
git branch -M main
git remote add origin https://github.com/kwkdbbowiek-svg/Startsbot.git
git push -u origin main
```

---

## ✅ Xavfsizlik Checklist

- [ ] `.env.example` bor (template)
- [ ] `.env` yaratilgan (haqiqiy ma'lumotlar)
- [ ] `.gitignore` da `.env` bor
- [ ] `config.secure.php` ishlatiladi
- [ ] `git status` da `.env` ko'rinmaydi
- [ ] GitHub da `.env.example` bor, `.env` yo'q
- [ ] Serverda `.env` chmod 600

---

## 🚨 Agar Token Expose Bo'lsa

1. **Darhol yangi token yarating** (@BotFather da `/revoke`)
2. **GitHub repository private qiling** yoki o'chiring
3. **Git history tozalang** (BFG Repo-Cleaner)
4. **Yangi token bilan `.env` yangilang**

---

## 📚 Qo'shimcha Xavfsizlik

### GitHub Secrets (CI/CD uchun)

Agar GitHub Actions ishlatmoqchi bo'lsangiz:

1. GitHub repo → Settings → Secrets and variables → Actions
2. New repository secret:
   - `BOT_TOKEN`
   - `DB_PASS`
   - va boshqalar

### Environment-based Config

- **Development**: `.env.development`
- **Production**: `.env.production`
- **Testing**: `.env.testing`

---

## 🎯 Natija

✅ **Token va parollar GitHub ga yuklanmaydi**  
✅ **Har bir serverda alohida .env**  
✅ **Open source bo'lishi mumkin (token sizda)**  
✅ **CI/CD uchun tayyor**  

---

**MUHIM:** `.env` faylni hech qachon GitHub ga yuklang! Faqat `.env.example` yuklanadi!

---

**Tayyor! Endi xavfsiz tarzda GitHub ga yuklashingiz mumkin!** 🔐🚀
