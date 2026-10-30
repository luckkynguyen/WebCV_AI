<?php
<<<<<<< Updated upstream

require_once __DIR__ . '/../includes/auth.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo CV bằng Form - CVAI</title>
    <style>
        body { max-width: 960px; margin: 0 auto; padding: 24px; font-family: Arial, sans-serif; background: #f6f7fb; color: #20212a; }
        form { background: #fff; padding: 24px; border-radius: 12px; }
        fieldset { margin: 0 0 20px; border: 1px solid #ddd; border-radius: 8px; padding: 16px; }
        label { display: block; margin: 10px 0 4px; font-weight: 600; }
        input, textarea, select { box-sizing: border-box; width: 100%; padding: 9px; border: 1px solid #bbb; border-radius: 6px; }
        textarea { min-height: 90px; resize: vertical; }
        .entry { border: 1px solid #e1e1e1; padding: 12px; margin: 12px 0; border-radius: 8px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 16px; }
        .full { grid-column: 1 / -1; }
        button { border: 0; border-radius: 6px; padding: 10px 16px; cursor: pointer; }
        .add { background: #eef0ff; color: #303d9e; }
        .remove { background: #fff0f0; color: #a22; margin-top: 10px; }
        .submit { background: #303d9e; color: #fff; font-size: 16px; }
        #form-message { margin: 12px 0; font-weight: 600; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } .full { grid-column: auto; } }
    </style>
</head>
<body>
    <p><a href="dashboard.php">← Về dashboard</a></p>
    <h1>Tạo CV bằng Form</h1>
    <p>Thông tin bắt buộc: họ tên, email và ít nhất một kỹ năng.</p>

    <form id="cv-form">
        <fieldset>
            <legend>Thông tin chung</legend>
            <label for="title">Tên CV</label>
            <input id="title" name="title" value="CV của tôi" maxlength="255">

            <label for="template_id">Mẫu CV</label>
            <select id="template_id" name="template_id">
                <option value="1">Modern</option>
                <option value="2">Professional</option>
                <option value="3">Simple</option>
            </select>
        </fieldset>

        <fieldset>
            <legend>Thông tin cá nhân</legend>
            <div class="grid">
                <div>
                    <label for="full_name">Họ và tên *</label>
                    <input id="full_name" required maxlength="255">
                </div>
                <div>
                    <label for="email">Email *</label>
                    <input id="email" type="email" required maxlength="255">
                </div>
                <div>
                    <label for="phone">Số điện thoại</label>
                    <input id="phone" maxlength="50">
                </div>
                <div>
                    <label for="address">Địa chỉ</label>
                    <input id="address" maxlength="255">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Mục tiêu nghề nghiệp</legend>
            <textarea id="objective"></textarea>
        </fieldset>

        <fieldset>
            <legend>Kỹ năng *</legend>
            <div id="skills-list">
                <div class="entry skill-entry"><input class="skill" placeholder="Ví dụ: PHP" required></div>
            </div>
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
        <button type="submit" class="submit">Lưu CV</button>
    </form>
    <script src="js/form.js?v=2"></script>
</body>
</html>
=======
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$cv = null;
$data = [
    'personal'=>['full_name'=>'','job_title'=>'','email'=>'','phone'=>'','address'=>''],
    'objective'=>'','education'=>[],'skills'=>[],'experience'=>[],'projects'=>[],'languages'=>[]
];
$title = 'CV mới';
$templateId = 1;
// Nhận mẫu được chọn từ trang thư viện mẫu CV.
$templateMap = [
    'Modern' => 1, 'Professional' => 2, 'Simple' => 3, 'Creative' => 4,
    'Student' => 5, 'Executive' => 6, 'Elegant' => 7, 'ATS Friendly' => 8,
];
if (!$id && isset($_GET['template'])) {
    $requestedTemplate = trim((string)$_GET['template']);
    if (isset($templateMap[$requestedTemplate])) {
        $templateId = $templateMap[$requestedTemplate];
    }
}

if ($id) {
    $stmt=$pdo->prepare('SELECT * FROM cvs WHERE id=? AND user_id=? LIMIT 1');
    $stmt->execute([$id,getCurrentUserId()]);
    $cv=$stmt->fetch();
    if (!$cv) { http_response_code(404); exit('Không tìm thấy CV hoặc bạn không có quyền truy cập.'); }
    $title=$cv['title']; $templateId=(int)$cv['template_id'];
    $loaded=json_decode($cv['data'],true);
    if (is_array($loaded)) $data=array_replace_recursive($data,$loaded);
}
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $id ? 'Chỉnh sửa CV' : 'Tạo CV' ?> | CVAI</title>
<link rel="stylesheet" href="css/app-consistent.css">
<style>
*{box-sizing:border-box}body{margin:0;background:#f5f6fb;color:#20243a;font-family:Inter,system-ui,sans-serif}.sidebar{position:fixed;inset:0 auto 0 0;width:245px;background:#fff;border-right:1px solid #e5e8f0;padding:26px 18px;display:flex;flex-direction:column;z-index:5}.logo{font-size:24px;font-weight:800;margin-bottom:35px}.logo a{color:inherit;text-decoration:none}.logo span{color:#635bff}.sidebar nav{display:grid;gap:7px}.sidebar nav a,.logout{padding:12px 14px;border-radius:11px;color:#586078;text-decoration:none}.sidebar nav a:hover,.sidebar nav a.active{background:#efefff;color:#635bff}.logout{margin-top:auto}.main{margin-left:245px;padding:30px;min-height:100vh}.top{display:flex;justify-content:space-between;gap:15px;align-items:center;margin-bottom:20px}.top h1{margin:0 0 5px}.muted{color:#687086}.layout{max-width:1000px;margin:auto}.panel{background:#fff;border:1px solid #e5e8f0;border-radius:16px;padding:24px;box-shadow:0 8px 28px #20243a08}.field{margin-bottom:15px}.field label,.dynamic-field label{display:block;font-size:13px;font-weight:700;margin-bottom:6px}.field input,.field textarea,.field select,.dynamic-field input,.dynamic-field textarea{width:100%;border:1px solid #dce0eb;border-radius:9px;padding:11px;font:inherit;background:#fff}.field textarea,.dynamic-field textarea{min-height:90px;resize:vertical}.grid2,.dynamic-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.section-title{display:flex;align-items:center;justify-content:space-between;margin:26px 0 12px;padding-top:18px;border-top:1px solid #edf0f5}.section-title h2{font-size:18px;margin:0}.btn{border:0;border-radius:10px;padding:10px 15px;background:#635bff;color:#fff;text-decoration:none;font-weight:700;cursor:pointer}.btn.secondary{background:#e9eaff;color:#5148d8}.dynamic-item{border:1px solid #e1e5ee;border-radius:12px;padding:15px;margin-bottom:12px;background:#fafbff}.dynamic-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}.remove-item{border:0;background:#ffe9e9;color:#b42332;border-radius:8px;padding:7px 10px;cursor:pointer;font-weight:700}.dynamic-field.full{grid-column:1/-1}.empty-item{border:1px dashed #cbd2df;border-radius:10px;padding:14px;text-align:center;color:#7a8293}.actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap}.status{font-size:13px;color:#687086}.notice{padding:11px;background:#eef0ff;border-radius:9px;color:#5148d8;margin-bottom:15px}@media(max-width:700px){.sidebar{position:static;width:auto;display:block}.sidebar nav{grid-template-columns:1fr 1fr}.logout{display:block}.main{margin:0;padding:15px}.grid2,.dynamic-grid{grid-template-columns:1fr}.dynamic-field.full{grid-column:auto}.top{align-items:flex-start;flex-direction:column}}
</style>
<link rel="stylesheet" href="css/app-consistent.css">
    <link rel="stylesheet" href="css/polish-effects.css?v=2">

    <link rel="stylesheet" href="css/global-font-fix.css?v=2">
</head>
<body>
<?php require __DIR__ . '/partials/sidebar.php'; ?>
<main class="main">
<header class="top"><div><h1><?= $id ? 'Chỉnh sửa CV' : 'Tạo CV mới' ?></h1><div class="muted">Thêm, sửa, xóa học vấn, kỹ năng, kinh nghiệm và dự án ngay trên form.</div></div><a class="btn secondary" href="dashboard.php#my-cvs">← Dashboard</a></header>
<div class="layout"><section class="panel">
<div class="notice">Dữ liệu được đóng gói thành JSON và lưu qua <b>api/save-cv.php</b>. Khi mở lại, form sẽ lấy dữ liệu qua <b>api/load-cv.php</b>.</div>
<form id="cvForm" data-cv-id="<?= $id ? (int)$id : '' ?>">
<input type="hidden" name="template_id" value="<?= (int)$templateId ?>">
<div class="field"><label>Tên CV *</label><input name="title" required value="<?=e($title)?>"></div>
<div class="field"><label>Mẫu CV</label><select name="template_id"><option value="1" <?= $templateId===1?'selected':'' ?>>Modern</option><option value="2" <?= $templateId===2?'selected':'' ?>>Professional</option><option value="3" <?= $templateId===3?'selected':'' ?>>Simple</option><option value="4" <?= $templateId===4?'selected':'' ?>>Creative</option><option value="5" <?= $templateId===5?'selected':'' ?>>Student</option><option value="6" <?= $templateId===6?'selected':'' ?>>Executive</option><option value="7" <?= $templateId===7?'selected':'' ?>>Elegant</option><option value="8" <?= $templateId===8?'selected':'' ?>>ATS Friendly</option></select></div>
<div class="section-title"><h2>Thông tin cá nhân</h2></div>
<div class="grid2"><div class="field"><label>Họ và tên *</label><input name="full_name" required value="<?=e($data['personal']['full_name']??'')?>"></div><div class="field"><label>Vị trí ứng tuyển</label><input name="job_title" value="<?=e($data['personal']['job_title']??'')?>"></div><div class="field"><label>Email</label><input type="email" name="email" value="<?=e($data['personal']['email']??'')?>"></div><div class="field"><label>Số điện thoại</label><input name="phone" value="<?=e($data['personal']['phone']??'')?>"></div></div>
<div class="field"><label>Địa chỉ</label><input name="address" value="<?=e($data['personal']['address']??'')?>"></div>
<div class="field"><label>Mục tiêu nghề nghiệp</label><textarea name="objective"><?=e($data['objective']??'')?></textarea></div>

<div class="section-title"><h2>Học vấn</h2><button type="button" class="btn" data-add="education">+ Thêm học vấn</button></div><div id="educationList"></div>
<div class="section-title"><h2>Kỹ năng</h2><button type="button" class="btn" data-add="skills">+ Thêm kỹ năng</button></div><div id="skillsList"></div>
<div class="section-title"><h2>Kinh nghiệm</h2><button type="button" class="btn" data-add="experience">+ Thêm kinh nghiệm</button></div><div id="experienceList"></div>
<div class="section-title"><h2>Dự án</h2><button type="button" class="btn" data-add="projects">+ Thêm dự án</button></div><div id="projectsList"></div>
<div class="section-title"><h2>Ngôn ngữ</h2></div><div class="field"><label>Nhập các ngôn ngữ, cách nhau bằng dấu phẩy</label><input name="languages" value="<?=e(implode(', ',is_array($data['languages']??null)?$data['languages']:[]))?>" placeholder="Tiếng Việt, Tiếng Anh"></div>
<div class="actions"><button class="btn" type="submit">Lưu CV</button><a class="btn secondary" href="dashboard.php#my-cvs">Hủy</a><span class="status">Sau khi lưu, hệ thống mở trang xem lại CV.</span></div>
</form></section></div></main>
<script>window.initialCVData = <?=json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)?>;</script>
<script src="js/form.js"></script>
</body></html>
>>>>>>> Stashed changes
