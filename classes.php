<?php

class DB{
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
    //sigin and login for leaners's table is `learners`
    public function signin( $table, array $data){
        try{
            $conn= new PDO('mysql:host=localhost;dbname='.$this->DBname(),$this->pass(),$this->username());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT $table(learner's_name,email,`password`,phone) VALUES(?,?,?,?)";
            $statement=$conn->prepare($sql);
            $statement->execute([$data[0],$data[1],$data[2],$data[3]]);
            $result = "Sigend in successfully";
        }catch( PDOException $err){
            $result= $err->getMessage();
            $conn=null;
        }
    }
        public function login($table, array $data){
            try{
            $conn= new PDO('mysql:host=localhost;dbname='.$this->DBname(),$this->pass(),$this->username());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql="SELECT * FROM $table WHERE email = ? AND `password` = ?";
            $statement = $conn->prepare($sql);
			$statement->execute([$data[0],$data[1]]);
            $conn = null;
		}catch(PDOException $err){
			$result= $err->getMessage();
			$conn = null;
		}
		
 
    }
    

//Getting the questions and other requirements   
    public function questions($table, array $data){
        try{
            $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql="INSERT INTO $table(`year`, title,`subject`,instructions,question,A,B,C,D,img,img_type,ans) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
            $statement = $conn->prepare($sql);
			$statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]]);
			$result = "Question added";
			$conn = null;
        }catch(PDOException $err){
            $result= $err->getMessage();
            echo "An error occurred: " . $err->getMessage();

            $conn = null;
            $data=[];
        }
    }
    public function answered($table, array $data){
        try{
            $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql="INSERT INTO stud_answred(`stud_id`, `o/a_level`,`subject`,`year`,score,duration,`date`)VALUES(?,?,?,?,?,?,?)";
            $statement = $conn->prepare($sql);
			$statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]]);
			$result = "Question added";
			$conn = null;
        }catch(PDOException $err){
            $result= $err->getMessage();
            echo "An error occurred: " . $err->getMessage();

            $conn = null;
            $data=[];
        }
    }
   

//displaying the questions and other requirements
public function Get($table,array $data,$arr=null) {
    
    try{
        $conn= new PDO("mysql:host=localhost; dbname=".$this->DBname(),$this->username(),$this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM $table WHERE `year` = ? AND `subject` = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0], $data[1]]);
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $questions = $statement->fetchAll();
        $conn = null;
    }catch(PDOException $err){
        $questions = [];

    }
   return $questions;
}

}

?>
