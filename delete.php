<?php

// Check the HTTP request method
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

//Handle the OPTIONS request for CORS preflight

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    return;
}
if($_SERVER['REQUEST_METHOD']!='DELETE'){
    http_response_code(405);
    return;
}



include_once '../db/Database.php';
include_once '../models/Bookmark.php';
 


$database = new Database();
$dbConnection = $database->connect();


$bookmark = new Bookmark($dbConnection);


$data = json_decode(file_get_contents('php://input'));


if (!$data || empty($data->id)) {
    http_response_code(422);
    echo json_encode(array('message' => 'Error: Missing required parameter in the JSON body'));
    return;
}


$bookmark->setId($data->id);

if ($bookmark->delete()) {
    echo json_encode(array('message' => 'The bookmark item was deleted'));
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Error: Failed to delete the bookmark item'));
}

?>
