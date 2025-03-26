<?php
require_once '../blocks/head.php';

$servername = "localhost"; 
$username = "TK_TenDangNhap"; 
$password = "TK_MatKhau"; 
$dbname = "qlkhohang"; 


// Kết nối database
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Kết nối không thành công: " . $connection->connect_error);
}

// Lấy PX_ID từ URL
$PX_ID = isset($_GET['PX_ID']) ? $_GET['PX_ID'] : '';

// Lấy thông tin phiếu xuất
$sql = "SELECT phieu_xuat.PX_ID, phieu_xuat.PX_NgayXuat, phieu_xuat.PX_GioXuat, phieu_xuat.TT_ID, trang_thai.TT_Ten 
        FROM phieu_xuat 
        LEFT JOIN trang_thai ON phieu_xuat.TT_ID = trang_thai.TT_ID
        WHERE phieu_xuat.PX_ID = '$PX_ID'";

$result = $connection->query($sql);
$phieu_xuat = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['TT_ID'];

    // Cập nhật trạng thái phiếu xuất
    $update_sql = "UPDATE phieu_xuat SET TT_ID = '$new_status' WHERE PX_ID = '$PX_ID'";
    if ($connection->query($update_sql) === TRUE) {
        echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href = 'lich_su_xuat.php';</script>";
    } else {
        echo "Lỗi: " . $connection->error;
    }
}

// Lấy danh sách trạng thái
$statuses = $connection->query("SELECT * FROM trang_thai");
?>

<!DOCTYPE html>
<html>
    <body>
        <div class="wrapper">
            <?php include '../blocks/sidebar.php'; ?>
            <div class="main">
                <div class="tkad">
                    <div class="dstaikhoan">
                        <h2>Cập nhật trạng thái Phiếu Xuất</h2>

                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="PX_NgayXuat">Ngày Xuất:</label>
                                <input type="text" class="form-control" id="PX_NgayXuat" value="<?php echo $phieu_xuat['PX_NgayXuat']; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label for="PX_GioXuat">Giờ Xuất:</label>
                                <input type="text" class="form-control" id="PX_GioXuat" value="<?php echo $phieu_xuat['PX_GioXuat']; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label for="TT_ID">Trạng Thái Hiện Tại:</label>
                                <input type="text" class="form-control" id="TT_Ten" value="<?php echo $phieu_xuat['TT_Ten']; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label for="TT_ID">Chọn Trạng Thái Mới:</label>
                                <select class="form-control" name="TT_ID" required>
                                    <?php while ($row = $statuses->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['TT_ID']; ?>" <?php echo ($row['TT_ID'] == $phieu_xuat['TT_ID']) ? 'selected' : ''; ?>>
                                            <?php echo $row['TT_Ten']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../main.js"></script>
    </body>
</html>
