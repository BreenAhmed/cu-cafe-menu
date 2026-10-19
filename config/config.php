<?php
/**
 * CU Cafe — Application Configuration
 */

declare(strict_types=1);

define('APP_NAME', 'CU Cafe');
define('APP_NAME_AR', 'سي يو');
define('APP_VERSION', '1.0.0');
define('BASE_PATH', dirname(__DIR__));

$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
$isLocal = $host === 'localhost'
    || $host === '127.0.0.1'
    || str_starts_with($host, 'localhost:')
    || str_starts_with($host, '127.0.0.1:');

if ($isLocal) {
    // XAMPP local
    define('BASE_URL', '/menu_cu');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'watermelon_lab');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // Hostinger production — cumenu.com
    define('BASE_URL', '');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'u100517965_cumenu');
    define('DB_USER', 'u100517965_cumenu');
    define('DB_PASS', 'Brezzo#0751');
}

define('DB_CHARSET', 'utf8mb4');

define('SESSION_NAME', 'wl_session');
define('ADMIN_SESSION_KEY', 'wl_admin_id');
define('LANG_SESSION_KEY', 'wl_lang');
define('DEFAULT_LANG', 'en');

define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');

date_default_timezone_set('Asia/Baghdad');

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}
