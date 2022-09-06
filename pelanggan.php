<!DOCTYPE html>
<html>

<?php
    include("config.php");
    include_once('includes/pelanggan.inc.php');
    include_once('includes/user.inc.php');

	session_start();
	if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

	$Pelanggan = new Pelanggan($db);
    $User = new User($db);
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
			// post pelanggan
			$Pelanggan->id_pelanggan = $_POST["id_pelanggan"];
            $Pelanggan->nama = $_POST["nama"];
            $Pelanggan->email = $_POST["email"];
            $Pelanggan->hp = $_POST["hp"];
            $Pelanggan->jenis_kelamin = $_POST["jenis_kelamin"];
            $Pelanggan->tgl_lahir = $_POST["tgl_lahir"];
			$Pelanggan->id_user = $_POST["id_user"];

			// post user
			$User->id_user = $_POST["id_user"];
			$User->username = $_POST["username"];
			$User->password = $_POST["password"];
			$User->role = $_POST["role"];

			if($User->insert() && $Pelanggan->insert()){
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
						<h4 class="text-blue h4"><i class="dw dw-user1"></i> Pelanggan</h4>
						<!-- <p class="mb-0">you can find more options <a class="text-primary" href="https://datatables.net/" target="_blank">Click Here</a></p> -->
                    </div>
                    <div style="padding-right:15px;">
                        <!-- <a href="user-create"> -->
                            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#createModal">Tambah</button>
                        <!-- </a> -->
                    </div>
                    <div class="pb-20">
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr class="text-center">
									<th>No</th>
									<th>Nama</th>
									<th>Email</th>
									<th>No. HP</th>
									<th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
									<?php if ($_SESSION['role'] == 'admin'): ?>
										<!-- <th>Action</th> -->
									<?php endif; ?>
								</tr>
							</thead>
							<tbody>
                                <?php $no=1; $Pelanggans = $Pelanggan->readAll(); while ($row = $Pelanggans->fetch(PDO::FETCH_ASSOC)) : ?>
								<tr class="text-center">
									<td><?=$no?></td>
									<td><?=$row['nama']?></td>
									<td><?=$row['email']?></td>
									<td>+62<?=$row['hp']?></td>
									<td>
										<?php if($row['jenis_kelamin'] == 'laki'): ?>
											Laki - Laki
										<?php else: ?>
											Perempuan
										<?php endif; ?>
									</td>
                                    <!-- date format -->
                                    <?php $date = strtotime($row['tgl_lahir']); ?>
                                    <td><?=date('d M Y', $date);?></td>
                                    <!-- date format -->
									<?php if ($_SESSION['role'] == 'admin'): ?>
										<!-- <td>
											<a class="dropdown-item link-action" href="pelanggan-send.php?id=<?php echo $row['id_pelanggan']; ?>"><i class="dw dw-mail"></i> Kirim</a> 
										</td> -->
									<?php endif; ?>
								</tr>
                                <?php $no++; endwhile; ?>
							</tbody>
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
									<input type="hidden" name="id_user" value="<?php echo $User->getNewId(); ?>">
									<input type="hidden" name="id_pelanggan" value="<?php echo $Pelanggan->getNewId(); ?>">
                                    <input type="hidden" name="role" value="pelanggan">
									<!-- hidden form -->
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Nama Lengkap<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="nama" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Username<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="username" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Password<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="password" class="form-control" name="password" required>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-sm-4 col-form-label">Email<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="email" class="form-control" name="email" required>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-sm-4 col-form-label">No Telpon<span style="color:red;">*</span></label>
										<div class="col-2" style="padding-right:5px;">
											<input class="form-control" type="text" value="62" readonly>
										</div>
										<div class="col-6" style="padding-left:0px;">
											<input class="form-control" type="number" min="0" name="hp" required>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-sm-4 col-form-label">Jenis Kelamin<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<div class="custom-control custom-radio custom-control-inline pb-0">
												<input type="radio" id="male" name="jenis_kelamin" class="custom-control-input" value="laki">
												<label class="custom-control-label" for="male">Laki - Laki</label>
											</div>
											<div class="custom-control custom-radio custom-control-inline pb-0">
												<input type="radio" id="female" name="jenis_kelamin" class="custom-control-input" value="perempuan">
												<label class="custom-control-label" for="female">Perempuan</label>
											</div>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-sm-4 col-form-label">Tanggal Lahir<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="date" class="form-control" name="tgl_lahir" required>
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
