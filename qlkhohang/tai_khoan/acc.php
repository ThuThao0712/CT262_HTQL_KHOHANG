<?php
    require_once '../blocks/head.php';

    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "qlkhohang"; 

    // Kết nối database
    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Kết nối không thành công: " . $connection->connect_error);
    }

    // Xử lý tìm kiếm theo họ tên nhân viên
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $sql = "SELECT * FROM nhan_vien 
                WHERE NV_HoTen LIKE ?";
        $stmt = $connection->prepare($sql);
        $search_param = "%$search%";
        $stmt->bind_param('s', $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT * FROM nhan_vien";
        $result = $connection->query($sql);
    }
?>

<!DOCTYPE html>
<html lang="vi">
    <body>
        <div class="wrapper">
            <?php include '../blocks/sidebar.php'; ?>
            <div class="main">
                <div class="tkad">
                    <div class="dstaikhoan">
                        <div class="tktenmuc">
                            <h2>Danh sách Nhân Viên</h2>
                            <form action="" method="GET" class="search-form">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo họ tên">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-outline-dark">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                            <a href="nhanvienthem.php" class="btn btn-outline-success">Thêm</a>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <td>ID Nhân Viên</td>
                                    <td>Họ Tên</td>
                                    <td>Giới Tính</td>
                                    <td>Email</td>
                                    <td>Số Điện Thoại</td>
                                    <td>Hành động</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (!$result) {
                                        die("Truy vấn không hợp lệ: ". $connection->error);
                                    }
                                    while($row = $result->fetch_assoc()){
                                        echo "
                                    <tr>
                                        <td>{$row['NV_ID']}</td>
                                        <td>{$row['NV_HoTen']}</td>
                                        <td>{$row['NV_GioiTinh']}</td>                                    
                                        <td>{$row['NV_Email']}</td>                                    
                                        <td>{$row['NV_SDT']}</td>
                                        <td>
                                            <a href='../nhanvien/nvsua.php?NV_ID={$row['NV_ID']}' class='btn btn-outline-primary'>Sửa</a>
                                            <a href='../nhanvien/nvxoa.php?NV_ID={$row['NV_ID']}' class='btn btn-outline-danger'>Xóa</a>
                                        </td>
                                    </tr>
                                        ";
                                    } 
                                ?>
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
