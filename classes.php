<?php

class DB{
    //database connection
private $DBname="interactives_mcqs";
private $pass="";
private $username="root";

    protected function DBname(){
        return $this->DBname;
    }
    protected function pass(){
        return $this->pass;
    }
    protected function username(){
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

    public function questions($table, array $data){
        try{
            $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql="INSERT INTO $table(`year`, title,`subject`,instructions,question,A,B,C,D,img,ans) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $statement = $conn->prepare($sql);
			$statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10]]);
			$result = "Question added";
			$conn = null;
        }catch(PDOException $err){
            $result= $err->getMessage();
            echo "An error occurred: " . $err->getMessage();
            echo "
            <script>
            alert('Error')
            </script>
            ";
            $conn = null;
            $data=[];
        }
    }
   

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

public function getArchives() {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=".$this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SHOW TABLES";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $tables = $statement->fetchAll(PDO::FETCH_COLUMN);
        
        $archives = [];
        
        foreach ($tables as $table) {
            if (strpos($table, 'a_level_gce') === false) {
    continue;
}

            
            $sql = "SELECT DISTINCT year, subject, title, COUNT(*) as question_count 
                    FROM `$table` 
                    GROUP BY year, subject, title 
                    ORDER BY year DESC, subject";
            
            $statement = $conn->prepare($sql);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $result) {
                $archives[] = [
                    'table_name' => $table,
                    'year' => $result['year'],
                    'subject' => $result['subject'],
                    'title' => $result['title'] ?: $result['subject'] . ' ' . $result['year'],
                    'question_count' => $result['question_count']
                ];
            }
        }
        
        $conn = null;
        return $archives;
        
    } catch(PDOException $err) {
        return [];
    }
}
public function savePerf(array $perf_data){
    try{
        $conn = new PDO("mysql:host=localhost;dbname=".$this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "INSERT INTO performances 
                (`stud_id`, `exam_type`, `level`, `subject`, `year`, `score`, `total_questions`, `correct_answers`, `date_taken`, `status`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $statement = $conn->prepare($sql);
        $result = $statement->execute([
            $perf_data['stud_id'],
            $perf_data['exam_type'],
            $perf_data['level'],
            $perf_data['subject'],
            $perf_data['year'],
            $perf_data['score'],
            $perf_data['total_questions'],
            $perf_data['correct_answers'],
            $perf_data['date_taken'],
            $perf_data['status']
        ]);
        
        $conn = null;
        // return $result ? $conn->lastInsertId() : false; // Return the new performance ID if successful
        
    } catch(PDOException $err){
        error_log("Database error in savePerf: " . $err->getMessage());
        $conn = null;
        return false;
    }
}




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
        $sql = "SELECT * FROM new_communities n
        JOIN comm_memebers c ON c.com_id=n.com_id
        WHERE c.member_id=?
        ";
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
public function getExistingCommunities($id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "
            SELECT nc.* 
            FROM new_communities nc
            LEFT JOIN comm_memebers cm ON nc.com_id = cm.com_id AND cm.member_id = ?
            WHERE cm.member_id IS NULL
        ";
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
public function joinCommunity(array $data){
    try{
        $conn= new PDO("mysql:host=localhost;dbname=".$this->DBname(),$this->username(),$this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="INSERT INTO comm_memebers(member_id,com_id,`date`) VALUES(?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0],$data[1],$data[2]]);
        $conn = null;
        $data=[];

    }catch(PDOException $err){
        $result= $err->getMessage();
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        $data=[];
    }

}



//TEACHERS FUNCTIONS
public function newTeacher(array $data) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO teachers(full_name, email, phone, `password`, subjects, profile_picture, date_created) VALUES(?, ?, ?, ?, ?, ?, ?)";
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
            WHERE m.stud_id = ? AND m.state = 'Yes'";
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

public function getMentorRequestsForTeacher($teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT m.mentor_id, s.stud_id, s.stud_name, m.state,s.level
                FROM mentors m 
                JOIN students s ON m.stud_id = s.stud_id 
                WHERE m.teacher_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $result;
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return [];
    }
}

public function updateMentorRequestState($mentor_id, $state) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE mentors SET state = ? WHERE mentor_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$state, $mentor_id]);
        $conn = null;
        return true;
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return false;
    }
}
public function getApprovedStudentsCount($teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT COUNT(*) AS approved_count 
                FROM mentors 
                WHERE teacher_id = ? AND state = 'Yes'";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $result['approved_count'];
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return 0;
    }
}

