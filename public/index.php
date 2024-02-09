<?php
    // error_reporting(0);

    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: GET'); 
    header('Access-Control-Allow-Headers: Content-Type, Access-control-Allow-Headers, Authorization, X-Request-With');


    require '../include/function.php';

    $requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == 'GET') {
    if (isset($_GET['name'])) {
        $getUserResult = getUser($_GET);
        echo $getUserResult;
    } else {
        $getUserListResult = getUserList();
        echo $getUserListResult;
    }
} elseif ($requestMethod == 'POST') {
    $createData = json_decode(file_get_contents("php://input"), true);

    if (empty($createData)) {
        $data = ['status' => 400,
                 'message' => 'Invalid JSON data provided'];
        header("HTTP/1.0 400 Bad Request");
        echo json_encode($data);
    } else {
        $createUserResult = storeUser($createData);
        echo $createUserResult;
    }
} elseif ($requestMethod == 'PUT'){
    $updateData = json_decode(file_get_contents("php://input"), true);   
    $updateUser = updateUser($updateData, $_GET);
    echo $updateUser;
    
} elseif($requestMethod == "DELETE"){
    $removeUser = removeUser($_GET);
    echo $removeUser;
} else {
    $data = ['status' => 405,
             'message' => $requestMethod . ' Method Not Allowed'];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}