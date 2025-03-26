<?php
require_once '../blocks/head.php';

$TT_TenTT = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TT_TenTT = test_input($_POST["TT_TenTT"]);

    $mysqli = new mysqli("localhost", "TK_TenDangNhap", "TK_MatKhau", "qlkhohang");
    if ($mysqli->connect_error) {
        die("Kết nối không thành công: " . $mysqli->connect_error);
    }

    if (empty($errorMessage)) {
        $sql = "INSERT INTO trang_thai (TT_TenTT)
                VALUES ('$TT_TenTT')";
        if ($mysqli->query($sql) === TRUE) {
            $successMessage = "Thêm Trạng Thái Thành Công";
            header("Location: tthai.php");
            exit; 
        } else {
            echo "Đã xảy ra lỗi khi tạo Trạng Thái: " . $mysqli->error;
        }
    }

        $mysqli->close();
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
    <div class="all">
        <h2>✅ Thêm TRẠNG THÁI ✅</h2>
        <?php
            if (!empty($errorMessage)) {
                echo "
                    <div class='alert alert-danger'>$errorMessage</div>
                ";
            }
        ?>
        <form method="POST">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Tên Trạng Thái</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="TT_TenTT" id="inputTenTT" value="<?php echo $TT_TenTT; ?>" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success btn-lg">Thêm</button>
                    <a href="tthai.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                </div>
            </div>
        </form>
        <span class="success"><?php echo $successMessage; ?></span>
    </div>
</body>
</html>
