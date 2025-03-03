<?php
session_start();

// if (!isset( $_SESSION['is_logged_in']) &&  $_SESSION['is_logged_in'] !== true) {
//   header("location:login.php");
// }
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
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
  
</head>
<body>
    <div class="wrapper">
       
            <div class="slider-container">
                <div class="slider">
                    <div class="slide">
                        <h1>Take A Quiz</h1><br>
                        <p>Boost up your current level with past GCE questions <br>and explore your brain's potential with our <br>quiz timer</p><br>
                        <a href="quest_selection.php"><button>Take Quiz <i class="fa fa-pen-to-square"></i></button> </a>
                    </div>
                    <div class="slide">
                        <h1>Discover Tutors</h1><br>
                       <p>Discover tutors all over the globe who are willing <br>to give you a detailed walkthrough on <br> all your subjects</p> 
                       <a href="discover.html"><button>Discover <i class="fa fa-chalkboard-user"></i></button></a>

                    </div>
                    <div class="slide">
                        <h1>Performances</h1> <br>
                        <p>Explore your various performances and statistics on  <br> your speed and accuracy on taking each test <br>to give you a rapid progress</p>
                        <a href="performance.html"><button>View performances <i class="fa fa-chart-line"></i></button></a>

                    </div>
                    <div class="slide">
                        <h1>Chat with Braze AI</h1> <br>
                        <p>Talk to oui AI chatbot to help you with <br>any doubts or worries while you <br>are studying</p>
                        <a href="chat.html"><button>Chat with AI <i class="fa fa-robot"></i></button></a>

                    </div>
                    
                </div>
                <div class="navigation-buttons">
                    <button class="nav-btn prev-btn">&lt;</button>
                    <button class="nav-btn next-btn">&gt;</button>
                </div>
            </div>
            <div class="dots-container"></div>
            <div class="row">
                <div class="col-lg-4 head">
                    <div class="heading bd-placeholder-img rounded-circle" width="140" height="140" >
                        <i class="fa fa-pen-to-square"></i>
                    </div>
                  <h2>Take Quiz</h2>
                  <p>Boost up your current level with past GCE questions and explore your brain's potential with our quiz timer</p><br>
                  <p><a class="btn btn-primary" href="quest_selection.php">View details &raquo;</a></p>
                </div>
                <div class="col-lg-4 head">
                    <div class="heading bd-placeholder-img rounded-circle" width="140" height="140" >
                        <i class="fa fa-chart-line"></i>
                    </div>          
                  <h2>Performances</h2>
                  <p>Explore your various performances and statistics on your speed and accuracy on taking each test to give you a rapid progress</p> <br>
                  <p><a class="btn btn-primary" href="performance.html">View details &raquo;</a></p>
                </div>
                <div class="col-lg-4 head">
                    <div class="heading bd-placeholder-img rounded-circle" width="140" height="140" >
                        <i class="fa fa-robot"></i>
                    </div>          
                  <h2>Chat with AI</h2>
                  <p>Talk to oui AI chatbot to help you with any doubts or worries while you are studying thereby making your learning smooth and exciting</p><br>
                  <p><a class="btn btn-primary" href="chat.html">View details &raquo;</a></p>
                </div>
              </div>
          
          
              <hr class="featurette-divider">
          
              <div class="row featurette">
                <div class="col-md-7">
                  <h2 class="featurette-heading">Unlock Your Potential. <span class="text-muted">Take The Challenge!.</span></h2>
                  <p class="lead">Test your knowledge and skills with our comprehensive quizzes, carefully crafted to challenge and engage you.Get instant feedback and insights into your strengths and weaknesses.</p>
                </div>
                <div class="col-md-5">
                  <img src="img/quiz.jpeg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Quiz</title><rect width="100%" height="100%" fill="#eee"/><text x="50%" y="50%" fill="#aaa" dy=".3em"></text></svg>
          
                </div>
              </div>
          
              <hr class="featurette-divider">
          
              <div class="row featurette">
                <div class="col-md-7 order-md-2">
                  <h2 class="featurette-heading">Your Learning Journey. <span class="text-muted">Track Your Progress!</span></h2>
                  <p class="lead">Track your progress and evaluate your performancewith our detailed analytics and reporting tools. Identify ares for improvement and monitor your growth over time.</p>
                </div>
                <div class="col-md-5 order-md-1">
                  <img src="img/tuto.jpeg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Tutors</title><rect width="100%" height="100%" fill="#eee"/><text x="50%" y="50%" fill="#aaa" dy=".3em"></text></svg>
          
                </div>
              </div>
          
              <hr class="featurette-divider">
          
              <div class="row featurette">
                <div class="col-md-7">
                  <h2 class="featurette-heading">Ask Me Anything. <span class="text-muted">Your Personal Study Assistant!.</span></h2>
                  <p class="lead">Meet your personal study assistant! Our AI-powered chatbot is here to help you with any questions or topics  you'd like to discuss. Get personalized guidance, explanations, and support to enhance your learning experience.</p>
                </div>
                <div class="col-md-5">
                  <img src="img/ai.png" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="600" height="600" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Braze AI</title><rect width="100%" height="100%" fill="#eee"/><text x="50%" y="50%" fill="#aaa" dy=".3em"></text></svg>
          
                </div>
              </div>
          
              <hr class="featurette-divider">
              <div class="back">
                <a href="index.php">
                    <button class="button float-start">
                        <i class="fa fa-arrow-left"></i>
                      </button>
                      </a>
                <a href="#">
                </div>
                <button class="button float-end">
                    <svg class="svgIcon" viewBox="0 0 384 512">
                      <path
                        d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"
                      ></path>
                    </svg>
                  </button>
                  </a></p>
            
          </div>
            <!-- FOOTER -->
            <div class="darksection">
                <div class="quizmaster">
                    <span>QuizMaster</span> <i class="fa fa-puzzle-piece"></i>
                    <p>Your alternate platform for interactive learning through carefully curated MCQs </p>
                </div>
                <div class="feature">
                    <span>Features</span> <i class="fa-regular fa-globe"></i>
                    <ul>
                        <li>Smart Learning</li>
                        <li>Progress Tracking</li>
                        <li>Study Groups</li>
                        <li>Performance Analytics</li>
                    </ul>
                </div>
                <div class="resources">
                    <span>Resources</span> <i class="fa fa-anchor"></i>
                    <ul>
                        <li>Blog</li>
                        <li>Tutorial</li>
                        <li>Support</li>
                        <li>F&Q</li>
                    </ul>
                </div>
                <div class="contact">
                    <span>Contact</span> <i class="fa fa-phone"></i>
                    <ul>
                        <li>brandonokale@gmail.com or lgor.kaze.0@gmail.com</li>
                        <li>+237 670 26 29 19  or  +237 651 36 08 72</li>
                        <li>@QuizMaster</li>
                    </ul>
                </div>
                <hr>
                <div class="foot">
                  
        
                    <p><i class="fa-regular fa-copyright"></i> 2025 QuizMaster. All rights reserved</p>
                </div>
            </div>
            </div>
          
            <script src="js/script.js"></script>
              <script src="js/bootstrap.bundle.min.js"></script>
            </div>
            <script>
                const slider = document.querySelector('.slider');
                const slides = document.querySelectorAll('.slide');
                const prevBtn = document.querySelector('.prev-btn');
                const nextBtn = document.querySelector('.next-btn');
                const dotsContainer = document.querySelector('.dots-container');
        
                let currentSlide = 0;
                const totalSlides = slides.length;
        
                // Create navigation dots
                slides.forEach((_, index) => {
                    const dot = document.createElement('span');
                    dot.classList.add('dot');
                    if (index === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => goToSlide(index));
                    dotsContainer.appendChild(dot);
                });
        
                const dots = document.querySelectorAll('.dot');
        
                function goToSlide(slideIndex) {
                    slider.style.transform = `translateX(-${slideIndex * 100}%)`;
                    
                    dots.forEach(dot => dot.classList.remove('active'));
                    dots[slideIndex].classList.add('active');
                    
                    currentSlide = slideIndex;
                }
        
                function nextSlide() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    goToSlide(currentSlide);
                }
        
                function prevSlide() {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    goToSlide(currentSlide);
                }
        
                // Auto slide every 4 seconds
                setInterval(nextSlide, 8000);
        
                // Event listeners for navigation buttons
                nextBtn.addEventListener('click', nextSlide);
                prevBtn.addEventListener('click', prevSlide);
            </script>
    
</body>
</html>