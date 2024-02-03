<?php

// Check the HTTP request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed'));
    return;
}

// Set the HTTP response headers
// Set the HTTP response headers
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate the database object
$database = new Database();
$dbConnection = $database->connect();

// Instantiate a bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP POST request JSON body
$data = json_decode(file_get_contents('php://input'), true);

// Check if required parameters are present
if (!$data || !isset($data['title'])) {
    http_response_code(422);
    echo json_encode(array('message' => 'Error: Missing required parameter in the JSON body'));
    return;
}


// Set bookmark title
$bookmark->setTitle($data['title']);


if (isset($data['URL'])) {
    $bookmark->setURL($data['URL']);
}

if ($bookmark->create()) {
    echo json_encode(array('message' => 'A bookmark item was created'));
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Error: No bookmark item was created'));
}

?>
