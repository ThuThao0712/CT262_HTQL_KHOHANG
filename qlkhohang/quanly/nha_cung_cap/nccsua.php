<?php
require_once '../blocks/head.php';

$servername = "localhost";
$username = "TK_TenDangNhap"; 
$password = "TK_MatKhau"; 
$dbname = "qlkhohang"; 

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Kết nối không thành công: " . $connection->connect_error);
}

// Kiểm tra NCC_ID có tồn tại không
if (!isset($_GET['NCC_ID'])) {
    die("Lỗi: Không tìm thấy nhà cung cấp.");
}

$NCC_ID = $_GET['NCC_ID'];

// Sử dụng Prepared Statement để tránh SQL Injection
$stmt = $connection->prepare("SELECT NCC_ID, NCC_HoTen, NCC_SDT, NCC_DiaChi, NCC_Email FROM nha_cung_cap WHERE NCC_ID = ?");
$stmt->bind_param("i", $NCC_ID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Lỗi: Nhà cung cấp không tồn tại.");
}

if (isset($_POST['update-btn'])) {
    $NCC_HoTen = $_POST['NCC_HoTen'];
    $NCC_SDT = $_POST['NCC_SDT'];
    $NCC_DiaChi = $_POST['NCC_DiaChi'];
    $NCC_Email = $_POST['NCC_Email'];

    // Cập nhật thông tin nhà cung cấp
    $update_stmt = $connection->prepare("UPDATE nha_cung_cap SET NCC_HoTen=?, NCC_SDT=?, NCC_DiaChi=?, NCC_Email=? WHERE NCC_ID=?");
    $update_stmt->bind_param("ssssi", $NCC_HoTen, $NCC_SDT, $NCC_DiaChi, $NCC_Email, $NCC_ID);
    $success = $update_stmt->execute();

    if ($success) {
        header("Location: ncc.php");
        exit();
    } else {
        echo "Đã xảy ra lỗi khi cập nhật nhà cung cấp.";
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="vi">
    <body>
        <div class="all">
            <h2>✍️ Sửa Nhà Cung Cấp ✍️</h2>

            <form method="post">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tên Nhà Cung Cấp</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="NCC_HoTen" value="<?php echo htmlspecialchars($row['NCC_HoTen']); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Số Điện Thoại</label> 
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="NCC_SDT" value="<?php echo htmlspecialchars($row['NCC_SDT']); ?>" required>
                    </div>
                </div>           
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Địa Chỉ</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="NCC_DiaChi" value="<?php echo htmlspecialchars($row['NCC_DiaChi']); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="NCC_Email" value="<?php echo htmlspecialchars($row['NCC_Email']); ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10"> 
                        <button type="submit" name="update-btn" class="btn btn-primary btn-lg">Cập nhật</button>
                        <a href="ncc.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
                </div>            
            </form>   
        </div>
    </body>
</html>
