# ⚡ Tezkor Yuklash - 3 Daqiqa

## 🎯 Eng Oson Usul (Boshlang'ichlar uchun)

### 1️⃣ ZIP Yaratish (30 soniya)

**Windows:**
1. `startsbot` papkasiga o'ng tugma bosing
2. "Send to" → "Compressed (zipped) folder"
3. `startsbot.zip` yaratiladi

**Yoki CMD da:**
```bash
# Bu papkada deploy.bat ni ishga tushiring
deploy.bat
```

### 2️⃣ FastPanel ga Kirish (30 soniya)

1. Browser da: **https://panel.5fh.ru/**
2. Login: `f0323`
3. Parol: `8c6664bba2ea682f`
4. "Войти" (Login) tugmasini bosing

### 3️⃣ Fayllarni Yuklash (1 daqiqa)

1. Chap menuda **"Файловый менеджер"** (File Manager)
2. `f0323.5fh.ru` papkasini oching
3. Yuqorida **"Загрузить"** (Upload) tugmasini bosing
4. `startsbot.zip` ni tanlang
5. Yuklash tugagandan keyin:
   - ZIP fayl ustiga o'ng tugma
   - **"Распаковать"** (Extract)
   - "ОК" bosing

### 4️⃣ config.php ni Tahrirlash (1 daqiqa)

1. File Manager da `bot/config.php` ni oching
2. Quyidagilarni o'zgartiring:

```php
// Line 20-22
define('BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE'); // @BotFather token

// Line 25-27
define('DB_NAME', 'f0323_starsbot');
define('DB_USER', 'f0323_starsbot');
define('DB_PASS', 'YOUR_DB_PASSWORD'); // Database parol

// Line 31
define('WEBAPP_URL', 'https://f0323.5fh.ru/webapp/index.html');
```

3. "Сохранить" (Save) bosing

### 5️⃣ Database Yaratish (30 soniya)

1. FastPanel chap menuda **"Базы данных"** (Databases)
2. **"+ Добавить"** (Add) tugmasini bosing
3. To'ldiring:
   - Имя БД: `f0323_starsbot`
   - Пользователь: `f0323_starsbot`
   - Пароль: kuchli parol (masalan: `StarBot2024!`)
4. "Создать" (Create) bosing
5. **phpMyAdmin** ga o'ting
6. `f0323_starsbot` ni tanlang
7. Yuqorida **"Импорт"** (Import)
8. "Выберите файл" → `database/schema.sql`
9. "Вперед" (Go) bosing

---

## ✅ Tayyor! Endi Test

### 1. Webhook
Browser: **https://f0323.5fh.ru/setup_webhook.php**
✅ "Webhook muvaffaqiyatli o'rnatildi" ko'rinadi

### 2. Bot Test
Telegram → botingizga → `/start`
✅ Javob keladi

### 3. Web App
Browser: **https://f0323.5fh.ru/webapp/index.html**
✅ Sahifa ochiladi (Dark mode + Neon)

### 4. Admin Panel
Browser: **https://f0323.5fh.ru/admin/**
- Login parol: `admin123`
✅ Dashboard ochiladi

---

## 🚨 Agar Xatolik Bo'lsa

### "Database connection error"
➡️ `config.php` da DB_USER, DB_PASS to'g'ri ekanligini tekshiring

### "500 Internal Server Error"
➡️ FastPanel → PHP → Version 8.0 yoki 8.1 ni tanlang

### "Webhook failed"
➡️ SSL sertifikat borligini tekshiring (FastPanel → SSL)

### Loglarni ko'rish
FastPanel → File Manager → `bot/logs/error_*.log`

---

## 📞 Yordam

Agar muammo bo'lsa, loglarni tekshiring:
```
bot/logs/error_2024-XX-XX.log
```

Yoki menga screenshot yuboring!

---

**Jami Vaqt: 3 daqiqa** ⏱️
