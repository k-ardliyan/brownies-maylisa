-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jun 2020 pada 09.14
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_toko`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `id` int(12) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(97) NOT NULL,
  `tipe` set('guest','user','admin') NOT NULL,
  `nama` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(16) NOT NULL,
  `gambar` varchar(36) DEFAULT NULL,
  `token` varchar(97) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `akun`
--

INSERT INTO `akun` (`id`, `username`, `password`, `tipe`, `nama`, `email`, `alamat`, `telepon`, `gambar`, `token`) VALUES
(1, 'admin', '$argon2id$v=19$m=65536,t=4,p=1$VWFWcnh1N3R1V1pnZDBrWg$lDmS+m4PQD2Yc7rIv6cRbnbtV3B8sr1mbpukrmORoto', 'admin', 'Irfan Ammar Afif', 'ipangcheater@gmail.com', 'Rumah Brownies Maylisa', '082242747096', 'admin.png', NULL),
(2, 'user', '$argon2id$v=19$m=65536,t=4,p=1$dnpObS9sWnF1WDEyWW50bA$sgTWs9jR0jA7tci+UL/kgVQGDGW+PiYBQ67goKC7iI4', 'user', 'user', 'user@user', 'user', '123', 'user.jpg', NULL),
(3, 'guest', '$argon2id$v=19$m=65536,t=4,p=1$Nkt0b1pidHphb1lPSVFMRQ$BmgChcD5J8SZRf0CMfcG0nAZ9xWptdJDVrimftgJnHY', 'guest', 'guest', 'guest@guest', 'guest', '321', NULL, NULL),
(29, 'kitari', '$argon2id$v=19$m=65536,t=4,p=1$RS5PaW5KV05kQWFPbzVQTw$E6WA5EaSgEILj3GynhtHW3/vDkP6af6BZ9EnW+Unb6s', 'user', 'Kholifatul Ardliyan', 'k.ardliyan@gmail.com', 'Dimana aja', '081904898065', 'kitari.png', NULL),
(40, 'argon', '$argon2id$v=19$m=65536,t=4,p=1$NnhsTFZoNkZ3YVpXenJBbA$GQhKrA1SgYIwQdgbCtKItewuXa1qvK92/ljfbwuVNnQ', 'guest', 'Irfan Ammar Afif', 'ipangcheater@gmail.com', '2312', '23231231', NULL, NULL),
(41, 'irfan', '$argon2id$v=19$m=65536,t=4,p=1$NkpQRy5ITUNxWTdWTGVsQQ$IAqbvV8z35+1Mj9HUi4j4nVQJ7/o0Bp8KF1RW3c+JTg', 'user', 'Irfan Ammar Afif', 'ipangcheater@gmail.com', 'Sembarang', '082', 'irfan.jpg', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` int(12) NOT NULL,
  `nama` varchar(32) NOT NULL,
  `harga` int(32) NOT NULL,
  `stok` int(12) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id`, `nama`, `harga`, `stok`, `deskripsi`, `gambar`) VALUES
(27, 'Brownies Oven Kecil', 17000, 41, 'Brownies panggang rasa coklat maknyus ukuran kecil cocok buat dimakan sendirian.\r\nCocok buat yang suka tekstur renyah.', 'Brownies Oven Kecil.webp'),
(28, 'Brownies Oven Sedang', 30000, 62, 'Brownies panggang rasa coklat mantap ukuran sedang pas buat satu rumah.\r\nTeksturnya renyah di luar empuk di dalam.', 'Brownies Oven Sedang.webp'),
(30, 'Brownies Oven Besar', 57000, 20, 'Brownies panggang ukuran besar pas buat rame-rame', 'Brownies Oven Besar.webp'),
(31, 'Brownies Kukus Kecil', 20000, 24, 'Brownies kukus rasa coklat ukuran kecil pas buat dimakan sendirian.\r\nBrownies ini cocok buat kamu yang suka tekstur lembut.', 'Brownies Kukus Kecil.webp'),
(32, 'Brownies Kukus Sedang', 35000, 49, 'Brownies Kukus Ukuran Sedang', 'Brownies Kukus Sedang.webp'),
(33, 'Brownies Kukus Besar', 66000, 0, 'Brownies Kukus Ukuran Besar', 'Brownies Kukus Besar.webp'),
(34, 'Brownies Blondie', 28000, 14, 'Brownies panggang rasa susu ukuran sedang.\r\nCocok dimakan satu keluarga.', 'Brownies Blondie.webp'),
(35, 'Brownies Blondie Blueberry', 30000, 4, 'Brownies panggang rasa susu dengan selai blueberry yang mantap.\r\nUkuran sedang cocok dimakan satu keluarga.', 'Brownies Blondie Blueberry.webp');

-- --------------------------------------------------------

--
-- Struktur dari tabel `info`
--

CREATE TABLE `info` (
  `nama` varchar(64) NOT NULL,
  `telepon` varchar(16) NOT NULL,
  `email` varchar(64) NOT NULL,
  `alamat` text NOT NULL,
  `link_alamat` varchar(64) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `info`
--

INSERT INTO `info` (`nama`, `telepon`, `email`, `alamat`, `link_alamat`, `keterangan`) VALUES
('Soni Wicaksono Utomo', '087731394360', 'ipangcheater@gmail.com', 'Jalan Bukit Barisan D6 no. 3 Permata Puri\r\n50189\r\nNgaliyan, Semarang', 'https://goo.gl/maps/oHzHEVUb28YDBPdW6', 'Toko buka setiap hari dari jam 5 pagi sampai jam 8 malam.\r\n\r\n\r\nDIMOHON UNTUK MEMBAWA UANG PAS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notif`
--

