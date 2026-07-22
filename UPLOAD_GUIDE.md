# Serverga Yuklash - Qadamlar

## 📦 Sizning Server Ma'lumotlari

```
Hosting: 5fh.ru (FastPanel)
Domain: f0323.5fh.ru
Login: f0323
Password: 8c6664bba2ea682f
```

## 🚀 Usul 1: FileZilla (TAVSIYA ETILADI)

### 1. FileZilla yuklab oling
https://filezilla-project.org/download.php?type=client

### 2. FileZilla da ulanish

**FTP orqali:**
```
Host: ftp.f0323.5fh.ru
Username: f0323
Password: 8c6664bba2ea682f
Port: 21
```

**SFTP orqali (xavfsizroq):**
```
Host: sftp://f0323.5fh.ru
Username: f0323
Password: 8c6664bba2ea682f
Port: 22
```

### 3. Fayllarni yuklash

1. FileZilla ochilganda:
   - **Chap tomon:** Kompyuteringiz (local)
   - **O'ng tomon:** Server (remote)

2. Chap tomonda:
   ```
   C:\Users\ISHONCH\OneDrive\Desktop\startsbot\
   ```
   papkasiga boring

3. O'ng tomonda:
   ```
   /home/f0323/f0323.5fh.ru/
   ```
   papkasiga boring

4. **Kerakli fayllar va papkalarni yuklash:**

#### MUHIM: Faqat kerakli fayllarni yuklang!

```
✅ Yuklash kerak:
- bot/ (butun papka)
- webapp/ (butun papka)
- admin/ (butun papka)
- database/ (butun papka)
- setup_webhook.php
- .htaccess

❌ Yuklamaslik:
- .vscode/
- .gitignore
- *.md fayllar (README, INSTALLATION, va h.k.)
```

5. Fayllarni drag & drop qiling (tortib tashlang)

### 4. Papka Strukturasi Serverda

Natijada serverda quyidagi struktura bo'lishi kerak:

```
/home/f0323/f0323.5fh.ru/
├── bot/
│   ├── config.php
│   ├── bot.php
│   ├── webhook.php
│   └── logs/ (avtomatik yaratiladi)
├── webapp/
│   ├── index.html
│   ├── css/
│   ├── js/
│   ├── api/
│   └── uploads/ (avtomatik yaratiladi)
├── admin/
│   ├── index.php
│   ├── settings.php
│   ├── broadcast.php
│   └── payments.php
├── database/
│   └── schema.sql
├── setup_webhook.php
└── .htaccess
```

---

## 🚀 Usul 2: FastPanel File Manager

### 1. FastPanel ga kiring
```
https://panel.5fh.ru/
Login: f0323
Password: 8c6664bba2ea682f
```

### 2. File Manager ga o'ting
- Chap menuda "Файловый менеджер" (File Manager)
- `f0323.5fh.ru` papkasini oching

### 3. Fayllarni yuklash
- "Загрузить" (Upload) tugmasini bosing
- ZIP fayl yaratgan bo'lsangiz, uni yuklang
- Keyin "Распаковать" (Extract) qiling

**Yoki:**
- Har bir papkani alohida yuklang (vaqt ko'p ketadi)

---

## 🔧 Yuklashdan Keyin

### 1. config.php ni tahrirlash

FileZilla da yoki FastPanel File Manager da:

1. `bot/config.php` faylni oching
2. Quyidagilarni o'zgartiring:

```php
// Bot Token (@BotFather dan)
define('BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE');

// Database (FastPanel da MySQL yarating)
define('DB_HOST', 'localhost');
define('DB_NAME', 'f0323_starsbot');
define('DB_USER', 'f0323_starsbot');
define('DB_PASS', 'YOUR_DB_PASSWORD');

// Web App URL
define('WEBAPP_URL', 'https://f0323.5fh.ru/webapp/index.html');
```

### 2. Database yaratish

**FastPanel da:**
1. "Базы данных" (Databases) ga o'ting
2. Yangi database yarating:
   - Nom: `f0323_starsbot`
   - User: `f0323_starsbot`
   - Parol: kuchli parol yarating

3. phpMyAdmin ga kiring
4. `database/schema.sql` faylni import qiling:
   - "Импорт" (Import)
   - "Выберите файл" (Choose file)
   - `schema.sql` ni tanlang
   - "Вперед" (Go)

### 3. Ruxsatlarni sozlash

SSH orqali yoki FastPanel File Manager da:

```bash
chmod 755 bot/ webapp/ admin/
chmod 644 bot/*.php webapp/*.html admin/*.php
chmod 750 bot/logs/
chmod 640 bot/config.php
```

Yoki FastPanel da:
- Fayl ustiga o'ng tugma
- "Права" (Permissions)
- Ruxsatlarni sozlang

### 4. Webhook o'rnatish

Brauzerda ochish:
```
https://f0323.5fh.ru/setup_webhook.php
```

### 5. Admin yaratish

phpMyAdmin da:

```sql
INSERT INTO admins (user_id, username, full_name) 
VALUES (123456789, '@yourusername', 'Ism Familiya');
```

**User ID olish:**
- Telegram da @userinfobot ga o'z profilingizni forward qiling

---

## ✅ Test Qilish

1. **Bot test:**
   ```
   Telegram → /start
   ```

2. **Web App test:**
   ```
   https://f0323.5fh.ru/webapp/index.html
   ```

3. **Admin panel:**
   ```
   https://f0323.5fh.ru/admin/
   Parol: admin123 (keyin o'zgartiring!)
   ```

---

## 🚨 Agar Xatolik Bo'lsa

### PHP xatosi
1. FastPanel → "PHP" → Version: 8.0 yoki yuqori
2. Extensions: PDO, PDO_MySQL, cURL

### Database xatosi
1. `config.php` da username/parol to'g'ri ekanligini tekshiring
2. Database user ga barcha ruxsatlar berilganligini tekshiring

### Webhook xatosi
1. HTTPS ishlayotganligini tekshiring
2. SSL sertifikat bor ekanligini tekshiring

### Loglarni ko'rish
```
bot/logs/error_2024-XX-XX.log
```

---

## 📞 Qo'shimcha Yordam

Agar qiyinchiliklar bo'lsa, quyidagilarni tekshiring:

1. ✅ Barcha fayllar yuklangan
2. ✅ config.php to'g'ri sozlangan
3. ✅ Database yaratilgan va import qilingan
4. ✅ Ruxsatlar to'g'ri (chmod)
5. ✅ PHP 8.0+ o'rnatilgan
6. ✅ SSL sertifikat bor

---

**Muvaffaqiyat!** 🎉
