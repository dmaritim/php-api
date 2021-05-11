<?php
require __DIR__.'/Person.php';

    class ChurchMember extends Person{

        // Connection
        private $conn;

        // Table
        private $table_name = "churchmembers";

        // Columns
        public $marital_status;
        public $type_of_marriage;
        public $date_joint_cathedral;
        public $citizenship;
        public $phonenumber;
        public $email;
        public $occupation;
        public $place_of_work;
        public $stewardship;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function read($page=1,$pagesize=50){
            //create query
            $query = 'SELECT * FROM ' . $this->table_name . ' p';
        
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $number_of_results = $stmt->rowCount();
            $number_of_pages = 1;
            $this_page_first_result =0;
            if($number_of_results){
              $number_of_pages = ceil($number_of_results/$pagesize);
            }
        
              $this_page_first_result = ($page-1) * $pagesize;
              $query = 'SELECT *
              FROM ' . $this->table_name . ' p
              LIMIT ' . $this_page_first_result . ',' . $pagesize;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // CREATE
        public function create(){
            $sqlQuery = "INSERT INTO
                        ". $this->table_name ."
                    SET
                    first_name= :first_name,
                    middle_name= :middle_name,
                    surname= :surname,
                    gender= :gender, 
                    created_at= :created_at, 
                    title= :title,  
                    marital_status= :marital_status,
                    type_of_marriage= :type_of_marriage,
                    date_joint_cathedral= :date_joint_cathedral,
                    citizenship= :citizenship,
                    email= :email,
                    occupation= :occupation,  
                    phonenumber= :phonenumber,
                    place_of_work= :place_of_work,
                    stewardship= :stewardship";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->first_name=htmlspecialchars(strip_tags($this->first_name));
            $this->middle_name=htmlspecialchars(strip_tags($this->middle_name));
            $this->surname=htmlspecialchars(strip_tags($this->surname));
            $this->gender=htmlspecialchars(strip_tags($this->gender));
            $this->created_at=htmlspecialchars(strip_tags($this->created_at));
            $this->title=htmlspecialchars(strip_tags($this->title));
            $this->marital_status=htmlspecialchars(strip_tags($this->marital_status));
            $this->type_of_marriage=htmlspecialchars(strip_tags($this->type_of_marriage));
            $this->date_joint_cathedral=htmlspecialchars(strip_tags($this->date_joint_cathedral));
            $this->citizenship=htmlspecialchars(strip_tags($this->citizenship));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->phonenumber=htmlspecialchars(strip_tags($this->phonenumber));
            $this->occupation=htmlspecialchars(strip_tags($this->occupation));
            $this->place_of_work=htmlspecialchars(strip_tags($this->place_of_work));
            $this->stewardship=htmlspecialchars(strip_tags($this->stewardship));
            // bind data
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":middle_name", $this->middle_name);
            $stmt->bindParam(":surname", $this->surname);
            $stmt->bindParam(":gender", $this->gender);
            $currentDateTime = date('Y-m-d H:i:s');
            $stmt->bindParam(":created_at", $currentDateTime);
            $stmt->bindParam(":title", $this->title);
            $stmt->bindParam(":marital_status", $this->marital_status);
            $stmt->bindParam(":type_of_marriage", $this->type_of_marriage);
            $stmt->bindParam(":date_joint_cathedral", $this->date_joint_cathedral);
            $stmt->bindParam(":citizenship", $this->citizenship);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":occupation", $this->occupation);
            $stmt->bindParam(":phonenumber", $this->phonenumber);
            $stmt->bindParam(":place_of_work", $this->place_of_work);
            $stmt->bindParam(":stewardship", $this->stewardship);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        // UPDATE
        public function getSingleMember(){
            $sqlQuery = "SELECT
                    first_name= :first_name,
                    middle_name= :middle_name,
                    surname= :surname,
                    gender= :gender, 
                    created_at= :created_at, 
                    title= :title,  
                    marital_status= :marital_status,
                    type_of_marriage= :type_of_marriage,
                    date_joint_cathedral= :date_joint_cathedral,
                    citizenship= :citizenship,
                    email= :email,
                    occupation= :occupation,  
                    phonenumber= :phonenumber,
                    place_of_work= :place_of_work,
                    stewardship= :stewardship
                      FROM
                        ". $this->table_name ."
                    WHERE 
                       id = ?
                    LIMIT 0,1";

            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            

            $this->first_name= $dataRow[first_name];
            $this->middle_name= $dataRow[middle_name];
            $this->surname= $dataRow[surname];
            $this->gender= $dataRow[gender]; 
            $this->created_at= $dataRow[created_at]; 
            $this->title= $dataRow[title];  
            $this->marital_status= $dataRow[marital_status];
            $this->type_of_marriage= $dataRow[type_of_marriage];
            $this->date_joint_cathedral= $dataRow[date_joint_cathedral];
            $this->citizenship= $dataRow[citizenship];
            $this->email= $dataRow[email]; 
            $this->occupation= $dataRow[occupation]; 
            $this->phonenumber= $dataRow[phonenumber];
            $this->place_of_work= $dataRow[place_of_work];
            $this->stewardship= $dataRow[stewardship];
        }        

        // UPDATE
        public function update(){
            $sqlQuery = "UPDATE
                        ". $this->table_name ."
                    SET
                    first_name= :first_name,
                    middle_name= :middle_name,
                    surname= :surname,
                    gender= :gender, 
                    created_at= :created_at, 
                    title= :title,  
                    marital_status= :marital_status,
                    type_of_marriage= :type_of_marriage,
                    date_joint_cathedral= :date_joint_cathedral,
                    citizenship= :citizenship,
                    email= :email,  
                    occupation= :occupation,
                    phonenumber= :phonenumber,
                    place_of_work= :place_of_work,
                    stewardship= :stewardship
                    WHERE 
                        id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->first_name=htmlspecialchars(strip_tags($this->first_name));
            $this->middle_name=htmlspecialchars(strip_tags($this->middle_name));
            $this->surname=htmlspecialchars(strip_tags($this->surname));
            $this->gender=htmlspecialchars(strip_tags($this->gender));
            $this->created_at=htmlspecialchars(strip_tags($this->created_at));
            $this->title=htmlspecialchars(strip_tags($this->title));
            $this->marital_status=htmlspecialchars(strip_tags($this->marital_status));
            $this->type_of_marriage=htmlspecialchars(strip_tags($this->type_of_marriage));
            $this->date_joint_cathedral=htmlspecialchars(strip_tags($this->date_joint_cathedral));
            $this->citizenship=htmlspecialchars(strip_tags($this->citizenship));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->occupation=htmlspecialchars(strip_tags($this->occupation));
            $this->phonenumber=htmlspecialchars(strip_tags($this->phonenumber));
            $this->place_of_work=htmlspecialchars(strip_tags($this->place_of_work));
            $this->stewardship=htmlspecialchars(strip_tags($this->stewardship));
            // bind data
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":middle_name", $this->middle_name);
            $stmt->bindParam(":surname", $this->surname);
            $stmt->bindParam(":gender", $this->gender);
            $stmt->bindParam(":created_at", $this->created_at);
            $stmt->bindParam(":title", $this->title);
            $stmt->bindParam(":marital_status", $this->marital_status);
            $stmt->bindParam(":type_of_marriage", $this->type_of_marriage);
            $stmt->bindParam(":date_joint_cathedral", $this->date_joint_cathedral);
            $stmt->bindParam(":citizenship", $this->citizenship);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":occupation", $this->occupation);
            $stmt->bindParam(":phonenumber", $this->phonenumber);
            $stmt->bindParam(":place_of_work", $this->place_of_work);
            $stmt->bindParam(":stewardship", $this->stewardship);
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        // DELETE
        function delete(){
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