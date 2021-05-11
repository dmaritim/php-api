<?php
class Person{
// database connection and table name
private $conn;
private $table_name = "person";
// object properties
public $id;
public $uuid;
public $first_name;
public $middle_name;
public $surname;
public $gender;
public $title;
public $created;


// Db connection
public function __construct($db){
  $this->conn = $db;
}

// GET ALL
public function allpersons(){
  $sqlQuery = "SELECT id, first_name,middle_name,surname,gender,title, created FROM " . $this->db_table . "";
  $stmt = $this->conn->prepare($sqlQuery);
  $stmt->execute();
  return $stmt;
}

// CREATE
public function createPerson(){
  $sqlQuery = "INSERT INTO
              ". $this->db_table ."
            SET
              first_name = :first_name,
              middle_name = :middle_name,
              surname = :surname,
              gender = :gender,
              title = :title,
              created = :created";

  $stmt = $this->conn->prepare($sqlQuery);

  // sanitize
  $this->first_name=htmlspecialchars(strip_tags($this->first_name));
  $this->middle_name=htmlspecialchars(strip_tags($this->middle_name));
  $this->surname=htmlspecialchars(strip_tags($this->surname));
  $this->gender=htmlspecialchars(strip_tags($this->gender));
  $this->title=htmlspecialchars(strip_tags($this->title));
  $this->created=htmlspecialchars(strip_tags($this->created));

  // bind data
  $stmt->bindParam(":first_name", $this->first_name);
  $stmt->bindParam(":middle_name", $this->middle_name);
  $stmt->bindParam(":surname", $this->surname);
  $stmt->bindParam(":gender", $this->gender);
  $stmt->bindParam(":title", $this->title);
  $stmt->bindParam(":created", $this->created);

  if($stmt->execute()){
     return $this->conn->lastInsertId();;
  } 
  return false;
}

// UPDATE
public function getSinglePerson(){
  $sqlQuery = "SELECT
              id, 
              first_name,
              middle_name,
              surname,
              gender,
              title,
              created
            FROM
              ". $this->db_table ."
          WHERE 
             id = ?
          LIMIT 0,1";

  $stmt = $this->conn->prepare($sqlQuery);

  $stmt->bindParam(1, $this->id);

  $stmt->execute();

  $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
  
  $this->first_name = $dataRow['first_name'];
  $this->middle_name = $dataRow['middle_name'];
  $this->surname = $dataRow['surname'];
  $this->gender = $dataRow['gender'];
  $this->title = $dataRow['title'];
  $this->created = $dataRow['created'];
}        

// UPDATE
public function updatePerson(){
  $sqlQuery = "UPDATE
              ". $this->db_table ."
          SET
          first_name = :first_name,
          middle_name = :middle_name,
          surname = :surname,
          gender = :gender,
          title = :title,
          created = :created
          WHERE 
              id = :id";

  $stmt = $this->conn->prepare($sqlQuery);

  $this->first_name=htmlspecialchars(strip_tags($this->first_name));
  $this->middle_name=htmlspecialchars(strip_tags($this->middle_name));
  $this->surname=htmlspecialchars(strip_tags($this->surname));
  $this->title=htmlspecialchars(strip_tags($this->title));
  $this->created=htmlspecialchars(strip_tags($this->created));
  $this->id=htmlspecialchars(strip_tags($this->id));

  // bind data
  $stmt->bindParam(":first_name", $this->first_name);
  $stmt->bindParam(":middle_name", $this->middle_name);
  $stmt->bindParam(":surname", $this->surname);
  $stmt->bindParam(":title", $this->title);
  $stmt->bindParam(":created", $this->created);
  $stmt->bindParam(":id", $this->id);

  if($stmt->execute()){
     return true;
  }
  return false;
}

// DELETE
function deletePerson(){
  $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
  $stmt = $this->conn->prepare($sqlQuery);

  $this->id=htmlspecialchars(strip_tags($this->id));

  $stmt->bindParam(1, $this->id);

  if($stmt->execute()){
      return true;
  }
  return false;
}

}
?>