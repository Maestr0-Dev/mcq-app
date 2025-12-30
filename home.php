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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: #ffffff;
            overflow-x: hidden;
        }

        /* Header Styling */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="40%" r="50%"><stop offset="0%" stop-color="%23fff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23fff" stop-opacity="0"/></radialGradient></defs><circle cx="10" cy="10" r="10" fill="url(%23a)"/><circle cx="30" cy="5" r="8" fill="url(%23a)"/><circle cx="60" cy="15" r="6" fill="url(%23a)"/><circle cx="80" cy="8" r="12" fill="url(%23a)"/></svg>') repeat;
            opacity: 0.3;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .header-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header img {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }

        .header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.3rem;
            font-weight: 300;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
            text-align: center;
        }

        .main-content h2 {
            font-size: 2.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            position: relative;
        }

        .main-content h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .main-content p {
            font-size: 1.2rem;
            color: #7f8c8d;
            max-width: 700px;
            margin: 40px auto;
            line-height: 1.8;
        }

        /* News Section */
        .news-section {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border: none;
            border-radius: 20px;
            padding: 30px;
            margin: 40px auto;
            max-width: 1000px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .news-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }

        .news-content {
            position: relative;
            z-index: 2;
        }

        .news-content strong {
            font-size: 1.3rem;
            color: #2c3e50;
            display: block;
            margin-bottom: 10px;
        }

        /* Features Grid */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1400px;
            margin: 80px auto;
            padding: 0 20px;
        }

        .feature {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .feature::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .feature:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }

        .feature:hover::before {
            left: 100%;
        }

        .feature img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 25px;
            border: 4px solid #f8f9fa;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .feature h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .feature p {
            color: #7f8c8d;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
            text-decoration: none;
        }

        .button i {
            margin-left: 8px;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 40px 0;
            margin-top: 80px;
        }

        .footer p {
            font-size: 1rem;
            opacity: 0.8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.5rem;
            }

            .header p {
                font-size: 1.1rem;
            }

            .main-content h2 {
                font-size: 2.2rem;
            }

            .features {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .feature {
                padding: 30px 20px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 60px 0;
            }

            .header h1 {
                font-size: 2rem;
            }

            .main-content {
                margin: 60px auto;
            }

            .main-content h2 {
                font-size: 1.8rem;
            }
        }

        /* Animation Classes */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Navbar Enhancements */
        .navbar.visible {
            transform: translateY(0);
        }
    </style>
</head>
<body>
<div class="header">
    <div class="header-content">
        <img src="Quiz-master-logo.png" alt="Quiz-Master Logo">
        <h1>Quiz-Master</h1>
        <p>Your ultimate platform for interactive learning through carefully curated questions</p>
    </div>
</div>

<?php
include 'menu.php';
?>

<div class="main-content">
    <?php
    include_once 'classes.php';
    $db = new DB();
    $news_items = $db->getAllNews();
     if (!empty($news_items)) {
                echo '<div class="news-section">';
                echo '<div class="news-content">';
                foreach ($news_items as $item) {
                    echo '<strong>' . htmlspecialchars($item['title']) . '</strong>' . htmlspecialchars($item['content']);
                    echo '<br><br>';
                }
                echo '</div>';
                echo '</div>';
            }
    ?>
    <h2>Learn Smart</h2>
    <p>At Quiz-Master, we value the individual. Students are provided with experienced industry professionals who make it their business to help you understand your subjects and unlock your full potential.</p>
</div>

<div class="features">
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Take Quiz">
        <h3>Take A Quiz</h3>
        <p>Boost up your current level with past GCE questions and explore your brain's potential with our intelligent quiz timer system.</p>
        <!-- <a href="quest_selection.php" class="button">Take Quiz <i class="fa fa-pen-to-square"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/tuto.jpeg" alt="Discover Tutors">
        <h3>Discover Tutors</h3>
        <p>Connect with experienced tutors worldwide who are ready to provide detailed walkthroughs on all your subjects.</p>
        <!-- <a href="discover.php" class="button">Discover <i class="fa fa-chalkboard-user"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/ai.png" alt="Chat with AI">
        <h3>Chat with Braze AI</h3>
        <p>Engage with our advanced AI chatbot to resolve any doubts or concerns while you're studying.</p>
        <!-- <a href="chat.php" class="button">Chat with AI <i class="fa fa-robot"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/ai.png" alt="View Performance">
        <h3>View Performance</h3>
        <p>Track your progress with detailed analytics and performance reports to identify strengths and improvement areas.</p>
        <!-- <a href="perf.php" class="button">View Performance <i class="fa fa-chart-line"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Community">
        <h3>Community</h3>
        <p>Join our vibrant learning community where students share knowledge, discuss concepts, and support each other's academic journey.</p>
        <!-- <a href="community.php" class="button">Join Community <i class="fa fa-users"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Question Bank">
        <h3>Question Bank</h3>
        <p>Access our comprehensive database of thousands of carefully curated questions across various subjects and difficulty levels.</p>
        <!-- <a href="question_bank.php" class="button">Browse Questions <i class="fa fa-bank"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Learn">
        <h3>Interactive Learning</h3>
        <p>Experience dynamic learning modules with multimedia content, interactive exercises, and real-time feedback systems.</p>
        <!-- <a href="learn.php" class="button">Start Learning <i class="fa fa-graduation-cap"></i></a> -->
    </div>
    <div class="feature">
        <img src="img/quiz.jpeg" alt="Personal Plans">
        <h3>Personal Plans</h3>
        <p>Get customized study plans tailored to your learning pace, goals, and preferences for optimal academic success.</p>
        <!-- <a href="personal_plans.php" class="button">Create Plan <i class="fa fa-calendar-check"></i></a> -->
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Quiz-Master. All rights reserved. Empowering minds, one question at a time.</p>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Floating navbar on scroll
    let prevScrollPos = window.pageYOffset;
    const navbar = document.querySelector('.navbar');
    
    window.onscroll = function() {
      const currentScrollPos = window.pageYOffset;
      
      if (prevScrollPos > currentScrollPos) {
        if(navbar) navbar.classList.add('visible');
      } else {
        if(navbar) navbar.classList.remove('visible');
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
    
    // Add fade-in class to elements
    document.querySelectorAll('.feature').forEach((element, index) => {
      element.classList.add('fade-in');
      element.style.transitionDelay = (index * 0.1) + 's';
    });
    
    const mainH2 = document.querySelector('.main-content h2');
    const mainP = document.querySelector('.main-content p');
    
    if(mainH2) mainH2.classList.add('fade-in');
    if(mainP) mainP.classList.add('fade-in');
    
    // Trigger initial scroll to show visible elements
    setTimeout(() => {
      window.dispatchEvent(new Event('scroll'));
    }, 100);
  });
</script>

</body>
</html>
