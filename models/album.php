<?php

class Album {
    
    private $conn;
    private $table = 'album';

    public $id;
    public $id_artist;
    public $title;
    public $published;
    public $content;
    public $summary;
    public $url_image;
    public $songs = array();
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($searchParams) {
        $query = "
            SELECT a.id,ar.name,title,published,content,summary,url_image,a.created_at 
            FROM " . $this->table . " AS a 
            LEFT JOIN artista AS ar 
            ON a.id_artist = ar.id
            WHERE " . $this->whereSearch($searchParams) . "
            ORDER BY a.created_at
        ";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }

        return $stmt;
    }

    public function set() {

        $isSet = false;

        $query = "INSERT INTO " . $this->table . " 
        (id_artist,title,published,content,summary,url_image) 
        VALUES 
        (:id_artist,:title,:published,:content,:summary,:url_image)";

        try {
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id_artist", $this->id_artist);
            $stmt->bindParam(":title", $this->title);
            $stmt->bindParam(":published", $this->published);
            $stmt->bindParam(":content", $this->content);
            $stmt->bindParam(":summary", $this->summary);
            $stmt->bindParam(":url_image", $this->url_image);

            if ($stmt->execute()) {
                $this->setIdAlbum($this->conn->lastInsertId());
                $isSet = true;
            }
        }
        catch (PDOException $exception) {
            echo $exception->getMessage();
        }
        
        return $isSet;
    }

    public function get($id) {
        $query = "
            SELECT a.id,ar.name,title,published,content,summary,url_image,a.created_at 
            FROM " . $this->table . " AS a 
            LEFT JOIN artista AS ar 
            ON a.id_artist = ar.id
            WHERE a.id = '" . $id . "'
        ";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
        
        return $stmt;
    }

    private function whereSearch($searchParams) {

        $where = "status = 'active'";

        if (!empty($searchParams)) {
            
            if (!empty($searchParams["artist"])) {
                   $where .= " AND ar.name LIKE '%" . $searchParams["artist"] . "%' ";
            }

            if (!empty($searchParams["title"])) {
                $where .= " AND a.title LIKE '%" . $searchParams["title"] . "%' ";
            }
            
            if (!empty($searchParams["fromDate"]) && !empty($searchParams["toDate"])) {
                $where .= " AND a.published BETWEEN " . $searchParams["fromDate"] . " AND " . $searchParams["toDate"] . "";
            }

        }

        return $where;
    }

    public function getIdAlbum() {
        return $this->id;
    }

    public function setIdAlbum($id_album) {
        $this->id = $id_album;
    }
}
?>