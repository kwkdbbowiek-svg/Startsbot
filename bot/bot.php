<?php
/**
 * Telegram Stars Bot - Asosiy Bot Mantiq
 * 
 * Bu fayl botning asosiy komandalarini va xabarlarni qayta ishlaydi
 */

// Railway yoki local config yuklash
if (getenv('RAILWAY_ENVIRONMENT')) {
    require_once 'config.railway.php';
} else {
    require_once 'config.php';
}

// Webhook dan kelgan ma'lumotni olish
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
    exit;
}

// Log yozish (debug uchun)
file_put_contents(__DIR__ . '/logs/updates.log', date('Y-m-d H:i:s') . "\n" . $content . "\n\n", FILE_APPEND);

// Message yoki Callback Query ni aniqlash
if (isset($update['message'])) {
    $message = $update['message'];
    $chatId = $message['chat']['id'];
    $userId = $message['from']['id'];
    $username = $message['from']['username'] ?? '';
    $firstName = $message['from']['first_name'] ?? 'Foydalanuvchi';
    $lastName = $message['from']['last_name'] ?? '';
    $text = $message['text'] ?? '';
    
    // Foydalanuvchini saqlash
    saveUser($userId, $username, $firstName, $lastName);
    
    // Komandalarni qayta ishlash
    if (strpos($text, '/start') === 0) {
        handleStartCommand($chatId, $firstName);
    } elseif ($text === '/help') {
        handleHelpCommand($chatId);
    } elseif ($text === '/balance') {
        handleBalanceCommand($chatId, $userId);
    } elseif ($text === '/admin' && isAdmin($userId)) {
        handleAdminCommand($chatId);
    }
    
} elseif (isset($update['callback_query'])) {
    $callbackQuery = $update['callback_query'];
    $chatId = $callbackQuery['message']['chat']['id'];
    $userId = $callbackQuery['from']['id'];
    $messageId = $callbackQuery['message']['message_id'];
    $data = $callbackQuery['data'];
    $callbackId = $callbackQuery['id'];
    
    // Callback query larni qayta ishlash
    handleCallbackQuery($chatId, $userId, $messageId, $data, $callbackId);
}

/**
 * /start komandasi
 */
function handleStartCommand($chatId, $firstName) {
    // Ismni 17 belgiga qisqartirish
    $shortName = truncateName($firstName, 17);
    $botName = getSetting('bot_username', BOT_USERNAME);
    $webappUrl = getSetting('webapp_url', WEBAPP_URL);
    
    // Xabar matni (HTML format)
    $text = "<b>Assalamu aleykum</b> {$shortName}\n\n";
    $text .= "{$botName} ga xush kelibsiz.\n\n";
    $text .= "<b>Arzon narxlarda Stars olishingiz mumkin\n";
    $text .= "Hisobingizga birpasda tushadi</b>\n\n";
    $text .= "<i>Pastdagi tugma orqali kirib zakaz berishingiz mumkin!</i>";
    
    // Inline tugma (Web App)
    $keyboard = [
        'inline_keyboard' => [
            [
                [
                    'text' => '🔥 Xizmatlar',
                    'web_app' => ['url' => $webappUrl]
                ]
            ]
        ]
    ];
    
    sendMessage($chatId, $text, $keyboard);
}

/**
 * /help komandasi
 */
function handleHelpCommand($chatId) {
    $text = "<b>📚 Yordam</b>\n\n";
    $text .= "Buyurtma berish:\n";
    $text .= "1. 🔥 Xizmatlar tugmasini bosing\n";
    $text .= "2. Kerakli Stars paketini tanlang\n";
    $text .= "3. Hisobingizni to'ldiring\n";
    $text .= "4. Buyurtma bering\n\n";
    $text .= "Savollar uchun admin bilan bog'laning.";
    
    sendMessage($chatId, $text);
}

/**
 * /balance komandasi
 */
function handleBalanceCommand($chatId, $userId) {
    $balance = getUserBalance($userId);
    
    $text = "<b>💰 Sizning balansingiz:</b>\n\n";
    $text .= number_format($balance, 0, '.', ' ') . " UZS";
    
    sendMessage($chatId, $text);
}

/**
 * Admin komandasi
 */
function handleAdminCommand($chatId) {
    $text = "<b>👨‍💼 Admin Panel</b>\n\n";
    $text .= "Admin panelga kirish uchun:\n";
    $text .= "https://yourdomain.com/admin/";
    
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '📊 Statistika', 'callback_data' => 'admin_stats']
            ],
            [
                ['text' => '📢 Broadcast', 'callback_data' => 'admin_broadcast']
            ],
            [
                ['text' => '⚙️ Sozlamalar', 'callback_data' => 'admin_settings']
            ]
        ]
    ];
    
    sendMessage($chatId, $text, $keyboard);
}

/**
 * Callback Query larni qayta ishlash
 */
function handleCallbackQuery($chatId, $userId, $messageId, $data, $callbackId) {
    global $pdo;
    
    // Admin callback lari
    if (strpos($data, 'admin_') === 0 && isAdmin($userId)) {
        handleAdminCallback($chatId, $userId, $messageId, $data, $callbackId);
        return;
    }
    
    // Standart callback javob
    answerCallbackQuery($callbackId, 'Ma\'lumot qayta ishlanmoqda...');
}

/**
 * Admin callback larni qayta ishlash
 */
function handleAdminCallback($chatId, $userId, $messageId, $data, $callbackId) {
    global $pdo;
    
    switch ($data) {
        case 'admin_stats':
            // Statistika
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
            $totalUsers = $stmt->fetch()['total'];
            
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM purchases WHERE status = 'completed'");
            $totalPurchases = $stmt->fetch()['total'];
            
            $stmt = $pdo->query("SELECT SUM(price) as total FROM purchases WHERE status = 'completed'");
            $totalRevenue = $stmt->fetch()['total'] ?? 0;
            
            $text = "<b>📊 Statistika</b>\n\n";
            $text .= "👥 Jami foydalanuvchilar: " . number_format($totalUsers) . "\n";
            $text .= "🛒 Jami sotuvlar: " . number_format($totalPurchases) . "\n";
            $text .= "💰 Jami daromad: " . number_format($totalRevenue, 0, '.', ' ') . " UZS";
            
            telegramRequest('editMessageText', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'parse_mode' => 'HTML'
            ]);
            
            answerCallbackQuery($callbackId);
            break;
            
        case 'admin_broadcast':
            $text = "📢 Broadcast xabar yuborish uchun admin panelga kiring:\n";
            $text .= "https://yourdomain.com/admin/broadcast.php";
            
            answerCallbackQuery($callbackId, $text, true);
            break;
            
        case 'admin_settings':
            $text = "⚙️ Sozlamalarni o'zgartirish uchun admin panelga kiring:\n";
            $text .= "https://yourdomain.com/admin/settings.php";
            
            answerCallbackQuery($callbackId, $text, true);
            break;
    }
}

/**
 * Foydalanuvchi admin ekanligini tekshirish
 */
function isAdmin($userId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        return false;
    }
}
