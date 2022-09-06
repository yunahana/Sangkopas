<?php
class Produk {
    private $conn;
	private $table_produk = 'produk';

    public $id_produk;
    public $nama;
    public $kategori;
    public $harga;
    public $foto;
    public $keterangan;

	public function __construct($db) {
		$this->conn = $db;
	}

	function insert() {
        $query = "INSERT INTO {$this->table_produk} 
		(id_produk, nama, kategori, harga, foto, keterangan) 
		VALUES(:id_produk, :nama, :kategori, :harga, :foto, :keterangan)";

        $stmt = $this->conn->prepare($query);
        // produk
        $stmt->bindParam(':id_produk', $this->id_produk);
		$stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':kategori', $this->kategori);
        $stmt->bindParam(':harga', $this->harga);
        $stmt->bindParam(':foto', $this->foto);
        $stmt->bindParam(':keterangan', $this->keterangan);

		if ($stmt->execute()) {
            // var_dump($stmt);
			return true;
		} else {
			return false;
		}
	}
	
	function getNewID() {
		$query = "SELECT MAX(id_produk) AS code FROM {$this->table_produk}";
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
		$query = "SELECT * FROM {$this->table_produk} ORDER BY id_produk ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readAllMakanan() {
		$query = "SELECT * FROM {$this->table_produk} WHERE kategori = 'makanan' ORDER BY id_produk ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readAllMinuman() {
		$query = "SELECT * FROM {$this->table_produk} WHERE kategori = 'minuman' ORDER BY id_produk ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readAllSnack() {
		$query = "SELECT * FROM {$this->table_produk} WHERE kategori = 'snack' ORDER BY id_produk ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readOne() {
		$query = "SELECT * FROM {$this->table_produk} WHERE id_produk=:id_produk LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id_produk', $this->id_produk);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id_produk = $row['id_produk'];
        $this->nama = $row['nama'];
		$this->kategori = $row['kategori'];
		$this->harga = $row['harga'];
        $this->foto = $row['foto'];
        $this->keterangan = $row['keterangan'];
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

    function delete() {
		$query = "DELETE FROM {$this->table_produk} WHERE id_produk = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_produk);
		if ($result = $stmt->execute()) {
			return true;
		} else {
			return false;
		}
    }

}