CREATE TABLE `notif` (
  `id` int(12) NOT NULL,
  `id_user` int(12) NOT NULL,
  `pesan` tinytext NOT NULL,
  `tanggal` date NOT NULL,
  `terbaca` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `notif`
--

INSERT INTO `notif` (`id`, `id_user`, `pesan`, `tanggal`, `terbaca`) VALUES
(11, 2, 'Pesanan Anda dengan id: 119 sedang diproses', '2020-05-29', 1),
(12, 2, 'Pesanan Anda dengan id: 120 sedang diproses', '2020-05-29', 1),
(13, 2, 'Pesanan Anda dengan id: 121 ditolak', '2020-05-29', 1),
(14, 2, 'Pesanan Anda dengan id: 119 sudah selesai', '2020-05-29', 1),
(15, 2, 'Pesanan Anda dengan id: 120 sudah selesai', '2020-05-29', 1),
(16, 2, 'Pesanan Anda dengan id: 123 sedang diproses', '2020-05-30', 1),
(17, 2, 'Pesanan Anda dengan id: 123 sudah selesai', '2020-05-30', 1),
(18, 29, 'Pesanan Anda dengan id: 124 sedang diproses', '2020-05-30', 1),
(19, 29, 'Pesanan Anda dengan id: 124 sudah selesai', '2020-05-30', 1),
(20, 2, 'Pesanan Anda dengan id: 126 sedang diproses', '2020-06-01', 1),
(21, 2, 'Pesanan Anda dengan id: 131 sedang diproses', '2020-06-01', 1),
(22, 2, 'Pesanan Anda dengan id: 130 sedang diproses', '2020-06-01', 1),
(23, 2, 'Pesanan Anda dengan id: 126 sudah selesai', '2020-06-01', 1),
(24, 2, 'Pesanan Anda dengan id: 132 sedang diproses', '2020-06-01', 1),
(25, 2, 'Pesanan Anda dengan id: 136 sedang diproses', '2020-06-01', 1),
(26, 2, 'Pesanan Anda dengan id: 137 sedang diproses', '2020-06-01', 1),
(27, 2, 'Pesanan Anda dengan id: 138 sedang diproses', '2020-06-01', 1),
(29, 2, 'Pesanan Anda dengan id: 130 sudah selesai', '2020-06-02', 1),
(30, 2, 'Pesanan Anda dengan id: 131 sudah selesai', '2020-06-02', 1),
(31, 2, 'Pesanan Anda dengan id: 132 sudah selesai', '2020-06-02', 1),
(32, 2, 'Pesanan Anda dengan id: 136 sudah selesai', '2020-06-02', 1),
(33, 2, 'Pesanan Anda dengan id: 137 sudah selesai', '2020-06-02', 1),
(34, 2, 'Pesanan Anda dengan id: 138 sudah selesai', '2020-06-02', 1),
(36, 2, 'Pesanan Anda dengan id: 140 sedang diproses', '2020-06-10', 1),
(37, 29, 'Pesanan Anda dengan id: 129 sedang diproses', '2020-06-10', 1),
(38, 2, 'Pesanan Anda dengan id: 140 sudah selesai', '2020-06-10', 1),
(39, 29, 'Pesanan Anda dengan id: 146 sedang diproses', '2020-06-10', 1),
(40, 29, 'Pesanan Anda dengan id: 147 ditolak', '2020-06-10', 1),
(41, 29, 'Pesanan Anda dengan id: 129 sudah selesai', '2020-06-10', 1),
(42, 29, 'Pesanan Anda dengan id: 146 sudah selesai', '2020-06-10', 1),
(43, 2, 'Pesanan Anda dengan id: 151 ditolak', '2020-06-10', 1),
(44, 2, 'Pesanan Anda dengan id: 150 ditolak', '2020-06-10', 1),
(45, 2, 'Pesanan Anda dengan id: 148 ditolak', '2020-06-10', 1),
(46, 2, 'Pesanan Anda dengan id: 152 ditolak', '2020-06-10', 1),
(47, 2, 'Pesanan Anda dengan id: 151 ditolak', '2020-06-10', 1),
(48, 2, 'Pesanan Anda dengan id: 150 ditolak', '2020-06-10', 1),
(49, 2, 'Pesanan Anda dengan id: 148 ditolak', '2020-06-10', 1),
(50, 2, 'Pesanan Anda dengan id: 153 sedang diproses', '2020-06-10', 0),
(51, 2, 'Pesanan Anda dengan id: 153 sudah selesai', '2020-06-10', 0),
(52, 41, 'Pesanan Anda dengan id: 155 sedang diproses', '2020-06-11', 1),
(53, 41, 'Pesanan Anda dengan id: 154 ditolak', '2020-06-11', 1),
(54, 41, 'Pesanan Anda dengan id: 155 sudah selesai', '2020-06-11', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `review`
--

CREATE TABLE `review` (
  `id` int(12) NOT NULL,
  `id_user` int(12) NOT NULL,
  `id_barang` int(12) NOT NULL,
  `ulasan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `review`
--

INSERT INTO `review` (`id`, `id_user`, `id_barang`, `ulasan`) VALUES
(9, 2, 28, '1'),
(10, 29, 28, '2'),
(11, 2, 27, 'asd'),
(12, 29, 27, 'asdasdasd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(12) NOT NULL,
  `id_user` int(12) NOT NULL,
  `id_barang` int(12) NOT NULL,
  `jumlah` int(6) NOT NULL,
  `total` int(32) NOT NULL,
  `status` set('selesai','proses','menunggu konfirmasi','belum') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_user`, `id_barang`, `jumlah`, `total`, `status`) VALUES
(119, 2, 27, 1, 17000, 'selesai'),
(120, 2, 28, 1, 30000, 'selesai'),
(123, 2, 27, 1, 17000, 'selesai'),
(124, 29, 28, 1, 30000, 'selesai'),
(126, 2, 28, 1, 30000, 'selesai'),
(129, 29, 28, 1, 30000, 'selesai'),
(130, 2, 27, 1, 17000, 'selesai'),
(131, 2, 32, 10, 350000, 'selesai'),
(132, 2, 27, 1, 17000, 'selesai'),
(136, 2, 28, 1, 30000, 'selesai'),
(137, 2, 27, 1, 17000, 'selesai'),
(138, 2, 27, 1, 17000, 'selesai'),
(140, 2, 28, 1, 30000, 'selesai'),
(146, 29, 30, 1, 57000, 'selesai'),
(153, 2, 27, 4, 68000, 'selesai'),
(155, 41, 28, 1, 30000, 'selesai');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indeks untuk tabel `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_barang` (`id_barang`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `notif`
--
ALTER TABLE `notif`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT untuk tabel `review`
--
ALTER TABLE `review`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `notif`
--
ALTER TABLE `notif`
  ADD CONSTRAINT `notif_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `akun` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `akun` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `akun` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
