<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
setFlashMessage('info', 'Bạn có thể bắt đầu tạo CV bằng biểu mẫu. Khu vực tạo CV bằng chat AI đang được hoàn thiện.');
header('Location: create-cv-form.php');
exit;
