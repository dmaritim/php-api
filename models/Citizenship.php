<?php
class Citizenship{
// database connection and table name
private $conn;
private $table_name = "citizenship";
// object properties
public $citizenship_id;
public $name;


// Db connection
public function __construct($db){
  $this->conn = $db;
}

// GET ALL
public function allStewardship(){
  $sqlQuery = "SELECT * FROM " . $this->table_name . "";
  $stmt = $this->conn->prepare($sqlQuery);
  $stmt->execute();
  return $stmt;
}

// CREATE
public function createStewardship(){
  $sqlQuery = "INSERT INTO
              ". $this->table_name ."
            SET
              name = :name";

  $stmt = $this->conn->prepare($sqlQuery);

  // sanitize
  $this->name=htmlspecialchars(strip_tags($this->name));

  if($stmt->execute()){
     return $this->conn->lastInsertId();;
  } 
  return false;
}

// UPDATE
//citizenship_id,name
public function getSingleStewardship(){
  $sqlQuery = "SELECT
              citizenship_id, 
              name
            FROM
              ". $this->table_name ."
          WHERE 
          citizenship_id = ?
          LIMIT 0,1";

  $this->citizenship_id=htmlspecialchars(strip_tags($this->citizenship_id));

  $stmt = $this->conn->prepare($sqlQuery);

  $stmt->bindParam(1, $this->citizenship_id);

  $stmt->execute();

  $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
  
  $this->name = $dataRow['name'];
}        

// UPDATE
public function updateStewardship(){
  $sqlQuery = "UPDATE
              ". $this->table_name ."
          SET
          name = :name
          WHERE 
          citizenship_id = :id";

  $stmt = $this->conn->prepare($sqlQuery);

  $this->name=htmlspecialchars(strip_tags($this->name));

  // bind data
  $stmt->bindParam(":name", $this->name);

  if($stmt->execute()){
     return true;
  }
  return false;
}

// DELETE
function deleteRelationship(){
  $sqlQuery = "DELETE FROM " . $this->table_name . " WHERE citizenship_id = ?";
  $stmt = $this->conn->prepare($sqlQuery);

  $this->citizenship_id=htmlspecialchars(strip_tags($this->citizenship_id));

  $stmt->bindParam(1, $this->citizenship_id);

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
      $query = 'SELECT citizenship_id, name
      FROM ' . $this->table_name . ' p
      LIMIT ' . $this_page_first_result . ',' . $pagesize;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

}
?>