<?php
// Include the configuration file to set environment variables
require_once 'db_config.php';

// Define a base URL constant for the application
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
define('BASE_URL', $protocol . $domainName . '/mcq-app/');

class DB{
    //database connection
    private $host=DB_HOST;
    private $DBname=DB_NAME;
    private $pass=DB_PASS;
            private $username=DB_USER;

            protected function host(){
                return $this->host;
            }
            protected function DBname(){
                return $this->DBname;
            }
            protected function pass(){
                return $this->pass;
            }
            protected function username(){
                return $this->username;
            }


            public function checkUserExists($username, $email) {
                try {
                    $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "SELECT * FROM students WHERE stud_name = ? OR email = ?";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$username, $email]);
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    $conn = null;
                    return $result ? true : false;
                } catch (PDOException $err) {
                    $conn = null;
                    return false;
                }
            }

            public function adminLogin($email, $password) {
                try {
                    $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "SELECT * FROM admins WHERE emails = ?";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$email]);
                    $admin = $statement->fetch(PDO::FETCH_ASSOC);
                    $conn = null;

                    if (!$admin) {
                        // No user found with that email
                        return false;
                    }

                    // Check if the stored password is a valid hash
                    if (password_verify($password, $admin['password'])) {
                        return $admin;
                    }

                    // If we reach here, password was incorrect
                    return false;

                } catch (PDOException $err) {
                    $conn = null;
                    return false;
                }
            }

            public function addAdmin($username, $email, $password, $level, $phone) {
                try {
                    $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO admins (adm_name, emails, password, level, phone) VALUES (?, ?, ?, ?, ?)";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$username, $email, $password_hash, $level, $phone]);
                    $conn = null;
                    return true;
                } catch (PDOException $err) {
                    $conn = null;
                    return false;
                }
            }

            public function getAllAdmins() {
                try {
                    $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "SELECT * FROM admins";
                    $statement = $conn->prepare($sql);
                    $statement->execute();
                    $admins = $statement->fetchAll(PDO::FETCH_ASSOC);
                    $conn = null;
                    return $admins;
                } catch (PDOException $err) {
                    $conn = null;
                    return [];
                }
            }

            public function updateAdminLevel($admin_id, $level) {
                try {
                    $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "UPDATE admins SET level = ? WHERE admin_id = ?";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$level, $admin_id]);
                    $conn = null;
                    return true;
                } catch (PDOException $err) {
                    $conn = null;
                    return false;
                }
            }

            public function getAdminById($admin_id) {
                try {
                    $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = "SELECT * FROM admins WHERE admin_id = ?";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$admin_id]);
                    $admin = $statement->fetch(PDO::FETCH_ASSOC);
                    $conn = null;
                    return $admin;
                } catch (PDOException $err) {
                    $conn = null;
                    return false;
                }
            }

            public function updateAdminProfile($admin_id, $username, $email, $password) {
                try {
                    $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    if (!empty($password)) {
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                        $sql = "UPDATE admins SET adm_name = ?, emails = ?, password = ? WHERE admin_id = ?";
                        $statement = $conn->prepare($sql);
                        $statement->execute([$username, $email, $password_hash, $admin_id]);
                    } else {
                        $sql = "UPDATE admins SET adm_name = ?, emails = ? WHERE admin_id = ?";
                        $statement = $conn->prepare($sql);
                        $statement->execute([$username, $email, $admin_id]);
                    }
                    
                    $conn = null;
                    return true;
                } catch (PDOException $err) {
                    $conn = null;
                    return false;
                }
            }
            //sigin and login for students table is 
            public function newUser(array $data){
                try{            
                    $conn= new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(),$this->username(),$this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    // Hash the user's password for secure storage.
                    $password_hash = password_hash($data[3], PASSWORD_DEFAULT);
                    $sql = "INSERT INTO students(stud_name,email,`number`,pass,`level`,`date`) VALUES(?,?,?,?,?,?)";
                    $statement=$conn->prepare($sql);
                    $statement->execute([$data[0],$data[1],$data[2],$password_hash,$data[4],$data[5]]);
                    $result = "Sigend in successfully";
                }catch( PDOException $err){
                    error_log("Database error in newUser: " . $err->getMessage());
                    $conn=null;
                }
            }


        public function login(array $data){
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Use a prepared statement to prevent SQL injection.
                $sql = "SELECT * FROM students WHERE stud_name = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$data[0]]);
                $user = $statement->fetch(PDO::FETCH_ASSOC);
                $conn = null;
        
                if (!$user) {
                    return [];
                }
        
                // Verify the password against the stored hash.
                if (password_verify($data[1], $user['pass'])) {
                    return [$user];
                } else {
                    // If the old password was plain text, verify it and update the hash.
                    if ($data[1] === $user['pass']) {
                        $this->updateStudentPassword($user['stud_id'], $data[1]);
                        return [$user];
                    }
                }
        
                return [];
            } catch (PDOException $err) {
                return [];
            }
        }

        public function updateStudentPassword($student_id, $password) {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE students SET pass = ? WHERE stud_id = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$password_hash, $student_id]);
                $conn = null;
                return true;
            } catch (PDOException $err) {
                $conn = null;
                return false;
            }
        }

        public function update_learner(array $data){
            try{
                $conn= new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(),$this->username(),$this->pass());
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
                if (!$this->isTableAllowed($table)) {
                    return;
                }
                try{
                    $conn= new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(),$this->username(),$this->pass());
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql="INSERT INTO $table(`year`,`subject`,instructions,question,A,B,C,D,img,ans) VALUES(?,?,?,?,?,?,?,?,?,?)";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]]);
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
            if (!$this->isTableAllowed($table)) {
                return [];
            }
            try{
                $conn= new PDO("mysql:host=".$this->host()."; dbname=".$this->DBname(),$this->username(),$this->pass());
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

        public function updateQuestion($table, $id, array $data) {
            if (!$this->isTableAllowed($table)) {
                return;
            }
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE $table SET question = ?, A = ?, B = ?, C = ?, D = ?, ans = ? WHERE id = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$data['question'], $data['A'], $data['B'], $data['C'], $data['D'], $data['ans'], $id]);
                $conn = null;
            } catch(PDOException $err) {
                echo "An error occurred: " . $err->getMessage();
                $conn = null;
            }
        }

        public function deleteQuiz($table, $year, $subject) {
            if (!$this->isTableAllowed($table)) {
                return;
            }
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "DELETE FROM `$table` WHERE `year` = ? AND `subject` = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$year, $subject]);
                $conn = null;
            } catch(PDOException $err) {
                echo "An error occurred: " . $err->getMessage();
                $conn = null;
            }
        }

        public function deleteQuestion($table, $id) {
            if (!$this->isTableAllowed($table)) {
                return;
            }
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "DELETE FROM `$table` WHERE `id` = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$id]);
                $conn = null;
            } catch(PDOException $err) {
                echo "An error occurred: " . $err->getMessage();
                $conn = null;
            }
        }

        public function getArchives() {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = "SHOW TABLES";
                $statement = $conn->prepare($sql);
                $statement->execute();
                $tables = $statement->fetchAll(PDO::FETCH_COLUMN);
                
                $archives = [];
                
                foreach ($tables as $table) {
                    if (strpos($table, 'a_level_gce') === false && strpos($table, 'o_level_gce') === false) {
                        continue;
                    }

                    
                    $sql = "SELECT DISTINCT year, subject, COUNT(*) as question_count 
                            FROM `$table` 
                            GROUP BY year, subject 
                            ORDER BY year DESC, subject";
                    
                    $statement = $conn->prepare($sql);
                    $statement->execute();
                    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($results as $result) {
                        $archives[] = [
                            'table_name' => $table,
                            'year' => $result['year'],
                            'subject' => $result['subject'],
                            'title' =>  $result['subject'] . ' ' . $result['year'],
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
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
                $perf_data=[];
            } catch(PDOException $err){
                error_log("Database error in savePerf: " . $err->getMessage());
                $conn = null;
                return false;
            }
        }




        public function newCommunity(array $data){
            try{
                $conn= new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(),$this->username(),$this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn= new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(),$this->username(),$this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Hash the teacher's password for secure storage.
                $password_hash = password_hash($data[3], PASSWORD_DEFAULT);
                $sql = "INSERT INTO teachers(full_name, email, phone, `password`, subjects, profile_picture, date_created) VALUES(?, ?, ?, ?, ?, ?, ?)";
                $statement = $conn->prepare($sql);
                $statement->execute([$data[0], $data[1], $data[2], $password_hash, $data[4], $data[5], $data[6]]);
                $conn = null;
                return true;
            } catch (PDOException $err) {
                echo "An error occurred: " . $err->getMessage();
                $conn = null;
                return false;
            }
        }
        public function teacherLogin(array $data) {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Use a prepared statement to prevent SQL injection.
                $sql = "SELECT * FROM teachers WHERE email = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$data[0]]);
                $teacher = $statement->fetch(PDO::FETCH_ASSOC);
                $conn = null;

                if (!$teacher) {
                    return [];
                }

                // Verify the password against the stored hash.
                if (password_verify($data[1], $teacher['password'])) {
                    return $teacher;
                } else {
                    // If the old password was plain text, verify it and update the hash.
                    if ($data[1] === $teacher['password']) {
                        $this->updateTeacherPassword($teacher['teacher_id'], $data[1]);
                        return $teacher;
                    }
                }

                return [];
            } catch (PDOException $err) {
                return [];
            }
        }

        public function updateTeacherPassword($teacher_id, $password) {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE teachers SET password = ? WHERE teacher_id = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$password_hash, $teacher_id]);
                $conn = null;
                return true;
            } catch (PDOException $err) {
                $conn = null;
                return false;
            }
        }
        public function getTeacherById($teacher_id) {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
            $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
            $sql = "SELECT teacher_id, full_name, profile_picture, subjects, TIMESTAMPDIFF(YEAR, date_created, CURDATE()) AS time_on_platform FROM teachers";
            $statement = $conn->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getVerifiedTeachers() {
            $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
            $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
        }//Quiz

        public function addStudyContent($subject, $category, $title, $content) {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "SELECT s.stud_id, s.stud_name, s.level, s.number, s.email FROM students s JOIN mentors m ON s.stud_id = m.stud_id WHERE m.teacher_id = ? AND m.state = 'Yes'";
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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

    public function getSubjectsWithContent() {
        try {
            $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "SHOW TABLES";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $tables = $statement->fetchAll(PDO::FETCH_COLUMN);
            
            $union_parts = [];

            foreach ($tables as $table) {
                if (strpos($table, '_gce') !== false) {
                     $union_parts[] = "SELECT DISTINCT subject FROM `$table`";
                }
            }

            if (empty($union_parts)) {
                return [];
            }

            $sql = implode(" UNION ", $union_parts) . " ORDER BY subject";
            
            $statement = $conn->prepare($sql);
            $statement->execute();
            $subjects = $statement->fetchAll(PDO::FETCH_COLUMN);
            
            $conn = null;
            return $subjects;
            
        } catch(PDOException $e) {
            error_log("Failed to get subjects with content: " . $e->getMessage());
            return [];
        }
    }

    public function getStudentContentById($student_content_id, $student_id) {
        try {
            $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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

        public function saveQuizScore($student_content_id, $score, $total_questions) {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE student_content SET score = ?, total_questions = ? WHERE student_content_id = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$score, $total_questions, $student_content_id]);
                $conn = null;
                return true;
            } catch (PDOException $err) {
                error_log("Database error in saveQuizScore: " . $err->getMessage());
                $conn = null;
                return false;
            }
        }

        public function getStudentById($stud_id) {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "SELECT * FROM students WHERE stud_id = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$stud_id]);
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                $conn = null;
                return $result;
            } catch (PDOException $err) {
                error_log("Database error in getStudentById: " . $err->getMessage());
                $conn = null;
                return false;
            }
        }

        public function getStudentContentFromTutor($student_id, $teacher_id) {
            try {
                $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
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

    public function columnExists($table, $column) {
        try {
            $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SHOW COLUMNS FROM `$table` LIKE '$column'";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $conn = null;
            return $result ? true : false;
        } catch (PDOException $err) {
            error_log("Database error in columnExists: " . $err->getMessage());
            $conn = null;
            return false;
        }
    }

    public function addColumn($table, $column, $attributes) {
        // Basic validation for column name to prevent SQL injection
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            error_log("Invalid column name format: " . $column);
            return false;
        }

        try {
            $conn = new PDO("mysql:host=".$this->host().";dbname=" . $this->DBname(), $this->username(), $this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Sanitize attributes by removing potentially harmful characters
            $sanitized_attributes = filter_var($attributes, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            $sql = "ALTER TABLE `$table` ADD `$column` $sanitized_attributes";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $conn = null;
            return true;
        } catch (PDOException $err) {
            error_log("Database error in addColumn: " . $err->getMessage());
            $conn = null;
            return false;
        }
    }

    public function getAllNews() {
        try {
            $conn = new PDO("mysql:host=".$this->host().";dbname=".$this->DBname(), $this->username(), $this->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM news WHERE expiry_date >= CURDATE() OR expiry_date IS NULL ORDER BY created_at DESC";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $news = $statement->fetchAll(PDO::FETCH_ASSOC);
            $conn = null;
            return $news;
        } catch (PDOException $err) {
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
            
            $host = $this->host();
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
                        AVG(score) as score,
                        SUM(total_questions) as total_questions,
                        SUM(correct_answers) as correct_answers,
                        MAX(date_taken) as date_taken,
                        (CASE WHEN AVG(score) >= 10 THEN 'passed' ELSE 'failed' END) as status,
                        MAX(year) as year,
                        level
                    FROM performances 
                    WHERE stud_id = ? AND subject = ?";
            
            $params = [$stud_id, $subject];
            
            if (!empty($level)) {
                $sql .= " AND level = ?";
                $params[] = $level;
            }
            
            $sql .= " GROUP BY DATE(date_taken), level ORDER BY date_taken ASC";
            
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
   //getTopPerformers
   public function savePersonalizedQuizPerf(array $perf_data){
    try{
        $conn = $this->getConnection();
        
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
        
        return $result ? $conn->lastInsertId() : false; // Return the new performance ID if successful
    } catch(PDOException $err){
        error_log("Database error in savePersonalizedQuizPerf: " . $err->getMessage());
        return false;
    }
}
}
class QuizGenerator extends Performance {
        private $geminiApiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent";

        private function getGeminiApiKey() {
            return getenv('GEMINI_API_KEY');
        }
        
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
            
            $apiKey = $this->getGeminiApiKey();
            if (empty($apiKey)) {
                error_log("Gemini API key is not set.");
                return ['success' => false, 'error' => "The quiz generation service is not configured. Please contact an administrator."];
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->geminiApiUrl . "?key=" . $apiKey);
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
                error_log("Gemini API error ($httpCode): " . $errorMessage);
                return ['success' => false, 'error' => "Failed to generate quiz. Please try again later."];
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
