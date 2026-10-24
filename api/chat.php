<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập để sử dụng Chat AI.', [], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Phương thức yêu cầu không hợp lệ.', [], 405);
}

$payload = json_decode(file_get_contents('php://input') ?: '', true);
$message = trim((string) (is_array($payload) ? ($payload['message'] ?? '') : ''));

if (mb_strlen($message) > 2000) {
    jsonResponse(false, 'Tin nhắn không được vượt quá 2000 ký tự.', [], 422);
}

if (!isset($_SESSION['cv_chat']) || !is_array($_SESSION['cv_chat'])) {
    $_SESSION['cv_chat'] = createEmptyCvChatState();
}

$state = $_SESSION['cv_chat'];
if ($message === '') {
    jsonResponse(true, 'Bắt đầu hội thoại.', [
        'reply' => 'Chào bạn! Trước tiên, bạn hãy cho tôi biết họ và tên của bạn nhé.',
        'current_step' => $state['current_step'],
        'is_complete' => false,
    ]);
}

if ($state['is_complete']) {
    jsonResponse(true, 'Hội thoại đã đủ thông tin.', [
        'reply' => 'Thông tin đã đủ. Bạn có thể bấm “Tạo CV”.',
        'current_step' => 'confirmation',
        'is_complete' => true,
    ]);
}

$steps = [
    'personal_name' => [
        'next' => 'email',
        'instruction' => 'Trích xuất họ tên vào extracted_data.personal.full_name.',
    ],
    'email' => [
        'next' => 'phone',
        'instruction' => 'Trích xuất email vào extracted_data.personal.email.',
    ],
    'phone' => [
        'next' => 'education',
        'instruction' => 'Trích xuất số điện thoại vào extracted_data.personal.phone. Nếu bỏ qua, dùng chuỗi rỗng.',
    ],
    'education' => [
        'next' => 'skills',
        'instruction' => 'Trích xuất học vấn vào extracted_data.education là mảng object có school, major, period, gpa.',
    ],
    'skills' => [
        'next' => 'experience',
        'instruction' => 'Trích xuất kỹ năng vào extracted_data.skills là mảng chuỗi.',
    ],
    'experience' => [
        'next' => 'projects',
        'instruction' => 'Trích xuất kinh nghiệm vào extracted_data.experience là mảng object có company, position, period, description. Nếu bỏ qua dùng mảng rỗng.',
    ],
    'projects' => [
        'next' => 'objective',
        'instruction' => 'Trích xuất dự án vào extracted_data.projects là mảng object có name, description, tech_stack. Nếu bỏ qua dùng mảng rỗng.',
    ],
    'objective' => [
        'next' => 'confirmation',
        'instruction' => 'Trích xuất mục tiêu nghề nghiệp vào extracted_data.objective. Nếu bỏ qua dùng chuỗi rỗng.',
    ],
    'confirmation' => [
        'next' => 'confirmation',
        'instruction' => 'Không cần trích xuất dữ liệu. Xác định người dùng có đồng ý tạo CV hay không.',
    ],
];

$step = $state['current_step'];
$stepConfig = $steps[$step] ?? $steps['personal_name'];
$history = array_slice($state['messages'], -8);
$prompt = 'Bạn là trợ lý thu thập thông tin CV bằng tiếng Việt. Chỉ dùng thông tin người dùng cung cấp, không bịa. '
    . 'Backend đang ở bước "' . $step . '" và bạn không được tự ý đổi bước. '
    . $stepConfig['instruction'] . ' '
    . 'Trả về duy nhất JSON object gồm reply (string), extracted_data (object), accepted (boolean). '
    . 'accepted=true nếu câu trả lời đủ rõ cho bước hiện tại; nếu người dùng nói bỏ qua ở bước tùy chọn thì accepted=true. '
    . 'Lịch sử gần đây: ' . json_encode($history, JSON_UNESCAPED_UNICODE)
    . '. Tin nhắn mới: ' . json_encode($message, JSON_UNESCAPED_UNICODE);

try {
    $aiResult = decodeGeminiJson(geminiGenerateContent($prompt));
} catch (RuntimeException $exception) {
    jsonResponse(false, $exception->getMessage(), [], 502);
}

$reply = trim((string) ($aiResult['reply'] ?? ''));
$extracted = $aiResult['extracted_data'] ?? [];
$accepted = $aiResult['accepted'] ?? false;
if ($reply === '' || !is_array($extracted) || !is_bool($accepted)) {
    jsonResponse(false, 'Gemini trả về dữ liệu hội thoại không hợp lệ.', [], 502);
}

$advance = $accepted;
if ($accepted) {
    if ($step === 'personal_name') {
        $fullName = trim((string) ($extracted['personal']['full_name'] ?? ''));
        $advance = $fullName !== '';
        if ($advance) {
            $state['collected_data']['personal']['full_name'] = $fullName;
        }
    } elseif ($step === 'email') {
        $email = trim((string) ($extracted['personal']['email'] ?? ''));
        $advance = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        if ($advance) {
            $state['collected_data']['personal']['email'] = $email;
        }
    } elseif ($step === 'phone') {
        $state['collected_data']['personal']['phone'] = trim((string) ($extracted['personal']['phone'] ?? ''));
    } elseif ($step === 'education' && isset($extracted['education']) && is_array($extracted['education'])) {
        $state['collected_data']['education'] = $extracted['education'];
    } elseif ($step === 'skills') {
        $skills = isset($extracted['skills']) && is_array($extracted['skills'])
            ? array_values(array_filter(array_map('strval', $extracted['skills'])))
            : [];
        $state['collected_data']['skills'] = $skills;
        $advance = $state['collected_data']['skills'] !== [];
    } elseif ($step === 'experience' && isset($extracted['experience']) && is_array($extracted['experience'])) {
        $state['collected_data']['experience'] = $extracted['experience'];
    } elseif ($step === 'projects' && isset($extracted['projects']) && is_array($extracted['projects'])) {
        $state['collected_data']['projects'] = $extracted['projects'];
    } elseif ($step === 'objective') {
        $state['collected_data']['objective'] = trim((string) ($extracted['objective'] ?? ''));
    } elseif ($step === 'confirmation') {
        $normalizedMessage = mb_strtolower($message);
        if (preg_match('/\b(không|khong|chưa|chua)\b/u', $normalizedMessage)) {
            $advance = false;
        } elseif (preg_match('/\b(có|co|đồng ý|tao|tạo)\b/u', $normalizedMessage)) {
            $state['is_complete'] = true;
        } else {
            $advance = false;
        }
    }

    if ($advance && $step !== 'confirmation') {
        $state['current_step'] = $stepConfig['next'];
    }
}

if (!$advance && $step !== 'confirmation') {
    $reply = 'Tôi chưa nhận đủ thông tin hợp lệ cho bước này. Bạn hãy thử trả lời lại nhé.';
}

$state['messages'][] = ['role' => 'user', 'content' => $message];
$state['messages'][] = ['role' => 'assistant', 'content' => $reply];
$_SESSION['cv_chat'] = $state;

jsonResponse(true, 'Đã xử lý tin nhắn.', [
    'reply' => $reply,
    'current_step' => $state['current_step'],
    'is_complete' => $state['is_complete'],
]);
