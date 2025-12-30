<?php
session_start();
include '../classes.php';

if (!isset($_SESSION['admin_logged_in']) || !in_array($_SESSION['admin_level'], [1, 2, 3])) {
    header("location:" . BASE_URL . "admin/login.php");
    exit();
}
$db = new DB();

if (isset($_GET['action']) && $_GET['action'] === 'delete_quiz' && isset($_GET['table']) && isset($_GET['year']) && isset($_GET['subject'])) {
    $db->deleteQuiz($_GET['table'], $_GET['year'], $_GET['subject']);
    $_SESSION['success_message'] = 'Quiz deleted successfully!';
    header("Location: view_questions.php");
    exit();
}

$archives = $db->getArchives();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Questions</title>
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .quiz-group {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .quiz-group h3 {
            margin-top: 0;
        }
        .quiz-item {
            display: block;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            margin-top: 5px;
            text-decoration: none;
            color: #333;
            border-radius: 3px;
        }
        .quiz-item:hover {
            background-color: #f0f0f0;
        }
        .delete-icon {
            color: red;
            cursor: pointer;
            float: right;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <?php include 'admin_nav.php'; ?>
  
    <div class="container">
        <div class="header">
            <h1 style="color:white;">View and Edit Questions</h1>
            <p>Select a quiz to view and edit its questions.</p>
        </div>

        <?php
        $grouped_archives = [];
        foreach ($archives as $archive) {
            $grouped_archives[$archive['table_name']][] = $archive;
        }

        foreach ($grouped_archives as $table_name => $quizzes) {
            echo '<div class="quiz-group">';
            echo '<h3>' . htmlspecialchars(str_replace('_', ' ', $table_name)) . '</h3>';
            foreach ($quizzes as $quiz) {
                $delete_link = 'view_questions.php?action=delete_quiz&table=' . urlencode($quiz['table_name']) . '&year=' . urlencode($quiz['year']) . '&subject=' . urlencode($quiz['subject']);
                echo '<div class="quiz-item">';
                echo '<a href="edit_questions.php?table=' . urlencode($quiz['table_name']) . '&year=' . urlencode($quiz['year']) . '&subject=' . urlencode($quiz['subject']) . '" style="text-decoration: none; color: #333;">';
                echo htmlspecialchars($quiz['title']);
                echo ' (' . $quiz['question_count'] . ' questions)';
                echo '</a>';
                echo '<a href="' . $delete_link . '" class="delete-icon" onclick="return confirm(\'Are you sure you want to delete this entire quiz?\');">🗑️</a>';
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
        <a href="CMS.php">Back</a>
    </div>
</body>
</html>
