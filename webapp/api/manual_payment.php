<?php
/**
 * Manual to'lov (chek yuklash) API
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../../bot/config.php';

if (!isset($_POST['user_id']) || !isset($_POST['amount']) || !isset($_FILES['receipt'])) {
    echo json_encode(['success' => false, 'message' => 'Barcha maydonlarni to\'ldiring']);
    exit;
}

$userId = $_POST['user_id'];
$amount = floatval($_POST['amount']);

// Validatsiya
if ($amount < 1000) {
    echo json_encode(['success' => false, 'message' => 'Minimal miqdor 1000 UZS']);
    exit;
}

// Fayl yuklash
$file = $_FILES['receipt'];
$uploadDir = __DIR__ . '/../../uploads/receipts/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
$allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    echo json_encode(['success' => false, 'message' => 'Faqat JPG, PNG yoki PDF fayl yuklash mumkin']);
    exit;
}

$fileName = uniqid('receipt_') . '.' . $fileExtension;
$filePath = $uploadDir . $fileName;

if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    echo json_encode(['success' => false, 'message' => 'Fayl yuklashda xatolik']);
    exit;
}

try {
    // To'lov so'rovini database ga saqlash
    $stmt = $pdo->prepare("INSERT INTO payments (user_id, amount, payment_method, receipt_file_id, status) VALUES (?, ?, 'manual', ?, 'pending')");
    $stmt->execute([$userId, $amount, $fileName]);
    
    $paymentId = $pdo->lastInsertId();
    
    // Admin ga xabar yuborish
    notifyAdminAboutPayment($userId, $amount, $paymentId);
    
    echo json_encode([
        'success' => true,
        'message' => 'To\'lov so\'rovi yuborildi',
        'payment_id' => $paymentId
    ]);
    
} catch (PDOException $e) {
    logError("Manual payment error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database xatosi']);
}

/**
 * Admin ga to'lov haqida xabar yuborish
 */
function notifyAdminAboutPayment($userId, $amount, $paymentId) {
    global $pdo;
    
    try {
        // Admin lar ro'yxatini olish
        $stmt = $pdo->query("SELECT user_id FROM admins LIMIT 1");
        $admin = $stmt->fetch();
        
        if ($admin) {
            $adminId = $admin['user_id'];
            
            // Foydalanuvchi ma'lumotlari
            $stmt = $pdo->prepare("SELECT first_name, username FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            $userName = $user['first_name'] ?? 'Foydalanuvchi';
            $username = $user['username'] ?? 'no_username';
            
            $text = "🆕 <b>Yangi to'lov so'rovi</b>\n\n";
            $text .= "👤 Foydalanuvchi: {$userName} (@{$username})\n";
            $text .= "💰 Miqdor: " . number_format($amount, 0, '.', ' ') . " UZS\n";
            $text .= "🆔 To'lov ID: {$paymentId}\n\n";
            $text .= "Admin panel orqali tasdiqlang.";
            
            sendMessage($adminId, $text);
        }
    } catch (Exception $e) {
        logError("Notify admin error: " . $e->getMessage());
    }
}