public function getAverageStudentScore($teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT AVG(s.score) AS average_score 
                FROM stud_answered s 
                JOIN mentors m ON s.stud_id = m.stud_id 
                WHERE m.teacher_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return round($result['average_score'], 2);
    } catch (PDOException $err) {
        echo "An error occurred: " . $err->getMessage();
        $conn = null;
        return 0;
    }
}

public function fetchSubjects() {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->query("SELECT DISTINCT subject FROM study_content ORDER BY subject");
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $conn = null;
        return $result;
    } catch(PDOException $e) {
        error_log("Failed to get subjects: " . $e->getMessage());
        return ['biology', 'physics', 'chemistry', 'computer_science']; // fallback
    }
}

public function getCategories($subject) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT DISTINCT category FROM study_content WHERE subject = ? ORDER BY category");
        $stmt->execute([$subject]);
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $conn = null;
        return $result;
    } catch(PDOException $e) {
        error_log("Failed to get categories: " . $e->getMessage());
        return ['definitions', 'experiments', 'laws', 'diagrams']; // fallback
    }
}

public function getStudyContent($subject, $category) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="
            SELECT title, content 
            FROM study_content 
            WHERE subject = ? AND category = ? 
            ORDER BY id ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$subject, $category]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $result;
    } catch(PDOException $e) {
        error_log("Database query failed: " . $e->getMessage());
        return [];
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

public function createPrepPlan(array $data) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO exam_preps (stud_id, subject, topics, exam_date) VALUES (?, ?, ?, ?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$data[0], $data[1], $data[2], $data[3]]);
        $conn = null;
        return true;
    } catch (PDOException $err) {
        error_log("Database error in createPrepPlan: " . $err->getMessage());
        $conn = null;
        return false;
    }
}

public function getPrepPlans($stud_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM exam_preps WHERE stud_id = ? ORDER BY exam_date ASC";
        $statement = $conn->prepare($sql);
        $statement->execute([$stud_id]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $result;
    } catch (PDOException $err) {
        error_log("Database error in getPrepPlans: " . $err->getMessage());
        $conn = null;
        return [];
    }
}

public function deletePrepPlan($prep_id, $stud_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Ensure the user owns the plan they are trying to delete
        $sql = "DELETE FROM exam_preps WHERE prep_id = ? AND stud_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$prep_id, $stud_id]);
        
        $conn = null;
        return true;
    } catch (PDOException $err) {
        error_log("Database error in deletePrepPlan: " . $err->getMessage());
        $conn = null;
        return false;
    }
}

public function getCommunityPosts($com_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "
            SELECT cp.*, s.stud_name 
            FROM community_posts cp
            JOIN students s ON cp.user_id = s.stud_id
            WHERE cp.com_id = ?
            ORDER BY cp.created_at DESC
        ";
        $statement = $conn->prepare($sql);
        $statement->execute([$com_id]);
        $posts = $statement->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $posts;
    } catch (PDOException $err) {
        error_log("Database error in getCommunityPosts: " . $err->getMessage());
        $conn = null;
        return [];
    }
}

public function createPost($com_id, $user_id, $post_type, $content, $quiz_id = null) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO community_posts (com_id, user_id, post_type, content, quiz_id) VALUES (?, ?, ?, ?, ?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$com_id, $user_id, $post_type, $content, $quiz_id]);
        $conn = null;
        return true;
    } catch (PDOException $err) {
        error_log("Database error in createPost: " . $err->getMessage());
        $conn = null;
        return false;
    }
}

public function createTeacherContent($teacher_id, $content_type, $title, $content) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO teacher_content (teacher_id, content_type, title, content) VALUES (?, ?, ?, ?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id, $content_type, $title, $content]);
        $conn = null;
        return true;
    } catch (PDOException $err) {
        error_log("Database error in createTeacherContent: " . $err->getMessage());
        $conn = null;
        return false;
    }
}

public function getTeacherContent($teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM teacher_content WHERE teacher_id = ? ORDER BY created_at DESC";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id]);
        $content = $statement->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $content;
    } catch (PDOException $err) {
        error_log("Database error in getTeacherContent: " . $err->getMessage());
        $conn = null;
        return [];
    }
}

