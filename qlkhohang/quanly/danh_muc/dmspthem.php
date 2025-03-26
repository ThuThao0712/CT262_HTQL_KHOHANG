<?php
    require_once '../blocks/head.php';

    $DM_Ten = "";
    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["DM_Ten"])) {
            $errorMessage = "Tên danh mục không được để trống";
        } else {
            $DM_Ten = test_input($_POST["DM_Ten"]);
        }

        if (empty($errorMessage)) {
            $mysqli = new mysqli("localhost", "TK_TenDangNhap", "TK_MatKhau", "qlkhohang"); //chỉnh kết nối 
            if ($mysqli->connect_error) {
                die("Kết nối không thành công: " . $mysqli->connect_error);
            }

            $sql = "INSERT INTO danh_muc_sp (DM_Ten) VALUES ('$DM_Ten')";
            if ($mysqli->query($sql) === TRUE) {
                $successMessage = "Thêm danh mục thành công!";
                header("Location: dmsp.php");
                exit;
            } else {
                $errorMessage = "Lỗi khi thêm danh mục: " . $mysqli->error;
            }

            $mysqli->close();
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="vi">
    <body>
        <div class="all">
            <h2>✅ Thêm DANH MỤC ✅</h2>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tên Danh Mục</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="DM_Ten" value="<?php echo $DM_Ten; ?>" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-success btn-lg">Thêm</button>
                        <a href="dmsp.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
                </div>
            </form>

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>
        </div>
    </body>
</html>
