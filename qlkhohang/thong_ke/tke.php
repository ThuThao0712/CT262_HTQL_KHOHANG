<?php
    require_once '../blocks/head.php';

    $servername = "localhost"; 
    $username = "TK_TenDangNhap"; 
    $password = "TK_MatKhau"; 
    $dbname = "qlkhohang"; 

    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
?>

<!DOCTYPE html>
<html lang="vi">
    <body>
        <div class="wrapper">
            <?php include '../blocks/sidebar.php'; ?>
            <div class="main">
                <div class="topbar">
                    <div class="toggle">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                    <div class="user">
                        <img src="assets/imgs/customer01.jpg" alt="">
                    </div>
                </div>
                <div class="container overflow-hidden text-center">
                <div class="row gx-5">
                    <div class="col">
                        <div class="p-3">
                            <a href="get_tkdh.php" class="btn btn-success p-3">
                                <span>Thống Kê Đơn Hàng</span>
                            </a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-3">
                            <a href="get_tknh.php" class="btn btn-warning p-3">
                                <span>Thống Kê Nhập Hàng</span>
                            </a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-3">
                            <a href="get_tkproduct.php" class="btn btn-primary p-3">
                                <span>Thống Kê SP Bán Ra</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="../main.js"></script>
        </div>
    </body>
</html>
