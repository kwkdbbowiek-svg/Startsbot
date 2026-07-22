# Loyiha Strukturasi - To'liq Ko'rinish

```
telegram-stars-bot/
│
├── 📁 bot/                                    # Telegram Bot Backend
│   ├── config.php                            # ⚙️ Asosiy konfiguratsiya (BOT_TOKEN, Database)
│   ├── config.example.php                    # 📝 Konfiguratsiya namunasi
│   ├── bot.php                               # 🤖 Bot asosiy mantiq (komandalar, callback)
│   ├── webhook.php                           # 🔗 Webhook handler
│   └── logs/                                 # 📊 Log fayllar (avtomatik yaratiladi)
│       ├── error_YYYY-MM-DD.log             # ❌ Xato loglari
│       └── updates.log                       # 📝 Update loglari
│
├── 📁 webapp/                                 # Web App (Telegram Mini App)
│   ├── index.html                            # 🏠 Asosiy HTML (Dark + Neon)
│   ├── 📁 css/
│   │   └── style.css                        # 🎨 Barcha uslublar (Dark mode, Neon effects)
│   ├── 📁 js/
│   │   ├── app.js                           # ⚡ Asosiy JavaScript mantiq
│   │   └── animations.js                    # ✨ Animatsiyalar (yulduzlar, confetti)
│   ├── 📁 api/                              # 🔌 Backend API endpoints
│   │   ├── check_subscription.php           # ✅ Obuna tekshirish
│   │   ├── get_user.php                     # 👤 Foydalanuvchi ma'lumotlari
│   │   ├── get_balance.php                  # 💰 Balans olish
│   │   ├── get_settings.php                 # ⚙️ Sozlamalarni yuklash
│   │   ├── get_packages.php                 # 📦 Paketlar ro'yxati
│   │   ├── buy_stars.php                    # ⭐ Stars xarid qilish
│   │   ├── manual_payment.php               # 💳 Manual to'lov (chek yuklash)
│   │   └── get_purchases.php                # 📜 Xaridlar tarixi
│   └── 📁 uploads/                          # 📤 Yuklangan fayllar (avtomatik)
│       └── receipts/                         # 🧾 To'lov cheklari
│
├── 📁 admin/                                  # Admin Panel
│   ├── index.php                             # 📊 Dashboard (statistika, oxirgi xaridlar)
│   ├── settings.php                          # ⚙️ Sozlamalar (narx, API, kanal, karta)
│   ├── broadcast.php                         # 📢 Broadcast (xabar + rasm yuborish)
│   └── payments.php                          # 💳 To'lovlarni boshqarish
│
├── 📁 database/
│   └── schema.sql                            # 🗄️ Database struktura (MySQL)
│
├── 📄 setup_webhook.php                       # 🔧 Webhook o'rnatish (bir marta ishlatish)
├── 📄 .htaccess                              # ⚙️ Apache konfiguratsiya
├── 📄 .gitignore                             # 🚫 Git ignore qoidalari
│
└── 📚 Hujjatlar
    ├── README.md                             # 📖 Asosiy yo'riqnoma
    ├── INSTALLATION.md                       # 📝 To'liq o'rnatish yo'riqnomasi
    ├── QUICKSTART.md                         # ⚡ Tezkor boshlash (5 daqiqa)
    ├── FEATURES.md                           # ✨ Batafsil xususiyatlar
    └── PROJECT_STRUCTURE.md                  # 📂 Bu fayl
```

---

## Fayl Hajmlari va Funksiyalari

### Bot Backend (PHP)

| Fayl | Hajm | Vazifa |
|------|------|--------|
| `bot/config.php` | ~3 KB | Asosiy sozlamalar, DB connection, API URLs |
| `bot/bot.php` | ~5 KB | Komandalar (/start, /help, /balance, /admin) |
| `bot/webhook.php` | ~100 B | Webhook qabul qilish |

### Web App Frontend

| Fayl | Hajm | Vazifa |
|------|------|--------|
| `webapp/index.html` | ~8 KB | HTML struktura, modal, forms |
| `webapp/css/style.css` | ~7 KB | Dark mode, Neon, responsive |
| `webapp/js/app.js` | ~12 KB | Asosiy mantiq, API calls, user flow |
| `webapp/js/animations.js` | ~4 KB | Yulduzlar, confetti, toast, loading |

### Web App API

