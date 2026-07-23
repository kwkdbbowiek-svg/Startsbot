<?php
/**
 * Xaridlar tarixini olish API
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../bot/config_loader.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID kerak']);
    exit;
}

$userId = $input['user_id'];

try {
    $stmt = $pdo->prepare("SELECT id, stars_amount, price, username_target, status, created_at FROM purchases WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
    $stmt->execute([$userId]);
    $purchases = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'purchases' => $purchases
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database xatosi',
        'purchases' => []
    ]);
}
