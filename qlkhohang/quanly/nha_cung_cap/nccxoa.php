<?php
if (isset($_GET["NCC_ID"])) {
    $NCC_ID = $_GET["NCC_ID"];

    // Kết nối CSDL
    $servername = "localhost"; 
    $username = "root"; // Hoặc TK_TenDangNhap nếu có tài khoản riêng
    $password = ""; // Nếu có mật khẩu thì thay vào
    $dbname = "qlkhohang"; 

    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Kết nối không thành công: " . $connection->connect_error);
    }

    // Kiểm tra xem NCC_ID có dữ liệu liên kết không
    $stmt = $connection->prepare("SELECT COUNT(*) FROM phieu_nhap WHERE NCC_ID = ?");
    $stmt->bind_param("i", $NCC_ID);
    $stmt->execute();
    $stmt->bind_result($countPhieuNhap);
    $stmt->fetch();
    $stmt->close();

    $stmt = $connection->prepare("SELECT COUNT(*) FROM san_pham WHERE NCC_ID = ?");
    $stmt->bind_param("i", $NCC_ID);
    $stmt->execute();
    $stmt->bind_result($countSanPham);
    $stmt->fetch();
    $stmt->close();

    if ($countPhieuNhap > 0 || $countSanPham > 0) {
        echo "<script>alert('❌ Không thể xóa! Nhà cung cấp có dữ liệu liên kết trong phiếu nhập hoặc sản phẩm.'); window.location='ncc.php';</script>";
    } else {
        // Nếu không có ràng buộc, thực hiện xóa
        $stmt = $connection->prepare("DELETE FROM nha_cung_cap WHERE NCC_ID = ?");
        $stmt->bind_param("i", $NCC_ID);
        if ($stmt->execute()) {
            echo "<script>alert('✅ Xóa nhà cung cấp thành công!'); window.location='ncc.php';</script>";
        } else {
            echo "<script>alert('⚠️ Lỗi! Không thể xóa nhà cung cấp.'); window.location='ncc.php';</script>";
        }
        $stmt->close();
    }

    $connection->close();
}
?>
