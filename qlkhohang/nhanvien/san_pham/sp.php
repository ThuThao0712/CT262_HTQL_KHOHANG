<?php
require_once '../blocks/head.php';
session_start(); // Bắt đầu session để lấy thông tin nhân viên

$servername = "localhost"; 
$username = "TK_TenDangNhap"; 
$password = "TK_MatKhau"; 
$dbname = "qlkhohang"; 

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Kết nối không thành công: " . $connection->connect_error);
}

// Lấy NV_ID từ session (giả sử bạn đã lưu khi đăng nhập)
$NV_ID = isset($_SESSION['NV_ID']) ? $_SESSION['NV_ID'] : 0;

if ($NV_ID == 0) {
    die("Lỗi: Không xác định được nhân viên.");
}

// Lấy KHO_ID của nhân viên
$stmt = $connection->prepare("SELECT KHO_ID FROM nhan_vien WHERE NV_ID = ?");
$stmt->bind_param("i", $NV_ID);
$stmt->execute();
$stmt->bind_result($KHO_ID);
$stmt->fetch();
$stmt->close();

if (!$KHO_ID) {
    die("Lỗi: Nhân viên không có kho hàng được phân công.");
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT sp.SP_ID, sp.SP_Ten, sp.SP_Gia, dm.DM_Ten, ncc.NCC_HoTen, 
               COALESCE(kho_cn.KCN_SoLuong, 0) AS KCN_SoLuong 
        FROM san_pham sp
        JOIN danh_muc_sp dm ON sp.DM_ID = dm.DM_ID
        JOIN nha_cung_cap ncc ON sp.NCC_ID = ncc.NCC_ID
        LEFT JOIN kho_cn ON sp.SP_ID = kho_cn.SP_ID 
        WHERE kho_cn.KHO_ID = ?";

if (!empty($search)) {
    $sql .= " AND (sp.SP_Ten LIKE ? OR dm.DM_Ten LIKE ?)";
}

$sql .= " ORDER BY sp.SP_ID ASC";

$stmt = $connection->prepare($sql);

if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param("iss", $KHO_ID, $searchParam, $searchParam);
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
                        <h2>DANH SÁCH SẢN PHẨM</h2>
                        <form action="" method="GET" class="search-form">
                            <div class="row">
                                <div class="col">
                                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm">
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-outline-dark">Tìm kiếm</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Tên Sản Phẩm</td>
                                <td>Danh Mục</td>
                                <td>Nhà Cung Cấp</td>
                                <td>Số Lượng Tồn Kho</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if (!$result) {
                                die("Lỗi truy vấn: " . $connection->error);
                            }
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <tr>
                                    <td>{$row['SP_ID']}</td>
                                    <td>{$row['SP_Ten']}</td>
                                    <td>{$row['DM_Ten']}</td>
                                    <td>{$row['NCC_HoTen']}</td>
                                    <td>{$row['KCN_SoLuong']}</td>

                                </tr>";
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
