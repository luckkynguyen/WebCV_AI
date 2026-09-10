
<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

redirectAuthenticatedUser();

$error = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Vui lòng nhập email hợp lệ và mật khẩu.';
    } else {
        $statement = $pdo->prepare('SELECT id, password FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Email hoặc mật khẩu không chính xác.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_email'] = $email;

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập - CVAI</title>

    <link rel="stylesheet" href="css/auth.css">
</head>

<body>

    <!-- HEADER -->

    <header class="header">

        <a href="home.html" class="logo">
            CV<span>AI</span>
        </a>

        <a href="register.php" class="back-home">
            Chưa có tài khoản? <b>Đăng ký</b>
        </a>

    </header>


    <!-- MÁY BAY -->

    <div class="paper-plane">

        <div class="plane-body"></div>

        <div class="plane-wing"></div>

    </div>


    <!-- LOGIN -->

    <main class="auth-container">

        <!-- BÊN TRÁI -->

        <section class="auth-intro">

            <p class="small-title">
                ✦ CHÀO MỪNG TRỞ LẠI
            </p>

            <h1>
                Tiếp tục hành trình
                <br>

                <span>tạo CV</span>
                của bạn.
            </h1>

            <p class="intro-text">

                Đăng nhập để tiếp tục chỉnh sửa CV,
                nhận hỗ trợ từ AI và tạo ra hồ sơ
                chuyên nghiệp hơn.

            </p>

            <div class="quote">

                <div class="quote-line"></div>

                <p>
                    "Một chiếc CV tốt có thể là
                    bước đầu tiên cho một cơ hội tốt."
                </p>

            </div>

        </section>


        <!-- FORM -->

        <section class="auth-box">

            <div class="form-header">

                <h2>
                    Đăng nhập
                </h2>

                <p>
                    Nhập thông tin tài khoản của bạn
                </p>

            </div>


            <?php if ($error !== null): ?>
                <p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form method="post" action="login.php">

                <!-- EMAIL -->

                <div class="input-group">

                    <label>
                        Email
                    </label>

                    <input
                        name="email"
                        type="email"
                        placeholder="Nhập email của bạn"
                        value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>


                <!-- PASSWORD -->

                <div class="input-group">

                    <div class="label-row">

                        <label>
                            Mật khẩu
                        </label>

                        <a href="login.php">
                            Quên mật khẩu?
                        </a>

                    </div>

                    <input
                        name="password"
                        type="password"
                        placeholder="Nhập mật khẩu"
                        required
                    >

                </div>


                <!-- REMEMBER -->

                <label class="remember">

                    <input type="checkbox">

                    <span>
                        Ghi nhớ đăng nhập
                    </span>

                </label>


                <!-- BUTTON -->

                <button type="submit" class="auth-button">

                    Đăng nhập

                    <span>→</span>

                </button>

            </form>


            <!-- DIVIDER -->

            <div class="divider">

                <span></span>

                <p>
                    hoặc
                </p>

                <span></span>

            </div>


            <!-- REGISTER -->

            <p class="switch-form">

                Chưa có tài khoản?

                <a href="register.php">
                    Đăng ký ngay
                </a>

            </p>

        </section>

    </main>


    <footer>

        <p>
            © 2026 CVAI - Tạo CV thông minh với AI
        </p>

    </footer>

</body>

</html>
```
