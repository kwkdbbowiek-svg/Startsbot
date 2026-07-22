# Telegram Stars Bot - Batafsil Xususiyatlar

## 🎯 Asosiy Funksiyalar

### 1. Telegram Bot

#### Komandalar
| Komanda | Tavsif | Dostup |
|---------|--------|--------|
| `/start` | Botni boshlash, xush kelibsiz xabari | Barcha |
| `/help` | Yordam xabari | Barcha |
| `/balance` | Hisobni ko'rish | Barcha |
| `/admin` | Admin panel | Faqat adminlar |

#### Xabar Formatlari
- **HTML** qo'llab-quvvatlanadi
- Bold: `<b>matn</b>`
- Italic: `<i>matn</i>`
- Code: `<code>matn</code>`
- Quote: `<q>matn</q>`

#### Inline Tugmalar
- 🔥 Xizmatlar (Web App)
- Admin tugmalari (statistika, broadcast, sozlamalar)

---

### 2. Web App (Mini App)

#### Dizayn Sistema

##### Ranglar
```css
Neon Green:  #00ff00  (asosiy, tasdiqlash)
Neon Red:    #ff0040  (bekor qilish, xato)
Neon Yellow: #ffff00  (diqqat, balans)
Neon Blue:   #00d4ff  (ma'lumot)
Dark BG:     #0a0a0a  (orqa fon)
Card BG:     #1a1a1a  (kartalar)
Input BG:    #2a2a2a  (input maydonlar)
```

##### Animatsiyalar
1. **Yulduzchalar (50 ta)**
   - Uchib yuradigan
   - Aylanadigan
   - Tasodifiy yo'nalish
   - 15-25 soniya davomiyligi

2. **Top Glow**
   - Sariq nur
   - Tepadan pastga
   - 3 soniyada yo'qoladi

3. **Neon Pulse**
   - Tugmalar
   - Matnlar
   - 2 soniya siklda

4. **Confetti**
   - Muvaffaqiyatli xarid
   - 50 ta rang-barang zarracha
   - Gravitatsiya effekti

#### Bo'limlar

##### 🛒 Xaridlar
1. **Qo'lda belgilash**
   - Min: 50 Stars
   - Max: 10,000,000 Stars
   - Real vaqtda narx hisoblash
   - Username kiritish (@ belgisiz)
   - Tasdiqlash/Bekor qilish tugmalari

2. **Tayyor Paketlar**
   - 50 Stars
   - 100 Stars
   - 500 Stars
   - 1000 Stars
   - 5000 Stars
   - Admin tomonidan qo'shiladigan boshqa paketlar

##### 🏠 Bosh Menyu
- Bot haqida ma'lumot
- Joriy Stars narxlari
- Admin bilan bog'lanish

##### 👤 Profil
- Profil rasmi
- Ism va username
- Admin bilan muloqot
- Hisobim
- Xaridlar tarixi

#### Obuna Tekshirish
- Modal oyna
- Kanal nomi (link)
- "Tekshirish" tugmasi
- Blurred orqa fon

#### To'lov Tizimlari

##### 1. Click (Avtomatik)
- Click API integratsiyasi
- Avtomatik balans qo'shish
- To'lov tasdiqnomasi

##### 2. Payme (Avtomatik)
- Payme API integratsiyasi
- Avtomatik balans qo'shish
- To'lov tasdiqnomasi

##### 3. Manual
- Karta raqami ko'rsatiladi
- Karta egasi ko'rsatiladi
- Chek faylini yuklash
- Admin tasdiqlashi kutiladi

---

### 3. API Integratsiya

#### Fragment API

**Endpoint:** `https://api.fragment-api.com/v1/order/stars/`

**Autentifikatsiya:**
```
Authorization: Bearer {JWT_TOKEN}
```

**So'rov:**
```json
{
  "username": "username",
  "amount": 100
}
```

**Javob:**
```json
{
  "success": true,
  "order_id": "12345",
  "status": "completed"
}
```

#### SMM Upper API

**Endpoint:** `https://smmupper.uz/api/v2`

**So'rov:**
```
GET ?action=buyStars&username=username&amount=100&api_key=KEY
```

**Javob:**
```json
{
  "status": "success",
  "order_id": "67890"
}
```

#### Fallback Mexanizm
```
1. Fragment API ga so'rov
2. Agar xatolik bo'lsa
3. SMM Upper API ga o'tish
4. Agar ikkisi ham ishlamasa
5. Xato qaytarish va balansni qaytarish
```

---

### 4. Admin Panel

#### Dashboard

**Statistika Kartalari:**
- 👥 Jami Foydalanuvchilar
- 🛒 Bajarilgan Xaridlar
- ⏳ Kutilayotgan Xaridlar
- 💰 Jami Daromad (UZS)
- 💳 Kutilayotgan To'lovlar

**Oxirgi Xaridlar Jadvali:**
- ID
- Foydalanuvchi (ism + username)
- Stars miqdori
- Narx (UZS)
- Maqsad username
- Status (completed/pending/failed)
- Sana va vaqt

#### Sozlamalar

**1. Stars Narxi**
- 1 Star = ? UZS
- Real vaqtda yangilanadi

**2. API Sozlamalari**
- API usuli tanlash (Fragment / SMM Upper)
- Fragment JWT Token
- SMM Upper API Key
- API uslubini almashtirish

**3. Majburiy Kanal**
- Kanal username (@kanal)
- Kanal ID (-1001234567890)
- Obuna tekshirish on/off

**4. Manual To'lov**
- Karta raqami (16 raqam)
- Karta egasi (Ism-Familiya)

#### Broadcast

