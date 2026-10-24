<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập để cập nhật CV.', [], 401);
}

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Phương thức yêu cầu không hợp lệ.', [], 405);
}

$contentType = strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? ''));
if (strpos($contentType, 'application/json') === false) {
    jsonResponse(false, 'Request phải có Content-Type là application/json.', [], 415);
}

$payload = json_decode(file_get_contents('php://input') ?: '', true);
$cvId = filter_var(is_array($payload) ? ($payload['cv_id'] ?? null) : null, FILTER_VALIDATE_INT);

if (!is_array($payload) || $cvId === false || $cvId < 1) {
    jsonResponse(false, 'cv_id không hợp lệ.', [], 400);
}

if (!array_key_exists('data', $payload)) {
    jsonResponse(false, 'Request phải có trường data chứa nội dung CV.', [], 400);
}

$errors = validateCvData($payload['data']);
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
    'UPDATE cvs
     SET template_id = :template_id, title = :title, data = :data
     WHERE id = :cv_id AND user_id = :user_id'
);
$statement->execute([
    'template_id' => $templateId,
    'title' => $title,
    'data' => json_encode($payload['data'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
    'cv_id' => $cvId,
    'user_id' => getCurrentUserId(),
]);

if ($statement->rowCount() === 0) {
    $existsStatement = $pdo->prepare(
        'SELECT id FROM cvs WHERE id = :cv_id AND user_id = :user_id LIMIT 1'
    );
    $existsStatement->execute([
        'cv_id' => $cvId,
        'user_id' => getCurrentUserId(),
    ]);
    if (!$existsStatement->fetch()) {
        jsonResponse(false, 'Không tìm thấy CV hoặc bạn không có quyền cập nhật.', [], 404);
    }
}

jsonResponse(true, 'Cập nhật CV thành công.', ['cv_id' => $cvId]);