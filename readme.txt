
*Trang web chính:

B1. Copy toàn bộ file và folder trong folder "source code" vào phần thư mục htdocs của Xampp ở máy.

B2: Mở file db.php trong source code/classroom/.
	Tại dòng thứ 13, 14 chỉnh sửa "username", "password" theo máy của  .
	Ví dụ, trong các video   hay sử dụng để hướng dẫn các bài lab:
	+ username : root
	+ password: 123456

B3: Khởi động Xampp (Apache, MySQL) với port 8888 (Apache) (Tùy vào port ở trên máy   cài đặt)

B4: Sử dụng trình duyệt Web với đường link: http://localhost:8888/phpmyadmin
 - Tại đây, giao diện phpMyAdmin xuất hiện. Chọn New => Import => Chọn tệp => database.sql => Go.

B5: Sử dụng trình duyệt Web với đường link: http://localhost:8888/classroom
