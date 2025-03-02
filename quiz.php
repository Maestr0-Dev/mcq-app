<?php session_start();
include 'classes.php';
if($_SESSION['started']==true){
$year = $_SESSION['year'];
$subj = $_SESSION['subj'];
$table = $_SESSION['exam'];
if ( !isset($table)) {
    echo "Error: Missing session variables.";
    header("location:quest_selection.php");

}


$data = [$year, $subj];

$db = new DB();
$result = $db->Get($table,$data);
?>
<head>
    <title>Quiz-Master</title>
<style>
.container {
      max-width: 1000px;
      margin: 5px auto;
      box-shadow: 10px 1px 160px rgba(0.4,0,0,0.4);
      border-radius:10px 10px 10px 10px;
      font-family:helvetica;
    }
    .quest_info{
        font-family:helvetica;
        height:auto;
        background:linear-gradient(to left,purple,blue);
        color:white;
        padding: 30px;
        font-weight:bolder;
    }
    .holder{
        margin:10px auto;
    }

    button{
        background:linear-gradient(to left,purple,blue);
        padding: 0.8rem 1.5rem;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin:10px 10px 10px 10px;
    }
</style>
</head>
<div class="container">
<?php
if (empty($result)) {
    echo "No questions found for the selected criteria.";
    exit;
}
$num = $_SESSION['num']=0;
$t=$result[0];
?>
<div class="quest_info">
    <p class="yr"> </p>
    <p> <?=$t['title']?> <?=$t['year']?></p>
    <p><?=$t['subject']?></p>
</div>

<div style="margin-left:20px;">
<p><?=$t['instructions']?></p>

<?php
$_SESSION['SCR']=4;
foreach($result as $key => $q) {
    $real_ans=$q['ans'];
    $num++;
    $_SESSION['num']++;
    ?>
    <p style="font-weight:bolder;"><?=$num . '. ' . $q['question']?></p>
    <?php
    if(!empty($q['img'])){
    $data = base64_decode($q['img']);
    $file = "diagrams/" . $q['A'] . '.'.$q['img_type'];
       $success = file_put_contents($file, $data);
?>
<img src="1.jpg">
<?php
}?>
    <p>A<input type="radio" name="us_ans<?=$num?>" value="<?=$q['A']?>" onclick="checkAns(<?=$q['A']?>,<?=$real_ans?>,<?=$num?>)" ><?=$q['A']?></p>
    <p>B<input type="radio" name="us_ans<?=$num?>" value="<?=$q['B']?>" onclick="checkAns(<?=$q['B']?>,<?=$real_ans?>,<?=$num?>)"> <?=$q['B']?></p>
    <p>C<input type="radio" name="us_ans<?=$num?>" value="<?=$q['C']?>" onclick="checkAns(<?=$q['C']?>,<?=$real_ans?>,<?=$num?>)"> <?=$q['C']?></p>
    <p>D<input type="radio" name="us_ans<?=$num?>" value="<?=$q['D']?>" onclick="checkAns(<?=$q['D']?>,<?=$real_ans?>,<?=$num?>)"> <?=$q['D']?></p>
   

<script>

//    function checkAns(ans,real_ans,num) {
//        $_SESSION['SCR']=3;
//      }
// //   function checkAns(user_ans,real_ans,quest){
//     if(user_ans == real_ans){
//         $.ajax({
//             type: 'POST',
//             url: 'update_score.php',
//             data: {update: true},
//             success: function(data) {
//                 console.log(data);
//             }
//         });
//     }
// }
//for the timer
</script>
<?php
}?>
<a href="result.php"><button>Submit</button></a>

</div>
<?php
$time=90*60;
$endTime = time() + $time;
?>
<script>
const endTime = <?= $endTime; ?>;
setInterval(function() {
    var currentTime = new Date().getTime();
    if (time() >= endTime) {
        window.location.href = 'result.php';
    }
    var duration=currentTime - endTime;
}, 1000); // check every second

<?php
// $_SESSION['start_time'] = time();
//  = currentTime - $_SESSION['start_time'];?>
// 
// console.log(currentTime);
var <?=$_SESSION['duration']?> =duration;

</script>
</div>
<?php
}else{
    header("location:quest_selection.php");

}?>
