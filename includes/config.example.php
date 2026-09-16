<?php
/**
 * MẪU CẤU HÌNH HỆ THỐNG & CƠ SỞ DỮ LIỆU (WEBCV_AI)
 * Hướng dẫn: Sao chép file này thành 'config.php' và điền API Key cá nhân.
 */

// Dynamic base path configuration
if (!defined('BASE_DIR')) {
    define('BASE_DIR', dirname(__DIR__));
}

// Cấu hình Cơ sở dữ liệu MySQL (Theo PROJECT_RULES.md)
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'cv_app');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Cấu hình Google Gemini API
define('GEMINI_API_KEY', 'YOUR_GEMINI_API_KEY_HERE');
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent');

// Cấu hình Session & Timezone
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Ho_Chi_Minh');
