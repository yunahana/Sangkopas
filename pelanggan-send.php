<!DOCTYPE html>
<html>

<?php
    include("config.php");
    include_once('includes/pelanggan.inc.php');
	include_once('includes/diskon.inc.php');

	session_start();
	if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

	$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

	$Pelanggan = new Pelanggan($db);
	$Pelanggan->id_pelanggan = $id;
	$Pelanggan->readOne();
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
			// Get Data Pelanggan
            $nama_pelanggan = $_POST["nama"];
            $no_pelanggan = $_POST["hp"];
			// Get Data Diskon
			$data_diskon = $_POST["diskon"];
			list($nama, $tgl_mulai, $tgl_selesai, $potongan, $keterangan) = explode(' , ', $data_diskon);
			$nama_diskon = $nama;
            $date_mulai = strtotime($tgl_mulai);
            $tgl_mulai = date('d M Y', $date_mulai);
			$date_selesai = strtotime($tgl_selesai);
            $tgl_selesai = date('d M Y', $date_selesai);
			$potongan = number_format($potongan,0,',','.');
			$keterangan = $keterangan;

			if($nama_diskon != null){
				echo '<script language="javascript">';
                echo 'alert("Pesan Berhasil Terkirim")';
				echo '</script>';
				echo "<script>location='https://api.whatsapp.com/send?phone=".$no_pelanggan."&text=Hallo ".$nama_pelanggan." nikmati ".$nama_diskon." dengan potongan sebesar Rp.".$potongan." untuk pembelian di Cafe Sangkopas pada tanggal ".$tgl_mulai." sampai tanggal ".$tgl_selesai.", dengan ketentuan ".$keterangan.". Jangan sampai anda kelewatan. *Wajib tunjukkan pesan ini saat memesan di Cafe Sangkopas.&source=&data='</script>";
			} else { 
				echo '<script language="javascript">';
                echo 'alert("Pesan Gagal Terkirim")';
                echo '</script>';
				echo "<script>location.href='pelanggan.php'</script>";
			}
		}
	?>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<!-- Simple Datatable start -->
				<div class="card-box mb-30">
					<div class="pd-20">
						<h4 class="text-blue h4"><i class="dw dw-mail"></i> Bagikan Diskon</h4>
						<!-- <p class="mb-0">you can find more options <a class="text-primary" href="https://datatables.net/" target="_blank">Click Here</a></p> -->
                    </div>
					<form method="POST" enctype="multipart/form-data">
                        <div style="padding-right:15px;">
                            <button type="submit" class="btn btn-success float-right">Kirim Pesan</button>
                        </div>
                        <!-- horizontal Basic Forms Start -->
                        <div class="pd-20 mb-30 row">
                            <div class="form-group col-6">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" value="<?php echo $Pelanggan->nama; ?>" readonly>
                            </div>
							<div class="form-group col-6">
                                <label>No. Hp</label>
                                <input type="text" class="form-control" name="hp" value="+62<?php echo $Pelanggan->hp; ?>" readonly>
                            </div>
							<div class="form-group col-6">
                                <label>Jenis Kelamin</label>
								<?php if($Pelanggan->jenis_kelamin == 'laki'):?>
                                <input type="text" class="form-control" name="jenis_kelamin" value="Laki - Laki" readonly>
								<?php else:?>
								<input type="text" class="form-control" name="jenis_kelamin" value="Perempuan" readonly>
								<?php endif;?>
							</div>
							<div class="form-group col-6">
                                <label>Tanggal Lahir</label>
                                <input type="text" class="form-control" name="tgl_lahir" value="<?php echo $Pelanggan->tgl_lahir; ?>" readonly>
                            </div>
							<div class="form-group col-12">
								<label>Diskon<span style="color:red;">*</span></label>
								<select class="selectpicker form-control form-control-lg" data-style="btn-outline-secondary btn-lg" title="Diskon" name="diskon" required>
									<option selected disabled>Pilih...</option>
									<?php $Diskons = $Diskon->readAllByDateNow(); while ($row = $Diskons->fetch(PDO::FETCH_ASSOC)) : ?>
										<option value="<?=$row['nama']?> , <?=$row['tgl_mulai']?> , <?=$row['tgl_selesai']?> , <?=$row['potongan']?> , <?=$row['keterangan']?>"><?=ucwords($row['nama'])?></option>
									<?php endwhile; ?>
								</select>
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
		
	</script>
</body>
</html>
