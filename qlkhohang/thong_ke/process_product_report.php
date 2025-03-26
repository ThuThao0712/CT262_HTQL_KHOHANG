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

        $sql = "SELECT sp.SP_Ten, SUM(ctdh.CTDH_SoLuong) AS total_quantity 
                FROM chi_tiet_don_hang ctdh
                JOIN sp_dvt sd ON ctdh.SPDVT_ID = sd.SPDVT_ID
                JOIN san_pham sp ON sd.SP_ID = sp.SP_ID
                JOIN don_hang dh ON ctdh.DH_ID = dh.DH_ID
                WHERE YEAR(dh.DH_NgayDat) = '$year' $monthCondition AND dh.TT_ID != 3
                GROUP BY sp.SP_Ten
                ORDER BY total_quantity DESC";
        $result = $connection->query($sql);

        if ($result) {
            $time_period = ($month != "") ? "trong tháng $month, năm $year" : "trong năm $year";
            echo "<h3 style='font-size: 30px; color: red;'>Thống Kê Sản Phẩm Bán Ra $time_period:</h3>";

            $total_quantity = 0;
            while ($row = $result->fetch_assoc()) {
                $total_quantity += $row['total_quantity'];
            }
            $result->data_seek(0);

            echo "<h4 style='font-size: 25px;'>Tổng Số Lượng Sản Phẩm Bán Ra: " . number_format($total_quantity, 0, ',', '.') . " sản phẩm</h4>";
            
            echo "<table style='width: 100%; border-collapse: collapse; border: 1px solid black;'>
                    <tr>
                        <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>Tên Sản Phẩm</th>
                        <th style='font-size: 20px; border: 1px solid black; padding: 8px; text-align: center;'>Số Lượng Bán Ra</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td style='border: 1px solid black; padding: 8px;'>" . $row['SP_Ten'] . "</td>
                        <td style='border: 1px solid black; padding: 8px;'>" . number_format($row['total_quantity'], 0, ',', '.') . " sản phẩm</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "Dữ liệu không hợp lệ.";
        }
    } else {
        echo "Dữ liệu không hợp lệ.";
    }
?>
