<?php
    require_once '../blocks/head.php';

    $SP_Ten = $SP_ThuongHieu = $SP_QuyCach = $SP_XuatXu = $SP_NSX = $SP_MoTa = $SP_ThanhPhan = $SP_ChiDinh = $SP_HDSD = $SP_LuuY = $SP_NgaySanXuat = $SP_NgayHetHan = $SP_Anh = "";
    $DM_ID = $NCC_ID = $DBC_ID = null;

    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $SP_Ten = test_input($_POST["SP_Ten"]);
        $SP_ThuongHieu = test_input($_POST["SP_ThuongHieu"]);
        $SP_QuyCach = test_input($_POST["SP_QuyCach"]);
        $SP_XuatXu = test_input($_POST["SP_XuatXu"]);
        $SP_NSX = test_input($_POST["SP_NSX"]);
        $SP_MoTa = test_input($_POST["SP_MoTa"]);
        $SP_ThanhPhan = test_input($_POST["SP_ThanhPhan"]);
        $SP_ChiDinh = test_input($_POST["SP_ChiDinh"]);
        $SP_HDSD = test_input($_POST["SP_HDSD"]);
        $SP_LuuY = test_input($_POST["SP_LuuY"]);
        $SP_NgaySanXuat = test_input($_POST["SP_NgaySanXuat"]);
        $SP_NgayHetHan = test_input($_POST["SP_NgayHetHan"]);
        $SP_Anh = test_input($_POST["SP_Anh"]);
        $DM_ID = test_input($_POST["DM_ID"]);
        $NCC_ID = test_input($_POST["NCC_ID"]);
        $DBC_ID = test_input($_POST["DBC_ID"]);

        $mysqli = new mysqli("localhost", "TK_TenDangNhap", "TK_MatKhau", "qlkhohang");
        if ($mysqli->connect_error) {
            die("Kết nối không thành công: " . $mysqli->connect_error);
        }

        $sql = "INSERT INTO san_pham (SP_Ten, SP_ThuongHieu, SP_QuyCach, SP_XuatXu, SP_NSX, SP_MoTa, SP_ThanhPhan, SP_ChiDinh, SP_HDSD, SP_LuuY, SP_NgaySanXuat, SP_NgayHetHan, SP_Anh, DM_ID, NCC_ID, DBC_ID)
                VALUES ('$SP_Ten', '$SP_ThuongHieu', '$SP_QuyCach', '$SP_XuatXu', '$SP_NSX', '$SP_MoTa', '$SP_ThanhPhan', '$SP_ChiDinh', '$SP_HDSD', '$SP_LuuY', '$SP_NgaySanXuat', '$SP_NgayHetHan', '$SP_Anh', '$DM_ID', '$NCC_ID', '$DBC_ID')";
        if ($mysqli->query($sql) === TRUE) {
            $successMessage = "Sản phẩm đã được tạo thành công.";
            header("Location: sp.php");
            exit; 
        } else {
            echo "Đã xảy ra lỗi khi tạo sản phẩm: " . $mysqli->error;
        }

            $mysqli->close();
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $mysqli = new mysqli("localhost", "TK_TenDangNhap", "TK_MatKhau", "qlkhohang");
    if ($mysqli->connect_error) {
        die("Kết nối không thành công: " . $mysqli->connect_error);
    }

    $result = $mysqli->query("SELECT DM_ID, DM_Ten FROM danh_muc");
    $categories = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    $resultNCC = $mysqli->query("SELECT NCC_ID, NCC_HoTen FROM nha_cung_cap");
    $nhacungcap = [];
    if ($resultNCC && $resultNCC->num_rows > 0) {
        while ($row = $resultNCC->fetch_assoc()) {
            $nhacungcap[] = $row;
        }
    }

    $resultDBC = $mysqli->query("SELECT DBC_ID, DBC_Ten FROM dang_bao_che");
    $dangbaoche = [];
    if ($resultDBC && $resultDBC->num_rows > 0) {
        while ($row = $resultDBC->fetch_assoc()) {
            $dangbaoche[] = $row;
        }
    }
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="main">
            <div class="all">
                <div class="mb-3 row">
                    <h2>✅ Thêm SẢN PHẨM ✅</h2>
                    <div class="col-sm-10">
                        <a href="../danh_muc/dmspthem.php" class="btn btn-warning">Thêm Danh Mục Sản Phẩm</a>
                        <a href="../nha_cung_cap/nccthem.php" class="btn btn-info">Thêm Nhà Cung Cấp</a>
                    </div>
                </div>
                <?php
                    if (!empty($errorMessage)) {
                        echo "
                            <div class='alert alert-danger'>$errorMessage</div>
                        ";
                    }
                ?>
                <form method="POST">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Tên Sản Phẩm</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="SP_Ten" value="<?php echo $SP_Ten; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Thương Hiệu</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="SP_ThuongHieu" value="<?php echo $SP_ThuongHieu; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Quy Cách</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="SP_QuyCach" value="<?php echo $SP_QuyCach; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Xuất Xứ</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="SP_XuatXu" value="<?php echo $SP_XuatXu; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Nhà Sản Xuất</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="SP_NSX" value="<?php echo $SP_NSX; ?>" required>
                        </div>
                    </div>                <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Ngày Sản Xuất</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="SP_NgaySanXuat" value="<?php echo $SP_NgaySanXuat; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Ngày Hết Hạn</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="SP_NgayHetHan" value="<?php echo $SP_NgayHetHan; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Mô Tả</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="SP_MoTa" rows="4" required><?php echo $SP_MoTa; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Thành Phần</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="SP_ThanhPhan" rows="2" required><?php echo $SP_ThanhPhan; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Chỉ Định</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="SP_ChiDinh" rows="4" required><?php echo $SP_ChiDinh; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Hướng Dẫn Sử Dụng</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="SP_HDSD" rows="4" required><?php echo $SP_HDSD; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Lưu Ý</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="SP_LuuY" rows="2" required><?php echo $SP_LuuY; ?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Ảnh Sản Phẩm</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="SP_Anh" value="<?php echo $SP_Anh; ?>" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Danh Mục</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="DM_ID">
                                <option value="">-- Chọn Danh Mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['DM_ID']; ?>"><?php echo $category['DM_Ten']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Nhà Cung Cấp</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="NCC_ID">
                                <option value="">-- Chọn Nhà Cung Cấp --</option>
                                <?php foreach ($nhacungcap as $ncc): ?>
                                    <option value="<?php echo $ncc['NCC_ID']; ?>"><?php echo $ncc['NCC_HoTen']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Dạng Bào Chế</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="DBC_ID">
                                <option value="">-- Chọn Dạng Bào Chế --</option>
                                <?php foreach ($dangbaoche as $dbc): ?>
                                    <option value="<?php echo $dbc['DBC_ID']; ?>"><?php echo $dbc['DBC_Ten']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-success btn-lg">Thêm</button>
                            <a href="sp.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                        </div>
                    </div>
                </form>
                <span class="success"><?php echo $successMessage; ?></span>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </body>
</html>