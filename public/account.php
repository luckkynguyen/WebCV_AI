<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$userId = getCurrentUserId();
$flash = getFlashMessage();
$errors = [];
$stmt = $pdo->prepare('SELECT full_name,email,created_at FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$userId]);
$user = $stmt->fetch();
if (!$user) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'profile';
    if ($action === 'profile') {
        $name = trim((string)($_POST['full_name'] ?? ''));
        if ($name === '') $errors[] = 'Vui lòng nhập họ và tên.';
        if (!$errors) {
            $stmt = $pdo->prepare('UPDATE users SET full_name = ? WHERE id = ?');
            $stmt->execute([$name, $userId]);
            setFlashMessage('success', 'Đã cập nhật thông tin tài khoản.');
            header('Location: account.php');
            exit;
        }
    } elseif ($action === 'password') {
        $current = (string)($_POST['current_password'] ?? '');
        $new = (string)($_POST['new_password'] ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');
        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $hash = (string)$stmt->fetchColumn();
        if (!password_verify($current, $hash)) $errors[] = 'Mật khẩu hiện tại không đúng.';
        if (strlen($new) < 8) $errors[] = 'Mật khẩu mới phải có ít nhất 8 ký tự.';
        if ($new !== $confirm) $errors[] = 'Mật khẩu xác nhận không khớp.';
        if (!$errors) {
            $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->execute([password_hash($new, PASSWORD_DEFAULT), $userId]);
            setFlashMessage('success', 'Đổi mật khẩu thành công.');
            header('Location: account.php');
            exit;
        }
    }
}
function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tài khoản | CVAI</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/app-consistent.css">
    <link rel="stylesheet" href="css/polish-effects.css?v=2">
    <style>
        .account-wrap {
            max-width: 980px;
            margin: 32px auto;
            padding: 0 24px 48px
        }

        .account-card {
            background: #fff;
            border: 1px solid #e7eaf0;
            border-radius: 18px;
            padding: 26px;
            margin: 18px 0;
            box-shadow: 0 8px 28px rgba(25, 35, 60, .04)
        }

        .account-card h2 {
            margin: 0 0 8px
        }

        .account-card p {
            color: #687386
        }

        .account-form {
            display: grid;
            gap: 15px;
            max-width: 620px
        }

        .account-form label {
            font-size: 13px;
            font-weight: 700
        }

        .account-form input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            margin-top: 7px;
            padding: 12px 14px;
            border: 1px solid #dfe3ec;
            border-radius: 10px;
            outline-color: #635bff
        }

        .account-form button {
            justify-self: start;
            border: 0;
            border-radius: 10px;
            background: #635bff;
            color: white;
            font-weight: 700;
            padding: 12px 18px;
            cursor: pointer
        }

        .account-alert {
            padding: 12px 14px;
            border-radius: 10px;
            background: #fff0f0;
            color: #b42332;
            margin: 12px 0
        }
    </style>

    <link rel="stylesheet" href="css/global-font-fix.css?v=2">
</head>

<body>
    <?php require __DIR__ . '/partials/sidebar.php'; ?>
    <main class="main">
        <header class="topbar">
            <div>
                <p class="welcome-small">CÀI ĐẶT</p>
                <h2>Tài khoản của bạn</h2>
            </div>
        </header>
        <div class="account-wrap">
            <?php if ($flash): ?><div class="notice notice-<?= h($flash['type']) ?>" role="status"><?= h($flash['message']) ?></div><?php endif; ?><?php foreach ($errors as $error): ?><div class="account-alert" role="alert"><?= h($error) ?></div><?php endforeach; ?>
            <section class="account-card">
                <h2>Thông tin cá nhân</h2>
                <p>Quản lý tên hiển thị và thông tin đăng nhập.</p>
                <form class="account-form" method="post"><input type="hidden" name="action" value="profile"><label>Họ và tên<input name="full_name" value="<?= h($_POST['full_name'] ?? $user['full_name']) ?>" maxlength="255" required></label><label>Email<input value="<?= h($user['email']) ?>" disabled></label><label>Ngày tạo tài khoản<input value="<?= h($user['created_at']) ?>" disabled></label><button type="submit">Lưu thông tin</button></form>
            </section>
            <section class="account-card">
                <h2>Đổi mật khẩu</h2>
                <p>Chọn mật khẩu mới có ít nhất 8 ký tự.</p>
                <form class="account-form" method="post"><input type="hidden" name="action" value="password"><label>Mật khẩu hiện tại<input type="password" name="current_password" autocomplete="current-password" required></label><label>Mật khẩu mới<input type="password" name="new_password" minlength="8" autocomplete="new-password" required></label><label>Nhập lại mật khẩu mới<input type="password" name="confirm_password" minlength="8" autocomplete="new-password" required></label><button type="submit">Cập nhật mật khẩu</button></form>
            </section>
        </div>
    </main>
</body>

</html>