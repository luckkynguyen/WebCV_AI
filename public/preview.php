<?php
<<<<<<< Updated upstream

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

requireLogin();

$cvId = filter_input(INPUT_GET, 'cv_id', FILTER_VALIDATE_INT);
if ($cvId === false || $cvId === null || $cvId < 1) {
    http_response_code(400);
    exit('CV không hợp lệ.');
}

$statement = $pdo->prepare(
    'SELECT cvs.id, cvs.title, cvs.template_id, cvs.data, cvs.created_at, cvs.updated_at,
            templates.name AS template_name
     FROM cvs
     LEFT JOIN templates ON templates.id = cvs.template_id
     WHERE cvs.id = :cv_id AND cvs.user_id = :user_id
     LIMIT 1'
);
$statement->execute([
    'cv_id' => $cvId,
    'user_id' => getCurrentUserId(),
]);
$cv = $statement->fetch();

if (!$cv) {
    http_response_code(404);
    exit('Không tìm thấy CV hoặc bạn không có quyền truy cập.');
}

try {
    $data = json_decode($cv['data'], true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException) {
    http_response_code(500);
    exit('Dữ liệu CV không hợp lệ.');
}

function escapePreviewValue(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function renderPreviewValue(mixed $value): string
{
    if (is_scalar($value)) {
        return '<p>' . escapePreviewValue($value) . '</p>';
    }

    if (!is_array($value)) {
        return '';
    }

    $html = '';
    foreach ($value as $item) {
        if (is_scalar($item)) {
            $html .= '<span class="tag">' . escapePreviewValue($item) . '</span>';
            continue;
        }

        if (!is_array($item)) {
            continue;
        }

        $html .= '<div class="item">';
        foreach ($item as $key => $itemValue) {
            if (is_array($itemValue)) {
                if ($itemValue !== []) {
                    $html .= '<p><strong>' . escapePreviewValue(ucfirst(str_replace('_', ' ', (string) $key))) . ':</strong> ';
                    $html .= escapePreviewValue(implode(', ', array_map('strval', $itemValue))) . '</p>';
                }
                continue;
            }

            if (is_scalar($itemValue) && trim((string) $itemValue) !== '') {
                $html .= '<p><strong>' . escapePreviewValue(ucfirst(str_replace('_', ' ', (string) $key))) . ':</strong> ';
                $html .= escapePreviewValue($itemValue) . '</p>';
            }
        }
        $html .= '</div>';
    }

    return $html;
}

$personal = is_array($data['personal'] ?? null) ? $data['personal'] : [];
$sectionLabels = [
    'objective' => 'Mục tiêu nghề nghiệp',
    'education' => 'Học vấn',
    'skills' => 'Kỹ năng',
    'experience' => 'Kinh nghiệm',
    'projects' => 'Dự án',
    'certificates' => 'Chứng chỉ',
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapePreviewValue($cv['title']) ?> - CVAI</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f5efe6; color: #3e352d; font-family: Arial, sans-serif; }
        .toolbar { max-width: 900px; margin: 24px auto 0; display: flex; justify-content: space-between; gap: 12px; }
        .toolbar a, .toolbar button { border: 0; border-radius: 8px; padding: 10px 16px; text-decoration: none; cursor: pointer; font-size: 14px; }
        .back { background: #fff; color: #75695e; }
        .print { background: #3e352d; color: #fff; }
        .cv-sheet { max-width: 900px; min-height: 1000px; margin: 16px auto 32px; padding: 56px 64px; background: #fff; box-shadow: 0 10px 30px rgba(70, 55, 40, 0.12); }
        h1 { margin: 0 0 8px; font-family: Georgia, serif; font-size: 34px; color: #8b6b45; }
        h2 { margin: 30px 0 10px; padding-bottom: 6px; border-bottom: 1px solid #e8dfd5; font-family: Georgia, serif; color: #8b6b45; }
        p { line-height: 1.6; margin: 6px 0; white-space: pre-wrap; }
        .subtitle, .meta { color: #75695e; }
        .contact { margin: 16px 0 24px; padding-bottom: 16px; border-bottom: 1px solid #e8dfd5; }
        .item { margin: 12px 0; padding: 10px 14px; border-left: 3px solid #d7bd9c; }
        .tag { display: inline-block; margin: 4px 6px 4px 0; padding: 6px 10px; border-radius: 14px; background: #f5efe6; }
        @media (max-width: 700px) { .toolbar { margin: 16px; } .cv-sheet { margin: 16px; padding: 32px 24px; min-height: auto; } }
        @media print { body { background: #fff; } .toolbar { display: none; } .cv-sheet { max-width: none; min-height: auto; margin: 0; padding: 0; box-shadow: none; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <a class="back" href="dashboard.php">← Về dashboard</a>
        <button class="print" type="button" onclick="window.print()">In / Lưu PDF</button>
    </div>

    <main class="cv-sheet">
        <h1><?= escapePreviewValue($personal['full_name'] ?? $cv['title']) ?></h1>
        <?php if (!empty($personal['job_title'])): ?>
            <p class="subtitle"><?= escapePreviewValue($personal['job_title']) ?></p>
        <?php endif; ?>

        <div class="contact">
            <?php foreach (['email', 'phone', 'address'] as $contactField): ?>
                <?php if (!empty($personal[$contactField])): ?>
                    <span><?= escapePreviewValue($personal[$contactField]) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <p class="meta">
            <?= escapePreviewValue($cv['title']) ?> ·
            Template: <?= escapePreviewValue($cv['template_name'] ?: 'Không xác định') ?>
        </p>

        <?php foreach ($sectionLabels as $field => $label) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === []) {
                continue;
            }
            ?>
            <section>
                <h2><?= escapePreviewValue($label) ?></h2>
                <?= renderPreviewValue($data[$field]) ?>
            </section>
        <?php } ?>
    </main>
</body>
</html>
=======
require_once __DIR__ . '/../includes/db.php'; require_once __DIR__ . '/../includes/auth.php'; requireLogin();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT); if(!$id){header('Location: dashboard.php#my-cvs');exit;}
$stmt=$pdo->prepare('SELECT c.*,t.name AS template_name FROM cvs c LEFT JOIN templates t ON t.id=c.template_id WHERE c.id=? AND c.user_id=? LIMIT 1');$stmt->execute([$id,getCurrentUserId()]);$cv=$stmt->fetch();if(!$cv){http_response_code(404);exit('Không tìm thấy CV hoặc bạn không có quyền truy cập.');}
$data=json_decode($cv['data'],true)?:[];$personal=$data['personal']??[];$templateId=(int)($cv['template_id']??1);$templateName=$cv['template_name']??'Modern';$layout=strtolower(str_replace(' ','-',$templateName));
function ph($v){return htmlspecialchars(is_scalar($v)?(string)$v:'',ENT_QUOTES,'UTF-8');}
function renderVal($v){if(is_scalar($v)){return '<p>'.ph($v).'</p>';} if(!is_array($v))return ''; $out=''; foreach($v as $item){if(is_scalar($item)){$out.='<span class="chip">'.ph($item).'</span>';continue;}if(is_array($item)){foreach($item as $k=>$val){if(is_scalar($val)&&trim((string)$val)!=='')$out.='<p>'.(count($item)>1?'<strong>'.ph(ucfirst(str_replace('_',' ',$k))).':</strong> ':'').ph($val).'</p>';}}}return $out;}
$labels=['objective'=>'Mục tiêu nghề nghiệp','education'=>'Học vấn','experience'=>'Kinh nghiệm làm việc','skills'=>'Kỹ năng','languages'=>'Ngôn ngữ','projects'=>'Dự án','certificates'=>'Chứng chỉ','awards'=>'Giải thưởng','activities'=>'Hoạt động'];
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=ph($cv['title'])?> | CVAI</title><link rel="stylesheet" href="css/app-consistent.css"><style>*{box-sizing:border-box}body{margin:0;background:#f4f6fb;color:#20243a;font-family:Inter,system-ui,-apple-system,'Segoe UI',sans-serif}.sidebar{position:fixed;inset:0 auto 0 0;width:245px;background:#fff;border-right:1px solid #e5e8f0;padding:26px 18px;display:flex;flex-direction:column}.logo{font-size:24px;font-weight:800;margin-bottom:35px}.logo a{color:inherit;text-decoration:none}.logo span{color:#635bff}.sidebar nav{display:grid;gap:7px}.sidebar nav a,.logout{padding:12px 14px;border-radius:11px;color:#586078;text-decoration:none}.sidebar nav a:hover,.sidebar nav .active{background:#efefff;color:#635bff}.logout{margin-top:auto}.main{margin-left:245px;padding:28px}.top{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}.top h1{margin:0;font-size:24px}.actions{display:flex;gap:9px;flex-wrap:wrap}.btn{border:0;border-radius:10px;padding:11px 15px;background:#635bff;color:white;text-decoration:none;font-weight:700;cursor:pointer}.btn.secondary{background:#e9eaff;color:#5148d8}.sheet{background:#fff;max-width:850px;min-height:900px;margin:24px auto;padding:56px 62px;box-shadow:0 12px 38px #18213a12;border-top:8px solid #635bff}.sheet.professional{border-top:0;border-left:15px solid #203e5d;padding-left:48px}.sheet.simple{border-top:2px solid #222;box-shadow:none}.sheet.creative{border:0;background:linear-gradient(90deg,#29234b 0 28%,#fff 28%);padding-left:34%}.sheet.creative h1{color:#6b43db}.sheet.student{border-top:8px solid #138a72}.sheet.student h1{color:#138a72}.sheet.executive{border-top:10px double #b28b45;font-family:Georgia,serif}.sheet.executive h1{letter-spacing:1.5px;color:#17344e}.sheet.elegant{border-top:0;border-bottom:10px solid #bd8a9c;font-family:Georgia,serif}.sheet.elegant h1{font-weight:500;color:#9a526c}.sheet.ats-friendly{border:0;box-shadow:none;padding:30px}.sheet.ats-friendly h1{font-size:25px}.sheet h1{font-size:32px;margin:0 0 6px}.sheet .job{font-weight:700;color:#635bff;margin:0}.sheet.professional .job{color:#203e5d}.sheet.simple .job{color:#333}.contact{font-size:12px;color:#657086;border-bottom:1px solid #e5e8f0;padding:10px 0 16px}.section{margin-top:25px;break-inside:avoid}.section h2{font-size:15px;letter-spacing:1px;text-transform:uppercase;border-bottom:1px solid #dce1eb;padding-bottom:7px;color:#635bff}.professional .section h2{color:#203e5d}.simple .section h2{color:#333}.section p{line-height:1.65;margin:6px 0;white-space:pre-wrap}.chip{display:inline-block;background:#f0f1ff;color:#4540ad;padding:5px 9px;border-radius:6px;margin:3px;font-size:13px}.notice{padding:12px;border-radius:10px;background:#e8f8ef;color:#17663a;margin:14px auto;max-width:850px}@media(max-width:750px){.sidebar{position:static;width:auto;display:block}.sidebar nav{grid-template-columns:1fr 1fr}.logout{display:block}.main{margin:0;padding:16px}.sheet{padding:28px 22px;min-height:0}}@media print{@page{size:A4;margin:12mm}body{background:white}.sidebar,.top,.notice,.no-print{display:none!important}.main{margin:0;padding:0}.sheet{max-width:none;min-height:0;margin:0;padding:0;box-shadow:none;border:0!important}.section{break-inside:avoid}.chip{border:1px solid #ddd}}
</style><link rel="stylesheet" href="css/app-consistent.css">
    <link rel="stylesheet" href="css/polish-effects.css?v=2">

    <link rel="stylesheet" href="css/global-font-fix.css?v=2">
</head><body><?php require __DIR__ . '/partials/sidebar.php'; ?><main class="main"><header class="top no-print"><div><h1><?=ph($cv['title'])?></h1><p style="color:#687086">Mẫu <?=ph($cv['template_name']??'Modern')?> · Cập nhật <?=ph($cv['updated_at'])?></p></div><div class="actions"><a class="btn" href="edit-cv.php?id=<?=$id?>">✎ Chỉnh sửa</a><a class="btn secondary" href="dashboard.php#my-cvs">CV của tôi</a><button class="btn" onclick="window.print()">Xuất PDF</button></div></header><?php $flash=getFlashMessage();if($flash):?><div class="notice"><?=ph($flash['message'])?></div><?php endif;?><article class="sheet <?=$layout?>"><h1><?=ph($personal['full_name']??$cv['title'])?></h1><p class="job"><?=ph($personal['job_title']??'')?></p><div class="contact"><?=ph($personal['email']??'')?><?=!empty($personal['phone'])?' · '.ph($personal['phone']):''?><?=!empty($personal['address'])?' · '.ph($personal['address']):''?></div><?php foreach($data as $key=>$value):if($key==='personal'||$value===null||$value===''||$value===[])continue;$label=$labels[$key]??ucfirst(str_replace('_',' ',$key));?><section class="section"><h2><?=ph($label)?></h2><?=renderVal($value)?></section><?php endforeach;?></article></main></body></html>
>>>>>>> Stashed changes
