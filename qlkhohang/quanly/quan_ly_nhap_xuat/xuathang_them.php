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

// Lấy danh sách kho ngoại trừ kho tổng (giả sử kho tổng có ID = 1)
$sql_kho = "SELECT * FROM kho_hang WHERE KHO_ID != 1";
$result_kho = $connection->query($sql_kho);

// Lấy danh sách sản phẩm trong kho tổng
$sql_sp = "SELECT san_pham.SP_ID, san_pham.SP_Ten, kho_cn.KCN_SoLuong 
           FROM san_pham
           JOIN kho_cn ON san_pham.SP_ID = kho_cn.SP_ID
           WHERE kho_cn.KHO_ID = 1";
$result_sp = $connection->query($sql_sp);

// Xử lý xuất hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $KHO_ID = $_POST['KHO_ID'];
    $SP_IDs = $_POST['SP_ID'] ?? [];
    $SL_Xuat = $_POST['SL_Xuat'] ?? [];
    $NV_ID = 1; // Giả sử lấy từ session

    $errors = [];
    $success = [];

    if (!$KHO_ID || empty($SP_IDs)) {
        $errors[] = "Vui lòng chọn kho và ít nhất một sản phẩm.";
    } else {
        $connection->begin_transaction();

        try {
            // Tạo phiếu xuất mới
            $sql_insert_phieuxuat = "INSERT INTO phieu_xuat (PX_NgayXuat, PX_GioXuat, TT_ID, KHO_ID, NV_ID) 
                                     VALUES (CURDATE(), CURTIME(), 4, '$KHO_ID', '$NV_ID')";
            $connection->query($sql_insert_phieuxuat);
            $PX_ID = $connection->insert_id; // Lấy ID của phiếu xuất vừa tạo

            foreach ($SP_IDs as $SP_ID) {
                $soLuongXuat = (int) $SL_Xuat[$SP_ID];

                // Kiểm tra số lượng trong kho tổng
                $sql_check = "SELECT KCN_SoLuong FROM kho_cn WHERE SP_ID = '$SP_ID' AND KHO_ID = 1";
                $result_check = $connection->query($sql_check);
                $row_check = $result_check->fetch_assoc();
                $soLuongKhoTong = (int) $row_check['KCN_SoLuong'];

                if ($soLuongXuat > $soLuongKhoTong) {
                    $errors[] = "Không đủ hàng trong kho tổng cho sản phẩm ID $SP_ID.";
                    continue;
                }

                // Trừ số lượng trong kho tổng
                $sql_update_khoTong = "UPDATE kho_cn SET KCN_SoLuong = KCN_SoLuong - $soLuongXuat WHERE SP_ID = '$SP_ID' AND KHO_ID = 1";
                $connection->query($sql_update_khoTong);

                // Kiểm tra sản phẩm có trong kho đích chưa
                $sql_check_khoDich = "SELECT KCN_SoLuong FROM kho_cn WHERE SP_ID = '$SP_ID' AND KHO_ID = '$KHO_ID'";
                $result_check_khoDich = $connection->query($sql_check_khoDich);

                if ($result_check_khoDich->num_rows > 0) {
                    $sql_update_khoDich = "UPDATE kho_cn SET KCN_SoLuong = KCN_SoLuong + $soLuongXuat WHERE SP_ID = '$SP_ID' AND KHO_ID = '$KHO_ID'";
                    $connection->query($sql_update_khoDich);
                } else {
                    $sql_insert_khoDich = "INSERT INTO kho_cn (SP_ID, KHO_ID, KCN_SoLuong) VALUES ('$SP_ID', '$KHO_ID', '$soLuongXuat')";
                    $connection->query($sql_insert_khoDich);
                }

                // Thêm vào chi tiết phiếu xuất
                $sql_insert_ctpx = "INSERT INTO chi_tiet_phieu_xuat (PX_ID, SP_ID, CTPX_SoLuong) 
                                    VALUES ('$PX_ID', '$SP_ID', '$soLuongXuat')";
                $connection->query($sql_insert_ctpx);

                $success[] = "Xuất thành công sản phẩm ID $SP_ID.";
            }

            if (!empty($errors)) {
                throw new Exception(implode('<br>', $errors));
            }

            $connection->commit();
            header("Location: lich_su_xuat.php"); // Chuyển hướng sau khi xuất hàng thành công
            exit();
        } catch (Exception $e) {
            $connection->rollback();
            $errors[] = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<body>
    <div class="wrapper">
        <div class="tkad">
            <div class="dstaikhoan">
                <div class="tktenmuc">
                    <h2 style="color: blue;">Xuất Hàng</h2>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error) echo $error . "<br>"; ?>
                    </div>
                <?php elseif (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?php foreach ($success as $msg) echo $msg . "<br>"; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label>Chọn kho xuất đến:</label>
                        <select name="KHO_ID" class="form-control" required>
                            <option value="">-- Chọn kho --</option>
                            <?php while ($row_kho = $result_kho->fetch_assoc()) { ?>
                                <option value="<?php echo $row_kho['KHO_ID']; ?>">
                                    <?php echo $row_kho['KHO_Ten']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Chọn</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Số Lượng Trong Kho Tổng</th>
                                <th>Số Lượng Cần Xuất</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row_sp = $result_sp->fetch_assoc()) { ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="SP_ID[]" value="<?php echo $row_sp['SP_ID']; ?>">
                                    </td>
                                    <td><?php echo $row_sp['SP_Ten']; ?></td>
                                    <td><?php echo $row_sp['KCN_SoLuong']; ?></td>
                                    <td>
                                        <input type="number" name="SL_Xuat[<?php echo $row_sp['SP_ID']; ?>]" class="form-control" min="1" max="<?php echo $row_sp['KCN_SoLuong']; ?>" disabled>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Xuất Hàng</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                let input = this.closest('tr').querySelector('input[type="number"]');
                input.disabled = !this.checked;
            });
        });
    </script>
</body>
</html>
