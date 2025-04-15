<?php
// session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link type="text/css" rel="stylesheet" href="css/style.css"> -->
    <link type="text/css" rel="stylesheet" href="myCss/nav.css">
    <link type="text/css" rel="stylesheet" href="C:\xampp\htdocs\mcq-app\fonts\css\all.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="C:\xampp\htdocs\mcq-app\css\bootstrap.min.css" rel="stylesheet">
    <title>Quiz-Master</title>
    <style>
      
        
    </style>
</head>
<body>


<div class="header">
<img src="Quiz-master-logo.png" alt="Quiz-Master Logo" style="width: 100px; height: auto; vertical-align: middle; margin-right: 10px;">
    <h2>Quiz-Master</h2>
    <!-- <p>Your alternate platform for interactive learning through carefully curated MCQs</p> -->
    <h3>Teacher Portal</h3>
</div>
<div class="navbar" id="navbar">

    <a href="quest_selection.php"> Official MCQ <i class="fa fa-pen-to-square"></i> </a>
    <a href="discover.php"> My students<i class="fa fa-user"></i> </a>
    <!-- <a href="performance.php"> View Performances <i class="fa fa-chart-line"></i> </a> -->
    <a href="chat_with_ai.php"> Braze AI <i class="fa fa-robot"></i> </a>
    <a href="communities.php">Community <i class="fa fa-users"></i> </a>
    <a href="question_bank.php"> Archives <i class="fa fa-bank"></i> </a>
    <a href="learn.php"> learn <i class="fa fa-book"></i> </a>
    <a href="teachersQuestions.php"> My Questions <i class="fa fa-book"></i> </a>

    <!-- <a href="myQuiz.php">Persona <i class="fa fa-map"></i> </a>/\ here the user will be able to create personal quizes, notes,  -->
    <a href="settings.php">Settings<i class="fa fa-repair"></i> </a>

    <?php
      if(isset( $_SESSION['teacher_id'])){
        $path = "teach_profil_imgs/" . $_SESSION['profile_picture'];
        ?>
    <a href="teacher_profile.php">My Profile <i class="fa fa-user"></i>
        <img src="<?=$path?>" alt="Profile Picture" style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">
</a>
    <?php 
      }
    else{?>
      <a href="login.php" style="border: 1px solid white; background: none; color: gray; padding: 10px 20px; text-decoration: none; font-size: 18px; margin: 0 10px; border-radius: 5px;">Login <i class="fa fa-sign-in-alt"></i></a>
    <?php
    }
    ?>

    <!-- <a href="question_bnk.php">School<i class="fa fa-"></i></a> -->


    

    <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>

  </div>


</body>
</html>