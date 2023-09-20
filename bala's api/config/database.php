<?php
class Database {

	// database credentials
	private $host = "localhost";
	private $db_name = "gallery_db";
	private $username = "root";
	public $conn;

	// connect to the database
	public function getConnection() {
		$this->conn = null;
		try {
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username);
		}
		catch(PDOException $exception) {
			echo "Connection error: " . $exception->getMessage();
		}
		return $this->conn;
	}
}
?>
