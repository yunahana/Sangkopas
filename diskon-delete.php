<?php

    include("config.php");
    include_once('includes/diskon.inc.php');

    session_start();
    if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

    $Diskon = new Diskon($db);
    $Diskon->id_diskon = $id;

    if($Diskon->delete()){
        echo "<script>alert('Berhasil Hapus Data');location.href='diskon.php';</script>";
    } else{
        echo "<script>alert('Gagal Hapus Data');location.href='ujian.php';</script>";
    }

?>
