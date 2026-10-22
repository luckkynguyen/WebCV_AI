<?php

require_once __DIR__ . '/config.php';

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT) !== false;
}

function setFlashMessage(string $type, string $message): void
{
    $_SESSION['flash_message'] = [
        'type' => in_array($type, ['success', 'error', 'info'], true) ? $type : 'info',
        'message' => $message,
    ];
}

function getFlashMessage(): ?array
{
    if (empty($_SESSION['flash_message']) || !is_array($_SESSION['flash_message'])) {
        return null;
    }

    $flash = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    return $flash;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        setFlashMessage('info', 'Vui lòng đăng nhập để truy cập trang này.');
        $requestedPage = basename($_SERVER['PHP_SELF'] ?? 'dashboard.php');
        header('Location: login.php?next=' . rawurlencode($requestedPage));
        exit;
    }
}

function getCurrentUserId(): ?int
{
    if (!isLoggedIn()) {
        return null;
    }

    return (int) $_SESSION['user_id'];
}

function redirectAuthenticatedUser(): void
{
    if (isLoggedIn()) {
        header('Location: dashboard.php');
        exit;
    }
}