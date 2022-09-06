<?php
class Pelanggan {
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

    function readAll() {
		$query = "SELECT * FROM {$this->table_pelanggan} ORDER BY nama ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readAllHBD() {
		$dateNow = date("m-d");

		$query = "SELECT * FROM {$this->table_pelanggan} WHERE tgl_lahir LIKE '%".$dateNow."' ORDER BY nama ASC";
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	function readOne() {
		$query = "SELECT * FROM {$this->table_pelanggan} WHERE id_pelanggan=:id_pelanggan LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id_pelanggan', $this->id_pelanggan);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id_pelanggan = $row['id_pelanggan'];
        $this->id_user = $row['id_user'];
        $this->nama = $row['nama'];
		$this->jenis_kelamin = $row['jenis_kelamin'];
		$this->hp = $row['hp'];
        $this->email = $row['email'];
        $this->tgl_lahir = $row['tgl_lahir'];
	}

	function update() {
		$query = "UPDATE {$this->table_pelanggan}
			SET
                id_pelanggan = :id_pelanggan,
                id_user = :id_user,
				nama = :nama,
				jenis_kelamin = :jenis_kelamin,
                hp = :hp,
                email = :email,
                tgl_lahir = :tgl_lahir
			WHERE
				id_pelanggan = :id_pelanggan";
        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(':id_pelanggan', $this->id_pelanggan);
        $stmt->bindParam(':id_user', $this->id_user);
		$stmt->bindParam(':nama', $this->nama);
		$stmt->bindParam(':jenis_kelamin', $this->jenis_kelamin);
        $stmt->bindParam(':hp', $this->hp);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':tgl_lahir', $this->tgl_lahir);
        $stmt->bindParam(':id_pelanggan', $this->id_pelanggan);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

    function delete() {
		$query = "DELETE FROM {$this->table_pelanggan} WHERE id_pelanggan = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id_pelanggan);
		if ($result = $stmt->execute()) {
			return true;
		} else {
			return false;
		}
    }

}
