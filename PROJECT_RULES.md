# BỘ QUY TẮC HƯỚNG DẪN AI CHO DỰ ÁN WEBCV_AI (AI PROJECT RULES)

Dự án này là sản phẩm Đồ án 1 (Web App Tạo CV bằng Chat AI) được phát triển bởi nhóm 2 thành viên. Tài liệu này chủ yếu dành cho AI Assistant đọc và tuân thủ trong suốt quá trình trao đổi, phân tích, đề xuất, chỉnh sửa, kiểm thử và quản lý dự án.

---

## 📜 CÁC QUY TẮC BẮT BUỘC ĐỐI VỚI AI (AI RULES)

### 0. Quy tắc Ưu tiên và Cách áp dụng (Priority & Execution)
- AI phải xem các quy tắc trong tài liệu này là yêu cầu vận hành bắt buộc khi làm việc với dự án, không phải chỉ là tài liệu tham khảo.
- Khi có xung đột giữa các quy tắc, AI phải dừng phần bị ảnh hưởng, thông báo rõ xung đột và hỏi chủ dự án trước khi tiếp tục.
- AI phải phân biệt rõ ba loại thông tin:
  - **Thông tin đã xác nhận:** được cung cấp trong dự án hoặc được chủ dự án xác nhận.
  - **Giả định:** suy luận tạm thời của AI, phải được ghi rõ và không được coi là yêu cầu chính thức.
  - **Điểm chưa rõ:** cần hỏi chủ dự án trước khi quyết định hoặc chỉnh sửa.
- AI không được tự nhận là đã kiểm tra, đã chạy, đã sửa hoặc đã hoàn thành một việc nếu chưa thực sự thực hiện việc đó.
- Khi người dùng yêu cầu thay đổi, AI chỉ được thực hiện trong phạm vi đã được yêu cầu hoặc phê duyệt; mọi phần phát sinh ngoài phạm vi phải hỏi lại.

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
- AI phải tham khảo và bám sát tài liệu kỹ thuật khi phân tích hoặc triển khai, nhưng **không được xem mọi nội dung trong tài liệu là bất biến hoặc bắt buộc áp dụng trong mọi trường hợp**.
- Trước khi áp dụng một thiết kế, công nghệ hoặc quy trình được mô tả trong tài liệu, AI phải kiểm tra tính hợp lý, khả năng triển khai, tính tương thích và mức độ phù hợp với trạng thái thực tế của dự án.
- Nếu phát hiện nội dung không còn phù hợp, có rủi ro hoặc có phương án tốt hơn, AI phải **chủ động thông báo, nêu rõ lý do và đề xuất hướng xử lý** để chủ dự án cân nhắc và quyết định.
- Khi dự án có thay đổi đã được thống nhất, tài liệu kỹ thuật phải được cập nhật tương ứng để phản ánh đúng trạng thái và kiến trúc thực tế của dự án.

### 5. Quy tắc Xin phép Trước khi Thay đổi Dự án (Change Approval Workflow)
- Trước mọi chỉnh sửa mã nguồn, tài liệu, cấu hình, cấu trúc thư mục hoặc thay đổi khác, AI phải nêu rõ phạm vi, các tệp hoặc thành phần bị ảnh hưởng, mục đích và cách thực hiện.
- AI **không được tự ý chỉnh sửa trực tiếp vào dự án khi chưa có sự cho phép rõ ràng của chủ dự án**.
- AI phải trình bày nội dung dự kiến để chủ dự án kiểm tra trước khi chỉnh sửa; việc xuất diff hoặc thông tin tại terminal chỉ được xem là thông tin kiểm tra, không thay thế cho sự cho phép.
- Chỉ sau khi chủ dự án xác nhận đồng ý, AI mới được bắt đầu chỉnh sửa. AI không được thực hiện trước rồi mới xin phép từng phần sau khi thay đổi đã xảy ra.
- Nếu phát sinh nhu cầu thay đổi ngoài phạm vi đã được phê duyệt, AI phải dừng phần phát sinh đó, thông báo lại và chờ chủ dự án xác nhận trước khi tiếp tục.

### 6. Quy tắc Quản lý Branch và Pull Request (Branch & Pull Request Workflow)
- AI phải kiểm tra branch hiện tại trước khi thực hiện các thao tác Git có thể ảnh hưởng đến lịch sử hoặc remote.
- AI không được phát triển trực tiếp trên branch `main` khi thực hiện thay đổi tính năng.
- Mỗi chức năng hoặc nhóm thay đổi lớn phải sử dụng một branch riêng.
- Khi tạo branch, AI phải dùng tên mô tả rõ mục đích, ví dụ:
  - `feature/user-authentication`
  - `feature/cv-management`
  - `fix/login-validation`
- Trước khi đề xuất hoặc tạo Pull Request, AI phải kiểm tra code, chạy thử chức năng phù hợp và xem lại các file đã thay đổi.
- AI không được tự merge vào `main` nếu chưa có sự cho phép của chủ dự án.
- AI không được sử dụng `git push --force` trên `main` hoặc branch đang được thành viên khác sử dụng nếu chưa có sự thống nhất rõ ràng.

