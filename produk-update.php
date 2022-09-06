<!DOCTYPE html>
<html>

<?php
    include("config.php");
    include_once('includes/produk.inc.php');

	session_start();
	if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

	$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

	$Produk = new Produk($db);
	$Produk->id_produk = $id;
	$Produk->readOne();
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
        // post img
		if(isset($_FILES['foto'])){
			$errors= array();
			$file_name = str_replace(" ", "-", $_FILES['foto']['name']);
			$file_size =$_FILES['foto']['size'];
			$file_tmp =$_FILES['foto']['tmp_name'];
			$file_type=$_FILES['foto']['type'];
			$tmp = explode('.', $file_name);
			$file_extension = end($tmp);
			$extensions= array("jpeg","jpg","png","pdf");

			if(in_array($file_extension,$extensions)=== false){
				$errors[]="extension not allowed, please choose a JPEG, JPG, PNG, or PDF file.";
			}

			if($file_size > 20097152){
				$errors[]='File size must be excately 20 MB';
			}

			if(empty($errors)==true){
				move_uploaded_file($file_tmp,"upload/".$file_name);
				// echo "Success";
			}else{
				print_r($errors);
			}
		}

		if($_POST){
			// update
			$Produk->id_produk = $_POST["id_produk"];
            $Produk->nama = $_POST["nama"];
            $Produk->kategori = $_POST["kategori"];
            $Produk->harga = $_POST["harga"];
            $Produk->keterangan = $_POST["keterangan"];

			// post name img
			if (!empty($_FILES['foto']['name'])){
				$Produk->foto = $_FILES['foto']['name'];
			} else{
				$Produk->foto = $Produk->foto;
			}

			if ($Produk->update()) {
				echo '<script language="javascript">';
                echo 'alert("Data Berhasil Terkirim")';
				echo '</script>';
				echo "<script>location.href='produk.php'</script>";
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
                        <input type="hidden" name="id_produk" value="<?php echo $Produk->id_produk; ?>">
                        <!-- hidden -->
                        <div style="padding-right:15px;">
                            <!-- <a href="ujian-create"> -->
                                <button type="submit" class="btn btn-success float-right">Simpan</button>
                            <!-- </a> -->
                        </div>
                        <!-- horizontal Basic Forms Start -->
                        <div class="pd-20 mb-30">
                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" class="form-control" name="nama" value="<?php echo $Produk->nama; ?>">
                            </div>
							<div class="form-group">
								<label>Kategori</label>
								<select class="selectpicker form-control form-control-lg" data-style="btn-outline-secondary btn-lg" title="Not Chosen" name="kategori">
									<option value="makanan" <?php if($Produk->kategori == 'makanan'): ?> selected <?php endif; ?>>Makanan</option>
									<option value="minuman" <?php if($Produk->kategori == 'minuman'): ?> selected <?php endif; ?> >Minuman</option>
									<option value="snack" <?php if($Produk->kategori == 'snack'): ?> selected <?php endif; ?> >Snack</option>
								</select>
							</div>
							<div class="form-group row">
                                <label class="col-12">Harga</label>
                                <div class="col-3 col-md-1" style="padding-right:5px;">
									<input class="form-control" type="text" value="Rp." readonly>
								</div>
								<div class="col-9 col-md-11" style="padding-left:0px;">
									<input class="form-control" type="number" min="0" name="harga" value="<?php echo $Produk->harga; ?>">
								</div>
                            </div>
							<div class="form-group">
                                <label>Foto <span><a href="#" onclick="showHide()"><i class="icon dw dw-exchange" style="color:#FF0000; border-radius: 99em; border: 1px solid #FF0000; box-shadow: 1px 1px 1px 4px rgb(255, 255, 255); padding: 4px;"></i></a></span></label> 
                                <input id="file_upload" type="file" class="form-control" name="foto" style="display:none;">
								<br/>
								<img id="file_img" src="upload/<?php echo $Produk->foto; ?>" alt="<?php echo $Produk->foto; ?>" style="width:200px;">
                            </div>
							<div class="form-group">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="keterangan" value="<?php echo $Produk->keterangan; ?>">
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
