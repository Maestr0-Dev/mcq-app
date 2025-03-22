<?php
session_start();
include 'classes.php';

$_SESSION['logged_in'] = false;
$password = "";
$username = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $data = [$username, $password];
    $db = new DB();
    $result = $db->login($data);

    if (count($result) > 0) {
        $_SESSION['logged_in'] = true;
        $_SESSION['id'] = $result[0]['stud_id'];
        $_SESSION['uname'] = $result[0]['stud_name'];
        $_SESSION['email'] = $result[0]['email'];
        $_SESSION['number'] = $result[0]['number'];
        $_SESSION['level']=$result[0]['level'];

        $message = "<p style='color:green;'>Logged in successfully</p>";
        header("location:menu.php");
    } else {
        $message = "<p style='color:red;'>Invalid username or password.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-container button {
            background: linear-gradient(to left, purple, blue);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin: 10px 0;
        }
        .login-container a {
            display: block;
            margin-top: 10px;
            color: #666;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .login-container p {
            margin: 10px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="login-container">
    <a href="menu.php" class="back-button">
        <i class="fa fa-arrow-left"></i> 
        </a>
        <h1>Login</h1>
        <form action="" method="post">
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Login</button>
            <a href="stud_signin.php">Create an account</a>
            <p><?=$message ?></p>
        </form>
    </div>
</body>
</html>
