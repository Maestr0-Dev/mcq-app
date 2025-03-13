<?php
session_start();
include 'classes.php';
$score=$_SESSION['SCR'];
$_SESSION['started']=false;
$year = $_SESSION['year'];
$subj = $_SESSION['subj'];
$table = $_SESSION['exam'];
$num=$_SESSION['num'];
$stud_id=$_SESSION['id'];
if ( !isset($table)) {
    echo "Error: Missing session variables.";
    exit;

}
    // $dur=$_SESSION['duration'];
$dur=0;
    $date=date('Y-m-d H:i:s');

$data = [$year, $subj];
$ans_data=[$stud_id,$table,$subj,$year,$score,$dur,$date];
$db = new DB();
$result = $db->Get($table,$data);
$done=$db->savePerf($ans_data);
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
    button {
            background:linear-gradient(to left,purple,blue);
            padding: 0.8rem 1.5rem;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin:10px;
        }
        img{
            height:150px;
            width:auto;
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
        <!-- <p>Total duration: <?=$_SESSION['duration'];?>minutes</p> -->
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
  <?php if(isset($_SESSION['id'])){
    ?><p><?=$q['instructions']?></p>
    <?php
    } else{
        echo "no ID";}?>
    <p style="font-weight:bolder;"><?=$num . '. ' . $q['question']?></p>
    <?php
        if (!empty($q['img'])) {
            $path = "diagrams/" . $q['img'];
?>
            <img src="<?=$path?>">
<?php
        }
?>
    <p><b>Answer:</b> <span style="color:green;"><?=$q['ans']?></span></p>

<?php
$num++;
}?>
</div>
<a href="menu.php">
    <button > Back to menu</button>
</a>
<a href="perf.php">
    <button >View performances</button>
</a>
    </section>
</body>

</html>
<?php
       $_SESSION['SCR']=0;

?>