USE `cv_app`;
INSERT INTO `templates` (`id`,`name`,`thumbnail`,`description`,`is_active`) VALUES
(1,'Modern','css/cv-templates.css','Phong cách hiện đại, phù hợp CNTT & Sáng tạo',1),
(2,'Professional','css/cv-templates.css','Phong cách công sở thanh lịch, chuyên nghiệp',1),
(3,'Simple','css/cv-templates.css','Tối giản, tập trung vào nội dung dễ đọc',1),
(4,'Creative','css/cv-templates.css','Bố cục sáng tạo, điểm nhấn màu sắc',1),
(5,'Student','css/cv-templates.css','Dành cho sinh viên và thực tập sinh',1),
(6,'Executive','css/cv-templates.css','Phong cách quản lý, trang trọng',1),
(7,'Elegant','css/cv-templates.css','Thanh lịch, tinh tế',1),
(8,'ATS Friendly','css/cv-templates.css','Bố cục đơn giản, dễ đọc bởi hệ thống tuyển dụng',1)
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), is_active=1;
