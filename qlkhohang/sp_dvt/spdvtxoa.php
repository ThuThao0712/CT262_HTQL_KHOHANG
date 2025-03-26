<?php
    if (isset($_GET["SPDVT_ID"])) {
        $SPDVT_ID = $_GET["SPDVT_ID"];

        $servername = "localhost"; 
        $username = "TK_TenDangNhap"; 
        $password = "TK_MatKhau"; 
        $dbname = "qlkhohang"; 

        $connection = new mysqli($servername, $username, $password, $dbname);

        if ($connection->connect_error) {
            die("Kết nối không thành công: " . $connection->connect_error);
        }

        $stmt = $connection->prepare("SELECT COUNT(*) FROM chi_tiet_gio_hang WHERE SPDVT_ID = ?");
        $stmt->bind_param("i", $SPDVT_ID);
        $stmt->execute();
        $stmt->bind_result($countCTGH);
        $stmt->fetch();
        $stmt->close();

        $stmt = $connection->prepare("SELECT COUNT(*) FROM kho WHERE SPDVT_ID = ?");
        $stmt->bind_param("i", $SPDVT_ID);
        $stmt->execute();
        $stmt->bind_result($countKho);
        $stmt->fetch();
        $stmt->close();

        $stmt = $connection->prepare("SELECT COUNT(*) FROM chi_tiet_don_hang WHERE SPDVT_ID = ?");
        $stmt->bind_param("i", $SPDVT_ID);
        $stmt->execute();
        $stmt->bind_result($countCTDH);
        $stmt->fetch();
        $stmt->close();


        if ($countCTGH > 0 || $countKho > 0 || $countCTDH > 0) {
            echo "Không thể xóa GIÁ SẢN PHẨM vì có dữ liệu liên kết trong các bảng khác.";
        } else {
            $stmt = $connection->prepare("DELETE FROM sp_dvt WHERE SPDVT_ID = ?");
            $stmt->bind_param("i", $SPDVT_ID);
            if ($stmt->execute()) {
                header("Location: spdvt.php");
                exit;
            } else {
                echo "Lỗi: Không thể xóa GIÁ SẢN PHẨM .";
            }
            $stmt->close();
        }

        $connection->close();
    }
?>
