/**
 * Telegram Stars Bot - Asosiy JavaScript
 */

// Telegram Web App API
const tg = window.Telegram.WebApp;
tg.expand();
tg.enableClosingConfirmation();

// Global o'zgaruvchilar
let userData = null;
let starsRate = 140; // 1 Star = 140 UZS (default)
let channelUsername = '';

// API base URL
const API_BASE = './api/';

/**
 * Sahifa yuklanganda
 */
document.addEventListener('DOMContentLoaded', async function() {
    // Telegram user ma'lumotlarini olish
    if (tg.initDataUnsafe && tg.initDataUnsafe.user) {
        userData = tg.initDataUnsafe.user;
        initializeApp();
    } else {
        // Test uchun (development)
        userData = {
            id: 123456789,
            first_name: 'Test',
            username: 'testuser'
        };
        initializeApp();
    }
});

/**
 * App ni ishga tushirish
 */
async function initializeApp() {
    showLoading();
    
    try {
        // Foydalanuvchi ma'lumotlarini yuklash
        await loadUserData();
        
        // Obuna tekshirish
        const isSubscribed = await checkSubscription();
        
        if (!isSubscribed) {
            showSubscriptionModal();
            hideLoading();
            return;
        }
        
        // Sozlamalarni yuklash
        await loadSettings();
        
        // Paketlarni yuklash
        await loadPackages();
        
        // Event listener larni o'rnatish
        setupEventListeners();
        
        hideLoading();
        
    } catch (error) {
        console.error('Initialization error:', error);
        showToast('Xatolik yuz berdi. Iltimos qaytadan urinib ko\'ring.', 'error');
        hideLoading();
    }
}

/**
 * Foydalanuvchi ma'lumotlarini yuklash
 */
async function loadUserData() {
    try {
        const response = await fetch(API_BASE + 'get_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userData.id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Profile ma'lumotlarini ko'rsatish
            document.getElementById('userName').textContent = truncateName(userData.first_name, 17);
            document.getElementById('profileName').textContent = userData.first_name;
            document.getElementById('profileUsername').textContent = '@' + (userData.username || 'no_username');
            document.getElementById('userBalance').textContent = formatNumber(data.balance) + ' UZS';
        }
    } catch (error) {
        console.error('Load user data error:', error);
    }
}

/**
 * Obuna tekshirish
 */
async function checkSubscription() {
    try {
        const response = await fetch(API_BASE + 'check_subscription.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userData.id })
        });
        
        const data = await response.json();
        
        if (data.channel_username) {
            channelUsername = data.channel_username;
        }
        
        return data.is_subscribed;
    } catch (error) {
        console.error('Check subscription error:', error);
        return true; // Xatolik bo'lsa, o'tkazib yuborish
    }
}

/**
 * Sozlamalarni yuklash
 */
async function loadSettings() {
    try {
        const response = await fetch(API_BASE + 'get_settings.php');
        const data = await response.json();
        
        if (data.success) {
            starsRate = parseFloat(data.stars_rate) || 140;
            document.getElementById('currentRate').textContent = starsRate;
            
            // Manual to'lov ma'lumotlari
            if (data.manual_card_number) {
                document.getElementById('cardNumber').textContent = data.manual_card_number;
            }
            if (data.manual_card_holder) {
                document.getElementById('cardHolder').textContent = data.manual_card_holder;
            }
        }
    } catch (error) {
        console.error('Load settings error:', error);
    }
}

/**
 * Paketlarni yuklash
 */
async function loadPackages() {
    try {
        const response = await fetch(API_BASE + 'get_packages.php');
        const data = await response.json();
        
        if (data.success && data.packages) {
            const container = document.getElementById('packagesContainer');
            container.innerHTML = '';
            
            data.packages.forEach(pkg => {
                const price = pkg.stars_amount * starsRate;
                const card = createPackageCard(pkg.stars_amount, price);
                container.appendChild(card);
            });
        }
    } catch (error) {
        console.error('Load packages error:', error);
    }
}

/**
 * Paket kartasini yaratish
 */
function createPackageCard(starsAmount, price) {
    const card = document.createElement('div');
    card.className = 'package-card';
    card.innerHTML = `
        <div class="stars-amount">⭐ ${formatNumber(starsAmount)}</div>
        <div class="package-price">${formatNumber(price)} UZS</div>
    `;
    
    card.addEventListener('click', () => {
        buyPackage(starsAmount, price);
    });
    
    return card;
}

/**
 * Event listener larni o'rnatish
 */
