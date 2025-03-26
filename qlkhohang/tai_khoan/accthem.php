<?php
    require_once '../blocks/head.php';

    $name = "";
    $username = "";
    $password = "";
    $email = "";
    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST["TK_HoTen"]);
        $username = trim($_POST["TK_TenDangNhap"]);
        $password = trim($_POST["TK_MatKhau"]);
        $email = trim($_POST["TK_Email"]);

        if (empty($name) || empty($username) || empty($password) || empty($email)) {
            $errorMessage = "Vui lòng điền đầy đủ thông tin.";
        } else {
            $mysqli = new mysqli("localhost", "TK_TenDangNhap", "TK_MatKhau", "qlkhohang");
            if ($mysqli->connect_error) {
                die("Kết nối không thành công: " . $mysqli->connect_error);
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $mysqli->prepare("INSERT INTO tai_khoan (TK_TenDangNhap, TK_MatKhau, TK_Email, TK_VaiTro) VALUES (?, ?, ?, 'admin')");
            if (!$stmt) {
                die("Lỗi chuẩn bị câu lệnh SQL: " . $mysqli->error);
            }
            $stmt->bind_param("sss", $username, $hashedPassword, $email);
            if ($stmt->execute()) {
                $newTK_ID = $stmt->insert_id;

                $stmtAdmin = $mysqli->prepare("INSERT INTO admin (TK_ID, AD_HoTen) VALUES (?, ?)");
                if (!$stmtAdmin) {
                    die("Lỗi chuẩn bị câu lệnh SQL admin: " . $mysqli->error);
                }
                $stmtAdmin->bind_param("is", $newTK_ID, $name);
                if ($stmtAdmin->execute()) {
                    $successMessage = "Tài khoản và admin đã được tạo thành công.";
                    header("Location: acc.php");
                    exit;
                } else {
                    $errorMessage = "Lỗi khi thêm admin: " . $stmtAdmin->error;
                }
                $stmtAdmin->close();
            } else {
                $errorMessage = "Lỗi khi thêm tài khoản: " . $stmt->error;
            }
            $stmt->close();
            $mysqli->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="all">
            <h2>✅ Thêm TÀI KHOẢN ✅</h2>
            <?php
                if(!empty($errorMessage)){
                    echo "
                        <div class='toast align-items-center' role='alert' aria-live='assertive' aria-atomic='true'>
                            <div class='d-flex'>
                            <strong>$errorMessage</strong>
                            <button type='button' class='btn-close me-2 m-auto' data-bs-dismiss='toast' aria-label='Close'></button>
                            </div>
                        </div>
                    ";
                }
            ?>
            <form method="post" >
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Họ Tên</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" name ="TK_HoTen" id="inputPassword" value="<?php echo $name; ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label  class="col-sm-2 col-form-label">Tên Đăng Nhập</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" name="TK_TenDangNhap" id="inputPassword" value="<?php echo $username; ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Mật Khẩu</label>
                    <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword" name="TK_MatKhau" value="<?php echo $password; ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputPassword" name="TK_Email" value="<?php echo $email; ?>">
                    </div>
                </div>            
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                    <button type="submit" class="btn btn-success btn-lg">Thêm</button>
                    <a href="acc.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
            </form> 
            <span class="success"><?php echo $successMessage;?></span>
        </div>
    </body>
</html>