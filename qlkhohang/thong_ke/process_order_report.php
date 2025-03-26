<?php
    require_once '../blocks/head.php';

    $servername = "localhost"; 
    $username = "TK_TenDangNhap"; 
    $password = "TK_MatKhau"; 
    $dbname = "qlkhohang"; 

    $connection = new mysqli($servername, $username, $password, $dbname);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    if (isset($_POST['month']) && isset($_POST['year'])) {
        $month = $_POST['month'];
        $year = $_POST['year'];
        $monthCondition = "";

        if ($month != "") {
            $monthCondition = "AND MONTH(dh.DH_NgayDat) = '$month'";
        }

        $sql = "SELECT dh.DH_ID, dh.DH_TenKH, dh.DH_TongTien, dh.DH_NgayDat 
                FROM don_hang dh 
                WHERE YEAR(dh.DH_NgayDat) = '$year' $monthCondition AND dh.TT_ID != 3";
        $result = $connection->query($sql);

        if ($result) {
            $sql_total = "SELECT COUNT(*) AS total_orders, SUM(DH_TongTien) AS total_amount
                        FROM don_hang dh 
                        WHERE YEAR(DH_NgayDat) = '$year' $monthCondition AND TT_ID != 3";
            $result_total = $connection->query($sql_total);
            if ($result_total) {
                $row_total = $result_total->fetch_assoc();
                $time_period = ($month != "") ? "trong tháng $month, năm $year" : "trong năm $year";
                echo "<h3 style='font-size: 40px; color: red;'>Số Đơn Hàng $time_period: " . $row_total['total_orders'] . "</h3>";
                echo "<h4 style='font-size: 30px;'>Tổng Số Tiền các ĐH $time_period: " . number_format($row_total['total_amount'], 0, ',', '.') . " VNĐ</h4>";
            }
            
            echo "<table style='width: 100%; border-collapse: collapse; border: 1px solid black;'>
                    <tr>
                        <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>ID Đơn Hàng</th>
                        <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>Ngày Đặt</th>
                        <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>Tên Khách Hàng</th>
                        <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>Tổng Đơn</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                $formatted_date = date('d/m/Y', strtotime($row['DH_NgayDat']));
                echo "<tr>
                        <td style='color: blue; border: 1px solid black; padding: 8px;'>#" . $row['DH_ID'] . "</td>
                        <td style='border: 1px solid black; padding: 8px;'>" . $formatted_date . "</td>
                        <td style='border: 1px solid black; padding: 8px;'>" . $row['DH_TenKH'] . "</td>
                        <td style='border: 1px solid black; padding: 8px;'>" . number_format($row['DH_TongTien'], 0, ',', '.') . " VNĐ</td>
                    </tr>";
            }
            echo "</table>";
        }
    } else {
        echo "Dữ liệu không hợp lệ.";
    }
?>
