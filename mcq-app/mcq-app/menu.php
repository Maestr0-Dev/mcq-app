<?php
session_start();
include 'src/classes.php';

// Assuming a Quiz class exists in classes.php for handling quizzes
$quizManager = new Quiz();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quiz'])) {
    $title = $_POST['title'];
    $creator = $_SESSION['username']; // Assuming username is stored in session
    $questions = $_POST['questions']; // Assuming questions are submitted as an array

    // Add the quiz to the database
    $quizManager->addQuiz($title, $creator, $questions);
}

$quizzes = $quizManager->getAllQuizzes(); // Fetch all quizzes

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
    <title>Question Bank</title>
</head>
<body>

<div class="navbar" id="navbar">
    <a href="home.php"><i class="fa fa-home"></i></a>
    <a href="user_profile.php">My Profile <i class="fa fa-user"></i></a>
    <a href="quest_selection.php"> Official MCQ <i class="fa fa-pen-to-square"></i> </a>
    <a href="discover.php"> Discover Tutors <i class="fa fa-chalkboard-user"></i> </a>
    <a href="perf.php"> View Performances <i class="fa fa-chart-line"></i> </a>
    <a href="chat.php"> Braze AI <i class="fa fa-robot"></i> </a>
    <a href="communities.php">Community <i class="fa fa-users"></i> </a>
    <a href="question_bank.php"> Question Bank <i class="fa fa-bank"></i> </a>
    <a href="learn.php"> learn <i class="fa fa-book"></i> </a>
    <a href="question_bnk.php"> Personal plans <i class="fa fa-map"></i> </a>
    <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
</div>

<div class="container">
    <h1>Question Bank</h1>

    <h2>Add a New Quiz</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Quiz Title" required>
        <textarea name="questions" placeholder="Enter questions separated by commas" required></textarea>
        <button type="submit" name="add_quiz">Add Quiz</button>
    </form>

    <h2>Available Quizzes</h2>
    <ul>
        <?php foreach ($quizzes as $quiz): ?>
            <li>
                <strong><?php echo htmlspecialchars($quiz['title']); ?></strong> by <?php echo htmlspecialchars($quiz['creator']); ?>
                <a href="take_quiz.php?id=<?php echo $quiz['id']; ?>">Take Quiz</a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>