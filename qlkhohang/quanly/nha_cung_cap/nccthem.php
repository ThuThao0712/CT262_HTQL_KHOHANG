<?php
    require_once '../blocks/head.php';

    $NCC_HoTen = $NCC_SDT = $NCC_DiaChi = $NCC_Email = "";
    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $NCC_HoTen = test_input($_POST["NCC_HoTen"]);
        $NCC_SDT = test_input($_POST["NCC_SDT"]);
        $NCC_DiaChi = test_input($_POST["NCC_DiaChi"]);
        $NCC_Email = test_input($_POST["NCC_Email"]);

        $mysqli = new mysqli("localhost", "root", "", "qlkhohang"); // chỉnh cái này
        
        if ($mysqli->connect_error) {
            die("Kết nối không thành công: " . $mysqli->connect_error);
        }

        if (empty($errorMessage)) {
            $sql = "INSERT INTO nha_cung_cap (NCC_HoTen, NCC_SDT, NCC_DiaChi, NCC_Email) 
                    VALUES ('$NCC_HoTen', '$NCC_SDT', '$NCC_DiaChi', '$NCC_Email')";
            if ($mysqli->query($sql) === TRUE) {
                $successMessage = "Thêm Nhà Cung Cấp Thành Công";
                header("Location: ncc.php");
                exit; 
            } else {
                echo "Đã xảy ra lỗi khi thêm nhà cung cấp: " . $mysqli->error;
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
<html lang="vi">
    <body>
        <div class="all">
            <h2>✅ Thêm NHÀ CUNG CẤP ✅</h2>

            <form method="POST">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tên Nhà Cung Cấp</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="NCC_HoTen" required>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Số Điện Thoại</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="NCC_SDT" required>
                    </div>
                </div>           
                
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Địa Chỉ</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="NCC_DiaChi" required>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="NCC_Email" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-success btn-lg">Thêm</button>
                        <a href="ncc.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
                </div>
            </form> 

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>
