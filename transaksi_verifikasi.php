<?php
    include("config.php");
    include_once('includes/Transaksi.inc.php');
    session_start();
    $config = new Config(); 
    $db = $config->getConnection();
    $Transaksi = new Transaksi($db);

    $id_transaksi = $_POST["id_transaksi"];
    $metode_pembayaran = $_POST["metode_pembayaran"];
    $diskon = $_POST["diskon"];
    $no_meja = $_POST["no_meja"];
    $status = $_POST["status"];

    $Transaksi->id_transaksi = $id_transaksi;
    $Transaksi->metode_pembayaran = $metode_pembayaran;
    $Transaksi->diskon = $diskon;
    $Transaksi->no_meja = $no_meja;
    $Transaksi->status = $status;
    $Transaksi->updateVerifikasiTransaksi();
    header("Location: transaksi_list_all.php");
