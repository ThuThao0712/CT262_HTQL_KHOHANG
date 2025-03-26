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

    $TT_ID = $_GET['TT_ID'];

    $select = "SELECT * FROM trang_thai WHERE TT_ID='$TT_ID'";
    $data = mysqli_query($connection, $select);
    $row = mysqli_fetch_array($data);

    if (isset($_POST['update-btn'])) {
        $TT_TenTT = $_POST['TT_TenTT'];

        $update = "UPDATE trang_thai SET TT_TenTT='$TT_TenTT' WHERE TT_ID='$TT_ID'";
        $data = mysqli_query($connection, $update);
        if ($data) {
            header("Location: tthai.php");
            exit; 
        } else {
            echo "Đã xảy ra lỗi khi cập nhật trạng thái: " . $connection->error;
        }

        $connection->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="all">
            <h2>✍️ Sửa TRẠNG THÁI ✍️</h2>

            <form method="POST">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tên Trạng Thái</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="TT_TenTT" value="<?php echo $row['TT_TenTT']; ?>" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" name="update-btn" class="btn btn-primary btn-lg">Cập nhật</button>
                        <a href="tthai.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
