<?php
session_start();
include '../classes.php';

if (!isset($_SESSION['admin_logged_in']) || !in_array($_SESSION['admin_level'], [1, 2, 3])) {
    header("location:" . BASE_URL . "admin/login.php");
    exit();
}
$year="";
$title="";
$subject="";
$instruction="";
$quest="";
$table="";
$A="";
$B="";
$C="";
$D="";
$ans="";
$err=false;
$subj=['Literature','History','Physics','English language','Mathemathics','Futher mathemathics','ICT','Computer science','Chemistry','Biology','Economics','French','Geography','Religion'];

if($_SERVER['REQUEST_METHOD']=='POST'){
    $db = new DB();
    $table = $_POST['exam'];
    $year = $_POST['year'];
    
    $subject = $_POST['subject'];
    $instruction = $_POST['instruction'];

    if (isset($_POST['questions'])) {
        foreach ($_POST['questions'] as $key => $q) {
            $quest = $q['question'];
            $A = $q['A'];
            $B = $q['B'];
            $C = $q['C'];
            $D = $q['D'];
            $options = [
                'A' => $A,
                'B' => $B,
                'C' => $C,
                'D' => $D
            ];
            $ans = $options[$q['ans']];

            $newImageName = '';
            if (!empty($_FILES['questions']['name'][$key]['diagram'])) {
                $fileName = $_FILES['questions']['name'][$key]['diagram'];
                $fileSize = $_FILES['questions']['size'][$key]['diagram'];
                $tmpName = $_FILES['questions']['tmp_name'][$key]['diagram'];
                $validImageExtension = ['jpeg', 'jpg', 'png'];
                $imageExtension = explode('.', $fileName);
                $imageExtension = strtolower(end($imageExtension));

                if (!in_array($imageExtension, $validImageExtension)) {
                    echo "<p>Invalid image type for question {$key}</p>";
                } else if ($fileSize > 500000) {
                    echo "<p>Image too big for question {$key}</p>";
                } else {
                    $newImageName = uniqid();
                    $newImageName .= '.' . $imageExtension;
                    move_uploaded_file($tmpName, 'diagrams/' . $newImageName);
                }
            }
            // if(empty($data)){
            $data = [$year, $subject, $instruction, $quest, $A, $B, $C, $D, $newImageName, $ans];
            $result = $db->questions($table, $data);
            // $data=[];
             
            // $table = "";
            // $year = "";
            // $title = "";
            // $subject ="";
            // $instruction = "";

            // }
        }
        $_SESSION['success_message'] = 'All questions added successfully!';
        header("Location: CMS.php");
        exit();
    } else {
        $quest=$_POST['question'];
        $A=$_POST['A'];
        $B=$_POST['B'];
        $C=$_POST['C'];
        $D=$_POST['D'];
        //answer selection
        $options =[
            'A'=>$A,
            'B'=>$B,
            'C'=>$C,
            'D'=>$D
        ];
        $ans=$options[$_POST['ans']];

        //image to database
        $newImageName = '';
        if(!empty($_FILES['diagram']['name'])){
            $fileName= $_FILES["diagram"]["name"];
            $fileSize= $_FILES["diagram"]["size"];
            $tmpName= $_FILES["diagram"]["tmp_name"];
            $validImageExtension=['jpeg','jpg','png'];
            $imageExtension=explode('.',$fileName);
            $imageExtension=strtolower(end($imageExtension));
            if(!in_array($imageExtension, $validImageExtension)){
                echo"
                <p>Invalid image type</p>
                ";
            }else if($fileSize>500000){
                echo"
                <p>Image too big</p>
                ";
            }else{
                $newImageName=uniqid();
                $newImageName .= '.' . $imageExtension;
                move_uploaded_file($tmpName, 'diagrams/'. $newImageName);
            }
        }
        if(!empty($data)){
        $data=[$year,$subject,$instruction,$quest,$A,$B,$C,$D,$newImageName,$ans];
        $result= $db->questions($table, $data);
        $_SESSION['success_message'] = 'Added Successfully';
        header("Location: CMS.php");
        exit();
        }else{
             echo "
         <script>
         alert('No data entered')
         </script>
         ";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
    <title>Quiz-master</title>
    <script>
    MathJax = {
      tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']]
      }
    };
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</head> 
<body>
 <?php include 'admin_nav.php'; ?> 


<?php
if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']);
}
?>
    <div class="container">
<div class="header">
    <h1 style="color:white;"> CONTENT MANAGMENT SYSTEM</h1>
<p>This section is reserved for administrators and is used to add and edit past questions for the users</p>
</div>
<form action="" method="post"  enctype="multipart/form-data">
    <div class="question_info">
<select name="exam" id="" required>
    <option disable>Select the type</option>
    <!-- <option value="o_level_mock" disabled>O-level Mock</option> -->
    <option value="o_level_gce">O-level GCE</option>
    <!-- <option value="a_level_mock" disabled>A-level Mock</option> -->
    <option value="a_level_gce">A-level GCE</option>
</select>
<select name="year" id="" required>
    <option value="">select the year</option>
    <?php
    $date=date("Y");
    for($i=2015;$i<$date;$i++){
        ?>
        <option value="<?=$i?>"><?=$i?></option>
    <?php
    }
    ?>
</select>

<!-- <input type="text" name="title" placeholder="title" > -->
<select name="subject" id="subj" required>
    <option value="" selected disabled>subject</option>
    <?php
    
        for($i=0;$i<count($subj);$i++){
            ?>
            <option value="<?=$subj[$i]?>"><?=$subj[$i]?></option>
     <?php }
    ?>
</select>
<input type="text" name="instruction" placeholder="instructions">
</div>
<br><textarea name="question" id="" required>
enter the question
</textarea>
 
    <div>
<p>A: <input  name="A" type="text" required></p>
<p>B: <input  name="B" type="text" required></p>
<p>C: <input  name="C" type="text" required></p>
<p>D: <input  name="D" type="text" required></p>
<p>
    <p>Select the answer to the question</p>
    <span><input name="ans" value="A" type="radio" required>A</span>
    <span><input name="ans" value="B" type="radio" required>B</span>
    <span><input name="ans" value="C" type="radio" required>C</span>
    <span><input name="ans" value="D" type="radio" required>D</span>
    
</p>
</div>
<div>
<label for="">Select an image if any
<input class="btn btn-primary" name="diagram" id="input" type="file" accept="image/*" style="color:white;" placeholder="Select an image">
</label>
<div class="imgDiv">
<img id="preview" src="" alt="your image" style="max-width: 300px; display:none;">
</div>
</div>
<button type="submit" class="save"><b>Save</b></button>
<button class="save">
<a href="view_questions.php">View Questions</a>
</button>
<button type="button" id="addQuestionsBtn" class="save"><b>Multiple Questions</b></button>

</form>
</div>

<div id="addQuestionsInterface" style="display: none;">
    <div class="header">
        <h1 style="color:white;">Add Multiple Questions</h1>
        <p>Enter the details for the questions you want to add.</p>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="question_info">
            <select name="exam" id="" required>
                <option disable>Select the type</option>
                <option value="o_level_gce">O-level GCE</option>
                <option value="a_level_gce">A-level GCE</option>
            </select>
            <select name="year" id="" required>
                <option value="">select the year</option>
                <?php
                $date=date("Y");
                for($i=2015;$i<$date;$i++){
                    ?>
                    <option value="<?=$i?>"><?=$i?></option>
                <?php
                }
                ?>
            </select>
            <!-- <input type="text" name="title" placeholder="title" > -->
            <select name="subject" id="subj" required>
                <option value="" selected disabled>subject</option>
                <?php
                for($i=0;$i<count($subj);$i++){
                    ?>
                    <option value="<?=$subj[$i]?>"><?=$subj[$i]?></option>
                <?php }
                ?>
            </select>
            <input type="text" name="instruction" placeholder="instructions">
        </div>
        <div id="questionsContainer">
            <!-- Questions will be added here dynamically -->
        </div>
        <button type="button" id="addQuestionField" class="save">Add Question</button>
        <button type="submit" class="save"><b>Submit Questions</b></button>
        <button type="button" id="backBtn" class="view">Back</button>
    </form>
</div>

<script>
    document.getElementById('addQuestionsBtn').addEventListener('click', function() {
        document.querySelector('.container').style.display = 'none';
        document.getElementById('addQuestionsInterface').style.display = 'block';
    });

    document.getElementById('backBtn').addEventListener('click', function() {
        document.getElementById('addQuestionsInterface').style.display = 'none';
        document.querySelector('.container').style.display = 'block';
    });

    let questionCount = 0;

    function addQuestion() {
        questionCount++;
        const questionsContainer = document.getElementById('questionsContainer');
        const newQuestionDiv = document.createElement('div');
        newQuestionDiv.classList.add('question-block');
        newQuestionDiv.innerHTML = `
            <hr>
            <h4>Question ${questionCount}</h4>
            <textarea name="questions[${questionCount}][question]" required>Enter the question</textarea>
            <p>A: <input name="questions[${questionCount}][A]" type="text" required></p>
            <p>B: <input name="questions[${questionCount}][B]" type="text" required></p>
            <p>C: <input name="questions[${questionCount}][C]" type="text" required></p>
            <p>D: <input name="questions[${questionCount}][D]" type="text" required></p>
            <p>
                <p>Select the answer to the question</p>
                <span><input name="questions[${questionCount}][ans]" value="A" type="radio" required>A</span>
                <span><input name="questions[${questionCount}][ans]" value="B" type="radio" required>B</span>
                <span><input name="questions[${questionCount}][ans]" value="C" type="radio" required>C</span>
                <span><input name="questions[${questionCount}][ans]" value="D" type="radio" required>D</span>
            </p>
            <div>
                <label for="">Select an image if any
                    <input class="btn btn-primary" name="questions[${questionCount}][diagram]" type="file" accept="image/*" style="color:white;" onchange="previewImage(this)">
                </label>
                <div class="imgDiv">
                    <img src="" alt="your image" style="max-width: 300px; display:none;">
                </div>
            </div>
        `;
        questionsContainer.appendChild(newQuestionDiv);
    }

    document.getElementById('addQuestionField').addEventListener('click', addQuestion);

    // Add one question field by default
    addQuestion();

    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            const preview = input.parentElement.nextElementSibling.querySelector('img');
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

//to view uploaded image
    const imageInput=document.getElementById('input');
    const imagePreview=document.getElementById('preview');
    imageInput.addEventListener('change',function(){
        const file=this.files[0];
        if(file){
            const reader=new FileReader();
            reader.onload=function(e){ 
                imagePreview.src=e.target.result;
                imagePreview.style.display='block';
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>
