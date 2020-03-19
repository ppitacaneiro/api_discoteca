<?php

class Song {

    private $conn;
    private $table = 'songs';

    public $id;
    public $id_album;
    public $title;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getSongsByAlbumId($id_album) {

        $query = "
            SELECT title 
            FROM " . $this->table . "
            WHERE id_album = '" . $id_album . "'
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function setSong() {
        
        $isSetSong = false;

        $query = "
            INSERT INTO 
            " . $this->table . "
            (id_album,title)
            VALUES
            (:id_album,:title)
        ";

        try {
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id_album", $this->id_album);
            $stmt->bindParam(":title", $this->title);

            if ($stmt->execute()) {
                $isSetSong = true;
            }
        }
        catch (PDOException $exception) {
            echo $exception->getMessage();
        }
        
        return $isSetSong;

    }

}

?>