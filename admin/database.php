<?php
include 'config.php';
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    public function select($query) {
        return $this->conn->query($query);
    }

    public function insert($query) {
        $this->conn->query($query);
        return $this->conn->insert_id;
    }

    public function update($query) {
        return $this->conn->query($query);
    }

    public function delete($query) {
        return $this->conn->query($query);
    }
}
?>
