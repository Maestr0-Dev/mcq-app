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
<div>
<span style="color:green"><?= $result ?></span>
<form action="" method="post">
	<label>Username</label>
	<br>
	<input type="text" name="username">
	<br>
	<br>
	<label>Password</label>
	<br>
	<input type="password" name="password">
	<br>
	<br>
	<label>Email</label>
	<br>
	<input type="email" name="email">
	<br>
	<br>
	<label>Phone</label>
	<br>
	<input type="number" name="phone">
	<br>
	<br>
	<button type="submit">Register me</button>
</form>
</div>