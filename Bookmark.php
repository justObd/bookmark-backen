<?php
class Bookmark {
    private $id;
    private $dateAdded;
    private $URL;
    private $title;
    private $done = false;
    private $dbConnection;
    private $dbTable = 'bookmarks';
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getDone() {
        return $this->done;
    }
    public function getURL() {
        return $this->URL;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }

    public function setURL($URL) {
        $this->URL = $URL;
    }
    public function setDone($done) {
        $this->done = $done;
    }


    public function create() {
        $query = "INSERT INTO " . $this->dbTable . " (title, link, date_added) VALUES (:titleName, :linkURL, NOW())";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":titleName", $this->title);
        $stmt->bindParam(":linkURL", $this->URL);
        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s", $stmt->error);
        return false;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->dbTable . " WHERE id = :id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute() && $stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $this->id = $result->id;
            $this->title = $result->title;
            $this->URL = $result->link;
            $this->dateAdded = $result->date_added;
            return true;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->dbTable;
        $stmt = $this->dbConnection->prepare($query);
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    

    public function update() {
        $query = "UPDATE " . $this->dbTable . " SET done = :done, title = :title, link = :link WHERE id = :id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":done", $this->done);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":link", $this->URL);
        $stmt->bindParam(":id", $this->id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    

    public function delete() {
        $query = "DELETE FROM " . $this->dbTable . " WHERE id = :id";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
