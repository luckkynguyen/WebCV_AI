<?php
/**
 * KẾT NỐI CƠ SỞ DỮ LIỆU BẰNG PDO (WEBCV_AI)
 * Sử dụng PDO Prepared Statements chống SQL Injection theo PROJECT_RULES.md
 */

require_once __DIR__ . '/config.php';

function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ];
        
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Lỗi kết nối Cơ sở dữ liệu: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

// Khởi tạo biến $pdo toàn cục sẵn sàng cho các controller/api gọi dùng
$pdo = getDBConnection();