| Fayl | Hajm | Vazifa |
|------|------|--------|
| `webapp/api/check_subscription.php` | ~1 KB | Kanal obunasini tekshirish |
| `webapp/api/get_user.php` | ~1 KB | User ma'lumotlari |
| `webapp/api/get_balance.php` | ~0.5 KB | Balans olish |
| `webapp/api/get_settings.php` | ~0.7 KB | Sozlamalar |
| `webapp/api/get_packages.php` | ~0.6 KB | Paketlar ro'yxati |
| `webapp/api/buy_stars.php` | ~6 KB | Stars xarid, API integration |
| `webapp/api/manual_payment.php` | ~3 KB | Manual to'lov, chek yuklash |
| `webapp/api/get_purchases.php` | ~0.8 KB | Xaridlar tarixi |

### Admin Panel

| Fayl | Hajm | Vazifa |
|------|------|--------|
| `admin/index.php` | ~8 KB | Dashboard, statistika, login |
| `admin/settings.php` | ~6 KB | Sozlamalarni boshqarish |
| `admin/broadcast.php` | ~7 KB | Xabar yuborish, tarixi |
| `admin/payments.php` | ~6 KB | To'lovlarni tasdiqlash/rad etish |

### Database

| Fayl | Hajm | Vazifa |
|------|------|--------|
| `database/schema.sql` | ~5 KB | 7 ta jadval, indexlar, default ma'lumotlar |

### Hujjatlar

| Fayl | Hajm | Vazifa |
|------|------|--------|
| `README.md` | ~6 KB | Loyiha tavsifi, tezkor yo'riqnoma |
| `INSTALLATION.md` | ~5 KB | To'liq o'rnatish qo'llanmasi |
| `QUICKSTART.md` | ~2 KB | 5 daqiqada ishga tushirish |
| `FEATURES.md` | ~10 KB | Batafsil funksional tavsif |

---

## Komponent Bog'liqligi

```
┌─────────────────────────────────────────────────┐
│           Telegram Server                       │
│  (Webhook → bot/webhook.php)                    │
└────────────────┬────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────┐
│           Bot Backend                           │
│  bot/bot.php (Komandalar, Callback)             │
│  bot/config.php (DB, API)                       │
└────────────────┬────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────┐
│           Database (MySQL)                      │
│  - users                                        │
│  - purchases                                    │
│  - payments                                     │
│  - settings                                     │
│  - admins                                       │
│  - broadcasts                                   │
│  - stars_packages                               │
└────┬────────────────────────────────────────────┘
     │
     ├──────────────────────┬──────────────────────┐
     ▼                      ▼                      ▼
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  Web App    │    │ Admin Panel │    │ External API│
│             │    │             │    │             │
│ index.html  │    │ index.php   │    │ Fragment    │
│ app.js      │    │ settings    │    │ SMM Upper   │
│ style.css   │    │ broadcast   │    │             │
│             │    │ payments    │    │             │
└─────────────┘    └─────────────┘    └─────────────┘
```

---

## API Flow Diagrammasi

### Stars Xarid Qilish Flow

```
┌─────────────┐
│ Foydalanuvchi│
│ Web App da  │
└──────┬──────┘
       │ 1. Stars paket tanlaydi
       ▼
┌─────────────────────────┐
│ webapp/js/app.js        │
│ buyPackage() funksiya   │
└──────┬──────────────────┘
       │ 2. POST so'rov
       ▼
┌─────────────────────────┐
│ webapp/api/buy_stars.php│
│ - Balansni tekshirish   │
│ - Transaction boshlash  │
└──────┬──────────────────┘
       │ 3. API call
       ├────────────────────────┐
       ▼                        ▼
┌──────────────┐      ┌──────────────┐
│ Fragment API │      │ SMM Upper API│
│ (Primary)    │      │ (Fallback)   │
└──────┬───────┘      └──────┬───────┘
       │                     │
       │ 4. Javob            │
       ▼                     ▼
┌─────────────────────────┐
│ buy_stars.php           │
│ - Transaction commit    │
│ - Purchase record save  │
└──────┬──────────────────┘
       │ 5. Response
       ▼
┌─────────────────────────┐
│ app.js                  │
│ - Confetti ko'rsatish   │
│ - Balansni yangilash    │
│ - Toast xabar           │
└─────────────────────────┘
```

### Manual To'lov Flow

```
┌─────────────┐
│ Foydalanuvchi│
└──────┬──────┘
       │ 1. Manual to'lov tanlaydi
       ▼
┌─────────────────────────┐
│ Miqdor + Chek yuklash   │
└──────┬──────────────────┘
       │ 2. POST (FormData)
       ▼
┌─────────────────────────┐
│ manual_payment.php      │
│ - Fayl saqlash          │
│ - Payment record        │
└──────┬──────────────────┘
       │ 3. Xabar
       ▼
┌─────────────────────────┐
│ Admin (Telegram)        │
│ "Yangi to'lov so'rovi"  │
└──────┬──────────────────┘
       │ 4. Admin panel
       ▼
┌─────────────────────────┐
│ admin/payments.php      │
│ - Chek ko'rish          │
│ - Tasdiqlash/Rad etish  │
└──────┬──────────────────┘
       │ 5. Tasdiqlash
       ▼
┌─────────────────────────┐
│ Database                │
│ - Balansga qo'shish     │
│ - Status = approved     │
└──────┬──────────────────┘
       │ 6. Xabar
       ▼
┌─────────────────────────┐
│ Foydalanuvchi           │
│ "To'lovingiz tasdiqlandi"│
└─────────────────────────┘
```