### 7. Quy tắc Commit (Commit Convention)
- AI chỉ được tạo commit khi chủ dự án yêu cầu hoặc đã phê duyệt việc commit.
- Mỗi commit chỉ nên chứa một nhóm thay đổi liên quan.
- AI không được commit các file chứa mật khẩu, API key, dữ liệu cá nhân hoặc cấu hình riêng của máy.
- Nội dung commit do AI tạo phải ngắn gọn và mô tả đúng thay đổi, ví dụ:
  - `feat: add user registration`
  - `fix: validate login input`
  - `docs: update project rules`
- AI không được sử dụng các commit quá chung chung như `update`, `test` hoặc `fix stuff`.

### 8. Quy tắc Đồng bộ Cơ sở Dữ liệu (Database Synchronization)
- Khi thay đổi bảng, cột, khóa hoặc dữ liệu khởi tạo, AI phải cập nhật `database.sql` hoặc file migration tương ứng trong cùng phạm vi thay đổi.
- AI không được chỉ sửa database trực tiếp trên một máy mà không cập nhật lại repository.
- Khi đề xuất hoặc thực hiện thay đổi database, AI phải ghi rõ:
  - Thành phần được thay đổi.
  - Lý do thay đổi.
  - Cách cập nhật database hiện tại.
  - Ảnh hưởng đến mã nguồn.
- AI phải đảm bảo mỗi thành viên có thể dựng lại database từ các file SQL trong repository.

### 9. Quy tắc Bảo mật và Cấu hình (Security & Configuration)
- AI tuyệt đối không được commit mật khẩu, API key, token hoặc thông tin bí mật vào Git.
- AI phải giữ thông tin cấu hình riêng trong file cấu hình local hoặc biến môi trường.
- Nếu cần file mẫu cấu hình, AI chỉ được commit file như `.env.example` với giá trị minh họa.
- AI không được sử dụng dữ liệu người dùng thật trong quá trình phát triển hoặc kiểm thử.
- AI phải kiểm tra dữ liệu đầu vào từ người dùng trước khi lưu vào database hoặc hiển thị ra giao diện.

### 10. Quy tắc Kiểm thử Trước khi Hoàn thành (Testing Before Completion)
- Trước khi tuyên bố hoàn thành một chức năng mới, AI phải kiểm tra ít nhất các trường hợp:
  - Dữ liệu hợp lệ.
  - Dữ liệu rỗng hoặc thiếu.
  - Dữ liệu sai định dạng.
  - Người dùng chưa đăng nhập hoặc không có quyền.
- Trước khi đề xuất hoặc thực hiện push code, AI phải kiểm tra các chức năng liên quan để tránh làm hỏng chức năng cũ.
- Nếu phát hiện lỗi chưa thể sửa ngay, AI phải ghi lại lỗi, nguyên nhân dự kiến và cách tái hiện; không được che giấu hoặc tuyên bố thành công giả tạo.

### 11. Quy tắc Xử lý Lỗi và Phạm vi Thay đổi (Error Handling & Change Scope)
- AI không được bỏ qua lỗi bằng cách để chương trình tiếp tục chạy như thể không có lỗi.
- AI phải xử lý lỗi rõ ràng và hiển thị thông báo phù hợp cho người dùng.
- AI không được để lộ thông tin nhạy cảm như câu lệnh SQL, đường dẫn máy cá nhân hoặc thông tin cấu hình hệ thống.
- Khi phát hiện lỗi nghiêm trọng, AI phải ghi lại lỗi và thông báo cho chủ dự án trước khi tiếp tục phát triển tính năng mới.
- AI không được tự ý kết hợp nhiều chức năng không liên quan trong cùng một thay đổi.
- Nếu phát hiện một vấn đề nằm ngoài phạm vi công việc hiện tại, AI phải ghi nhận và thông báo trước khi sửa.
- AI phải ưu tiên hoàn thành chức năng nhỏ, dễ kiểm tra trước khi mở rộng sang chức năng lớn hơn.

### 12. Quy tắc Làm rõ Thông tin và Không Tự Bịa đặt (Clarification & No Fabrication)
- Trong quá trình trao đổi, nếu yêu cầu, mục tiêu, phạm vi hoặc thông tin kỹ thuật chưa rõ ràng, AI phải chủ động hỏi lại chủ dự án để có đủ thông tin trước khi đề xuất quyết định hoặc thực hiện.
- AI không được tự suy đoán hoặc tự bịa ra thông tin, yêu cầu, dữ liệu, hành vi hệ thống hoặc quyết định thiết kế mà chủ dự án chưa cung cấp hoặc xác nhận.
- Khi phải đưa ra giả định tạm thời vì chưa thể hỏi lại ngay, AI phải nêu rõ đó là giả định và không được xem giả định đó là yêu cầu chính thức của dự án.
- Nếu có nhiều cách hiểu hoặc nhiều phương án hợp lý, AI phải trình bày các điểm khác nhau và yêu cầu chủ dự án xác nhận phương án được chọn.

---
