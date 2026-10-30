<?php
<<<<<<< Updated upstream

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập để tải CV.', [], 401);
}

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse(false, 'Phương thức yêu cầu không hợp lệ.', [], 405);
}

$cvId = filter_input(INPUT_GET, 'cv_id', FILTER_VALIDATE_INT);
if ($cvId === false || $cvId === null || $cvId < 1) {
    jsonResponse(false, 'cv_id không hợp lệ.', [], 400);
}

$statement = $pdo->prepare(
    'SELECT id, template_id, title, data, created_at, updated_at
     FROM cvs
     WHERE id = :cv_id AND user_id = :user_id
     LIMIT 1'
);
$statement->execute([
    'cv_id' => $cvId,
    'user_id' => getCurrentUserId(),
]);
$cv = $statement->fetch();

if (!$cv) {
    jsonResponse(false, 'Không tìm thấy CV hoặc bạn không có quyền truy cập.', [], 404);
}

try {
    $cv['data'] = json_decode($cv['data'], true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException) {
    jsonResponse(false, 'Dữ liệu CV trong cơ sở dữ liệu không hợp lệ.', [], 500);
}

jsonResponse(true, 'Tải CV thành công.', ['cv' => $cv]);
=======
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
header('Content-Type: application/json; charset=utf-8');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); echo json_encode(['success'=>false,'message'=>'Thiếu id CV'], JSON_UNESCAPED_UNICODE); exit; }

$stmt = $pdo->prepare('SELECT c.id,c.title,c.template_id,c.data,c.created_at,c.updated_at,t.name AS template_name FROM cvs c LEFT JOIN templates t ON t.id=c.template_id WHERE c.id=? AND c.user_id=? LIMIT 1');
$stmt->execute([$id, getCurrentUserId()]);
$cv = $stmt->fetch();
if (!$cv) { http_response_code(404); echo json_encode(['success'=>false,'message'=>'Không tìm thấy CV'], JSON_UNESCAPED_UNICODE); exit; }

$cv['data'] = json_decode($cv['data'], true) ?: [];
echo json_encode(['success'=>true,'cv'=>$cv], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
>>>>>>> Stashed changes
