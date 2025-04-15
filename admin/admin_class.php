<?php

class DB{
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

}
?>