<?php
/**
 * Railway.app uchun Config
 * Environment Variables dan sozlamalarni oladi
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Tashkent');

// Railway Environment Variables
define('BOT_TOKEN', getenv('BOT_TOKEN') ?: 'YOUR_BOT_TOKEN_HERE');
define('BOT_USERNAME', getenv('BOT_USERNAME') ?: '@YourBotUsername');
define('WEBAPP_URL', getenv('WEBAPP_URL') ?: 'https://your-app.railway.app/webapp/index.html');

// Railway MySQL Database
// Railway avtomatik DATABASE_URL beradi
$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // DATABASE_URL parse qilish
    // Format: mysql://user:pass@host:port/dbname
    $dbParts = parse_url($databaseUrl);
    
    define('DB_HOST', $dbParts['host']);
    define('DB_NAME', ltrim($dbParts['path'], '/'));
    define('DB_USER', $dbParts['user']);
    define('DB_PASS', $dbParts['pass']);
    define('DB_PORT', $dbParts['port'] ?? 3306);
} else {
    // Manual config (agar DATABASE_URL bo'lmasa)
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    define('DB_NAME', getenv('DB_NAME') ?: 'telegram_stars_bot');
    define('DB_USER', getenv('DB_USER') ?: 'root');
    define('DB_PASS', getenv('DB_PASS') ?: '');
    define('DB_PORT', getenv('DB_PORT') ?: 3306);
}

define('DB_CHARSET', 'utf8mb4');

// Database ulanish
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Database ulanish xatosi: " . $e->getMessage());
}

// API URLs
define('FRAGMENT_API_URL', 'https://api.fragment-api.com/v1/order/stars/');
define('SMMUPPER_API_URL', 'https://smmupper.uz/api/v2');
define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

// Barcha funksiyalar config.php dan
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
    $channelId = getSetting('channel_id');
    
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
