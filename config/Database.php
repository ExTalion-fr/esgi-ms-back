<?php
class Database{
    // Connexion à la base de données
    private $host = "localhost";
    private $db_name = "esgi-ms";
    private $port = "3306";
    private $username = "root";
    private $password = "";
    public $connexion;

    // getter pour la connexion
    public function getConnection(){

        $this->connexion = null;

        try{
            $this->connexion = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";port=" . $this->port , $this->username, $this->password);
            $this->connexion->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Erreur de connexion : " . $exception->getMessage();
        }

        return $this->connexion;
    }   

    public function isSameValue($value1, $value2) {
        if (is_array($value1) xor is_array($value2)) {
            return false;
        }
        if (is_object($value1) xor is_object($value2)) {
            return false;
        }

        if (is_array($value1) && is_array($value2)) {
            return $this->isSameArray($value1, $value2);
        }
        if (is_object($value1) && is_object($value2)) {
            return $this->isSameObject(get_object_vars($value1), get_object_vars($value2));
        }

        return $value1 === $value2;
    }

    public function isSameArray($value1, $value2) {
        if (!is_array($value1) or !is_array($value2)) {
            return false;
        }
        if (count($value1) !== count($value2)) {
            return false;
        }
        foreach ($value1 as $v1) {
            $hasSame = false;
            foreach ($value2 as $v2) {
                if ($this->isSameValue($v1, $v2)) {
                    $hasSame = true;
                    break;
                }
            }
            if (!$hasSame) {
                return false;
            }
        }
        return true;
    }

    public function isSameObject($value1, $value2) {
        if (!is_array($value1) or !is_array($value2)) {
            return false;
        }
        if (count($value1) !== count($value2)) {
            return false;
        }
        foreach ($value1 as $key => $value) {
            if (!isset($value2[$key])) {
                return false;
            }
            if (!$this->isSameValue($value, $value2[$key])) {
                return false;
            }
        }
        return true;
    }
}
