<?php
require_once __DIR__ . '/../config/database.php';

$database = new Database();
$conn = $database->connect();

$categories = $conn->query("SELECT * FROM categories")
->fetchAll(PDO::FETCH_ASSOC);
?>

    class Database {
    private $host = "localhost";
    private $db_name = "blogsphere";
    private $username = "root";
    private $password = "";
    public $conn;
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }

        return $this->conn;
    }
}