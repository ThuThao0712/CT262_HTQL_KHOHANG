<?php
    require_once '../blocks/head.php';
    $mysqli = new mysqli("localhost", "TK_TenDangNhap", "TK_MatKhau", "qlkhohang");

    if ($mysqli->connect_error) {
        die("Kết nối không thành công: " . $mysqli->connect_error);
    }

    $SP_ID = $_GET['SP_ID'] ?? null;
    if (!$SP_ID) {
        die("ID sản phẩm không hợp lệ.");
    }

    $SP_Ten = $SP_ThuongHieu = $SP_QuyCach = $SP_MoTa = $SP_ThanhPhan = $SP_ChiDinh = $SP_HDSD = $SP_LuuY = $SP_NgaySanXuat = $SP_NgayHetHan = $SP_XuatXu = $SP_NSX = $SP_Anh = "";
    $DM_ID = $NCC_ID = $DBC_ID = null;
    $errorMessage = "";
    $successMessage = "";

    $danhMucQuery = "SELECT DM_ID, DM_Ten FROM danh_muc";
    $resultDanhMuc = $mysqli->query($danhMucQuery);

    $nhaCCQuery = "SELECT NCC_ID, NCC_HoTen FROM nha_cung_cap";
    $resultNCC = $mysqli->query($nhaCCQuery);

    $dangBaoCheQuery = "SELECT DBC_ID, DBC_Ten FROM dang_bao_che";
    $resultDangBaoChe = $mysqli->query($dangBaoCheQuery);

    $sql = "SELECT sp.*, dm.DM_Ten, ncc.NCC_HoTen, dbc.DBC_Ten
            FROM san_pham sp
            JOIN danh_muc dm ON sp.DM_ID = dm.DM_ID
            JOIN nha_cung_cap ncc ON sp.NCC_ID = ncc.NCC_ID
            JOIN dang_bao_che dbc ON dbc.DBC_ID = sp.DBC_ID
            WHERE sp.SP_ID = '$SP_ID'";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $SP_Ten = $row["SP_Ten"];
        $SP_ThuongHieu = $row["SP_ThuongHieu"];
        $SP_QuyCach = $row["SP_QuyCach"];
        $SP_MoTa = $row["SP_MoTa"];
        $SP_NSX = $row["SP_NSX"];
        $SP_ThanhPhan = $row["SP_ThanhPhan"];
        $SP_ChiDinh = $row["SP_ChiDinh"];
        $SP_HDSD = $row["SP_HDSD"];
        $SP_LuuY = $row["SP_LuuY"];
        $SP_NgaySanXuat = $row["SP_NgaySanXuat"];
        $SP_NgayHetHan = $row["SP_NgayHetHan"];
        $SP_XuatXu = $row["SP_XuatXu"];
        $SP_Anh = $row["SP_Anh"];
        $DM_ID = $row["DM_ID"];
        $NCC_ID = $row["NCC_ID"];
        $DBC_ID = $row["DBC_ID"];
    } else {
        $errorMessage = "Không tìm thấy sản phẩm với ID này.";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $SP_Ten = test_input($_POST["SP_Ten"]);
        $SP_ThuongHieu = test_input($_POST["SP_ThuongHieu"]);
        $SP_QuyCach = test_input($_POST["SP_QuyCach"]);
        $SP_MoTa = test_input($_POST["SP_MoTa"]);
        $SP_NSX = test_input($_POST["SP_NSX"]);
        $SP_ThanhPhan = test_input($_POST["SP_ThanhPhan"]);
        $SP_ChiDinh = test_input($_POST["SP_ChiDinh"]);
        $SP_HDSD = test_input($_POST["SP_HDSD"]);
        $SP_LuuY = test_input($_POST["SP_LuuY"]);
        $SP_NgaySanXuat = test_input($_POST["SP_NgaySanXuat"]);
        $SP_NgayHetHan = test_input($_POST["SP_NgayHetHan"]);
        $SP_XuatXu = test_input($_POST["SP_XuatXu"]);
        $DM_ID = test_input($_POST["DM_ID"]);
        $NCC_ID = test_input($_POST["NCC_ID"]);
        $DBC_ID = test_input($_POST["DBC_ID"]);

        // Xử lý tải lên ảnh mới nếu có
        if ($_FILES["SP_Anh"]["name"]) {
            $target_dir = "C:/xampp/htdocs/htqlkhohang/client/img/items/"; // Đảm bảo đường dẫn chính xác tới thư mục lưu ảnh
            $target_file = $target_dir . basename($_FILES["SP_Anh"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = array("jpg", "jpeg", "png", "gif");

            if (in_array($imageFileType, $allowed_types)) {
                if (move_uploaded_file($_FILES["SP_Anh"]["tmp_name"], $target_file)) {
                    // Lưu tên file ảnh vào biến $SP_Anh
                    $SP_Anh = htmlspecialchars(basename($_FILES["SP_Anh"]["name"]));
                } else {
                    $errorMessage = "Có lỗi xảy ra khi tải lên ảnh.";
                }
            } else {
                $errorMessage = "Chỉ cho phép tải lên các định dạng JPG, JPEG, PNG & GIF.";
            }
        } else {
            // Nếu không có ảnh mới, giữ nguyên giá trị ảnh cũ
            $SP_Anh = $SP_Anh; // Tên ảnh cũ vẫn được giữ lại
        }

        // Câu lệnh SQL cập nhật sản phẩm
        $sql = "UPDATE san_pham
                SET SP_Ten='$SP_Ten', SP_ThuongHieu='$SP_ThuongHieu', SP_QuyCach='$SP_QuyCach',
                    SP_MoTa='$SP_MoTa', SP_NSX='$SP_NSX', SP_ThanhPhan='$SP_ThanhPhan', SP_ChiDinh='$SP_ChiDinh',
                    SP_HDSD='$SP_HDSD', SP_LuuY='$SP_LuuY', SP_NgaySanXuat='$SP_NgaySanXuat',
                    SP_NgayHetHan='$SP_NgayHetHan', SP_XuatXu='$SP_XuatXu', DM_ID='$DM_ID',
                    NCC_ID='$NCC_ID', DBC_ID='$DBC_ID'";

        // Chỉ cập nhật ảnh nếu có ảnh mới
        if (!empty($SP_Anh)) {
            $sql .= ", SP_Anh='$SP_Anh'";
        }

        $sql .= " WHERE SP_ID='$SP_ID'";

        // Thực thi câu lệnh SQL
        if ($mysqli->query($sql) === TRUE) {
            $successMessage = "Cập nhật sản phẩm thành công.";
            header("Location: sp.php");
            exit;
        } else {
            $errorMessage = "Đã xảy ra lỗi khi cập nhật sản phẩm: " . $mysqli->error;
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="all">
            <h2>✍️ Sửa SẢN PHẨM ✍️</h2>
            <?php
                if (!empty($errorMessage)) {
                    echo "<div class='alert alert-danger'>$errorMessage</div>";
                } elseif (!empty($successMessage)) {
                    echo "<div class='alert alert-success'>$successMessage</div>";
                }
            ?>
            
            <form method="post" enctype="multipart/form-data">
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
                <div class="mb-3">
                    <label for="SP_Anh">Hình ảnh sản phẩm</label>
                    <input type="file" id="SP_Anh" name="SP_Anh" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="DM_ID">Danh mục</label>
                    <select id="DM_ID" name="DM_ID" class="form-control">
                        <option value="">Chọn danh mục</option>
                        <?php
                        while ($rowDanhMuc = $resultDanhMuc->fetch_assoc()) {
                            $selected = ($DM_ID == $rowDanhMuc['DM_ID']) ? "selected" : "";
                            echo "<option value='" . $rowDanhMuc['DM_ID'] . "' $selected>" . htmlspecialchars($rowDanhMuc['DM_Ten']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="NCC_ID">Nhà cung cấp</label>
                    <select id="NCC_ID" name="NCC_ID" class="form-control">
                        <option value="">Chọn Nhà cung cấp</option>
                        <?php
                        while ($rowNCC = $resultNCC->fetch_assoc()) {
                            $selected = ($NCC_ID == $rowNCC['NCC_ID']) ? "selected" : "";
                            echo "<option value='" . $rowNCC['NCC_ID'] . "' $selected>" . htmlspecialchars($rowNCC['NCC_HoTen']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="DBC_ID">Dạng bào chế</label>
                    <select id="DBC_ID" name="DBC_ID" class="form-control">
                        <option value="">Chọn dạng bào chế</option>
                        <?php
                        while ($rowDangBaoChe = $resultDangBaoChe->fetch_assoc()) {
                            $selected = ($DBC_ID == $rowDangBaoChe['DBC_ID']) ? "selected" : "";
                            echo "<option value='" . $rowDangBaoChe['DBC_ID'] . "' $selected>" . htmlspecialchars($rowDangBaoChe['DBC_Ten']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="sp.php" class="btn btn-outline-danger">Hủy</a>
                </div>
            </form>
        </div>
    </body>
</html>