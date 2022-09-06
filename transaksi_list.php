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
?>
<!DOCTYPE html>
<html lang="en">
    <?php include("header.php"); ?>
<body>
    <?php include("head-navbar.php"); ?>

    <!-- right sidebar -->
    <?php include("right-sidebar.php"); ?>

    <!-- left sidebar -->
    <?php include("left-sidebar.php"); ?>

    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20">

            <?php if ($_SESSION['role'] == 'pelanggan'): ?>
                <div class="page-header">
                    <div class="row">
                        <div class="col-12">
                            <div class="title">
                                <h4 class="text-blue h4"><i class="dw dw-invoice-1"></i> Transaksi</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="page-header">
                    <?php $no=0; $Transaksi = $Transaksi->readAll(); while ($row = $Transaksi->fetch(PDO::FETCH_ASSOC)) : ?>
                        <div class="card mb-2">
                            <div class="card-header row mx-0">
                                <div class="col-6">
                                    <h4>Kode Transaksi : <?= $row['id_transaksi'] ?></h4>
                                </div>
                                <div class="col-6">
                                    <h4 class="float-right">Tanggal Transaksi : <?= $row['tgl_transaksi'] ?></h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-1">
                                    <div class="col">
                                        <h4>Status Transaksi : <?= ucwords($row['status']) ?></h4>
                                    </div>
                                    <div class="col">
                                    <h4>Metode Pembayaran : <?= $row['metode_pembayaran'] != "" ? $row['metode_pembayaran'] : "-" ?></h4>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Item</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                            $subtotal = 0;
                                            $TransaksiDetail->id_transaksi = $row['id_transaksi'];
                                            foreach($TransaksiDetail->readAll() as $item)
                                        :?>
                                        <tr>
                                            <th scope="row"><?= $i ?></th>
                                            <td><?= $item['nama'] ?></td>
                                            <td><?= number_format($item['harga'],0,'.','.'); ?></td>
                                            <td><?= $item['jumlah'] ?></td>
                                            <td><?= number_format($item['jumlah'] * $item['harga'],0,'.','.') ?></td>
                                        </tr>
                                        <?php 
                                            $subtotal += $item['jumlah']*$item['harga'];
                                            $i++;
                                            endforeach; 
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" align="right">Sub Total Item</td>
                                            <td><?= number_format($subtotal,0,'.','.'); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="right">Diskon</td>
                                            <td><?= number_format($row['potongan'],0,'.','.'); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" align="right">Total</td>
                                            <?php if($row['id_diskon'] != 0):?>
                                                <td><?= number_format($subtotal-$row['potongan'],0,'.','.'); ?></td>
                                            <?php else:?>
                                                    <td><?= number_format($subtotal,0,'.','.'); ?></td>
                                            <?php endif;?>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                *Catatan : <br/>
                                                <?php $no=1; foreach($TransaksiDetail->readAll() as $item): ?>
                                                    <?php if($item['catatan'] != null):?>
                                                        <?=$no;?>. <?=ucwords($item['nama'])?> <b>"<?=ucwords($item['catatan'])?>"</b> <br/>
                                                    <?php endif;?>
                                                <?php $no++; endforeach; ?>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <?php if($row['status'] == "belum bayar"):?>
                                    <form action="transaksi_batal.php" method="POST">
                                        <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi']?>">
                                        <button type="submit" class="btn btn-block btn-danger text-capitalize">Batalkan Transaksi</button>
                                    </form>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php $no++; endwhile; ?>

                    <?php if ($no == 0): ?>
                        <p class="text-center">Belum Ada Transaksi</p>
                    <?php endif;?>
                </div>
                
                
            <?php endif; ?>

            <!-- footer -->
            <?php include("footer.php"); ?>
        </div>
    </div>
    <!-- js -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
    <script src="vendors/scripts/dashboard.js"></script>
    <!-- bootstrap-touchspin js -->
    <script src="src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js"></script>
    <script src="vendors/scripts/advanced-components.js"></script>
    <script>
        $("input[name='jumlah']").TouchSpin({
            min: 0,
            max: 100
        });
    </script>
</body>
</html>