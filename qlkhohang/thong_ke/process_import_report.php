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
            $monthCondition = "AND MONTH(pnh.PNH_NgayLap) = '$month'";
        }

        $sql = "SELECT SUM(pnh.PNH_TongSoLuongNhap) AS total_quantity, SUM(pnh.PNH_TongPhieu) AS total_amount 
                FROM phieu_nhap_hang pnh 
                WHERE YEAR(pnh.PNH_NgayLap) = '$year' $monthCondition";
        $result = $connection->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $time_period = ($month != "") ? "trong tháng $month, năm $year" : "trong năm $year";
            echo "<h3 style='font-size: 40px;color: red;'>Thống Kê Nhập Hàng $time_period:</h3>";
            echo "<p style='font-size: 25px; font-weight: bold;'>Tổng Số Lượng Nhập: " . number_format($row['total_quantity'], 0, ',', '.') . " sản phẩm</p>";
            echo "<p style='font-size: 30px; font-weight: bold; '>Tổng Tiền Nhập: " . number_format($row['total_amount'], 0, ',', '.') . " VNĐ</p>";
            
            $sql_details = "SELECT pnh.PNH_ID, pnh.PNH_TongSoLuongNhap, pnh.PNH_TongPhieu, pnh.PNH_NgayLap 
                            FROM phieu_nhap_hang pnh 
                            WHERE YEAR(pnh.PNH_NgayLap) = '$year' $monthCondition";
            $result_details = $connection->query($sql_details);

            if ($result_details) {
                echo "<table style='width: 100%; border-collapse: collapse; border: 1px solid black;'>
                        <tr>
                            <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>ID Phiếu Nhập</th>
                            <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>Ngày Nhập</th>
                            <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>Số Lượng Nhập</th>
                            <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>Tổng Tiền</th>
                        </tr>";
                while ($row_details = $result_details->fetch_assoc()) {
                    $formatted_date = date('d/m/Y', strtotime($row_details['PNH_NgayLap']));
                    echo "<tr>
                            <td style='color: blue; border: 1px solid black; padding: 8px;'>#" . $row_details['PNH_ID'] . "</td>
                            <td style='border: 1px solid black; padding: 8px;'>" . $formatted_date . "</td>
                            <td style='border: 1px solid black; padding: 8px;'>" . number_format($row_details['PNH_TongSoLuongNhap'], 0, ',', '.') . " sản phẩm</td>
                            <td style='border: 1px solid black; padding: 8px;'>" . number_format($row_details['PNH_TongPhieu'], 0, ',', '.') . " VNĐ</td>
                        </tr>";
                }
                echo "</table>";
            }
        } else {
            echo "Dữ liệu không hợp lệ.";
        }
    } else {
        echo "Dữ liệu không hợp lệ.";
    }
?>
