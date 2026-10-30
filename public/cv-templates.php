<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$userStmt=$pdo->prepare('SELECT full_name,email FROM users WHERE id=? LIMIT 1');$userStmt->execute([getCurrentUserId()]);$templateUser=$userStmt->fetch();$templateUserName=$templateUser ? ($templateUser['full_name'] ?: $templateUser['email']) : 'Tài khoản';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mẫu CV - CV AI</title>
    <link rel="stylesheet" href="css/cv-templates.css">
    <link rel="stylesheet" href="css/app-consistent.css">
    <link rel="stylesheet" href="css/polish-effects.css?v=2">

    <link rel="stylesheet" href="css/global-font-fix.css?v=2">
</head>

<body>

    <!-- SIDEBAR -->
    <?php require __DIR__ . '/partials/sidebar.php'; ?>


    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">

            <div>
                <h2>MẪU CV</h2>
                <p>Chọn mẫu CV phù hợp với bạn</p>
            </div>

            <div class="user-info"><strong><?= htmlspecialchars($templateUserName, ENT_QUOTES, 'UTF-8') ?></strong><span>Tài khoản của bạn</span></div>

        </header>


        <!-- CONTENT -->
        <section class="content">

            <div class="page-title">
                <div>
                    <h1>Chọn mẫu CV của bạn</h1>
                    <p>
                        Hơn 8 mẫu CV chuyên nghiệp được thiết kế phù hợp
                        với nhiều ngành nghề khác nhau.
                    </p>
                </div>

                <div class="filter">
                    <button class="filter-btn active">Tất cả</button>
                    <button class="filter-btn">Sinh viên</button>
                    <button class="filter-btn">Kinh nghiệm</button>
                    <button class="filter-btn">Sáng tạo</button>
                </div>
            </div>


            <!-- CV GRID -->
            <div class="cv-grid">


                <!-- CV 1 -->
                <div class="cv-card">

                    <div class="cv-preview modern">

                        <div class="cv-left">
                            <div class="cv-photo"></div>

                            <div class="cv-line title"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line short"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line short"></div>
                            <div class="cv-line"></div>
                        </div>

                        <div class="cv-right">
                            <div class="cv-name"></div>
                            <div class="cv-line"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line short"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line"></div>
                        </div>

                    </div>

                    <div class="cv-info">
                        <div>
                            <h3>Modern</h3>
                            <p>Hiện đại · Chuyên nghiệp</p>
                        </div>

                        <button onclick="useTemplate('Modern')">
                            Sử dụng
                        </button>
                    </div>

                </div>


                <!-- CV 2 -->
                <div class="cv-card">

                    <div class="cv-preview professional">

                        <div class="professional-header">
                            <div class="big-name"></div>
                            <div class="small-text"></div>
                        </div>

                        <div class="professional-body">

                            <div class="professional-sidebar">
                                <div class="cv-section"></div>
                                <div class="cv-line"></div>
                                <div class="cv-line short"></div>

                                <div class="cv-section"></div>
                                <div class="cv-line"></div>
                                <div class="cv-line"></div>
                            </div>

                            <div class="professional-content">
                                <div class="cv-section"></div>
                                <div class="cv-line"></div>
                                <div class="cv-line"></div>
                                <div class="cv-line short"></div>

                                <div class="cv-section"></div>
                                <div class="cv-line"></div>
                                <div class="cv-line"></div>
                            </div>

                        </div>

                    </div>

                    <div class="cv-info">
                        <div>
                            <h3>Professional</h3>
                            <p>Thanh lịch · Công sở</p>
                        </div>

                        <button onclick="useTemplate('Professional')">
                            Sử dụng
                        </button>
                    </div>

                </div>


                <!-- CV 3 -->
                <div class="cv-card">

                    <div class="cv-preview simple">

                        <div class="simple-header">
                            <div class="big-name"></div>
                            <div class="small-text"></div>
                        </div>

                        <div class="simple-content">

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line short"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                        </div>

                    </div>

                    <div class="cv-info">
                        <div>
                            <h3>Simple</h3>
                            <p>Tối giản · Dễ đọc</p>
                        </div>

                        <button onclick="useTemplate('Simple')">
                            Sử dụng
                        </button>
                    </div>

                </div>


                <!-- CV 4 -->
                <div class="cv-card">

                    <div class="cv-preview creative">

                        <div class="creative-top">
                            <div class="cv-photo"></div>

                            <div>
                                <div class="big-name"></div>
                                <div class="small-text"></div>
                            </div>
                        </div>

                        <div class="creative-body">

                            <div>
                                <div class="cv-section"></div>
                                <div class="cv-line"></div>
                                <div class="cv-line short"></div>
                            </div>

                            <div>
                                <div class="cv-section"></div>
                                <div class="cv-line"></div>
                                <div class="cv-line"></div>
                            </div>

                        </div>

                    </div>

                    <div class="cv-info">
                        <div>
                            <h3>Creative</h3>
                            <p>Sáng tạo · Thiết kế</p>
                        </div>

                        <button onclick="useTemplate('Creative')">
                            Sử dụng
                        </button>
                    </div>

                </div>


                <!-- CV 5 -->
                <div class="cv-card">

                    <div class="cv-preview student">

                        <div class="student-header">
                            <div class="cv-photo"></div>

                            <div>
                                <div class="big-name"></div>
                                <div class="small-text"></div>
                            </div>
                        </div>

                        <div class="student-body">

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line short"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                        </div>

                    </div>

                    <div class="cv-info">
                        <div>
                            <h3>Student</h3>
                            <p>Phù hợp sinh viên</p>
                        </div>

                        <button onclick="useTemplate('Student')">
                            Sử dụng
                        </button>
                    </div>

                </div>


                <!-- CV 6 -->
                <div class="cv-card">

                    <div class="cv-preview executive">

                        <div class="executive-header">
                            <div class="big-name"></div>
                            <div class="small-text"></div>
                        </div>

                        <div class="executive-body">

                            <div class="cv-section"></div>

                            <div class="cv-line"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line short"></div>

                            <div class="cv-section"></div>

                            <div class="cv-line"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line"></div>

                        </div>

                    </div>

                    <div class="cv-info">
                        <div>
                            <h3>Executive</h3>
                            <p>Cao cấp · Quản lý</p>
                        </div>

                        <button onclick="useTemplate('Executive')">
                            Sử dụng
                        </button>
                    </div>

                </div>


                <!-- CV 7 -->
                <div class="cv-card">

                    <div class="cv-preview elegant">

                        <div class="elegant-header">

                            <div class="big-name"></div>

                            <div class="small-text"></div>

                            <div class="elegant-line"></div>

                        </div>

                        <div class="elegant-body">

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line short"></div>

                            <div class="cv-section"></div>
                            <div class="cv-line"></div>

                        </div>

                    </div>

                    <div class="cv-info">
                        <div>
                            <h3>Elegant</h3>
                            <p>Sang trọng · Thanh lịch</p>
                        </div>

                        <button onclick="useTemplate('Elegant')">
                            Sử dụng
                        </button>
                    </div>

                </div>


                <!-- CV 8 -->
                <div class="cv-card">

                    <div class="cv-preview ats">

                        <div class="ats-header">

                            <div class="big-name"></div>

                            <div class="small-text"></div>

                        </div>

                        <div class="ats-body">

                            <div class="cv-section"></div>

                            <div class="cv-line"></div>
                            <div class="cv-line"></div>
                            <div class="cv-line"></div>

                            <div class="cv-section"></div>

                            <div class="cv-line"></div>
                            <div class="cv-line"></div>

                            <div class="cv-section"></div>

                            <div class="cv-line"></div>
                            <div class="cv-line"></div>

                        </div>

                    </div>

                    <div class="cv-info">

                        <div>
                            <h3>ATS Friendly</h3>
                            <p>Tối ưu hệ thống ATS</p>
                        </div>

                        <button onclick="useTemplate('ATS Friendly')">
                            Sử dụng
                        </button>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <script src="js/cv-templates.js"></script>

</body>
</html>