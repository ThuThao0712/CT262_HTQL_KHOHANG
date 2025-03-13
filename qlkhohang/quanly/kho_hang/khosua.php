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

    $K_ID = $_GET['K_ID'];
    $select = "SELECT k.*, s.SP_Ten, dvt.DVT_Ten
            FROM kho k
            JOIN sp_dvt spdvt ON spdvt.SPDVT_ID = k.SPDVT_ID
            JOIN san_pham s ON spdvt.SP_ID = s.SP_ID
            JOIN don_vi_tinh dvt ON dvt.DVT_ID = spdvt.DVT_ID
            WHERE k.K_ID='$K_ID'";
    $data = mysqli_query($connection, $select);
    $row = mysqli_fetch_array($data);

    if (isset($_POST['update-btn'])) {
        $K_SoLuongNhap = $_POST['K_SoLuongNhap'];
        $K_SoLuongConLai = $_POST['K_SoLuongConLai'];

        $update = "UPDATE kho SET K_SoLuongNhap='$K_SoLuongNhap', K_SoLuongConLai='$K_SoLuongConLai' WHERE K_ID='$K_ID'";
        $data = mysqli_query($connection, $update);

        if ($data) {
            header("Location: kho.php");
        } else {
            echo "Đã xảy ra lỗi khi cập nhật thông tin kho.";
        }

        $connection->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="all">
            <h2>✍️ Sửa số lượng trong KHO ✍️</h2>

            <form method="post">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tên Sản Phẩm</label>
                    <div class="col-sm-10">
                        <p class="form-control"><?php echo $row['SP_Ten']; ?></p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Đơn Vị Tính</label>
                    <div class="col-sm-10">
                        <p class="form-control"><?php echo $row['DVT_Ten']; ?></p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Số Lượng Nhập</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="K_SoLuongNhap" value="<?php echo $row['K_SoLuongNhap']; ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Số Lượng Còn Lại</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="K_SoLuongConLai" value="<?php echo $row['K_SoLuongConLai']; ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-10">
                        <button type="submit" name="update-btn" class="btn btn-primary btn-lg">Cập nhật</button>
                        <a href="kho.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
