<?php
session_start();
        $_SESSION['SCR']=$_SESSION['SCR']+1; 
        header("Location:quiz.php");
?>
