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

    // Xử lý tìm kiếm theo địa chỉ kho
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $sql = "SELECT kho_hang.*, quan_ly.QL_HoTen, trang_thai.TT_Ten 
                FROM kho_hang 
                LEFT JOIN quan_ly ON kho_hang.QL_ID = quan_ly.QL_ID
                LEFT JOIN trang_thai ON kho_hang.TT_ID = trang_thai.TT_ID
                WHERE KHO_DiaChi LIKE '%$search%' 
                ORDER BY KHO_ID ASC";
    } else {
        $sql = "SELECT kho_hang.*, quan_ly.QL_HoTen, trang_thai.TT_Ten 
                FROM kho_hang 
                LEFT JOIN quan_ly ON kho_hang.QL_ID = quan_ly.QL_ID
                LEFT JOIN trang_thai ON kho_hang.TT_ID = trang_thai.TT_ID
                ORDER BY KHO_ID ASC";
    }

    $result = $connection->query($sql);
?>

<!DOCTYPE html>
<html>
    <body>
        <div class="wrapper">
            <?php include '../blocks/sidebar.php'; ?>
            <div class="main">
                <div class="tkad">
                    <div class="dstaikhoan">
                        <div class="tktenmuc">
                            <h2>Kho Hàng</h2>
                            <form action="" method="GET" class="search-form">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm theo địa chỉ" aria-label="First name">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-outline-dark">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                        
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <td>ID Kho</td>
                                    <td>Tên Kho</td>
                                    <td>Số Lượng</td>
                                    <td>Diện Tích</td>
                                    <td>Địa Chỉ</td>
                                    <td>Quản Lý</td>
                                    <td>Trạng Thái</td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "
                                        <tr>
                                            <td>{$row['KHO_ID']}</td>
                                            <td>{$row['KHO_Ten']}</td>
                                            <td>{$row['KHO_SoLuong']}</td>
                                            <td>{$row['KHO_DienTich']}</td>
                                            <td>{$row['KHO_DiaChi']}</td>
                                            <td>{$row['QL_HoTen']}</td>
                                            <td>{$row['TT_Ten']}</td>
        
                                        </tr>
                                        ";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>Không có dữ liệu</td></tr>";
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
