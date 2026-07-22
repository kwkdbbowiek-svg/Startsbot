<?php
/**
 * Admin Panel - To'lovlarni Boshqarish
 */

session_start();
require_once '../bot/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$message = '';

// To'lovni tasdiqlash
if (isset($_POST['approve_payment'])) {
    $paymentId = intval($_POST['payment_id']);
    
    try {
        $pdo->beginTransaction();
        
        // To'lov ma'lumotlarini olish
        $stmt = $pdo->prepare("SELECT user_id, amount FROM payments WHERE id = ? AND status = 'pending'");
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch();
        
        if ($payment) {
            $userId = $payment['user_id'];
            $amount = $payment['amount'];
            
            // Foydalanuvchi balansiga qo'shish
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$amount, $userId]);
            
            // To'lov statusini yangilash
            $stmt = $pdo->prepare("UPDATE payments SET status = 'approved', approved_at = NOW() WHERE id = ?");
            $stmt->execute([$paymentId]);
            
            $pdo->commit();
            
            // Foydalanuvchiga xabar yuborish
            $text = "✅ <b>To'lovingiz tasdiqlandi!</b>\n\n";
            $text .= "💰 Miqdor: " . number_format($amount, 0, '.', ' ') . " UZS\n";
            $text .= "💳 Hisobingizga qo'shildi.\n\n";
            $text .= "Endi Stars sotib olishingiz mumkin!";
            
            sendMessage($userId, $text);
            
            $message = '✅ To\'lov tasdiqlandi va balans qo\'shildi';
        } else {
            $message = '❌ To\'lov topilmadi yoki allaqachon tasdiqlangan';
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $message = '❌ Xatolik: ' . $e->getMessage();
    }
}

// To'lovni rad etish
if (isset($_POST['reject_payment'])) {
    $paymentId = intval($_POST['payment_id']);
    
    try {
        $stmt = $pdo->prepare("SELECT user_id, amount FROM payments WHERE id = ? AND status = 'pending'");
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch();
        
        if ($payment) {
            $userId = $payment['user_id'];
            $amount = $payment['amount'];
            
            $stmt = $pdo->prepare("UPDATE payments SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$paymentId]);
            
            // Foydalanuvchiga xabar yuborish
            $text = "❌ <b>To'lovingiz rad etildi</b>\n\n";
            $text .= "💰 Miqdor: " . number_format($amount, 0, '.', ' ') . " UZS\n\n";
            $text .= "Iltimos, admin bilan bog'laning.";
            
            sendMessage($userId, $text);
            
            $message = '❌ To\'lov rad etildi';
        }
    } catch (Exception $e) {
        $message = '❌ Xatolik: ' . $e->getMessage();
    }
}

// Kutilayotgan to'lovlar
$stmt = $pdo->query("SELECT p.*, u.first_name, u.username FROM payments p JOIN users u ON p.user_id = u.id WHERE p.status = 'pending' ORDER BY p.created_at DESC");
$pendingPayments = $stmt->fetchAll();

// Barcha to'lovlar tarixi
$stmt = $pdo->query("SELECT p.*, u.first_name, u.username FROM payments p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 50");
$allPayments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To'lovlar - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0a0a;
            color: #fff;
            padding: 20px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
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
        .section {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #00ff00;
            margin-bottom: 30px;
        }
        h2 {
            color: #00ff00;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .status-pending { color: #ffff00; }
        .status-approved { color: #00ff00; }
        .status-rejected { color: #ff0040; }
        .btn {
            padding: 8px 16px;
            border: 2px solid;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
            transition: all 0.3s;
        }
        .btn-approve {
            background: transparent;
            border-color: #00ff00;
            color: #00ff00;
        }
        .btn-approve:hover {
            background: #00ff00;
            color: #000;
        }
        .btn-reject {
            background: transparent;
            border-color: #ff0040;
            color: #ff0040;
        }
        .btn-reject:hover {
            background: #ff0040;
            color: #fff;
        }
        .btn-view {
            background: transparent;
            border-color: #00d4ff;
            color: #00d4ff;
        }
        .btn-view:hover {
            background: #00d4ff;
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
        .no-data {
            text-align: center;
            color: #a0a0a0;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💳 To'lovlarni Boshqarish</h1>
        
        <div class="nav">
            <a href="index.php">Dashboard</a>
            <a href="settings.php">⚙️ Sozlamalar</a>
            <a href="broadcast.php">📢 Broadcast</a>
            <a href="payments.php">💳 To'lovlar</a>
        </div>
        
        <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
        <?php endif; ?>
        
        <!-- Kutilayotgan to'lovlar -->
        <div class="section">
            <h2>⏳ Kutilayotgan To'lovlar</h2>
            <?php if (empty($pendingPayments)): ?>
            <div class="no-data">Kutilayotgan to'lovlar yo'q</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foydalanuvchi</th>
                        <th>Miqdor</th>
                        <th>Usul</th>
                        <th>Chek</th>
                        <th>Sana</th>
                        <th>Harakat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingPayments as $payment): ?>
                    <tr>
                        <td><?= $payment['id'] ?></td>
                        <td><?= htmlspecialchars($payment['first_name']) ?> (@<?= htmlspecialchars($payment['username']) ?>)</td>
                        <td><?= number_format($payment['amount'], 0, '.', ' ') ?> UZS</td>
                        <td><?= strtoupper($payment['payment_method']) ?></td>
                        <td>
                            <?php if ($payment['receipt_file_id']): ?>
                            <a href="../../uploads/receipts/<?= htmlspecialchars($payment['receipt_file_id']) ?>" target="_blank" class="btn btn-view">
                                👁️ Ko'rish
                            </a>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td><?= date('d.m.Y H:i', strtotime($payment['created_at'])) ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="payment_id" value="<?= $payment['id'] ?>">
                                <button type="submit" name="approve_payment" class="btn btn-approve" onclick="return confirm('To\'lovni tasdiqlaysizmi?')">
                                    ✅ Tasdiqlash
                                </button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="payment_id" value="<?= $payment['id'] ?>">
                                <button type="submit" name="reject_payment" class="btn btn-reject" onclick="return confirm('To\'lovni rad etasizmi?')">
                                    ❌ Rad etish
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        
        <!-- Barcha to'lovlar -->
        <div class="section">
            <h2>📜 Barcha To'lovlar</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foydalanuvchi</th>
                        <th>Miqdor</th>
                        <th>Usul</th>
                        <th>Status</th>
                        <th>Sana</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allPayments as $payment): ?>
                    <tr>
                        <td><?= $payment['id'] ?></td>
                        <td><?= htmlspecialchars($payment['first_name']) ?> (@<?= htmlspecialchars($payment['username']) ?>)</td>
                        <td><?= number_format($payment['amount'], 0, '.', ' ') ?> UZS</td>
                        <td><?= strtoupper($payment['payment_method']) ?></td>
                        <td class="status-<?= $payment['status'] ?>"><?= ucfirst($payment['status']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($payment['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
