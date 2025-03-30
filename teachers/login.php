<?php 
include 'C:\xampp\htdocs\mcq-app\classes.php';


$email = ""; 
$password = ""; 
$error = ""; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = new DB();
    $teacher = $db->teacherLogin([$email, $password]);

    if (!empty($teacher)) {
        // Start session and redirect to dashboard
        session_start();
        $_SESSION['teacher_id'] = $teacher['teacher_id'];
        $_SESSION['full_name'] = $teacher['full_name'];
        $_SESSION['email'] = $teacher['email'];
        $_SESSION['phone'] = $teacher['phone'];
        $_SESSION['subjects'] = $teacher['subjects'];
        $_SESSION['profile_picture'] = $teacher['profile_picture'];
        header("location:teacher_dashboard.php");
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link type="text/css" rel="stylesheet" href="myCss/signin.css">
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
            padding: 30px;
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
            color: red;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Teacher Login</h1>
        <form action="" method="post">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit">Login</button>
            <a href="teacher_signin.php">Don't have an account? Sign up</a>
            <p><?= $error ?></p>
        </form>
    </div>
</body>
</html>