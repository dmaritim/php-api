<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

// INCLUDING DATABASE AND MAKING OBJECT
require __DIR__.'/../classes/Database.php';
require __DIR__.'/../models/User.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

$user = new User($conn);

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT POST
if($_SERVER["REQUEST_METHOD"] != "POST"):
    $returnData = msg(0,404,'Page Not Found!');

// CHECKING EMPTY FIELDS
//$first_name,$middle_name,$surname,$gender,$title,$created
//$email,$pword,$isadmin,$cancheckreports,$cancreatehouseholds,$canreceivedcash
elseif(!isset($data->first_name)
    || !isset($data->middle_name)
    || !isset($data->surname)
    || !isset($data->gender)
    || !isset($data->title)
    || !isset($data->isadmin)
    || !isset($data->cancheckreports)
    || !isset($data->cancreatehouseholds) 
    || !isset($data->canreceivedcash)   
    || !isset($data->email) 
    || !isset($data->password)
    || empty(trim($data->first_name))
    || empty(trim($data->middle_name))
    || empty(trim($data->surname))
    || empty(trim($data->gender))
    || empty(trim($data->title))
    || empty(trim($data->isadmin))
    || empty(trim($data->cancheckreports))
    || empty(trim($data->cancreatehouseholds)) 
    || empty(trim($data->canreceivedcash))   
    || empty(trim($data->email))
    || empty(trim($data->password))
    ):

    $fields = ['fields' => ['first_name','middle_name','surname','gender','title','created','email','password','isadmin','cancheckreports','cancreatehouseholds','canreceivedcash']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else:
/*     $user->password = trim($data->password);
    $user->isadmin = trim($data->isadmin);
    $user->cancheckreports = trim($data->cancheckreports);
    $user->cancreatehouseholds = trim($data->cancreatehouseholds);
    $user->canreceivedcash = trim($data->canreceivedcash); */

    //$first_name,$middle_name,$surname,$gender,$title,$created, 
    //$email,$pword,$isadmin,$cancheckreports,$cancreatehouseholds,$canreceivedcash

    if(!filter_var($data->email, FILTER_VALIDATE_EMAIL)):
        $returnData = msg(0,422,'Invalid Email Address!');
    
    elseif(strlen($data->password) < 8):
        $returnData = msg(0,422,'Your password must be at least 8 characters long!');

    elseif(strlen($data->first_name) < 3):
        $returnData = msg(0,422,'Your name must be at least 3 characters long!');

    else:

            //Validation
            $user->first_name=htmlspecialchars(strip_tags($data->first_name));
            $user->middle_name=htmlspecialchars(strip_tags($data->middle_name));
            $user->surname=htmlspecialchars(strip_tags($data->surname));
            $user->gender=htmlspecialchars(strip_tags($data->gender));
            $user->title=htmlspecialchars(strip_tags($data->title));


            $user->email=htmlspecialchars(strip_tags($data->email));
            $user->password=htmlspecialchars(strip_tags($data->password));
            $user->isadmin=htmlspecialchars(strip_tags($data->isadmin));
            $user->cancheckreports=htmlspecialchars(strip_tags($data->cancheckreports));
            $user->cancreatehouseholds=htmlspecialchars(strip_tags($data->cancreatehouseholds));
            $user->canreceivedcash=htmlspecialchars(strip_tags($data->canreceivedcash));

            if(!$user->isUniqueEmail()):
                $returnData = msg(0,422, 'This E-mail already in use! ');
            
            else:
                $returnData = $user->createUser();

            endif;
    endif;
    
endif;

echo json_encode($returnData);