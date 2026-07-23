<?php
/**
 * Admin Panel - Sozlamalar
 */

session_start();
require_once __DIR__ . '/../bot/config_loader.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$message = '';

// Sozlamalarni saqlash
if (isset($_POST['save_settings'])) {
    $starsRate = floatval($_POST['stars_rate']);
    $apiMethod = $_POST['api_method'];
    $fragmentToken = $_POST['fragment_jwt_token'];
    $smmupperKey = $_POST['smmupper_api_key'];
    $channelUsername = $_POST['channel_username'];
    $channelId = $_POST['channel_id'];
    $cardNumber = $_POST['manual_card_number'];
    $cardHolder = $_POST['manual_card_holder'];
    
    updateSetting('stars_rate', $starsRate);
    updateSetting('api_method', $apiMethod);
    updateSetting('fragment_jwt_token', $fragmentToken);
    updateSetting('smmupper_api_key', $smmupperKey);
    updateSetting('channel_username', $channelUsername);
    updateSetting('channel_id', $channelId);
    updateSetting('manual_card_number', $cardNumber);
    updateSetting('manual_card_holder', $cardHolder);
    
    $message = '✅ Sozlamalar saqlandi';
}

// Joriy sozlamalar
$starsRate = getSetting('stars_rate', '140');
$apiMethod = getSetting('api_method', 'fragment');
$fragmentToken = getSetting('fragment_jwt_token', '');
$smmupperKey = getSetting('smmupper_api_key', '');
$channelUsername = getSetting('channel_username', '@yourchannel');
$channelId = getSetting('channel_id', '-100');
$cardNumber = getSetting('manual_card_number', '');
$cardHolder = getSetting('manual_card_holder', '');
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sozlamalar - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0a0a;
            color: #fff;
            padding: 20px;
        }
        .container { max-width: 800px; margin: 0 auto; }
        h1 {
            color: #00ff00;
            text-shadow: 0 0 10px #00ff00;
            margin-bottom: 30px;
            text-align: center;
        }
        .nav {
            background: #1a1a1a;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            border: 2px solid #00ff00;
        }
        .nav a {
            color: #00ff00;
            text-decoration: none;
            padding: 10px 20px;
            background: #2a2a2a;
            border: 2px solid #00ff00;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .nav a:hover {
            background: #00ff00;
            color: #000;
        }
        .form-section {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 10px;
            border: 2px solid #00ff00;
            margin-bottom: 20px;
        }
        h2 {
            color: #00ff00;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #a0a0a0;
            font-size: 14px;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            background: #2a2a2a;
            border: 2px solid #00ff00;
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 15px 30px;
            background: transparent;
            border: 2px solid #00ff00;
            color: #00ff00;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        button:hover {
            background: #00ff00;
            color: #000;
        }
        .message {
            background: #00ff00;
            color: #000;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .info-box {
            background: #2a2a2a;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #00d4ff;
            margin-top: 10px;
            font-size: 14px;
            color: #a0a0a0;
        }
        .info-box strong {
            color: #00d4ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ Sozlamalar</h1>
        
        <div class="nav">
            <a href="index.php">Dashboard</a>
            <a href="settings.php">⚙️ Sozlamalar</a>
            <a href="broadcast.php">📢 Broadcast</a>
            <a href="payments.php">💳 To'lovlar</a>
        </div>
        
        <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <!-- Stars narxi -->
            <div class="form-section">
                <h2>💰 Stars Narxi</h2>
                <div class="form-group">
                    <label>1 Star narxi (UZS)</label>
                    <input type="number" name="stars_rate" value="<?= $starsRate ?>" step="0.01" required>
                    <div class="info-box">
                        <strong>Masalan:</strong> 140 UZS = 1 Star
                    </div>
                </div>
            </div>
            
            <!-- API sozlamalari -->
            <div class="form-section">
                <h2>🔌 API Sozlamalari</h2>
                <div class="form-group">
                    <label>API Usuli</label>
                    <select name="api_method" required>
                        <option value="fragment" <?= $apiMethod == 'fragment' ? 'selected' : '' ?>>Fragment API</option>
                        <option value="smmupper" <?= $apiMethod == 'smmupper' ? 'selected' : '' ?>>SMM Upper API</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Fragment JWT Token</label>
                    <textarea name="fragment_jwt_token" rows="3"><?= htmlspecialchars($fragmentToken) ?></textarea>
                    <div class="info-box">
                        Fragment API uchun JWT tokenni bu yerga kiriting
                    </div>
                </div>
                
                <div class="form-group">
                    <label>SMM Upper API Key</label>
                    <input type="text" name="smmupper_api_key" value="<?= htmlspecialchars($smmupperKey) ?>">
                    <div class="info-box">
                        <strong>SMM Upper sozlash:</strong><br>
                        1. @smmuppercombot ga kiring<br>
                        2. API kalitini oling<br>
                        3. Botga yuboring (Masalan: 9e7cf27c6922dc2)<br>
                        4. Bu yerga kiriting
                    </div>
                </div>
            </div>
            
            <!-- Kanal sozlamalari -->
            <div class="form-section">
                <h2>📢 Majburiy Kanal</h2>
                <div class="form-group">
                    <label>Kanal Username</label>
                    <input type="text" name="channel_username" value="<?= htmlspecialchars($channelUsername) ?>" placeholder="@yourchannel">
                </div>
                <div class="form-group">
                    <label>Kanal ID</label>
                    <input type="text" name="channel_id" value="<?= htmlspecialchars($channelId) ?>" placeholder="-1001234567890">
                    <div class="info-box">
                        <strong>Kanal ID ni olish:</strong><br>
                        @userinfobot ga kanal postini forward qiling
                    </div>
                </div>
            </div>
            
            <!-- Manual to'lov -->
            <div class="form-section">
                <h2>💳 Manual To'lov (Karta)</h2>
                <div class="form-group">
                    <label>Karta Raqami</label>
                    <input type="text" name="manual_card_number" value="<?= htmlspecialchars($cardNumber) ?>" placeholder="8600 1234 5678 9012">
                </div>
                <div class="form-group">
                    <label>Karta Egasi (Ism-Familiya)</label>
                    <input type="text" name="manual_card_holder" value="<?= htmlspecialchars($cardHolder) ?>" placeholder="ABDULLAYEV SARDOR">
                </div>
            </div>
            
            <button type="submit" name="save_settings">💾 Saqlash</button>
        </form>
    </div>
</body>
</html>
