<?php
/**
 * Admin Panel - Broadcast (Xabar Tarqatish)
 */

session_start();
require_once '../bot/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = '';

// Broadcast yuborish
if (isset($_POST['send_broadcast'])) {
    $messageText = trim($_POST['message_text']);
    $photoFileId = '';
    
    // Rasm yuklash
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $file = $_FILES['photo'];
        $uploadDir = __DIR__ . '/../uploads/broadcast/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('broadcast_') . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $photoFileId = $fileName;
        }
    }
    
    if (empty($messageText) && empty($photoFileId)) {
        $error = '❌ Matn yoki rasm kiriting';
    } else {
        // Barcha foydalanuvchilarni olish
        $stmt = $pdo->query("SELECT id FROM users");
        $users = $stmt->fetchAll();
        
        $totalUsers = count($users);
        $sentCount = 0;
        $failedCount = 0;
        
        foreach ($users as $user) {
            $userId = $user['id'];
            
            try {
                if ($photoFileId) {
                    // Rasm bilan
                    $result = sendPhoto($userId, $filePath, $messageText);
                } else {
                    // Faqat matn
                    $result = sendMessage($userId, $messageText);
                }
                
                if ($result && isset($result['ok']) && $result['ok']) {
                    $sentCount++;
                } else {
                    $failedCount++;
                }
                
                // Rate limit uchun kutish (30 ta xabar/soniya)
                usleep(35000); // 35ms
                
            } catch (Exception $e) {
                $failedCount++;
                logError("Broadcast error for user $userId: " . $e->getMessage());
            }
        }
        
        // Broadcast tarixga saqlash
        $stmt = $pdo->prepare("INSERT INTO broadcasts (admin_id, message_text, photo_file_id, total_users, sent_count, failed_count, status, completed_at) VALUES (?, ?, ?, ?, ?, ?, 'completed', NOW())");
        $stmt->execute([$_SESSION['admin_id'], $messageText, $photoFileId, $totalUsers, $sentCount, $failedCount]);
        
        $message = "✅ Broadcast yuborildi!\n\n";
        $message .= "📊 Jami: $totalUsers\n";
        $message .= "✅ Yuborildi: $sentCount\n";
        $message .= "❌ Xatolik: $failedCount";
    }
}

// Broadcast tarixi
$stmt = $pdo->query("SELECT * FROM broadcasts ORDER BY created_at DESC LIMIT 20");
$broadcastHistory = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broadcast - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0a0a;
            color: #fff;
            padding: 20px;
        }
        .container { max-width: 1000px; margin: 0 auto; }
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
            margin-bottom: 30px;
        }
        h2 {
            color: #00ff00;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #a0a0a0;
        }
        textarea {
            width: 100%;
            padding: 12px;
            background: #2a2a2a;
            border: 2px solid #00ff00;
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
            min-height: 150px;
            font-family: inherit;
        }
        input[type="file"] {
            width: 100%;
            padding: 12px;
            background: #2a2a2a;
            border: 2px solid #00ff00;
            color: #fff;
            border-radius: 5px;
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
            white-space: pre-line;
            font-weight: bold;
        }
        .error {
            background: #ff0040;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #1a1a1a;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #2a2a2a;
        }
        th {
            background: #2a2a2a;
            color: #00ff00;
        }
        .status-completed { color: #00ff00; }
        .status-failed { color: #ff0040; }
        .info-box {
            background: #2a2a2a;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #00d4ff;
            margin-top: 10px;
            font-size: 14px;
            color: #a0a0a0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📢 Broadcast Xabar Yuborish</h1>
        
        <div class="nav">
            <a href="index.php">Dashboard</a>
            <a href="settings.php">⚙️ Sozlamalar</a>
            <a href="broadcast.php">📢 Broadcast</a>
            <a href="payments.php">💳 To'lovlar</a>
        </div>
        
        <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="form-section">
            <h2>✉️ Yangi Broadcast</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>📝 Xabar Matni (HTML format qo'llab-quvvatlanadi)</label>
                    <textarea name="message_text" placeholder="Xabaringizni kiriting...&#10;&#10;<b>Bold</b>, <i>Italic</i>, <code>Code</code> ishlatishingiz mumkin"></textarea>
                </div>
                
                <div class="form-group">
                    <label>🖼 Rasm (ixtiyoriy)</label>
                    <input type="file" name="photo" accept="image/*">
                </div>
                
                <div class="info-box">
                    <strong>⚠️ Diqqat:</strong><br>
                    Bu barcha foydalanuvchilarga xabar yuboradi. Iltimos, ehtiyot bo'ling!<br>
                    Telegram rate limit: 30 xabar/soniya
                </div>
                
                <button type="submit" name="send_broadcast" onclick="return confirm('Barcha foydalanuvchilarga xabar yuborishni tasdiqlaysizmi?')">
                    📤 Yuborish
                </button>
            </form>
        </div>
        
        <div class="form-section">
            <h2>📜 Broadcast Tarixi</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Matn</th>
                        <th>Jami</th>
                        <th>Yuborildi</th>
                        <th>Xatolik</th>
                        <th>Sana</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($broadcastHistory)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #a0a0a0;">Broadcast tarixi yo'q</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($broadcastHistory as $bc): ?>
                        <tr>
                            <td><?= $bc['id'] ?></td>
                            <td><?= htmlspecialchars(mb_substr($bc['message_text'], 0, 50)) ?>...</td>
                            <td><?= $bc['total_users'] ?></td>
                            <td class="status-completed"><?= $bc['sent_count'] ?></td>
                            <td class="status-failed"><?= $bc['failed_count'] ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($bc['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
