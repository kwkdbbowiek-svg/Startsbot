<?php
/**
 * Foydalanuvchi balansini olish API
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
$balance = getUserBalance($userId);

echo json_encode([
    'success' => true,
    'balance' => $balance
]);
