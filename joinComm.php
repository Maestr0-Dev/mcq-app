<?php 
session_start();
include 'classes.php';
$com_id=$_POST['com_id'];
$id=$_POST['id'];
$date= date("Y-m-d h:i:s");
$data=[$id,$com_id,$date];
echo "<script>console.log(".$com_id.")</script>";
$db= new DB();
$result=$db->joinCommunity($data);

header("location:communities.php");
?>