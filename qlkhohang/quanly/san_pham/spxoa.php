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

    // Bắt đầu transaction
    $connection->begin_transaction();

    try {
        // Xóa dữ liệu liên quan trước
        $tables = ["chi_tiet_phieu_nhap"];
        foreach ($tables as $table) {
            $stmt = $connection->prepare("DELETE FROM $table WHERE SP_ID = ?");
            $stmt->bind_param("i", $SP_ID);
            $stmt->execute();
            $stmt->close();
        }

        // Xóa sản phẩm
        $stmt = $connection->prepare("DELETE FROM san_pham WHERE SP_ID = ?");
        $stmt->bind_param("i", $SP_ID);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi xóa sản phẩm.");
        }
        $stmt->close();

        // Commit transaction nếu mọi thứ đều thành công
        $connection->commit();
        
        // Chuyển hướng sau khi xóa thành công
        header("Location: sp.php");
        exit;
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $connection->rollback();
        echo "Lỗi: " . $e->getMessage();
    }

    $connection->close();
}
?>
