<?php
  // Database connection
  $servername = "localhost";
  $username = "TK_TenDangNhap";
  $password = "TK_MatKhau";
  $dbname = "qlkhohang";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  else{
      // echo "Thanh cong";
    }
?>