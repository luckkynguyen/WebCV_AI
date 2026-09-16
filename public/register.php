
<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

redirectAuthenticatedUser();

$error = null;
$fullName = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

    if ($fullName === '' || mb_strlen($fullName) > 255) {
        $error = 'Vui lòng nhập họ tên hợp lệ.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Vui lòng nhập email hợp lệ.';
    } elseif (mb_strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif ($password !== $passwordConfirmation) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } else {
        $statement = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);

        if ($statement->fetch()) {
            $error = 'Email này đã được sử dụng.';
        } else {
            $statement = $pdo->prepare(
                'INSERT INTO users (full_name, email, password) VALUES (:full_name, :email, :password)'
            );
            $statement->execute([
                'full_name' => $fullName,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $pdo->lastInsertId();
            $_SESSION['user_email'] = $email;
            $_SESSION['user_full_name'] = $fullName;

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Đăng ký - CVAI</title>

    <link rel="stylesheet" href="css/auth.css">

</head>

<body>


    <!-- HEADER -->

    <header class="header">

        <a href="home.html" class="logo">
            CV<span>AI</span>
        </a>

        <a href="login.php" class="back-home">
            Đã có tài khoản?
            <b>Đăng nhập</b>
        </a>

    </header>


    <!-- MÁY BAY -->

    <div class="paper-plane">

        <div class="plane-body"></div>

        <div class="plane-wing"></div>

    </div>


    <!-- REGISTER -->

    <main class="auth-container register-container">


        <!-- FORM -->

        <section class="auth-box">


            <div class="form-header">

                <p class="small-title">
                    ✦ BẮT ĐẦU NGAY
                </p>

                <h2>
                    Tạo tài khoản
                </h2>

                <p>
                    Bắt đầu tạo CV chuyên nghiệp
                    cùng CVAI
                </p>

            </div>


            <?php if ($error !== null): ?>
                <p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form method="post" action="register.php">


                <!-- HỌ TÊN -->

                <div class="input-group">

                    <label>
                        Họ và tên
                    </label>

                    <input
                        name="full_name"
                        type="text"
                        placeholder="Nhập họ và tên"
                        value="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>


                <!-- EMAIL -->

                <div class="input-group">

                    <label>
                        Email
                    </label>

                    <input
                        name="email"
                        type="email"
                        placeholder="Nhập email"
                        value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>


                <!-- PASSWORD -->

                <div class="input-group">

                    <label>
                        Mật khẩu
                    </label>

                    <input
                        name="password"
                        type="password"
                        placeholder="Tạo mật khẩu"
                        required
                    >

                </div>


                <!-- CONFIRM PASSWORD -->

                <div class="input-group">

                    <label>
                        Xác nhận mật khẩu
                    </label>

                    <input
                        name="password_confirmation"
                        type="password"
                        placeholder="Nhập lại mật khẩu"
                        required
                    >

                </div>


                <!-- ĐIỀU KHOẢN -->

                <label class="remember">

                    <input
                        type="checkbox"
                        required
                    >

                    <span>
                        Tôi đồng ý với điều khoản sử dụng
                    </span>

                </label>


                <!-- BUTTON -->

                <button
                    type="submit"
                    class="auth-button"
                >

                    Tạo tài khoản

                    <span>→</span>

                </button>


            </form>


            <!-- LOGIN -->

            <p class="switch-form">

                Đã có tài khoản?

                <a href="login.php">
                    Đăng nhập
                </a>

            </p>


        </section>


        <!-- BÊN PHẢI -->

        <section class="register-intro">

            <div class="intro-circle">

                <div class="circle-number">
                    01
                </div>

                <h2>
                    Tạo CV
                    <br>
                    <span>theo cách của bạn.</span>
                </h2>

                <p>
                    Điền thông tin.
                    <br>
                    Để AI giúp bạn hoàn thiện.
                </p>

            </div>

        </section>


    </main>


    <footer>

        <p>
            © 2026 CVAI - Tạo CV thông minh với AI
        </p>

    </footer>


</body>

</html>
