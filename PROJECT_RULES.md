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

### 4. Quy tắc Đánh giá Tài liệu Kỹ thuật & Cập nhật Theo Thực tế (Technical Documentation Review)
- Tài liệu kỹ thuật được xây dựng bởi chủ dự án và thành viên trong nhóm, là cơ sở định hướng ban đầu để phát triển dự án.
- AI và thành viên phát triển phải tham khảo, bám sát tài liệu kỹ thuật khi triển khai, nhưng **không được xem mọi nội dung trong tài liệu là bất biến hoặc bắt buộc áp dụng trong mọi trường hợp**.
- Trước khi áp dụng một thiết kế, công nghệ hoặc quy trình được mô tả trong tài liệu, phải kiểm tra tính hợp lý, khả năng triển khai, tính tương thích và mức độ tối ưu trong bối cảnh thực tế của dự án.
- Nếu phát hiện nội dung không còn phù hợp, có rủi ro hoặc có phương án tốt hơn, AI phải **chủ động thông báo, nêu rõ lý do và đề xuất hướng xử lý** để chủ dự án cân nhắc và quyết định.
- Khi dự án có thay đổi đã được thống nhất, tài liệu kỹ thuật phải được cập nhật tương ứng để phản ánh đúng trạng thái và kiến trúc thực tế của dự án.

### 5. Quy tắc Xin phép Trước khi Thay đổi Dự án (Change Approval Workflow)
- Mọi chỉnh sửa mã nguồn, tài liệu, cấu hình, cấu trúc thư mục hoặc thay đổi khác trong dự án đều phải được **nêu rõ nội dung dự kiến và xuất thông tin tại terminal để chủ dự án kiểm tra**.
- AI **không được tự ý chỉnh sửa trực tiếp vào dự án khi chưa có sự cho phép rõ ràng của chủ dự án**.
- Trước khi thực hiện thay đổi đã được đề xuất, AI phải trình bày trước phạm vi, các tệp hoặc thành phần dự kiến bị ảnh hưởng, mục đích và cách thực hiện để chủ dự án xem xét.
- Chỉ sau khi chủ dự án xác nhận đồng ý, AI mới được bắt đầu chỉnh sửa. AI không được thực hiện trước rồi mới xin phép từng phần sau khi thay đổi đã xảy ra.
- Nếu phát sinh nhu cầu thay đổi ngoài phạm vi đã được phê duyệt, AI phải dừng phần phát sinh đó, thông báo lại và chờ chủ dự án xác nhận trước khi tiếp tục.

### 6. Quy tắc Quản lý Branch và Pull Request (Branch & Pull Request Workflow)
- Không phát triển trực tiếp trên branch `main`.
- Mỗi chức năng hoặc nhóm thay đổi lớn phải sử dụng một branch riêng.
- Tên branch phải mô tả rõ mục đích, ví dụ:
  - `feature/user-authentication`
  - `feature/cv-management`
  - `fix/login-validation`
- Trước khi tạo Pull Request phải kiểm tra code, chạy thử chức năng và xem lại các file đã thay đổi.
- Chỉ merge vào `main` sau khi đã test và review.
- Không sử dụng `git push --force` trên `main` hoặc branch đang được thành viên khác sử dụng nếu chưa thống nhất.

### 7. Quy tắc Commit (Commit Convention)
- Mỗi commit chỉ nên chứa một nhóm thay đổi liên quan.
- Không commit các file chứa mật khẩu, API key, dữ liệu cá nhân hoặc cấu hình riêng của máy.
- Nội dung commit phải ngắn gọn và mô tả đúng thay đổi, ví dụ:
  - `feat: add user registration`
  - `fix: validate login input`
  - `docs: update project rules`
- Không sử dụng các commit quá chung chung như `update`, `test` hoặc `fix stuff`.

### 8. Quy tắc Đồng bộ Cơ sở Dữ liệu (Database Synchronization)
- Mọi thay đổi bảng, cột, khóa hoặc dữ liệu khởi tạo phải được cập nhật trong `database.sql` hoặc file migration tương ứng.
- Không chỉ sửa database trực tiếp trên một máy mà không cập nhật lại repository.
- Khi thay đổi database phải ghi rõ:
  - Thành phần được thay đổi.
  - Lý do thay đổi.
  - Cách cập nhật database hiện tại.
  - Ảnh hưởng đến mã nguồn.
- Mỗi thành viên phải có thể dựng lại database từ các file SQL trong repository.

### 9. Quy tắc Bảo mật và Cấu hình (Security & Configuration)
- Không commit mật khẩu, API key, token hoặc thông tin bí mật vào Git.
- Thông tin cấu hình riêng phải được đặt trong file cấu hình local hoặc biến môi trường.
- Nếu có file mẫu cấu hình, chỉ commit file như `.env.example` với giá trị minh họa.
- Không sử dụng dữ liệu người dùng thật trong quá trình phát triển hoặc kiểm thử.
- Dữ liệu đầu vào từ người dùng phải được kiểm tra trước khi lưu vào database hoặc hiển thị ra giao diện.

### 10. Quy tắc Kiểm thử Trước khi Hoàn thành (Testing Before Completion)
- Mỗi chức năng mới phải được kiểm tra ít nhất bằng các trường hợp:
  - Dữ liệu hợp lệ.
  - Dữ liệu rỗng hoặc thiếu.
  - Dữ liệu sai định dạng.
  - Người dùng chưa đăng nhập hoặc không có quyền.
- Trước khi push code phải kiểm tra các chức năng liên quan để tránh làm hỏng chức năng cũ.
- Nếu phát hiện lỗi chưa thể sửa ngay, phải ghi lại lỗi, nguyên nhân dự kiến và cách tái hiện.

### 11. Quy tắc Xử lý Lỗi và Phạm vi Thay đổi (Error Handling & Change Scope)
- Không được bỏ qua lỗi bằng cách để chương trình tiếp tục chạy như thể không có lỗi.
- Lỗi phải được xử lý rõ ràng và hiển thị thông báo phù hợp cho người dùng.
- Không hiển thị thông tin nhạy cảm như câu lệnh SQL, đường dẫn máy cá nhân hoặc thông tin cấu hình hệ thống.
- Khi phát hiện lỗi nghiêm trọng, phải ghi lại lỗi và thông báo cho thành viên còn lại trước khi tiếp tục phát triển tính năng mới.
- Không tự ý kết hợp nhiều chức năng không liên quan trong cùng một thay đổi.
- Nếu phát hiện một vấn đề nằm ngoài phạm vi công việc hiện tại, phải ghi nhận và thông báo trước khi sửa.
- Ưu tiên hoàn thành chức năng nhỏ, dễ kiểm tra trước khi mở rộng sang chức năng lớn hơn.

---
