<?php session_start();
include 'classes.php';
$time= $_SESSION['time'];

if(isset($_SESSION['started'])){
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
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
        .timer {
            font-size: 20px;
            font-weight: bold;
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
    <script>
        let timeLeft = <?= $time ?>; 

        function startTimer() {
            const timerElement = document.getElementById('timer');
            const interval = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    document.getElementById('quizForm').submit();
                } else {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    timeLeft--;
                }
            }, 1000);
        }

        window.onload = startTimer;
    </script>
</head>
<body>
<div class="container">
    <?php
    if (empty($result)) {
        echo "No questions found for the selected criteria.";
        exit;
    }
    $num = $_SESSION['num'] = 0;
    $t = $result[0];
?>
    <div class="timer" id="timer"></div>
    <div class="quest_info">
        <p class="yr"> </p>
        <p> <?=$t['title']?> <?=$t['year']?></p>
        <p><?=$t['subject']?></p>
    </div>
    <form id="quizForm" action="result.php" method="post">
        <div style="margin-left:20px;">
            <p><?=$t['instructions']?></p>
            <?php
            foreach($result as $key => $q){
                $real_ans = $q['ans'];
                $num++;
                $_SESSION['num']++;
            ?>
                <p style="font-weight:bolder;"><?=$num . '. ' . $q['question']?></p>
                <?php
                if (!empty($q['img'])) {
                    $path = "diagrams/" . $q['img'];
                ?>
                    <img src="<?=$path?>">
                <?php
                }
                ?>
                <p>A<input type="radio" name="us_ans[<?=$num?>]" value="<?=$q['A']?>"> <?=$q['A']?> </p>
                <p>B<input type="radio" name="us_ans[<?=$num?>]" value="<?=$q['B']?>"> <?=$q['B']?> </p>
                <p>C<input type="radio" name="us_ans[<?=$num?>]" value="<?=$q['C']?>"> <?=$q['C']?> </p>
                <p>D<input type="radio" name="us_ans[<?=$num?>]" value="<?=$q['D']?>"> <?=$q['D']?> </p>
            <?php
            }
            ?>
        </div>
        <button type="submit">I'm done</button>
    </form>
</div>
</body>
</html>

<?php
} else {
    header("location:quest_selection.php");
}
?>
