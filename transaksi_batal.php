<?php
    include("config.php");
    include_once('includes/transaksi.inc.php');
    include_once('includes/transaksi_detail.inc.php');
    $config = new Config(); 
    $db = $config->getConnection();

	$Transaksi = new Transaksi($db);
	$TransaksiDetail = new TransaksiDetail($db);
    session_start();
    $Transaksi->id_user =  $_SESSION['id_user'];

    $Transaksi->id_transaksi = $_POST['id_transaksi'];
    $Transaksi->delete();
    $TransaksiDetail->id_transaksi = $_POST['id_transaksi'];
    $TransaksiDetail->delete();
    header("Location: transaksi_list.php?id=".$id_user."");