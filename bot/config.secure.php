<?php
/**
 * Telegram Stars Bot - XAVFSIZ Konfiguratsiya
 * 
 * .env fayldan o'qiydi (GitHub ga yuklanmaydi!)
 */

// .env faylni yuklash
function loadEnv($path) {
    if (!file_exists($path)) {
        die(".env fayl topilmadi! .env.example dan nusxa oling va .env nomini bering.");
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Kommentlarni o'tkazib yuborish
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // KEY=VALUE ni parse qilish
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Qo'shtirnoqlarni olib tashlash
            $value = trim($value, '"\'');
            
            // Environment variable sifatida o'rnatish
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// .env faylni yuklash
$envPath = __DIR__ . '/../.env';
loadEnv($envPath);

// Helper funksiya - environment variable olish
function env($key, $default = null) {
    $value = getenv($key);
    
    if ($value === false) {
        return $default;
    }
    
    // Boolean qiymatlarni convert qilish
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }
    
    return $value;
}

// Xatoliklarni ko'rsatish (environment asosida)
if (env('APP_ENV') === 'development' || env('APP_DEBUG', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Vaqt zonasini o'rnatish
date_default_timezone_set('Asia/Tashkent');

// ============================================
// TELEGRAM BOT SOZLAMALARI (.env dan)
// ============================================
define('BOT_TOKEN', env('BOT_TOKEN'));
define('BOT_USERNAME', env('BOT_USERNAME', '@YourBotUsername'));
define('WEBAPP_URL', env('WEBAPP_URL'));

// ============================================
// DATABASE SOZLAMALARI (.env dan)
// ============================================
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'telegram_stars_bot'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_CHARSET', 'utf8mb4');

// Validation
if (empty(BOT_TOKEN) || BOT_TOKEN === 'your_bot_token_here') {
    die("XATO: BOT_TOKEN .env faylda to'g'ri sozlanmagan!");
}

// Database ulanishi
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    if (env('APP_DEBUG', false)) {
        die("Database ulanish xatosi: " . $e->getMessage());
    } else {
        die("Database ulanish xatosi. Admin bilan bog'laning.");
    }
}

// ============================================
// API SOZLAMALARI
// ============================================
define('FRAGMENT_API_URL', 'https://api.fragment-api.com/v1/order/stars/');
define('SMMUPPER_API_URL', 'https://smmupper.uz/api/v2');
define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

// ============================================
// YORDAMCHI FUNKSIYALAR
// ============================================

function getSetting($key, $default = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

function updateSetting($key, $value) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                               ON DUPLICATE KEY UPDATE setting_value = ?");
        return $stmt->execute([$key, $value, $value]);
    } catch (PDOException $e) {
        return false;
    }
}

function logError($message) {
    $logFile = __DIR__ . '/logs/error_' . date('Y-m-d') . '.log';
    $logDir = dirname($logFile);
    
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

function telegramRequest($method, $data = []) {
    $url = API_URL . $method;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        logError("cURL xatosi: " . curl_error($ch));
        curl_close($ch);
        return false;
    }
    
    curl_close($ch);
    return json_decode($response, true);
}

function sendMessage($chatId, $text, $replyMarkup = null, $parseMode = 'HTML') {
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => $parseMode
    ];
    
    if ($replyMarkup) {
        $data['reply_markup'] = json_encode($replyMarkup);
    }
    
    return telegramRequest('sendMessage', $data);
}

function sendPhoto($chatId, $photo, $caption = '', $replyMarkup = null) {
    $data = [
        'chat_id' => $chatId,
        'photo' => $photo,
        'caption' => $caption,
        'parse_mode' => 'HTML'
    ];
    
    if ($replyMarkup) {
        $data['reply_markup'] = json_encode($replyMarkup);
    }
    
    return telegramRequest('sendPhoto', $data);
}

function answerCallbackQuery($callbackQueryId, $text = '', $showAlert = false) {
    return telegramRequest('answerCallbackQuery', [
        'callback_query_id' => $callbackQueryId,
        'text' => $text,
        'show_alert' => $showAlert
    ]);
}

function checkChannelSubscription($userId) {
    $channelId = env('CHANNEL_ID', getSetting('channel_id'));
    
    if (empty($channelId) || $channelId == '-100') {
        return true;
    }
    
    $response = telegramRequest('getChatMember', [
        'chat_id' => $channelId,
        'user_id' => $userId
    ]);
    
    if ($response && $response['ok']) {
        $status = $response['result']['status'];
        return in_array($status, ['creator', 'administrator', 'member']);
    }
    
    return false;
}

function saveUser($userId, $username, $firstName, $lastName = '') {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (id, username, first_name, last_name) 
                               VALUES (?, ?, ?, ?) 
                               ON DUPLICATE KEY UPDATE 
                               username = VALUES(username), 
                               first_name = VALUES(first_name),
                               last_name = VALUES(last_name),
                               updated_at = CURRENT_TIMESTAMP");
        
        return $stmt->execute([$userId, $username, $firstName, $lastName]);
    } catch (PDOException $e) {
        logError("Foydalanuvchini saqlashda xato: " . $e->getMessage());
        return false;
    }
}

function getUserBalance($userId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        
        return $result ? floatval($result['balance']) : 0;
    } catch (PDOException $e) {
        return 0;
    }
}

function truncateName($name, $maxLength = 17) {
    if (mb_strlen($name, 'UTF-8') <= $maxLength) {
        return $name;
    }
    
    return mb_substr($name, 0, $maxLength, 'UTF-8') . '...';
}
