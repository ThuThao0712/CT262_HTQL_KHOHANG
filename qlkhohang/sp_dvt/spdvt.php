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
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
    
        $sql = "SELECT spdvt.SPDVT_ID, spdvt.SPDVT_Gia, sp.SP_Ten, dvt.DVT_Ten
                FROM sp_dvt spdvt
                JOIN san_pham sp ON sp.SP_ID = spdvt.SP_ID
                JOIN don_vi_tinh dvt ON spdvt.DVT_ID = dvt.DVT_ID
                WHERE SP_Ten LIKE '%$search%'
                ORDER BY spdvt.SPDVT_ID ASC";
    } else {
        $sql = "SELECT spdvt.SPDVT_ID, spdvt.SPDVT_Gia, sp.SP_Ten, dvt.DVT_Ten
                FROM sp_dvt spdvt
                JOIN san_pham sp ON sp.SP_ID = spdvt.SP_ID
                JOIN don_vi_tinh dvt ON spdvt.DVT_ID = dvt.DVT_ID
                ORDER BY spdvt.SPDVT_ID ASC";
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
                            <h2>Danh sách GIÁ SẢN PHẨM</h2>
                            <form action="" method="GET" class="search-form">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm" aria-label="First name">
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-outline-dark">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                            <a href="spdvtthem.php" class="btn btn-outline-success">Thêm</a>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Tên Sản Phẩm</td>
                                    <td>Giá</td>
                                    <td>ĐVT</td>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                                if (!$result) {
                                    die("invalid query: ". $connection->error);
                                }
                                while($row = $result->fetch_assoc()){
                                    echo "
                                <tr>
                                    <td>$row[SPDVT_ID]</td>
                                    <td>$row[SP_Ten]</td>
                                    <td>" . number_format($row['SPDVT_Gia'], 0, ',', '.') . "</td>
                                    <td>$row[DVT_Ten]</td>
                                    <td>
                                        <a href='spdvtsua.php?SPDVT_ID=$row[SPDVT_ID]' class='btn btn-outline-primary'>Sửa</a>
                                        <a href='spdvtxoa.php?SPDVT_ID=$row[SPDVT_ID]' class='btn btn-outline-danger'>Xóa</a>
                                    </td>
                                </tr>
                                    ";
                                } 
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>        
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="../main.js"></script>
    </body>
</html>