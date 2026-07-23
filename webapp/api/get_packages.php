<?php
/**
 * Stars paketlarni olish API
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../bot/config_loader.php';

try {
    $stmt = $pdo->query("SELECT id, stars_amount FROM stars_packages WHERE is_active = 1 ORDER BY sort_order ASC, stars_amount ASC");
    $packages = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'packages' => $packages
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Paketlarni yuklab bo\'lmadi',
        'packages' => []
    ]);
}
