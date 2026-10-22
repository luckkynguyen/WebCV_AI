<?php
require_once __DIR__ . '/../includes/db.php';require_once __DIR__ . '/../includes/auth.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: ../public/dashboard.php');exit;}
requireLogin();$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);if(!$id){setFlashMessage('error','CV không hợp lệ.');header('Location: ../public/dashboard.php#my-cvs');exit;}
$stmt=$pdo->prepare('DELETE FROM cvs WHERE id=? AND user_id=?');$stmt->execute([$id,getCurrentUserId()]);setFlashMessage($stmt->rowCount()?'success':'error',$stmt->rowCount()?'Đã xóa CV.':'Không tìm thấy CV hoặc bạn không có quyền xóa.');header('Location: ../public/dashboard.php#my-cvs');exit;
