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
