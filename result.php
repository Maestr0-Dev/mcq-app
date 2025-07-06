<?php
session_start();
include 'classes.php';

$score=$_SESSION['SCR'];
$_SESSION['started']=false;
$year = $_SESSION['year'];
$subj = $_SESSION['subj'];
$table = $_SESSION['exam'];
$num=$_SESSION['num'];
$lvl=$_SESSION['level'];
$stud_id=$_SESSION['id'];
    $date=date("Y:M:D");


if ( !isset($table)) {
    echo "Error: Missing session variables.";
    exit;

}
    // $dur=$_SESSION['duration'];
    $dur=0;
    $date=date('Y-m-d h:i:s');

$data = [$year, $subj];
$ans_data=[$stud_id,$table,$lvl,$subj,$year,$score,$dur,$date];


$db = new DB();
$done= $db->savePerf($ans_data);

$dB = new DB();
$result = $dB->Get($table,$data);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" href="css/style.css">
        <link type="text/css" rel="stylesheet" href="myCss/result-style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="script.js"></script>
<title>Results</title>


</style>
</head>
<body>
    <section class="container">
    <?php
    if($score>=($num/2)){
        ?>
        <div class="scrCmnt" style="background-color:green;">
        <p>Congrats. You passed the quiz with a total of <br><?=$score.'/'.$num?></p>
        </div>
        <?php
    }else{
        ?>
        <div class="scrCmnt" style="background-color:red;">
        <p class="score-display">Sorry. You failed the quiz with a total of <br><?=$score?>/<?=$num;?></p>
       
        </div>
        <?php
    }
    ?>
<div class="review-section" style="margin-left:20px;">
<p>Tap on <i  style="color:green;"class="fa fa-brain"></i> for explanations.</p>

    <?php
    $num=1;
    
foreach($result as $key => $q) {
?>
<br>
<div class="question-cont">  <p><?=$q['instructions']?></p>
  <?php if(isset($_SESSION['id'])){
    ?>
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
    <p><b>Answer:</b> <span style="color:green;"><?=$q['ans']?></span>  <i  style="color:green;"class="fa fa-brain"></i></p>
    </div>

<?php
$num++;
}?>
</div>

    </section>
    <a href="home.php">
    <button > 
    <i class="fa fa-arrow-home"></i>
    Home
    </button>
</a>
<a href="quest_selection.php">
    <button >
    <i class="fa fa-arrow-chart-line"></i>
    Take another Quiz
    </button>
</a>
<button>Save </button>

</body>

</html>
<?php
       $_SESSION['SCR']=0;
?>