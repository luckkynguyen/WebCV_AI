# WebCV_AI — Website tạo CV bằng AI

## Yêu cầu
- PHP 8.1+ (khuyến nghị PHP 8.2+)
- MySQL 8+ hoặc MariaDB tương thích
- Apache (XAMPP/WAMP) và extension `pdo_mysql`, `mbstring`

## Cài đặt trên XAMPP
1. Giải nén thư mục `WebCV_AI` vào `C:\xampp\htdocs\WebCV_AI`.
2. Mở phpMyAdmin, chọn tab **Import**, nhập file `database.sql` và chạy.
3. Mở `includes/config.php`, chỉnh `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` đúng với máy. Mặc định database là `cv_app`, user `root`, mật khẩu rỗng.
4. Bật Apache và MySQL trong XAMPP.
5. Truy cập `http://localhost/WebCV_AI/public/home.html`.
6. Tạo tài khoản tại **Đăng ký**, sau đó thử đăng nhập và vào Dashboard.

## Các luồng đã đồng bộ
- Chưa đăng nhập mà vào Dashboard/trang tạo CV/mẫu CV: chuyển về đăng nhập và hiển thị thông báo.
- Đăng nhập sai: hiển thị thông báo email hoặc mật khẩu không chính xác.
- Đăng ký với email đã tồn tại: hiển thị thông báo và không tạo tài khoản trùng.
- Đăng ký thành công: tự đăng nhập, chuyển Dashboard và hiển thị thông báo thành công.
- Đăng xuất: quay lại đăng nhập và hiển thị thông báo đã đăng xuất.
- Các liên kết điều hướng giữa Dashboard, Mẫu CV, Tạo CV và Tài khoản đã được sửa về đúng trang.

## Lưu ý
- Các tính năng tạo CV bằng chat AI, chỉnh sửa CV và xem trước có thể chưa hoàn thiện đầy đủ; các trang tạm thời chuyển về biểu mẫu/Dashboard kèm thông báo thay vì để trang trắng.
- Cần cấu hình `GEMINI_API_KEY` trước khi sử dụng các chức năng AI tích hợp Gemini.
- Không dùng cấu hình database mặc định cho môi trường production. Hãy đặt mật khẩu mạnh, bật HTTPS và cấu hình bảo vệ CSRF/rate limit trước khi triển khai công khai.

## Giao diện dùng chung (bản cập nhật)
- Các trang sử dụng chung font Inter và stylesheet `public/css/app-consistent.css`.
- Sidebar được chuẩn hóa về chiều rộng, màu sắc, trạng thái active và responsive trên màn hình nhỏ.
- Trang `public/account.php` cho phép cập nhật tên hiển thị và đổi mật khẩu.
- Menu Tài khoản trỏ về `account.php`; đăng xuất dùng `logout.php`.
- Cần có PHP 8+, MySQL và import `database.sql` trước khi chạy. Nếu Google Fonts không có Internet, trình duyệt sẽ dùng font hệ thống thay thế.


## Cập nhật luồng lưu, chỉnh sửa và xuất CV

- Trang chủ có thể mở tại `public/home.php`; khi đã đăng nhập sẽ hiển thị tên tài khoản, không cần avatar. `home.html` tự chuyển đến `home.php`.
- Tạo CV tại `public/create-cv-form.php`, chọn một trong 8 mẫu và lưu vào bảng `cvs`.
- CV đã lưu xuất hiện trong Dashboard; nút Xem mở `preview.php?id=...`, nút Sửa mở `edit-cv.php?id=...`; sau khi lưu chỉnh sửa có thể xem và sửa tiếp.
- Nút **Xuất PDF** mở hộp thoại in của trình duyệt. Chọn **Save as PDF / Lưu dưới dạng PDF** để tải tệp.
- Nếu đã cài database từ trước, hãy chọn database `cv_app` trong phpMyAdmin và import `database_update_templates.sql` để thêm đủ 8 mẫu. Khi tạo database mới, dùng `database.sql`.
- Đã chạy `php -l` cho các file PHP. Cần kiểm tra kết nối MySQL trên máy chạy XAMPP của bạn trước khi sử dụng.
