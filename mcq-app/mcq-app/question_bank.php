<?php
session_start();
include 'src/classes.php';

// Assuming a Quiz class exists in classes.php for handling quiz data
$quizClass = new Quiz();
$quizzes = $quizClass->getAllQuizzes(); // Fetch all quizzes from the database

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_quiz'])) {
    // Handle quiz submission
    $title = $_POST['title'];
    $creator = $_SESSION['username']; // Assuming username is stored in session
    $questions = $_POST['questions']; // Assuming questions are submitted in a specific format

    $quizClass->addQuiz($title, $creator, $questions);
    header("Location: question_bank.php"); // Redirect to the same page to see the new quiz
    exit();
}
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

<div class="container">
    <h1>Question Bank</h1>

    <h2>Add a New Quiz</h2>
    <form method="POST" action="">
        <input type="text" name="title" placeholder="Quiz Title" required>
        <textarea name="questions" placeholder="Enter questions here..." required></textarea>
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