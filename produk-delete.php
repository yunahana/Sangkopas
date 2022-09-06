<?php

    include("config.php");
    include_once('includes/produk.inc.php');

    session_start();
    if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

    $Produk = new Produk($db);
    $Produk->id_produk = $id;

    if($Produk->delete()){
        echo "<script>alert('Berhasil Hapus Data');location.href='produk.php';</script>";
    } else{
        echo "<script>alert('Gagal Hapus Data');location.href='ujian.php';</script>";
    }

?>