function setupEventListeners() {
    // Navigation
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const section = this.dataset.section;
            switchSection(section);
        });
    });
    
    // Balans qo'shish tugmasi
    document.getElementById('addBalanceBtn').addEventListener('click', () => {
        openPaymentModal();
    });
    
    // To'lov modalni yopish
    document.getElementById('closePaymentModal').addEventListener('click', () => {
        closePaymentModal();
    });
    
    // To'lov usullari
    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectPaymentMethod(this.dataset.method);
        });
    });
    
    // Manual to'lov yuborish
    document.getElementById('submitManualPayment').addEventListener('click', () => {
        submitManualPayment();
    });
    
    // Obuna tekshirish
    document.getElementById('checkSubscriptionBtn').addEventListener('click', async () => {
        showLoading();
        const isSubscribed = await checkSubscription();
        hideLoading();
        
        if (isSubscribed) {
            closeSubscriptionModal();
            showToast('✅ Obuna tasdiqlandi!', 'success');
        } else {
            showToast('❌ Siz hali obuna bo\'lmadingiz', 'error');
        }
    });
    
    // Qo'lda belgilash
    const customStarsInput = document.getElementById('customStarsAmount');
    customStarsInput.addEventListener('input', () => {
        updateCustomPrice();
    });
    
    document.getElementById('confirmCustomBtn').addEventListener('click', () => {
        confirmCustomPurchase();
    });
    
    document.getElementById('cancelCustomBtn').addEventListener('click', () => {
        clearCustomForm();
    });
    
    // Profile actions
    document.getElementById('chatWithAdminBtn').addEventListener('click', () => {
        // Admin bilan bog'lanish
        showToast('Admin bilan bog\'lanish...', 'info');
    });
    
    document.getElementById('myAccountBtn').addEventListener('click', () => {
        loadUserData();
        showToast('Hisobingiz yangilandi', 'success');
    });
    
    document.getElementById('purchaseHistoryBtn').addEventListener('click', () => {
        showPurchaseHistory();
    });
    
    document.getElementById('contactAdminBtn').addEventListener('click', () => {
        showToast('Admin bilan bog\'lanish...', 'info');
    });
}

/**
 * Bo'limni almashtirish
 */
function switchSection(sectionName) {
    // Barcha bo'limlarni yashirish
    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Tanlangan bo'limni ko'rsatish
    const targetSection = document.getElementById(sectionName + 'Section');
    if (targetSection) {
        targetSection.style.display = 'block';
    }
    
    // Navigation tugmalarini yangilash
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.section === sectionName) {
            btn.classList.add('active');
        }
    });
}

/**
 * Obuna modalini ko'rsatish
 */
function showSubscriptionModal() {
    const modal = document.getElementById('subscriptionModal');
    const channelLink = document.getElementById('channelLink');
    
    channelLink.textContent = channelUsername || '@channel';
    channelLink.href = 'https://t.me/' + (channelUsername ? channelUsername.replace('@', '') : 'channel');
    
    modal.classList.add('active');
}

/**
 * Obuna modalini yopish
 */
function closeSubscriptionModal() {
    const modal = document.getElementById('subscriptionModal');
    modal.classList.remove('active');
}

/**
 * To'lov modalini ochish
 */
function openPaymentModal() {
    const modal = document.getElementById('paymentModal');
    modal.classList.add('active');
    document.getElementById('manualPaymentForm').style.display = 'none';
}

/**
 * To'lov modalini yopish
 */
function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    modal.classList.remove('active');
}

/**
 * To'lov usulini tanlash
 */
function selectPaymentMethod(method) {
    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    event.target.closest('.payment-method-btn').classList.add('active');
    
    if (method === 'manual') {
        document.getElementById('manualPaymentForm').style.display = 'block';
    } else {
        document.getElementById('manualPaymentForm').style.display = 'none';
        
        // Click yoki Payme uchun (hozircha oddiy xabar)
        showToast(`${method.toUpperCase()} to\'lov tizimi hozircha ishlamayapti`, 'warning');
    }
}

/**
 * Manual to'lovni yuborish
 */
async function submitManualPayment() {
    const amount = document.getElementById('manualAmount').value;
    const fileInput = document.getElementById('receiptFile');
    
    if (!amount || amount < 1000) {
        showToast('Minimal miqdor 1000 UZS', 'error');
        return;
    }
    
    if (!fileInput.files[0]) {
        showToast('Chek faylini yuklang', 'error');
        return;
    }
    
    showLoading();
    
    try {
        const formData = new FormData();
        formData.append('user_id', userData.id);
        formData.append('amount', amount);
        formData.append('receipt', fileInput.files[0]);
        
        const response = await fetch(API_BASE + 'manual_payment.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('✅ To\'lov so\'rovi yuborildi. Admin tasdiqlashini kuting.', 'success');
            closePaymentModal();
        } else {
            showToast('❌ Xatolik: ' + (data.message || 'Noma\'lum xato'), 'error');
        }
    } catch (error) {
        console.error('Manual payment error:', error);
        showToast('❌ Xatolik yuz berdi', 'error');
    } finally {
        hideLoading();
    }
}

