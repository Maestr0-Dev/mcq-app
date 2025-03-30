<?php
//Ends the session and brings back the user to the menu
session_start();
session_destroy();
header("location:home.php");

?>