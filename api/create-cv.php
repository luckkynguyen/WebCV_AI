<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập để tạo CV.', [], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Phương thức yêu cầu không hợp lệ.', [], 405);
}

$state = $_SESSION['cv_chat'] ?? null;
if (!is_array($state) || empty($state['is_complete'])) {
    jsonResponse(false, 'Bạn chưa xác nhận tạo CV.', [], 422);
}

$cvData = $state['collected_data'] ?? [];
$errors = validateCvData($cvData);
if ($errors !== []) {
    jsonResponse(false, 'Dữ liệu CV từ Chat AI chưa hợp lệ.', ['errors' => $errors], 422);
}

require_once __DIR__ . '/../includes/db.php';

$statement = $pdo->prepare(
    'INSERT INTO cvs (user_id, template_id, title, data)
     VALUES (:user_id, :template_id, :title, :data)'
);
$statement->execute([
    'user_id' => getCurrentUserId(),
    'template_id' => 1,
    'title' => 'CV tạo bằng Chat AI',
    'data' => json_encode($cvData, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
]);

$cvId = (int) $pdo->lastInsertId();
unset($_SESSION['cv_chat']);

jsonResponse(true, 'Tạo CV từ Chat AI thành công.', ['cv_id' => $cvId]);
