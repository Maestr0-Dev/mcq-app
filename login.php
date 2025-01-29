<? 
include 'classes.php';
$password="";
$email="";
$username="";
$num="";
$err=false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email=$_POST['email'];
    $num=$_POST['number'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $data=[$email,$num,$username,$password];
    $table="forlearners";
    $db= new DB();
    $result=$db-> login($table, $data);
    echo $result;

    if($err == false){
        $data=[$email,$num,$username,$password];
    $table="learners";
    $db= new DB();
    $result=$db-> login($table, $data);

        if(count($result) > 0){
            $_SESSION['id'] = $result[0]['id'];
            $_SESSION['username'] = $result[0]['name'];
            $_SESSION['email'] = $result[0]['email'];
            $_SESSION['password'] = $result[0]['password'];
            $_SESSION['num'] = $result[0]['number'];


            echo '<script>window.location.href = "./?p=quiz" </script>';
        }else{
            $result = "Invalid username or password";
        }
    }
}
?>
<html>
    <head>

    </head>
    <body>
        <h1>login</h1>
        <form action="" method="post">
        <input type="email" name="email" placeholder="E-mail">
        <input type="number" name="number">
        <input type="text" name="username" >
        <input type="password" name="password">

<button type="submit">Login</button>
        </form>
    </body>
</html>