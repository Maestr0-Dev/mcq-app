<?php
session_start();
include 'classes.php';

$db = new DB();
$student_id = $_SESSION['id'] ?? null; // Assuming student ID is stored in the session

// Fetch all teachers
$all_teachers = $db->getAllTeachers();

// Fetch verified teachers
$verified_teachers = $db->getVerifiedTeachers();

// Fetch mentors for the current student
$mentors = $db->getStudentMentors($student_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Tutors</title>
    <link type="text/css" rel="stylesheet" href="myCss/discover.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
   
</head>
<body>
    <div class="container">
        <h1>Discover Tutors</h1>
        <div class="tabs">
            <button class="tab-btn active" data-target="all-teachers">All Teachers</button>
            <button class="tab-btn" data-target="verified-teachers">Verified Teachers</button>
            <button class="tab-btn" data-target="mentors">My Mentors</button>
        </div>

        <!-- All Teachers Section -->
        <div class="section active" id="all-teachers">
            <?php foreach ($all_teachers as $teacher): ?>
                <div class="teacher-card">
                    <img src="teachers/teach_profil_imgs/<?= $teacher['profile_picture'] ?>" alt="Profile Picture">
                    <div class="teacher-info">
                        <h3><?= $teacher['full_name'] ?></h3>
                        <p>Subjects: <?= $teacher['subjects'] ?></p>
                        <p>On Platform: <?= $teacher['time_on_platform'] ?> years</p>
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star" data-rating="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="teacher-actions">
    <?php
    // Check if the student has already sent a request to this teacher
    $is_request_sent = $db->isMentorRequestSent($student_id, $teacher['teacher_id']);
    ?>
    <button 
        class="mentor-btn" 
        data-teacher-id="<?= $teacher['teacher_id'] ?>" 
        <?= $is_request_sent ? 'disabled' : '' ?>>
        <?= $is_request_sent ? 'Request Sent' : 'Ask to Mentor' ?>
    </button>
</div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Verified Teachers Section -->
        <div class="section" id="verified-teachers">
            <?php foreach ($verified_teachers as $teacher): ?>
                <div class="teacher-card">
                    <img src="teachers/teach_profil_imgs/<?= $teacher['profile_picture'] ?>" alt="Profile Picture">
                    <div class="teacher-info">
                        <h3><?= $teacher['full_name'] ?> <i class="verified-icon">✔</i></h3>
                        <p>Subjects: <?= $teacher['subjects'] ?> </p>
                        <p>On Platform: <?= $teacher['time_on_platform'] ?> years</p>
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star" data-rating="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="teacher-actions">
                        <button class="mentor-btn" data-teacher-id="<?= $teacher['teacher_id'] ?>">Ask to Mentor</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- My Mentors Section -->
        <div class="section" id="mentors">
            <?php foreach ($mentors as $mentor): ?>
                <div class="teacher-card">
                    <img src="teachers/teach_profil_imgs/<?= $mentor['profile_picture'] ?>" alt="Profile Picture">
                    <div class="teacher-info">
                        <h3><?= $mentor['full_name'] ?></h3>
                        <p>Subjects: <?= $mentor['subjects'] ?></p>
                        <p>On Platform: <?= $mentor['time_on_platform'] ?> years</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="">
        document.addEventListener('DOMContentLoaded', () => {
    
   // Tab switching logic
const tabButtons = document.querySelectorAll('.tab-btn');
const sections = document.querySelectorAll('.section');

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active class from all buttons and sections
        tabButtons.forEach(btn => btn.classList.remove('active'));
        sections.forEach(section => section.classList.remove('active'));

        // Add active class to clicked button and corresponding section
        button.classList.add('active');
        const targetSection = document.getElementById(button.dataset.target);
        if (targetSection) {
            targetSection.classList.add('active');
        }
    });
});
    
    // Parse subjects into badges
    document.querySelectorAll('.teacher-info p:first-of-type').forEach(subjectsParagraph => {
      const text = subjectsParagraph.textContent;
      if (text.startsWith('Subjects:')) {
        const subjects = text.replace('Subjects:', '').trim().split(',');
        const wrapper = document.createElement('div');
        wrapper.className = 'subjects-wrapper';
        
        subjects.forEach(subject => {
          const badge = document.createElement('span');
          badge.className = 'subject-badge';
          badge.textContent = subject.trim();
          wrapper.appendChild(badge);
        });
        
        subjectsParagraph.innerHTML = 'Subjects: ';
        subjectsParagraph.appendChild(wrapper);
      }
    });
  });
  // Tab switching logic
const tabButtons = document.querySelectorAll('.tab-btn');
const sections = document.querySelectorAll('.section');

tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active class from all buttons and sections
        tabButtons.forEach(btn => btn.classList.remove('active'));
        sections.forEach(section => section.classList.remove('active'));
        
        // Add active class to clicked button and corresponding section
        button.classList.add('active');
        document.getElementById(button.dataset.target).classList.add('active');
    });
});

// Mentor button logic
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('mentor-btn')) {
        const teacherId = e.target.dataset.teacherId;
        const button = e.target;

        // Send AJAX request to request_mentor.php
        fetch('request_mentor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ teacher_id: teacherId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Change button text to "Request Sent"
                button.textContent = 'Request Sent';
                button.disabled = true;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
});
    </script>
</body>
</html>