/*
MySQL Backup
Source Server Version: 5.1.31
Source Database: hayun
Date: 8/22/2022 22:20:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `diskon`
-- ----------------------------
DROP TABLE IF EXISTS `diskon`;
CREATE TABLE `diskon` (
  `id_diskon` int(11) NOT NULL DEFAULT '0',
  `nama` varchar(255) DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `potongan` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_diskon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `pelanggan`
-- ----------------------------
DROP TABLE IF EXISTS `pelanggan`;
CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `hp` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('laki','perempuan') DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `produk`
-- ----------------------------
DROP TABLE IF EXISTS `produk`;
CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL DEFAULT '0',
  `nama` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `harga` varchar(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `transaksi`
-- ----------------------------
DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) DEFAULT NULL,
  `tgl_transaksi` date DEFAULT NULL,
  `metode_pembayaran` enum('Tunai','Non Tunai') DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `status` enum('belum bayar','lunas') DEFAULT NULL,
  `id_diskon` int(11) DEFAULT NULL,
  `no_meja` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `transaksi_detail`
-- ----------------------------
DROP TABLE IF EXISTS `transaksi_detail`;
CREATE TABLE `transaksi_detail` (
  `id_transaksi_detail` int(11) NOT NULL DEFAULT '0',
  `id_transaksi` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_transaksi_detail`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `role` enum('admin','kasir','pelanggan') DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `diskon` VALUES ('1','Diskon Hari Kemerdekaan','2022-08-15','2022-08-20','12000','Minimal pembelian makanan dan minuman 50.000'), ('2','Diskon 21 Aug','2022-08-21','2022-08-21','10000','minimal pembelian 20000 keatas');
INSERT INTO `pelanggan` VALUES ('1','3','Ricky Rodesta','ricky@mail.com','82240454123','laki','1995-12-22'), ('2','4','Nisa Wiguna','nisa@mail.com','81293212347','perempuan','1998-10-15'), ('3','5','Anis','anis@mail.com','81273612374','perempuan','1997-11-18'), ('4','6','Hayun','hayun@mail.com','82146483679','perempuan','2010-02-18');
INSERT INTO `produk` VALUES ('1','Nasi Goreng','makanan','18000','nasigoreng.jpg','Nasi Goreng dengan Telur'), ('3','Ayam Kremes','makanan','15000','ayamkremes.jpg','Ayam Kremes Renyah'), ('4','Es Teh','minuman','5000','esteh.jpg','Es teh manis atau tawar'), ('5','Kentang Goreng','snack','10000','kentanggoreng.jpg','Kentang Goreng Renyah');
INSERT INTO `transaksi_detail` VALUES ('1','220820001','1','18000','3',NULL), ('2','220820001','3','15000','3',NULL), ('3','220820001','4','5000','6',NULL), ('4','220820001','5','10000','2',NULL), ('5','220820002','1','18000','2',NULL), ('6','220820002','3','15000','2',NULL), ('7','220820002','4','5000','5',NULL), ('8','220820003','3','15000','3',NULL), ('9','220820003','4','5000','5',NULL), ('10','220820003','5','10000','1',NULL), ('11','220820004','1','18000','2',NULL), ('12','220820004','4','5000','2',NULL);
INSERT INTO `user` VALUES ('1','admin','admin','admin','Admin Sang Kopas'), ('2','kasir','kasir','kasir','Kasir Sang Kopas'), ('3','ricky','ricky','pelanggan',NULL), ('4','nisa','nisa','pelanggan',NULL), ('5','anis','anis','pelanggan',NULL), ('6','hayun','hayun','pelanggan',NULL);
