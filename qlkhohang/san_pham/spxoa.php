<?php
    if (isset($_GET["SP_ID"])) {
        $SP_ID = $_GET["SP_ID"];

        $servername = "localhost"; 
        $username = "TK_TenDangNhap"; 
        $password = "TK_MatKhau"; 
        $dbname = "qlkhohang"; 

        $connection = new mysqli($servername, $username, $password, $dbname);

        if ($connection->connect_error) {
            die("Kết nối không thành công: " . $connection->connect_error);
        }

        $stmt = $connection->prepare("SELECT COUNT(*) FROM binh_luan WHERE SP_ID = ?");
        $stmt->bind_param("i", $SP_ID);
        $stmt->execute();
        $stmt->bind_result($countBinhLuan);
        $stmt->fetch();
        $stmt->close();

        $stmt = $connection->prepare("SELECT COUNT(*) FROM giam_gia WHERE SP_ID = ?");
        $stmt->bind_param("i", $SP_ID);
        $stmt->execute();
        $stmt->bind_result($countGiamGia);
        $stmt->fetch();
        $stmt->close();

        $stmt = $connection->prepare("SELECT COUNT(*) FROM sp_dvt WHERE SP_ID = ?");
        $stmt->bind_param("i", $SP_ID);
        $stmt->execute();
        $stmt->bind_result($countSPDVT);
        $stmt->fetch();
        $stmt->close();

        $stmt = $connection->prepare("SELECT COUNT(*) FROM chi_tiet_phieu_nhap WHERE SP_ID = ?");
        $stmt->bind_param("i", $SP_ID);
        $stmt->execute();
        $stmt->bind_result($countCTPNH);
        $stmt->fetch();
        $stmt->close();

        if ($countBinhLuan > 0 || $countSPDVT > 0 || $countCTPNH > 0) {
            echo "Không thể xóa SẢN PHẨM vì có dữ liệu liên kết trong các bảng khác.";
        } else {
            $stmt = $connection->prepare("DELETE FROM san_pham WHERE SP_ID = ?");
            $stmt->bind_param("i", $SP_ID);
            if ($stmt->execute()) {
                header("Location: sp.php");
                exit;
            } else {
                echo "Lỗi: Không thể xóa SẢN PHẨM.";
            }
            $stmt->close();
        }
        $connection->close();
    }
?>
