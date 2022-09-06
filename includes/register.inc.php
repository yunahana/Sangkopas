<?php
class Register {
    private $conn;
    private $table_pelanggan = 'pelanggan';

    public $id_pelanggan;
    public $nama;
    public $jenis_kelamin;
    public $hp;
    public $email;
    public $tgl_lahir;
    public $id_user;

	public function __construct($db) {
		$this->conn = $db;
	}

    function insert() {
        $query = "INSERT INTO {$this->table_pelanggan} (id_pelanggan, id_user, nama, jenis_kelamin, hp, email, tgl_lahir) VALUES(:id_pelanggan, :id_user, :nama, :jenis_kelamin, :hp, :email, :tgl_lahir)";

        $stmt = $this->conn->prepare($query);
        // pelanggan
        $stmt->bindParam(':id_pelanggan', $this->id_pelanggan);
        $stmt->bindParam(':id_user', $this->id_user);
		$stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':jenis_kelamin', $this->jenis_kelamin);
        $stmt->bindParam(':hp', $this->hp);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':tgl_lahir', $this->tgl_lahir);

		if ($stmt->execute()) {
            // var_dump($stmt);
			return true;
		} else {
			return false;
		}
	}
	
	function getNewID() {
		$query = "SELECT MAX(id_pelanggan) AS code FROM {$this->table_pelanggan}";
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

}
