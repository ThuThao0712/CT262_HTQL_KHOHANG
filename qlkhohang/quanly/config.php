<?php
$servername = "localhost"; // Hoặc IP máy chủ MySQL
$username = "root"; // Tài khoản MySQL (mặc định của XAMPP là root)
$password = ""; // Mật khẩu MySQL (mặc định của XAMPP để trống)
$database = "qlkhohang"; // Thay bằng tên database của bạn

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
