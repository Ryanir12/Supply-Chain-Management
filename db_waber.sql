-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jul 2024 pada 16.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_waber`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`) VALUES
(9, NULL),
(10, NULL),
(11, NULL),
(12, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi_pembelian`
--

CREATE TABLE `detail_transaksi_pembelian` (
  `id_detail_transaksi` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah_beli` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi_penjualan`
--

CREATE TABLE `detail_transaksi_penjualan` (
  `id_detail_transaksi` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah_jual` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi_penjualan`
--

INSERT INTO `detail_transaksi_penjualan` (`id_detail_transaksi`, `id_transaksi`, `id_produk`, `jumlah_jual`, `harga_satuan`) VALUES
(43, 11, 9, 1, 27600000.00),
(44, 12, 10, 1, 18500000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `satuan_produk` varchar(20) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `satuan_produk`, `harga`, `stok`) VALUES
(1, 'TL-955 SMART SPINNING BIKE  SEPEDA OLAHRAGA FITNESS SEPEDA BALAP ', 'Unit', 3650000.00, 10),
(7, 'TL-960 SPINNING BIKE SEPEDA FITNESS INDOOR  SEPEDA STATIS', 'Unit', 2300000.00, 10),
(8, 'TL-926 EXERCISE BIKE SEPEDA STATIS MULTIFUNGSI SEPEDA OLAHRAGA ', 'Unit', 2550000.00, 10),
(9, 'TL-026 SMITH MACHINE TL-026 MULTI POWER RACK + BEBAN ALAT ANGKAT BEBAN ', 'Unit', 27600000.00, 5),
(10, 'TL-022 POWER RACK   ALAT ANGKAT BEBAN MULTIFUNGSI', 'Unit', 18500000.00, 10),
(11, 'TL-629 TREADMILL ELEKTRIK  MULTIFUNGSI', 'Unit', 3750000.00, 10),
(12, 'TL-8300 SPINNING BIKE ', 'Unit', 3380000.00, 10),
(13, 'TL-123M TREADMILL ELEKTRIK AUTO INCLINE ', 'Unit', 10200000.00, 10),
(14, 'TL-199 TREADMILL ELEKTRIK AUTO INCLINE', 'Unit', 10200000.00, 5),
(15, 'TL-8308 SPINNING BIKE ', 'Unit', 3750000.00, 10),
(16, 'TL-188 TREADMILL ELEKTRIK TOTAL FITNESS 3 HP', 'Unit', 10800000.00, 5),
(17, 'TL-121 TREADMILL ELEKTRIK MULTIFUNGSI ', 'Unit', 11200000.00, 5),
(18, 'TL-126 TREADMILL ELEKTRIK TOTAL FITNESS', 'Unit', 13800000.00, 5),
(19, 'TL-129 TREADMILL ELEKTRIK  ', 'Unit', 15800000.00, 3),
(20, 'TL-29 AC TREADMILL ELEKTRIK TOTAL FITNESS', 'Unit', 14900000.00, 3),
(21, 'TL-33 AC TREADMILL ELEKTRIK  ', 'Unit', 23000000.00, 2),
(22, 'TL-8290 TOTAL FITNES ORBITRACK ELLIPTICAL CROSSTRAINER', 'Unit', 3100000.00, 7),
(23, 'TL HG-077 HOME GYM SAMSAK ', 'Unit', 8100000.00, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(40) NOT NULL,
  `alamat_supplier` varchar(50) NOT NULL,
  `no_telp` varchar(50) NOT NULL DEFAULT '',
  `email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat_supplier`, `no_telp`, `email`) VALUES
(1, 'Yudi Hartono (Total FItness)', 'Jakarta Barat', '+62 856-1994-447', 'totalfitness@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_pembelian`
--

CREATE TABLE `transaksi_pembelian` (
  `id_transaksi` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_penjualan`
--

CREATE TABLE `transaksi_penjualan` (
  `id_transaksi` int(11) NOT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_penjualan`
--

INSERT INTO `transaksi_penjualan` (`id_transaksi`, `id_customer`, `total_harga`, `tanggal`) VALUES
(11, 11, 27600000.00, '2024-10-21'),
(12, 12, 18500000.00, '2024-07-13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `hak_akses` varchar(20) NOT NULL,
  `alamat` varchar(40) DEFAULT NULL,
  `jabatan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password`, `email`, `hak_akses`, `alamat`, `jabatan`) VALUES
(7, 'Ryan', 'admin', 'admin', 'onryanilhamramadhan@gmail.com', 'admin', 'Padang', 'admin'),
(8, 'Didi Waber', 'pimpinan', 'pimpinan', 'tes123@gmail.com', 'pimpinan', 'Padang', 'Pimpinan'),
(9, 'Yudi Hartono', 'supplier', 'supplier', 'totalfitness@gmail.com', 'supplier', 'Jakarta Barat', 'Supplier'),
(10, 'Gudang', 'gudang', 'gudang', 'gudang@gmail.com', 'gudang', 'Padang', 'Gudang');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indeks untuk tabel `detail_transaksi_pembelian`
--
ALTER TABLE `detail_transaksi_pembelian`
  ADD PRIMARY KEY (`id_detail_transaksi`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `detail_transaksi_penjualan`
--
ALTER TABLE `detail_transaksi_penjualan`
  ADD PRIMARY KEY (`id_detail_transaksi`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `transaksi_pembelian`
--
ALTER TABLE `transaksi_pembelian`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indeks untuk tabel `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi_pembelian`
--
ALTER TABLE `detail_transaksi_pembelian`
  MODIFY `id_detail_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi_penjualan`
--
ALTER TABLE `detail_transaksi_penjualan`
  MODIFY `id_detail_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `transaksi_pembelian`
--
ALTER TABLE `transaksi_pembelian`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaksi_pembelian`
--
ALTER TABLE `detail_transaksi_pembelian`
  ADD CONSTRAINT `detail_transaksi_pembelian_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi_pembelian` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_pembelian_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Ketidakleluasaan untuk tabel `detail_transaksi_penjualan`
--
ALTER TABLE `detail_transaksi_penjualan`
  ADD CONSTRAINT `detail_transaksi_penjualan_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi_penjualan` (`id_transaksi`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi_pembelian`
--
ALTER TABLE `transaksi_pembelian`
  ADD CONSTRAINT `transaksi_pembelian_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);

--
-- Ketidakleluasaan untuk tabel `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  ADD CONSTRAINT `transaksi_penjualan_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