---

## Xavfsizlik Qatlamlari

```
1. Transport Layer
   └── HTTPS (SSL/TLS)

2. Application Layer
   ├── Bot Token (Telegram)
   ├── Session Auth (Admin Panel)
   └── User ID Validation (Web App API)

3. Data Layer
   ├── PDO Prepared Statements
   ├── HTML Entities Escape
   ├── File Upload Validation
   └── Input Sanitization

4. Infrastructure Layer
   ├── .htaccess (Directory Protection)
   ├── File Permissions (755/644)
   └── Database User Privileges
```

---

## Performance Optimizatsiya

### Database
- ✅ Primary Keys (id)
- ✅ Foreign Keys (user_id)
- ✅ Indexes (user_id, status, created_at)
- ✅ ON DUPLICATE KEY UPDATE

### Frontend
- ✅ CSS Neon Effects (GPU-accelerated)
- ✅ JavaScript Animations (requestAnimationFrame)
- ✅ Lazy Loading (images)
- ✅ Async API Calls (fetch)

### Backend
- ✅ Database Connection Pooling
- ✅ Prepared Statements (reusable)
- ✅ Error Logging (faylga, ekranga emas)
- ✅ Gzip Compression (.htaccess)

### Caching
- ✅ Browser Cache (1 yil statik fayllar)
- ✅ Settings Cache (database query reduction)
- ⏳ Redis/Memcached (kelajakda)

---

## Testing Checklist

### ✅ Bot Testing
- [ ] /start komandasi
- [ ] /help komandasi
- [ ] /balance komandasi
- [ ] /admin komandasi (admin uchun)
- [ ] Inline tugmalar ishlaydi
- [ ] HTML formatting to'g'ri

### ✅ Web App Testing
- [ ] Sahifa ochiladi (HTTPS)
- [ ] Telegram WebApp API ishlaydi
- [ ] Animatsiyalar ko'rinadi (yulduzlar, glow)
- [ ] Obuna modali ko'rinadi
- [ ] Navigation ishlaydi
- [ ] Form validation ishlaydi

### ✅ Xarid Testing
- [ ] Qo'lda belgilash ishlaydi
- [ ] Real vaqtda narx hisoblanadi
- [ ] Tayyor paketlar ko'rinadi
- [ ] Balans yetarli tekshiriladi
- [ ] Stars yuboriladi (API)
- [ ] Xaridlar tarixida ko'rinadi

### ✅ To'lov Testing
- [ ] Manual to'lov form ochiladi
- [ ] Karta ma'lumotlari ko'rinadi
- [ ] Chek yuklash ishlaydi
- [ ] Admin ga xabar keladi
- [ ] Admin tasdiqlashi ishlaydi
- [ ] Balans qo'shiladi

### ✅ Admin Panel Testing
- [ ] Login ishlaydi
- [ ] Dashboard ko'rinadi
- [ ] Statistika to'g'ri
- [ ] Sozlamalar saqlanadi
- [ ] Broadcast yuboriladi
- [ ] To'lovlar boshqariladi

---

## Deployment Checklist

### Pre-deployment
- [ ] config.php sozlangan
- [ ] Database import qilingan
- [ ] Admin yaratilgan
- [ ] Webhook o'rnatilgan
- [ ] SSL sertifikat o'rnatilgan

### Production Settings
- [ ] Error reporting o'chirilgan
- [ ] Admin parol o'zgartirilgan
- [ ] Database parol kuchli
- [ ] Fayl ruxsatlari to'g'ri
- [ ] .htaccess to'g'ri

### Post-deployment
- [ ] Bot test qilingan
- [ ] Web App test qilingan
- [ ] Admin panel test qilingan
- [ ] API test qilingan
- [ ] Backup sozlangan

---

**Loyiha versiyasi:** 1.0.0  
**Oxirgi yangilanish:** 2024  
**Umumiy fayllar soni:** 30+  
**Umumiy kod qatorlari:** 3000+  
**Texnologiyalar:** PHP 8.x, MySQL, Vanilla JS, HTML5, CSS3
