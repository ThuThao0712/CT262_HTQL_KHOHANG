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
?>

<!DOCTYPE html>
<html lang="vi">
    <body>
        <div class="wrapper">
            <?php include '../blocks/sidebar.php'; ?>
            <div class="main">
                <div class="topbar">
                    <div class="toggle">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                    <div class="user">
                        <img src="assets/imgs/customer01.jpg" alt="">
                    </div>
                </div>
                
                <div class="container overflow-hidden text-center" style="margin-top: 20px;">
                    <div class="containertk" id="statisticSelection" style="margin-top: 20px;">
                        <h2 style="margin-bottom: 20px; color: green; font-weight: bold;">Xem Thống Kê ĐƠN HÀNG</h2>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="selectMonth">Chọn Tháng:</label>
                            <select class="form-select" id="selectMonth">
                                <option value="">Tất cả các tháng</option>
                                <option value="01">Tháng 1</option>
                                <option value="02">Tháng 2</option>
                                <option value="03">Tháng 3</option>
                                <option value="04">Tháng 4</option>
                                <option value="05">Tháng 5</option>
                                <option value="06">Tháng 6</option>
                                <option value="07">Tháng 7</option>
                                <option value="08">Tháng 8</option>
                                <option value="09">Tháng 9</option>
                                <option value="10">Tháng 10</option>
                                <option value="11">Tháng 11</option>
                                <option value="12">Tháng 12</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="selectYear">Chọn Năm:</label>
                            <select class="form-select" id="selectYear">
                                <?php
                                $currentYear = date('Y');
                                for ($i = $currentYear; $i >= $currentYear - 3; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-success" id="btnViewReport" style="margin-top: 20px;">Xem Báo Cáo</button>
                        <div id="reportResult" style="margin-top: 40px;"></div>
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="../main.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#btnViewReport').click(function() {
                            var selectedMonth = $('#selectMonth').val();
                            var selectedYear = $('#selectYear').val();
                            
                            $.ajax({
                                url: 'process_order_report.php',
                                type: 'POST',
                                data: {
                                    month: selectedMonth,
                                    year: selectedYear
                                },
                                success: function(response) {
                                    $('#reportResult').html(response);
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </body>
</html>
