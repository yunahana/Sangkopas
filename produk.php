<!DOCTYPE html>
<html>

<?php
    include("config.php");
    include_once('includes/produk.inc.php');

	session_start();
	if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

	$Produk = new Produk($db);
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
			// post
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

			if($Produk->insert()){
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
						<h4 class="text-blue h4"><i class="dw dw-price-tag"></i> Produk</h4>
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
									<th>Kategori</th>
									<th>Harga</th>
									<th>Foto</th>
                                    <th>Keterangan</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
                                <?php $no=1; $Produks = $Produk->readAll(); while ($row = $Produks->fetch(PDO::FETCH_ASSOC)) : ?>
								<tr class="text-center">
									<td><?=$no?></td>
									<td><?=ucwords($row['nama'])?></td>
									<td><?=ucwords($row['kategori'])?></td>
									<td>Rp. <?=$row['harga']?></td>
									<td>
										<img src="upload/<?=$row['foto']?>" alt="<?=$row['foto']?>" style="width:70px;">
									</td>
									<td><?=$row['keterangan']?></td>
									<td>
                                        <!-- <a class="dropdown-item link-action" href="produk-detail.php?id=<?php echo $row['id_produk']; ?>"><i class="dw dw-eye"></i> Detail</a> |  -->
										<a class="dropdown-item link-action" href="produk-update.php?id=<?php echo $row['id_produk']; ?>" data-color="#265ed7"><i class="dw dw-edit-1"></i> Update</a> | 
										<a class="dropdown-item link-action" href="produk-delete.php?id=<?php echo $row['id_produk']; ?>" data-color="#e95959"><i class="dw dw-delete-3"></i> Delete</a>
									</td>
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
									<input type="hidden" name="id_produk" value="<?php echo $Produk->getNewId(); ?>">
									<!-- hidden form -->
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Nama Produk<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="text" class="form-control" name="nama" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-4 col-form-label">Kategori<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<select class="custom-select col-12" name="kategori">
												<option selected disabled>Pilih...</option>
												<option value="makanan">Makanan</option>
												<option value="minuman">Minuman</option>
												<option value="snack">Snack</option>
											</select>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-sm-4 col-form-label">Harga<span style="color:red;">*</span></label>
										<div class="col-2" style="padding-right:5px;">
											<input class="form-control" type="text" value="Rp." readonly>
										</div>
										<div class="col-6" style="padding-left:0px;">
											<input class="form-control" type="number" min="0" name="harga" required>
										</div>
									</div>
                                    <div class="form-group row">
										<label class="col-sm-4 col-form-label">Foto<span style="color:red;">*</span></label>
										<div class="col-sm-8">
											<input type="file" class="form-control" name="foto" required>
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
