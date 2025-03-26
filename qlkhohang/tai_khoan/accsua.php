<?php
    require_once '../blocks/head.php';
    $servername = "localhost"; 
    $username = "TK_TenDangNhap"; 
    $password = "TK_MatKhau"; 
    $dbname = "qlkhohang";

    $connection = new mysqli($servername, $username, $password, $dbname);

    $TK_ID = $_GET['TK_ID'];
    $select = "SELECT * FROM tai_khoan WHERE TK_ID='$TK_ID'";
    $data = mysqli_query($connection, $select);
    $row = mysqli_fetch_array($data);

    $vaiTro = $row['TK_VaiTro'];

    if (isset($_POST['update-btn'])) {
        $TK_TenDangNhap = $_POST['TK_TenDangNhap'];
        $TK_MatKhau = $_POST['TK_MatKhau'];
        $TK_Email = $_POST['TK_Email'];

        $update = "UPDATE tai_khoan SET TK_TenDangNhap='$TK_TenDangNhap', TK_MatKhau='$TK_MatKhau', TK_Email='$TK_Email' WHERE TK_ID='$TK_ID'";
        $data = mysqli_query($connection, $update);

        if ($vaiTro == 'admin') {
            $AD_HoTen = $_POST['AD_HoTen'];
            $updateAdmin = "UPDATE admin SET AD_HoTen='$AD_HoTen' WHERE TK_ID='$TK_ID'";
            mysqli_query($connection, $updateAdmin);
        }

        if ($data) {
            header("Location: acc.php");
        } else {
            echo "Đã xảy ra lỗi khi cập nhật tài khoản.";
        }

        $mysqli->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="all">
            <h2>✍️ Sửa TÀI KHOẢN ✍️</h2>
            <form method="post">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Họ Tên</label>
                    <div class="col-sm-10">
                        <?php
                            if ($vaiTro == 'admin') {
                                $resultAdmin = mysqli_query($connection, "SELECT * FROM admin WHERE TK_ID='$TK_ID'");
                                $admin = mysqli_fetch_array($resultAdmin);
                                echo '<input type="text" class="form-control" name="AD_HoTen" value="' . $admin['AD_HoTen'] . '">';
                            } else {
                                $resultClient = mysqli_query($connection, "SELECT * FROM khach_hang WHERE TK_ID='$TK_ID'");
                                $client = mysqli_fetch_array($resultClient);
                                echo '<p class="form-control">' . $client['KH_HoTen'] . '</p>';
                            }
                        ?>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Tên Đăng Nhập</label>
                    <div class="col-sm-10">
                        <?php
                            if ($vaiTro == 'admin') {
                                echo '<input type="text" class="form-control" name="TK_TenDangNhap" value="' . $row['TK_TenDangNhap'] . '">';
                            } else {
                                echo '<p class="form-control">' . $row['TK_TenDangNhap'] . '</p>';
                            }
                        ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Mật Khẩu</label>
                    <div class="col-sm-10">
                        <?php
                            if ($vaiTro == 'admin') {
                                echo '<input type="text" class="form-control" name="TK_MatKhau" value="' . $row['TK_MatKhau'] . '">';
                            } else {
                                echo '<p class="form-control">' . $row['TK_MatKhau'] . '</p>';
                            }
                        ?>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <?php
                            if ($vaiTro == 'admin') {
                                echo '<input type="email" class="form-control" name="TK_Email" value="' . $row['TK_Email'] . '">';
                            } else {
                                echo '<p class="form-control">' . $row['TK_Email'] . '</p>';
                            }
                        ?>
                    </div>
                </div>

                <?php
                    if ($vaiTro == 'client') {
                        echo '
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Địa Chỉ</label>
                            <div class="col-sm-10">
                                <p class="form-control">' . $client['KH_DiaChi'] . '</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Tỉnh Thành</label>
                            <div class="col-sm-10">
                                <p class="form-control">' . $client['KH_TinhThanh'] . '</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Số Điện Thoại</label>
                            <div class="col-sm-10">
                                <p class="form-control">' . $client['KH_SDT'] . '</p>
                            </div>
                        </div>';
                    }
                ?>

                <div class="mb-3 row">
                    <div class="col-sm-10">
                        <?php if ($vaiTro == 'admin') {
                            echo '<button type="submit" name="update-btn" class="btn btn-primary btn-lg">Cập Nhật</button>';
                        } ?>
                        <a href="acc.php" class="btn btn-outline-danger btn-lg">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>