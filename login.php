<?php
session_start();
include 'classes.php';
$_SESSION['is_logged_in']="";
$password = "";
$email = "";
// $username = "";
// $num = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    // $num = $_POST['number'];
    // $username = $_POST['username'];
    $password = $_POST['password'] ;
    $data = [$email, $password];
    
    $db = new DB();
    $result = $db->login('learners', $data);
    echo $result;

    if (count($result) > 0) {
        $_SESSION['is_logged_in']=true;
        $_SESSION['id'] = $result[0]['id'];
        $_SESSION['uname'] = $result[0]['name'];
        // $_SESSION['email'] = $result[0]['email'];
        $_SESSION['phone'] = $result[0]['phone'];
        $_SESSION['usname'] = 'mike';
        
        
        header("location:landing.php");
        exit;
    } else {
        $_SESSION['is_logged_in']=false;
        $message = "Invalid username or password.";
     }
    }
?>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if ($message): ?>
        <p style="color:red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form action="landing.php" method="post">
        <input type="email" name="email" placeholder="E-mail" required>
        <!-- <input type="number" name="number" required>
        <input type="text" name="username" required> -->
        <input type="password" name="password" required placeholder="password">
        <button type="submit">Login</button>
        <a href="sigin.php">create to an account</a>
    </form>
</body>
</html>
