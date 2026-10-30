<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: dashboard.php#my-cvs'); exit; }
header('Location: create-cv-form.php?id=' . $id); exit;
