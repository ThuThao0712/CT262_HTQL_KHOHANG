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
        $sql = "SELECT *
                FROM trang_thai
                WHERE TT_TenTT LIKE ?";

        $stmt = $connection->prepare($sql);
        $search_param = "%$search%";
        $stmt->bind_param('s', $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT *
                FROM trang_thai";
        $result = $connection->query($sql);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <div class="wrapper">
        <?php include '../blocks/sidebar.php'; ?>
            <div class="main">
                <div class="tkad">
                    <div class="dstaikhoan">
                        <div class="tktenmuc">
                            <h2>Danh sách TRẠNG THÁI</h2>
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
                            <a href="tthaithem.php" class="btn btn-outline-success">Thêm</a>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Tên Trạng Thái</td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    if (!$result) {
                                        die("invalid query: ". $connection->error);
                                    }
                                    while($row = $result->fetch_assoc()){
                                        echo "
                                    <tr>
                                        <td>$row[TT_ID]</td>
                                        <td>$row[TT_TenTT]</td>
                                        <td>
                                            <a href='../trang_thai/tthaisua.php?TT_ID=$row[TT_ID]' class='btn btn-outline-primary'>Sửa</a>
                                            <a href='../trang_thai/tthaixoa.php?TT_ID=$row[TT_ID]' class='btn btn-outline-danger'>Xóa</a>
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
