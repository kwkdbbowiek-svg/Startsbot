<?php
/**
 * Foydalanuvchi ma'lumotlarini olish API
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../../bot/config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID kerak']);
    exit;
}

$userId = $input['user_id'];

try {
    $stmt = $pdo->prepare("SELECT id, username, first_name, last_name, balance, is_subscribed, created_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo json_encode([
            'success' => true,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'balance' => floatval($user['balance']),
            'is_subscribed' => (bool)$user['is_subscribed'],
            'created_at' => $user['created_at']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Foydalanuvchi topilmadi'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database xatosi'
    ]);
}
