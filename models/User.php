<?php
class User {
    private $connexion;

    public $userId;
    public $userName;
    public $userEmail;

    public function __construct($db) {
        $this->connexion = $db;
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM user";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        return $query;
    }

    public function getUserById() {
        $sql = "SELECT * FROM user WHERE userId = :userId";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':userId', $this->userId);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $this->userName = $row['userUsername'];
        $this->userEmail = $row['userEmail'];
        return;
    }

    public function addUser($userName, $userEmail) {
        $sql = "INSERT INTO user SET userName = :userName, userEmail = :userEmail";
        $query = $this->connexion->prepare($sql);
        $query->bindParam(':userName', $userName);
        $query->bindParam(':userEmail', $userEmail);
        $query->execute();
        $this->userId = $this->lastInsertId();
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        return [
            "id" => $this->userId,
            "name" => $this->userName,
            "email" => $this->userEmail
        ];
    }

    public function lastInsertId() {
        $sql = "SELECT userId FROM user ORDER BY userId DESC LIMIT 1";
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row['userId'];
    }

}