<?php

class User {
	private $conn;
	private $table_name = "user";

	public $id;
	public $username;
	public $first_name;
	public $last_name;
	public $email;
	public $gender;
	public $profile_picture;
	public $password;
	public $created_at;

    public function __construct($db){
        $this->conn = $db;
    }

    function read() {
		$query = "SELECT u.id, u.username, u.first_name, u.last_name, u.email, u.gender, u.profile_picture
		FROM " . $this->table_name . " u ORDER BY u.created_at DESC";

		$stmt = $this->conn->prepare($query);

		$stmt->execute();

		return $stmt;
	}

	function create() {
		$query = "INSERT INTO " . $this->table_name . " (username, first_name, last_name, email, gender, password, profile_picture, created_at) VALUES (:username, :first_name, :last_name, :email, :gender, :password, :profile_picture, :created_at) ";
		// SET username=:username, first_name=:first_name, last_name=:last_name, email=:email, gender=:gender, password=:password, created_at=:created_at";

		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->username = htmlspecialchars(strip_tags($this->username));
		$this->first_name = htmlspecialchars(strip_tags($this->first_name));
		$this->last_name = htmlspecialchars(strip_tags($this->last_name));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->gender = htmlspecialchars(strip_tags($this->gender));

		// bind values
		$stmt->bindParam(":username", $this->username);
		$stmt->bindParam(":first_name", $this->first_name);
		$stmt->bindParam(":last_name", $this->last_name);
		$stmt->bindParam(":email", $this->email);
		$stmt->bindParam(":gender", $this->gender);
		$stmt->bindParam(":profile_picture", $this->profile_picture);
		$stmt->bindParam(":password", $this->password);
		$stmt->bindParam(":created_at", $this->created_at);

		if ($stmt->execute()) return $this->conn->lastInsertId();
		return false;
	}	

	function delete() {
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		$stmt = $this->conn->prepare($query);
	    $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        unlink('C:/xampp/htdocs/api/data/userProfilePhotos/' . $this->username . '.jpeg');
		if ($stmt->execute()) return true;
		return false;
	}

	function readOneById() {
		$query = "SELECT u.id, u.username, u.first_name, u.last_name, u.email, u.gender, u.profile_picture FROM " . $this->table_name . " u WHERE id = ?
			LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->username = $row['username'];
		$this->first_name = $row['first_name'];
		$this->last_name = $row['last_name'];
		$this->email = $row['email'];
		$this->gender = $row['gender'];
		$this->profile_picture = $row['profile_picture'];
	}

	function readOneByUsername() {
		$query = "SELECT u.id, u.username, u.first_name, u.last_name, u.email, u.gender, u.profile_picture FROM " . $this->table_name . " u WHERE username = ?
			LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->username);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->id = $row['id'];
		$this->username = $row['username'];
		$this->first_name = $row['first_name'];
		$this->last_name = $row['last_name'];
		$this->email = $row['email'];
		$this->gender = $row['gender'];
		$this->profile_picture = $row['profile_picture'];
	}

	function update($key, $value) {
		$query = "UPDATE " . $this->table_name . " SET ". $key . " = ". '"' . $value . '"' . " WHERE id = ?";

		$stmt = $this->conn->prepare($query);
	    $this->id=htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);

		if ($stmt->execute()) return true;
		return false;
	}

	function getUsername() {
		$query = "SELECT u.username FROM " . $this->table_name . " u WHERE id = ? LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
	    $this->id=htmlspecialchars(strip_tags($this->id));
		$stmt->bindParam(1, $this->id);

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->username = $row['username'];
	}

	function usernameExists(){
	    // query to check if username exists
	    $query = "SELECT id, first_name, last_name, password
	            FROM " . $this->table_name . "
	            WHERE username = ?
	            LIMIT 0,1";
	 
	    $stmt = $this->conn->prepare( $query );
	    $this->username=htmlspecialchars(strip_tags($this->username));
	    $stmt->bindParam(1, $this->username);
	 
	    $stmt->execute();
	    $num = $stmt->rowCount();
	 
	    if($num > 0){
	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
	        $this->id = $row['id'];
	        $this->first_name = $row['first_name'];
	        $this->last_name = $row['last_name'];
	        $this->password = $row['password'];
	 
	        return true;
	    }
	    return false;
	}

	function like_album($album_id) {
		$query = "INSERT INTO like_album SET album_id =:album_id, user_id =:user_id";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":album_id", $album_id);
		$stmt->bindParam(":user_id", $this->id);
		$stmt->execute();
	}
}
?>
