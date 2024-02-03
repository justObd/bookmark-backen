<?php

// Check the HTTP request method

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    return;
}
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    header('Allow: PUT');
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed'));
    return;
}

// Set the HTTP response headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate the database object
$database = new Database();
$dbConnection = $database->connect();

// Instantiate a bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP PUT request data from the JSON body
$data = json_decode(file_get_contents('php://input'));

// Check if required parameters are present
if (!$data || empty($data->id) || !isset($data->done)) {
    http_response_code(422);
    echo json_encode(array('message' => 'Error: Missing required parameter in the JSON body'));
    return;
}
// Update the bookmark item
$bookmark->setId($data->id);
$bookmark->setTitle($data->title); 
$bookmark->setURL($data->URL); 
$bookmark->setDone($data->done);

if ($bookmark->update()) {
    echo json_encode(array('message' => 'The bookmark item was updated'));
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Error: Failed to update the bookmark item'));
}

?>