<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập để xóa CV.', [], 401);
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

if ($cvId === false || $cvId < 1) {
    jsonResponse(false, 'cv_id không hợp lệ.', [], 400);
}

$statement = $pdo->prepare(
    'DELETE FROM cvs WHERE id = :cv_id AND user_id = :user_id'
);
$statement->execute([
    'cv_id' => $cvId,
    'user_id' => getCurrentUserId(),
]);

if ($statement->rowCount() === 0) {
    jsonResponse(false, 'Không tìm thấy CV hoặc bạn không có quyền xóa.', [], 404);
}

jsonResponse(true, 'Xóa CV thành công.', ['cv_id' => $cvId]);