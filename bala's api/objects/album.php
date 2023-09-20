<?php

class Album {
	private $conn;
	private $table_name = "album";

	public $id;
	public $name;
	public $description;
	public $created_by;
	public $cover_photo;
	public $created_at;
	public $photo_count;
	public $likes_count;

	public function __construct($db) {
		$this->conn = $db;
	}

	function readAll() {
		$query = "SELECT id, name, description, created_by, cover_photo, created_at, photo_count, likes_count FROM " . $this->table_name;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function readOne() {
		$query = "SELECT id, name, description, created_by, cover_photo, created_at, photo_count, likes_count FROM " . $this->table_name . " WHERE id = ?";

		$stmt = $this->conn->prepare($query);
		$this->id = htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);

		$stmt->execute();
		return $stmt;
	}

	function readByUsername() {
		$query = "SELECT id, name, description, created_by, cover_photo, created_at, photo_count, likes_count FROM " . $this->table_name . " WHERE created_by = ?";

		$stmt = $this->conn->prepare($query);
		$this->created_by = htmlspecialchars(strip_tags($this->created_by));
		$stmt->bindParam(1, $this->created_by);

		$stmt->execute();
		return $stmt;
	}	

	function create() {
		$query = "INSERT INTO " . $this->table_name . " SET name = :name, description = :description, created_by =:created_by, created_at = :created_at";
		$stmt = $this->conn->prepare($query);

		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->created_by = htmlspecialchars(strip_tags($this->created_by));

		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":created_by", $this->created_by);
		$stmt->bindParam(":created_at", $this->created_at);

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

        // unlink('C:/xampp/htdocs/api/data/albumCoverPhotos/' . $this->id . '.jpeg');
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

	function decrement($key) {
		$query = "UPDATE " . $this->table_name . " SET {$key} = {$key} - 1 WHERE id = ?";

		$stmt = $this->conn->prepare($query);
	    $this->id=htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);
		
		if ($stmt->execute()) return true;
		return false;
	}
}