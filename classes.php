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
            $sql="SELECT * FROM $table WHERE email = ? AND `pass` = ?";
            $statement = $conn->prepare($sql);
			$statement->execute([$data[0],$data[1]]);
			$result = "Welcome back";
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
            echo "an error occured";
            $conn = null;
            $data=[];b
        }
    }
   

//displaying the questions and other requirements
public function fetch($table, $arr = null){
    $question = []; 
    try{
        $conn= new PDO('mysql:host=localhost; DBname='.$this->DBname(),$this->pass(),$this->username());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM $table";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $question = $statement->fetchAll();
        $conn = null;
    }catch(PDOException $err){
        $result= $err->getMessage();
        $conn = null;
    }
    return $question;
}
}

?>