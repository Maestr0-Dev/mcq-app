<?php
session_start();
if (isset($_POST['update'])) {
    $_SESSION['SCR']++; // Increment the session variable SCR

    header("location:quiz.php");
} elseif (isset($_POST['correct']) && $_POST['correct'] == 'true') {
    $_SESSION['SCR']++; // Increment the session variable SCR

    // No redirection for correct answer request
}


?>
