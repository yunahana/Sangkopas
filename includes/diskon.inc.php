<?php
class Diskon {
    private $conn;
	private $table_diskon = 'diskon';

    public $id_diskon;
    public $nama;
    public $tgl_mulai;
    public $tgl_selesai;
    public $potongan;
    public $keterangan;

	public function __construct($db) {
		$this->conn = $db;
	}

	function insert() {
        $query = "INSERT INTO {$this->table_diskon} 
		(id_diskon, nama, tgl_mulai, tgl_selesai, potongan, keterangan) 
		VALUES(:id_diskon, :nama, :tgl_mulai, :tgl_selesai, :potongan, :keterangan)";

        $stmt = $this->conn->prepare($query);
        // diskon
        $stmt->bindParam(':id_diskon', $this->id_diskon);
		$stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':tgl_mulai', $this->tgl_mulai);
        $stmt->bindParam(':tgl_selesai', $this->tgl_selesai);
        $stmt->bindParam(':potongan', $this->potongan);
        $stmt->bindParam(':keterangan', $this->keterangan);

		if ($stmt->execute()) {
            // var_dump($stmt);
			return true;
		} else {
			return false;
		}
	}
	
	function getNewID() {
		$query = "SELECT MAX(id_diskon) AS code FROM {$this->table_diskon}";
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
		$query = "SELECT * FROM {$this->table_diskon} 
		ORDER BY id_diskon ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readAllByDateNow() {
		$query = "SELECT * FROM {$this->table_diskon} 
		WHERE tgl_mulai <= CURDATE() AND CURDATE() <= tgl_selesai
		ORDER BY id_diskon ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readOne() {
		$query = "SELECT * FROM {$this->table_diskon} WHERE id_diskon=:id_diskon LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id_diskon', $this->id_diskon);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id_diskon = $row['id_diskon'];
        $this->nama = $row['nama'];
		$this->tgl_mulai = $row['tgl_mulai'];
		$this->tgl_selesai = $row['tgl_selesai'];
        $this->potongan = $row['potongan'];
        $this->keterangan = $row['keterangan'];
	}

	function update() {
		$query = "UPDATE {$this->table_diskon}
			SET
                id_diskon = :id_diskon,
				nama = :nama,
				tgl_mulai = :tgl_mulai,
                tgl_selesai = :tgl_selesai,
                potongan = :potongan,
                keterangan = :keterangan
			WHERE
				id_diskon = :id_diskon";
        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(':id_diskon', $this->id_diskon);
		$stmt->bindParam(':nama', $this->nama);
		$stmt->bindParam(':tgl_mulai', $this->tgl_mulai);
        $stmt->bindParam(':tgl_selesai', $this->tgl_selesai);
        $stmt->bindParam(':potongan', $this->potongan);
        $stmt->bindParam(':keterangan', $this->keterangan);
        $stmt->bindParam(':id_diskon', $this->id_diskon);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

    function delete() {
		$query = "DELETE FROM {$this->table_diskon} WHERE id_diskon = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_diskon);
		if ($result = $stmt->execute()) {
			return true;
		} else {
			return false;
		}
    }

}
