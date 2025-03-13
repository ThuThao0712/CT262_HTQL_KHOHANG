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

    if (isset($_POST['add-btn'])) {
        $SPDVT_ID = $_POST['SPDVT_ID'];
        $K_SoLuongNhap = $_POST['K_SoLuongNhap'];
        $K_SoLuongConLai = $_POST['K_SoLuongConLai'];

        $check_query = "SELECT * FROM kho WHERE SPDVT_ID='$SPDVT_ID'";
        $check_result = mysqli_query($connection, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "Sản phẩm đã tồn tại trong kho.";
        } else {
            $insert = "INSERT INTO kho (SPDVT_ID, K_SoLuongNhap, K_SoLuongConLai) VALUES ('$SPDVT_ID', '$K_SoLuongNhap', '$K_SoLuongConLai')";
            $data = mysqli_query($connection, $insert);

            if ($data) {
                header("Location: kho.php");
            } else {
                echo "Đã xảy ra lỗi khi thêm thông tin vào kho.";
            }
        }

        $connection->close();
    }

    $productQuery = "SELECT spdvt.SPDVT_ID, san_pham.SP_Ten, don_vi_tinh.DVT_Ten
                    FROM sp_dvt spdvt
                    JOIN san_pham ON spdvt.SP_ID = san_pham.SP_ID
                    JOIN don_vi_tinh ON spdvt.DVT_ID = don_vi_tinh.DVT_ID";
    $productResult = mysqli_query($connection, $productQuery);
?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="all">
            <h2>✅ Thêm số lượng vào KHO ✅</h2>
            <form method="post">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tên Sản Phẩm</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="SPDVT_ID" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            <?php
                            if (mysqli_num_rows($productResult) > 0) {
                                while ($productRow = mysqli_fetch_assoc($productResult)) {
                                    echo "<option value='" . $productRow['SPDVT_ID'] . "'>" . htmlspecialchars($productRow['SP_Ten']) . " - " . htmlspecialchars($productRow['DVT_Ten']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Số Lượng Nhập</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="K_SoLuongNhap" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Số Lượng Còn Lại</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="K_SoLuongConLai" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-10">
                        <button type="submit" name="add-btn" class="btn btn-success btn-lg">Thêm</button>
                        <a href="kho.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
