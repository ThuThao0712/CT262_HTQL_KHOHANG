<?php
// Kết nối trực tiếp MySQL
$servername = "localhost";
$username = "root";  // Thay bằng tài khoản MySQL của bạn
$password = "";      // Thay bằng mật khẩu nếu có
$dbname = "qlkhohang";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý thêm phiếu nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NV_ID = $_POST["NV_ID"];
    $NCC_ID = $_POST["NCC_ID"];
    $SP_IDs = $_POST["SP_ID"];
    $SoLuongNhaps = $_POST["CTPN_SoLuongNhap"];
    $DonGiaNhaps = $_POST["CTPN_DonGiaNhap"];
    $ThueVATs = $_POST["CTPN_ThueVAT"];

    $TongSoLuongNhap = array_sum($SoLuongNhaps);
    $TongTienNhap = 0;
    $TongTienVAT = 0;

    foreach ($SP_IDs as $index => $SP_ID) {
        $TongTienNhap += $DonGiaNhaps[$index] * $SoLuongNhaps[$index];
        $TongTienVAT += ($DonGiaNhaps[$index] * ($ThueVATs[$index] / 100)) * $SoLuongNhaps[$index];
    }

    $TongTienPhieu = $TongTienNhap + $TongTienVAT;
    $NgayLap = date('Y-m-d');

    // Thêm vào bảng `phieu_nhap`
    $sql = "INSERT INTO phieu_nhap (PN_NgayLap, PN_TongSoLuongNhap, PN_TongTienNhap, PN_TongThueVAT, PN_TongPN, NV_ID, NCC_ID)
            VALUES ('$NgayLap', '$TongSoLuongNhap', '$TongTienNhap', '$TongTienVAT', '$TongTienPhieu', '$NV_ID', '$NCC_ID')";
    
    if ($conn->query($sql) === TRUE) {
        $PN_ID = $conn->insert_id; // Lấy ID của phiếu nhập vừa tạo

        // Thêm vào bảng `chi_tiet_phieu_nhap`
        foreach ($SP_IDs as $index => $SP_ID) {
            $sql_ct = "INSERT INTO chi_tiet_phieu_nhap (PN_ID, SP_ID, CTPN_SoLuongNhap, CTPN_GiaNhap, CTPN_ThueVAT) 
                       VALUES ('$PN_ID', '$SP_ID', '{$SoLuongNhaps[$index]}', '{$DonGiaNhaps[$index]}', '{$ThueVATs[$index]}')";
            $conn->query($sql_ct);

            // Cập nhật số lượng trong kho
            $sql_update_kho = "UPDATE kho_cn SET KCN_SoLuong = KCN_SoLuong + {$SoLuongNhaps[$index]} WHERE SP_ID = '$SP_ID'";
            $conn->query($sql_update_kho);
        }

        header("Location: lich_su_nhap.php?success=1"); 
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Lấy danh sách nhân viên
$resultNV = $conn->query("SELECT NV_ID, NV_HoTen FROM nhan_vien");
$nhanviens = $resultNV->fetch_all(MYSQLI_ASSOC);

// Lấy danh sách nhà cung cấp
$resultNCC = $conn->query("SELECT NCC_ID, NCC_HoTen FROM nha_cung_cap");
$nhacungcaps = $resultNCC->fetch_all(MYSQLI_ASSOC);

// Lấy danh sách sản phẩm theo nhà cung cấp
$sanphams = [];
foreach ($nhacungcaps as $ncc) {
    $resultSP = $conn->query("SELECT SP_ID, SP_Ten FROM san_pham WHERE NCC_ID = " . $ncc['NCC_ID']);
    $sanphams[$ncc['NCC_ID']] = $resultSP->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Phiếu Nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <h2 class="mt-4">✅ Thêm Phiếu Nhập ✅</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nhân Viên</label>
            <select class="form-control" name="NV_ID" required>
                <option value="">-- Chọn Nhân Viên --</option>
                <?php foreach ($nhanviens as $nv): ?>
                    <option value="<?= $nv['NV_ID'] ?>"><?= $nv['NV_HoTen'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nhà Cung Cấp</label>
            <select class="form-control" name="NCC_ID" id="NCC_ID" required>
                <option value="">-- Chọn Nhà Cung Cấp --</option>
                <?php foreach ($nhacungcaps as $ncc): ?>
                    <option value="<?= $ncc['NCC_ID'] ?>"><?= $ncc['NCC_HoTen'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="sanPhamContainer">
            <div class="sanPhamGroup">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Sản Phẩm</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="SP_ID[]" required>
                            <option value="">-- Chọn Sản Phẩm --</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Số Lượng Nhập</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="CTPN_SoLuongNhap[]" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Đơn Giá Nhập</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="CTPN_DonGiaNhap[]" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Thuế VAT (%)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="CTPN_ThueVAT[]" required>
                    </div>
                </div>
                <button type="button" class="btn btn-danger removeProduct">Xóa</button>
                <hr>
            </div>
        </div>

        <button type="button" id="addMoreProducts" class="btn btn-success">Thêm Sản phẩm</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="lich_su_nhap.php" class="btn btn-danger">Hủy</a>
    </form>
</div>

<script>
$(document).ready(function() {
    var sanphamData = <?= json_encode($sanphams) ?>;

    $('#NCC_ID').change(function() {
        var ncc_id = $(this).val();
        var options = '<option value="">-- Chọn Sản Phẩm --</option>';
        if (sanphamData[ncc_id]) {
            sanphamData[ncc_id].forEach(sp => {
                options += `<option value="${sp.SP_ID}">${sp.SP_Ten}</option>`;
            });
        }
        $('select[name="SP_ID[]"]').html(options);
    });

    $('#addMoreProducts').click(function() {
        var newProduct = $('.sanPhamGroup:first').clone();
        newProduct.find('input').val('');
        newProduct.find('select').val('');
        $('#sanPhamContainer').append(newProduct);
    });

    $(document).on('click', '.removeProduct', function() {
        if ($('.sanPhamGroup').length > 1) {
            $(this).closest('.sanPhamGroup').remove();
        } else {
            alert('Cần ít nhất một sản phẩm!');
        }
    });
});
</script>
</body>
</html>
