<?php

class UserModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = require __DIR__ . '/../../config/database.php';
    }

    public function findUserByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function searchByUsername($query)
    {
        $like = '%' . $query . '%';
        $stmt = $this->conn->prepare("SELECT id, username, full_name, profile_image FROM users WHERE username LIKE ? OR full_name LIKE ? LIMIT 20");
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertUser($username, $password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);
        return $stmt->execute() ? $this->conn->insert_id : false;
    }

    public function updateProfile($id, $full_name, $username, $bio, $profile_image = null)
    {
        if ($profile_image) {
            $stmt = $this->conn->prepare("UPDATE users SET full_name=?, username=?, bio=?, profile_image=? WHERE id=?");
            $stmt->bind_param("ssssi", $full_name, $username, $bio, $profile_image, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET full_name=?, username=?, bio=? WHERE id=?");
            $stmt->bind_param("sssi", $full_name, $username, $bio, $id);
        }
        return $stmt->execute();
    }
}
