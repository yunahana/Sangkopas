<?php
    include("config.php");
    include_once('includes/produk.inc.php');
    include_once('includes/transaksi.inc.php');
    include_once('includes/transaksi_detail.inc.php');
    session_start();
    $id_user = $_SESSION['id_user'];
    $config = new Config(); 
    $db = $config->getConnection();
    $Produk = new Produk($db);
    $Transaksi = new Transaksi($db);
    $TransaksiDetail = new TransaksiDetail($db);

    $arr_produk = $_POST['produk'];
    $new = array_filter($arr_produk, function ($var) {
        return ($var['qty'] > 0);
    });
    if($Transaksi->getNewId() == 1){
        $id_transaksi = date('ymd')."001";
    }else{
        $id_transaksi = $Transaksi->getNewId();
    }
    $Transaksi->id_user =  $_SESSION['id_user'];
    $cek_transaksi = $Transaksi->readOne();

    $Transaksi->status =  "belum bayar";
    $Transaksi->tgl_transaksi =  date('Y-m-d');
    if($cek_transaksi != 1) {
        foreach($new as $n){
            $TransaksiDetail->id_produk =  $n['id_produk'];
            $cek_detail_transaksi = $TransaksiDetail->readOneByProduk();
            $Produk->id_produk = $n['id_produk'];
            $Produk->readOne(); 
            if($cek_detail_transaksi['status'] != 0){
                $TransaksiDetail->id_transaksi_detail =  $cek_detail_transaksi['id_transaksi_detail'];
                $TransaksiDetail->jumlah =  $n['qty'] + $cek_detail_transaksi['jumlah'];
                $TransaksiDetail->catatan =  $n['catatan'];
                $TransaksiDetail->updateJumlah();
                $Transaksi->id_transaksi = $cek_transaksi['id_transaksi'];
                $Transaksi->total_harga = $cek_transaksi['total_harga'] +  ($n['qty'] * $Produk->harga);
                $Transaksi->updateHarga();
                header("Location: index.php");
            }else{
                $TransaksiDetail->id_transaksi_detail = $TransaksiDetail->getNewId();
                $TransaksiDetail->id_transaksi =  $cek_transaksi['id_transaksi'];
                $TransaksiDetail->id_produk =  $n['id_produk'];
                $TransaksiDetail->harga =  $Produk->harga;
                $TransaksiDetail->jumlah =  $n['qty'];
                $TransaksiDetail->catatan =  $n['catatan'];
                $TransaksiDetail->insert();
                $Transaksi->id_transaksi = $cek_transaksi['id_transaksi'];
                $Transaksi->total_harga = $cek_transaksi['total_harga'] +  ($n['qty'] * $Produk->harga);
                $Transaksi->updateHarga();
                header("Location: index.php");
            }
        }
    }else{
        $Transaksi->id_transaksi = $id_transaksi;
        $Transaksi->insert();
        $sub_total_produk = 0;
        foreach($new as $n) {
            $Produk->id_produk = $n['id_produk'];
            $Produk->readOne(); 
            $cek_transaksi = $Transaksi->readOne();
            $TransaksiDetail->id_transaksi_detail = $TransaksiDetail->getNewId();
            $TransaksiDetail->id_transaksi = $id_transaksi;
            $TransaksiDetail->id_produk =  $n['id_produk'];
            $TransaksiDetail->harga =  $Produk->harga;
            $TransaksiDetail->jumlah =  $n['qty'];
            $TransaksiDetail->catatan =  $n['catatan'];
            $TransaksiDetail->insert();
            $sub_total_produk += $n['qty'] * $Produk->harga;
        }
        $Transaksi->total_harga = $sub_total_produk;
        $Transaksi->updateHarga();
        
        header("Location: transaksi_list.php?id=".$id_user."");
    }