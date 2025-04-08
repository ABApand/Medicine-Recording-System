-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 04:58 AM
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
-- Database: `medicine_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id` int(11) NOT NULL,
  `nama_pasien` varchar(100) DEFAULT NULL,
  `no_rm` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_resep` varchar(50) DEFAULT NULL,
  `tanggal_keluar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `petugas` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_barang_keluar`
--

CREATE TABLE `detail_barang_keluar` (
  `id` int(11) NOT NULL,
  `id_barang_keluar` int(11) DEFAULT NULL,
  `nama_item` varchar(100) DEFAULT NULL,
  `takaran` varchar(50) DEFAULT NULL,
  `jumlah_tablet` int(11) DEFAULT NULL,
  `catatan_pagi` varchar(100) DEFAULT NULL,
  `catatan_siang` varchar(100) DEFAULT NULL,
  `catatan_makan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_barang_masuk`
--

CREATE TABLE `detail_barang_masuk` (
  `id` int(11) NOT NULL,
  `barang_masuk_id` int(11) NOT NULL,
  `nama_item` varchar(100) NOT NULL,
  `takaran` varchar(50) DEFAULT NULL,
  `uom` varchar(50) DEFAULT NULL,
  `jumlah_tablet` int(11) DEFAULT NULL,
  `kadaluarsa` date DEFAULT NULL,
  `no_rak` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_item` varchar(100) DEFAULT NULL,
  `takaran` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `uom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama_item`, `takaran`, `stok`, `uom`) VALUES
(15, 'Paracetamol', '500 mg', 100, 'Tablet'),
(16, 'Ibuprofen', '400 mg', 80, 'Tablet'),
(17, 'Amoxicillin', '500 mg', 60, 'Kapsul'),
(18, 'Ciprofloxacin', '500 mg', 50, 'Tablet'),
(19, 'Metformin', '500 mg', 120, 'Tablet'),
(20, 'Amlodipine', '5 mg', 90, 'Tablet'),
(21, 'Simvastatin', '10 mg', 100, 'Tablet'),
(22, 'Omeprazole', '20 mg', 70, 'Kapsul'),
(23, 'Cetirizine', '10 mg', 95, 'Tablet'),
(24, 'Loperamide', '2 mg', 40, 'Tablet'),
(25, 'Ranitidine', '150 mg', 60, 'Tablet'),
(26, 'Captopril', '25 mg', 75, 'Tablet'),
(27, 'Furosemide', '40 mg', 50, 'Tablet'),
(28, 'Antasida DOEN', '15 ml', 30, 'Sirup'),
(29, 'Vitamin C', '500 mg', 100, 'Tablet'),
(30, 'Salbutamol', '2 mg', 39, 'Tablet'),
(31, 'Prednison', '5 mg', 53, 'Tablet'),
(32, 'Asam Mefenamat', '500 mg', 63, 'Tablet'),
(33, 'Dextromethorphan', '10 mg', 25, 'Sirup'),
(34, 'Loratadine', '10 mg', 55, 'Tablet');

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `no_rm` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `no_resep` varchar(100) DEFAULT NULL,
  `nama_item` varchar(255) DEFAULT NULL,
  `takaran` varchar(100) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resep`
--

INSERT INTO `resep` (`id`, `nama`, `no_rm`, `tgl_lahir`, `no_resep`, `nama_item`, `takaran`, `jumlah`, `catatan`, `created_at`) VALUES
(26, 'Apand', '123123', '2025-04-08', '123213', 'Loratadine', '10 mg', 1, '', '2025-04-06 05:24:55'),
(27, 'sadsadsad', 'sadsad', '2025-04-15', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 05:24:55'),
(28, 'Apand', 'sadsad', '2025-04-16', 'asdsads', 'Loratadine', '10 mg', 1, '', '2025-04-06 05:24:55'),
(29, 'Apand', 'sadsad', '2025-04-16', 'asdsads', 'Asam Mefenamat', '500 mg', 1, '', '2025-04-06 05:24:55'),
(30, 'Apand', 'sadsad', '2025-04-16', 'asdsads', 'Prednison', '5 mg', 1, '', '2025-04-06 05:24:55'),
(31, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(32, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(33, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(34, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(35, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(36, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(37, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(38, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(39, 'asdsa', 'sadsa', '2025-04-08', 'asdsad', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:22:34'),
(40, 'sadsa', 'sadsa', '2025-04-15', 'sadsa', 'Loratadine', '10 mg', 1, '', '2025-04-06 06:26:18'),
(41, 'Apand', 'sadsadsa', '2025-04-17', 'sadsadsa', 'Loratadine', '10 mg', 1, '', '2025-04-06 09:07:09'),
(42, 'Apand', 'sadsadsa', '2025-04-17', 'sadsadsa', 'Loratadine', '10 mg', 1, '', '2025-04-06 09:07:09'),
(43, 'Apand', 'sadsadsa', '2025-04-17', 'sadsadsa', 'Asam Mefenamat', '500 mg', 1, '', '2025-04-06 09:07:09'),
(44, 'Apand', 'sadsadsa', '2025-04-17', 'sadsadsa', 'Prednison', '5 mg', 1, '', '2025-04-06 09:07:09'),
(45, 'Apand', 'sadsadsa', '2025-04-17', 'sadsadsa', 'Salbutamol', '2 mg', 1, '', '2025-04-06 09:07:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`) VALUES
(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Administrator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_barang_keluar`
--
ALTER TABLE `detail_barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_barang_keluar` (`id_barang_keluar`);

--
-- Indexes for table `detail_barang_masuk`
--
ALTER TABLE `detail_barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_masuk_id` (`barang_masuk_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resep`
--
ALTER TABLE `resep`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `detail_barang_keluar`
--
ALTER TABLE `detail_barang_keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_barang_masuk`
--
ALTER TABLE `detail_barang_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `resep`
--
ALTER TABLE `resep`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_barang_keluar`
--
ALTER TABLE `detail_barang_keluar`
  ADD CONSTRAINT `detail_barang_keluar_ibfk_1` FOREIGN KEY (`id_barang_keluar`) REFERENCES `barang_keluar` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_barang_masuk`
--
ALTER TABLE `detail_barang_masuk`
  ADD CONSTRAINT `detail_barang_masuk_ibfk_1` FOREIGN KEY (`barang_masuk_id`) REFERENCES `barang_masuk` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
