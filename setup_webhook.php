<?php
/**
 * Telegram Webhook ni o'rnatish
 * 
 * Bu faylni brauzerda bir marta ishga tushiring:
 * https://yourdomain.com/setup_webhook.php
 */

require_once 'bot/config.php';

// Webhook URL
$webhookUrl = 'https://yourdomain.com/bot/webhook.php'; // O'z domeningizga o'zgartiring

// Webhook ni o'rnatish
$response = telegramRequest('setWebhook', [
    'url' => $webhookUrl,
    'max_connections' => 100,
    'drop_pending_updates' => true
]);

?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #0a0a0a;
            color: #fff;
            padding: 40px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #1a1a1a;
            padding: 40px;
            border-radius: 10px;
            border: 2px solid #00ff00;
        }
        h1 { color: #00ff00; margin-bottom: 20px; }
        .success { color: #00ff00; font-size: 18px; margin: 20px 0; }
        .error { color: #ff0040; font-size: 18px; margin: 20px 0; }
        .info { background: #2a2a2a; padding: 20px; border-radius: 5px; text-align: left; margin-top: 20px; }
        pre { background: #000; padding: 15px; border-radius: 5px; overflow-x: auto; }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 30px;
            background: transparent;
            border: 2px solid #00ff00;
            color: #00ff00;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }
        a:hover {
            background: #00ff00;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🤖 Telegram Webhook Setup</h1>
        
        <?php if ($response && isset($response['ok']) && $response['ok']): ?>
            <div class="success">
                ✅ Webhook muvaffaqiyatli o'rnatildi!
            </div>
            <div class="info">
                <strong>Webhook URL:</strong><br>
                <?= htmlspecialchars($webhookUrl) ?>
            </div>
        <?php else: ?>
            <div class="error">
                ❌ Xatolik yuz berdi!
            </div>
            <div class="info">
                <strong>Xato tafsilotlari:</strong>
                <pre><?= isset($response['description']) ? htmlspecialchars($response['description']) : 'Noma\'lum xato' ?></pre>
            </div>
        <?php endif; ?>
        
        <div class="info" style="margin-top: 30px;">
            <strong>📋 Tekshirish uchun:</strong><br>
            Telegram botingizga /start komandasi yuboring
        </div>
        
        <a href="admin/">Admin Panelga O'tish</a>
    </div>
</body>
</html>
