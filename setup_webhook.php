<?php
/**
 * Telegram Webhook ni o'rnatish (Railway)
 * 
 * Bu faylni brauzerda bir marta ishga tushiring:
 * https://startsbot-production.up.railway.app/setup_webhook.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bot Token
$botToken = getenv('BOT_TOKEN') ?: '7895807418:AAHK9AUJ0oFv33uGBpxkV4TU4Ns1wHmpZcE';

// Webhook URL - Railway URL
$railwayUrl = getenv('RAILWAY_PUBLIC_DOMAIN') 
    ? 'https://' . getenv('RAILWAY_PUBLIC_DOMAIN')
    : 'https://startsbot-production.up.railway.app';

$webhookUrl = $railwayUrl . '/bot/webhook.php';

// Telegram API request
function telegramRequest($token, $method, $data = []) {
    $url = "https://api.telegram.org/bot{$token}/{$method}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['ok' => false, 'description' => 'cURL Error: ' . $error];
    }
    
    return json_decode($response, true);
}

// Webhook ni o'rnatish
$response = telegramRequest($botToken, 'setWebhook', [
    'url' => $webhookUrl,
    'max_connections' => 100,
    'drop_pending_updates' => true
]);

// Bot info
$botInfo = telegramRequest($botToken, 'getMe');

?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Setup - Railway</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
            font-size: 32px;
        }
        .status {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-size: 18px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }
        .info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .info strong {
            color: #667eea;
            display: block;
            margin-bottom: 10px;
        }
        .info-value {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            word-break: break-all;
            font-size: 14px;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 13px;
            border: 1px solid #dee2e6;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
            font-weight: bold;
        }
        .btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .emoji { font-size: 48px; margin-bottom: 20px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center">
            <div class="emoji"><?= ($response && $response['ok']) ? '✅' : '❌' ?></div>
            <h1>Telegram Webhook Setup</h1>
        </div>
        
        <?php if ($response && isset($response['ok']) && $response['ok']): ?>
            <div class="status success">
                ✅ Webhook muvaffaqiyatli o'rnatildi!
            </div>
            
            <?php if ($botInfo && $botInfo['ok']): ?>
            <div class="info">
                <strong>🤖 Bot Ma'lumotlari:</strong>
                <div class="info-value">
                    @<?= htmlspecialchars($botInfo['result']['username']) ?><br>
                    <?= htmlspecialchars($botInfo['result']['first_name']) ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="info">
                <strong>🔗 Webhook URL:</strong>
                <div class="info-value"><?= htmlspecialchars($webhookUrl) ?></div>
            </div>
            
            <div class="info">
                <strong>📋 Keyingi Qadamlar:</strong>
                <ol style="padding-left: 20px; margin-top: 10px;">
                    <li>Telegram'da botingizga /start yuboring</li>
                    <li>Database'ni import qiling (schema.pgsql.sql)</li>
                    <li>Admin yarating (INSERT INTO admins ...)</li>
                    <li>Web App'ni test qiling</li>
                </ol>
            </div>
            
        <?php else: ?>
            <div class="status error">
                ❌ Xatolik yuz berdi!
            </div>
            <div class="info">
                <strong>⚠️ Xato Tafsilotlari:</strong>
                <pre><?php 
                    echo isset($response['description']) 
                        ? htmlspecialchars($response['description']) 
                        : 'Noma\'lum xato';
                    echo "\n\nBot Token: " . substr($botToken, 0, 10) . "...";
                    echo "\nWebhook URL: " . $webhookUrl;
                ?></pre>
            </div>
        <?php endif; ?>
        
        <div class="text-center">
            <a href="webapp/index.html" class="btn">🌐 Web App</a>
            <a href="admin/" class="btn">👨‍💼 Admin Panel</a>
        </div>
    </div>
</body>
</html>
