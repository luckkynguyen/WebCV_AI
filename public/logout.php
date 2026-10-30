<?php

require_once __DIR__ . '/../includes/config.php';

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $parameters = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $parameters['path'],
        $parameters['domain'],
        $parameters['secure'],
        $parameters['httponly']
    );
}

session_destroy();
session_start();
$_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Bạn đã đăng xuất thành công.'];

header('Location: login.php');
exit;