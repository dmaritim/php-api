<?php
class Stewardship{
// database connection and table name
private $conn;
private $table_name = "stewarship";
// object properties
public $id;
public $name;
public $gmail;


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
public function create(){
  $sqlQuery = "INSERT INTO
              ". $this->table_name ."
            SET
              name = :name,
              gmail = :gmail";

  $stmt = $this->conn->prepare($sqlQuery);

  // sanitize
  $this->name=htmlspecialchars(strip_tags($this->name));
  $stmt->bindParam(":name", $this->name);
  $stmt->bindParam(":gmail", $this->gmail);

  if($stmt->execute()){
     return $this->conn->lastInsertId();
  } 
  return false;
}

// UPDATE
//stewarship_id,name
public function getSingleStewardship(){
  $sqlQuery = "SELECT
              id, 
              name,
              email
            FROM
              ". $this->table_name ."
          WHERE 
          id = ?
          LIMIT 0,1";

  $this->id=htmlspecialchars(strip_tags($this->id));

  $stmt = $this->conn->prepare($sqlQuery);

  $stmt->bindParam(1, $this->id);

  $stmt->execute();

  $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
  
  $this->name = $dataRow['name'];
}        

// UPDATE
public function update(){
  $sqlQuery = "UPDATE
              ". $this->table_name ."
          SET
          name = :name,
          email = :email
          WHERE 
          id = :id";

  $stmt = $this->conn->prepare($sqlQuery);

  $this->name=htmlspecialchars(strip_tags($this->name));

  // bind data
  $stmt->bindParam(":name", $this->name);
  $stmt->bindParam(":email", $this->email);

  if($stmt->execute()){
     return true;
  }
  return false;
}

// DELETE
function delete(){
  $sqlQuery = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  $stmt = $this->conn->prepare($sqlQuery);

  $this->id=htmlspecialchars(strip_tags($this->id));

  $stmt->bindParam(1, $this->id);

  if($stmt->execute()){
      return true;
  }
  return false;
}

public function read($page=1,$pagesize=50){
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
      $query = 'SELECT id, name, email
      FROM ' . $this->table_name . ' p
      LIMIT ' . $this_page_first_result . ',' . $pagesize;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

}
?>