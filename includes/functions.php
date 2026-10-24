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