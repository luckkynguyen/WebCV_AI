<?php
<<<<<<< Updated upstream

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
=======
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['success'=>false,'message'=>'Chỉ hỗ trợ POST'], JSON_UNESCAPED_UNICODE); exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) { http_response_code(400); echo json_encode(['success'=>false,'message'=>'Dữ liệu JSON không hợp lệ'], JSON_UNESCAPED_UNICODE); exit; }

$title = trim((string)($input['title'] ?? 'CV mới'));
$templateId = (int)($input['template_id'] ?? 1);
$id = isset($input['id']) && $input['id'] !== '' ? (int)$input['id'] : null;
$data = $input['data'] ?? [];

if ($title === '') { http_response_code(422); echo json_encode(['success'=>false,'message'=>'Tên CV không được để trống'], JSON_UNESCAPED_UNICODE); exit; }
if (!is_array($data)) { http_response_code(422); echo json_encode(['success'=>false,'message'=>'data phải là object JSON'], JSON_UNESCAPED_UNICODE); exit; }

// Chuẩn hóa các mảng động.
foreach (['education','skills','experience','projects','languages'] as $key) {
    if (!isset($data[$key]) || !is_array($data[$key])) $data[$key] = [];
}
if (!isset($data['personal']) || !is_array($data['personal'])) $data['personal'] = [];

$json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$userId = getCurrentUserId();

try {
    if ($id) {
        $check = $pdo->prepare('SELECT id FROM cvs WHERE id=? AND user_id=? LIMIT 1');
        $check->execute([$id, $userId]);
        if (!$check->fetch()) { http_response_code(404); echo json_encode(['success'=>false,'message'=>'CV không tồn tại hoặc không thuộc tài khoản này'], JSON_UNESCAPED_UNICODE); exit; }
        $stmt = $pdo->prepare('UPDATE cvs SET title=?, template_id=?, data=? WHERE id=? AND user_id=?');
        $stmt->execute([$title, $templateId, $json, $id, $userId]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO cvs (user_id, template_id, title, data) VALUES (?,?,?,?)');
        $stmt->execute([$userId, $templateId, $title, $json]);
        $id = (int)$pdo->lastInsertId();
    }
    echo json_encode(['success'=>true,'message'=>'Đã lưu CV','id'=>$id,'redirect'=>'preview.php?id='.$id], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('save-cv.php: '.$e->getMessage());
    http_response_code(500); echo json_encode(['success'=>false,'message'=>'Không thể lưu CV. Kiểm tra kết nối cơ sở dữ liệu.'], JSON_UNESCAPED_UNICODE);
}
>>>>>>> Stashed changes
