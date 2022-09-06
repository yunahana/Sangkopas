<!DOCTYPE html>
<html>

<?php
    include("config.php");
    include_once('includes/diskon.inc.php');

	session_start();
	if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

	$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

	$Diskon = new Diskon($db);
	$Diskon->id_diskon = $id;
	$Diskon->readOne();
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
			// update
			$Diskon->id_diskon = $_POST["id_diskon"];
            $Diskon->nama = $_POST["nama"];
            $Diskon->tgl_mulai = $_POST["tgl_mulai"];
			$Diskon->tgl_selesai = $_POST["tgl_selesai"];
            $Diskon->potongan = $_POST["potongan"];
            $Diskon->keterangan = $_POST["keterangan"];

			if ($Diskon->update()) {
				echo '<script language="javascript">';
                echo 'alert("Data Berhasil Terkirim")';
				echo '</script>';
				echo "<script>location.href='diskon.php'</script>";
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
						<h4 class="text-blue h4"><i class="dw dw-edit-1"></i> Update Data</h4>
						<!-- <p class="mb-0">you can find more options <a class="text-primary" href="https://datatables.net/" target="_blank">Click Here</a></p> -->
                    </div>
					<form method="POST" enctype="multipart/form-data">
                        <!-- hidden -->
                        <input type="hidden" name="id_diskon" value="<?php echo $Diskon->id_diskon; ?>">
                        <!-- hidden -->
                        <div style="padding-right:15px;">
                            <!-- <a href="ujian-create"> -->
                                <button type="submit" class="btn btn-success float-right">Simpan</button>
                            <!-- </a> -->
                        </div>
                        <!-- horizontal Basic Forms Start -->
                        <div class="pd-20 mb-30">
                            <div class="form-group">
                                <label>Nama Diskon</label>
                                <input type="text" class="form-control" name="nama" value="<?php echo $Diskon->nama; ?>">
                            </div>
							<div class="form-group">
                                <label>Tgl Mulai</label>
                                <input type="date" class="form-control" name="tgl_mulai" value="<?php echo $Diskon->tgl_mulai; ?>">
                            </div>
							<div class="form-group">
                                <label>Tgl Selesai</label>
                                <input type="date" class="form-control" name="tgl_selesai" value="<?php echo $Diskon->tgl_selesai; ?>">
                            </div>
							<div class="form-group row">
                                <label class="col-12">Potongan</label>
                                <div class="col-3 col-md-1" style="padding-right:5px;">
									<input class="form-control" type="text" value="Rp." readonly>
								</div>
								<div class="col-9 col-md-11" style="padding-left:0px;">
									<input class="form-control" type="number" min="0" name="potongan" value="<?php echo $Diskon->potongan; ?>">
								</div>
                            </div>
							<div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" value="<?php echo $Diskon->keterangan; ?>">
                            </div>
                        </div>
					</form>
				</div>
				<!-- Simple Datatable End -->
			</div>
            <!-- footer -->
            <?php include("footer.php"); ?>
		</div>
	</div>
	<!-- js -->
    <?php include("script.php"); ?>
	<script>
		function showHide() {
			var upload = document.getElementById("file_upload");
			var img = document.getElementById("file_img");
			if (img.style.display === "none") {
				img.style.display = "block";
			} else {
				img.style.display = "none";
			}
			if (upload.style.display === "block") {
				upload.style.display = "none";
			} else {
				upload.style.display = "block";
			}
		}
	</script>
</body>
</html>
