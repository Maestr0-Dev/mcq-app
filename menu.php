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
    <link type="text/css" rel="stylesheet" href="myCss/menu-style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Quiz-Master</title>
    <style>
      
        
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
  
  document.addEventListener('DOMContentLoaded', function() {
    // Floating navbar on scroll
    let prevScrollPos = window.pageYOffset;
    const navbar = document.querySelector('.navbar');
    
    window.onscroll = function() {
      const currentScrollPos = window.pageYOffset;
      
      if (prevScrollPos > currentScrollPos) {
        navbar.classList.add('visible');
      } else {
        navbar.classList.remove('visible');
      }
      
      prevScrollPos = currentScrollPos;
      
      // Scroll animations
      const fadeElements = document.querySelectorAll('.fade-in');
      fadeElements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        if (elementTop < window.innerHeight - 100) {
          element.classList.add('visible');
        }
      });
    };
    
    // Add floating bubbles to header
    const bubbles = document.createElement('div');
    bubbles.className = 'bubbles';
    
    for (let i = 0; i < 4; i++) {
      const bubble = document.createElement('div');
      bubble.className = 'bubble';
      bubbles.appendChild(bubble);
    }
    
    document.querySelector('.header').appendChild(bubbles);
    
    // Add fade-in class to elements
    document.querySelectorAll('.feature').forEach(element => {
      element.classList.add('fade-in');
    });
    
    document.querySelector('.main-content h2').classList.add('fade-in');
    document.querySelector('.main-content p').classList.add('fade-in');
    
    // Trigger initial scroll to show visible elements
    window.dispatchEvent(new Event('scroll'));
  });
</script>

</body>
</html>