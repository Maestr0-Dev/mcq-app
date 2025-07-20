<?php

class admindb{
    //database connection
private $DBname="interactives_mcqs";
private $pass="";
private $username="root";

    private function DBname(){
        return $this->DBname;
    }
    private function pass(){
        return $this->pass;
    }
    private function username(){
        return $this->username;
    }


public function getAllSTudents(){

    try{
        $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="SELECT * FROM students";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result= $statement->setFetchMode(PDO::FETCH_ASSOC);
        $user= $statement->fetchAll();
        $conn = null;
}catch(PDOException $err){
    $user=[];
    $conn = null;
    return $err->getMessage();
}
    return $user;
}
public function countStud(){
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT COUNT(*) AS count 
                FROM students";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['count'];
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return 0;
    }
}
public function getAllTeachers(){
    try{
    $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT * FROM teachers ";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $result= $statement->setFetchMode(PDO::FETCH_ASSOC);
    $Tea= $statement->fetchAll();
    $conn = null;
}catch(PDOException $err){
$Tea=[];
$conn = null;
return $err->getMessage();
}
return $Tea;

}
public function countTeach(){
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT COUNT(*) AS count 
                FROM teachers";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['count'];
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return 0;
    }
}

public function addStudyContent($subject, $category, $title, $content) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO study_content (subject, category, title, content, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$subject, $category, $title, $content]);
        $conn = null;
        return "Content added successfully";
    } catch(PDOException $e) {
        error_log("Failed to add content: " . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
}

// Update study content
public function updateStudyContent($id, $subject, $category, $title, $content) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE study_content SET subject = ?, category = ?, title = ?, content = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$subject, $category, $title, $content, $id]);
        $conn = null;
        return "Content updated successfully";
    } catch(PDOException $e) {
        error_log("Failed to update content: " . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
}

// Delete study content
public function deleteStudyContent($id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM study_content WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $conn = null;
        return "Content deleted successfully";
    } catch(PDOException $e) {
        error_log("Failed to delete content: " . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
}

// Get all study content for admin view
public function getAllStudyContent() {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->query("SELECT * FROM study_content ORDER BY subject, category, id");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $result;
    } catch(PDOException $e) {
        error_log("Failed to get all content: " . $e->getMessage());
        return [];
    }
}

// Get single content item by ID
public function getStudyContentById($id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM study_content WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result;
    } catch(PDOException $e) {
        error_log("Failed to get content by ID: " . $e->getMessage());
        return [];
    }
}


}
?>