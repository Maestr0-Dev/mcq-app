<?php
session_start();
include 'classes.php';
$score=$_SESSION['SCR'];
// $_SESSION['started']=false;
$year = $_SESSION['year'];
$subj = $_SESSION['subj'];
$table = $_SESSION['exam'];
$time=$_SESSION['time'];
$num=$_SESSION['num'];
if ( !isset($table)) {
    echo "Error: Missing session variables.";
    exit;

}
$data = [$year, $subj];

$db = new DB();
$result = $db->Get($table,$data);

?>
<!DOCTYPE html>
<html>
<head>

<style>
    \*{box-sizing:border-box;margin:0;padding:0;}
    .container {
      max-width: 1000px;
      margin: 5px auto;
      box-shadow: 10px 1px 160px rgba(0.4,0,0,0.4);
      border-radius:10px 10px 10px 10px;
      font-family:helvetica;
    }
    .scrCmnt{
        font-family:helvetica;
        height:auto;
        color:white;
        padding: 30px;
        font-weight:bolder;
    }
</style>
</head>
<body>
    <section class="container">
    <?php
    if($score>=($num/2)){
        ?>
        <div class="scrCmnt" style="background-color:green;">
        <p>Congrats. You passed the quiz with a total of <br><?=$score.'/'.$num?></p>
        <p>Total duration: <?=$_SESSION['duration'];?>minutes</p>
        </div>
        <?php
    }else{
        ?>
        <div class="scrCmnt" style="background-color:red;">
        <p>Sorry. You failed the quiz with a total of <br><?=$score?>/<?=$num;?></p>
        <p>Total duration:</p>
        </div>
        <?php
    }
    ?>
<div style="margin-left:20px;">
    <?php
    $num=1;
foreach($result as $key => $q) {
?><br>
  <p><?=$q['instructions']?></p>
    <p style="font-weight:bolder;"><?=$num . '. ' . $q['question']?></p>
    <p><b>Answer:</b> <span style="color:green;"><?=$q['ans']?></span></p>

<?php
$num++;
}?>
</div>
    </section>
</body>

</html>