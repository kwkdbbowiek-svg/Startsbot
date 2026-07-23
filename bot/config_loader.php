<?php
/**
 * Config Loader - Railway yoki Local config yuklaydi
 * Bu faylni barcha fayllarda require qiling
 */

// Railway environment check
if (getenv('RAILWAY_ENVIRONMENT') || getenv('RAILWAY_PUBLIC_DOMAIN')) {
    require_once __DIR__ . '/config.railway.php';
} else {
    require_once __DIR__ . '/config.php';
}
