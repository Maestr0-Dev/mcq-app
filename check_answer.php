<?php
session_start();

$user_ans = $_POST['user_ans'];
$real_ans = $_POST['real_ans'];
$num = $_POST['num'];


$previousAnswer = $_POST['previousAnswer'];

if ($previousAnswer === $real_ans && $user_ans !== $real_ans) {
    $_SESSION['SCR'] -= 1;
} elseif ($previousAnswer !== $real_ans && $user_ans === $real_ans) {
    $_SESSION['SCR'] += 1;
}

$_SESSION['user_answers'][$num] = $user_ans;


header("Location:quiz.php");
?>