# 🚂 Railway.app ga Deploy Qilish

## 🎯 Afzalliklari

✅ **Bepul**: $5/oy kredit (hobby plan)  
✅ **Oson**: Git push → avtomatik deploy  
✅ **SSL**: Avtomatik HTTPS  
✅ **Database**: MySQL avtomatik  
✅ **Logs**: Real-time ko'rish  
✅ **Scaling**: Avtomatik  

---

## 📋 Talab Qilinadigan Fayllar

Men yaratdim:
- ✅ `Dockerfile` - Railway build uchun
- ✅ `railway.toml` - Railway config
- ✅ `.dockerignore` - Keraksiz fayllar
- ✅ `bot/config.railway.php` - Environment variables uchun

---

## 🚀 Deploy Qilish (10 daqiqa)

### 1️⃣ Railway.app ga Ro'yxatdan O'tish

1. **https://railway.app** ga kiring
2. **"Login with GitHub"** (tavsiya) yoki Email
3. GitHub account bilan ulaning

---

### 2️⃣ GitHub Repository Yaratish

#### Variant A: GitHub Desktop (Oson)

1. **GitHub Desktop** yuklab oling
2. `startsbot` papkasini repository qiling:
   - File → Add Local Repository
   - `C:\Users\ISHONCH\OneDrive\Desktop\startsbot`
   - Publish repository → **Public** yoki **Private**

#### Variant B: Git CMD (Terminal)

```bash
cd C:\Users\ISHONCH\OneDrive\Desktop\startsbot

# Git init
git init
git add .
git commit -m "Initial commit - Telegram Stars Bot"

# GitHub da repo yarating, keyin:
git remote add origin https://github.com/SIZNING_USERNAME/startsbot.git
git branch -M main
git push -u origin main
```

---

### 3️⃣ Railway ga Deploy

1. **Railway Dashboard:** https://railway.app/dashboard
2. **"New Project"** tugmasini bosing
3. **"Deploy from GitHub repo"** ni tanlang
4. **Repository ni tanlang:** `startsbot`
5. **"Deploy Now"** bosing

Railway avtomatik:
- Dockerfile ni ko'radi
- Docker image build qiladi
- Deploy qiladi
- URL beradi (masalan: `your-app.up.railway.app`)

---

### 4️⃣ MySQL Database Qo'shish

1. Railway dashboard da **"New"** → **"Database"** → **"Add MySQL"**
2. MySQL avtomatik yaratiladi
3. **Connect** qilish uchun:
   - Railway avtomatik `DATABASE_URL` environment variable yaratadi
   - `bot/config.railway.php` avtomatik ishlatadi

---

### 5️⃣ Environment Variables Sozlash

Railway dashboard da:

1. **Project Settings** → **Variables**
2. Quyidagilarni qo'shing:

```bash
BOT_TOKEN=1234567890:ABCdefGHIjklMNOpqrsTUVwxyz
BOT_USERNAME=@YourBotUsername
WEBAPP_URL=https://your-app.up.railway.app/webapp/index.html

# Database (Railway avtomatik beradi, lekin manual ham qo'shish mumkin)
# DB_HOST=containers-us-west-xxx.railway.app
# DB_PORT=5432
# DB_NAME=railway
# DB_USER=postgres
# DB_PASS=xxxxx
```