public function getMenteesForTeacher($teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT s.stud_id, s.stud_name FROM students s JOIN mentors m ON s.stud_id = m.stud_id WHERE m.teacher_id = ? AND m.state = 'Yes'";
        $statement = $conn->prepare($sql);
        $statement->execute([$teacher_id]);
        $mentees = $statement->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $mentees;
    } catch (PDOException $err) {
        error_log("Database error in getMenteesForTeacher: " . $err->getMessage());
        $conn = null;
        return [];
    }
}

public function sendContentToStudent($content_id, $student_id, $teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO student_content (content_id, student_id, teacher_id) VALUES (?, ?, ?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$content_id, $student_id, $teacher_id]);
        $conn = null;
        return true;
    } catch (PDOException $err) {
        error_log("Database error in sendContentToStudent: " . $err->getMessage());
        $conn = null;
        return false;
    }
}

public function getStudentContent($student_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "
            SELECT sc.*, tc.title, tc.content_type, t.full_name 
            FROM student_content sc
            JOIN teacher_content tc ON sc.content_id = tc.content_id
            JOIN teachers t ON sc.teacher_id = t.teacher_id
            WHERE sc.student_id = ?
            ORDER BY sc.sent_at DESC
        ";
        $statement = $conn->prepare($sql);
        $statement->execute([$student_id]);
        $content = $statement->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $content;
    } catch (PDOException $err) {
        error_log("Database error in getStudentContent: " . $err->getMessage());
        $conn = null;
        return [];
    }
}

public function getStudentContentById($student_content_id, $student_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "
            SELECT sc.*, tc.title, tc.content, tc.content_type, t.full_name 
            FROM student_content sc
            JOIN teacher_content tc ON sc.content_id = tc.content_id
            JOIN teachers t ON sc.teacher_id = t.teacher_id
            WHERE sc.student_content_id = ? AND sc.student_id = ?
        ";
        $statement = $conn->prepare($sql);
        $statement->execute([$student_content_id, $student_id]);
        $content = $statement->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $content;
    } catch (PDOException $err) {
        error_log("Database error in getStudentContentById: " . $err->getMessage());
        $conn = null;
        return false;
    }
}

public function updateStudentContentStatus($student_content_id, $status) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE student_content SET status = ? WHERE student_content_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$status, $student_content_id]);
        $conn = null;
        return true;
    } catch (PDOException $err) {
        error_log("Database error in updateStudentContentStatus: " . $err->getMessage());
        $conn = null;
        return false;
    }
}

public function getStudentContentFromTutor($student_id, $teacher_id) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=" . $this->DBname(), $this->username(), $this->pass());
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "
            SELECT sc.*, tc.title, tc.content_type 
            FROM student_content sc
            JOIN teacher_content tc ON sc.content_id = tc.content_id
            WHERE sc.student_id = ? AND sc.teacher_id = ?
            ORDER BY sc.sent_at DESC
        ";
        $statement = $conn->prepare($sql);
        $statement->execute([$student_id, $teacher_id]);
        $content = $statement->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $content;
    } catch (PDOException $err) {
        error_log("Database error in getStudentContentFromTutor: " . $err->getMessage());
        $conn = null;
        return [];
    }
}


}

class Performance extends DB {
    private $conn;
    
    public function __construct() {
        $this->conn = null;
    }
    
