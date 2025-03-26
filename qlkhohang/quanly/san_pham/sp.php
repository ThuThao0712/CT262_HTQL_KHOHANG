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

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT sp.SP_ID, sp.SP_Ten, sp.SP_Gia, dm.DM_Ten, ncc.NCC_HoTen, 
               COALESCE(kho_cn.KCN_SoLuong, 0) AS KCN_SoLuong 
        FROM san_pham sp
        JOIN danh_muc_sp dm ON sp.DM_ID = dm.DM_ID
        JOIN nha_cung_cap ncc ON sp.NCC_ID = ncc.NCC_ID
        LEFT JOIN kho_cn ON sp.SP_ID = kho_cn.SP_ID AND kho_cn.KHO_ID = 1";

if (!empty($search)) {
    $sql .= " WHERE (sp.SP_Ten LIKE '%$search%' OR dm.DM_Ten LIKE '%$search%')";
}

$sql .= " ORDER BY sp.SP_ID ASC";

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
                        <a href="spthem.php" class="btn btn-outline-success">Thêm</a>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Tên Sản Phẩm</td>
                                <td>Danh Mục</td>
                                <td>Nhà Cung Cấp</td>
                                <td>Số Lượng Tồn Kho</td>
                                <td>Hành Động</td>
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
                                    <td>
                                        <a href='../san_pham/spsua.php?SP_ID={$row['SP_ID']}' class='btn btn-outline-primary'>Sửa</a>
                                        <a href='../san_pham/spxoa.php?SP_ID={$row['SP_ID']}' class='btn btn-outline-danger'>Xóa</a>
                                    </td>
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