**Xabar Yuborish:**
- 📝 Matn (HTML format)
- 🖼 Rasm (ixtiyoriy)
- 👥 Barcha foydalanuvchilarga
- 📊 Statistika (yuborildi/xatolik)

**Rate Limiting:**
- 30 xabar/soniya (Telegram cheklovi)
- Avtomatik kutish

**Broadcast Tarixi:**
- ID
- Matn (qisqartirilgan)
- Jami foydalanuvchilar
- Yuborilgan xabarlar
- Xatoliklar soni
- Sana

#### To'lovlar

**Kutilayotgan To'lovlar:**
- Foydalanuvchi ma'lumotlari
- Miqdor (UZS)
- Chek faylini ko'rish
- Tasdiqlash tugmasi
- Rad etish tugmasi

**To'lovni Tasdiqlash:**
1. Chek ko'rish
2. Tasdiqlash
3. Balans avtomatik qo'shiladi
4. Foydalanuvchiga xabar yuboriladi

**To'lovni Rad Etish:**
1. Rad etish
2. Foydalanuvchiga xabar yuboriladi
3. Sabab tushuntirish

---

### 5. Database Arxitektura

#### users jadval
```sql
- id (BIGINT, PRIMARY KEY)
- username (VARCHAR)
- first_name (VARCHAR)
- last_name (VARCHAR)
- balance (DECIMAL)
- is_subscribed (BOOLEAN)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

#### purchases jadval
```sql
- id (INT, AUTO_INCREMENT)
- user_id (BIGINT, FK)
- stars_amount (INT)
- price (DECIMAL)
- username_target (VARCHAR)
- status (ENUM: pending/completed/failed/refunded)
- api_method (ENUM: fragment/smmupper)
- api_response (TEXT)
- created_at (TIMESTAMP)
```

#### payments jadval
```sql
- id (INT, AUTO_INCREMENT)
- user_id (BIGINT, FK)
- amount (DECIMAL)
- payment_method (ENUM: click/payme/manual)
- transaction_id (VARCHAR)
- receipt_file_id (VARCHAR)
- status (ENUM: pending/approved/rejected)
- created_at (TIMESTAMP)
- approved_at (TIMESTAMP)
```

#### stars_packages jadval
```sql
- id (INT, AUTO_INCREMENT)
- stars_amount (INT)
- is_active (BOOLEAN)
- sort_order (INT)
- created_at (TIMESTAMP)
```

#### settings jadval
```sql
- setting_key (VARCHAR, PRIMARY KEY)
- setting_value (TEXT)
- updated_at (TIMESTAMP)
```

#### admins jadval
```sql
- user_id (BIGINT, PRIMARY KEY)
- username (VARCHAR)
- full_name (VARCHAR)
- created_at (TIMESTAMP)
```

#### broadcasts jadval
```sql
- id (INT, AUTO_INCREMENT)
- admin_id (BIGINT, FK)
- message_text (TEXT)
- photo_file_id (VARCHAR)
- total_users (INT)
- sent_count (INT)
- failed_count (INT)
- status (ENUM: pending/in_progress/completed/failed)
- created_at (TIMESTAMP)
- completed_at (TIMESTAMP)
```

---

### 6. Xavfsizlik

#### Autentifikatsiya
- Admin panel: Session-based
- API: Telegram user ID validation
- Webhook: Telegram signature validation (ixtiyoriy)

#### Ma'lumotlar Xavfsizligi
- PDO Prepared Statements (SQL Injection himoya)
- HTML Entities (XSS himoya)
- File upload validation
- HTTPS majburiy

#### Rate Limiting
- Telegram API: 30 req/sec
- Broadcast: 30 msg/sec
- Web App API: Cheklovsiz (hozircha)

---

### 7. Xato Qayta Ishlash

#### Log Sistema
- Error logs: `bot/logs/error_YYYY-MM-DD.log`
- Update logs: `bot/logs/updates.log`
- Timestamp bilan
- Stack trace

#### Xato Turlari
- Database xatolar
- API xatolar (Fragment, SMM Upper)
- Fayl yuklash xatolar
- Telegram API xatolar

#### Foydalanuvchiga Xabarlar
- ✅ Muvaffaqiyat: Yashil
- ❌ Xato: Qizil
- ⚠️ Ogohlantirish: Sariq
- ℹ️ Ma'lumot: Ko'k

---

### 8. Performance

#### Optimizatsiya
- Database indexlar
- CSS minification (production)
- JS minification (production)
- Image optimization
- Gzip compression

#### Caching
- Browser caching (1 yil statik fayllar)
- Database query caching (ixtiyoriy)

#### Loading
- Lazy loading (rasmlar)
- Async JavaScript
- Loading spinner

---

### 9. Mobile Optimization

#### Responsive Design
- Mobile-first approach
- Flexbox/Grid
- Media queries
- Touch-friendly tugmalar (min 44px)

#### Telegram Mini App
- tg.expand() - To'liq ekran
- tg.enableClosingConfirmation() - Yopishdan oldin tasdiqlash
- tg.MainButton - Asosiy tugma (ixtiyoriy)

---

### 10. Kelajak Rivojlanish

#### Rejalashtirilgan Xususiyatlar
- [ ] Click/Payme to'liq integratsiya
- [ ] Ko'p tilli qo'llab-quvvatlash (O'zbek/Rus/Ingliz)
- [ ] Referal tizimi
- [ ] Chegirmalar va promocode
- [ ] Statistika grafiklari
- [ ] Export (Excel/CSV)
- [ ] 2FA admin uchun
- [ ] Push notifications
- [ ] Webhook callbacks
- [ ] API documentation (Swagger)

---

**Versiya:** 1.0.0  
**Oxirgi yangilanish:** 2024  
**Muallif:** Senior Full-Stack Developer