    private function getConnection() {
        try {
            if (isset($this->conn) && $this->conn !== null) {
                return $this->conn;
            }
            
            $host = 'localhost';
            $dbname = parent::DBname();
            $username = parent::username();
            $password = parent::pass();
            
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->conn = new PDO($dsn, $username, $password, $options);
            
            error_log("Database connection established successfully");
            
            return $this->conn;
            
        } catch(PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public function getSubjects($stud_id) {
        try {
            error_log("Getting subjects for student ID: " . $stud_id);
            
            $conn = $this->getConnection();
            
            $checkSql = "SELECT COUNT(*) as total FROM performances WHERE stud_id = ?";
            $checkStatement = $conn->prepare($checkSql);
            $checkStatement->execute([$stud_id]);
            $totalRecords = $checkStatement->fetch(PDO::FETCH_ASSOC);
            
            error_log("Total performances found: " . $totalRecords['total']);
            
            if ($totalRecords['total'] == 0) {
                return ['success' => true, 'subjects' => []];
            }
            
            $sql = "SELECT 
                        subject,
                        level,
                        COUNT(*) as test_count,
                        AVG((score/20)*100) as average_score,
                        MAX(date_taken) as last_test
                    FROM performances 
                    WHERE stud_id = ? 
                    GROUP BY subject, level
                    ORDER BY last_test DESC";
            
            $statement = $conn->prepare($sql);
            $statement->execute([$stud_id]);
            $subjects = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Raw subjects query result: " . print_r($subjects, true));
            
            // Format the data
            foreach($subjects as &$subject) {
                $subject['average_score'] = round((float)$subject['average_score'], 1);
                $subject['test_count'] = (int)$subject['test_count'];
                $subject['display_name'] = $subject['subject'] . ' (' . $subject['level'] . '-level)';
            }
            
            error_log("Formatted subjects: " . print_r($subjects, true));
            
            return ['success' => true, 'subjects' => $subjects];
            
        } catch(Exception $e) {
            error_log("Error in getSubjects: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getPerformanceData($stud_id, $subject, $level = '') {
        if (empty($subject)) {
            return ['success' => false, 'error' => 'Subject not specified'];
        }
        
        try {
            error_log("Getting performance data for: student=$stud_id, subject=$subject, level=$level");
            
            $conn = $this->getConnection();
            
            $sql = "SELECT 
                        performance_id,
                        score,
                        total_questions,
                        correct_answers,
                        date_taken,
                        status,
                        year,
                        level
                    FROM performances 
                    WHERE stud_id = ? AND subject = ?";
            
            $params = [$stud_id, $subject];
            
            if (!empty($level)) {
                $sql .= " AND level = ?";
                $params[] = $level;
            }
            
            $sql .= " ORDER BY date_taken ASC";
            
            $statement = $conn->prepare($sql);
            $statement->execute($params);
            $performance = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Performance data found: " . count($performance) . " records");
            
            $statsSql = "SELECT 
                            COUNT(*) as total_attempts,
                            AVG((score/20)*100) as average_score,
                            MAX(score) as best_score,
                            COUNT(CASE WHEN status = 'passed' THEN 1 END) as passed_count
                        FROM performances 
                        WHERE stud_id = ? AND subject = ?";
            
            $statsParams = [$stud_id, $subject];
            
            if (!empty($level)) {
                $statsSql .= " AND level = ?";
                $statsParams[] = $level;
            }
            
            $statsStatement = $conn->prepare($statsSql);
            $statsStatement->execute($statsParams);
            $stats = $statsStatement->fetch(PDO::FETCH_ASSOC);
            
            $stats['pass_rate'] = $stats['total_attempts'] > 0 ? 
                ($stats['passed_count'] / $stats['total_attempts']) * 100 : 0;
            $stats['best_score'] = $stats['best_score'] ? ($stats['best_score'] / 20) * 100 : 0;
            
            foreach($performance as &$perf) {
                $perf['score'] = (float)$perf['score'];
                $perf['total_questions'] = (int)($perf['total_questions'] ?? 20);
                $perf['correct_answers'] = (int)($perf['correct_answers'] ?? $perf['score']);
                $perf['percentage'] = ($perf['score'] / 20) * 100;
            }
            
            $stats['total_attempts'] = (int)$stats['total_attempts'];
            $stats['average_score'] = round((float)$stats['average_score'], 1);
            $stats['best_score'] = round((float)$stats['best_score'], 1);
            $stats['pass_rate'] = round((float)$stats['pass_rate'], 1);
            
            return [
                'success' => true, 
                'performance' => $performance,
                'stats' => $stats
            ];
            
        } catch(Exception $e) {
            error_log("Error in getPerformanceData: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getSummaryStats($stud_id) {
        try {
            error_log("Getting summary stats for student ID: " . $stud_id);
            
            $conn = $this->getConnection();
            
            $sql = "SELECT 
                        COUNT(*) as total_attempts,
                        AVG((score/20)*100) as average_score,
                        COUNT(CASE WHEN status = 'passed' THEN 1 END) as passed_count,
                        COUNT(DISTINCT subject) as total_subjects,
                        MAX(date_taken) as last_test,
                        MIN(date_taken) as first_test
                    FROM performances 
                    WHERE stud_id = ?";
            
            $statement = $conn->prepare($sql);
            $statement->execute([$stud_id]);
            $stats = $statement->fetch(PDO::FETCH_ASSOC);
            
            error_log("Summary stats raw: " . print_r($stats, true));
            
            $stats['pass_rate'] = $stats['total_attempts'] > 0 ? 
                ($stats['passed_count'] / $stats['total_attempts']) * 100 : 0;
            
            $stats['total_attempts'] = (int)$stats['total_attempts'];
            $stats['average_score'] = round((float)$stats['average_score'], 1);
            $stats['pass_rate'] = round((float)$stats['pass_rate'], 1);
            $stats['total_subjects'] = (int)$stats['total_subjects'];
            $stats['passed_count'] = (int)$stats['passed_count'];
            $stats['failed_count'] = $stats['total_attempts'] - $stats['passed_count'];
            
            return ['success' => true, 'stats' => $stats];
            
        } catch(Exception $e) {
            error_log("Error in getSummaryStats: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getRecentPerformances($stud_id, $limit = 10) {
        try {
            error_log("Getting recent performances for student ID: " . $stud_id);
            
            $conn = $this->getConnection();
            
            $sql = "SELECT 
                        performance_id,
                        subject,
                        level,
                        score,
                        total_questions,
                        correct_answers,
                        date_taken,
                        status,
                        year
                    FROM performances 
                    WHERE stud_id = ? 
                    ORDER BY date_taken DESC 
                    LIMIT ?";
            
            $statement = $conn->prepare($sql);
            $statement->execute([$stud_id, (int)$limit]);
            $performances = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Recent performances found: " . count($performances) . " records");
            
            foreach($performances as &$perf) {
                $perf['score'] = (float)$perf['score'];
                $perf['total_questions'] = (int)($perf['total_questions'] ?? 20);
                $perf['correct_answers'] = (int)($perf['correct_answers'] ?? $perf['score']);
                $perf['percentage'] = ($perf['score'] / 20) * 100;
                $perf['performance_id'] = (int)$perf['performance_id'];
            }
            
            return ['success' => true, 'performances' => $performances];
            
        } catch(Exception $e) {
            error_log("Error in getRecentPerformances: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // Debug method to test the connection and table structure
    public function debugDatabase($stud_id) {
        try {
            $conn = $this->getConnection();
            
            // Check if performances table exists
            $tableCheck = $conn->query("SHOW TABLES LIKE 'performances'");
            if ($tableCheck->rowCount() == 0) {
                return ['success' => false, 'error' => 'performances table does not exist'];
            }
            
            // Check table structure
            $structure = $conn->query("DESCRIBE performances")->fetchAll();
            error_log("Table structure: " . print_r($structure, true));
            
            // Check if student has any data
            $dataCheck = $conn->prepare("SELECT COUNT(*) as count FROM performances WHERE stud_id = ?");
            $dataCheck->execute([$stud_id]);
            $count = $dataCheck->fetch();
            
            // Get sample data
            $sampleData = $conn->prepare("SELECT * FROM performances WHERE stud_id = ? LIMIT 3");
            $sampleData->execute([$stud_id]);
            $sample = $sampleData->fetchAll();
            
            return [
                'success' => true,
                'table_exists' => true,
                'table_structure' => $structure,
                'student_record_count' => $count['count'],
                'sample_data' => $sample
            ];
            
        } catch(Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
class QuizGenerator extends Performance {
        private $geminiApiKey = "AIzaSyAcQR-u0L_189b-I0rrWb7qi-NyIg0SOoc"; 
       private $geminiApiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent";

        
        
        public function generatePersonalizedQuiz($stud_id, $subject, $level = '') {
            try {
                $performanceData = $this->getPerformanceData($stud_id, $subject, $level);
                $summaryStats = $this->getSummaryStats($stud_id);
                
                if (!$performanceData['success']) {
                    return ['success' => false, 'error' => 'Could not fetch performance data'];
                }
                
                $performanceAnalysis = $this->analyzePerformance($performanceData, $subject, $level);
                
                $quiz = $this->callGeminiAPI($performanceAnalysis);
                
                if ($quiz['success']) {
                    session_start();
                    $_SESSION['generated_quiz'] = $quiz['quiz'];
                    $_SESSION['quiz_subject'] = $subject;
                    $_SESSION['quiz_level'] = $level;
                    $_SESSION['student_id'] = $stud_id;
                    
                    return ['success' => true, 'quiz_id' => session_id()];
                }
                
                return $quiz;
                
            } catch (Exception $e) {
                error_log("Error generating quiz: " . $e->getMessage());
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }
        
        private function analyzePerformance($performanceData, $subject, $level) {
            $stats = $performanceData['stats'];
            $recentPerformances = array_slice($performanceData['performance'], -5); // Last 5 attempts
            
            $analysis = [
                'subject' => $subject,
                'level' => $level,
                'average_score' => $stats['average_score'],
                'total_attempts' => $stats['total_attempts'],
                'pass_rate' => $stats['pass_rate'],
                'best_score' => $stats['best_score'],
                'recent_trend' => $this->calculateTrend($recentPerformances),
                // 'weak_areas' => $this->identifyWeakAreas($recentPerformances)
            ];
            
            return $analysis;
        }
        
        private function calculateTrend($performances) {
            if (count($performances) < 2) return 'insufficient_data';
            
            $recent = array_slice($performances, -3);
            $older = array_slice($performances, 0, -3);
            
            if (empty($older)) return 'improving';
            
            $recentAvg = array_sum(array_column($recent, 'percentage')) / count($recent);
            $olderAvg = array_sum(array_column($older, 'percentage')) / count($older);
            
            if ($recentAvg > $olderAvg + 5) return 'improving';
            if ($recentAvg < $olderAvg - 5) return 'declining';
            return 'stable';
        }
        
        private function identifyWeakAreas($performances) {
            $lowScores = array_filter($performances, function($p) {
                return $p['percentage'] < 60;
            });
            
            return count($lowScores) > count($performances) * 0.5 ? 'fundamental_concepts' : 'advanced_topics';
        }
        
        private function callGeminiAPI($analysis) {
            $prompt = $this->buildPrompt($analysis);
            
            $data = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->geminiApiUrl . "?key=" . $this->geminiApiKey);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200) {
                $errorBody = json_decode($response, true);
                $errorMessage = isset($errorBody['error']['message']) ? $errorBody['error']['message'] : $response;
                return ['success' => false, 'error' => "Gemini API error ($httpCode): " . $errorMessage];
            }
            
            $result = json_decode($response, true);
            
            if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                return ['success' => false, 'error' => 'Invalid API response'];
            }
            
            $quizText = $result['candidates'][0]['content']['parts'][0]['text'];
            $quiz = $this->parseQuizFromText($quizText);
            
            return ['success' => true, 'quiz' => $quiz];
        }
        
        private function buildPrompt($analysis) {
            $trend = $analysis['recent_trend'];
            $subject = $analysis['subject'];
            $level = $analysis['level'];
            $avgScore = $analysis['average_score'];
            // $weakAreas = $analysis['weak_areas'];
            
            return "Create a personalized quiz for a student with the following performance:
            
    Subject: {$subject} ({$level} level)
    Average Score: {$avgScore}%
    Performance Trend: {$trend}
    Pass Rate: {$analysis['pass_rate']}%

    Create exactly 20 multiple choice question that will help this student improve. Focus on areas where they struggle most. Each question should have 4 options (A, B, C, D) with only one correct answer.

    Format your response as JSON with this exact structure:
    {
        \"title\": \"Personalized Quiz Title\",
        \"questions\": [
            {
                \"question\": \"Question text here\",
                \"options\": {
                    \"A\": \"Option A text\",
                    \"B\": \"Option B text\",
                    \"C\": \"Option C text\",
                    \"D\": \"Option D text\"
                },
                \"correct\": \"A\",
                \"explanation\": \"Why this answer is correct\"
            }
        ]
    }

    Make questions progressively challenging and relevant to {$subject} at {$level} level. Include clear explanations for each answer.";
        }
        
        private function parseQuizFromText($text) {
            $jsonStart = strpos($text, '{');
            $jsonEnd = strrpos($text, '}');
            
            if ($jsonStart === false || $jsonEnd === false) {
                throw new Exception("Could not find JSON in API response");
            }
            
            $jsonText = substr($text, $jsonStart, $jsonEnd - $jsonStart + 1);
            $quiz = json_decode($jsonText, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON in API response: " . json_last_error_msg());
            }
            
            return $quiz;
        }
}