/**
 * Qo'lda belgilangan narxni hisoblash
 */
function updateCustomPrice() {
    const amount = parseInt(document.getElementById('customStarsAmount').value) || 0;
    const price = amount * starsRate;
    document.getElementById('customPrice').textContent = formatNumber(price) + ' UZS';
}

/**
 * Qo'lda belgilangan xaridni tasdiqlash
 */
function confirmCustomPurchase() {
    const amount = parseInt(document.getElementById('customStarsAmount').value);
    const username = document.getElementById('customUsername').value.trim();
    
    if (!amount || amount < 50 || amount > 10000000) {
        showToast('Stars miqdori 50 dan 10,000,000 gacha bo\'lishi kerak', 'error');
        shakeElement(document.getElementById('customStarsAmount'));
        return;
    }
    
    if (!username) {
        showToast('Username kiriting', 'error');
        shakeElement(document.getElementById('customUsername'));
        return;
    }
    
    if (username.includes('@')) {
        showToast('Username @ belgisiz kiriting', 'error');
        shakeElement(document.getElementById('customUsername'));
        return;
    }
    
    const price = amount * starsRate;
    buyPackage(amount, price, username);
}

/**
 * Qo'lda belgilash formasini tozalash
 */
function clearCustomForm() {
    document.getElementById('customStarsAmount').value = '';
    document.getElementById('customUsername').value = '';
    document.getElementById('customPrice').textContent = '0 UZS';
}

/**
 * Paket sotib olish
 */
async function buyPackage(starsAmount, price, username = null) {
    // Username so'rash (agar berilmagan bo'lsa)
    if (!username) {
        username = prompt('Telegram username ni kiriting (@ belgisiz):');
        if (!username) return;
        
        username = username.replace('@', '');
    }
    
    // Balansni tekshirish
    const currentBalance = await getCurrentBalance();
    
    if (currentBalance < price) {
        showToast('❌ Balans yetarli emas. Hisobingizni to\'ldiring.', 'error');
        return;
    }
    
    // Tasdiqlash
    const confirmed = confirm(`${starsAmount} Stars sotib olmoqchimisiz?\nNarx: ${formatNumber(price)} UZS\nUsername: @${username}`);
    
    if (!confirmed) return;
    
    showLoading();
    
    try {
        const response = await fetch(API_BASE + 'buy_stars.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: userData.id,
                stars_amount: starsAmount,
                username: username,
                price: price
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showConfetti();
            showToast('✅ Stars muvaffaqiyatli sotib olindi!', 'success');
            await loadUserData(); // Balansni yangilash
            clearCustomForm();
        } else {
            showToast('❌ Xatolik: ' + (data.message || 'Noma\'lum xato'), 'error');
        }
    } catch (error) {
        console.error('Buy package error:', error);
        showToast('❌ Xatolik yuz berdi', 'error');
    } finally {
        hideLoading();
    }
}

/**
 * Joriy balansni olish
 */
async function getCurrentBalance() {
    try {
        const response = await fetch(API_BASE + 'get_balance.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userData.id })
        });
        
        const data = await response.json();
        return data.balance || 0;
    } catch (error) {
        console.error('Get balance error:', error);
        return 0;
    }
}

/**
 * Xaridlar tarixini ko'rsatish
 */
async function showPurchaseHistory() {
    showLoading();
    
    try {
        const response = await fetch(API_BASE + 'get_purchases.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userData.id })
        });
        
        const data = await response.json();
        
        if (data.success && data.purchases.length > 0) {
            let historyText = '📜 Xaridlar tarixi:\n\n';
            data.purchases.forEach((purchase, index) => {
                historyText += `${index + 1}. ${purchase.stars_amount} Stars - ${formatNumber(purchase.price)} UZS\n`;
                historyText += `   Status: ${purchase.status}\n`;
                historyText += `   Sana: ${purchase.created_at}\n\n`;
            });
            
            alert(historyText);
        } else {
            showToast('Xaridlar tarixi bo\'sh', 'info');
        }
    } catch (error) {
        console.error('Purchase history error:', error);
        showToast('❌ Xatolik yuz berdi', 'error');
    } finally {
        hideLoading();
    }
}

/**
 * Utility funksiyalar
 */
function truncateName(name, maxLength = 17) {
    if (name.length <= maxLength) return name;
    return name.substring(0, maxLength) + '...';
}

function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

function showLoading() {
    document.getElementById('loadingSpinner').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loadingSpinner').style.display = 'none';
}

function showToast(message, type = 'info') {
    window.animations.showToast(message, type);
}

function shakeElement(element) {
    window.animations.shakeElement(element);
}

function showConfetti() {
    window.animations.showConfetti();
}
