<?php
class MaritalStatus{
// database connection and table name
private $conn;
private $table_name = "marital_status";
// object properties
public $marital_status_id;
public $marital_status_name;

// Db connection
public function __construct($db){
    $this->conn = $db;
  }

// GET ALL
public function getall(){
    $sqlQuery = "SELECT * FROM " . $this->table_name . "";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
  }
  
  // CREATE
  public function createPerson(){
    $sqlQuery = "INSERT INTO
                ". $this->table_name ."
              SET
              marital_status_name = :marital_status_name";
  
    $stmt = $this->conn->prepare($sqlQuery);
  
    // sanitize
    $this->marital_status_name=htmlspecialchars(strip_tags($this->marital_status_name));
  
    if($stmt->execute()){
       return $this->conn->lastInsertId();;
    } 
    return false;
  }
  
  // UPDATE
  public function getSingleRelationship(){
    $sqlQuery = "SELECT
                marital_status_id, 
                marital_status_name
              FROM
                ". $this->table_name ."
            WHERE 
            marital_status_id = ?
            LIMIT 0,1";
  
    $this->marital_status_id=htmlspecialchars(strip_tags($this->marital_status_id));
  
    $stmt = $this->conn->prepare($sqlQuery);
  
    $stmt->bindParam(1, $this->marital_status_id);
  
    $stmt->execute();
  
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $this->name = $dataRow['marital_status_name'];
  }        
  
  // UPDATE
  public function updateRelationship(){
    $sqlQuery = "UPDATE
                ". $this->table_name ."
            SET
            marital_status_name = :marital_status_name
            WHERE 
            marital_status_id = :marital_status_id";
  
    $stmt = $this->conn->prepare($sqlQuery);
  
    $this->marital_status_name=htmlspecialchars(strip_tags($this->marital_status_name));
  
    // bind data
    $stmt->bindParam(":marital_status_name", $this->marital_status_name);
  
    if($stmt->execute()){
       return true;
    }
    return false;
  }
  
  // DELETE
  function deleteRelationship(){
    $sqlQuery = "DELETE FROM " . $this->table_name . " WHERE relationship_to_head_id = ?";
    $stmt = $this->conn->prepare($sqlQuery);
  
    $this->marital_status_id=htmlspecialchars(strip_tags($this->marital_status_id));
  
    $stmt->bindParam(1, $this->marital_status_id);
  
    if($stmt->execute()){
        return true;
    }
    return false;
  }
  
  public function read($page=1,$pagesize=5){
      //create query
      $query = 'SELECT *
      FROM ' . $this->table_name . ' p';
  
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $number_of_results = $stmt->rowCount();
      $number_of_pages = 1;
      $this_page_first_result =0;
      if($number_of_results){
        $number_of_pages = ceil($number_of_results/$pagesize);
      }
  
        $this_page_first_result = ($page-1) * $pagesize;
        $query = 'SELECT marital_status_id, marital_status_name
        FROM ' . $this->table_name . ' p
        LIMIT ' . $this_page_first_result . ',' . $pagesize;
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt;
  }
}
?>