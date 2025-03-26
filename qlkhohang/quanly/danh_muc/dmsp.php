<?php
    require_once '../blocks/head.php';

    $servername = "localhost"; 
    $username = "TK_TenDangNhap"; 
    $password = "TK_MatKhau"; 
    $dbname = "qlkhohang"; 


    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Kết nối không thành công: " . $connection->connect_error);
    }
    
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $sql = "SELECT * FROM danh_muc_sp WHERE DM_Ten LIKE '%$search%'";
    } else {
        $sql = "SELECT * FROM danh_muc_sp"; 
    }
    
    $result = $connection->query($sql);

    if (!$result) {
        die("Invalid query: " . $connection->error);
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

                <div class="tkad">
                    <div class="dstaikhoan">
                        <div class="tktenmuc">
                            <h2>Danh sách DANH MỤC</h2>
                            <form action="" method="GET" class="search-form">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-outline-dark">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                            <a href="dmspthem.php" class="btn btn-outline-success">Thêm</a>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Danh Mục</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['DM_ID']; ?></td>
                                    <td><?php echo $row['DM_Ten']; ?></td>
                                    <td>
                                        <a href='../danh_muc/dmspsua.php?DM_ID=<?php echo $row['DM_ID']; ?>' class='btn btn-outline-primary'>Sửa</a>
                                        <a href='../danh_muc/dmspxoa.php?DM_ID=<?php echo $row['DM_ID']; ?>' class='btn btn-outline-danger'>Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../main.js"></script>
    </body>
</html>
