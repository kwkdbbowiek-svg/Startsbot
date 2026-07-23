<?php
/**
 * Admin Panel - Dashboard
 */

session_start();
require_once __DIR__ . '/../bot/config_loader.php';

// Admin autentifikatsiyasini tekshirish (oddiy versiya)
// Production da to'liq autentifikatsiya tizimini qo'shing
if (!isset($_SESSION['admin_id'])) {
    // Agar login qilinmagan bo'lsa, login sahifasiga yo'naltirish
    // Hozircha oddiy parol orqali kirish
    if (isset($_POST['login'])) {
        $password = $_POST['password'] ?? '';
        
        // Oddiy parol (production da hash ishlatish kerak)
        if ($password === 'admin123') {
            $_SESSION['admin_id'] = 1;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Parol noto\'g\'ri';
        }
    }
    
    if (!isset($_SESSION['admin_id'])) {
        ?>
        <!DOCTYPE html>
        <html lang="uz">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #0a0a0a;
                    color: #fff;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .login-box {
                    background: #1a1a1a;
                    padding: 40px;
                    border-radius: 10px;
                    border: 2px solid #00ff00;
                    box-shadow: 0 0 20px rgba(0, 255, 0, 0.3);
                }
                h2 { color: #00ff00; text-align: center; }
                input {
                    width: 100%;
                    padding: 12px;
                    margin: 10px 0;
                    background: #2a2a2a;
                    border: 2px solid #00ff00;
                    color: #fff;
                    border-radius: 5px;
                }
                button {
                    width: 100%;
                    padding: 12px;
                    background: transparent;
                    border: 2px solid #00ff00;
                    color: #00ff00;
                    cursor: pointer;
                    border-radius: 5px;
                    font-size: 16px;
                }
                button:hover {
                    background: #00ff00;
                    color: #000;
                }
                .error { color: #ff0040; text-align: center; margin-top: 10px; }
            </style>
        </head>
        <body>
            <div class="login-box">
                <h2>🔐 Admin Login</h2>
                <form method="POST">
                    <input type="password" name="password" placeholder="Parol" required>
                    <button type="submit" name="login">Kirish</button>
                    <?php if (isset($error)): ?>
                        <div class="error"><?= $error ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Statistika
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
$totalUsers = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM purchases WHERE status = 'completed'");
$completedPurchases = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM purchases WHERE status = 'pending'");
$pendingPurchases = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT SUM(price) as total FROM purchases WHERE status = 'completed'");
$totalRevenue = $stmt->fetch()['total'] ?? 0;

$stmt = $pdo->query("SELECT COUNT(*) as total FROM payments WHERE status = 'pending'");
$pendingPayments = $stmt->fetch()['total'];

// Oxirgi xaridlar
$stmt = $pdo->query("SELECT p.*, u.first_name, u.username FROM purchases p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 10");
$recentPurchases = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
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
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #00ff00;
            text-align: center;
        }
        .stat-value {
            font-size: 32px;
            color: #00ff00;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-label {
            color: #a0a0a0;
            font-size: 14px;
        }
        .section {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #00ff00;
            margin-bottom: 20px;
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
        .status-completed { color: #00ff00; }
        .status-pending { color: #ffff00; }
        .status-failed { color: #ff0040; }
        .logout {
            background: #ff0040 !important;
            border-color: #ff0040 !important;
        }
        .logout:hover {
            background: #fff !important;
            color: #ff0040 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Admin Panel</h1>
        
        <div class="nav">
            <a href="index.php">Dashboard</a>
            <a href="settings.php">⚙️ Sozlamalar</a>
            <a href="broadcast.php">📢 Broadcast</a>
            <a href="payments.php">💳 To'lovlar</a>
            <a href="?logout=1" class="logout">🚪 Chiqish</a>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Jami Foydalanuvchilar</div>
                <div class="stat-value"><?= number_format($totalUsers) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Bajarilgan Xaridlar</div>
                <div class="stat-value"><?= number_format($completedPurchases) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Kutilayotgan Xaridlar</div>
                <div class="stat-value"><?= number_format($pendingPurchases) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Jami Daromad</div>
                <div class="stat-value"><?= number_format($totalRevenue, 0, '.', ' ') ?> UZS</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Kutilayotgan To'lovlar</div>
                <div class="stat-value"><?= number_format($pendingPayments) ?></div>
            </div>
        </div>
        
        <div class="section">
            <h2>📜 Oxirgi Xaridlar</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foydalanuvchi</th>
                        <th>Stars</th>
                        <th>Narx</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Sana</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentPurchases as $purchase): ?>
                    <tr>
                        <td><?= $purchase['id'] ?></td>
                        <td><?= htmlspecialchars($purchase['first_name']) ?> (@<?= htmlspecialchars($purchase['username']) ?>)</td>
                        <td><?= number_format($purchase['stars_amount']) ?></td>
                        <td><?= number_format($purchase['price'], 0, '.', ' ') ?> UZS</td>
                        <td>@<?= htmlspecialchars($purchase['username_target']) ?></td>
                        <td class="status-<?= $purchase['status'] ?>"><?= $purchase['status'] ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($purchase['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
