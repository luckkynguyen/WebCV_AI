<?php

function jsonResponse(bool $success, string $message, array $data = [], int $statusCode = 200): never
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function validateCvData(mixed $data): array
{
    if (!is_array($data)) {
        return ['Dữ liệu CV phải là một object JSON.'];
    }

    $errors = [];
    $personal = $data['personal'] ?? null;

    if (!is_array($personal)) {
        $errors[] = 'Trường personal là bắt buộc và phải là object.';
    } else {
        $fullName = trim((string) ($personal['full_name'] ?? ''));
        $email = trim((string) ($personal['email'] ?? ''));

        if ($fullName === '') {
            $errors[] = 'Họ tên là bắt buộc.';
        } elseif (mb_strlen($fullName) > 255) {
            $errors[] = 'Họ tên không được vượt quá 255 ký tự.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ.';
        }
    }

    $arrayFields = ['education', 'skills', 'experience', 'projects', 'certificates'];
    foreach ($arrayFields as $field) {
        if (!isset($data[$field]) || !is_array($data[$field])) {
            $errors[] = "Trường {$field} phải là một mảng.";
        }
    }

    if (isset($data['skills']) && is_array($data['skills'])) {
        $skills = array_values(array_filter(array_map(
            static fn ($skill): string => trim((string) $skill),
            $data['skills']
        )));

        if ($skills === []) {
            $errors[] = 'Cần có ít nhất một kỹ năng.';
        }
    }

    $objectArrayFields = [
        'education' => ['school', 'major', 'period', 'gpa'],
        'experience' => ['company', 'position', 'period', 'description'],
        'projects' => ['name', 'description', 'tech_stack'],
        'certificates' => ['name', 'organization', 'year'],
    ];

    foreach ($objectArrayFields as $field => $allowedFields) {
        if (!isset($data[$field]) || !is_array($data[$field])) {
            continue;
        }

        foreach ($data[$field] as $index => $item) {
            if (!is_array($item)) {
                $errors[] = "Phần tử {$field}[{$index}] phải là object.";
                continue;
            }

            if ($field === 'projects' && isset($item['tech_stack']) && !is_array($item['tech_stack'])) {
                $errors[] = "Trường {$field}[{$index}].tech_stack phải là một mảng.";
            }

            foreach ($allowedFields as $allowedField) {
                if (isset($item[$allowedField]) && !is_scalar($item[$allowedField]) && $allowedField !== 'tech_stack') {
                    $errors[] = "Trường {$field}[{$index}].{$allowedField} phải là chuỗi.";
                }
            }
        }
    }

    if (isset($data['objective']) && !is_scalar($data['objective'])) {
        $errors[] = 'Trường objective phải là chuỗi.';
    }

    return $errors;
}

function geminiGenerateContent(string $prompt): string
{
    if (GEMINI_API_KEY === '' || GEMINI_API_KEY === 'YOUR_GEMINI_API_KEY_HERE') {
        throw new RuntimeException('Gemini API key chưa được cấu hình.');
    }

    $separator = str_contains(GEMINI_API_URL, '?') ? '&' : '?';
    $url = GEMINI_API_URL . $separator . 'key=' . rawurlencode(GEMINI_API_KEY);
    $payload = json_encode([
        'contents' => [
            [
                'role' => 'user',
                'parts' => [['text' => $prompt]],
            ],
        ],
        'generationConfig' => [
            'temperature' => 0.2,
            'responseMimeType' => 'application/json',
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

    $curl = curl_init($url);
    if ($curl === false) {
        throw new RuntimeException('Không thể khởi tạo kết nối Gemini.');
    }

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $statusCode = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl);

    if ($response === false || $curlError !== '') {
        throw new RuntimeException('Không thể kết nối Gemini.');
    }

    if ($statusCode < 200 || $statusCode >= 300) {
        throw new RuntimeException('Gemini trả về lỗi HTTP ' . $statusCode . '.');
    }

    try {
        $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        throw new RuntimeException('Gemini trả về response không hợp lệ.');
    }

    $text = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;
    if (!is_string($text) || trim($text) === '') {
        throw new RuntimeException('Gemini không trả về nội dung.');
    }

    return $text;
}

function decodeGeminiJson(string $text): array
{
    $text = trim($text);
    if (str_starts_with($text, '```')) {
        $text = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', $text) ?? $text;
    }

    try {
        $decoded = json_decode(trim($text), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        throw new RuntimeException('Gemini không trả về JSON đúng định dạng.');
    }

    if (!is_array($decoded)) {
        throw new RuntimeException('Gemini JSON phải là object.');
    }

    return $decoded;
}

function createEmptyCvChatState(): array
{
    return [
        'current_step' => 'personal_name',
        'collected_data' => [
            'personal' => [],
            'objective' => '',
            'education' => [],
            'skills' => [],
            'experience' => [],
            'projects' => [],
            'certificates' => [],
        ],
        'messages' => [],
        'is_complete' => false,
    ];
}