<?php
session_start();

        $_SESSION['SCR']++; 
        header("Location:quiz.php");

?>
