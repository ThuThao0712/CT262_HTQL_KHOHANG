<?php
    $servername = "localhost"; 
    $username = "TK_TenDangNhap"; 
    $password = "TK_MatKhau"; 
    $dbname = "qlkhohang"; 

// Kết nối database
$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Kết nối thất bại: " . $connection->connect_error);
}

// Truy vấn tổng số lượng nhập theo tháng
$query_nhap = "SELECT DATE_FORMAT(PN_NgayLap, '%Y-%m') AS month, SUM(PN_TongSoLuongNhap) AS total_nhap 
               FROM phieu_nhap 
               GROUP BY month";
$result_nhap = $connection->query($query_nhap);
$data_nhap = [];
while ($row = $result_nhap->fetch_assoc()) {
    $data_nhap[$row['month']] = $row['total_nhap'];
}

// Truy vấn tổng số lượng xuất theo tháng
$query_xuat = "SELECT DATE_FORMAT(PX_NgayXuat, '%Y-%m') AS month, COUNT(PX_ID) AS total_xuat 
               FROM phieu_xuat 
               GROUP BY month";
$result_xuat = $connection->query($query_xuat);
$data_xuat = [];
while ($row = $result_xuat->fetch_assoc()) {
    $data_xuat[$row['month']] = $row['total_xuat'];
}

// Gộp các tháng lại để đồng nhất
$all_months = array_unique(array_merge(array_keys($data_nhap), array_keys($data_xuat)));
sort($all_months); // Sắp xếp theo thời gian

// Tạo dữ liệu đầy đủ cho JSON
$response = [];
foreach ($all_months as $month) {
    $response[] = [
        "month" => $month,
        "nhap" => isset($data_nhap[$month]) ? $data_nhap[$month] : 0,
        "xuat" => isset($data_xuat[$month]) ? $data_xuat[$month] : 0
    ];
}

// Trả về JSON
echo json_encode($response);
?>