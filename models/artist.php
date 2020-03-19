<?php

class Artist {

    private $conn;
    private $table = 'artista';

    public $id;
    public $name;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getIdArtistaByName($name) {

        $query = "
            SELECT id
            FROM " . $this->table . "
            WHERE name = '" . $name . "'
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch();

        return $row[0];
    }

    public function set() {

        $query = "
            INSERT INTO 
            " . $this->table . "
            (name)
            VALUES ('" . $this->name . "');
        ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $insertId = $this->conn->lastInsertId();
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }

        return $insertId;
    }

    public function setName($name) {
        $this->name = $name;
    }



}

?>