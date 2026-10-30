# Sửa lỗi font tiếng Việt toàn bộ WebCV AI

Đã cập nhật:
- Nạp Be Vietnam Pro cho nội dung và Noto Serif cho tiêu đề serif, đều hỗ trợ tiếng Việt.
- Áp dụng font dùng chung trên các trang public; cập nhật cache-busting `global-font-fix.css?v=2`.
- Tăng khoảng cách dòng và vùng đệm phía trên/dưới các tiêu đề lớn để dấu tiếng Việt không bị cắt.
- Mở rộng vùng clip theo chiều dọc cho hiệu ứng reveal chữ ở trang chủ, tránh cắt dấu.
- Giữ nguyên bố cục, màu sắc và các mẫu CV hiện có.

Sau khi thay project, chạy lại web server và nhấn Ctrl + F5 để tải CSS mới. Font Google được tải từ Google Fonts; nếu máy không có Internet, trình duyệt sẽ dùng font dự phòng Segoe UI/Arial và Georgia.
