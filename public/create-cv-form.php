<?php

require_once __DIR__ . '/../includes/auth.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo CV bằng Form - CVAI</title>
    <style>
        body { max-width: 960px; margin: 0 auto; padding: 24px; font-family: Arial, sans-serif; background: #f6f7fb; color: #20212a; }
        form { background: #fff; padding: 24px; border-radius: 12px; }
        fieldset { margin: 0 0 20px; border: 1px solid #ddd; border-radius: 8px; padding: 16px; }
        label { display: block; margin: 10px 0 4px; font-weight: 600; }
        input, textarea, select { box-sizing: border-box; width: 100%; padding: 9px; border: 1px solid #bbb; border-radius: 6px; }
        textarea { min-height: 90px; resize: vertical; }
        .entry { border: 1px solid #e1e1e1; padding: 12px; margin: 12px 0; border-radius: 8px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 16px; }
        .full { grid-column: 1 / -1; }
        button { border: 0; border-radius: 6px; padding: 10px 16px; cursor: pointer; }
        .add { background: #eef0ff; color: #303d9e; }
        .remove { background: #fff0f0; color: #a22; margin-top: 10px; }
        .submit { background: #303d9e; color: #fff; font-size: 16px; }
        #form-message { margin: 12px 0; font-weight: 600; }
        @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } .full { grid-column: auto; } }
    </style>
</head>
<body>
    <p><a href="dashboard.php">← Về dashboard</a></p>
    <h1>Tạo CV bằng Form</h1>
    <p>Thông tin bắt buộc: họ tên, email và ít nhất một kỹ năng.</p>

    <form id="cv-form">
        <fieldset>
            <legend>Thông tin chung</legend>
            <label for="title">Tên CV</label>
            <input id="title" name="title" value="CV của tôi" maxlength="255">

            <label for="template_id">Mẫu CV</label>
            <select id="template_id" name="template_id">
                <option value="1">Modern</option>
                <option value="2">Professional</option>
                <option value="3">Simple</option>
            </select>
        </fieldset>

        <fieldset>
            <legend>Thông tin cá nhân</legend>
            <div class="grid">
                <div>
                    <label for="full_name">Họ và tên *</label>
                    <input id="full_name" required maxlength="255">
                </div>
                <div>
                    <label for="email">Email *</label>
                    <input id="email" type="email" required maxlength="255">
                </div>
                <div>
                    <label for="phone">Số điện thoại</label>
                    <input id="phone" maxlength="50">
                </div>
                <div>
                    <label for="address">Địa chỉ</label>
                    <input id="address" maxlength="255">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Mục tiêu nghề nghiệp</legend>
            <textarea id="objective"></textarea>
        </fieldset>

        <fieldset>
            <legend>Kỹ năng *</legend>
            <div id="skills-list">
                <div class="entry skill-entry"><input class="skill" placeholder="Ví dụ: PHP" required></div>
            </div>
            <button type="button" class="add" data-add="skill">+ Thêm kỹ năng</button>
        </fieldset>

        <fieldset>
            <legend>Học vấn</legend>
            <div id="education-list"></div>
            <button type="button" class="add" data-add="education">+ Thêm học vấn</button>
        </fieldset>

        <fieldset>
            <legend>Kinh nghiệm</legend>
            <div id="experience-list"></div>
            <button type="button" class="add" data-add="experience">+ Thêm kinh nghiệm</button>
        </fieldset>

        <fieldset>
            <legend>Dự án</legend>
            <div id="projects-list"></div>
            <button type="button" class="add" data-add="project">+ Thêm dự án</button>
        </fieldset>

        <fieldset>
            <legend>Chứng chỉ</legend>
            <div id="certificates-list"></div>
            <button type="button" class="add" data-add="certificate">+ Thêm chứng chỉ</button>
        </fieldset>

        <p id="form-message" role="alert"></p>
        <button type="submit" class="submit">Lưu CV</button>
    </form>
    <script src="js/form.js?v=3"></script>
</body>
</html>