<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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
    $post = new Post($db);

    $post->id = isset($_GET['id']) ? $_GET['id'] : die();

    //Get Post
    $result = $post->read_single();

    $post_arr = array(
        'id' => $post->id,
        'title' => $post->title,
        'body' => $post->body,
        'author' => $post->author,
        'category_id' => $post->category_id,
        'category_name' => $post->category_name,
    );
    $returnData =$post_arr;
}

print_r(json_encode($returnData));