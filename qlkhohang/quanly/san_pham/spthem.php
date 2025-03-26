<?php
require_once '../blocks/head.php';

$SP_Ten = $SP_Gia = "";
$DM_ID = $NCC_ID = null;
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $SP_Ten = test_input($_POST["SP_Ten"]);
    $SP_Gia = test_input($_POST["SP_Gia"]);
    $DM_ID = test_input($_POST["DM_ID"]);
    $NCC_ID = test_input($_POST["NCC_ID"]);

    $mysqli = new mysqli("localhost", "TK_TenDangNhap", "TK_MatKhau", "qlkhohang"); 
    if ($mysqli->connect_error) {
        die("Kết nối không thành công: " . $mysqli->connect_error);
    }

    $sql = "INSERT INTO san_pham (SP_Ten, SP_Gia, DM_ID, NCC_ID) VALUES ('$SP_Ten', '$SP_Gia', '$DM_ID', '$NCC_ID')";
    if ($mysqli->query($sql) === TRUE) {
        $successMessage = "Sản phẩm đã được tạo thành công.";
        header("Location: sp.php");
        exit;
    } else {
        $errorMessage = "Đã xảy ra lỗi: " . $mysqli->error;
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

$resultDM = $mysqli->query("SELECT DM_ID, DM_Ten FROM danh_muc_sp");
$categories = [];
while ($row = $resultDM->fetch_assoc()) {
    $categories[] = $row;
}

$resultNCC = $mysqli->query("SELECT NCC_ID, NCC_HoTen FROM nha_cung_cap");
$nhacungcap = [];
while ($row = $resultNCC->fetch_assoc()) {
    $nhacungcap[] = $row;
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="all">
        <h2>✅ Thêm SẢN PHẨM ✅</h2>

            <div class="tkad">
                <div class="dstaikhoan">
                    <div class="main">
                        <?php if (!empty($errorMessage)) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
                        <form method="post">
                            <div class="mb-3 row">

                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Tên Sản Phẩm</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="SP_Ten" value="<?php echo $SP_Ten; ?>" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Danh Mục</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="DM_ID" required>
                                        <option value="">-- Chọn Danh Mục --</option>
                                        <?php foreach ($categories as $category) { echo "<option value='{$category['DM_ID']}'>{$category['DM_Ten']}</option>"; } ?>
                                    </select>
                                </div>
                            </div>


                            
                            <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Nhà Cung Cấp</label>
                                <div class="col-sm-10">
                                    <select class="form-select" name="NCC_ID" required>
                                        <option value="">-- Chọn Nhà Cung Cấp --</option>
                                        <?php foreach ($nhacungcap as $ncc) { echo "<option value='{$ncc['NCC_ID']}'>{$ncc['NCC_HoTen']}</option>"; } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-10">
                                    <button type="submit" name="add-btn" class="btn btn-success btn-lg">Thêm</button>
                                    <a href="sp.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                                </div>
                            </div>

                        </form>
                        <span class="success"><?php echo $successMessage; ?></span>
                    </div>
                </div>
            </div>
    </div>
</body>
</html>