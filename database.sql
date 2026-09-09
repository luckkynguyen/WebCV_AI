-- ========================================================
-- WEBCV_AI DATABASE SCHEMA (OPTION A - UPGRADED 4 TABLES)
-- ========================================================

CREATE DATABASE IF NOT EXISTS `cv_app` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `cv_app`;

-- 1. BẢNG USERS (Quản lý tài khoản)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. BẢNG TEMPLATES (Quản lý mẫu CV)
CREATE TABLE IF NOT EXISTS `templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `thumbnail` VARCHAR(255) DEFAULT NULL,
    `description` TEXT,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. BẢNG CVS (Lưu trữ nội dung CV dưới dạng JSON)
CREATE TABLE IF NOT EXISTS `cvs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `template_id` INT DEFAULT 1,
    `title` VARCHAR(255) DEFAULT 'CV Chưa Đặt Tên',
    `data` JSON NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_cvs_users` 
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
        ON DELETE CASCADE,
    CONSTRAINT `fk_cvs_templates` 
        FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) 
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. BẢNG CHAT_SESSIONS (Lưu lịch sử & trạng thái hội thoại Chat AI)
CREATE TABLE IF NOT EXISTS `chat_sessions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `cv_id` INT DEFAULT NULL,
    `current_step` VARCHAR(50) DEFAULT 'personal',
    `messages` JSON NOT NULL,
    `status` ENUM('in_progress', 'completed') DEFAULT 'in_progress',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_chat_users` 
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
        ON DELETE CASCADE,
    CONSTRAINT `fk_chat_cvs` 
        FOREIGN KEY (`cv_id`) REFERENCES `cvs` (`id`) 
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- SEED DATA (Dữ liệu mẫu cho thử nghiệm)
-- ========================================================

-- Dữ liệu mẫu cho bảng templates
INSERT INTO `templates` (`id`, `name`, `thumbnail`, `description`, `is_active`) VALUES
(1, 'Modern', 'css/mau-cv.css', 'Phong cách hiện đại, phù hợp CNTT & Sáng tạo', 1),
(2, 'Professional', 'css/mau-cv.css', 'Phong cách công sở thanh lịch, chuyên nghiệp', 1),
(3, 'Simple', 'css/mau-cv.css', 'Tối giản, tập trung vào nội dung dễ đọc', 1)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Mật khẩu tài khoản mẫu testuser@gmail.com: 123456 (bcrypt hash)
INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'testuser@gmail.com', '$2y$10$wT8v1R5j.yM7N1U8pGZJ3.5n9S8E0v3Q8K8v8W8X8Y8Z8A8B8C8D8')
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Dữ liệu mẫu cho CV đầu tiên
INSERT INTO `cvs` (`id`, `user_id`, `template_id`, `title`, `data`) VALUES
(1, 1, 1, 'CV Nguyễn Văn A - Lập trình viên PHP', '{
  "personal": {
    "full_name": "Nguyễn Văn A",
    "email": "testuser@gmail.com",
    "phone": "0987654321",
    "address": "Cần Thơ"
  },
  "objective": "Mong muốn trở thành Lập trình viên Backend PHP chuyên nghiệp.",
  "education": [
    {
      "school": "Đại học Cần Thơ",
      "major": "Công nghệ thông tin",
      "period": "2022 - 2026",
      "gpa": "3.5/4.0"
    }
  ],
  "skills": ["PHP", "MySQL", "JavaScript", "HTML/CSS", "Git"],
  "experience": [],
  "projects": [
    {
      "name": "WebCV_AI",
      "description": "Hệ thống tạo CV thông minh tích hợp AI.",
      "tech_stack": ["PHP", "MySQL", "Gemini API"]
    }
  ],
  "certificates": []
}')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);
