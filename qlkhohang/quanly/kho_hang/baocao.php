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

    $sql = "SELECT sp.SP_ID, sp.SP_Ten, kcn.KCN_SoLuong, kh.KHO_Ten
            FROM kho_cn kcn
            JOIN san_pham sp ON kcn.SP_ID = sp.SP_ID
            JOIN kho_hang kh ON kcn.KHO_ID = kh.KHO_ID
            WHERE kcn.KCN_SoLuong > 0";
    
    $result = $connection->query($sql);
    
?>

<!DOCTYPE html>
<html lang="vi">

<body>
    <div class="wrapper">
        <?php include '../blocks/sidebar.php'; ?>
        <div class="main">
            <div class="tkad">
                <div class="dstaikhoan">
                    <div class="tktenmuc">
                        <h2>Báo Cáo</h2>
                    </div>

                    <div class="">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID Sản Phẩm</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Số Lượng Tồn Kho</th>
                                    <th>Kho</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['SP_ID']}</td>
                                <td>{$row['SP_Ten']}</td>
                                <td>{$row['KCN_SoLuong']}</td>
                                <td>{$row['KHO_Ten']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Không có sản phẩm tồn kho</td></tr>";
                    }
                ?>
                            </tbody>
                        </table>
                        <h6 class="fst-italic">*Số lượng sản phẩm tồn kho</h6>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../main.js"></script>
</body>

</html>