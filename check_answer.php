<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_ans = $_POST['user_ans'];
    $real_ans = $_POST['real_ans'];

    if ($user_ans === $real_ans) {
        $_SESSION['SCR']++; // Increment the session variable SCR
    }

    echo $_SESSION['SCR']; // Return the updated score
    exit();
}
?>
