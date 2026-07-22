<?php
/**
 * Kanal obunasini tekshirish API
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
$channelUsername = getSetting('channel_username', '@yourchannel');
$channelId = getSetting('channel_id', '-100');

// Kanal sozlanmagan bo'lsa
if (empty($channelId) || $channelId == '-100') {
    echo json_encode([
        'success' => true,
        'is_subscribed' => true,
        'channel_username' => $channelUsername
    ]);
    exit;
}

// Telegram API orqali tekshirish
$isSubscribed = checkChannelSubscription($userId);

// Database ga saqlash
if ($isSubscribed) {
    $stmt = $pdo->prepare("UPDATE users SET is_subscribed = 1 WHERE id = ?");
    $stmt->execute([$userId]);
}

echo json_encode([
    'success' => true,
    'is_subscribed' => $isSubscribed,
    'channel_username' => $channelUsername
]);
