<?php
    require_once '../blocks/head.php';

    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "qlkhohang"; 

    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Kết nối không thành công: " . $connection->connect_error);
    }

    $PN_ID = isset($_GET['PN_ID']) ? $_GET['PN_ID'] : null;

    if (!$PN_ID) {
        die("Không có PN_ID trong URL");
    }

    $sql_pn = "SELECT PN_NgayLap FROM phieu_nhap WHERE PN_ID = '$PN_ID'";
    $result_pn = $connection->query($sql_pn);

    if ($result_pn && $result_pn->num_rows > 0) {
        $row_pn = $result_pn->fetch_assoc();
        $PN_NgayLap = $row_pn["PN_NgayLap"];
    } else {
        die("Không tìm thấy phiếu nhập với PN_ID: $PN_ID");
    }

    $sql_ctpn = "SELECT ctpn.*, sp.SP_Ten
                FROM chi_tiet_phieu_nhap ctpn
                JOIN san_pham sp ON ctpn.SP_ID = sp.SP_ID
                WHERE ctpn.PN_ID = '$PN_ID'";
    $result_ctpn = $connection->query($sql_ctpn);

    if (!$result_ctpn) {
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
                        <h2 style="color: blue;">Chi tiết Phiếu Nhập Ngày <?php echo htmlspecialchars(date('d/m/Y', strtotime($PN_NgayLap))); ?></h2>
                        <a href="export_excel.php?PN_ID=<?php echo $PN_ID; ?>" class="btn btn-success">Xuất Excel</a>
                        <a href="lich_su_nhap.php" class="btn btn-outline-secondary">Trở Lại</a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên Sản Phẩm</th>
                                <th>Số Lượng Nhập</th>
                                <th>Đơn Giá Nhập</th>
                                <th>Phần Trăm Thuế</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_ctpn->num_rows > 0) {
                                while ($row = $result_ctpn->fetch_assoc()) {
                                    echo "
                                    <tr>
                                        <td>{$row['SP_Ten']}</td>
                                        <td>{$row['CTPN_SoLuongNhap']}</td>
                                        <td>" . number_format($row['CTPN_GiaNhap']) . "</td>
                                        <td>{$row['CTPN_ThueVAT']}%</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>Không có dữ liệu.</td></tr>";
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
