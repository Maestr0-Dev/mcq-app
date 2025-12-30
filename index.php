<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Master - Ace Your GCE Exams</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
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
        :root {
            --gradient: linear-gradient(135deg, #4776E6, #8E54E9);
            --primary: #6C5CE7;
            --accent: #FF7675;
            --text: #2D3436;
            --light: #F7F9FC;
            --white: #FFFFFF;
            --shadow: 0 4px 15px rgba(0,0,0,0.1);
            --radius: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        
        body {
            color: var(--text);
            line-height: 1.6;
        }
        
        .container {
            width: 92%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Header */
        header {
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .logo img {
            height: 40px;
            margin-right: 8px;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
        }
        
        .nav-links li {
            margin-left: 25px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--text);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: var(--primary);
        }
        
        /* Hero Section */
        .hero {
            background: var(--gradient);
            padding: 60px 0;
            color: var(--white);
        }
        
        .hero-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .hero-text {
            max-width: 550px;
        }
        
        .hero-text h1 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-text p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .hero-image {
            width: 45%;
        }
        
        .hero-image img {
            width: 100%;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        
        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--accent);
            color: var(--white);
            border: none;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary {
            background-color: var(--white);
            color: var(--primary);
        }
        
        .btn-secondary {
            background-color: transparent;
            border: 2px solid var(--white);
            margin-left: 15px;
        }
        
        /* Section Styling */
        section {
            padding: 70px 0;
        }
        
        section:nth-child(even) {
            background-color: var(--light);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 32px;
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        /* Features */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .feature-card {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: var(--shadow);
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: var(--white);
            font-size: 24px;
        }
        
        /* How It Works */
        .steps {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            counter-reset: step-counter;
        }
        
        .step {
            width: 30%;
            text-align: center;
            position: relative;
            padding: 20px;
        }
        
        .step:before {
            counter-increment: step-counter;
            content: counter(step-counter);
            width: 60px;
            height: 60px;
            background: var(--gradient);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        
        /* Testimonials */
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .testimonial-card {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: var(--shadow);
            position: relative;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }
        
        .author-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            background-color: #DDD;
            overflow: hidden;
        }
        
        /* FAQ */
        .faq-item {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 15px;
        }
        
        .faq-question {
            cursor: pointer;
            font-weight: 600;
            color: var(--primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .faq-answer {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(0,0,0,0.05);
            display: none;
        }
        
        .faq-item.active .faq-answer {
            display: block;
        }
        
        /* Footer */
        footer {
            background: var(--gradient);
            color: var(--white);
            padding: 40px 0 20px;
            text-align: center;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 30px;
            width: 90%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .modal-title {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .modal-options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 25px;
        }
        
        .modal-option {
            padding: 25px;
            border-radius: var(--radius);
            border: 2px solid rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s;
            width: 45%;
        }
        
        .modal-option:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }
        
        .modal-option i {
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #777;
            background: none;
            border: none;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .hero-content {
                flex-direction: column;
                text-align: center;
            }
            
            .hero-text {
                margin-bottom: 30px;
            }
            
            .hero-image {
                width: 80%;
            }
            
            .step {
                width: 100%;
                margin-bottom: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .hero-text h1 {
                font-size: 32px;
            }
            
            .modal-options {
                flex-direction: column;
                align-items: center;
            }
            
            .modal-option {
                width: 100%;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <a href="#" class="logo">
                    <img src="Quiz-master-logo.png" alt="Quiz Master Logo">
                    <span>Quiz Master</span>
                </a>
                <ul class="nav-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#testimonials">Success Stories</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
                <button class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <?php
            include 'classes.php';
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
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Ace Your GCE Exams with Confidence</h1>
                    <p>Access thousands of past questions, personalized study plans, and expert tutors. The ultimate exam preparation app for students.</p>
                    <div>
                        <button class="btn btn-primary" id="join-now-btn">Join Now</button>
                        <!-- <a href="#features" class="btn btn-secondary">Learn More</a> -->
                    </div>
                </div>
                <div class="hero-image">
                    <!-- <img src="/api/placeholder/500/400" alt="Students using Quiz Master app"> -->
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose Quiz Master?</h2>
                <p>Everything you need to excel in your GCE exams</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>GCE Question Bank</h3>
                    <p>Access thousands of past GCE questions with instant corrections and explanations.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Performance Tracking</h3>
                    <p>Follow your progress with detailed analytics and personalized learning paths.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3>Expert Tutors</h3>
                    <p>Connect with verified tutors for one-on-one sessions on difficult topics.</p>
                </div>
               
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Online Bookstore</h3>
                    <p>Purchase textbooks and exam materials directly through our app.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3>Teacher Dashboard</h3>
                    <p>Teachers can track student performance and provide targeted support.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Community Support</h3>
                    <p>Join a community of learners and share tips, resources, and encouragement.</p>                
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>AI assistant</h3>
                    <p>Get helped by AI to asnwer any question, and get into agent mode for futher assistance.</p>                
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map"></i>
                    </div>
                    <h3>Exam Preps</h3>
                    <p>Get a complete plan and quizzes to help you prepare for youe exams on time.</p>                
                </div>
                
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works">
        <div class="container">
            <div class="section-title">
                <h2>How Quiz Master Works</h2>
                <p>Start your journey to GCE success in just 3 simple steps</p>
            </div>
            <div class="steps">
                <div class="step">
                    <h3>Sign Up</h3>
                    <p>Create your account as a student or teacher and set up your profile.</p>
                </div>
                <div class="step">
                    <h3>Take Your 1st Quiz</h3>
                    <p>Complete your 1st test to identify your strengths and weaknesses.</p>
                </div>
                <div class="step">
                    <h3>Start Learning</h3>
                    <p>Follow your personalized study plan and track your progress.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Success Stories</h2>
                <p>Join students who have achieved their academic goals with Quiz Master</p>
            </div>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <p>"Quiz Master helped me improve my grades from C's to A's in just three months. The practice questions were exactly what I needed!"</p>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="/api/placeholder/50/50" alt="Student">
                        </div>
                        <div>
                            <h4>Marie N.</h4>
                            <p>GCE A-Level Student, Douala</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"As a teacher, I've seen remarkable improvement in my students' performance. The teacher dashboard helps me identify struggling students quickly."</p>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="/api/placeholder/50/50" alt="Teacher">
                        </div>
                        <div>
                            <h4>Mr. Emmanuel O.</h4>
                            <p>Physics Teacher, Yaoundé</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"The study groups feature allowed me to connect with other ambitious students. I passed my GCE with flying colors thanks to Quiz Master!"</p>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="/api/placeholder/50/50" alt="Student">
                        </div>
                        <div>
                            <h4>John K.</h4>
                            <p>GCE O-Level Graduate, Buea</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
            </div>
            <div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How up-to-date are the practice questions?</h3>
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Our question bank is updated regularly with the latest GCE exam patterns and questions. We add new questions every month based on curriculum changes.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Can I access Quiz Master offline?</h3>
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, premium users can download questions for offline practice. Your progress will sync when you reconnect to the internet.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How are the tutors verified?</h3>
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>All tutors undergo a rigorous verification process including academic credential checks, teaching experience verification, and background checks.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is Quiz Master free to use?</h3>
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Quiz Master offers a freemium model. Basic features are free, while premium features require a subscription. Schools can contact us for special rates.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Quiz Master. All rights reserved.</p>
        </div>
    </footer>

    <!-- Modal -->
    <div class="modal" id="signup-modal">
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <h2 class="modal-title">Join Quiz Master</h2>
            <p>Select how you want to continue</p>
            <div class="modal-options">
                <div class="modal-option" id="student-option">
                    <i class="fas fa-user-graduate"></i>
                    <h3>I'm a Student</h3>
                    <p>Prepare for your GCE exams</p>
                </div>
                <div class="modal-option" id="teacher-option">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>I'm a Teacher</h3>
                    <p>Help your students succeed</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('signup-modal');
        const joinBtn = document.getElementById('join-now-btn');
        const closeBtn = document.querySelector('.close-modal');
        const studentOption = document.getElementById('student-option');
        const teacherOption = document.getElementById('teacher-option');
        
        joinBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
        
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
        
        studentOption.addEventListener('click', () => {
            window.location.href = 'stud_signin.php';
        });
        
        teacherOption.addEventListener('click', () => {
            window.location.href = 'teachers/teacher_signin.php';
        });
        
        window.addEventListener('click', (e) => {
            if (e.target == modal) {
                modal.style.display = 'none';
            }
        });
        
        // FAQ functionality
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                // Close all FAQ items
                faqItems.forEach(faq => {
                    faq.classList.remove('active');
                    const icon = faq.querySelector('.faq-question i');
                    icon.className = 'fas fa-plus';
                });
                
                // If the clicked item wasn't active, open it
                if (!isActive) {
                    item.classList.add('active');
                    const icon = item.querySelector('.faq-question i');
                    icon.className = 'fas fa-minus';
                }
            });
        });
        
        // Mobile menu toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinks = document.querySelector('.nav-links');
        
        menuToggle.addEventListener('click', () => {
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        });
    </script>
</body>
</html>
