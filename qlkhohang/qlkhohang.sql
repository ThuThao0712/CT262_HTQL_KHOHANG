-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 07:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlkhohang`
--

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_phieu_nhap`
--

CREATE TABLE `chi_tiet_phieu_nhap` (
  `PN_ID` int(11) NOT NULL,
  `SP_ID` int(11) NOT NULL,
  `CTPN_SoLuongNhap` int(11) NOT NULL,
  `CTPN_GiaNhap` decimal(10,2) NOT NULL,
  `CTPN_ThueVAT` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chi_tiet_phieu_nhap`
--

INSERT INTO `chi_tiet_phieu_nhap` (`PN_ID`, `SP_ID`, `CTPN_SoLuongNhap`, `CTPN_GiaNhap`, `CTPN_ThueVAT`) VALUES
(1, 1, 100, 2500000.00, 10.00),
(2, 2, 50, 2300000.00, 10.00),
(3, 1, 10, 3000000.00, 30000.00),
(4, 1, 10, 3000000.00, 30000.00),
(5, 1, 10, 3000000.00, 30000.00),
(6, 1, 10, 3000000.00, 30000.00),
(7, 1, 10, 3000000.00, 30000.00),
(8, 1, 10, 3000000.00, 30000.00),
(9, 3, 5, 5.00, 5.00),
(10, 1, 5, 5.00, 5.00),
(10, 2, 5, 5.00, 5.00),
(11, 2, 3, 3.00, 3.00),
(11, 4, 3, 3.00, 3.00);

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_phieu_xuat`
--

CREATE TABLE `chi_tiet_phieu_xuat` (
  `PX_ID` int(11) NOT NULL,
  `SP_ID` int(11) NOT NULL,
  `CTPX_SoLuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chi_tiet_phieu_xuat`
--

INSERT INTO `chi_tiet_phieu_xuat` (`PX_ID`, `SP_ID`, `CTPX_SoLuong`) VALUES
(1, 1, 5),
(2, 2, 3),
(3, 1, 1),
(3, 2, 1),
(4, 1, 2),
(4, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc_sp`
--

CREATE TABLE `danh_muc_sp` (
  `DM_ID` int(11) NOT NULL,
  `DM_Ten` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_muc_sp`
--

INSERT INTO `danh_muc_sp` (`DM_ID`, `DM_Ten`) VALUES
(1, 'Điện thoại'),
(2, 'Laptop'),
(3, 'Máy tính bảng');

-- --------------------------------------------------------

--
-- Table structure for table `kho_cn`
--

CREATE TABLE `kho_cn` (
  `KCN_SoLuong` int(11) NOT NULL,
  `NV_ID` int(11) DEFAULT NULL,
  `SP_ID` int(11) NOT NULL,
  `KHO_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kho_cn`
--

INSERT INTO `kho_cn` (`KCN_SoLuong`, `NV_ID`, `SP_ID`, `KHO_ID`) VALUES
(237, 1, 1, 1),
(147, 2, 2, 1),
(18, NULL, 1, 2),
(11, NULL, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `kho_hang`
--

CREATE TABLE `kho_hang` (
  `KHO_ID` int(11) NOT NULL,
  `KHO_Ten` varchar(255) NOT NULL,
  `KHO_SoLuong` int(11) NOT NULL,
  `KHO_DienTich` decimal(10,2) NOT NULL,
  `KHO_DiaChi` text NOT NULL,
  `TT_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kho_hang`
--

INSERT INTO `kho_hang` (`KHO_ID`, `KHO_Ten`, `KHO_SoLuong`, `KHO_DienTich`, `KHO_DiaChi`, `TT_ID`) VALUES
(1, 'Kho Tổng', 500, 100.50, 'Kho 1 - TP.HCM', 1),
(2, 'Kho Hà Nội', 300, 80.20, 'Kho 2 - Hà Nội', 1),
(3, 'Kho Cần Thơ', 1000, 500.00, '123 Đường 3/2, Cần Thơ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nhan_vien`
--

CREATE TABLE `nhan_vien` (
  `NV_ID` int(11) NOT NULL,
  `NV_HoTen` varchar(255) NOT NULL,
  `NV_GioiTinh` varchar(10) NOT NULL,
  `NV_Email` varchar(255) NOT NULL,
  `NV_SDT` varchar(20) NOT NULL,
  `TK_ID` int(11) DEFAULT NULL,
  `KHO_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhan_vien`
--

INSERT INTO `nhan_vien` (`NV_ID`, `NV_HoTen`, `NV_GioiTinh`, `NV_Email`, `NV_SDT`, `TK_ID`, `KHO_ID`) VALUES
(1, 'Nguyễn Châu', 'Nư', 'chau01@gmail.com', '0934567890', 2, 1),
(2, 'Ngô Trung Quân', 'Nam', 'quan@gmail.com', '0976543210', 3, 1),
(3, 'Hồ Hoàng Ngọc', 'Nữ', 'ngoc@gmail.com', '0322467648', 7, 2),
(4, 'Hồ Ngọc Hà', 'Nữ', 'ha@gmail.com', '0426135867', 8, 3);

-- --------------------------------------------------------

--
-- Table structure for table `nha_cung_cap`
--

CREATE TABLE `nha_cung_cap` (
  `NCC_ID` int(11) NOT NULL,
  `NCC_HoTen` varchar(255) NOT NULL,
  `NCC_SDT` varchar(20) NOT NULL,
  `NCC_DiaChi` text NOT NULL,
  `NCC_Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nha_cung_cap`
--

INSERT INTO `nha_cung_cap` (`NCC_ID`, `NCC_HoTen`, `NCC_SDT`, `NCC_DiaChi`, `NCC_Email`) VALUES
(1, 'Công ty ABC', '0123456789', '123 Đường A, TP.HCM', 'abc1@gmail.com'),
(2, 'Công ty XYZ', '0987654321', '456 Đường B, Hà Nội', 'xyz@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `phieu_nhap`
--

CREATE TABLE `phieu_nhap` (
  `PN_ID` int(11) NOT NULL,
  `PN_NgayLap` date NOT NULL,
  `PN_GioLap` time NOT NULL,
  `PN_TongSoLuongNhap` int(11) NOT NULL,
  `PN_TongTienNhap` decimal(10,2) NOT NULL,
  `PN_TongThueVAT` decimal(10,2) NOT NULL,
  `PN_TongPN` decimal(10,2) NOT NULL,
  `NV_ID` int(11) DEFAULT NULL,
  `SP_ID` int(11) DEFAULT NULL,
  `NCC_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieu_nhap`
--

INSERT INTO `phieu_nhap` (`PN_ID`, `PN_NgayLap`, `PN_GioLap`, `PN_TongSoLuongNhap`, `PN_TongTienNhap`, `PN_TongThueVAT`, `PN_TongPN`, `NV_ID`, `SP_ID`, `NCC_ID`) VALUES
(1, '2024-02-20', '10:30:00', 100, 99999999.99, 25000000.00, 99999999.99, 1, 1, 1),
(2, '2024-02-21', '14:15:00', 50, 99999999.99, 11500000.00, 99999999.99, 2, 2, 2),
(3, '2025-03-12', '00:00:00', 10, 30000000.00, 99999999.99, 99999999.99, 1, NULL, 1),
(4, '2025-03-12', '00:00:00', 10, 30000000.00, 99999999.99, 99999999.99, 1, NULL, 1),
(5, '2025-03-12', '00:00:00', 10, 30000000.00, 99999999.99, 99999999.99, 1, NULL, 1),
(6, '2025-03-12', '00:00:00', 10, 30000000.00, 99999999.99, 99999999.99, 1, NULL, 1),
(7, '2025-03-12', '00:00:00', 10, 30000000.00, 99999999.99, 99999999.99, 1, NULL, 1),
(8, '2025-03-12', '00:00:00', 10, 30000000.00, 99999999.99, 99999999.99, 1, NULL, 1),
(9, '2025-03-12', '00:00:00', 5, 25.00, 1.25, 26.25, 1, NULL, 1),
(10, '2025-03-12', '00:00:00', 10, 50.00, 2.50, 52.50, 2, NULL, 1),
(11, '2025-03-12', '00:00:00', 6, 18.00, 0.54, 18.54, 2, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `phieu_xuat`
--

CREATE TABLE `phieu_xuat` (
  `PX_ID` int(11) NOT NULL,
  `PX_NgayXuat` date NOT NULL,
  `PX_GioXuat` time NOT NULL,
  `PX_NgayHoanThanh` date NOT NULL,
  `TT_ID` int(11) DEFAULT NULL,
  `NV_ID` int(11) DEFAULT NULL,
  `KHO_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieu_xuat`
--

INSERT INTO `phieu_xuat` (`PX_ID`, `PX_NgayXuat`, `PX_GioXuat`, `PX_NgayHoanThanh`, `TT_ID`, `NV_ID`, `KHO_ID`) VALUES
(1, '2025-03-12', '14:00:00', '2025-03-13', 1, 1, 1),
(2, '2025-03-13', '10:15:00', '2025-03-14', 2, 2, 2),
(3, '2025-03-13', '00:24:26', '0000-00-00', 3, 1, 2),
(4, '2025-03-13', '00:26:44', '0000-00-00', 4, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `quan_ly`
--

CREATE TABLE `quan_ly` (
  `QL_ID` int(11) NOT NULL,
  `QL_HoTen` varchar(255) NOT NULL,
  `QL_GioiTinh` varchar(10) NOT NULL,
  `QL_Email` varchar(255) NOT NULL,
  `QL_SDT` varchar(10) NOT NULL,
  `TK_ID` int(11) DEFAULT NULL,
  `KHO_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quan_ly`
--

INSERT INTO `quan_ly` (`QL_ID`, `QL_HoTen`, `QL_GioiTinh`, `QL_Email`, `QL_SDT`, `TK_ID`, `KHO_ID`) VALUES
(1, 'Nguyễn Anh Thư', 'Nữ', 'anhthu01@gmail.com', '0912345678', 1, 1),
(2, 'Trần Thị Thư', 'Nữ', 'thithu02@gmail.com', '0987654321', 4, 1),
(3, 'Nguyen Thi Mai', 'Nữ', 'nguyenmai@gmail.com', '0987654321', 5, 3),
(4, 'Nguyễn Minh Trung', 'Nam', 'trung@gmail.com', '0923647527', 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `SP_ID` int(11) NOT NULL,
  `SP_Ten` varchar(255) NOT NULL,
  `SP_Gia` decimal(10,2) NOT NULL,
  `DM_ID` int(11) DEFAULT NULL,
  `TT_ID` int(11) DEFAULT NULL,
  `NCC_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`SP_ID`, `SP_Ten`, `SP_Gia`, `DM_ID`, `TT_ID`, `NCC_ID`) VALUES
(1, 'iPhone 15', 25000000.00, 1, 1, 1),
(2, 'Samsung Galaxy S23', 23000000.00, 1, 1, 1),
(3, 'MacBook Pro 16', 55000000.00, 2, 1, 1),
(4, 'iPad Air', 15000000.00, 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tai_khoan`
--

CREATE TABLE `tai_khoan` (
  `TK_ID` int(11) NOT NULL,
  `TK_TenDangNhap` varchar(20) NOT NULL,
  `TK_MatKhau` varchar(255) DEFAULT NULL,
  `TK_VaiTro` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tai_khoan`
--

INSERT INTO `tai_khoan` (`TK_ID`, `TK_TenDangNhap`, `TK_MatKhau`, `TK_VaiTro`) VALUES
(1, 'anhthu01', '123456', 'Quản Lý'),
(2, 'chau01', '123456', 'Nhân Viên'),
(3, 'thao01', '123456', 'Nhân Viên'),
(4, 'anhthu02', '123456', 'Quản Lý'),
(5, 'mai01', '123456', 'Quản Lý'),
(6, 'minhtrung', '123456', 'Quản Lý'),
(7, 'hoangngoc', '1234567', 'Nhân Viên'),
(8, 'ngocha', '123456', 'Nhân Viên');

-- --------------------------------------------------------

--
-- Table structure for table `trang_thai`
--

CREATE TABLE `trang_thai` (
  `TT_ID` int(11) NOT NULL,
  `TT_Ten` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trang_thai`
--

INSERT INTO `trang_thai` (`TT_ID`, `TT_Ten`) VALUES
(1, 'Còn hàng'),
(2, 'Hết hàng'),
(3, 'Đang nhập hàng'),
(4, 'Đang xuất hàng');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chi_tiet_phieu_nhap`
--
ALTER TABLE `chi_tiet_phieu_nhap`
  ADD PRIMARY KEY (`PN_ID`,`SP_ID`),
  ADD KEY `SP_ID` (`SP_ID`);

--
-- Indexes for table `chi_tiet_phieu_xuat`
--
ALTER TABLE `chi_tiet_phieu_xuat`
  ADD PRIMARY KEY (`PX_ID`,`SP_ID`),
  ADD KEY `SP_ID` (`SP_ID`);

--
-- Indexes for table `danh_muc_sp`
--
ALTER TABLE `danh_muc_sp`
  ADD PRIMARY KEY (`DM_ID`);

--
-- Indexes for table `kho_cn`
--
ALTER TABLE `kho_cn`
  ADD PRIMARY KEY (`KHO_ID`,`SP_ID`),
  ADD KEY `NV_ID` (`NV_ID`),
  ADD KEY `SP_ID` (`SP_ID`);

--
-- Indexes for table `kho_hang`
--
ALTER TABLE `kho_hang`
  ADD PRIMARY KEY (`KHO_ID`),
  ADD KEY `TT_ID` (`TT_ID`);

--
-- Indexes for table `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD PRIMARY KEY (`NV_ID`),
  ADD KEY `FK_NhanVien_TaiKhoan` (`TK_ID`),
  ADD KEY `FK_NHAN_VIEN_KHO` (`KHO_ID`);

--
-- Indexes for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  ADD PRIMARY KEY (`NCC_ID`);

--
-- Indexes for table `phieu_nhap`
--
ALTER TABLE `phieu_nhap`
  ADD PRIMARY KEY (`PN_ID`),
  ADD KEY `NV_ID` (`NV_ID`),
  ADD KEY `SP_ID` (`SP_ID`),
  ADD KEY `NCC_ID` (`NCC_ID`);

--
-- Indexes for table `phieu_xuat`
--
ALTER TABLE `phieu_xuat`
  ADD PRIMARY KEY (`PX_ID`),
  ADD KEY `TT_ID` (`TT_ID`),
  ADD KEY `NV_ID` (`NV_ID`),
  ADD KEY `FK_PHIEU_XUAT_KHO` (`KHO_ID`);

--
-- Indexes for table `quan_ly`
--
ALTER TABLE `quan_ly`
  ADD PRIMARY KEY (`QL_ID`),
  ADD KEY `FK_QuanLy_TaiKhoan` (`TK_ID`),
  ADD KEY `FK_QUAN_LY_KHO` (`KHO_ID`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`SP_ID`),
  ADD KEY `DM_ID` (`DM_ID`),
  ADD KEY `TT_ID` (`TT_ID`),
  ADD KEY `fk_ncc_sanpham` (`NCC_ID`);

--
-- Indexes for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  ADD PRIMARY KEY (`TK_ID`),
  ADD UNIQUE KEY `TK_TenDangNhap` (`TK_TenDangNhap`);

--
-- Indexes for table `trang_thai`
--
ALTER TABLE `trang_thai`
  ADD PRIMARY KEY (`TT_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `danh_muc_sp`
--
ALTER TABLE `danh_muc_sp`
  MODIFY `DM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kho_hang`
--
ALTER TABLE `kho_hang`
  MODIFY `KHO_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nhan_vien`
--
ALTER TABLE `nhan_vien`
  MODIFY `NV_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  MODIFY `NCC_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phieu_nhap`
--
ALTER TABLE `phieu_nhap`
  MODIFY `PN_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `phieu_xuat`
--
ALTER TABLE `phieu_xuat`
  MODIFY `PX_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quan_ly`
--
ALTER TABLE `quan_ly`
  MODIFY `QL_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `SP_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  MODIFY `TK_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trang_thai`
--
ALTER TABLE `trang_thai`
  MODIFY `TT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chi_tiet_phieu_nhap`
--
ALTER TABLE `chi_tiet_phieu_nhap`
  ADD CONSTRAINT `chi_tiet_phieu_nhap_ibfk_1` FOREIGN KEY (`PN_ID`) REFERENCES `phieu_nhap` (`PN_ID`),
  ADD CONSTRAINT `chi_tiet_phieu_nhap_ibfk_2` FOREIGN KEY (`SP_ID`) REFERENCES `san_pham` (`SP_ID`);

--
-- Constraints for table `chi_tiet_phieu_xuat`
--
ALTER TABLE `chi_tiet_phieu_xuat`
  ADD CONSTRAINT `chi_tiet_phieu_xuat_ibfk_1` FOREIGN KEY (`PX_ID`) REFERENCES `phieu_xuat` (`PX_ID`),
  ADD CONSTRAINT `chi_tiet_phieu_xuat_ibfk_2` FOREIGN KEY (`SP_ID`) REFERENCES `san_pham` (`SP_ID`);

--
-- Constraints for table `kho_cn`
--
ALTER TABLE `kho_cn`
  ADD CONSTRAINT `FK_KHO_CN_KHO` FOREIGN KEY (`KHO_ID`) REFERENCES `kho_hang` (`KHO_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `kho_cn_ibfk_1` FOREIGN KEY (`NV_ID`) REFERENCES `nhan_vien` (`NV_ID`);

--
-- Constraints for table `kho_hang`
--
ALTER TABLE `kho_hang`
  ADD CONSTRAINT `kho_hang_ibfk_2` FOREIGN KEY (`TT_ID`) REFERENCES `trang_thai` (`TT_ID`);

--
-- Constraints for table `nhan_vien`
--
ALTER TABLE `nhan_vien`
  ADD CONSTRAINT `FK_NHAN_VIEN_KHO` FOREIGN KEY (`KHO_ID`) REFERENCES `kho_hang` (`KHO_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_NhanVien_TaiKhoan` FOREIGN KEY (`TK_ID`) REFERENCES `tai_khoan` (`TK_ID`);

--
-- Constraints for table `phieu_nhap`
--
ALTER TABLE `phieu_nhap`
  ADD CONSTRAINT `phieu_nhap_ibfk_1` FOREIGN KEY (`NV_ID`) REFERENCES `nhan_vien` (`NV_ID`),
  ADD CONSTRAINT `phieu_nhap_ibfk_2` FOREIGN KEY (`SP_ID`) REFERENCES `san_pham` (`SP_ID`),
  ADD CONSTRAINT `phieu_nhap_ibfk_3` FOREIGN KEY (`NCC_ID`) REFERENCES `nha_cung_cap` (`NCC_ID`);

--
-- Constraints for table `phieu_xuat`
--
ALTER TABLE `phieu_xuat`
  ADD CONSTRAINT `FK_PHIEU_XUAT_KHO` FOREIGN KEY (`KHO_ID`) REFERENCES `kho_hang` (`KHO_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `phieu_xuat_ibfk_1` FOREIGN KEY (`TT_ID`) REFERENCES `trang_thai` (`TT_ID`),
  ADD CONSTRAINT `phieu_xuat_ibfk_3` FOREIGN KEY (`NV_ID`) REFERENCES `nhan_vien` (`NV_ID`);

--
-- Constraints for table `quan_ly`
--
ALTER TABLE `quan_ly`
  ADD CONSTRAINT `FK_QUAN_LY_KHO` FOREIGN KEY (`KHO_ID`) REFERENCES `kho_hang` (`KHO_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_QuanLy_TaiKhoan` FOREIGN KEY (`TK_ID`) REFERENCES `tai_khoan` (`TK_ID`);

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `fk_ncc_sanpham` FOREIGN KEY (`NCC_ID`) REFERENCES `nha_cung_cap` (`NCC_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`DM_ID`) REFERENCES `danh_muc_sp` (`DM_ID`),
  ADD CONSTRAINT `san_pham_ibfk_2` FOREIGN KEY (`TT_ID`) REFERENCES `trang_thai` (`TT_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
