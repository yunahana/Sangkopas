<!DOCTYPE html>
<html>

<?php
    include("config.php");
    include_once('includes/pelanggan.inc.php');
	include_once('includes/diskon.inc.php');

	session_start();
	if (!isset($_SESSION['id_user']) && $_SESSION['role'] != 'pelanggan') echo "<script>location.href='login.php'</script>";
    $config = new Config(); $db = $config->getConnection();

	$Pelanggan = new Pelanggan($db);
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
			$i=0;
			$variable = $_POST["pelanggan"];
			foreach ($variable as $key => $value) {
				// Get Data Pelanggan
				$data_pelanggan = $_POST["pelanggan"][$i];
				list($nama, $hp) = explode(' , ', $data_pelanggan);
				$nama_pelanggan = $nama;
				$no_pelanggan = $hp;
				$i++;

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
					// echo '<script language="javascript">';
					// echo 'alert("Pesan Berhasil Terkirim")';
					// echo '</script>';
					echo "<script>window.open('https://api.whatsapp.com/send?phone=62".$no_pelanggan."&text=Hallo ".$nama_pelanggan." nikmati ".$nama_diskon." dengan potongan sebesar Rp.".$potongan." untuk pembelian di Cafe Sangkopas pada tanggal ".$tgl_mulai." sampai tanggal ".$tgl_selesai.", dengan ketentuan ".$keterangan.". Jangan sampai anda kelewatan. *Wajib tunjukkan pesan ini saat memesan di Cafe Sangkopas.&source=&data=','_blank');</script>";
				} else { 
					echo '<script language="javascript">';
					echo 'alert("Pesan Gagal Terkirim Kepada '.$nama_pelanggan.'")';
					echo '</script>';
				}
			}
			echo "<script>location.href='pelanggan.php'</script>";
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
							<div class="form-group col-12">
								<label>Diskon<span style="color:red;">*</span></label>
								<select class="selectpicker form-control" title="Diskon" name="diskon" required>
									<option selected disabled>Pilih...</option>
									<?php $Diskons = $Diskon->readAllByDateNow(); while ($row = $Diskons->fetch(PDO::FETCH_ASSOC)) : ?>
										<option value="<?=$row['nama']?> , <?=$row['tgl_mulai']?> , <?=$row['tgl_selesai']?> , <?=$row['potongan']?> , <?=$row['keterangan']?>"><?=ucwords($row['nama'])?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group col-12">
								<label>Pelanggan<span style="color:red;">*</span></label>
									<select class="custom-select2 form-control" multiple="multiple" title="Pelanggan" name="pelanggan[]" required>
									<?php $Pelanggans = $Pelanggan->readAll(); while ($row = $Pelanggans->fetch(PDO::FETCH_ASSOC)) : ?>
										<option value="<?=$row['nama']?> , <?=$row['hp']?>"><?=ucwords($row['nama'])?></option>
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
