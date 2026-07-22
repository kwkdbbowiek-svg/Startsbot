# Telegram Stars Bot - O'rnatish Yo'riqnomasi

## 1. Talablar

- PHP 8.0 yoki undan yuqori
- MySQL 5.7 yoki undan yuqori
- Web server (Apache/Nginx)
- SSL sertifikat (HTTPS - Telegram webhook uchun majburiy)
- cURL faollashtirilgan
- PDO MySQL extension

## 2. O'rnatish Bosqichlari

### 2.1. Fayllarni Yuklash

Barcha fayllarni web serveringizga yuklang:

```
/var/www/html/telegram-stars-bot/
```

### 2.2. Database Yaratish

1. phpMyAdmin yoki MySQL CLI orqali kirish
2. `database/schema.sql` faylini import qiling:

```bash
mysql -u username -p database_name < database/schema.sql
```

Yoki phpMyAdmin da:
- Database yarating: `telegram_stars_bot`
- Import → `schema.sql` ni tanlang

### 2.3. Konfiguratsiya

`bot/config.php` faylini tahrirlang:

```php
// Telegram Bot Token
define('BOT_TOKEN', 'YOUR_BOT_TOKEN_HERE');

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'telegram_stars_bot');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');

// Web App URL
define('WEBAPP_URL', 'https://yourdomain.com/webapp/index.html');
```

### 2.4. Telegram Bot Yaratish

1. Telegram da @BotFather ga yuboring `/newbot`
2. Bot nomini kiriting
3. Bot username kiriting
4. Token ni nusxalang va `config.php` ga kiriting

### 2.5. Webhook O'rnatish

1. Brauzerda ochish:
```
https://yourdomain.com/setup_webhook.php
```

2. "Webhook muvaffaqiyatli o'rnatildi" xabarini ko'rishingiz kerak

3. Test qilish:
- Telegram botingizga `/start` yuboring
- Javob kelishi kerak

### 2.6. Admin Yaratish

phpMyAdmin da SQL buyrug'ini bajaring:

```sql
INSERT INTO admins (user_id, username, full_name) 
VALUES (YOUR_TELEGRAM_USER_ID, '@yourusername', 'Ism Familiya');
```

**User ID ni olish:**
- @userinfobot ga o'z profilingizni forward qiling
- ID ni nusxalang

### 2.7. Web App Sozlash

1. `bot/config.php` da `WEBAPP_URL` ni to'g'ri manzilga o'zgartiring
2. Telegram botingizda Web App tugmasini sozlash:

@BotFather da:
```
/setmenubutton
→ Botingizni tanlang
→ "Xizmatlar" yoki o'z nomingiz
→ https://yourdomain.com/webapp/index.html
```

## 3. Admin Panel

### 3.1. Kirish

URL: `https://yourdomain.com/admin/`

Default parol: `admin123`

**⚠️ DIQQAT:** Parolni o'zgartiring!

`admin/index.php` da:
```php
if ($password === 'admin123') {
```
Bu qatorni topib, yangi parol kiriting.

### 3.2. Sozlamalar

Admin panel → Sozlamalar:

#### Stars Narxi
- 1 Star = 140 UZS (default)
- O'z narxingizni kiriting

#### API Sozlash

**Fragment API:**
1. Fragment platformasidan JWT token oling
2. Token ni "Fragment JWT Token" maydoniga kiriting

**SMM Upper API:**
1. Telegram da @smmuppercombot ga kiring
2. API kalitini oling (masalan: `9e7cf27c6922dc2`)
3. API kalitini "SMM Upper API Key" maydoniga kiriting

#### Majburiy Kanal
1. Kanalingizni yarating (yoki mavjudini ishlating)
2. Kanal username: `@yourchannel`
3. Kanal ID: @userinfobot ga kanal postini forward qiling, ID ni nusxalang
4. Botni kanalga admin qiling

#### Manual To'lov
1. Karta raqamingizni kiriting: `8600 1234 5678 9012`
2. Ism-familiyangizni kiriting: `ABDULLAYEV SARDOR`

## 4. Test Qilish

### 4.1. Bot Test
1. Telegram da botga `/start` yuboring
2. "Xizmatlar" tugmasini bosing
3. Web App ochilishi kerak

### 4.2. Obuna Test
1. Kanalga obuna bo'lmang
2. Web App ochganda modal ko'rinishi kerak
3. Kanalga obuna bo'ling
4. "Tekshirish" tugmasini bosing
5. Modal yopilishi kerak

### 4.3. To'lov Test (Manual)
1. Web App da "+" tugmani bosing
2. "Manual" ni tanlang
3. Miqdor kiriting: 10000 UZS
4. Test rasm yuklang
5. "Yuborish" ni bosing
6. Admin panelda "To'lovlar" bo'limida ko'rinishi kerak
7. Admin to'lovni tasdiqlash mumkin

### 4.4. Stars Xarid Test
1. Foydalanuvchi balansini to'ldiring (Admin panel orqali yoki manual to'lov)
2. Web App da "Qo'lda belgilash" yoki tayyor paketni tanlang
3. Username kiriting (@ belgisiz)
4. "Tasdiqlash" ni bosing
5. Xarid tarixi ko'rinishi kerak

## 5. Troubleshooting

### Webhook ishlamayapti?
```bash
# Webhook statusini tekshirish
curl https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getWebhookInfo
```

### Database xatosi?
- Database foydalanuvchi ruxsatlarini tekshiring
- `bot/logs/error_*.log` faylini ko'ring

### Web App ochilmayapti?
- HTTPS ishlayotganini tekshiring
- `webapp/api/` fayllar uchun yo'llarni tekshiring
- Browser Console xatolarini ko'ring (F12)

### API ishlamayapti?
- Fragment JWT token amal qilish muddatini tekshiring
- SMM Upper API balansini tekshiring
- `bot/logs/error_*.log` da xatolarni ko'ring

## 6. Xavfsizlik

### Production da Majburiy:

1. **Admin parolini o'zgartiring**
2. **Database parolini kuchli qiling**
3. **Error reporting o'chiring:**
```php
// config.php da
error_reporting(0);
ini_set('display_errors', 0);
```
4. **HTTPS ishlatilishini tekshiring**
5. **Fayl ruxsatlarini sozlang:**
```bash
chmod 755 bot/
chmod 644 bot/*.php
chmod 750 bot/logs/
chmod 640 bot/config.php
```

## 7. Backup

### Database Backup
```bash
mysqldump -u username -p telegram_stars_bot > backup_$(date +%Y%m%d).sql
```

### Fayllar Backup
```bash
tar -czf backup_files_$(date +%Y%m%d).tar.gz /var/www/html/telegram-stars-bot/
```

## 8. Qo'shimcha

### Broadcast
- Admin panel → Broadcast
- Xabar yozish (HTML format)
- Rasm yuklash (ixtiyoriy)
- Yuborish

### Paketlar Boshqarish
Database da `stars_packages` jadvalini tahrirlash:
```sql
INSERT INTO stars_packages (stars_amount, is_active, sort_order) 
VALUES (2000, 1, 6);
```

### Admin Qo'shish
```sql
INSERT INTO admins (user_id, username, full_name) 
VALUES (USER_ID, '@username', 'Ism Familiya');
```

## 9. Yordam

Savollar uchun:
- GitHub Issues
- Telegram: @yoursupport

---

**Muvaffaqiyat!** 🎉
