<?php
    if (isset($_GET["TK_ID"])) {
        $TK_ID = $_GET["TK_ID"];

        $servername = "localhost";
        $username = "TK_TenDangNhap";
        $password = "TK_MatKhau";
        $dbname = "qlkhohang";

        $connection = new mysqli($servername, $username, $password, $dbname);

        if ($connection->connect_error) {
            die("Kết nối không thành công: " . $connection->connect_error);
        }

        $stmt = $connection->prepare("SELECT TK_VaiTro FROM tai_khoan WHERE TK_ID = ?");
        $stmt->bind_param("i", $TK_ID);
        $stmt->execute();
        $stmt->bind_result($TK_VaiTro);
        $stmt->fetch();
        $stmt->close();

        $hasLinkedData = false;

        if ($TK_VaiTro == 'client') {
            $stmt = $connection->prepare("SELECT COUNT(*) FROM don_hang WHERE KH_ID = (SELECT KH_ID FROM khach_hang WHERE TK_ID = ?)");
            $stmt->bind_param("i", $TK_ID);
            $stmt->execute();
            $stmt->bind_result($countDonHang);
            $stmt->fetch();
            $stmt->close();

            $stmt = $connection->prepare("SELECT COUNT(*) FROM gio_hang WHERE KH_ID = (SELECT KH_ID FROM khach_hang WHERE TK_ID = ?)");
            $stmt->bind_param("i", $TK_ID);
            $stmt->execute();
            $stmt->bind_result($countGioHang);
            $stmt->fetch();
            $stmt->close();

            $stmt = $connection->prepare("SELECT COUNT(*) FROM binh_luan WHERE KH_ID = (SELECT KH_ID FROM khach_hang WHERE TK_ID = ?)");
            $stmt->bind_param("i", $TK_ID);
            $stmt->execute();
            $stmt->bind_result($countBinhLuan);
            $stmt->fetch();
            $stmt->close();

            if ($countDonHang > 0 || $countGioHang > 0 || $countBinhLuan > 0) {
                $hasLinkedData = true;
            }
        } elseif ($TK_VaiTro == 'admin') {
            $stmt = $connection->prepare("SELECT COUNT(*) FROM bai_viet WHERE AD_ID = (SELECT AD_ID FROM admin WHERE TK_ID = ?)");
            $stmt->bind_param("i", $TK_ID);
            $stmt->execute();
            $stmt->bind_result($countBaiViet);
            $stmt->fetch();
            $stmt->close();

            $stmt = $connection->prepare("SELECT COUNT(*) FROM phieu_nhap_hang WHERE AD_ID = (SELECT AD_ID FROM admin WHERE TK_ID = ?)");
            $stmt->bind_param("i", $TK_ID);
            $stmt->execute();
            $stmt->bind_result($countPhieuNhap);
            $stmt->fetch();
            $stmt->close();

            $stmt = $connection->prepare("SELECT COUNT(*) FROM thong_ke WHERE AD_ID = (SELECT AD_ID FROM admin WHERE TK_ID = ?)");
            $stmt->bind_param("i", $TK_ID);
            $stmt->execute();
            $stmt->bind_result($countThongKe);
            $stmt->fetch();
            $stmt->close();

            if ($countBaiViet > 0 || $countPhieuNhap > 0 || $countThongKe > 0) {
                $hasLinkedData = true;
            }
        }

        if ($hasLinkedData) {
            echo "Không thể xóa TÀI KHOẢN vì có dữ liệu liên kết trong các bảng khác.";
        } else {
            if ($TK_VaiTro == 'client') {
                $stmt = $connection->prepare("DELETE FROM khach_hang WHERE TK_ID = ?");
                $stmt->bind_param("i", $TK_ID);
                $stmt->execute();
                $stmt->close();
            } elseif ($TK_VaiTro == 'admin') {
                $stmt = $connection->prepare("DELETE FROM admin WHERE TK_ID = ?");
                $stmt->bind_param("i", $TK_ID);
                $stmt->execute();
                $stmt->close();
            }

            $stmt = $connection->prepare("DELETE FROM tai_khoan WHERE TK_ID = ?");
            $stmt->bind_param("i", $TK_ID);
            if ($stmt->execute()) {
                header("Location: acc.php");
                exit;
            } else {
                echo "Lỗi: Không thể xóa TÀI KHOẢN.";
            }
            $stmt->close();
        }

        $connection->close();
    }
?>