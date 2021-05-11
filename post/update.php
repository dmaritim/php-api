<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/../classes/Database.php';
require __DIR__.'/../middlewares/Auth.php';
require __DIR__.'/../models/Post.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$db = $db_connection->dbConnection();
$auth = new Auth($db,$allHeaders);

$returnData = [
    "success" => 0,
    "status" => 401,
    "message" => "Unauthorized"
];

if($auth->isAuth()){
    //Instantiate blog Post Object
    $post = new Post($db);

    $data = json_decode(file_get_contents("php://input"));

    //Set the ID
    $post->id = $data->id;

    $post->title = $data->title;
    $post->body = $data->body;
    $post->author = $data->author;
    $post->category_id = $data->category_id;

    if($post->update()){
            $post_return = array('message' => 'Post Updated');
    }else{
        $post_return = array('message' => 'Post Not Updated');
    };
    $returnData =$post_return;
}

echo json_encode($returnData);