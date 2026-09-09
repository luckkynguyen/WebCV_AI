# QUẢN TRỊ NGUYÊN TẮC VÀ QUY TẮC PHÁT TRIỂN DỰ ÁN WEBCV_AI (PROJECT RULES)

Dự án này là sản phẩm Đồ án 1 (Web App Tạo CV bằng Chat AI) được phát triển bởi nhóm 2 thành viên. Tất cả các AI Assistant và thành viên phát triển BẮT BUỘC tuân thủ nghiêm ngặt các quy tắc dưới đây.

---

## 📜 CÁC QUY TẮC BẮT BUỘC (PROJECT RULES)

### 1. Quy tắc Môi trường Phát triển Thống nhất (Development Environment)
- **Môi trường bắt buộc:** Nhóm thống nhất sử dụng **XAMPP** (Apache + PHP 8.x + MySQL) trên cả 2 máy tính. KHÔNG sử dụng Laragon hay các môi trường khác để tránh bất đồng cấu hình.
- **Cấu hình DB mặc định:** Host `localhost`, Port `3306`, User `root`, Password `""` (rỗng), Database name `cv_app`.
- **Đường dẫn truy cập Local:** Dự án chạy dưới dạng `http://localhost/WebCV_AI/public/` (hoặc tương đương trong thư mục `htdocs` của XAMPP).

### 2. Quy tắc Đường dẫn Tương đối & Tương thích Đa Môi trường (Cross-Platform & Relative Paths)
- Dự án được phát triển song song bởi 2 người trên 2 máy tính khác nhau.
- **Cấm:** KHÔNG ĐƯỢC hardcode đường dẫn tuyệt đối của máy cá nhân (như `D:/tài liệu học/...`, `C:/xampp/htdocs/...`) hoặc dùng URL gốc tuyệt đối phụ thuộc VirtualHost (như `/css/style.css`, `http://localhost/...`).
- **Yêu cầu:** Mọi liên kết file (CSS, JS, Image), link chuyển trang (`href`), và câu lệnh nạp file PHP (`require_once`, `include_once`) BẮT BUỘC phải dùng **đường dẫn tương đối (relative paths)** hoặc sử dụng hằng số đường dẫn gốc động `__DIR__`. Đảm bảo code chạy mượt mà trên cả 2 máy của thành viên nhóm mà không cần chỉnh sửa lại path.

### 3. Quy tắc Nhận xét Cá nhân & Đề xuất Hướng Hành động (Self-Reflection & Actionable Next Steps)
- Sau mỗi câu trả lời, phản hồi hoặc tương tác với người dùng qua khung chat, AI phải **luôn luôn có phần Nhận xét cá nhân** về tình hình công việc / tiến độ hiện tại.
- Đồng thời, AI phải **đưa ra Hướng hành động cụ thể, rõ ràng cho bước tiếp theo** để hai bên luôn chủ động và thống nhất trong quá trình phát triển.


---
