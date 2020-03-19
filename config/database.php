<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'discoteca';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:dbname=" . $this->db_name . ";host=" . $this->host,$this->username,$this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch (PDOException $exception) {
            echo "Connecction Error -> " .  $exception->getMessage();
        }

        return $this->conn;
    }
}
?>