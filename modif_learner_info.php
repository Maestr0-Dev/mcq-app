<?php
session_start();
include 'classes.php';
$name="";
$num="";
$email="";
$level="";
if($_SESSION['logged_in']==true){
    $name=$_SESSION['uname'];
    $num=$_SESSION['number'];
    $email=$_SESSION['email'];
    $level=$_SESSION['level'];


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name=$_POST['name'];
    $num=$_POST['num'];
    $email=$_POST['email'];
    $level=$_POST['level'];

    $data=[$name,$num,$email,$level,$_SESSION['id']];
    $db=new DB();
    $db->update_learner($data);
    $_SESSION['uname']=$name;
    $_SESSION['number']=$num;
    $_SESSION['email']=$email;
    $_SESSION['level']=$level;
    header("location:user_profile.php");
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify your informations</title>
</head>
<body>
    <a href="user_profile.php">Discard</a>
    <form action="" method="post">
        <input type="text" name="name" value="<?=$name?>">
        <input type="text" name="num" value="<?=$num?>">
        <input type="text" name="email" value="<?=$email?>">
        <select name="level" id="">
            <option value="O">O</option>
            <option value="A">A</option>
        </select>
        <button type="submit">Save</button>
    </form>
</body>
</html>
<?php
}else{
    header("location:login.php");
}