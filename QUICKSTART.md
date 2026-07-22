# Telegram Stars Bot - Tezkor Boshlash

## 5 Daqiqada Ishga Tushirish

### 1. Bot Yaratish (2 daqiqa)
```
Telegram → @BotFather
/newbot
Bot nomi: "My Stars Shop"
Username: mystarsshop_bot
Token ni saqlang!
```

### 2. Database O'rnatish (1 daqiqa)
```bash
mysql -u root -p
CREATE DATABASE telegram_stars_bot;
exit;

mysql -u root -p telegram_stars_bot < database/schema.sql
```

### 3. Konfiguratsiya (1 daqiqa)
```bash
cd bot/
cp config.example.php config.php
nano config.php
```

O'zgartiring:
- `BOT_TOKEN` → @BotFather dan olingan token
- `DB_USER` → database username
- `DB_PASS` → database parol
- `WEBAPP_URL` → sizning domeningiz

### 4. Webhook (30 soniya)
```
Browser da ochish:
https://yourdomain.com/setup_webhook.php
```

### 5. Admin Yaratish (30 soniya)
```sql
-- phpMyAdmin da SQL buyrug'i
INSERT INTO admins (user_id, username, full_name) 
VALUES (123456789, '@yourusername', 'Ism Familiya');

-- User ID: @userinfobot ga o'z profilingizni forward qiling
```

## Test

1. Telegram botga `/start` yuboring
2. ✅ Javob kelishi kerak
3. 🔥 "Xizmatlar" tugmasi ko'rinadi
4. Admin panel: `https://yourdomain.com/admin/` (Parol: `admin123`)

## Kerakli Sozlamalar

### Admin Panel → Sozlamalar

1. **Stars Narxi**: 140 (1 Star = 140 UZS)
2. **API Usuli**: Fragment yoki SMM Upper
3. **Kanal ID**: @userinfobot orqali oling
4. **Karta**: Manual to'lov uchun

## Muhim Fayllar

```
bot/config.php          - Asosiy sozlamalar
database/schema.sql     - Database struktura
webapp/index.html       - Web App
admin/index.php         - Admin panel
```

## Yordam

```
❌ Webhook ishlamayapti?
→ HTTPS borligini tekshiring
→ bot/logs/error_*.log ni ko'ring

❌ Database xatosi?
→ config.php da username/parol to'g'ri ekanligini tekshiring

❌ Web App ochilmayapti?
→ WEBAPP_URL ni tekshiring
→ Browser Console (F12) xatolarini ko'ring
```

## Keyingi Qadamlar

1. ✅ SMS Upper API sozlash
2. ✅ Kanal majburiy obunasini sozlash
3. ✅ Admin parolini o'zgartirish
4. ✅ Test xarid qilish
5. ✅ Broadcast yuborish

---

**To'liq yo'riqnoma:** [INSTALLATION.md](INSTALLATION.md)
