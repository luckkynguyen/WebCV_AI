<?php

require_once __DIR__ . '/../includes/auth.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo CV với Chat AI - CVAI</title>
    <style>
        body { max-width: 900px; margin: 0 auto; padding: 24px; font-family: Arial, sans-serif; background: #f6f7fb; color: #20212a; }
        .chat { background: #fff; border-radius: 12px; padding: 20px; }
        #messages { min-height: 420px; max-height: 60vh; overflow-y: auto; padding: 8px; background: #f8f9fc; border-radius: 8px; }
        .message { max-width: 80%; margin: 10px 0; padding: 10px 14px; border-radius: 12px; white-space: pre-wrap; line-height: 1.5; }
        .assistant { background: #eef0ff; color: #303d9e; }
        .user { margin-left: auto; background: #303d9e; color: #fff; }
        .composer { display: flex; gap: 8px; margin-top: 14px; }
        .composer input { flex: 1; padding: 12px; border: 1px solid #bbb; border-radius: 8px; }
        button { border: 0; border-radius: 8px; padding: 11px 16px; cursor: pointer; }
        #send-button { background: #303d9e; color: #fff; }
        #create-button { display: none; margin-top: 14px; background: #198754; color: #fff; }
        #status { min-height: 22px; margin: 10px 0; font-weight: 600; }
        .back { display: inline-block; margin-bottom: 16px; color: #303d9e; }
        @media (max-width: 600px) { .composer { flex-direction: column; } .message { max-width: 92%; } }
    </style>
</head>
<body>
    <p><a class="back" href="dashboard.php">← Về dashboard</a></p>
    <h1>Tạo CV với Chat AI</h1>
    <p>AI sẽ hỏi từng bước. Bạn có thể nói “bỏ qua” ở phần không muốn cung cấp.</p>

    <section class="chat">
        <div id="messages" aria-live="polite"></div>
        <p id="status" role="alert"></p>
        <form id="chat-form" class="composer">
            <input id="message-input" maxlength="2000" placeholder="Nhập câu trả lời..." autocomplete="off">
            <button id="send-button" type="submit">Gửi</button>
        </form>
        <button id="create-button" type="button">Tạo CV</button>
    </section>
    <script src="js/chat.js?v=1"></script>
</body>
</html>
