-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 03 Jul 2026 pada 16.32
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
-- Database: `db_cafe_uas`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `harga`, `stok`, `kategori`, `foto`) VALUES
(1, 'Ice Caramel Latte', 24000, 37, 'Minuman', 'uploads/1783073204_Ice_Caramel_Latte.png'),
(2, 'Es Kopi Susu Aren', 18000, 51, 'Minuman', 'uploads/1783073243_Es_Kopi_Susu_Aren.png'),
(3, 'Ayam Geprek + Nasi', 22000, 26, 'Makanan', 'uploads/1783073303_Ayam_Geprek___Nasi.png'),
(4, 'Croissant Almond', 19000, 19, 'Makanan', 'uploads/1783073316_Croissant_Almond.png'),
(5, 'Matcha Green Tea', 21000, 45, 'Minuman', 'uploads/1783073331_Matcha_Green_Tea.png'),
(7, 'Cloud Velvet Latte', 26000, 60, 'Minuman', 'uploads/1783083730_Cloud_Velvet_Latte.png'),
(8, 'Midnight Mint Mocha', 27000, 45, 'Minuman', 'uploads/1783083984_Midnight_Mint_Mocha.png'),
(9, 'Golden Hour Macchiato', 25000, 70, 'Minuman', 'uploads/1783084361_Golden_Hour_Macchiato.png'),
(10, 'Coconut Breeze Americano', 22000, 79, 'Minuman', 'uploads/1783084376_Coconut_Breeze_Americano.png'),
(15, 'Smoked Beef Croissant Sandwich', 27000, 30, 'Makanan', 'uploads/1783084689_Smoked_Beef_Croissant_Sandwich.png'),
(17, 'Korean Honey Butter Chicken Wings', 28000, 45, 'Makanan', 'uploads/1783085203_Korean_Honey_Butter_Chicken_Wings.png'),
(18, 'Crispy Chicken Nanban Bowl', 29000, 50, 'Makanan', 'uploads/1783085218_Crispy_Chicken_Nanban_Bowl.png'),
(28, 'Mango Sticky Rice', 10000, 18, 'Dessert', 'uploads/1783086402_Mango_Sticky_Rice.png'),
(29, 'Nasi Gila Pedas', 23000, 22, 'Makanan', 'uploads/1783087307_Nasi_Gila_Pedas.png'),
(34, 'Kentang Goreng Bolognaise', 19000, 25, 'Makanan', 'uploads/1783087519_Kentang_Goreng_Bolognaise.png'),
(35, 'Es Lemon Mint', 16000, 45, 'Minuman', 'uploads/1783086462_Es_Lemon_Mint.png'),
(39, 'Banana Toffee Pudding', 22000, 16, 'Dessert', 'uploads/1783086870_Banana_Toffee_Pudding.png'),
(40, 'Risol Melted Artisan', 5000, 45, 'Makanan', 'uploads/1783086590_Risol_Melted_Artisan.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `no_nota` varchar(50) NOT NULL,
  `kode_lacak` varchar(10) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `tag_pesanan` varchar(255) DEFAULT NULL,
  `total_harga` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Selesai',
  `tanggal_transaksi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- (Dumping data transaksi dummy sengaja dihapus agar database bersih)

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama_kasir` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_kasir`, `role`) VALUES
(1, 'kasir1', 'kasir123', 'Khoerul Fadli', '');

--
-- Indexes for dumped tables
--

ALTER TABLE `produk` ADD PRIMARY KEY (`id_produk`);
ALTER TABLE `transaksi` ADD PRIMARY KEY (`id_transaksi`);
ALTER TABLE `users` ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel
--

ALTER TABLE `produk` MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
ALTER TABLE `transaksi` MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `users` MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;