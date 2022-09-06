<!DOCTYPE html>
<html>

<!-- header -->
<?php
	include("config.php");
	$config = new Config(); $db = $config->getConnection();

	include("header.php");

	include_once("includes/user.inc.php");
	$User = new User($db);

	include_once("includes/register.inc.php");
	$Register = new Register($db);

?>

<body class="login-page">
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="login.html">
					<img src="vendors/images/deskapp-logo.svg" alt="">
				</a>
			</div>
			<div class="login-menu">
				<ul>
					<li><a href="login.php">Login</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="register-page-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/register-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="register-box bg-white box-shadow border-radius-10">
						<div class="wizard-content">
							<?php
								if($_POST){
									// post pelanggan
									$Register->id_pelanggan = $_POST["id_pelanggan"];
									$Register->nama = $_POST["nama"];
									$Register->jenis_kelamin = $_POST["jenis_kelamin"];
									$Register->hp = $_POST["hp"];
									$Register->email = $_POST["email"];
									$Register->tgl_lahir = $_POST["tgl_lahir"];
									$Register->id_user = $_POST["id_user"];

									// post user
									$User->id_user = $_POST["id_user"];
									$User->username = $_POST["username"];
									$User->password = $_POST["password"];
									$User->role = $_POST["role"];

									if($User->insert() && $Register->insert()){
										echo '<script language="javascript">';
										echo 'alert("Data Berhasil Terkirim")';
										echo '</script>';
										echo "<script>location.href='login.php'</script>";
									} else { 
										echo '<script language="javascript">';
										echo 'alert("Data Gagal Terkirim")';
										echo '</script>';
									}
								}
							?>
							<form method="POST" class="tab-wizard2 wizard-circle wizard">
								<h5>Informasi Account</h5>
								<section>
									<div class="form-wrap max-width-600 mx-auto">
										<!-- hidden form -->
										<input type="hidden" name="id_user" value="<?php echo $User->getNewId(); ?>">
										<input type="hidden" name="id_pelanggan" value="<?php echo $Register->getNewId(); ?>">
										<input type="hidden" name="role" value="pelanggan">
										<!-- hidden form -->
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">Email<span style="color:red;">*</span></label>
											<div class="col-sm-8">
												<input type="email" class="form-control" name="email" required>
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
											<label class="col-sm-4 col-form-label">Nama Lengkap<span style="color:red;">*</span></label>
											<div class="col-sm-8">
												<input type="text" class="form-control" name="nama" required>
											</div>
										</div>
									</div>
								</section>
								<!-- Step 2 -->
								<h5>Data Diri</h5>
								<section>
									<div class="form-wrap max-width-600 mx-auto">
										<div class="form-group row">
											<label class="col-sm-4 col-form-label">No HP<span style="color:red;">*</span></label>
											<div class="col-2" style="padding-right:5px;">
												<input class="form-control" type="text" value="62" readonly>
											</div>
											<div class="col-6" style="padding-left:0px;">
												<input class="form-control" type="number" min="0" name="hp" required>
											</div>
										</div>
										<div class="form-group row align-items-center">
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
								</section>
								<!-- success Popup html Start -->
								<button type="button" id="success-modal-btn" hidden data-toggle="modal" data-target="#success-modal" data-backdrop="static">Launch modal</button>
								<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered max-width-400" role="document">
										<div class="modal-content">
											<div class="modal-body text-center font-18">
												<h3 class="mb-20">Apa data yang anda kirimkan sudah benar ?</h3>
												<div class="mb-30 text-center"><img src="vendors/images/success.png"></div>
												<!-- Terimakasih Telah Mendaftar ! -->
											</div>
											<div class="modal-footer justify-content-center">
												<!-- <a href="login.php" class="btn btn-primary">Done</a> -->
												<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary">Simpan</button>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- success Popup html End -->
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/jquery-steps/jquery.steps.js"></script>
	<script src="vendors/scripts/steps-setting.js"></script>
</body>

</html>