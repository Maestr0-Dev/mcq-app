<?php
session_start();
include 'src/classes.php';

// Assuming a Quiz class exists in classes.php to handle quiz-related operations
$quizManager = new Quiz();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quiz'])) {
    $title = $_POST['title'];
    $creator = $_SESSION['username']; // Assuming the username is stored in the session
    $questions = $_POST['questions']; // This should be an array of questions

    // Add the quiz to the database
    $quizManager->addQuiz($title, $creator, $questions);
}

$quizzes = $quizManager->getAllQuizzes(); // Fetch all quizzes from the database

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
    <title>User Profile</title>
</head>
<body>

<div class="container">
    <h1>User Profile</h1>
    <h2>Your Quizzes</h2>
    <ul>
        <?php foreach ($quizzes as $quiz): ?>
            <li>
                <strong><?php echo htmlspecialchars($quiz['title']); ?></strong> by <?php echo htmlspecialchars($quiz['creator']); ?>
                <a href="quiz.php?id=<?php echo $quiz['id']; ?>">Take Quiz</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Add a New Quiz</h2>
    <form method="POST" action="">
        <input type="text" name="title" placeholder="Quiz Title" required>
        <textarea name="questions" placeholder="Enter your questions here (JSON format)" required></textarea>
        <button type="submit" name="add_quiz">Add Quiz</button>
    </form>
</div>

</body>
</html>