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

    $sql_nhap = "SELECT sp.SP_Ten, SUM(ctpn.CTPN_SoLuongNhap) AS SoLuongNhap
             FROM chi_tiet_phieu_nhap ctpn
             JOIN san_pham sp ON ctpn.SP_ID = sp.SP_ID
             GROUP BY ctpn.SP_ID";
$result_nhap = $connection->query($sql_nhap);

$labels = [];
$dataNhap = [];
while ($row = $result_nhap->fetch_assoc()) {
    $labels[] = $row['SP_Ten'];
    $dataNhap[] = $row['SoLuongNhap'];
}

$sql_xuat = "SELECT sp.SP_Ten, SUM(ctpx.CTPX_SoLuong) AS SoLuongXuat
FROM chi_tiet_phieu_xuat ctpx
JOIN san_pham sp ON ctpx.SP_ID = sp.SP_ID
GROUP BY ctpx.SP_ID";
$result_xuat = $connection->query($sql_xuat);

$dataXuat = [];
while ($row = $result_xuat->fetch_assoc()) {
$dataXuat[] = $row['SoLuongXuat'];
}
$connection->close();

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
                        <h2>Thống Kê</h2>
                        <div class="d-flex justify-content-between gap-3">
                            <button class="btn btn-danger" id="btnSanPham">Thống kê theo sản phẩm</button>
                            <button class="btn btn-danger" id="btnNhapXuat">Thống kê theo số lượng nhập - xuất</button>
                        </div>

                    </div>

                    <div class="chart-container" id="chartSanPham" style="display: none;">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-md-6">
                                <h4 class="text-center mt-3">Thống kê số lượng nhập hàng</h4>
                                <div class="d-flex justify-content-center align-items-center">
                                    <canvas id="chartNhap" style="max-width: 300px; max-height: 400px;"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-center mt-3">Thống kê số lượng xuất hàng</h4>
                                <div class="d-flex justify-content-center align-items-center">
                                    <canvas id="chartXuat" style="max-width: 300px; max-height: 400px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="chart-container mt-3" id="chartNhapXuat" style="display: none;">
                        <canvas id="canvasNhapXuat"></canvas>

                    </div>

                </div>

                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const btnSanPham = document.getElementById("btnSanPham");
                    const btnNhapXuat = document.getElementById("btnNhapXuat");
                    const chartSanPham = document.getElementById("chartSanPham");
                    const chartNhapXuat = document.getElementById("chartNhapXuat");


                    chartSanPham.style.display = "none";
                    chartNhapXuat.style.display = "none";


                    btnSanPham.addEventListener("click", function() {
                        chartSanPham.style.display = "block";
                        chartNhapXuat.style.display = "none";
                        drawChartSanPham(); // 
                    });


                    btnNhapXuat.addEventListener("click", function() {
                        chartNhapXuat.style.display = "block";
                        chartSanPham.style.display = "none";
                        drawChartNhapXuat();
                    });


                    let isChartSanPhamDrawn = false;
                    let isChartNhapXuatDrawn = false;


                    function drawChartSanPham() {
                        if (isChartSanPhamDrawn) return;
                        isChartSanPhamDrawn = true;

                        const labels = <?php echo json_encode($labels); ?>;
                        const dataNhap = <?php echo json_encode($dataNhap); ?>;
                        const dataXuat = <?php echo json_encode($dataXuat); ?>;

                        const ctxNhap = document.getElementById('chartNhap').getContext('2d');
                        new Chart(ctxNhap, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Số lượng nhập',
                                    data: dataNhap,
                                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56',
                                        '#4BC0C0', '#9966FF', '#FF9F40'
                                    ]
                                }]
                            }
                        });

                        const ctxXuat = document.getElementById('chartXuat').getContext('2d');
                        new Chart(ctxXuat, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Số lượng xuất',
                                    data: dataXuat,
                                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56',
                                        '#4BC0C0', '#9966FF', '#FF9F40'
                                    ]
                                }]
                            }
                        });
                    }

                    // Vẽ biểu đồ nhập xuất theo tháng
                    function drawChartNhapXuat() {
                        console.log("🔹 Hàm drawChartNhapXuat() đã được gọi!");

                        if (isChartNhapXuatDrawn) {
                            console.log("⏭️ Biểu đồ đã vẽ trước đó, không vẽ lại.");
                            return;
                        }
                        isChartNhapXuatDrawn = true;

                        fetch('get_chart_data.php')
                            .then(response => response.json())
                            .then(data => {
                                console.log("📊 Dữ liệu API:", data);

                                const labels = data.map(item => item.month);
                                const valuesNhap = data.map(item => item.nhap);
                                const valuesXuat = data.map(item => item.xuat);

                                console.log("🔹 Labels:", labels);
                                console.log("🔹 Nhập:", valuesNhap);
                                console.log("🔹 Xuất:", valuesXuat);

                                const ctx = document.getElementById("canvasNhapXuat").getContext("2d");

                                console.log("🎨 Vẽ biểu đồ trên canvas:", ctx);

                                new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                                label: 'Nhập hàng',
                                                data: valuesNhap,
                                                backgroundColor: 'rgba(54, 162, 235, 0.6)'
                                            },
                                            {
                                                label: 'Xuất hàng',
                                                data: valuesXuat,
                                                backgroundColor: 'rgba(255, 99, 132, 0.6)'
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            })
                            .catch(error => console.error("⚠️ Lỗi khi fetch API:", error));
                    }

                });
                </script>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../main.js"></script>
</body>

</html>