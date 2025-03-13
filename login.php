<?php
session_start();

include 'classes.php';
$password = "";
$username = "";
$message = "";
$_SESSION['logged_in']=false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'] ;

    
    $data = [$username, $password];
    $db = new DB();
    $result = $db->login($data);

    if (count($result) > 0) {
        $_SESSION['logged_in']=true;
        $_SESSION['id'] = $result[0]['stud_id'];
        $_SESSION['uname'] = $result[0]['stud_name'];
        $_SESSION['email']=$result[0]['email'];
        $_SESSION['number'] = $result[0]['number'];
        
        $message = "<p style='color:green;'>Loged in successfuly</p>";
        header("location:menu.php");

    } else {
        $message = "<p style='color:red;'>Invalid username or password.</p>";
     }
    }
?>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
   
    
    <form action=" " method="post">
        <input type="text" name="username" required> 
        <input type="password" name="password" required placeholder="password">
        <button type="submit">Login</button>
        <a href="signin.php">create to an account</a>
        <p><?=$message ?></p>


    </form>
</body>
</html>
