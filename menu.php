<?php
// session_start();
include 'classes.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="myCss/menu-style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Quiz-Master</title>
    <style>
      
        
    </style>
</head>
<body>



<div class="navbar" id="navbar">
<a href="home.php"><i class="fa fa-home"></i></a>

<?php
      if(isset( $_SESSION['logged_in'])&& $_SESSION['logged_in']==true){
        ?>
    <a href="user_profile.php">My Profile <i class="fa fa-user"></i></a>
    <?php 
      }
    else{?>
      <a href="login.php" style="border: 1px solid white; background: none; color: gray; padding: 10px 20px; text-decoration: none; font-size: 18px; margin: 0 10px; border-radius: 5px;">Login <i class="fa fa-sign-in-alt"></i></a>
    <?php
    }
    ?>
    <a href="quest_selection.php"> Official MCQ <i class="fa fa-pen-to-square"></i> </a>
    <a href="discover.php"> Discover Tutors <i class="fa fa-chalkboard-user"></i> </a>
    <a href="perf.php"> View Performances <i class="fa fa-chart-line"></i> </a>
    <a href="chat_with_ai.php"> Braze AI <i class="fa fa-robot"></i> </a>
    <a href="communities.php">Community <i class="fa fa-users"></i> </a>
    <a href="question_bank.php"> Question Bank <i class="fa fa-bank"></i> </a>
    <a href="learn.php"> learn <i class="fa fa-book"></i> </a>
    <a href="personal.php"> Personal plans <i class="fa fa-map"></i> </a><!-- /\ here the user will be able to create personal quizes, notes,  -->
    <a href="settings.php">Settings<i class="fa fa-repair"></i> </a>

    <!-- <a href="question_bnk.php">School<i class="fa fa-"></i></a> -->


    

    <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>

  </div>


</body>
</html>