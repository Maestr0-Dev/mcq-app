<?php 
session_start();
include 'classes.php';
      if(isset( $_SESSION['id'])){

    $year="";
    $subject="";
    $db = new DB();
    $subj = $db->getSubjectsWithContent();
    $exam="";
    $time="";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

       $_SESSION['year']= $_POST['year'];
       $_SESSION['subj']=$_POST['subj'];
       $_SESSION['exam']=$_POST['exam'];
       $_SESSION['time']=$_POST['timer']*60;
       $_SESSION['started']=true;
       $_SESSION['SCR']=0;

      header("location:passed_quest.php");
    
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question selection</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="myCss/qu-sel.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 600px;
        }

       
       

        h2 {
            color:rgb(19, 67, 114);
            margin-bottom: 1.5rem;
            text-align: center;
        }   

        select, input {
            width: 100%;
            padding: 0.8rem;
            margin: 1rem 0;
            border: 2px solid rgb(10, 97, 184);
            border-radius: 5px;
        }

        .nav-buttons {
            display: flex;
            justify-content: right;
            margin-top: 2rem;
        }

        #startQuiz {
            background-color: #28a745;
            /* padding: 0.8rem 1.5rem; */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
      
        
        #startQuiz:hover {
            background-color:rgb(28, 110, 46);
        }

        @media (max-width: 480px) {
            .container {
                padding: 1rem;
            }

            .nav-btn {
                padding: 0.6rem 1rem;
            }
        }
    </style>
</head>
<body>
    <form action="" class="container"  method="post">

        <div class="section active" id="section1">
        <a href="home.php" class="back-button">
        <i class="fa fa-arrow-left"></i> 
        </a>
            <h2>Choose an Option</h2>
            <select name="exam" id="" required>
    <option  disabled>Select exam</option>
    <!-- <option value="o_level_mock">O-level Mock</option> -->
    <option value="o_level_gce">O-level GCE</option>
    <!-- <option value="a_level_mock">A-level Mock</option> -->
    <option value="a_level_gce">A-level GCE</option>
</select>
    

       
            <h2>Select Subject</h2>
            <select name="subj" id="subject" required>
            <option disabled selected>Select a subject</option>
            <?php
                foreach ($subj as $subject) {
                    echo "<option value=\"$subject\">$subject</option>";
                }
            ?>
            <!-- <option value="Physics">Physics</option> -->
            </select>
        
            <h2>Select Year</h2>
            <select name="year" id="year" required>
    <option disabled default >select the year</option>
    <?php
    $date=date("Y");
    for($i=2015;$i<$date;$i++){
        ?>
        <option value="<?=$i?>"><?=$i?></option>
    <?php
    }
    ?>
    <!-- <option value="2024">2024</option> -->

</select>
        
            <h2>Select Timer</h2>
           <input type="number" name="timer" placeholder="Time in minutes" required>
        <!-- <div class="nav-buttons"> -->
            <a href="quiz.php">
                <button class="nav-btn" id="startQuiz" type="submit">Start</button>
            </a>
        <!-- </div> -->

</form>
    
</body>
</html> 
<?php
}
else{
    header("location:login.php");

}
?>
