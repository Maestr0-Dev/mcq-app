<?php
session_start();
$name = "";
$num = "";
$email = "";
$level = "";
if (isset($_SESSION['id'] ) ){
    $name = $_SESSION['uname'];
    $num = $_SESSION['number'];
    $email = $_SESSION['email'];
    $level = $_SESSION['level'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="myCss/us_profile.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .profile-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .profile-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .profile-container p {
            margin: 10px 0;
            color: #666;
        }
        .profile-container a {
            display: inline-block;
            margin-top: 10px;
            color: white;
            background: linear-gradient(to left, purple, blue);
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .profile-container a:hover {
            background: linear-gradient(to right, purple, blue);
        }
    </style>
</head>
<body>
<a href="home.php" class="back-button">
        <i class="fa fa-arrow-left"></i> 
        </a>
    <div class="profile-container">
        <h1>Hi,  <?=$name?></h1>
        <p><strong>Name:</strong> <?=$name?></p>
        <p><strong>Number:</strong> <?=$num?></p>
        <p><strong>Email:</strong> <?=$email?></p>
        <p><strong>Status:</strong> <?=$level?>-level</p>
        <p><strong>Password:</strong>*******</p>
        <a href="modif_learner_info.php">Modify</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
<?php
} else {
    header("location:login.php");
}
?>