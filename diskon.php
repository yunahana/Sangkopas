<!DOCTYPE html>
<html>

<?php
    include("config.php");
    include_once('includes/diskon.inc.php');

	session_start();
	if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

	$Diskon = new Diskon($db);
?>

<!-- header -->
<?php include("header.php"); ?>

<body>
	<!-- head navbar -->
	<?php include("head-navbar.php"); ?>

	<!-- right sidebar -->
	<?php include("right-sidebar.php"); ?>

	<!-- left sidebar -->
    <?php include("left-sidebar.php"); ?>
    
	<div class="mobile-menu-overlay"></div>

    <?php
		if($_POST){
			// post
			$Diskon->id_diskon = $_POST["id_diskon"];
            $Diskon->nama = $_POST["nama"];
            $Diskon->tgl_mulai = $_POST["tgl_mulai"];
            $Diskon->tgl_selesai = $_POST["tgl_selesai"];
			$Diskon->potongan = $_POST["potongan"];
            $Diskon->keterangan = $_POST["keterangan"];

			if($Diskon->insert()){
				echo '<script language="javascript">';
                echo 'alert("Data Berhasil Terkirim")';
                echo '</script>';
			} else { 
				echo '<script language="javascript">';
                echo 'alert("Data Gagal Terkirim")';
                echo '</script>';
			}
		}
	?>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<!-- Simple Datatable start -->
				<div class="card-box mb-30">
					<div class="pd-20">
						<h4 class="text-blue h4"><i class="dw dw-money-2"></i> Diskon</h4>
						<!-- <p class="mb-0">you can find more options <a class="text-primary" href="https://datatables.net/" target="_blank">Click Here</a></p> -->
                    </div>
                    <div style="padding-right:15px;">
                        <!-- <a href="user-create"> -->
                            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#createModal">Tambah</button>
							<?php if ($_SESSION['role'] == 'admin'): ?>
								<a href="diskon-send.php" class="btn btn-primary float-right" style="margin-right:15px;"><i class="dw dw-mail"></i> Kirim</a>
							<?php endif; ?>
						<!-- </a> -->
                    </div>
                    <div class="pb-20">
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr class="text-center">
									<th>No</th>
									<th>Nama</th>
									<th>Tgl Mulai</th>
									<th>Tgl Selesai</th>
									<th>Potongan</th>
                                    <th>Keterangan</th>
									<?php if ($_SESSION['role'] == 'admin'): ?>
										<th>Action</th>
									<?php endif; ?>
								</tr>
							</thead>
							<?php if ($_SESSION['role'] == 'admin'): ?>
								<tbody>
									<?php $no=1; $Diskons = $Diskon->readAll(); while ($row = $Diskons->fetch(PDO::FETCH_ASSOC)) : ?>
									<tr class="text-center">
										<td><?=$no?></td>
										<td><?=ucwords($row['nama'])?></td>
										<!-- date format -->
										<?php $date_mulai = strtotime($row['tgl_mulai']); ?>
										<td><?=date('d M Y', $date_mulai);?></td>
										<?php $date_selesai = strtotime($row['tgl_selesai']); ?>
										<td><?=date('d M Y', $date_selesai);?></td>
										<!-- date format -->
										<td>Rp. <?=$row['potongan']?></td>
										<td><?=ucwords($row['keterangan'])?></td>
										<td>
											<!-- <a class="dropdown-item link-action" href="diskon-detail.php?id=<?php echo $row['id_diskon']; ?>"><i class="dw dw-eye"></i> Detail</a> |  -->
											<a class="dropdown-item link-action" href="diskon-update.php?id=<?php echo $row['id_diskon']; ?>" data-color="#265ed7"><i class="dw dw-edit-1"></i> Update</a> | 
											<a class="dropdown-item link-action" href="diskon-delete.php?id=<?php echo $row['id_diskon']; ?>" data-color="#e95959"><i class="dw dw-delete-3"></i> Delete</a>
										</td>
									</tr>
									<?php $no++; endwhile; ?>
								</tbody>
							<?php else: ?>
								<tbody>
									<?php $no=1; $Diskons = $Diskon->readAllByDateNow(); while ($row = $Diskons->fetch(PDO::FETCH_ASSOC)) : ?>
									<tr class="text-center">
										<td><?=$no?></td>
										<td><?=ucwords($row['nama'])?></td>
										<!-- date format -->
										<?php $date_mulai = strtotime($row['tgl_mulai']); ?>
										<td><?=date('d M Y', $date_mulai);?></td>
										<?php $date_selesai = strtotime($row['tgl_selesai']); ?>
										<td><?=date('d M Y', $date_selesai);?></td>
										<!-- date format -->
										<td>Rp. <?=$row['potongan']?></td>
										<td><?=ucwords($row['keterangan'])?></td>
									</tr>
									<?php $no++; endwhile; ?>
								</tbody>
							<?php endif; ?>
						</table>
					</div>
				</div>
				<!-- Simple Datatable End -->

                <!-- Modal Create-->
                <div class="modal fade" id="createModal" role="dialog">
                    <div class="modal-dialog">
                        <form method="POST" enctype="multipart/form-data">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Tambah Data</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <!-- hidden form -->
									<input type="hidden" name="id_diskon" value="<?php echo $Diskon->getNewId(); ?>">
									<!-- hidden form -->
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Nama Diskon<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="nama" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Tgl Mulai<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="date" class="form-control" name="tgl_mulai" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Tgl Selesai<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="date" class="form-control" name="tgl_selesai" required>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-sm-4 col-form-label">Potongan<span style="color:red;">*</span></label>
										<div class="col-2" style="padding-right:5px;">
											<input class="form-control" type="text" value="Rp." readonly>
										</div>
										<div class="col-6" style="padding-left:0px;">
											<input class="form-control" type="number" min="0" name="potongan" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Keterangan<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="keterangan" required>
										</div>
									</div>
                                </div>
                                <div class="modal-footer">
                                    <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

			</div>
            <!-- footer -->
            <?php include("footer.php"); ?>
		</div>
	</div>
	<!-- js -->
    <?php include("script.php"); ?>
</body>
</html>
