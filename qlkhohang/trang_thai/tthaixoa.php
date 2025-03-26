<?php
    if (isset($_GET["TT_ID"])) {
        $TT_ID = $_GET["TT_ID"];

        $servername = "localhost"; 
        $username = "TK_TenDangNhap"; 
        $password = "TK_MatKhau"; 
        $dbname = "qlkhohang"; 

        $connection = new mysqli($servername, $username, $password, $dbname);

        if ($connection->connect_error) {
            die("Kết nối không thành công: " . $connection->connect_error);
        }

        $stmt = $connection->prepare("SELECT COUNT(*) FROM don_hang WHERE TT_ID = ?");
        $stmt->bind_param("i", $TT_ID);
        $stmt->execute();
        $stmt->bind_result($countDonHang);
        $stmt->fetch();
        $stmt->close();

        if ($countDonHang > 0) {
            echo "Không thể xóa TRẠNG THÁI vì có dữ liệu liên kết trong các bảng khác.";
        } else {
            $stmt = $connection->prepare("DELETE FROM trang_thai WHERE TT_ID = ?");
            $stmt->bind_param("i", $TT_ID);
            if ($stmt->execute()) {
                header("Location: tthai.php");
                exit;
            } else {
                echo "Lỗi: Không thể xóa TRẠNG THÁI.";
            }
            $stmt->close();
        }
        $connection->close();
    }
?>
