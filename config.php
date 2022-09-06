<?php
class Config {
  private $host = "localhost";
  private $db_name = "sangkopas";
  private $username = "root";
  private $password = ""; // Dibagian ini (Edited)
  public $conn;

  public function getConnection() {
    $this->conn = null;
    try {
      $this->conn = new PDO("mysql:host=" . $this->host . "; dbname=" . $this->db_name, $this->username, $this->password);
    } catch (PDOException $exception) {
      echo "Connection error: " . $exception->getMessage();
    }
    return $this->conn;
  }
}
