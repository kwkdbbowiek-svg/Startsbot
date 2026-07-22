<?php
/**
 * Sozlamalarni olish API
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../../bot/config.php';

try {
    $starsRate = getSetting('stars_rate', '140');
    $manualCardNumber = getSetting('manual_card_number', '');
    $manualCardHolder = getSetting('manual_card_holder', '');
    
    echo json_encode([
        'success' => true,
        'stars_rate' => $starsRate,
        'manual_card_number' => $manualCardNumber,
        'manual_card_holder' => $manualCardHolder
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Sozlamalarni yuklab bo\'lmadi'
    ]);
}
