<?php
class Photo {
	private $conn;
	private $table_name = "photo";

	public $id;
	public $name;
	public $description;
	public $created_at;
	public $album_id;
	public $likes_count;

	public function __construct ($db) {
		$this->conn = $db;
	}

	function read() {
		$query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
		$stmt = $this->conn->prepare($query);

		$this->id = htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);
		
		$stmt->execute();
		return $stmt;
	}

	function create() {
		$query = "INSERT INTO " . $this->table_name . " SET name =:name, description =:description, created_at =:created_at, album_id =:album_id";

		$stmt = $this->conn->prepare($query);

		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->album_id = htmlspecialchars(strip_tags($this->album_id));

		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":created_at", $this->created_at);
		$stmt->bindParam(":album_id", $this->album_id);

		if ($stmt->execute()) {
			return $this->conn->lastInsertId();
		}
		else return false;
	}

	function update($key, $value) {
		$query = "UPDATE " . $this->table_name . " SET ". $key . " = ". '"' . $value . '"' . " WHERE id = ?";

		$stmt = $this->conn->prepare($query);
	    $this->id=htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);

		if ($stmt->execute()) return true;
		return false;
	}

	function delete() {
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		$stmt = $this->conn->prepare($query);

	    $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        // unlink("C:/xampp/htdocs/api/data/photos/{$this->album_id}/{$this->id}.jpeg");
		if ($stmt->execute()) return true;
		return false;
	}

	function increment($key) {
		$query = "UPDATE " . $this->table_name . " SET {$key} = {$key} + 1 WHERE id = ?";
		$stmt = $this->conn->prepare($query);

	    $this->id=htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);

		if ($stmt->execute()) return true;
		return false;
	}

	function getAlbumId() {
		$query = "SELECT album_id FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);

	    $this->id=htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->album_id = $row['album_id'];
	}


}