3. **Save** bosing (avtomatik redeploy bo'ladi)

---

### 6️⃣ Database Schema Import Qilish

#### Variant A: Railway MySQL Client (Web)

1. Railway da **MySQL** servisiga kiring
2. **"Connect"** → **"MySQL Client"** (web-based)
3. `database/schema.sql` ni nusxalang va paste qiling
4. Execute qiling

#### Variant B: MySQL Workbench

1. Railway da MySQL **connection details** ni oling:
   - Host
   - Port
   - User
   - Password
   - Database
2. MySQL Workbench da yangi connection yarating
3. `schema.sql` ni import qiling

#### Variant C: Railway CLI

```bash
# Railway CLI o'rnatish
npm install -g @railway/cli

# Login
railway login

# Project ga ulash
railway link

# MySQL ga kirish
railway connect mysql

# Schema import
source /path/to/schema.sql
```

---

### 7️⃣ config.php ni config.railway.php ga O'zgartirish

Railway da environment variables ishlatish uchun:

**bot/webhook.php, bot/bot.php va barcha faylarda:**

```php
// O'zgartiring:
require_once 'config.php';

// Bu ga:
require_once 'config.railway.php';
```

Yoki global ravishda `config.php` ni `config.railway.php` ga rename qiling:

```bash
cd bot
mv config.php config.original.php
mv config.railway.php config.php
```

---

### 8️⃣ Webhook O'rnatish

Railway deploy bo'lgandan keyin:

1. Railway dashboardda **URL** ni nusxalang (masalan: `https://startsbot-production.up.railway.app`)

2. Browser da ochish:
```
https://startsbot-production.up.railway.app/setup_webhook.php
```

3. ✅ "Webhook muvaffaqiyatli o'rnatildi" xabarini ko'rishingiz kerak

---

### 9️⃣ Test Qilish

1. **Bot test:**
   ```
   Telegram → @YourBot → /start
   ```

2. **Web App test:**
   ```
   https://startsbot-production.up.railway.app/webapp/index.html
   ```

3. **Admin panel:**
   ```
   https://startsbot-production.up.railway.app/admin/
   Parol: admin123
   ```

---

## 🔧 Railway CLI Buyruqlari

```bash
# Railway CLI o'rnatish
npm install -g @railway/cli

# Login
railway login

# Projectni link qilish
railway link

# Logs ko'rish
railway logs

# Environment variables ko'rish
railway variables

# Local da test qilish
railway run npm start

# Deploy qilish (git push o'rniga)
railway up
```

---

## 🐛 Troubleshooting

### Deploy failed

**Xatolik:** `Error: Cannot find module 'config.php'`

**Yechim:** 
```bash
# bot/config.railway.php ni config.php ga rename qiling
# yoki barcha require_once 'config.php' ni config.railway.php ga o'zgartiring
```

### Database connection error

**Xatolik:** `SQLSTATE[HY000] [2002] Connection refused`

**Yechim:**
1. Railway MySQL servisining environment variables to'g'ri ekanligini tekshiring
2. `DATABASE_URL` variable bor ekanligini tekshiring
3. MySQL servis ishlab turganini tekshiring (Railway da)

### Webhook failed

**Xatolik:** `Invalid SSL certificate`

**Yechim:**
- Railway avtomatik SSL beradi, bir necha daqiqa kuting
- `https://` bilan ishlatayotganingizni tekshiring

---

## 💰 Narxlar (2024)

| Plan | Narx | Xususiyatlar |
|------|------|-------------|
| **Hobby** | $5/oy kredit (bepul) | 500 hours/oy, 512MB RAM, 1GB storage |
| **Developer** | $20/oy | Unlimited, 8GB RAM, 100GB storage |

**Hobby plan yetarli** kichik botlar uchun!

---

## 🔄 Continuous Deployment

Railway ga connect qilgandan keyin:

```bash
# Fayllarni o'zgartiring
# Masalan: bot/bot.php

# Git ga commit qiling
git add .
git commit -m "Update bot features"
git push origin main

# Railway avtomatik deploy qiladi! 🎉
```

---

## 📊 Railway vs 5fh.ru

| | Railway | 5fh.ru |
|---|---------|---------|
| **Deploy** | `git push` | FTP/SSH manual |
| **SSL** | Avtomatik | Manual setup |
| **Database** | Avtomatik | Manual create |
| **Logs** | Real-time | Fayl orqali |
| **Scaling** | Avtomatik | Manual |
| **CI/CD** | Built-in | Yo'q |
| **Monitoring** | Built-in | Yo'q |
| **Backup** | Avtomatik | Manual |
| **Narx** | $5 bepul | To'lov |

---

## ✅ Railway Afzalliklari

1. **Git-based workflow** - Har bir commit = deploy
2. **Instant SSL** - HTTPS avtomatik
3. **Environment variables** - Xavfsiz config
4. **Auto-scaling** - Traffic ortsa, avtomatik scale
5. **Logs & Monitoring** - Real-time ko'rish
6. **Database backups** - Avtomatik
7. **Free tier** - $5/oy kredit

---

## 🎯 Tavsiya

**Ikkalasini ham ishlatish:**
- **Railway:** Production (stable, avtomatik)
- **5fh.ru:** Backup/Test (manual control)

---

**Tayyor!** Railway ga deploy qilish uchun barcha fayllar tayyor! 🚀

Keyingi qadam: GitHub repository yarating va Railway ga ulang!
