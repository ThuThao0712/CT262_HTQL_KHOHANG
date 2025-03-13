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

    // Xử lý tìm kiếm
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';
    
    $sql = "SELECT phieu_xuat.PX_ID, phieu_xuat.PX_NgayXuat, phieu_xuat.PX_GioXuat, 
    nhan_vien.NV_HoTen, trang_thai.TT_Ten, kho_hang.KHO_Ten
    FROM phieu_xuat
    LEFT JOIN nhan_vien ON phieu_xuat.NV_ID = nhan_vien.NV_ID 
    LEFT JOIN trang_thai ON phieu_xuat.TT_ID = trang_thai.TT_ID
    LEFT JOIN kho_hang ON phieu_xuat.KHO_ID = kho_hang.KHO_ID
    WHERE (phieu_xuat.PX_NgayXuat LIKE '%$search%' 
    OR nhan_vien.NV_HoTen LIKE '%$search%' 
    OR EXISTS (SELECT 1 FROM chi_tiet_phieu_xuat ctpn2 
            JOIN san_pham sp ON ctpn2.SP_ID = sp.SP_ID 
            WHERE ctpn2.PX_ID = phieu_xuat.PX_ID AND sp.SP_Ten LIKE '%$search%'))";

    if (!empty($date_filter)) {
    $sql .= " AND phieu_xuat.PX_NgayXuat = '$date_filter'";
    }

    $sql .= " GROUP BY phieu_xuat.PX_ID ORDER BY phieu_xuat.PX_NgayXuat DESC";

    
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
                        <div class="tktenmuc d-flex justify-content-between align-items-center">
                            <h2>Lịch Sử Xuất Hàng</h2>
                            <a href="xuathang_them.php" class="btn btn-success">+ Thêm Phiếu Xuất</a>
                        </div>

                        <form action="" method="GET" class="search-form">
                            <div class="row">
                                <div class="col">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm theo sản phẩm hoặc nhân viên" value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                                <div class="col">
                                    <input type="date" name="date_filter" class="form-control" value="<?php echo htmlspecialchars($date_filter); ?>">
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-outline-dark">Tìm kiếm</button>
                                </div>
                            </div>
                        </form>

                        <table class="table table-striped mt-3">
                            <thead>
                                <tr>
                                    <td>ID Phiếu Xuất</td>
                                    <td>Ngày Xuất</td>
                                    <td>Giờ Xuất</td>
                                    <td>Nhân Viên</td>
                                    <td>Trạng Thái</td>
                                    <td>Kho Nhận</td>
                                    <td>Chi Tiết</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "
                                        <tr>
                                            <td>{$row['PX_ID']}</td>
                                            <td>{$row['PX_NgayXuat']}</td>
                                            <td>{$row['PX_GioXuat']}</td>
                                            <td>{$row['NV_HoTen']}</td>
                                            <td>{$row['TT_Ten']}</td>
                                            <td>{$row['KHO_Ten']}</td>
                                            <td>
                                                <a href='chitiet_xuat.php?PX_ID={$row['PX_ID']}' class='btn btn-outline-primary'>Xem</a>
                                            </td>
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
