<?php
session_start();
include 'classes.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Quiz-Master</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }
        .header {
            background: linear-gradient(to left, purple, blue);
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .navbar {
            display: flex;
            justify-content: center;
            background: linear-gradient(to left, purple, blue);
            padding: 10px 0;
        }
        .navbar a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 18px;
            margin: 0 10px;
            border-radius: 5px;
        }
        .navbar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .navbar .menu-icon {
            display: none;
            font-size: 24px;
            cursor: pointer;
            color: white;
        }
        .main-content {
            padding: 20px;
            text-align: center;
        }
        .main-content h2 {
            color: #333;
        }
        .main-content p {
            color: #666;
        }
        .features {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .feature {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 300px;
            margin: 10px;
            text-align: center;
        }
        .feature img {
            max-width: 100%;
            border-radius: 10px;
        }
        .feature h3 {
            color: #333;
        }
        .feature p {
            color: #666;
        }
        .footer {
            background: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .footer p {
            margin: 0;
        }
        @media (max-width: 768px) {
            .navbar a {
                display: none;
            }
            .navbar .menu-icon {
                display: block;
            }
            .navbar.responsive {
                position: relative;
            }
            .navbar.responsive a {
                display: block;
                text-align: left;
                padding: 10px;
                width: 100%;
                background: linear-gradient(to left, purple, blue);
            }
        }
        .feat{
          background: white;
            border: 2px solid; transparent
            border-radius: 5px;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
            color: transparent;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to left, purple, blue);
        }
        
    </style>
</head>
<body>

<div class="header">
    <h1>Quiz-Master</h1>
    <p>Your alternate platform for interactive learning through carefully curated MCQs</p>
</div>

<div class="navbar" id="navbar">
    <a href="quest_selection.php">Take Quiz <i class="fa fa-pen-to-square"></i></a>
    <a href="discover.php">Discover Tutors <i class="fa fa-chalkboard-user"></i></a>
    <a href="perf.php">View Performances <i class="fa fa-chart-line"></i></a>
    <a href="chat.php">Chat with AI <i class="fa fa-robot"></i></a>
    <a href="communities.php">Community <i class="fa fa-users"></i></a>
    <?php
    $log= $_SESSION['logged_in'];

      if($log==true){
        ?>
    <a href="user_profile.php">My Profile <i class="fa fa-user"></i></a>
    <?php 
      }
    else{?>
      <a href="login.php" style="border: 1px solid white; background: none; color: white; padding: 10px 20px; text-decoration: none; font-size: 18px; margin: 0 10px; border-radius: 5px;">Login <i class="fa fa-sign-in-alt"></i></a>
    <?php
    }
    ?>
    <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
</div>

<div class="main-content">
    <h2>Learning is For Everyone</h2>
    <p>At Quiz-Master, we value the individual. Students and parents are provided with experienced industry professionals who make it their business to help you understand your subjects.</p>
</div>
<!-- <div class="features">
  <div class="feature">
<a href="quest_selection.php">
  <button class="feat">Take Quiz <i class="fa fa-pen-to-square"></i></button>
</a>
</div>
</div> -->


<div class="features">
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Take Quiz">
        <h3>Take A Quiz</h3>
        <p>Boost up your current level with past GCE questions and explore your brain's potential with our quiz timer.</p>
        <!-- <a href="quest_selection.php" class="button">Take Quiz <i class="fa fa-pen-to-square"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/tuto.jpeg" alt="Discover Tutors">
        <h3>Discover Tutors</h3>
        <p>Discover tutors all over the globe who are willing to give you a detailed walkthrough on all your subjects.</p>
        <!-- <a href="discover.php" class="button">Discover <i class="fa fa-chalkboard-user"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/ai.png" alt="Chat with AI">
        <h3>Chat with Braze AI</h3>
        <p>Talk to our AI chatbot to help you with any doubts or worries while you are studying.</p>
        <!-- <a href="chat.php" class="button">Chat with AI <i class="fa fa-robot"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/performance.png" alt="View Performance">
        <h3>View Performance</h3>
        <p>Track your progress and view detailed performance reports to understand your strengths and areas for improvement.</p>
        <!-- <a href="perf.php" class="button">View Performance <i class="fa fa-chart-line"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Take Quiz">
        <h3>Community</h3>
        <p>Studying in group help you to evolve and learn faster</p>
        <!-- <a href="quest_selection.php" class="button">Take Quiz <i class="fa fa-pen-to-square"></i></a> -->
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Quiz-Master. All rights reserved.</p>
</div>

<script>
    function toggleMenu() {
        var navbar = document.getElementById("navbar");
        if (navbar.className === "navbar") {
            navbar.className += " responsive";
        } else {
            navbar.className = "navbar";
        }
    }
</script>

</body>
</html>