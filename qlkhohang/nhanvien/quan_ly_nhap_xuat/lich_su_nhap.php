<?php
session_start();
require_once '../blocks/head.php';

// Kiểm tra nếu nhân viên chưa đăng nhập
if (!isset($_SESSION['NV_ID']) || !isset($_SESSION['KHO_ID'])) {
    die("Bạn chưa đăng nhập hoặc không có quyền truy cập!");
}

// Lấy KHO_ID của nhân viên từ session
$KHO_ID = $_SESSION['KHO_ID'];

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
$search = isset($_GET['search']) ? $_GET['search'] : '';
$date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';

$sql = "SELECT 
    phieu_nhap.PN_ID, 
    phieu_nhap.PN_NgayLap, 
    nhan_vien.NV_HoTen, 
    nha_cung_cap.NCC_HoTen, 
    phieu_nhap.PN_TongSoLuongNhap, 
    phieu_nhap.PN_TongTienNhap, 
    kho_hang.KHO_Ten,
    GROUP_CONCAT(DISTINCT san_pham.SP_Ten SEPARATOR ', ') AS DanhSachSanPham
FROM phieu_nhap 
LEFT JOIN nhan_vien ON phieu_nhap.NV_ID = nhan_vien.NV_ID 
LEFT JOIN nha_cung_cap ON phieu_nhap.NCC_ID = nha_cung_cap.NCC_ID
LEFT JOIN chi_tiet_phieu_nhap ctpn ON phieu_nhap.PN_ID = ctpn.PN_ID
LEFT JOIN san_pham ON ctpn.SP_ID = san_pham.SP_ID
LEFT JOIN kho_cn ON ctpn.SP_ID = kho_cn.SP_ID
LEFT JOIN kho_hang ON kho_cn.KHO_ID = kho_hang.KHO_ID
WHERE kho_cn.KHO_ID = ?";  // Chỉ lấy dữ liệu từ kho của nhân viên

// Thêm điều kiện tìm kiếm nếu có
if (!empty($search)) {
    $sql .= " AND (phieu_nhap.PN_NgayLap LIKE ? 
                OR nha_cung_cap.NCC_HoTen LIKE ? 
                OR EXISTS (SELECT 1 FROM chi_tiet_phieu_nhap ctpn2 
                    JOIN san_pham sp ON ctpn2.SP_ID = sp.SP_ID 
                    WHERE ctpn2.PN_ID = phieu_nhap.PN_ID AND sp.SP_Ten LIKE ?))";
}

// Lọc theo ngày nếu có
if (!empty($date_filter)) {
    $sql .= " AND phieu_nhap.PN_NgayLap = ?";
}

$sql .= " GROUP BY phieu_nhap.PN_ID ORDER BY phieu_nhap.PN_NgayLap DESC";

// Chuẩn bị truy vấn
$stmt = $connection->prepare($sql);

// Gán giá trị cho truy vấn
if (!empty($search) && !empty($date_filter)) {
    $search_param = "%$search%";
    $stmt->bind_param("isss", $KHO_ID, $search_param, $search_param, $search_param, $date_filter);
} elseif (!empty($search)) {
    $search_param = "%$search%";
    $stmt->bind_param("isss", $KHO_ID, $search_param, $search_param, $search_param);
} elseif (!empty($date_filter)) {
    $stmt->bind_param("is", $KHO_ID, $date_filter);
} else {
    $stmt->bind_param("i", $KHO_ID);
}

$stmt->execute();
$result = $stmt->get_result();
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
                            <h2>Lịch Sử Nhập Hàng</h2>
                            <a href="nhaphang_them.php" class="btn btn-primary">Tạo Phiếu Nhập</a>
                            <form action="" method="GET" class="search-form">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm theo sản phẩm hoặc nhà cung cấp" value="<?php echo htmlspecialchars($search); ?>">
                                    </div>
                                    <div class="col">
                                        <input type="date" name="date_filter" class="form-control" value="<?php echo htmlspecialchars($date_filter); ?>">
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
                                <td>ID Phiếu Nhập</td>
                                <td>Ngày Lập</td>
                                <td>Nhân Viên</td>
                                <td>Nhà Cung Cấp</td>
                                <td>Tổng Số Lượng</td>
                                <td>Kho Nhập</td>
                                <td>Sản Phẩm</td>
                                <td>Chi Tiết</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "
                                    <tr>
                                        <td>{$row['PN_ID']}</td>
                                        <td>{$row['PN_NgayLap']}</td>
                                        <td>{$row['NV_HoTen']}</td>
                                        <td>{$row['NCC_HoTen']}</td>
                                        <td>{$row['PN_TongSoLuongNhap']}</td>
                                        <td>{$row['KHO_Ten']}</td>
                                        <td>{$row['DanhSachSanPham']}</td>
                                        <td><a href='chitiet_nhap.php?PN_ID={$row['PN_ID']}' class='btn btn-outline-primary'>Xem</a></td>
                                    </tr>
                                    ";
                                }
                            } else {
                                echo "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
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