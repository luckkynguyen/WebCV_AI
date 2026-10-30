# CVAI – bản tăng cường hiệu ứng

- Giữ bảng màu kem, nâu và vàng champagne hiện có.
- Thêm hiệu ứng hover cho nút/thẻ/menu, focus rõ ràng cho form, animation xuất hiện nhẹ và hỗ trợ `prefers-reduced-motion`.
- Chuẩn hóa font stack ưu tiên Be Vietnam Pro/Noto Sans/Segoe UI để giảm lỗi dấu tiếng Việt; các trang giữ `<meta charset="UTF-8">`.
- CSS mới: `public/css/polish-effects.css`, được nhúng vào các trang chính.

Nếu trình duyệt vẫn hiển thị lỗi dấu: lưu các file PHP/CSS dưới dạng UTF-8 (không chuyển sang ANSI), đồng thời kiểm tra cấu hình kết nối MySQL và header HTTP có charset UTF-8.
