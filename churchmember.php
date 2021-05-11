<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,PUT, GET, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';
require __DIR__.'/middlewares/Auth.php';
require __DIR__.'/models/ChurchMember.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$db = $db_connection->dbConnection();
$auth = new Auth($db,$allHeaders);


$returnData = [
    "success" => 0,
    "status" => 401,
    "message" => "Unauthorized ."
];

$method = $_SERVER['REQUEST_METHOD'];
$input_data = json_decode(file_get_contents("php://input"));
$churchMember = new ChurchMember($db);

switch ($method) {
  case 'GET':
    if($auth->isAuth()){
        try{
            $result = '';
            $posts_arr = array();
            $posts_arr['paging'] = array();
            $posts_arr['data'] = array();
            if(!isset($input_data->page) || !isset($input_data->pagesize) || empty(trim($data->page))|| empty(trim($data->pagesize))){
                $result = $churchMember->read();
            }else{
                $paging = array(
                    'page' => $input_data->page,
                    'size' => $input_data->pagesize
                );
                array_push($posts_arr['paging'], $paging);
                $result = $churchMember->read($input_data->page,$input_data->pagesize);
            }
            //$result = $post->read();
            $num = $result->rowCount();
                if($num > 0){
                    
                    while($row = $result->fetch(PDO::FETCH_ASSOC)){
                        extract($row);

                        $post_item = array(
                        'id' => $id,
                        'uuid' => $uuid,
                        'first_name' => $first_name,
                        'middle_name' => $middle_name,
                        'surname' => $surname,
                        'gender' => $gender, 
                        'created_at' => $created_at, 
                        'title' => $title,  
                        'marital_status' => $marital_status,
                        'type_of_marriage' => $type_of_marriage,
                        'date_joint_cathedral' => $date_joint_cathedral,
                        'citizenship' => $citizenship,
                        'email' => $email,
                        'occupation' => $occupation,  
                        'phonenumber' => $phonenumber,
                        'place_of_work' => $place_of_work,
                        'stewardship' => $stewardship
                        );
                
                        // Push to "data"
                        //array_push($posts_arr, $post_item);
                        array_push($posts_arr['data'], $post_item);
                    }
                }else{
                    $posts_arr['Error'] = array('message' => 'No Posts Found');
                    $returnData =$posts_arr;
                }
            }catch(Exception $e ) { 
                $posts_arr['Error'] = array('message' => $e->getMessage());
                $returnData =$posts_arr;
            }
        $returnData =$posts_arr;
    }
    break;
  case 'POST':
    if($auth->isAuth()){
        //var_dump($input_data);
        $churchMember->uuid= $input_data->uuid;
        $churchMember->first_name= $input_data->first_name;
        $churchMember->middle_name= $input_data->middle_name;
        $churchMember->surname= $input_data->surname;
        $churchMember->gender= $input_data->gender; 
        $churchMember->created_at= $input_data->created_at; 
        $churchMember->title= $input_data->title;  
        $churchMember->marital_status= $input_data->marital_status;
        $churchMember->type_of_marriage= $input_data->type_of_marriage;
        $churchMember->date_joint_cathedral= $input_data->date_joint_cathedral;
        $churchMember->citizenship= $input_data->citizenship;
        $churchMember->email= $input_data->email;
        $churchMember->occupation= $input_data->occupation;  
        $churchMember->phonenumber= $input_data->phonenumber;
        $churchMember->place_of_work= $input_data->place_of_work;
        $churchMember->stewardship= $input_data->stewardship;

        if($churchMember->create()){
                $post_return = array('message' => 'Post Created');
        }else{
            $post_return = array('message' => 'Post Not Created');
        };
        $returnData =$post_return;
    }
    break;
  case 'PUT':
    if($auth->isAuth()){
   
        //Set the ID
        $churchMember->id = $input_data->id;
        $churchMember->uuid= $input_data->uuid;
        $churchMember->first_name= $input_data->first_name;
        $churchMember->middle_name= $input_data->middle_name;
        $churchMember->surname= $input_data->surname;
        $churchMember->gender= $input_data->gender; 
        $churchMember->created_at= $input_data->created_at; 
        $churchMember->title= $input_data->title;  
        $churchMember->marital_status= $input_data->marital_status;
        $churchMember->type_of_marriage= $input_data->type_of_marriage;
        $churchMember->date_joint_cathedral= $input_data->date_joint_cathedral;
        $churchMember->citizenship= $input_data->citizenship;
        $churchMember->email= $input_data->email;
        $churchMember->occupation= $input_data->occupation;  
        $churchMember->phonenumber= $input_data->phonenumber;
        $churchMember->place_of_work= $input_data->place_of_work;
        $churchMember->stewardship= $input_data->stewardship;
    
        if($churchMember->update()){
                $post_return = array('message' => 'Post Updated');
        }else{
            $post_return = array('message' => 'Post Not Updated');
        };
        $returnData =$post_return;
    }
    break;
  case 'DELETE':
    if($auth->isAuth()){
   
        //Set the ID
        $churchMember->id = htmlspecialchars($_GET["id"]);
    
        if($churchMember->delete()){
                $post_return = array('message' => 'Deleted');
        }else{
            $post_return = array('message' => 'Not Deleted');
        };
        $returnData =$post_return;
    }
    break;
}



echo json_encode($returnData);