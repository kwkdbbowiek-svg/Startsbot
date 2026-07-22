# 🌟 Telegram Stars Selling System

Professional Telegram bot va Web App tizimi Stars sotish uchun - to'liq ishlaydigan, zamonaviy dizayn bilan va admin panel.

[![GitHub Stars](https://img.shields.io/github/stars/kwkdbbowiek-svg/Startsbot?style=social)](https://github.com/kwkdbbowiek-svg/Startsbot)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://www.php.net/)

> ⚠️ **MUHIM:** `.env` faylni yarating va tokenlarni u yerga kiriting! `.env.example` dan nusxa oling.

## ✨ Xususiyatlar

### 📱 Telegram Bot
- `/start` komandasi - Foydalanuvchini kutib olish
- HTML formatda xabarlar
- Inline Web App tugmasi
- Majburiy kanal obunasi tekshiruvi
- Admin komandalar

### 🎨 Web App (Telegram Mini App)
- **Dark Mode** + **Neon Effektlar** (Yashil, Qizil, Sariq)
- Animatsiyali yulduzchalar orqa fonda
- Tepa qismidan pastga tushuvchi nur effekti
- Responsive dizayn (mobile-first)

### 💎 Asosiy Funksiyalar
1. **Qo'lda belgilash**: 50-10,000,000 Stars, real vaqtda narx hisoblash
2. **Tayyor paketlar**: Admin tomonidan sozlanadigan (50, 100, 500, 1000, 5000 Stars)
3. **To'lov tizimlari**:
   - Click (avtomatik)
   - Payme (avtomatik)
   - Manual (chek yuklash orqali)
4. **Obuna tekshirish**: Modal oyna bilan majburiy kanal obunasi
5. **Profile**: Xaridlar tarixi, hisobni ko'rish

### 🔌 API Integratsiyasi
- **Fragment API** (JWT token)
- **SMM Upper API** (API key)
- Avtomatik fallback (agar Fragment ishlamasa, SMM Upper ga o'tadi)

### 👨‍💼 Admin Panel
- Dashboard (statistika)
- Sozlamalar (Stars kursi, API, kanal, karta)
- Broadcast (rasm + matn)
- To'lovlarni boshqarish (tasdiqlash/rad etish)
- Xaridlar tarixi

## 🚀 Tezkor Boshlash

### 1️⃣ Repository ni Clone Qiling

```bash
git clone https://github.com/kwkdbbowiek-svg/Startsbot.git
cd Startsbot
```

### 2️⃣ .env Faylni Yarating

```bash
# .env.example dan nusxa oling
cp .env.example .env

# .env ni tahrirlang va haqiqiy ma'lumotlarni kiriting
nano .env
```

**`.env` faylga kiriting:**
- `BOT_TOKEN` - @BotFather dan
- `DB_*` - Database ma'lumotlari
- `WEBAPP_URL` - Sizning domeningiz

### 3️⃣ Database O'rnatish

```bash
mysql -u root -p < database/schema.sql
```

### 4️⃣ Webhook O'rnatish

Browser da: `https://yourdomain.com/setup_webhook.php`

---

**🔐 XAVFSIZLIK:** `.env` faylda tokenlar saqlanadi va GitHub ga yuklanmaydi!

**To'liq yo'riqnoma:** [INSTALLATION.md](INSTALLATION.md)  
**Tezkor qo'llanma:** [QUICKSTART.md](QUICKSTART.md)

## 📂 Loyiha Strukturasi

```
telegram-stars-bot/
├── bot/                          # Bot backend
│   ├── config.php               # Konfiguratsiya
│   ├── bot.php                  # Asosiy bot mantiq
│   ├── webhook.php              # Webhook handler
│   └── logs/                    # Log fayllar
├── webapp/                       # Web App (Mini App)
│   ├── index.html               # Asosiy HTML
│   ├── css/
│   │   └── style.css           # Dark mode + Neon styles
│   ├── js/
│   │   ├── app.js              # Asosiy mantiq
│   │   └── animations.js       # Animatsiyalar
│   └── api/                     # Backend API
│       ├── check_subscription.php
│       ├── get_user.php
│       ├── get_balance.php
│       ├── get_settings.php
│       ├── get_packages.php
│       ├── buy_stars.php
│       ├── manual_payment.php
│       └── get_purchases.php
├── admin/                        # Admin panel
│   ├── index.php                # Dashboard
│   ├── settings.php             # Sozlamalar
│   ├── broadcast.php            # Xabar yuborish
│   └── payments.php             # To'lovlar
├── database/
│   └── schema.sql               # Database struktura
├── setup_webhook.php            # Webhook sozlash
├── .htaccess                    # Apache config
├── .gitignore                   
├── README.md                    
├── INSTALLATION.md              # To'liq yo'riqnoma
└── QUICKSTART.md                # Tezkor qo'llanma
```

## 🎯 Asosiy Komponentlar

### Database Jadvallar
- `users` - Foydalanuvchilar
- `purchases` - Xaridlar tarixi
- `payments` - To'lovlar
- `stars_packages` - Stars paketlar
- `settings` - Sozlamalar
- `admins` - Admin foydalanuvchilar
- `broadcasts` - Broadcast xabarlar

### API Endpoints
| Endpoint | Vazifa |
|----------|--------|
| `check_subscription.php` | Kanal obunasini tekshirish |
| `get_user.php` | Foydalanuvchi ma'lumotlari |
| `get_balance.php` | Balansni olish |
| `get_settings.php` | Sozlamalarni yuklash |
| `get_packages.php` | Paketlar ro'yxati |
| `buy_stars.php` | Stars xarid qilish |
| `manual_payment.php` | Manual to'lov (chek yuklash) |
| `get_purchases.php` | Xaridlar tarixi |

## 🎨 Dizayn Xususiyatlari

### Neon Effektlar
- Yashil (`#00ff00`) - Asosiy
- Qizil (`#ff0040`) - Bekor qilish
- Sariq (`#ffff00`) - Diqqat
- Ko'k (`#00d4ff`) - Ma'lumot

### Animatsiyalar
- ⭐ Uchib yuruvchi yulduzchalar (50 ta)
- 🌟 Tepa qismidan nur (fade-out)
- 💫 Neon pulse (tugmalar)
- 🎉 Confetti (muvaffaqiyatli xarid)
- 🔄 Smooth transitions

## ⚙️ Sozlamalar

### Stars Narxi
Default: `140 UZS = 1 Star`  
Admin panel orqali o'zgartiriladi

### API Sozlash

**Fragment API:**
```
URL: https://api.fragment-api.com/v1/order/stars/
Auth: JWT Bearer Token
```

**SMM Upper API:**
```
URL: https://smmupper.uz/api/v2
Auth: API Key
Olish: @smmuppercombot
```

### Majburiy Kanal
```
Channel Username: @yourchannel
Channel ID: -1001234567890 (@userinfobot orqali)
```

## 🔐 Xavfsizlik

### Production da:
1. ✅ Admin parolini o'zgartiring
2. ✅ Error reporting o'chiring
3. ✅ HTTPS ishlatilishini tekshiring
4. ✅ Database parolini kuchli qiling
5. ✅ Fayl ruxsatlarini sozlang

### Tavsiya etilgan ruxsatlar:
```bash
chmod 755 bot/ webapp/ admin/
chmod 644 bot/*.php webapp/*.html admin/*.php
chmod 750 bot/logs/
chmod 640 bot/config.php
```

## 📊 Admin Panel

**URL:** `https://yourdomain.com/admin/`  
**Default Parol:** `admin123` (o'zgartiring!)

### Imkoniyatlar:
- 📈 Statistika (foydalanuvchilar, sotuvlar, daromad)
- ⚙️ Sozlamalar (narx, API, kanal, karta)
- 📢 Broadcast (xabar + rasm)
- 💳 To'lovlarni boshqarish
- 📜 Xaridlar tarixi

## 🧪 Test

```bash
# 1. Bot test
Telegram → /start

# 2. Obuna test
Kanalga obuna bo'lmasdan Web App ochish

# 3. To'lov test
Admin panel → To'lovlar → Tasdiqlash

# 4. Xarid test
Web App → Qo'lda belgilash → Username → Tasdiqlash
```

## 📝 Texnologiyalar

- **Backend:** PHP 8.x
- **Frontend:** Vanilla HTML5/CSS3/JavaScript
- **Database:** MySQL 5.7+
- **API:** Telegram Bot API, Fragment API, SMM Upper API
- **Design:** Dark Mode + Neon Effects
- **Animation:** CSS3 + JavaScript

## 🤝 Hissa Qo'shish

Pull request larni qabul qilamiz!

1. Fork qiling
2. Feature branch yarating (`git checkout -b feature/AmazingFeature`)
3. Commit qiling (`git commit -m 'Add AmazingFeature'`)
4. Push qiling (`git push origin feature/AmazingFeature`)
5. Pull Request oching

## 📄 Litsenziya

MIT License - batafsil ma'lumot uchun [LICENSE](LICENSE) faylini ko'ring

## 💬 Qo'llab-quvvatlash

- 📧 Email: support@yourdomain.com
- 💬 Telegram: @yoursupport
- 🐛 Issues: [GitHub Issues](https://github.com/yourusername/telegram-stars-bot/issues)

## 🌟 Minnatdorchilik

- Telegram Bot API
- Fragment API
- SMM Upper
- Open Source community

---

**Made with ❤️ for Telegram Stars sellers**

⭐ Agar loyiha foydali bo'lsa, GitHub da yulduz qoldiring!
