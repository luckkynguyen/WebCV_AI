<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập để lưu CV.', [], 401);
}

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Phương thức yêu cầu không hợp lệ.', [], 405);
}

$contentType = strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? ''));
if (strpos($contentType, 'application/json') === false) {
    jsonResponse(false, 'Request phải có Content-Type là application/json.', [], 415);
}

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody ?: '', true);

if (!is_array($payload) || !array_key_exists('data', $payload)) {
    jsonResponse(false, 'Request phải có trường data chứa nội dung CV.', [], 400);
}

$cvData = $payload['data'];
$errors = validateCvData($cvData);
if ($errors !== []) {
    jsonResponse(false, 'Dữ liệu CV không hợp lệ.', ['errors' => $errors], 422);
}

$title = trim((string) ($payload['title'] ?? 'CV Chưa Đặt Tên'));
if ($title === '') {
    $title = 'CV Chưa Đặt Tên';
}
if (mb_strlen($title) > 255) {
    jsonResponse(false, 'Tiêu đề CV không được vượt quá 255 ký tự.', [], 422);
}

$templateId = filter_var($payload['template_id'] ?? 1, FILTER_VALIDATE_INT);
if ($templateId === false || $templateId < 1) {
    jsonResponse(false, 'template_id không hợp lệ.', [], 422);
}

$templateStatement = $pdo->prepare(
    'SELECT id FROM templates WHERE id = :template_id AND is_active = 1 LIMIT 1'
);
$templateStatement->execute(['template_id' => $templateId]);
if (!$templateStatement->fetch()) {
    jsonResponse(false, 'Mẫu CV không tồn tại hoặc đã bị tắt.', [], 422);
}

$statement = $pdo->prepare(
    'INSERT INTO cvs (user_id, template_id, title, data)
     VALUES (:user_id, :template_id, :title, :data)'
);
$statement->execute([
    'user_id' => getCurrentUserId(),
    'template_id' => $templateId,
    'title' => $title,
    'data' => json_encode($cvData, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
]);

jsonResponse(true, 'Lưu CV thành công.', [
    'cv_id' => (int) $pdo->lastInsertId(),
]);