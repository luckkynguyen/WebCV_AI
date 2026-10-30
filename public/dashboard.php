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
<<<<<<< Updated upstream

$cvStatement = $pdo->prepare(
    'SELECT cvs.id, cvs.title, cvs.updated_at, templates.name AS template_name
     FROM cvs
     LEFT JOIN templates ON templates.id = cvs.template_id
     WHERE cvs.user_id = :user_id
     ORDER BY cvs.updated_at DESC, cvs.id DESC
     LIMIT 10'
);
$cvStatement->execute(['user_id' => getCurrentUserId()]);
$cvs = $cvStatement->fetchAll();
=======
$cvStmt = $pdo->prepare('SELECT c.id,c.title,c.template_id,c.updated_at,t.name AS template_name FROM cvs c LEFT JOIN templates t ON t.id=c.template_id WHERE c.user_id=? ORDER BY c.updated_at DESC');
$cvStmt->execute([getCurrentUserId()]);
$userCvs = $cvStmt->fetchAll();
$flash = getFlashMessage();

>>>>>>> Stashed changes
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
    <link rel="stylesheet" href="css/polish-effects.css?v=2">

    <link rel="stylesheet" href="css/global-font-fix.css?v=2">
</head>


<body>


<!-- =====================================
     SIDEBAR
===================================== -->

<?php require __DIR__ . '/partials/sidebar.php'; ?>



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


<<<<<<< Updated upstream
            <a class="create-button" href="create-cv-form.php">
=======
            <!-- NÚT TẠO CV -->

            <a
                href="create-cv-form.php"
                class="create-button"
            >
>>>>>>> Stashed changes
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


<<<<<<< Updated upstream
                        <a href="create-cv-form.php">
=======
                        <!-- ĐÃ SỬA LINK -->

                        <a href="create-cv-form.php">

>>>>>>> Stashed changes
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


<<<<<<< Updated upstream
                <span><?= count($cvs) ?> CV</span>
=======
                <a href="dashboard.php#my-cvs">
                    Xem tất cả →
                </a>
>>>>>>> Stashed changes

            </div>



<<<<<<< Updated upstream
            <div class="cv-list">
                <?php if ($cvs === []): ?>
                    <p>Chưa có CV nào. Hãy tạo CV đầu tiên bằng form.</p>
                <?php else: ?>
                    <?php foreach ($cvs as $cv): ?>
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
                                    <h3><?= htmlspecialchars($cv['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                </div>

                                <p class="update">
                                    Cập nhật: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($cv['updated_at'])), ENT_QUOTES, 'UTF-8') ?>
                                </p>

                                <div class="template">
                                    <span>Template</span>
                                    <strong><?= htmlspecialchars($cv['template_name'] ?: 'Không xác định', ENT_QUOTES, 'UTF-8') ?></strong>
                                </div>

                                <div class="cv-actions">
                                    <a class="view" href="preview.php?cv_id=<?= (int) $cv['id'] ?>">◉ Xem CV</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
=======
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
>>>>>>> Stashed changes
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