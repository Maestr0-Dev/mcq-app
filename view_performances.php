<a href="./p?=takequiz"> Take a quiz</a>
<?php
if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_GET['p'])){
    if(isset($_GET['p']) && $_GET['p'] == 'login'){
        include 'signin.php';
    }else{
        include 'login.php';
    }
}else{
    include $_GET['p'].'.php';
} 
?>