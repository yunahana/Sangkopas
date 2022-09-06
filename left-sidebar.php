    <div class="left-side-bar">
		<div class="brand-logo">
			<a href="index.php">
				<img src="vendors/images/deskapp-logo.svg" alt="" class="dark-logo">
				<img src="vendors/images/deskapp-logo-white.svg" alt="" class="light-logo">
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">
					<li>
						<a href="index.php" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-home"></span><span class="mtext">Home</span>
						</a>
					</li>
					<?php if ($_SESSION['role'] == 'admin'): ?>
						<!-- Admin -->
						<li>
							<a href="pelanggan.php" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-user1"></span><span class="mtext">Pelanggan</span>
							</a>
						</li>
						<li>
							<a href="produk.php" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-price-tag"></span><span class="mtext">Produk</span>
							</a>
						</li>
						<li>
							<a href="diskon.php" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-money-2"></span><span class="mtext">Diskon</span>
							</a>
						</li>
						<li>
							<a href="transaksi_list_all.php" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-invoice-1"></span><span class="mtext">Transaksi</span>
							</a>
						</li>
						<li>
							<a href="laporan.php" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-analytics1"></span><span class="mtext">Laporan</span>
							</a>
						</li>
					<?php elseif ($_SESSION['role'] == 'kasir'): ?>
						<!-- Kasir -->
						<li>
							<a href="pelanggan.php" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-user1"></span><span class="mtext">Pelanggan</span>
							</a>
						</li>
						<li>
							<a href="diskon.php" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-money-2"></span><span class="mtext">Diskon</span>
							</a>
						</li>
						<li>
							<a href="transaksi_list_all.php" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-invoice-1"></span><span class="mtext">Transaksi</span>
							</a>
						</li>
					<?php else: ?>
						<!-- Pelanggan -->
						<li>
							<a href="transaksi_list.php?id=<?php echo $_SESSION['id_pelanggan']; ?>" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-invoice-1"></span><span class="mtext">Transaksi</span>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>