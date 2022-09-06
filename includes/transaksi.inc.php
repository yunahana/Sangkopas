<?php

class Transaksi {
    private $conn;
    private $table_transaksi = 'transaksi';
    private $table_transaksi_detail = 'transaksi_detail';
	private $table_user = 'user';
    private $table_diskon = 'diskon';

    public $id_transaksi;
    public $id_user;
    public $tgl_transaksi;
    public $metode_pembayaran;
    public $total_harga;
    public $status;
    public $id_diskon;
    public $no_meja;

    public function __construct($db) {
		$this->conn = $db;
	}


    function insert() {
        $query = "INSERT INTO {$this->table_transaksi} 
		(id_transaksi, id_user, tgl_transaksi,  status) 
		VALUES(:id_transaksi, :id_user, :tgl_transaksi, :status)";

        $stmt = $this->conn->prepare($query);
        // produk
        $stmt->bindParam(':id_transaksi', $this->id_transaksi);
        $stmt->bindParam(':id_user', $this->id_user);
		$stmt->bindParam(':tgl_transaksi', $this->tgl_transaksi);
        $stmt->bindParam(':status', $this->status);

		if ($stmt->execute()) {
            // var_dump($stmt);
			return true;
		} else {
			return false;
		}
	}

	function getNewId() {
		$query = "SELECT MAX(id_transaksi) AS code FROM {$this->table_transaksi}";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			return $this->genCode($row["code"], '');
		} else {
			return $this->genCode($nomor_terakhir, '');
		}
	}

	function genCode($latest, $key, $chars = 0) {
        $new = intval(substr($latest, strlen($key))) + 1;
        $numb = str_pad($new, $chars, "0", STR_PAD_LEFT);
        return $key . $numb;
	}

    function readAll() {
		$query = "SELECT * FROM {$this->table_transaksi} LEFT JOIN {$this->table_diskon} ON {$this->table_transaksi}.id_diskon = {$this->table_diskon}.id_diskon WHERE id_user=:id_user ORDER BY tgl_transaksi DESC";
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(':id_user', $this->id_user);
		$stmt->execute();

		return $stmt;
	}

	function readAllTransaksi() {
		$query = "SELECT * FROM {$this->table_transaksi} LEFT JOIN {$this->table_diskon} ON {$this->table_transaksi}.id_diskon = {$this->table_diskon}.id_diskon ORDER BY tgl_transaksi DESC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readAllTransaksiSearch() {
		$search = $this->no_tran;
		$query = "SELECT * FROM {$this->table_transaksi} LEFT JOIN {$this->table_diskon} ON {$this->table_transaksi}.id_diskon = {$this->table_diskon}.id_diskon WHERE  {$this->table_transaksi}.id_transaksi LIKE '%$search%' ORDER BY tgl_transaksi DESC";
		// print('<pre>');print_r($search);exit();
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(':no_tran', $this->no_tran);
		$stmt->execute();

		return $stmt;
	}

	function readOne() {
		$query = "SELECT * FROM {$this->table_transaksi} WHERE id_user = :id_user AND status = 'belum bayar' LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id_user', $this->id_user);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row == ""){
            return true;
        }else{
            $data['id_transaksi'] = $row['id_transaksi'];
            $data['id_user'] = $row['id_user'];
            $data['tgl_transaksi'] = $row['tgl_transaksi'];
            $data['metode_pembayaran'] = $row['metode_pembayaran'];
            $data['total_harga'] = $row['total_harga'];
            $data['status'] = $row['status'];
            $data['id_diskon'] = $row['id_diskon'];
            $data['no_meja'] = $row['no_meja'];
            return $data;
        }

	}

	function update() {
		$query = "UPDATE {$this->table_produk}
			SET
                id_produk = :id_produk,
				nama = :nama,
				kategori = :kategori,
                harga = :harga,
                foto = :foto,
                keterangan = :keterangan
			WHERE
				id_produk = :id_produk";
        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(':id_produk', $this->id_produk);
		$stmt->bindParam(':nama', $this->nama);
		$stmt->bindParam(':kategori', $this->kategori);
        $stmt->bindParam(':harga', $this->harga);
        $stmt->bindParam(':foto', $this->foto);
        $stmt->bindParam(':keterangan', $this->keterangan);
        $stmt->bindParam(':id_produk', $this->id_produk);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function updateHarga() {
		$query = "UPDATE transaksi
			SET
                total_harga = :total_harga
			WHERE
				id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id_transaksi', $this->id_transaksi);
		$stmt->bindParam(':total_harga', $this->total_harga);
	
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function updateVerifikasiTransaksi() {
		$query = "UPDATE {$this->table_transaksi}
			SET
				metode_pembayaran = :metode_pembayaran,
				id_diskon = :id_diskon,
				no_meja = :no_meja,
				status = :status
			WHERE
				id_transaksi = :id_transaksi";
        $stmt = $this->conn->prepare($query);
		
		$stmt->bindParam(':id_transaksi', $this->id_transaksi);
		$stmt->bindParam(':metode_pembayaran', $this->metode_pembayaran);
		$stmt->bindParam(':id_diskon', $this->diskon);
		$stmt->bindParam(':no_meja', $this->no_meja);
		$stmt->bindParam(':status', $this->status);
		// print('<pre>');print_r($stmt);exit();
		if ($stmt->execute()) {
			// echo "here";exit();
			return true;
		} else {
			// echo "here2";exit();
			return false;
		}
	}

    function delete() {
		$query = "DELETE FROM {$this->table_transaksi} WHERE id_transaksi = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_transaksi);
		if ($result = $stmt->execute()) {
			return true;
		} else {
			return false;
		}
    }

	function get_laporan_penjualan()
	{
		$query = "SELECT a.tgl_transaksi, a.id_transaksi, b.username, SUM(c.harga * c.jumlah) AS subtotal, d.potongan
		FROM {$this->table_transaksi} AS a
		LEFT JOIN {$this->table_user} AS b ON a.id_user = b.id_user
		LEFT JOIN {$this->table_transaksi_detail} AS c ON a.id_transaksi = c.id_transaksi
		LEFT JOIN {$this->table_diskon} AS d ON a.id_diskon = d.id_diskon
		GROUP BY a.id_transaksi
		ORDER BY a.tgl_transaksi DESC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}
}