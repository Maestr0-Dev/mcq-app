<?php session_start();
include 'classes.php';
if($_SESSION['started']==true){
    $year = $_SESSION['year'];
    $subj = $_SESSION['subj'];
    $table = $_SESSION['exam'];
    if (!isset($table)) {
        echo "Error: Missing session variables.";
        header("location:quest_selection.php");
        exit;
    }

    $data = [$year, $subj];

    $db = new DB();
    $result = $db->Get($table, $data);
    count;
?>
<head>
    <title>Quiz-Master</title>
    <style>
        .container {
            max-width: 1000px;
            margin: 5px auto;
            box-shadow: 10px 1px 160px rgba(0.4,0,0,0.4);
            border-radius:10px;
            font-family:helvetica;
        }
        .quest_info {
            font-family:helvetica;
            height:auto;
            background:linear-gradient(to left,purple,blue);
            color:white;
            padding: 30px;
            font-weight:bolder;
            text-align:center;
        }
        .holder {
            margin:10px auto;
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
<div class="container">
<?php
    if (empty($result)) {
        echo "No questions found for the selected criteria.";
        exit;
    }
    $num = $_SESSION['num'] = 0;
    $t = $result[0];
?>
<div class="quest_info">
    <p class="yr"> </p>
    <p> <?=$t['title']?> <?=$t['year']?></p>
    <p><?=$t['subject']?></p>
</div>

<div style="margin-left:20px;">
    <p><?=$t['instructions']?></p>

<?php
    $_SESSION['SCR'] = 0; // Remove resetting SCR to 0

    foreach($result as $key => $q) {
       $real_ans = $q['ans'];
        $num++;
        $_SESSION['num']++;
?>
<form action="" method="post">
        <p style="font-weight:bolder;"><?=$num . '. ' . $q['question']?></p>
<?php
        if (!empty($q['img'])) {
            $path = "diagrams/" . $q['img'];
?>
            <img src="<?=$path?>">
<?php
        }
?>
        <p>A<input type="radio" name="us_ans" value="<?=$q['A']?>" onclick="checkAns(<?=$q['A']?>,<?=$real_ans?>,<?=$num?>)" ><?=$q['A']?></p>
        <p>B<input type="radio" name="us_ans" value="<?=$q['B']?>" onclick="checkAns(<?=$q['B']?>,<?=$real_ans?>,<?=$num?>)"> <?=$q['B']?></p>
        <p>C<input type="radio" name="us_ans" value="<?=$q['C']?>" onclick="checkAns(<?=$q['C']?>,<?=$real_ans?>,<?=$num?>)"> <?=$q['C']?></p>
        <p>D<input type="radio" name="us_ans" value="<?=$q['D']?>" onclick="checkAns(<?=$q['D']?>,<?=$real_ans?>,<?=$num?>)"> <?=$q['D']?></p>

        <script>
        <?php

    $time = 90 * 60;
    $endTime = time() + $time;
?>
    const endTime = <?= $endTime; ?>;
    setInterval(function() {
        var currentTime = new Date().getTime();
        if (currentTime >= endTime * 1000) {
            window.location.href = 'result.php';
        }
    }, 1000); // check every second

               // Remove PHP logic from JavaScript function
            // //    function checkAns(user_ans, real_ans, num){
            //        $.ajax({
            //            type: 'POST',
            //            url: 'check_answer.php',
            //            data: {user_ans: user_ans, real_ans: real_ans},
            //            success: function(data) {
            //                console.log(data);
            //            }
            //        });
            //    }
            function checkAns(user_ans, real_ans, num){
                windows.location.href="check_answer.php";

            }
            
        </script>
        <button type="submit">submit</button>
        </form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $_SESSION['us_ans']=$_POST['us_ans'];
        $_SESSION['real_ans']=$real_ans;

}
    }
?>


</div>
<a href="result.php" ><button>I'm done</button></a>
</div>
   

<?php
} else {
    header("location:quest_selection.php");
}
?>
