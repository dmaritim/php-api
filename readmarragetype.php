<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';
require __DIR__.'/middlewares/Auth.php';
require __DIR__.'/models/MaritalType.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$db = $db_connection->dbConnection();
$auth = new Auth($db,$allHeaders);
$data = json_decode(file_get_contents("php://input"));

$returnData = [
    "success" => 0,
    "status" => 401,
    "message" => "Unauthorized ."
];

if($auth->isAuth()){
    $maritalType = new MaritalType($db);
    try{
        $result = '';
        $posts_arr = array();
        $posts_arr['paging'] = array();
        $posts_arr['data'] = array();
        if(!isset($data->page) || !isset($data->pagesize) || empty(trim($data->page))|| empty(trim($data->pagesize))){
            $result = $maritalType->read();
        }else{
            $paging = array(
                'page' => $data->page,
                'size' => $data->pagesize
            );
            array_push($posts_arr['paging'], $paging);
            $result = $maritalType->read($data->page,$data->pagesize);
        }
        //$result = $post->read();
        $num = $result->rowCount();
            if($num > 0){
                
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $post_item = array(
                    'id' => $marriage_type_id,
                    'name' => $name
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

echo json_encode($returnData);