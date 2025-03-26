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

    if (isset($_GET['DM_ID'])) {
        $DM_ID = $_GET['DM_ID'];

        // Lấy thông tin danh mục cần sửa
        $select = "SELECT * FROM danh_muc_sp WHERE DM_ID='$DM_ID'";
        $data = mysqli_query($connection, $select);
        $row = mysqli_fetch_array($data);

        if (!$row) {
            echo "Danh mục không tồn tại!";
            exit;
        }
    } else {
        echo "Lỗi: Không có DM_ID!";
        exit;
    }

    if (isset($_POST['update-btn'])) {
        $DM_Ten = $_POST['DM_Ten'];

        // Cập nhật tên danh mục
        $update = "UPDATE danh_muc_sp SET DM_Ten='$DM_Ten' WHERE DM_ID='$DM_ID'";
        $data = mysqli_query($connection, $update);   

        if ($data) {
            header("Location: dmsp.php");
            exit;
        } else {
            echo "Đã xảy ra lỗi khi cập nhật danh mục.";
        }
    }
    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="vi">
    <body>
        <div class="all">
            <h2>✅ Sửa DANH MỤC ✅</h2>

            <form method="post">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tên Danh Mục</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="DM_Ten" value="<?php echo $row['DM_Ten']; ?>" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" name="update-btn" class="btn btn-primary btn-lg">Cập Nhật</button>
                        <a href="dmsp.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
