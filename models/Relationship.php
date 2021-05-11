<?php
class Relationship{
// database connection and table name
private $conn;
private $table_name = "relationship_to_head";
// object properties
public $relationship_to_head_id;
public $name;


// Db connection
public function __construct($db){
  $this->conn = $db;
}

// GET ALL
public function allRelationships(){
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
public function getSingleRelationship(){
  $sqlQuery = "SELECT
              relationship_to_head_id, 
              name
            FROM
              ". $this->table_name ."
          WHERE 
             relationship_to_head_id = ?
          LIMIT 0,1";

  $this->relationship_to_head_id=htmlspecialchars(strip_tags($this->relationship_to_head_id));

  $stmt = $this->conn->prepare($sqlQuery);

  $stmt->bindParam(1, $this->relationship_to_head_id);

  $stmt->execute();

  $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
  
  $this->name = $dataRow['name'];
}        

// UPDATE
public function updateRelationship(){
  $sqlQuery = "UPDATE
              ". $this->table_name ."
          SET
          name = :name
          WHERE 
          relationship_to_head_id = :id";

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
  $sqlQuery = "DELETE FROM " . $this->table_name . " WHERE relationship_to_head_id = ?";
  $stmt = $this->conn->prepare($sqlQuery);

  $this->relationship_to_head_id=htmlspecialchars(strip_tags($this->relationship_to_head_id));

  $stmt->bindParam(1, $this->relationship_to_head_id);

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
      $query = 'SELECT relationship_to_head_id, name
      FROM ' . $this->table_name . ' p
      LIMIT ' . $this_page_first_result . ',' . $pagesize;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

}
?>