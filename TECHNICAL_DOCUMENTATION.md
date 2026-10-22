# TÀI LIỆU KỸ THUẬT DỰ ÁN

## WEB APP TẠO CV BẰNG CHAT AI

> Công nghệ: HTML + CSS + JavaScript + PHP (thuần) + MySQL
> Quy mô: Nhóm 2 người
> Môi trường: Chạy local (XAMPP/Laragon)
> Thời gian: 12 tuần

## Mục lục

- [1. TỔNG QUAN DỰ ÁN](#1-tổng-quan-dự-án)
  - [1.1. Tên dự án](#11-tên-dự-án)
  - [1.2. Mục tiêu](#12-mục-tiêu)
  - [1.3. Nguyên tắc hoạt động của AI](#13-nguyên-tắc-hoạt-động-của-ai)
- [2. PHẠM VI VÀ GIỚI HẠN](#2-phạm-vi-và-giới-hạn)
  - [2.1. Bao gồm](#21-bao-gồm)
  - [2.2. Không bao gồm](#22-không-bao-gồm)
- [3. YÊU CẦU CHỨC NĂNG](#3-yêu-cầu-chức-năng)
- [4. KIẾN TRÚC HỆ THỐNG](#4-kiến-trúc-hệ-thống)
  - [4.1. Mô hình tổng thể](#41-mô-hình-tổng-thể)
  - [4.2. Vai trò các thành phần](#42-vai-trò-các-thành-phần)
  - [4.3. Luồng dữ liệu khi dùng form](#43-luồng-dữ-liệu-khi-dùng-form)
  - [4.4. Luồng dữ liệu khi dùng chat AI](#44-luồng-dữ-liệu-khi-dùng-chat-ai)
- [5. CÔNG NGHỆ SỬ DỤNG](#5-công-nghệ-sử-dụng)
- [6. THIẾT KẾ CƠ SỞ DỮ LIỆU](#6-thiết-kế-cơ-sở-dữ-liệu)
  - [6.1. Bảng users](#61-bảng-users)
  - [6.2. Bảng cvs](#62-bảng-cvs)
  - [6.3. Định dạng JSON CV](#63-định-dạng-json-cv)
- [7. API NỘI BỘ](#7-api-nội-bộ)
- [8. LUỒNG HOẠT ĐỘNG CHI TIẾT](#8-luồng-hoạt-động-chi-tiết)
  - [8.1. Đăng ký / Đăng nhập](#81-đăng-ký-đăng-nhập)
  - [8.2. Tạo CV bằng form](#82-tạo-cv-bằng-form)
  - [8.3. Tạo CV bằng chat AI](#83-tạo-cv-bằng-chat-ai)
  - [8.4. Chỉnh sửa CV](#84-chỉnh-sửa-cv)
  - [8.5. Xóa CV](#85-xóa-cv)
  - [8.6. Xuất PDF](#86-xuất-pdf)
- [9. THIẾT KẾ GIAO DIỆN](#9-thiết-kế-giao-diện)
  - [9.1. Các trang chính](#91-các-trang-chính)
  - [9.2. Yêu cầu template CV](#92-yêu-cầu-template-cv)
- [10. TÍCH HỢP AI CHATBOT](#10-tích-hợp-ai-chatbot)
  - [10.1. Nhà cung cấp AI](#101-nhà-cung-cấp-ai)
  - [10.2. Quản lý trạng thái hội thoại](#102-quản-lý-trạng-thái-hội-thoại)
  - [10.3. Luồng hỏi của AI (cố định)](#103-luồng-hỏi-của-ai-cố-định)
  - [10.4. System Prompt (mẫu)](#104-system-prompt-mẫu)
  - [10.5. Tạo JSON CV từ AI](#105-tạo-json-cv-từ-ai)
- [11. XUẤT PDF](#11-xuất-pdf)
  - [11.1. Thư viện](#111-thư-viện)
  - [11.2. Quy trình](#112-quy-trình)
  - [11.3. Lưu ý](#113-lưu-ý)
- [12. BẢO MẬT](#12-bảo-mật)
- [13. CẤU TRÚC THƯ MỤC CHUẨN HÓA](#13-cấu-trúc-thư-mục-chuẩn-hóa)
- [14. KẾ HOẠCH TRIỂN KHAI 12 TUẦN](#14-kế-hoạch-triển-khai-12-tuần)
- [15. RỦI RO VÀ BIỆN PHÁP KHẮC PHỤC](#15-rủi-ro-và-biện-pháp-khắc-phục)
- [16. HƯỚNG DẪN CÀI ĐẶT VÀ CHẠY LOCAL](#16-hướng-dẫn-cài-đặt-và-chạy-local)
- [17. KẾT LUẬN](#17-kết-luận)

### 1. TỔNG QUAN DỰ ÁN

#### 1.1. Tên dự án

**Web App Tạo CV bằng Chat AI**

#### 1.2. Mục tiêu

Xây dựng một web app chạy hoàn toàn trên local, cho phép người dùng tạo CV nhanh chóng thông qua hai phương thức:

Phương thức 1 – Form truyền thống: Người dùng điền thông tin vào form có cấu trúc.

Phương thức 2 – Chat với AI: Người dùng trò chuyện với AI, cung cấp thông tin bằng ngôn ngữ tự nhiên. AI sẽ thu thập dữ liệu, hỏi những phần còn thiếu, viết lại câu chữ chuyên nghiệp và tạo ra dữ liệu CV có cấu trúc.

Sau khi CV được tạo (dù bằng cách nào), người dùng có thể xem trước, chỉnh sửa, chọn mẫu template và xuất file PDF.

#### 1.3. Nguyên tắc hoạt động của AI

AI không được phép tự bịa thông tin (họ tên, kinh nghiệm, kỹ năng, học vấn...).

AI chỉ sử dụng dữ liệu người dùng cung cấp.

AI có thể viết lại câu chữ cho mạch lạc, chuyên nghiệp hơn, nhưng không thêm bớt sự kiện.

AI không quản lý trạng thái hội thoại; PHP quản lý trạng thái và điều khiển luồng.

### 2. PHẠM VI VÀ GIỚI HẠN

#### 2.1. Bao gồm

Đăng ký, đăng nhập, đăng xuất.

Dashboard quản lý danh sách CV.

Tạo CV bằng form.

Tạo CV bằng chat AI.

Chọn 1 trong 2–3 template cố định.

Xem trước CV (preview).

Chỉnh sửa CV (form giống hệt form nhập liệu ban đầu).

Lưu, cập nhật, xóa CV.

Xuất CV ra PDF.

Chatbot AI thu thập thông tin và tạo CV.

#### 2.2. Không bao gồm

Drag & Drop CV Builder.

Hệ thống tuyển dụng, nhà tuyển dụng.

Đa ngôn ngữ (chỉ tiếng Việt).

AI tự sinh nội dung không có thật.

Quản lý nhiều phiên chat phức tạp, lưu lịch sử chat dài hạn (chỉ lưu tạm trong session).

### 3. YÊU CẦU CHỨC NĂNG

| Chức năng | Mô tả |
| --- | --- |
| Đăng ký | Tạo tài khoản email + mật khẩu. Mật khẩu hash. |
| Đăng nhập | Xác thực, tạo session. |
| Đăng xuất | Hủy session. |
| Dashboard | Hiển thị danh sách CV của người dùng. |
| Tạo CV bằng form | Form nhập thông tin cá nhân, học vấn, kỹ năng, kinh nghiệm, dự án, mục tiêu. |
| Tạo CV bằng chat | Trò chuyện với AI để AI thu thập thông tin và tạo CV. |
| Xem trước CV | Hiển thị CV theo template đã chọn với dữ liệu hiện có. |
| Chỉnh sửa CV | Mở form điền sẵn dữ liệu cũ để chỉnh sửa. |
| Lưu CV | Lưu dữ liệu JSON vào database. |
| Cập nhật CV | Cập nhật dữ liệu CV sau chỉnh sửa. |
| Xóa CV | Xóa CV khỏi database. |
| Xuất PDF | Tải file PDF từ dữ liệu CV và template. |
| Chatbot AI | Hỗ trợ trò chuyện, thu thập thông tin, tạo JSON CV. |

### 4. KIẾN TRÚC HỆ THỐNG

#### 4.1. Mô hình tổng thể

```text
[Trình duyệt]
│
▼
[Apache + PHP]  ← controller chính
│
├──► [MySQL]       lưu users, templates, cvs, chat_sessions
├──► [Gemini API]  gọi bằng cURL để xử lý ngôn ngữ
└──► [mPDF]        xuất PDF từ HTML template
```

#### 4.2. Vai trò các thành phần

PHP: Điều phối mọi request, quản lý session, xác thực, gọi AI, kiểm tra JSON, truy vấn DB, render template, gọi mPDF.

AI (Gemini): Chỉ xử lý ngôn ngữ tự nhiên: hiểu câu trả lời, tạo câu hỏi tiếp theo, viết lại câu chữ, tạo JSON CV. Không lưu trạng thái.

MySQL: Lưu thông tin người dùng, danh sách mẫu CV, dữ liệu CV và lịch sử hội thoại Chat AI trong bảng `chat_sessions`.

HTML/CSS/JS: Giao diện và tương tác phía client.

#### 4.3. Luồng dữ liệu khi dùng form

Người dùng nhập dữ liệu vào form.

JS thu thập, gửi AJAX đến api/save-cv.php.

PHP validate, tạo JSON, lưu vào bảng cvs.

Người dùng xem preview hoặc chỉnh sửa.

#### 4.4. Luồng dữ liệu khi dùng chat AI

Người dùng mở trang chat, AI gửi câu chào.

Người dùng trả lời.

JS gửi tin nhắn đến api/chat.php.

PHP nhận tin nhắn, lấy trạng thái hội thoại từ session, gọi Gemini API kèm lịch sử.

AI trả lời (hỏi tiếp hoặc thông báo đủ thông tin).

PHP cập nhật state, trả phản hồi cho JS hiển thị.

Khi đủ thông tin, người dùng bấm "Tạo CV".

JS gọi api/create-cv.php, PHP gọi AI để tạo JSON CV.

PHP kiểm tra JSON, lưu vào database, trả về ID CV.

Chuyển hướng sang trang preview.

### 5. CÔNG NGHỆ SỬ DỤNG

| Thành phần | Công nghệ | Lý do |
| --- | --- | --- |
| Frontend | HTML5, CSS3, JavaScript (vanilla) | Đã biết, không cần framework. |
| CSS Framework | Bootstrap (tùy chọn, qua CDN) | Tăng tốc làm giao diện. |
| Backend | PHP 8.x thuần | Đã học, chạy local dễ dàng. |
| Database | MySQL | Đã biết, dùng qua XAMPP/Laragon. |
| Kết nối DB | PDO | Chống SQL injection. |
| AI | Google Gemini API (miễn phí) | Có free tier, gọi qua cURL. |
| PDF | mPDF | Chuyển HTML/CSS sang PDF, hỗ trợ font tiếng Việt. |
| Quản lý thư viện | Composer | Cài mPDF. |
| Version control | Git | Quản lý code nhóm. |
| Công cụ test API | Postman | Test endpoint. |

### 6. THIẾT KẾ CƠ SỞ DỮ LIỆU

#### 6.1. Bảng `users` (Quản lý tài khoản)
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 6.2. Bảng `templates` (Quản lý mẫu CV)
```sql
CREATE TABLE templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 6.3. Bảng `cvs` (Lưu dữ liệu CV)
```sql
CREATE TABLE cvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    template_id INT DEFAULT 1,
    title VARCHAR(255) DEFAULT 'CV Chưa Đặt Tên',
    data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### 6.4. Bảng `chat_sessions` (Lưu lịch sử & trạng thái hội thoại Chat AI)
```sql
CREATE TABLE chat_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cv_id INT DEFAULT NULL,
    current_step VARCHAR(50) DEFAULT 'personal',
    messages JSON NOT NULL,
    status ENUM('in_progress', 'completed') DEFAULT 'in_progress',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Ghi chú thiết kế:**
- Cột `data` trong bảng `cvs` lưu toàn bộ dữ liệu CV chi tiết dạng JSON để đảm bảo tính linh hoạt tối đa.
- Bảng `templates` cho phép quản lý mẫu CV động thay vì hardcode trong frontend.
- Bảng `chat_sessions` cho phép lưu vết hội thoại AI, giúp người dùng không bị mất dữ liệu khi lỡ reload trang hoặc bị hết hạn session.

#### 6.5. Định dạng JSON CV

json

{

"personal": {

"full_name": "Nguyễn Văn A",

"email": "abc@gmail.com",

"phone": "0123456789",

"address": "Cần Thơ"

},

"objective": "Sinh viên CNTT định hướng phát triển Web Backend...",

"education": [

{

"school": "Đại học Cần Thơ",

"major": "Công nghệ thông tin",

"period": "2022 - 2026",

"gpa": "3.2/4.0"

}

],

"skills": ["PHP", "MySQL", "JavaScript", "HTML", "CSS"],

"experience": [

{

"company": "Công ty ABC",

"position": "Thực tập sinh",

"period": "6/2024 - 8/2024",

"description": "Phát triển module quản lý..."

}

],

"projects": [

{

"name": "Website bán hàng",

"description": "Phát triển website bán hàng sử dụng PHP và MySQL.",

"tech_stack": ["PHP", "MySQL", "Bootstrap"]

}

],

"certificates": []

}

Lưu ý: Nếu người dùng không có kinh nghiệm hoặc dự án, mảng tương ứng có thể rỗng.

### 7. API NỘI BỘ

Tất cả endpoint nằm trong thư mục api/, nhận và trả JSON (trừ export-pdf.php trả file PDF).

| Endpoint | Method | Chức năng | Tham số |
| --- | --- | --- | --- |
| api/chat.php | POST | Gửi tin nhắn tới AI, nhận câu trả lời | message |
| api/create-cv.php | POST | Yêu cầu AI tạo JSON CV từ dữ liệu đã thu thập | Không cần (dùng session) |
| api/save-cv.php | POST | Lưu CV từ form hoặc AI | data, template_id, title |
| api/load-cv.php | GET | Lấy dữ liệu CV của user | cv_id |
| api/update-cv.php | POST | Cập nhật CV sau chỉnh sửa | cv_id, data, template_id, title |
| api/delete-cv.php | POST | Xóa CV | cv_id |
| api/export-pdf.php | GET | Xuất PDF từ CV | cv_id |
| api/get-templates.php | GET | Lấy danh sách template (tùy chọn) | Không |

**Quy ước response JSON:**

json

{

"success": true,

"message": "...",

"data": { ... }

}

### 8. LUỒNG HOẠT ĐỘNG CHI TIẾT

#### 8.1. Đăng ký / Đăng nhập

Form gửi POST đến file PHP (vd: register.php, login.php).

PHP kiểm tra, hash mật khẩu bằng password_hash().

Khi thành công, khởi tạo $_SESSION['user_id'].

#### 8.2. Tạo CV bằng form

Trang create-cv-form.php hiển thị form gồm các trường: thông tin cá nhân, mục tiêu, học vấn, kỹ năng, kinh nghiệm, dự án, chứng chỉ.

Người dùng có thể thêm nhiều mục học vấn/kỹ năng/kinh nghiệm/dự án bằng JavaScript.

Khi submit, JS gửi AJAX đến api/save-cv.php với dữ liệu JSON.

PHP lưu vào DB và trả về cv_id.

Chuyển hướng sang preview.php?cv_id=<id>.

#### 8.3. Tạo CV bằng chat AI

Trang create-cv-chat.php hiển thị khung chat.

Khi mở trang, JS gọi api/chat.php với tin nhắn đầu tiên (hoặc để trống) để AI gửi câu chào.

AI chào và hỏi thông tin từng bước (xem mục 10).

Mỗi lần người dùng gửi tin nhắn, JS gửi đến api/chat.php.

api/chat.php:

Lấy conversation và collected_data từ session.

Thêm tin nhắn người dùng vào conversation.

Gọi Gemini API với system prompt + conversation.

Nhận phản hồi từ AI (câu hỏi tiếp hoặc thông báo đủ thông tin).

Cập nhật session.

Trả về câu trả lời cho JS.

Khi AI thông báo đã đủ thông tin (hoặc PHP nhận thấy đủ), JS hiển thị nút "Tạo CV".

Người dùng bấm "Tạo CV" → gọi api/create-cv.php.

api/create-cv.php gọi AI lần cuối để tạo JSON CV từ dữ liệu đã thu thập.

PHP kiểm tra JSON, lưu vào DB, trả về cv_id.

Chuyển hướng sang preview.php?cv_id=<id>.

#### 8.4. Chỉnh sửa CV

Trang edit-cv.php nhận cv_id, lấy dữ liệu từ DB qua api/load-cv.php, điền vào form giống hệt form tạo CV.

Người dùng sửa và submit → gọi api/update-cv.php.

#### 8.5. Xóa CV

Từ dashboard, người dùng bấm nút xóa → JS gọi api/delete-cv.php → xóa khỏi DB.

#### 8.6. Xuất PDF

Từ trang preview, người dùng bấm nút "Xuất PDF".

JS gửi request đến api/export-pdf.php?cv_id=<id>.

PHP lấy dữ liệu CV, render HTML từ template tương ứng, dùng mPDF tạo file PDF và force download.

### 9. THIẾT KẾ GIAO DIỆN

#### 9.1. Các trang chính

login.php / register.php: form đăng nhập/đăng ký đơn giản.

dashboard.php: bảng liệt kê CV, nút tạo mới (form hoặc chat), nút xóa/xem/sửa.

create-cv-form.php: form nhập liệu dài.

create-cv-chat.php: khung chat AI.

preview.php: hiển thị CV theo template, có nút chọn template, chỉnh sửa, xuất PDF.

edit-cv.php: form chỉnh sửa.

#### 9.2. Yêu cầu template CV

Template là file PHP chứa HTML/CSS, nhận dữ liệu JSON và hiển thị.

Bố cục nên sử dụng table hoặc float để tương thích với mPDF, tránh flexbox/grid.

Mỗi template có 2 phiên bản: một dùng cho preview (có thể dùng CSS hiện đại), một dùng cho PDF (CSS đơn giản). Hoặc dùng chung một CSS đơn giản để tiết kiệm thời gian.

Ban đầu tạo 2–3 template cơ bản.

### 10. TÍCH HỢP AI CHATBOT

#### 10.1. Nhà cung cấp AI

Sử dụng Google Gemini API (miễn phí, có hạn mức).

Gọi qua cURL trong PHP.

Endpoint: https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=API_KEY

#### 10.2. Quản lý trạng thái hội thoại

Trạng thái hội thoại được lưu trữ bền vững trong bảng **`chat_sessions`** của Database (và đồng bộ tạm trong `$_SESSION['chat_state']` để tối ưu tốc độ).

Cấu trúc lưu trữ dữ liệu phiên chat:

```json
{
  "current_step": "personal",
  "collected_data": {
    "personal": {},
    "education": [],
    "skills": [],
    "experience": [],
    "projects": [],
    "objective": ""
  },
  "conversation": [
    {"role": "assistant", "content": "..."},
    {"role": "user", "content": "..."}
  ]
}
```

PHP cập nhật `current_step` và ghi chú tin nhắn mới vào DB sau mỗi lượt trao đổi, giúp giữ nguyên vết hội thoại kể cả khi người dùng làm mới trang hoặc bị gián đoạn mạng.

PHP cập nhật current_step sau mỗi bước, dựa trên phản hồi AI hoặc logic riêng.

#### 10.3. Luồng hỏi của AI (cố định)

Chào hỏi, yêu cầu họ tên.

Hỏi email.

Hỏi số điện thoại.

Hỏi học vấn (trường, ngành).

Hỏi kỹ năng (ít nhất 1).

Hỏi kinh nghiệm (nếu có).

Hỏi dự án (nếu có).

Hỏi mục tiêu nghề nghiệp (có thể để trống).

Kiểm tra đủ thông tin, thông báo.

Ghi chú: AI không tự ý nhảy bước; PHP quyết định bước tiếp theo dựa trên current_step và dữ liệu đã có. Tuy nhiên, AI có thể được phép linh hoạt hỏi thêm nếu câu trả lời chưa rõ.

#### 10.4. System Prompt (mẫu)

Bạn là trợ lý tạo CV thông minh. Nhiệm vụ của bạn là trò chuyện với người dùng để thu thập thông tin cần thiết cho CV.

Quy tắc:

- Chỉ sử dụng thông tin người dùng cung cấp, không tự bịa thông tin.

- Hỏi từng bước theo trình tự: họ tên, email, số điện thoại, học vấn, kỹ năng, kinh nghiệm, dự án, mục tiêu.

- Sau mỗi câu trả lời, xác nhận và hỏi bước tiếp theo.

- Khi đã có đủ thông tin bắt buộc (họ tên, email, ít nhất một kỹ năng, và ít nhất một trong hai: kinh nghiệm hoặc dự án), thông báo: "Tôi đã có đủ thông tin. Bạn có muốn tôi tạo CV không?".

- Không hỏi quá 3 lần cùng một câu hỏi.

#### 10.5. Tạo JSON CV từ AI

Khi người dùng đồng ý tạo CV, api/create-cv.php gọi AI với prompt yêu cầu trả về JSON:

Dựa trên thông tin đã thu thập, hãy tạo dữ liệu CV dưới dạng JSON theo cấu trúc:

{

"personal": {...},

"objective": "...",

"education": [...],

"skills": [...],

"experience": [...],

"projects": [...],

"certificates": [...]

}

Chỉ trả về JSON, không kèm văn bản khác.

PHP nhận phản hồi, json_decode, kiểm tra các trường bắt buộc.

Nếu lỗi, thử gọi lại một lần hoặc thông báo lỗi cho người dùng.

### 11. XUẤT PDF

#### 11.1. Thư viện

Dùng mPDF (composer require mpdf/mpdf).

Cấu hình font tiếng Việt nếu cần (mặc định đã hỗ trợ DejaVu).

#### 11.2. Quy trình

api/export-pdf.php nhận cv_id.

Lấy dữ liệu CV từ DB.

Load file template tương ứng (templates/templateX.php) và truyền dữ liệu.

Render HTML.

Khởi tạo mPDF: $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

$mpdf->WriteHTML($html);

$mpdf->Output('CV_' . $fullName . '.pdf', 'D');

#### 11.3. Lưu ý

Template xuất PDF nên dùng CSS đơn giản, tránh flex/grid.

Test sớm để phát hiện lỗi font, layout.

### 12. BẢO MẬT

SQL Injection: Luôn dùng PDO prepared statements.

XSS: Dùng htmlspecialchars() khi hiển thị dữ liệu người dùng.

Mật khẩu: Hash bằng password_hash() và kiểm tra bằng password_verify().

API key AI: Không commit lên Git; đặt trong file includes/config.php (đưa vào .gitignore).

Session: Kiểm tra session trước khi cho phép truy cập các trang yêu cầu đăng nhập.

Validate dữ liệu: Kiểm tra required, email hợp lệ, độ dài trước khi lưu.

### 13. CẤU TRÚC THƯ MỤC CHUẨN HÓA

cv-ai-webapp/

```text
├── public/                       # Thư mục gốc web
│   ├── index.php                 # Trang chủ / redirect
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── dashboard.php             # Danh sách CV
│   ├── create-cv-form.php        # Tạo CV bằng form
│   ├── create-cv-chat.php        # Tạo CV bằng chat AI
│   ├── edit-cv.php               # Chỉnh sửa CV
│   ├── preview.php               # Xem trước CV
│   ├── css/
│   │   ├── style.css
│   │   └── chat.css
│   └── js/
│       ├── form.js               # Xử lý form động
│       ├── chat.js               # Xử lý chat
│       └── preview.js            # Xử lý preview, chọn template
├── includes/                     # Các file PHP dùng chung
│   ├── config.php                # Cấu hình DB, API key
│   ├── db.php                    # Kết nối PDO
│   ├── auth.php                  # Kiểm tra đăng nhập
│   ├── functions.php             # Hàm tiện ích
│   └── pdf_generator.php         # Hàm xuất PDF
├── templates/                    # Các template CV
│   ├── template1.php
│   ├── template2.php
│   └── template3.php
├── api/                          # Các endpoint xử lý AJAX
│   ├── chat.php
│   ├── create-cv.php
│   ├── save-cv.php
│   ├── load-cv.php
│   ├── update-cv.php
│   ├── delete-cv.php
│   ├── export-pdf.php
│   └── get-templates.php
├── vendor/                       # Thư viện Composer (mPDF)
├── .gitignore
├── composer.json
└── README.md
```

**Giải thích:**

public/ chứa các file người dùng truy cập trực tiếp.

api/ nhận AJAX từ frontend, trả JSON.

includes/ chứa cấu hình và hàm dùng chung, không truy cập trực tiếp từ trình duyệt (nên đặt ngoài public/ để bảo mật).

templates/ chứa các mẫu CV, chỉ được include bởi preview hoặc export-pdf.

### 14. KẾ HOẠCH TRIỂN KHAI 12 TUẦN

**Tuần 1–2: Khởi động, môi trường, database**

Cài đặt XAMPP/Laragon, Composer.

Tạo cấu trúc thư mục, file config.php, db.php.

Tạo database và bảng.

Xây dựng chức năng đăng ký/đăng nhập/đăng xuất (session).

Deliverable: Đăng nhập/đăng ký hoạt động.

**Tuần 3–4: Form nhập liệu và lưu CV (hướng A)**

Thiết kế form create-cv-form.php với các trường.

Viết JS để thêm/sửa/xóa các mục (học vấn, kỹ năng, kinh nghiệm, dự án).

Tạo api/save-cv.php để lưu dữ liệu JSON.

Tạo api/load-cv.php để load dữ liệu.

Deliverable: Có thể tạo, lưu CV bằng form, xem lại trong dashboard.

**Tuần 5–6: Dashboard, chỉnh sửa, xóa**

Hoàn thiện dashboard liệt kê CV.

Tạo edit-cv.php (form giống hệt tạo CV).

Tạo api/update-cv.php, api/delete-cv.php.

Deliverable: Quản lý CV đầy đủ (CRUD) bằng form.

**Tuần 7–8: Tích hợp chat AI (hướng B)**

Đăng ký Gemini API key (nếu chưa).

Tạo api/chat.php: nhận tin nhắn, gọi AI, cập nhật session.

Tạo create-cv-chat.php giao diện chat.

Viết chat.js xử lý gửi/nhận tin nhắn.

Tạo api/create-cv.php để AI tạo JSON CV.

Deliverable: Người dùng có thể chat với AI để tạo CV (ít nhất là thu thập thông tin cơ bản).

**Tuần 9–10: Preview, template, xuất PDF**

Tạo 2–3 template CV (templates/).

Tạo preview.php hiển thị CV theo template.

Tạo api/export-pdf.php với mPDF.

Deliverable: Xem trước CV, đổi template, tải PDF.

**Tuần 11–12: Kiểm thử, bảo mật, báo cáo**

Test toàn bộ luồng (form + chat).

Rà soát lỗi bảo mật (SQL injection, XSS).

Viết README hướng dẫn cài đặt.

Viết báo cáo kỹ thuật, slide demo.

Deliverable: Sản phẩm hoàn chỉnh, tài liệu, slide.

### 15. RỦI RO VÀ BIỆN PHÁP KHẮC PHỤC

| Rủi ro | Mức độ | Biện pháp |
| --- | --- | --- |
| AI trả JSON sai format | Cao | PHP json_decode kiểm tra, nếu lỗi gọi lại hoặc yêu cầu người dùng dùng form. |
| AI hỏi lặp hoặc quên thông tin | Cao | PHP quản lý state chặt chẽ, lưu current_step, gửi kèm lịch sử ngắn gọn. |
| Quota API miễn phí hết | Trung bình | Thông báo lỗi, fallback sang form. |
| PDF lỗi layout | Cao | Dùng template dạng table, tránh flex/grid, test sớm. |
| Không hiểu code do AI viết | Trung bình | Dành thời gian review, yêu cầu AI giải thích, tự code một số phần. |
| Xung đột code nhóm | Trung bình | Dùng Git, phân nhánh, họp thường xuyên. |
| Chậm tiến độ | Trung bình | Ưu tiên MVP hướng A trước, nếu không kịp thì vẫn có sản phẩm. |

### 16. HƯỚNG DẪN CÀI ĐẶT VÀ CHẠY LOCAL

Cài đặt XAMPP (hoặc Laragon) bao gồm Apache, PHP 8.x, MySQL.

Clone project hoặc tạo cấu trúc thư mục như trên.

Tạo database mới (ví dụ cv_app) và chạy các câu lệnh SQL ở mục 6.

Cấu hình file includes/config.php: khai báo thông tin DB, API key Gemini.

Chạy Composer: composer require mpdf/mpdf.

Đặt document root trỏ vào thư mục public/ (hoặc cấu hình virtual host).

Truy cập http://localhost/cv-ai-webapp/public/ và bắt đầu sử dụng.

Viết README với đầy đủ các bước trên để giảng viên có thể tự chạy.

### 17. KẾT LUẬN

Dự án “Web App Tạo CV bằng Chat AI” là một đồ án khả thi trong khuôn khổ môn học. Với sự kết hợp giữa form truyền thống và chat AI, nhóm có thể linh hoạt trong quá trình phát triển, đảm bảo luôn có sản phẩm hoàn chỉnh ngay cả khi phần AI gặp khó khăn. Kiến trúc PHP controller + AI language brain là điểm nhấn giúp nhóm dễ dàng bảo vệ trước hội đồng.

**PHỤ LỤC (TÙY CHỌN)**

**Gợi ý template CV đơn giản**

html

<!-- templates/template1.php -->

<!DOCTYPE html>

<html>

<head>

<style>

body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }

.container { width: 100%; max-width: 800px; margin: auto; }

.header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; }

.section { margin-top: 20px; }

.section h2 { background: #f0f0f0; padding: 5px; }

table { width: 100%; border-collapse: collapse; }

td, th { border: 1px solid #ddd; padding: 8px; }

</style>

</head>

<body>

<div class="container">

<div class="header">

<h1><?= htmlspecialchars($data['personal']['full_name']) ?></h1>

<p>Email: <?= htmlspecialchars($data['personal']['email']) ?></p>

<p>Phone: <?= htmlspecialchars($data['personal']['phone']) ?></p>

</div>

<div class="section">

<h2>Mục tiêu nghề nghiệp</h2>

<p><?= nl2br(htmlspecialchars($data['objective'])) ?></p>

</div>

<div class="section">

<h2>Học vấn</h2>

<?php foreach ($data['education'] as $edu): ?>

<p><strong><?= htmlspecialchars($edu['school']) ?></strong> - <?= htmlspecialchars($edu['major']) ?> (<?= htmlspecialchars($edu['period']) ?>)</p>

<?php endforeach; ?>

</div>

<!-- Tương tự cho kỹ năng, kinh nghiệm, dự án -->

</div>

</body>

</html>

Lưu ý: Trong thực tế, bạn nên dùng PHP để render dữ liệu, tránh dùng JS nếu muốn xuất PDF ổn định.
