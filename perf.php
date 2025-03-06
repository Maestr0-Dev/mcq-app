<?php
session_start();
if (isset($_SESSION['id'])) {
$db= new DB();
$table="stud_answered";
$data=[$_SESSION['id']];
$perform=$db-> getPerf($table, $data);
foreach($perform as $p){
?>

<?php
}}
?>