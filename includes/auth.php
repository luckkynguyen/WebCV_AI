<?php

require_once __DIR__ . '/config.php';

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT) !== false;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
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