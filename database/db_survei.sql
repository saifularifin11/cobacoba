-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 03:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_survei`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_survei`
--

CREATE TABLE `tbl_survei` (
  `id` int(11) NOT NULL,
  `nilai` enum('SANGAT PUAS','PUAS','TIDAK PUAS') NOT NULL,
  `ulasan` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_survei`
--

INSERT INTO `tbl_survei` (`id`, `nilai`, `ulasan`, `foto`, `waktu`) VALUES
(1, 'SANGAT PUAS', 'Pelayanan sangat baik!', 'uploads/foto1.jpg', '2025-03-07 04:02:12'),
(2, 'PUAS', 'Cukup memuaskan.', NULL, '2025-03-07 04:02:12'),
(3, 'TIDAK PUAS', 'Kurang ramah dalam pelayanan.', 'uploads/foto2.jpg', '2025-03-07 04:02:12'),
(4, 'SANGAT PUAS', 'bagus gk ya', 'uploads/img_67ca6ff0ed690.png', '2025-03-07 04:02:56'),
(5, 'SANGAT PUAS', 'oke', 'uploads/img_67ca9c1b943f9.png', '2025-03-07 07:11:23'),
(6, 'PUAS', 'bagus', 'uploads/img_67ceff5fe1e49.png', '2025-03-10 15:03:59'),
(7, 'PUAS', 'Fasilitas cukup nyaman, bisa lebih ditingkatkan.', 'uploads/img_67cf06729dd7f.png', '2025-03-10 15:34:10'),
(8, 'SANGAT PUAS', 'Pelayanan sangat baik dan cepat!', 'uploads/img_67cf072c28cf9.png', '2025-03-10 15:37:16'),
(9, 'SANGAT PUAS', 'Pelayanan sangat baik.', 'uploads/img_67cf945b1e26d.png', '2025-03-11 01:39:39'),
(10, 'SANGAT PUAS', 'Pelayanan sangat baik.', 'uploads/img_67cf947c1c8eb.png', '2025-03-11 01:40:12'),
(11, 'TIDAK PUAS', 'Staf kurang ramah dan tidak responsif.', 'uploads/img_67cf95e899b3b.png', '2025-03-11 01:46:16'),
(12, 'SANGAT PUAS', 'Pelayanan sangat baik.', 'uploads/img_67cf983f029c3.png', '2025-03-11 01:56:15'),
(13, 'PUAS', 'Cukup puas, tapi ada beberapa hal yang perlu diperbaiki.', 'uploads/img_67cf9852101c4.png', '2025-03-11 01:56:34'),
(14, 'PUAS', 'Cukup puas, tapi ada beberapa hal yang perlu diperbaiki.', 'uploads/img_67cf9976a45d1.png', '2025-03-11 02:01:26'),
(15, 'TIDAK PUAS', 'Staf kurang ramah dan tidak responsif.', 'uploads/img_67cfa69e5076d.png', '2025-03-11 02:57:34'),
(16, 'TIDAK PUAS', 'Staf kurang ramah dan tidak responsif.', 'uploads/img_67cfa6b16a469.png', '2025-03-11 02:57:53'),
(17, 'PUAS', 'Cukup puas, tapi ada beberapa hal yang perlu diperbaiki.', 'uploads/img_67cfa6b971771.png', '2025-03-11 02:58:01'),
(18, 'TIDAK PUAS', 'Staf kurang ramah dan tidak responsif.', 'uploads/img_67cfa8db4eb79.png', '2025-03-11 03:07:07'),
(19, 'TIDAK PUAS', 'Kurangnya informasi yang jelas.', 'uploads/img_67cfa8fd6f106.png', '2025-03-11 03:07:41'),
(20, 'TIDAK PUAS', 'Pelayanan lambat, mohon perbaikan ke depannya.', 'uploads/img_67cfa9283103d.png', '2025-03-11 03:08:24'),
(21, 'PUAS', 'Cukup puas, tapi ada beberapa hal yang perlu diperbaiki.', 'uploads/img_67cfa933128f6.png', '2025-03-11 03:08:35'),
(22, 'SANGAT PUAS', 'Datanya lengkap.', 'uploads/img_67cfaa6f05b7f.png', '2025-03-11 03:13:51'),
(23, 'SANGAT PUAS', 'Pelayanan sangat baik.', 'uploads/img_67cfaa9788844.png', '2025-03-11 03:14:31'),
(24, 'PUAS', 'Cukup puas, tapi ada beberapa hal yang perlu diperbaiki.', 'uploads/img_67cfaaa25d161.png', '2025-03-11 03:14:42'),
(25, 'TIDAK PUAS', 'Staf kurang ramah dan tidak responsif.', 'uploads/img_67cfb17533a34.png', '2025-03-11 03:43:49'),
(26, 'PUAS', 'Cukup puas, tapi ada beberapa hal yang perlu diperbaiki.', 'uploads/img_67cfb17e801f2.png', '2025-03-11 03:43:58'),
(27, 'SANGAT PUAS', 'Pelayanan sangat baik.', 'uploads/img_67cfb2dca8069.png', '2025-03-11 03:49:48'),
(28, 'SANGAT PUAS', 'Pelayanan sangat baik.', 'uploads/img_67cfb33810c15.png', '2025-03-11 03:51:20'),
(29, 'TIDAK PUAS', 'Staf kurang ramah dan tidak responsif.', 'uploads/img_67cfb340643cc.png', '2025-03-11 03:51:28'),
(30, 'SANGAT PUAS', 'Datanya lengkap.', 'uploads/img_67cfe10ba57d8.png', '2025-03-11 07:06:51'),
(31, 'SANGAT PUAS', 'Datanya lengkap.', 'uploads/img_67cfe2185e780.png', '2025-03-11 07:11:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tbl_survei`
--
ALTER TABLE `tbl_survei`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_survei`
--
ALTER TABLE `tbl_survei`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
