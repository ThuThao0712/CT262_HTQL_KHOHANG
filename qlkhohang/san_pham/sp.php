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
    
        $sql = "SELECT sp.*, dm.DM_Ten, ncc.NCC_HoTen, dbc.DBC_Ten 
                FROM san_pham sp
                JOIN danh_muc dm ON sp.DM_ID = dm.DM_ID
                JOIN nha_cung_cap ncc ON sp.NCC_ID = ncc.NCC_ID
                JOIN dang_bao_che dbc ON dbc.DBC_ID = sp.DBC_ID
                WHERE sp.SP_Ten LIKE '%$search%' OR dm.DM_Ten LIKE '%$search%'"; 
    } else {
        $sql = "SELECT sp.*, dm.DM_Ten, ncc.NCC_HoTen, dbc.DBC_Ten 
                FROM san_pham sp
                JOIN danh_muc dm ON sp.DM_ID = dm.DM_ID
                JOIN nha_cung_cap ncc ON sp.NCC_ID = ncc.NCC_ID
                JOIN dang_bao_che dbc ON dbc.DBC_ID = sp.DBC_ID
                ORDER BY sp.SP_ID ASC";
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
                            <h2>Danh sách SẢN PHẨM</h2>
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
                            <a href="spthem.php" class="btn btn-outline-success">Thêm</a>
                        </div>

                        <table>
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Tên Sản Phẩm</td>
                                    <td>Danh Mục</td>
                                    <td>Thương hiệu</td>
                                    <td>Dạng bào chế</td>
                                    <td>Quy Cách</td>
                                    <td>Xuất Xứ</td>
                                    <td>NSX</td>
                                    <td>Mô tả</td>
                                    <td>Thành phần</td>
                                    <td>Chỉ định</td>
                                    <td>HDSD</td>
                                    <td>Lưu ý</td>
                                    <td>Ngày Sản Xuất</td>
                                    <td>Ngày Hết Hạn</td>
                                    <td>Ảnh Sản Phẩm</td>
                                    <td>Nhà Cung Cấp</td>
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
                                        <td>$row[SP_ID]</td>
                                        <td>" . substr($row['SP_Ten'], 0, 50) . "...</td>                                    
                                        <td>$row[DM_Ten]</td>
                                        <td>$row[SP_ThuongHieu]</td>
                                        <td>$row[DBC_Ten]</td>                                    
                                        <td>$row[SP_QuyCach]</td>
                                        <td>$row[SP_XuatXu]</td>
                                        <td>$row[SP_NSX]</td>
                                        <td>" . substr($row['SP_MoTa'], 0, 20) . "...</td>                                    
                                        <td>" . substr($row['SP_ThanhPhan'], 0, 50) . "...</td>                                    
                                        <td>" . substr($row['SP_ChiDinh'], 0, 20) . "...</td>                                    
                                        <td>" . substr($row['SP_HDSD'], 0, 20) . "...</td>                                    
                                        <td>" . substr($row['SP_LuuY'], 0, 20) . "...</td>                                    
                                        <td>" . date('d-m-Y', strtotime($row['SP_NgaySanXuat'])) . "</td>
                                        <td>" . date('d-m-Y', strtotime($row['SP_NgayHetHan'])) . "</td>
                                        <td>$row[SP_Anh]</td>
                                        <td><a href='spnccxem.php?NCC_ID=$row[NCC_ID]' class='link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover'>$row[NCC_HoTen]</a></td>
                                        <td>
                                            <a href='../san_pham/spsua.php?SP_ID=$row[SP_ID]' class='btn btn-outline-primary'>Sửa</a>
                                            <a href='../san_pham/spxoa.php?SP_ID=$row[SP_ID]' class='btn btn-outline-danger'>Xóa</a>
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