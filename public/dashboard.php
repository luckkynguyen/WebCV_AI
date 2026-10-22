<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$statement = $pdo->prepare(
    'SELECT full_name, email 
     FROM users 
     WHERE id = :user_id 
     LIMIT 1'
);

$statement->execute([
    'user_id' => getCurrentUserId()
]);

$currentUser = $statement->fetch();

if (!$currentUser) {

    $_SESSION = [];

    session_destroy();

    header('Location: login.php');

    exit;
}

$displayName = $currentUser['full_name'] ?: $currentUser['email'];
$cvStmt = $pdo->prepare('SELECT c.id,c.title,c.template_id,c.updated_at,t.name AS template_name FROM cvs c LEFT JOIN templates t ON t.id=c.template_id WHERE c.user_id=? ORDER BY c.updated_at DESC');
$cvStmt->execute([getCurrentUserId()]);
$userCvs = $cvStmt->fetchAll();
$flash = getFlashMessage();

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

    <link rel="stylesheet" href="css/app-consistent.css">
</head>


<body>


<!-- =====================================
     SIDEBAR
===================================== -->

<aside class="sidebar">

    <!-- LOGO -->

    <div class="logo">
        <a href="./home.html">✦ CV<span>AI</span></a>
        
    </div>


    <!-- MENU -->

    <nav class="sidebar-menu">

        <p class="menu-title">
            MENU
        </p>


        <!-- Dashboard -->

        <a
            href="dashboard.php"
            class="menu-item active"
        >

            <span class="icon">⌂</span>

            Dashboard

        </a>


        <!-- CV của tôi -->

        <a
            href="dashboard.php#my-cvs"
            class="menu-item"
        >

            <span class="icon">▤</span>

            CV của tôi

        </a>


        <!-- Tạo CV AI -->

        <a
            href="create-cv-chat.php"
            class="menu-item"
        >

            <span class="icon">✦</span>

            Tạo CV với AI

        </a>


        <!-- Mẫu CV -->

        <a
            href="cv-templates.php"
            class="menu-item"
        >

            <span class="icon">▣</span>

            Mẫu CV

        </a>


        <p class="menu-title account-title">
            TÀI KHOẢN
        </p>


        <!-- Tài khoản -->

        <a
            href="account.php"
            class="menu-item"
        >

            <span class="icon">♙</span>

            Tài khoản

        </a>

    </nav>


    <!-- BOTTOM -->

    <div class="sidebar-bottom">

        <a
            href="logout.php"
            class="menu-item logout"
        >

            <span class="icon">↪</span>

            Đăng xuất

        </a>

    </div>

</aside>



<!-- =====================================
     MAIN
===================================== -->

<main class="main">

    <?php if ($flash !== null): ?>
        <div class="notice notice-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>" role="status">
            <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>


    <!-- HEADER -->

    <header class="topbar">


        <div>

            <p class="welcome-small">
                DASHBOARD
            </p>

            <h2>

                Xin chào,

                <span>
                    <?= htmlspecialchars(
                        $displayName,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

                👋

            </h2>

        </div>



        <!-- USER -->

        <div class="user-profile">


            <div class="avatar">

                <?= htmlspecialchars(
                    strtoupper(
                        mb_substr(
                            $displayName,
                            0,
                            1,
                            'UTF-8'
                        )
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>


            <div class="user-info">

                <strong>

                    <?= htmlspecialchars(
                        $displayName,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </strong>

                <span>
                    Sinh viên
                </span>

            </div>


            <button
                type="button"
                class="dropdown"
            >
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


            <!-- NÚT TẠO CV -->

            <a
                href="create-cv-form.php"
                class="create-button"
            >
                + Tạo CV mới
            </a>

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


                        <a href="create-cv-chat.php">

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


                        <!-- ĐÃ SỬA LINK -->

                        <a href="create-cv-form.php">

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


                <a href="dashboard.php#my-cvs">
                    Xem tất cả →
                </a>

            </div>



            <div class="cv-list" id="my-cvs">
                <?php if (!$userCvs): ?>
                    <div class="cv-item" style="padding:28px;display:block;text-align:center">
                        <h3>Bạn chưa có CV nào</h3>
                        <p class="update">Chọn một mẫu CV hoặc tạo CV mới để bắt đầu.</p>
                        <a class="create-button" href="cv-templates.php">Chọn mẫu CV</a>
                    </div>
                <?php else: foreach ($userCvs as $userCv): ?>
                    <article class="cv-item">
                        <div class="cv-preview"><div class="mini-cv"><div class="mini-top"><div></div><span></span></div><div class="mini-title"></div><div class="mini-line"></div><div class="mini-line short"></div><div class="mini-title second"></div><div class="mini-line"></div><div class="mini-line short"></div></div></div>
                        <div class="cv-details">
                            <div class="cv-name-row"><h3><?= htmlspecialchars($userCv['title'], ENT_QUOTES, 'UTF-8') ?></h3><span class="status">Đã lưu</span></div>
                            <p class="update">Cập nhật: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($userCv['updated_at'])), ENT_QUOTES, 'UTF-8') ?></p>
                            <div class="template"><span>Mẫu</span><strong><?= htmlspecialchars($userCv['template_name'] ?? 'Modern', ENT_QUOTES, 'UTF-8') ?></strong></div>
                            <div class="cv-actions">
                                <a class="view" href="preview.php?id=<?= (int)$userCv['id'] ?>">◉ Xem</a>
                                <a class="edit" href="edit-cv.php?id=<?= (int)$userCv['id'] ?>">✎ Sửa</a>
                                <form method="post" action="../api/delete-cv.php" onsubmit="return confirm('Bạn có chắc muốn xóa CV này?')"><input type="hidden" name="id" value="<?= (int)$userCv['id'] ?>"><button type="submit" class="delete">♲ Xóa</button></form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; endif; ?>
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


            <a href="create-cv-chat.php">

                Chat với AI →

            </a>


        </section>


    </section>

</main>



<!-- =====================================
     JAVASCRIPT
===================================== -->

<script>

    // User dropdown

    const dropdown =
        document.querySelector(".dropdown");

    const profile =
        document.querySelector(".user-profile");


    if (dropdown && profile) {

        dropdown.addEventListener("click", () => {

            profile.classList.toggle("open");

        });

    }


    // Hover CV

    const cvItems =
        document.querySelectorAll(".cv-item");


    cvItems.forEach(item => {

        item.addEventListener(
            "mouseenter",
            () => {

                item.classList.add("hover");

            }
        );


        item.addEventListener(
            "mouseleave",
            () => {

                item.classList.remove("hover");

            }
        );

    });

</script>


</body>

</html>