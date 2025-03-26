<?php
    session_start();
  // Database connection
  $servername = "localhost";
  $username = "TK_TenDangNhap";
  $password = "TK_MatKhau";
  $dbname = "qlkhohang";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  else{
      // echo "Thanh cong";
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

        // Kiểm tra mật khẩu (không mã hóa)
        if ($password == $row['TK_MatKhau']) {
            $_SESSION['TK_ID'] = $row['TK_ID'];
            $_SESSION['TK_VaiTro'] = $row['TK_VaiTro'];
            
            if ($row['TK_VaiTro'] == 'Nhân Viên') {
                $stmt_nv = $conn->prepare("SELECT NV_ID, KHO_ID FROM nhan_vien WHERE TK_ID = ?");
                $stmt_nv->bind_param("i", $row['TK_ID']);
                $stmt_nv->execute();
                $result_nv = $stmt_nv->get_result();

                if ($result_nv->num_rows > 0) {
                    $nv = $result_nv->fetch_assoc();
                    $_SESSION['NV_ID'] = $nv['NV_ID']; // Lưu NV_ID
                    $_SESSION['KHO_ID'] = $nv['KHO_ID']; // Lưu KHO_ID của nhân viên
                }
                $stmt_nv->close();
            }


            // Chuyển hướng theo vai trò
            if ($row['TK_VaiTro'] == 'Quản Lý') {
                header("Location: quanly/kho_hang/kho.php");
            } elseif ($row['TK_VaiTro'] == 'Nhân Viên') {
                header("Location: nhanvien/san_pham/sp.php");
            } else {
                echo "Vai trò không hợp lệ!";
            }
            exit();
        }
        else{
            echo "Tài khoản hoặc mật khẩu không chính xác";
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
</body>
</html>

