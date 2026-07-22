<?php
/**
 * Stars sotib olish API
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../../bot/config.php';

$input = json_decode(file_get_contents('php://input'), true);

// Validatsiya
if (!isset($input['user_id']) || !isset($input['stars_amount']) || !isset($input['username']) || !isset($input['price'])) {
    echo json_encode(['success' => false, 'message' => 'Barcha maydonlarni to\'ldiring']);
    exit;
}

$userId = $input['user_id'];
$starsAmount = intval($input['stars_amount']);
$username = trim($input['username']);
$price = floatval($input['price']);

// Validatsiya
if ($starsAmount < 50 || $starsAmount > 10000000) {
    echo json_encode(['success' => false, 'message' => 'Stars miqdori noto\'g\'ri']);
    exit;
}

if (empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Username kiriting']);
    exit;
}

// Username dan @ ni olib tashlash
$username = str_replace('@', '', $username);

try {
    // Balansni tekshirish
    $balance = getUserBalance($userId);
    
    if ($balance < $price) {
        echo json_encode(['success' => false, 'message' => 'Balans yetarli emas']);
        exit;
    }
    
    // Transaction boshlash
    $pdo->beginTransaction();
    
    // Balansdan ayirish
    $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?");
    $stmt->execute([$price, $userId, $price]);
    
    if ($stmt->rowCount() == 0) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Balans yangilashda xatolik']);
        exit;
    }
    
    // API usulini aniqlash
    $apiMethod = getSetting('api_method', 'fragment');
    
    // Purchase record yaratish
    $stmt = $pdo->prepare("INSERT INTO purchases (user_id, stars_amount, price, username_target, status, api_method) VALUES (?, ?, ?, ?, 'pending', ?)");
    $stmt->execute([$userId, $starsAmount, $price, $username, $apiMethod]);
    $purchaseId = $pdo->lastInsertId();
    
    // Stars yuborish
    $apiResult = sendStars($username, $starsAmount, $apiMethod);
    
    if ($apiResult['success']) {
        // Muvaffaqiyatli
        $stmt = $pdo->prepare("UPDATE purchases SET status = 'completed', api_response = ? WHERE id = ?");
        $stmt->execute([json_encode($apiResult), $purchaseId]);
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Stars muvaffaqiyatli yuborildi',
            'purchase_id' => $purchaseId
        ]);
    } else {
        // Xatolik - balansni qaytarish
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$price, $userId]);
        
        $stmt = $pdo->prepare("UPDATE purchases SET status = 'failed', api_response = ? WHERE id = ?");
        $stmt->execute([json_encode($apiResult), $purchaseId]);
        
        $pdo->commit();
        
        echo json_encode([
            'success' => false,
            'message' => 'Stars yuborishda xatolik: ' . ($apiResult['message'] ?? 'Noma\'lum xato')
        ]);
    }
    
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    logError("Buy stars error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Database xatosi'
    ]);
}

/**
 * Stars yuborish funksiyasi
 */
function sendStars($username, $amount, $method) {
    if ($method == 'smmupper') {
        return sendStarsSMMUpper($username, $amount);
    } else {
        return sendStarsFragment($username, $amount);
    }
}

/**
 * Fragment API orqali Stars yuborish
 */
function sendStarsFragment($username, $amount) {
    $jwtToken = getSetting('fragment_jwt_token', '');
    
    if (empty($jwtToken)) {
        return ['success' => false, 'message' => 'Fragment JWT token sozlanmagan'];
    }
    
    $url = FRAGMENT_API_URL;
    
    $data = [
        'username' => $username,
        'amount' => $amount
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $jwtToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        logError("Fragment API error: $error");
        return ['success' => false, 'message' => 'API xatosi: ' . $error];
    }
    
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($httpCode == 200 && isset($result['success']) && $result['success']) {
        return ['success' => true, 'response' => $result];
    } else {
        // Agar Fragment ishlamasa, SMM Upper ga o'tish
        $apiMethod = getSetting('api_method');
        if ($apiMethod == 'fragment') {
            logError("Fragment failed, trying SMM Upper");
            return sendStarsSMMUpper($username, $amount);
        }
        
        return ['success' => false, 'message' => $result['message'] ?? 'API xatosi', 'response' => $result];
    }
}

/**
 * SMM Upper API orqali Stars yuborish
 */
function sendStarsSMMUpper($username, $amount) {
    $apiKey = getSetting('smmupper_api_key', '');
    
    if (empty($apiKey)) {
        return ['success' => false, 'message' => 'SMM Upper API key sozlanmagan'];
    }
    
    $url = SMMUPPER_API_URL . '?action=buyStars&username=' . urlencode($username) . '&amount=' . $amount . '&api_key=' . $apiKey;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        logError("SMM Upper API error: $error");
        return ['success' => false, 'message' => 'API xatosi: ' . $error];
    }
    
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if (isset($result['status']) && $result['status'] == 'success') {
        return ['success' => true, 'response' => $result];
    } else {
        return ['success' => false, 'message' => $result['message'] ?? 'API xatosi', 'response' => $result];
    }
}
