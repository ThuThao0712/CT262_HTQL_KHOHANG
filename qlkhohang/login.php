<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "qlkhohang";

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Truy vấn kiểm tra tài khoản
    $stmt = $conn->prepare("SELECT TK_ID, TK_MatKhau, TK_VaiTro FROM tai_khoan WHERE TK_TenDangNhap = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Debug: In ra mật khẩu để kiểm tra
        var_dump($row['TK_MatKhau']);
        var_dump($password);

        // Kiểm tra mật khẩu (không mã hóa)
        if ($password == $row['TK_MatKhau']) {
            $_SESSION['TK_ID'] = $row['TK_ID'];
            $_SESSION['TK_VaiTro'] = $row['TK_VaiTro'];

            // Chuyển hướng theo vai trò
            if ($row['TK_VaiTro'] == 'Quản Lý') {
                header("Location: quanly/kho_hang/kho.php");
            } elseif ($row['TK_VaiTro'] == 'Nhân Viên') {
                header("Location: nhanvien/san_pham/sp.php");
            } else {
                echo "Vai trò không hợp lệ!";
            }
            exit();
        } else {
            echo "Sai mật khẩu!";
        }
    } else {
        echo "Tài khoản không tồn tại!";
    }
    
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Đăng nhập</h2>
    <form action="" method="post">
        <input type="text" name="username" placeholder="Tên người dùng" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>
    <a href="index.php">Chưa có tài khoản? Đăng ký</a>
</body>
</html>

