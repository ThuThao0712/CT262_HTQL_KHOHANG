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

    $PX_ID = isset($_GET['PX_ID']) ? $_GET['PX_ID'] : null;

    if (!$PX_ID) {
        die("Không có PX_ID trong URL");
    }

    $sql_px = "SELECT PX_NgayXuat FROM phieu_xuat WHERE PX_ID = '$PX_ID'";
    $result_px = $connection->query($sql_px);

    if ($result_px && $result_px->num_rows > 0) {
        $row_px = $result_px->fetch_assoc();
        $PX_NgayXuat = $row_px["PX_NgayXuat"];
    } else {
        die("Không tìm thấy phiếu xuất với PX_ID: $PX_ID");
    }

    $sql_ctpx = "SELECT ctpx.*, sp.SP_Ten
                FROM chi_tiet_phieu_xuat ctpx
                JOIN san_pham sp ON ctpx.SP_ID = sp.SP_ID
                WHERE ctpx.PX_ID = '$PX_ID'";
    $result_ctpx = $connection->query($sql_ctpx);

    if (!$result_ctpx) {
        die("Truy vấn thất bại: " . $connection->error);
    }
?>

<!DOCTYPE html>
<html lang="vi">
    <body>
        <div class="wrapper">
            <div class="tkad">
                <div class="dstaikhoan">
                    <div class="tktenmuc">
                        <h2 style="color: blue;">Chi tiết Phiếu Xuất Ngày <?php echo htmlspecialchars(date('d/m/Y', strtotime($PX_NgayXuat))); ?></h2>
                        <a href="export_excel_xuat.php?PX_ID=<?php echo $PX_ID; ?>" class="btn btn-success">Xuất Excel</a>
                        <a href="lich_su_xuat.php" class="btn btn-outline-secondary">Trở Lại</a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên Sản Phẩm</th>
                                <th>Số Lượng Xuất</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_ctpx->num_rows > 0) {
                                while ($row = $result_ctpx->fetch_assoc()) {
                                    echo "
                                    <tr>
                                        <td>{$row['SP_Ten']}</td>
                                        <td>{$row['CTPX_SoLuong']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>Không có dữ liệu.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../main.js"></script>
    </body>
</html>
