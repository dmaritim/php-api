<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,PUT, GET, DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';
require __DIR__.'/middlewares/Auth.php';
require __DIR__.'/models/Stewardship.php';

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
$stewardship = new Stewardship($db);

switch ($method) {
  case 'GET':
    if($auth->isAuth()){
        try{
            $result = '';
            $posts_arr = array();
            $posts_arr['paging'] = array();
            $posts_arr['data'] = array();
            if(!isset($input_data->page) || !isset($input_data->pagesize) || empty(trim($data->page))|| empty(trim($data->pagesize))){
                $result = $stewardship->read();
            }else{
                $paging = array(
                    'page' => $input_data->page,
                    'size' => $input_data->pagesize
                );
                array_push($posts_arr['paging'], $paging);
                $result = $stewardship->read($input_data->page,$input_data->pagesize);
            }
            //$result = $post->read();
            $num = $result->rowCount();
                if($num > 0){
                    
                    while($row = $result->fetch(PDO::FETCH_ASSOC)){
                        extract($row);

                        $post_item = array(
                        'id' => $id,
                        'name' => $name,
                        'email' => $email
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
        var_dump($input_data);
        $stewardship->name = $input_data->name;
        $stewardship->gmail = $input_data->gmail;

        if($stewardship->create()){
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
        $stewardship->id = $input_data->id;
        $stewardship->name = $input_data->name;
        $stewardship->gmail = $input_data->gmail;
    
        if($stewardship->update()){
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
        var_dump($input_data);
        $stewardship->id = htmlspecialchars($_GET["id"]);
    
        if($stewardship->delete()){
                $post_return = array('message' => 'Deleted');
        }else{
            $post_return = array('message' => 'Not Deleted');
        };
        $returnData =$post_return;
    }
    break;
}



echo json_encode($returnData);