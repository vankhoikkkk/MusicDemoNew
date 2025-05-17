
<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "musicdemo";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function select($query) {
        $result = $this->conn->query($query);
        if (!$result) {
            die("Lỗi truy vấn: " . $this->conn->error);
        }
        return $result;
    }

    public function close() {
        $this->conn->close();
    }
}
?>