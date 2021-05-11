<?php
class Household {
    private $conn;
    private $table = 'households';

    public $id;
    public $household_code;
    public $estate;
    public $street;
    public $cathedral_zone_id;
    public $postal_address;
    public $head_id;
    public $created_at;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        //create query
        $query = 'SELECT h.household_code, h.id, h.estate, h.street, h.
        FROM ' . $this->table . ' h
        LEFT JOIN
        cathedral_zone c ON h.cathedral_zone_id = c.id
        ORDER BY
          p.created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single(){
         //create query
         $query = 'SELECT *
         FROM ' . $this->table . ' p
         WHERE
           p.household_id = ?
         LIMIT 0,1';
         $stmt = $this->conn->prepare($query);
         //Bind the ID in to the statement
         $stmt->bindParam(1, $this->id);
         $stmt->execute();

         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $this->household_id = $row['household_id'];
         $this->household_code = $row['household_code'];
         $this->estate = $row['estate'];
         $this->street = $row['street'];
         $this->cathedral_zone_id = $row['cathedral_zone_id'];
         $this->postal_address = $row['postal_address'];
         $this->head_id = $row['head_id'];
         $this->created_at = $row['created_at'];
         return $stmt;     
    }

    public function create(){
      $query ='INSERT INTO ' . $this->table . ' 
      SET
      household_code = :household_code,
      estate = :estate,
      street = :street,
      cathedral_zone_id = :cathedral_zone_id,
      postal_address = :postal_address,
      head_id = :head_id,
      created_at = :created_at';

    //prepare statement
    $stmt = $this->conn->prepare($query);
    
    //Clean data
    $this->household_code = htmlspecialchars(strip_tags($this->household_code));
    $this->estate = htmlspecialchars(strip_tags($this->estate));
    $this->street = htmlspecialchars(strip_tags($this->street));
    $this->cathedral_zone_id = htmlspecialchars(strip_tags($this->cathedral_zone_id));
    $this->postal_address = htmlspecialchars(strip_tags($this->postal_address));
    $this->head_id = htmlspecialchars(strip_tags($this->head_id));
    $this->created_at = htmlspecialchars(strip_tags($this->created_at));

    //Bind parameters
    $stmt->bindParam(':household_code', $this->household_code);
    $stmt->bindParam(':estate', $this->estate);
    $stmt->bindParam(':street', $this->street);
    $stmt->bindParam(':cathedral_zone_id', $this->cathedral_zone_id);
    $stmt->bindParam(':postal_address', $this->postal_address);
    $stmt->bindParam(':head_id', $this->head_id);
    $stmt->bindParam(':created_at', $this->created_at);

    if ($stmt->execute()){
      return true;
    }

    print_r("Error: %s. #n", $stmt->error);

    return false;
    }

    // Update Post
    public function update() {
      // Create query
      $query = 'UPDATE ' . $this->table . '
                            SET household_code = :household_code, estate = :estate, 
                            street = :street, cathedral_zone_id = :cathedral_zone_id,
                            postal_address = :postal_address,
                            head_id = :head_id,
                            created_at = :created_at  
                            WHERE household_id = :household_id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->household_code = htmlspecialchars(strip_tags($this->household_code));
      $this->estate = htmlspecialchars(strip_tags($this->estate));
      $this->street = htmlspecialchars(strip_tags($this->street));
      $this->cathedral_zone_id = htmlspecialchars(strip_tags($this->cathedral_zone_id));
      $this->postal_address = htmlspecialchars(strip_tags($this->postal_address));
      $this->head_id = htmlspecialchars(strip_tags($this->head_id));
      $this->created_at = htmlspecialchars(strip_tags($this->created_at));
      $this->household_id = htmlspecialchars(strip_tags($this->household_id));

      // Bind data
      $stmt->bindParam(':household_code', $this->household_code);
      $stmt->bindParam(':estate', $this->estate);
      $stmt->bindParam(':street', $this->street);
      $stmt->bindParam(':cathedral_zone_id', $this->cathedral_zone_id);
      $stmt->bindParam(':postal_address', $this->postal_address);
      $stmt->bindParam(':head_id', $this->head_id);
      $stmt->bindParam(':created_at', $this->created_at);
      $stmt->bindParam(':household_id', $this->household_id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
}



    // Delete Post
    public function delete() {
      // Create query
      $query = 'DELETE FROM ' . $this->table . ' WHERE household_id = :ihousehold_idd';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->household_id = htmlspecialchars(strip_tags($this->household_id));

      // Bind data
      $stmt->bindParam(':household_id', $this->household_id);

      // Execute query
      if($stmt->execute()) {
        return true;
      }

      // Print error if something goes wrong
      printf("Error: %s.\n", $stmt->error);

      return false;
}
}
?>