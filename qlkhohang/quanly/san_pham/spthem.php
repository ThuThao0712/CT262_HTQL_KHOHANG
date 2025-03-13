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

    $mysqli = new mysqli("localhost", "root", "", "qlkhohang"); //chỉnh cái này
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

$mysqli = new mysqli("localhost", "root", "", "qlkhohang");
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
<html lang="vi">
<body>
    <div class="main">
        <h2>✅ Thêm SẢN PHẨM ✅</h2>
        <?php if (!empty($errorMessage)) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
        <form method="POST">
            <label>Tên Sản Phẩm</label>
            <input type="text" name="SP_Ten" required>

            <label>Giá</label>
            <input type="number" name="SP_Gia" required>

            <label>Danh Mục</label>
            <select name="DM_ID">
                <option value="">-- Chọn Danh Mục --</option>
                <?php foreach ($categories as $category) { echo "<option value='{$category['DM_ID']}'>{$category['DM_Ten']}</option>"; } ?>
            </select>

            <label>Nhà Cung Cấp</label>
            <select name="NCC_ID">
                <option value="">-- Chọn Nhà Cung Cấp --</option>
                <?php foreach ($nhacungcap as $ncc) { echo "<option value='{$ncc['NCC_ID']}'>{$ncc['NCC_HoTen']}</option>"; } ?>
            </select>

            <button type="submit">Thêm</button>
            <a href="sp.php">Hủy</a>
        </form>
        <span class="success"><?php echo $successMessage; ?></span>
    </div>
</body>
</html>