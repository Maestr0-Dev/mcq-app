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
    //sigin and login for students table is 
    public function newUser( $table, array $data){
        try{            
            $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO $table(stud_name,email,`number`,pass,`date`) VALUES(?,?,?,?,?)";
            $statement=$conn->prepare($sql);
            $statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4]]);
            $result = "Sigend in successfully";
        }catch( PDOException $err){
            $result= $err->getMessage();
            $conn=null;
        }
    }


public function login(array $data){

            try{
                $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql="SELECT * FROM students WHERE stud_name ='$data[0]'  AND `pass` = '$data[1]'";
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
//get performances per student
public function getPerf($id) {
    
    try{
        $conn= new PDO("mysql:host=localhost; dbname=".$this->DBname(),$this->username(),$this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM stud WHERE `id` = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$id]);
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $perf = $statement->fetchAll();
        $conn = null;
    }catch(PDOException $err){
        $perf = [];

    }
   return $perf;
}

// save perfromances per student
public function savePerf( array $data){
    try{
        $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="INSERT INTO stud_answered(`stud_id`, `o/a_level_title`,`subject`,`year`,score,duration,`date`) VALUES(?,?,?,?,?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6]]);
        $conn = null;
    }catch(PDOException $err){
        $result= $err->getMessage();
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        $data=[];
    }
}


public function getTopPerformers() {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT stud_id, MAX(score) as max_score FROM stud_answered GROUP BY stud_id ORDER BY max_score DESC LIMIT 10";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $topPerformers = $statement->fetchAll();
        $conn = null;
    } catch (PDOException $err) {
        $topPerformers = [];
    }
    return $topPerformers;
}
}
?>
