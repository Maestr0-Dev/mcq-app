<?php
 session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="myCss/home.css">

    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="header">
<img src="Quiz-master-logo.png" alt="Quiz-Master Logo" style="width: 100px; height: auto; vertical-align: middle; margin-right: 10px;">
    <h1>Quiz-Master</h1>
    <p>Your alternate platform for interactive learning through carefully curated MCQs</p>
</div>
<?php
include 'menu.php';
?>
<div class="main-content">
    <h2>Learning is For Everyone</h2>
    <p>At Quiz-Master, we value the individual. Students are provided with experienced industry professionals who make it their business to help you understand your subjects.</p>
</div>

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
        <img src="img/ai.png" alt="View Performance">
        <h3>View Performance</h3>
        <p>Track your progress and view detailed performance reports to understand your strengths and areas for improvement.</p>
        <!-- <a href="perf.php" class="button">View Performance <i class="fa fa-chart-line"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Take Quiz">
        <h3>Community</h3>
        <p>Text </p>
        <!-- <a href="quest_selection.php" class="button">Take Quiz <i class="fa fa-pen-to-square"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Take Quiz">
        <h3>Question bank</h3>
        <p>Text </p>
        <!-- <a href="quest_selection.php" class="button">Take Quiz <i class="fa fa-pen-to-square"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Take Quiz">
        <h3>Learn</h3>
        <p>Text </p>
        <!-- <a href="quest_selection.php" class="button">Take Quiz <i class="fa fa-pen-to-square"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Take Quiz">
        <h3>Personal plans</h3>
        <p>Text </p>
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
