
<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$statement = $pdo->prepare('SELECT full_name, email FROM users WHERE id = :user_id LIMIT 1');
$statement->execute(['user_id' => getCurrentUserId()]);
$currentUser = $statement->fetch();

if (!$currentUser) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

$displayName = $currentUser['full_name'] ?: $currentUser['email'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard - CVAI</title>

    <link
        rel="stylesheet"
        href="css/dashboard.css"
    >

</head>


<body>


<!-- =====================================
     SIDEBAR
===================================== -->

<aside class="sidebar">

    <!-- LOGO -->

    <div class="logo">
        ✦ CV<span>AI</span>
    </div>


    <!-- MENU -->

    <nav class="sidebar-menu">

        <p class="menu-title">
            MENU
        </p>


        <a href="#" class="menu-item active">

            <span class="icon">⌂</span>

            Dashboard

        </a>


        <a href="#" class="menu-item">

            <span class="icon">▤</span>

            CV của tôi

        </a>


        <a href="#" class="menu-item">

            <span class="icon">✦</span>

            Tạo CV với AI

        </a>


        <a href="#" class="menu-item">

            <span class="icon">▣</span>

            Mẫu CV

        </a>


        <p class="menu-title account-title">
            TÀI KHOẢN
        </p>


        <a href="#" class="menu-item">

            <span class="icon">♙</span>

            Tài khoản

        </a>


    </nav>


    <!-- BOTTOM -->

    <div class="sidebar-bottom">

        <a href="logout.php" class="menu-item logout">

            <span class="icon">↪</span>

            Đăng xuất

        </a>

    </div>

</aside>



<!-- =====================================
     MAIN
===================================== -->

<main class="main">


    <!-- HEADER -->

    <header class="topbar">


        <div>

            <p class="welcome-small">
                DASHBOARD
            </p>

            <h2>
                Xin chào,
                <span><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></span> 👋
            </h2>

        </div>


        <!-- USER -->

        <div class="user-profile">

            <div class="avatar">
                A
            </div>


            <div class="user-info">

                <strong>
                    <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>
                </strong>

                <span>
                    Sinh viên
                </span>

            </div>


            <button class="dropdown">
                ▼
            </button>

        </div>

    </header>



    <!-- =====================================
         CONTENT
    ===================================== -->

    <section class="content">


        <!-- TITLE -->

        <div class="page-title">

            <div>

                <p class="small-title">
                    ✦ QUẢN LÝ CV
                </p>

                <h1>
                    CV của bạn
                </h1>

                <p>
                    Tạo, chỉnh sửa và quản lý hồ sơ
                    nghề nghiệp của bạn.
                </p>

            </div>


            <button class="create-button">

                + Tạo CV mới

            </button>

        </div>



        <!-- =====================================
             CREATE OPTIONS
        ===================================== -->

        <section class="create-section">


            <h3>
                Tạo CV mới bằng cách
            </h3>


            <div class="create-options">


                <!-- AI -->

                <div class="create-card ai-card">

                    <div class="card-icon">
                        ✦
                    </div>


                    <div class="card-content">

                        <span class="card-label">
                            KHUYẾN NGHỊ
                        </span>

                        <h3>
                            Chat với AI
                        </h3>

                        <p>
                            Trò chuyện với AI để được
                            hướng dẫn và tạo nội dung CV
                            phù hợp với bạn.
                        </p>


                        <a href="#">
                            Bắt đầu với AI
                            <span>→</span>
                        </a>

                    </div>


                    <div class="card-decoration">
                        ✦
                    </div>

                </div>



                <!-- FORM -->

                <div class="create-card form-card">

                    <div class="card-icon">
                        ✎
                    </div>


                    <div class="card-content">

                        <span class="card-label">
                            NHANH CHÓNG
                        </span>

                        <h3>
                            Tạo bằng Form
                        </h3>

                        <p>
                            Nhập trực tiếp thông tin cá nhân,
                            học vấn, kinh nghiệm và kỹ năng.
                        </p>


                        <a href="#">
                            Tạo CV bằng Form
                            <span>→</span>
                        </a>

                    </div>

                </div>


            </div>

        </section>



        <!-- =====================================
             MY CV
        ===================================== -->

        <section class="my-cv">


            <div class="section-heading">

                <div>

                    <h2>
                        CV của bạn
                    </h2>

                    <p>
                        Các CV bạn đã tạo
                    </p>

                </div>


                <a href="#">
                    Xem tất cả →
                </a>

            </div>



            <div class="cv-list">


                <!-- CV 1 -->

                <div class="cv-item">


                    <div class="cv-preview">

                        <div class="mini-cv">

                            <div class="mini-top">

                                <div></div>

                                <span></span>

                            </div>


                            <div class="mini-title"></div>

                            <div class="mini-line"></div>

                            <div class="mini-line short"></div>

                            <div class="mini-title second"></div>

                            <div class="mini-line"></div>

                            <div class="mini-line short"></div>

                        </div>

                    </div>


                    <div class="cv-details">

                        <div class="cv-name-row">

                            <h3>
                                CV Nguyễn Văn A
                            </h3>

                            <span class="status">
                                Đang sử dụng
                            </span>

                        </div>


                        <p class="update">
                            Cập nhật: 09/09/2026
                        </p>


                        <div class="template">

                            <span>
                                Template
                            </span>

                            <strong>
                                Modern
                            </strong>

                        </div>


                        <div class="cv-actions">

                            <button class="view">
                                ◉ Xem
                            </button>

                            <button class="edit">
                                ✎ Sửa
                            </button>

                            <button class="delete">
                                ♲ Xóa
                            </button>

                        </div>

                    </div>

                </div>



                <!-- CV 2 -->

                <div class="cv-item">


                    <div class="cv-preview">

                        <div class="mini-cv">

                            <div class="mini-top">

                                <div></div>

                                <span></span>

                            </div>


                            <div class="mini-title"></div>

                            <div class="mini-line"></div>

                            <div class="mini-line short"></div>

                            <div class="mini-title second"></div>

                            <div class="mini-line"></div>

                            <div class="mini-line short"></div>

                        </div>

                    </div>


                    <div class="cv-details">

                        <div class="cv-name-row">

                            <h3>
                                CV Thực tập sinh Backend
                            </h3>

                        </div>


                        <p class="update">
                            Cập nhật: 05/09/2026
                        </p>


                        <div class="template">

                            <span>
                                Template
                            </span>

                            <strong>
                                Professional
                            </strong>

                        </div>


                        <div class="cv-actions">

                            <button class="view">
                                ◉ Xem
                            </button>

                            <button class="edit">
                                ✎ Sửa
                            </button>

                            <button class="delete">
                                ♲ Xóa
                            </button>

                        </div>

                    </div>

                </div>


            </div>

        </section>



        <!-- =====================================
             AI BANNER
        ===================================== -->

        <section class="ai-banner">


            <div class="ai-plane">
                ✈
            </div>


            <div>

                <span>
                    ✦ AI ASSISTANT
                </span>

                <h2>
                    Không biết viết CV?
                    <br>
                    Để AI giúp bạn.
                </h2>

                <p>
                    Mô tả kinh nghiệm của bạn bằng
                    ngôn ngữ tự nhiên và để AI biến
                    chúng thành nội dung chuyên nghiệp.
                </p>

            </div>


            <a href="#">
                Chat với AI →
            </a>


        </section>


    </section>


</main>



<!-- =====================================
     JAVASCRIPT
===================================== -->

<script>

    /* User dropdown */

    const dropdown =
        document.querySelector(".dropdown");

    const profile =
        document.querySelector(".user-profile");


    dropdown.addEventListener("click", () => {

        profile.classList.toggle("open");

    });


    /* Hover CV */

    const cvItems =
        document.querySelectorAll(".cv-item");


    cvItems.forEach(item => {

        item.addEventListener("mouseenter", () => {

            item.classList.add("hover");

        });


        item.addEventListener("mouseleave", () => {

            item.classList.remove("hover");

        });

    });

</script>


</body>

</html>
```
