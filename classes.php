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
    //sigin and login for students table is 
    public function newUser( $table, array $data){
        try{            
            $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO $table(stud_name,email,`number`,pass,`level`,`date`) VALUES(?,?,?,?,?,?)";
            $statement=$conn->prepare($sql);
            $statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4],$data[5]]);
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

//Create funtion "update_leaner" to update the learner's information
public function update_learner(array $data){
    try{
        $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="UPDATE students SET stud_name=?, `number`=?, email=?, level=? WHERE stud_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4]]);
        $conn = null;
    }catch(PDOException $err){
        $result= $err->getMessage();
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        $data=[];
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
        $sql="INSERT INTO stud_answered(`stud_id`, `o/a_level_title`,`level`,`subject`,`year`,score,duration,`date`) VALUES(?,?,?,?,?,?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7]]);
        $conn = null;
    }catch(PDOException $err){
        $result= $err->getMessage();
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        $data=[];
    }
}


public function getTopPerformers($lvl) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT stud_id, MAX(score) as max_score FROM stud_answered GROUP BY stud_id ORDER BY max_score DESC LIMIT 10 WHERE `level` = 'O'";
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

//create a new community in table "new communities" and insert necessary infor mations in the columns stud_id,teacher_id,com_name,pass
public function newCommunity(array $data){
    try{
        $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="INSERT INTO new_communities(stud_id,teacher_id,com_name,describtion,pass,img) VALUES(?,?,?,?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4],$data[5]]);
        $conn = null;
    }catch(PDOException $err){
        $result= $err->getMessage();
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        $data=[];
    }
}
//function to get all the communities
public function CheckCommunities($id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM comm_memebers WHERE member_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$id]);
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $communities = $statement->fetchAll();
        $conn = null;
    } catch (PDOException $err) {
        $communities = [];
    }
    return $communities;

}
public function MyCommunities($id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM new_communities WHERE com_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$id]);
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $communities = $statement->fetchAll();
        $conn = null;
    } catch (PDOException $err) {
        $communities = [];
    }
    return $communities;

}
// get all existing communities
public function getExistingCommunities() {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM new_communities";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
        $communities = $statement->fetchAll();
        $conn = null;
    } catch (PDOException $err) {
        $communities = [];
    }
    return $communities;
}
//function to join a community
public function joinCommunity(array $data){
    try{
        $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="INSERT INTO comm_memebers(member_id,comm_id,`date`) VALUES(?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0],$data[1],$data[2]]);
        $conn = null;
    }catch(PDOException $err){
        $result= $err->getMessage();
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        $data=[];
    }
}
//function to get all the members of a community



//TEACHERS FUNCTIONS
public function newTeacher(array $data) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO teachers(full_name, email, phone, password, subjects, profile_picture, date_created) VALUES(?, ?, ?, ?, ?, ?, ?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]]);
        $result = "Teacher signed up successfully";
        $conn = null;
    } catch (PDOException $err) {
        $result = $err->getMessage();
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
    }
    return $result;
}
public function teacherLogin(array $data) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM teachers WHERE email = ? AND password = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0], $data[1]]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $conn = null;
    } catch (PDOException $err) {
        $result = [];
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
    }
    return $result;
}
public function getTeacherById($teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM teachers WHERE teacher_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $conn = null;
    } catch (PDOException $err) {
        $result = [];
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
    }
    return $result;
}
public function updateTeacher(array $data) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE teachers SET full_name = ?, email = ?, phone = ?, subjects = ?, profile_picture = ? WHERE teacher_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5]]);
        $conn = null;
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
    }
}
public function submitVerificationDetails($teacher_id, $full_name, $dob, $id_card, $certificate) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO Verified_teachers (teacher_id, full_name, dob, id_card, certificate, state) 
                VALUES (?, ?, ?, ?, ?, 'No')";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id, $full_name, $dob, $id_card, $certificate]);
        $conn = null;
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
    }
}

public function getVerificationRequests() {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM Verified_teachers";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
    } catch (PDOException $err) {
        $result = [];
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
    }
    return $result;
}
public function updateVerificationState($verification_id, $state) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE Verified_teachers SET state = ? WHERE verification_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$state, $verification_id]);
        $conn = null;
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
    }
}
public function checkVerificationStatus($teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT state FROM Verified_teachers WHERE teacher_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $conn = null;

        return $result && $result['state'] === 'Yes';
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return false;
    }
}
public function getAllTeachers() {
    $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
    $sql = "SELECT teacher_id, full_name, profile_picture, subjects, TIMESTAMPDIFF(YEAR, date_created, CURDATE()) AS time_on_platform FROM teachers";
    $statement = $conn->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

public function getVerifiedTeachers() {
    $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
    $sql = "SELECT t.teacher_id, t.full_name, t.profile_picture, t.subjects, 
                   TIMESTAMPDIFF(YEAR, t.date_created, CURDATE()) AS time_on_platform 
            FROM teachers t 
            JOIN Verified_teachers v ON t.teacher_id = v.teacher_id 
            WHERE v.state = 'Yes'";
    $statement = $conn->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


public function getStudentMentors($student_id) {
    $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
    $sql = "SELECT t.teacher_id, t.full_name, t.profile_picture, t.subjects, 
                   TIMESTAMPDIFF(YEAR, t.date_created, CURDATE()) AS time_on_platform 
            FROM teachers t 
            JOIN mentors m ON t.teacher_id = m.teacher_id 
            WHERE m.stud_id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$student_id]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

public function requestMentor($student_id, $teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO mentors (stud_id, teacher_id, state) VALUES (?, ?, 'No')";
        $statement = $conn->prepare($sql);
        $statement->execute([$student_id, $teacher_id]);
        $conn = null;
        return true;
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return false;
    }
}
public function isMentorRequestSent($student_id, $teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT COUNT(*) FROM mentors WHERE stud_id = ? AND teacher_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$student_id, $teacher_id]);
        $count = $statement->fetchColumn();
        $conn = null;
        return $count > 0;
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return false;
    }
}
}