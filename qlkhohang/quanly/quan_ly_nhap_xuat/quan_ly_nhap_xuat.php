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

    // Xử lý tìm kiếm
    $whereCondition = "";
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $whereCondition = "WHERE san_pham.SP_Ten LIKE '%$search%' OR phieu_nhap.PN_NgayLap LIKE '%$search%' OR phieu_xuat.PX_NgayXuat LIKE '%$search%'";
    }

    // Lấy dữ liệu nhập hàng
    $sqlNhap = "SELECT phieu_nhap.*, san_pham.SP_Ten, nha_cung_cap.NCC_HoTen FROM phieu_nhap 
                LEFT JOIN san_pham ON phieu_nhap.SP_ID = san_pham.SP_ID 
                LEFT JOIN nha_cung_cap ON phieu_nhap.NCC_ID = nha_cung_cap.NCC_ID $whereCondition ORDER BY PN_NgayLap DESC";
    $resultNhap = $connection->query($sqlNhap);

    // Lấy dữ liệu xuất hàng
    $sqlXuat = "SELECT phieu_xuat.*, kho_cn.KCN_ID, nhan_vien.NV_HoTen FROM phieu_xuat 
                LEFT JOIN kho_cn ON phieu_xuat.KCN_ID = kho_cn.KCN_ID 
                LEFT JOIN nhan_vien ON phieu_xuat.NV_ID = nhan_vien.NV_ID $whereCondition ORDER BY PX_NgayXuat DESC";
    $resultXuat = $connection->query($sqlXuat);
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
                            <h2>Lịch Sử Nhập Xuất Kho</h2>
                            <form action="" method="GET" class="search-form">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm theo sản phẩm hoặc ngày" aria-label="Search">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-outline-dark">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <h3>Danh sách Nhập Hàng</h3>
                        <table>
                            <thead>
                                <tr>
                                    <td>ID Phiếu Nhập</td>
                                    <td>Ngày Lập</td>
                                    <td>Giờ Lập</td>
                                    <td>Tổng Số Lượng</td>
                                    <td>Nhà Cung Cấp</td>
                                    <td>Sản Phẩm</td>
                                    <td>Hành động</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultNhap->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['PN_ID']; ?></td>
                                        <td><?php echo $row['PN_NgayLap']; ?></td>
                                        <td><?php echo $row['PN_GioLap']; ?></td>
                                        <td><?php echo $row['PN_TongSoLuongNhap']; ?></td>
                                        <td><?php echo $row['NCC_HoTen']; ?></td>
                                        <td><?php echo $row['SP_Ten']; ?></td>
                                        <td>
                                            <a href='chitiet_phieunhap.php?PN_ID=<?php echo $row['PN_ID']; ?>' class='btn btn-outline-info'>Xem Chi Tiết</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <h3>Danh sách Xuất Hàng</h3>
                        <table>
                            <thead>
                                <tr>
                                    <td>ID Phiếu Xuất</td>
                                    <td>Ngày Xuất</td>
                                    <td>Giờ Xuất</td>
                                    <td>Ngày Hoàn Thành</td>
                                    <td>Kho</td>
                                    <td>Nhân Viên</td>
                                    <td>Hành động</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultXuat->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['PX_ID']; ?></td>
                                        <td><?php echo $row['PX_NgayXuat']; ?></td>
                                        <td><?php echo $row['PX_GioXuat']; ?></td>
                                        <td><?php echo $row['PX_NgayHoanThanh']; ?></td>
                                        <td><?php echo $row['KCN_ID']; ?></td>
                                        <td><?php echo $row['NV_HoTen']; ?></td>
                                        <td>
                                            <a href='chitiet_phieuxuat.php?PX_ID=<?php echo $row['PX_ID']; ?>' class='btn btn-outline-info'>Xem Chi Tiết</a>
                                        </td>
                                    </tr>
                                <?php } ?>
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
