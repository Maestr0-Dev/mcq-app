<?php 
include 'classes.php';

$name = ""; $pw= ""; $email = ""; $phone = "";
$error = false; $result = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['username'];
    $pw = $_POST['password'];
    $email =$_POST['email'];
    $phone = $_POST['phone'];
    $date= date("Y-m-d h:i:s");
    $table="students";

    $data = [$name,$email, $phone,$pw,  $date];        
    $db = new DB();
    $result = $db->newUser($table, $data);
    
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        .signup-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .signup-container h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .signup-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .signup-container button {
            background: linear-gradient(to left, purple, blue);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin: 10px 0;
        }
        .signup-container a {
            display: block;
            margin-top: 10px;
            color: #666;
            text-decoration: none;
        }
        .signup-container a:hover {
            text-decoration: underline;
        }
        .signup-container p {
            margin: 10px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Sign Up</h1>
        <form action="" method="post">
            <input type="text" name="username" required placeholder="Username">
            <input type="password" name="password" required placeholder="Password">
            <input type="email" name="email" required placeholder="Email">
            <input type="number" name="phone" required placeholder="Phone">
            <button type="submit">Sign up</button>
            <a href="login.php">Already have an account? Login</a>
            <p><span style="color:green"><?= $result ?></span></p>
        </form>
    </div>
</body>
</html>