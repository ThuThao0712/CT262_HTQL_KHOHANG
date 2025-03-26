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

    $errorMessage = "";
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $SPDVT_Gia = $_POST["SPDVT_Gia"];
        $SP_ID = $_POST["SP_ID"];
        $DVT_ID = $_POST["DVT_ID"];

        $sql = "INSERT INTO sp_dvt (SPDVT_Gia, DVT_ID, SP_ID) VALUES ('$SPDVT_Gia', '$DVT_ID', '$SP_ID')";

        if ($connection->query($sql) === TRUE) {
            header("Location: spdvt.php");
            exit();
        } else {
            $errorMessage = "Đã xảy ra lỗi: " . $connection->error;
        }
    }

    $productQuery = "SELECT SP_ID, SP_Ten FROM san_pham";
    $productResult = $connection->query($productQuery);

    $dvtQuery = "SELECT DVT_ID, DVT_Ten FROM don_vi_tinh";
    $dvtResult = $connection->query($dvtQuery);

    $connection->close();
?>

<!DOCTYPE html>
<html>
    <body>    
        <div class="all">
            <div class="container">
                <h2>✅ Thêm GIÁ SẢN PHẨM ✅</h2>
                <?php
                if (!empty($errorMessage)) {
                    echo "<div class='alert alert-danger'>$errorMessage</div>";
                } elseif (!empty($successMessage)) {
                    echo "<div class='alert alert-success'>$successMessage</div>";
                }
                ?>

                <form method="POST">
                    <div class="mb-3 product-item">
                        <label for="product-list" class="form-label">Danh sách Sản phẩm</label>
                        <select class="form-select product-list" name="SP_ID" required>
                            <option value="">Chọn sản phẩm</option>
                            <?php
                            if ($productResult->num_rows > 0) {
                                while ($row = $productResult->fetch_assoc()) {
                                    echo "<option value='" . $row['SP_ID'] . "'>" . htmlspecialchars($row['SP_Ten']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 product-item">
                        <label for="product-list" class="form-label">Danh sách Đơn vị tính</label>
                        <select class="form-select product-list" name="DVT_ID" required>
                            <option value="">Chọn đơn vị tính</option>
                            <?php
                                if ($dvtResult->num_rows > 0) {
                                    while ($row = $dvtResult->fetch_assoc()) {
                                        echo "<option value='" . $row['DVT_ID'] . "'>" . htmlspecialchars($row['DVT_Ten']) . "</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>   
                    <div class="mb-3">
                        <label for="SPDVT_Gia" class="form-label">Giá  SP</label>
                        <input type="text" class="form-control" id="SPDVT_Gia" name="SPDVT_Gia" required>
                    </div>
                
                    <button type="submit" class="btn btn-success">Thêm</button>
                    <a href="spdvt.php" class="btn btn-outline-danger">Hủy</a>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </body>
</html>