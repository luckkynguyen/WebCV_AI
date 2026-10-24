<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireLogin();

$cvId = filter_input(INPUT_GET, 'cv_id', FILTER_VALIDATE_INT);
if ($cvId === false || $cvId === null || $cvId < 1) {
    http_response_code(400);
    exit('CV không hợp lệ.');
}

$statement = $pdo->prepare(
    'SELECT id, title, template_id, data
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
    http_response_code(404);
    exit('Không tìm thấy CV hoặc bạn không có quyền chỉnh sửa.');
}

try {
    $cv['data'] = json_decode($cv['data'], true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException) {
    http_response_code(500);
    exit('Dữ liệu CV không hợp lệ.');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa CV - CVAI</title>
    <style>
        body { max-width: 960px; margin: 0 auto; padding: 24px; font-family: Arial, sans-serif; background: #f6f7fb; color: #20212a; }
        form { background: #fff; padding: 24px; border-radius: 12px; }
        fieldset { margin: 0 0 20px; border: 1px solid #ddd; border-radius: 8px; padding: 16px; }
        label { display: block; margin: 10px 0 4px; font-weight: 600; }
        input, textarea, select { box-sizing: border-box; width: 100%; padding: 9px; border: 1px solid #bbb; border-radius: 6px; }
        textarea { min-height: 90px; resize: vertical; }
        .entry { border: 1px solid #e1e1e1; padding: 12px; margin: 12px 0; border-radius: 8px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 16px; }
        button { border: 0; border-radius: 6px; padding: 10px 16px; cursor: pointer; }
        .add { background: #eef0ff; color: #303d9e; }
        .remove { background: #fff0f0; color: #a22; margin-top: 10px; }
        .submit { background: #303d9e; color: #fff; font-size: 16px; }
        .back { display: inline-block; margin-bottom: 16px; color: #303d9e; }
        #form-message { margin: 12px 0; font-weight: 600; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <p><a class="back" href="dashboard.php">← Về dashboard</a></p>
    <h1>Chỉnh sửa CV</h1>
    <p>Cập nhật thông tin CV rồi bấm lưu để ghi đè dữ liệu cũ.</p>

    <form id="cv-form">
        <fieldset>
            <legend>Thông tin chung</legend>
            <label for="title">Tên CV</label>
            <input id="title" maxlength="255">
            <label for="template_id">Mẫu CV</label>
            <select id="template_id">
                <option value="1">Modern</option>
                <option value="2">Professional</option>
                <option value="3">Simple</option>
            </select>
        </fieldset>
        <fieldset>
            <legend>Thông tin cá nhân</legend>
            <div class="grid">
                <div><label for="full_name">Họ và tên *</label><input id="full_name" required maxlength="255"></div>
                <div><label for="email">Email *</label><input id="email" type="email" required maxlength="255"></div>
                <div><label for="phone">Số điện thoại</label><input id="phone" maxlength="50"></div>
                <div><label for="address">Địa chỉ</label><input id="address" maxlength="255"></div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Mục tiêu nghề nghiệp</legend>
            <textarea id="objective"></textarea>
        </fieldset>
        <fieldset>
            <legend>Kỹ năng *</legend>
            <div id="skills-list"></div>
            <button type="button" class="add" data-add="skill">+ Thêm kỹ năng</button>
        </fieldset>
        <fieldset>
            <legend>Học vấn</legend>
            <div id="education-list"></div>
            <button type="button" class="add" data-add="education">+ Thêm học vấn</button>
        </fieldset>
        <fieldset>
            <legend>Kinh nghiệm</legend>
            <div id="experience-list"></div>
            <button type="button" class="add" data-add="experience">+ Thêm kinh nghiệm</button>
        </fieldset>
        <fieldset>
            <legend>Dự án</legend>
            <div id="projects-list"></div>
            <button type="button" class="add" data-add="project">+ Thêm dự án</button>
        </fieldset>
        <fieldset>
            <legend>Chứng chỉ</legend>
            <div id="certificates-list"></div>
            <button type="button" class="add" data-add="certificate">+ Thêm chứng chỉ</button>
        </fieldset>
        <p id="form-message" role="alert"></p>
        <button type="submit" class="submit">Lưu thay đổi</button>
    </form>

    <script>
        window.cvFormConfig = {
            endpoint: '../api/update-cv.php',
            successRedirect: 'preview.php?cv_id=:id',
            cvId: <?= (int) $cv['id'] ?>,
            initial: <?= json_encode([
                'title' => $cv['title'],
                'template_id' => (int) $cv['template_id'],
                'data' => $cv['data'],
            ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
        };
    </script>
    <script src="js/form.js?v=3"></script>
</body>
</html>
