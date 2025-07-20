<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['quiz_result'])) {
    header('Location: student_content.php');
    exit;
}

$result = $_SESSION['quiz_result'];
unset($_SESSION['quiz_result']); // Clear the result from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Quiz Result</h1>
        <h2><?= htmlspecialchars($result['title']) ?></h2>
        <p>You scored <?= htmlspecialchars($result['score']) ?> out of <?= htmlspecialchars($result['total']) ?>.</p>
        <a href="student_content.php">Back to My Content</a>
    </div>
</body>
</html>
