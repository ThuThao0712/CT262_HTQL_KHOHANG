<?php
require_once '../blocks/head.php';
$mysqli = new mysqli("localhost", "root", "", "qlkhohang"); // chỉnh cái này

if ($mysqli->connect_error) {
    die("Kết nối không thành công: " . $mysqli->connect_error);
}

$SP_ID = $_GET['SP_ID'] ?? null;
if (!$SP_ID) {
    die("ID sản phẩm không hợp lệ.");
}

// Khởi tạo biến
$SP_Ten = $SP_Gia = "";
$DM_ID = $NCC_ID = null;
$errorMessage = "";
$successMessage = "";

// Lấy danh mục & nhà cung cấp
$danhMucQuery = "SELECT DM_ID, DM_Ten FROM danh_muc_sp";
$resultDanhMuc = $mysqli->query($danhMucQuery);

$nhaCCQuery = "SELECT NCC_ID, NCC_HoTen FROM nha_cung_cap";
$resultNCC = $mysqli->query($nhaCCQuery);

// Lấy thông tin sản phẩm
$sql = "SELECT SP_Ten, SP_Gia, DM_ID, NCC_ID FROM san_pham WHERE SP_ID = '$SP_ID'";
$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $SP_Ten = $row["SP_Ten"];
    $SP_Gia = $row["SP_Gia"];
    $DM_ID = $row["DM_ID"];
    $NCC_ID = $row["NCC_ID"];
} else {
    die("Không tìm thấy sản phẩm.");
}

// Xử lý cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $SP_Ten = test_input($_POST["SP_Ten"]);
    $SP_Gia = test_input($_POST["SP_Gia"]);
    $DM_ID = test_input($_POST["DM_ID"]);
    $NCC_ID = test_input($_POST["NCC_ID"]);

    $sql = "UPDATE san_pham SET SP_Ten='$SP_Ten', SP_Gia='$SP_Gia', DM_ID='$DM_ID', NCC_ID='$NCC_ID' WHERE SP_ID='$SP_ID'";

    if ($mysqli->query($sql) === TRUE) {
        header("Location: sp.php");
        exit;
    } else {
        $errorMessage = "Lỗi khi cập nhật sản phẩm: " . $mysqli->error;
    }
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="vi">
<body>
    <div class="all">
        <h2>✍️ Sửa SẢN PHẨM ✍️</h2>
        <?php if ($errorMessage) echo "<div class='alert alert-danger'>$errorMessage</div>"; ?>
        
        <form method="post">
            <div class="mb-3">
                <label>Tên Sản Phẩm</label>
                <input type="text" class="form-control" name="SP_Ten" value="<?php echo $SP_Ten; ?>" required>
            </div>
            <div class="mb-3">
                <label>Giá Sản Phẩm</label>
                <input type="number" class="form-control" name="SP_Gia" value="<?php echo $SP_Gia; ?>" required>
            </div>
            <div class="mb-3">
                <label>Danh mục</label>
                <select name="DM_ID" class="form-control">
                    <?php while ($row = $resultDanhMuc->fetch_assoc()) {
                        $selected = ($DM_ID == $row['DM_ID']) ? "selected" : "";
                        echo "<option value='" . $row['DM_ID'] . "' $selected>" . htmlspecialchars($row['DM_Ten']) . "</option>";
                    } ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Nhà cung cấp</label>
                <select name="NCC_ID" class="form-control">
                    <?php while ($row = $resultNCC->fetch_assoc()) {
                        $selected = ($NCC_ID == $row['NCC_ID']) ? "selected" : "";
                        echo "<option value='" . $row['NCC_ID'] . "' $selected>" . htmlspecialchars($row['NCC_HoTen']) . "</option>";
                    } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="sp.php" class="btn btn-outline-danger">Hủy</a>
        </form>
    </div>
</body>
</html>
