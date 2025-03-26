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

// Xử lý tìm kiếm theo địa chỉ kho
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT kho_hang.KHO_ID, kho_hang.KHO_Ten, kho_hang.KHO_DienTich, kho_hang.KHO_DiaChi, 
                   quan_ly.QL_HoTen, trang_thai.TT_Ten, 
                   COALESCE(SUM(kho_cn.KCN_SoLuong), 0) AS TongSoLuong
            FROM kho_hang
            LEFT JOIN quan_ly ON kho_hang.KHO_ID = quan_ly.KHO_ID
            LEFT JOIN trang_thai ON kho_hang.TT_ID = trang_thai.TT_ID
            LEFT JOIN kho_cn ON kho_hang.KHO_ID = kho_cn.KHO_ID
            WHERE kho_hang.KHO_DiaChi LIKE '%$search%' 
            GROUP BY kho_hang.KHO_ID
            ORDER BY kho_hang.KHO_ID ASC";
} else {
    $sql = "SELECT kho_hang.KHO_ID, kho_hang.KHO_Ten, kho_hang.KHO_DienTich, kho_hang.KHO_DiaChi, 
                   quan_ly.QL_HoTen, trang_thai.TT_Ten, 
                   COALESCE(SUM(kho_cn.KCN_SoLuong), 0) AS TongSoLuong
            FROM kho_hang
            LEFT JOIN quan_ly ON kho_hang.KHO_ID = quan_ly.KHO_ID
            LEFT JOIN trang_thai ON kho_hang.TT_ID = trang_thai.TT_ID
            LEFT JOIN kho_cn ON kho_hang.KHO_ID = kho_cn.KHO_ID
            GROUP BY kho_hang.KHO_ID
            ORDER BY kho_hang.KHO_ID ASC";
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
                                <td>Tổng Số Lượng</td>
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
                                        <td>{$row['TongSoLuong']}</td>
                                        <td>{$row['KHO_DienTich']}</td>
                                        <td>{$row['KHO_DiaChi']}</td>
                                        <td>{$row['QL_HoTen']}</td>
                                        <td>{$row['TT_Ten']}</td>
                                    </tr>
                                    ";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
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
