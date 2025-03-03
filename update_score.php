<?php
session_start();
if(isset($_POST['update'])) {
    $_SESSION['SCR']=$_SESSION['SCR']+1;
    header("location:quiz.php");
}
?>