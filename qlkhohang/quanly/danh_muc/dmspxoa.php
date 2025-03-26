<?php
    if (isset($_GET["DM_ID"])) {
        $DM_ID = $_GET["DM_ID"];
        $servername = "localhost"; 
        $username = "TK_TenDangNhap"; 
        $password = "TK_MatKhau"; 
        $dbname = "qlkhohang"; 
     $connection = new mysqli($servername, $username, $password, $dbname);

        if ($connection->connect_error) {
            die("Kết nối không thành công: " . $connection->connect_error);
        }

        // Kiểm tra danh mục có sản phẩm nào không
        $stmt = $connection->prepare("SELECT COUNT(*) FROM san_pham WHERE DM_ID = ?");
        $stmt->bind_param("i", $DM_ID);
        $stmt->execute();
        $stmt->bind_result($countSanPham);
        $stmt->fetch();
        $stmt->close();

        if ($countSanPham > 0) {
            echo "<script>alert('Không thể xóa DANH MỤC vì có sản phẩm liên kết!'); window.location.href='dmsp.php';</script>";
        } else {
            // Xóa danh mục nếu không có sản phẩm liên kết
            $stmt = $connection->prepare("DELETE FROM danh_muc_sp WHERE DM_ID = ?");
            $stmt->bind_param("i", $DM_ID);
            if ($stmt->execute()) {
                echo "<script>alert('Xóa danh mục thành công!'); window.location.href='dmsp.php';</script>";
            } else {
                echo "<script>alert('Lỗi: Không thể xóa danh mục!'); window.location.href='dmsp.php';</script>";
            }
            $stmt->close();
        }

        $connection->close();
    }
?>
