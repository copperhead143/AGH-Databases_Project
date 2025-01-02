<?php
class Database{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database_name = 'sklep_internetowy';
    public $conn;

    public function __construct() {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database_name);
            
            if ($this->conn->connect_error) {
                throw new Exception("błąd połączenia z bazą danych: " . $this->conn->connect_error);
            }
            
            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function execute_query($query, $params = array()) {
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die("Failed to prepare statement: " . $this->conn->error . " - Query: " . $query);
        }

        if($params){
            $types = str_repeat('s', count($params));
            if (!$stmt->bind_param($types, ...$params)) {
                die("Failed to bind parameters: " . $stmt->error);
            }
        }

        if (!$stmt->execute()) {
            die("Failed to execute statement: " . $stmt->error);
        }

        return $stmt->get_result();
    }

    public function __destruct() {
        if($this->conn) {
            $this->conn->close();
        }
    }
}
?>