<?php
require __DIR__.'/Person.php';

    class User extends Person{

        // Connection
        private $conn;

        // Table
        private $db_table = "users";

        // Columns
        public $id;
        public $email;
        public $password;
        public $isadmin;
        public $cancheckreports;
        public $cancreatehouseholds;
        public $canreceivedcash;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function allusers(){
            $sqlQuery ="SELECT u.id,email,first_name,middle_name,surname, email,gender FROM users 
            u left join person p on p.id =u.id";
           // $sqlQuery = "SELECT id, name, email, age, designation, created FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // CREATE
        public function createUser(){
            $returnData = [];
            $sqlQuery = "INSERT INTO person
            SET
                first_name = :first_name,
                middle_name = :middle_name,
                surname = :surname,
                gender = :gender,
                title = :title,
                created_at = :created";


            $sqlQuery2 = "INSERT INTO users
                    SET
                        id = :id,
                        email = :email,
                        password = :password,
                        isadmin = :isadmin,
                        cancheckreports = :cancheckreports,
                        cancreatehouseholds = :cancreatehouseholds,
                        canreceivedcash = :canreceivedcash";
        
            $person_stmt = $this->conn->prepare($sqlQuery);
            $user_stmt = $this->conn->prepare($sqlQuery2);
        
            // sanitize
            //Person
            //$first_name,$middle_name,$surname,$gender,$title,$created
/*             $this->first_name=htmlspecialchars(strip_tags($first_name));
            $this->middle_name=htmlspecialchars(strip_tags($middle_name));
            $this->surname=htmlspecialchars(strip_tags($surname));
            $this->gender=htmlspecialchars(strip_tags($gender));
            $this->title=htmlspecialchars(strip_tags($title));
            $this->created=htmlspecialchars(strip_tags($created)); */
            
            //User
            //$email,$pword,$isadmin,$cancheckreports,$cancreatehouseholds,$canreceivedcash
/*             $this->email=htmlspecialchars(strip_tags($email));
            $this->password=password_hash($pword,PASSWORD_DEFAULT);
            $this->isadmin=htmlspecialchars(strip_tags($isadmin));
            $this->cancheckreports=htmlspecialchars(strip_tags($cancheckreports));
            $this->cancreatehouseholds=htmlspecialchars(strip_tags($cancreatehouseholds));
            $this->canreceivedcash=htmlspecialchars(strip_tags($canreceivedcash)); */
        

        
            try {
                // From this point and until the transaction is being committed every change to the database can be reverted
                $this->conn->beginTransaction();
                // bind data
                //Person Query
                $person_stmt->bindParam(":first_name", $this->first_name);
                $person_stmt->bindParam(":middle_name", $this->middle_name);
                $person_stmt->bindParam(":surname", $this->surname);
                $person_stmt->bindParam(":gender", $this->gender);
                $person_stmt->bindParam(":title", $this->title);
                $currentDateTime = date('Y-m-d H:i:s');
                $person_stmt->bindParam(":created", $currentDateTime);
                //Execute the SQL above

                $person_stmt->execute();
                // Get the generated person id
                $this->id = $this->conn->lastInsertId();

                //User Query
                $user_stmt->bindParam(":id", $this->id);
                $user_stmt->bindParam(":email", $this->email);
                $user_stmt->bindValue(':password',password_hash($this->password, PASSWORD_DEFAULT),PDO::PARAM_STR);
                $user_stmt->bindParam(":isadmin", $this->isadmin);
                $user_stmt->bindParam(":cancheckreports", $this->cancheckreports);
                $user_stmt->bindParam(":cancreatehouseholds", $this->cancreatehouseholds);
                $user_stmt->bindParam(":canreceivedcash", $this->canreceivedcash);

                $user_stmt->execute();
                $this->conn->commit();
                $returnData = msg(1,201,'You have successfully registered.');
            }catch ( PDOException $e ) { 
                // Failed to insert the order into the database so we rollback any changes
                $this->conn->rollback();
                $returnData = msg(0,500,$e->getMessage());
            }
            return $returnData;
        }

        // UPDATE
        public function getSingleMember(){
            $sqlQuery = "SELECT
                        id, 
                        name, 
                        email, 
                        age, 
                        designation, 
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
            
            $this->name = $dataRow['name'];
            $this->email = $dataRow['email'];
            $this->age = $dataRow['age'];
            $this->designation = $dataRow['designation'];
            $this->created = $dataRow['created'];
        }        

        // UPDATE
        public function updateEmployee(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        name = :name, 
                        email = :email, 
                        age = :age, 
                        designation = :designation, 
                        created = :created
                    WHERE 
                        id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->age=htmlspecialchars(strip_tags($this->age));
            $this->designation=htmlspecialchars(strip_tags($this->designation));
            $this->created=htmlspecialchars(strip_tags($this->created));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind data
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":age", $this->age);
            $stmt->bindParam(":designation", $this->designation);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":id", $this->id);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        // DELETE
        public function deleteEmployee(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
            $stmt = $this->conn->prepare($sqlQuery);

            $this->id=htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(1, $this->id);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
        public function isUniqueEmail(){
            $check_email = "SELECT `email` FROM `users` WHERE `email`=:email";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':email', $this->email,PDO::PARAM_STR);
            $check_email_stmt->execute();

            if($check_email_stmt->rowCount()){
                return false;
            }else{
                return true;
            }
        }

        public function validate(){
            $returnData =[];
            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)):
                $returnData = msg(0,422,'Invalid Email Address!');
            
            elseif(strlen($this->password) < 8):
                $returnData = msg(0,422,'Your password must be at least 8 characters long!');
        
            elseif(strlen($this->first_name) < 3):
                $returnData = msg(0,422,'Your name must be at least 3 characters long!');
            
            elseif(strlen($this->middle_name) < 3):
                $returnData = msg(0,422,'Your name must be at least 3 characters long!'); 
                
            elseif(strlen($this->middle_name) < 3):
                $returnData = msg(0,422,'Your name must be at least 3 characters long!'); 

            elseif(strlen($this->surname) < 3):
                $returnData = msg(0,422,'Your name must be at least 3 characters long!'); 

            elseif(strlen($this->surname) < 3):
                $returnData = msg(0,422,'Your name must be at least 3 characters long!');
            endif;

            return returnData;

        }

    }
?>