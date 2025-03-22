<?php 
session_start();
include 'classes.php';

$date= date("Y-m-d h:i:s");
$data=[$_SESSION['id'],$_SESSION['com_id'],$date];
$db= new DB();
$result=$db->joinCommunity($data);

header("location:communities.php");
?>