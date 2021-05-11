<?php
class Database{
    
    private $db_host = 'localhost';
    private $db_name = 'php_auth_api';
    private $db_username = 'root';
    private $db_password = 'yes123';
    private $conn = null;

    // Db connection
    public function __construct(){
        //$this->bConnection();
    }
    
    public function dbConnection(){
        try{
          $this->conn = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name, $this->db_username, $this->db_password);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
          echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;  
    }
}