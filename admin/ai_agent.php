<?php
session_start();
include '../classes.php';

if (!isset($_SESSION['admin_logged_in'])) {
    // header("location:admin_login.php");
    // exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Agent</title>
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
</head>
<body>
    <?php include 'admin_nav.php'; ?>

    <div class="container">
        <h1>AI Agent</h1>
        <p>This page is under construction.</p>
    </div>
</body>
</html>
