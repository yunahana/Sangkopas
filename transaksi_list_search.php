<?php
    include("config.php");
    include_once('includes/transaksi.inc.php');
    include_once('includes/transaksi_detail.inc.php');
    include_once('includes/diskon.inc.php');
    $config = new Config(); 
    $db = $config->getConnection();

	$Transaksi = new Transaksi($db);
	$TransaksiDetail = new TransaksiDetail($db);
    $Diskon = new Diskon($db);
    $Transaksi->no_tran = $_GET['no_tran'];
    session_start();
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

            <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'kasir'): ?>
                <div class="page-header">
                    <div class="row">
                        <div class="col-12">
                            <div class="title">
                                <h4 class="text-blue h4"><i class="dw dw-invoice-1"></i> Transaksi</h4>
                                <form action="transaksi_list_search.php" method="GET">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="no_tran" placeholder="Cari nomor transaksi" value="<?= $_GET['no_tran']?>">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-info btn-sm">Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="page-header">
                    <?php 
                        $no=0; 
                        $Transaksi = $Transaksi->readAllTransaksiSearch(); 
                        while ($row = $Transaksi->fetch(PDO::FETCH_ASSOC)) : 
                    ?>
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
                                    <h4>Metode Pembayaran : <?= $row['metode_pembayaran'] != "" ? ucwords($row['metode_pembayaran']) : "-" ?></h4>
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
                                <?php if($row['status'] != 'lunas'): ?>
                                    <button class="btn btn-block btn-success mb-2" data-toggle="modal" data-target="#verifikasiModal<?= $row['id_transaksi'] ?>">Verifikasi Pembayaran</button>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="modal fade" id="verifikasiModal<?= $row['id_transaksi'] ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <form action="transaksi_verifikasi.php" method="POST" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Verikasi Pembayaran</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="id_transaksi">Id Transaksi</label>
                                            <input type="text" class="form-control" name="id_transaksi" id="id_transaksi" value="<?= $row['id_transaksi'] ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="metode_pembayaran">Metode Pembayaran</label>
                                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control">
                                                <option value="Tunai">Tunai</option>
                                                <option value="Non Tunai">Non Tunai</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="diskon">Diskon</label>
                                            <select name="diskon" id="diskon" class="form-control">
                                                <option value="0">Tidak Ada Diskon</option>
                                                <?php foreach($Diskon->readAll() as $d):?>
                                                    <option value="<?= $d['id_diskon'] ?>"><?= $d['nama'] ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <input type="text" class="form-control" name="status_read" id="status_read" value="Lunas" readonly>
                                            <input type="hidden" name="status" id="status" value="lunas">
                                        </div>
                                        <div class="form-group">
                                            <label for="no_meja">Nomor Meja</label>
                                            <select name="no_meja" id="no_meja" class="form-control">
                                                <?php for($i = 1; $i <= 12; $i++):?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php endfor;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
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