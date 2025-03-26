<?php
    require_once '../blocks/head.php';

    $servername = "localhost"; 
    $username = "TK_TenDangNhap"; 
    $password = "TK_MatKhau"; 
    $dbname = "qlkhohang"; 

    // Kết nối database
    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Kết nối không thành công: " . $connection->connect_error);
    }

    // Xử lý tìm kiếm theo tên nhà cung cấp
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $sql = "SELECT * FROM nha_cung_cap WHERE NCC_HoTen LIKE ?";
        $stmt = $connection->prepare($sql);
        $search_param = "%$search%";
        $stmt->bind_param('s', $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT * FROM nha_cung_cap";
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
                            <h2>Danh sách Nhà Cung Cấp</h2>
                            <!-- Form tìm kiếm -->
                            <form action="" method="GET" class="search-form">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="search" class="form-control" placeholder="Nhập tên nhà cung cấp">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-outline-dark">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                            <a href="nccthem.php" class="btn btn-outline-success">Thêm Nhà Cung Cấp</a>
                        </div>

                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Nhà Cung Cấp</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Địa Chỉ</th>
                                    <th>Email</th>
                                    <th>Hành động</th>
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
                                        <td>{$row['NCC_ID']}</td>
                                        <td>{$row['NCC_HoTen']}</td>
                                        <td>{$row['NCC_SDT']}</td>
                                        <td>{$row['NCC_DiaChi']}</td>
                                        <td>{$row['NCC_Email']}</td>
                                        <td>
                                            <a href='../nha_cung_cap/nccsua.php?NCC_ID={$row['NCC_ID']}' class='btn btn-outline-primary'>Sửa</a>
                                            <a href='../nha_cung_cap/nccxoa.php?NCC_ID={$row['NCC_ID']}' class='btn btn-outline-danger' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>
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
