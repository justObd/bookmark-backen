<?php
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed'));
    return;
}

// Set the HTTP response header
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

// Include necessary files
include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate the database and bookmark objects
$database = new Database();
$dbConnection = $database->connect();
$bookmark = new Bookmark($dbConnection);


if (!isset($_GET['id'])) {
    $bookmarks = $bookmark->readAll();
    echo json_encode($bookmarks); 
} else {
    $bookmark->setId($_GET['id']);
    if ($bookmark->readOne()) {
        $result = array(
            'id' => $bookmark->getId(),
            'title' => $bookmark->getTitle(),
            'dateAdded' => $bookmark->getDateAdded(),
            'URL' => $bookmark->getURL(),
            'done' => $bookmark->getDone()
        );
        echo json_encode($result); 
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'Error: No such bookmark item')); 
    }
}
?>
