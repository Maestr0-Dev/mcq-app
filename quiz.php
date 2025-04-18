<?php session_start();
include 'classes.php';
if (isset($_SESSION['started'])) {
    $year = $_SESSION['year'];
    $subj = $_SESSION['subj'];
    $table = $_SESSION['exam'];
    $time = $_SESSION['time'];
    $_SESSION['mic_set']=true;

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
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="myCss/quiz-style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
    <title>Quiz-Master</title>
    <style>
        .container {
            max-width: 1000px;
            margin: 5px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            font-family: helvetica;
        }
        .quest_info {
            font-family: helvetica;
            height: auto;
            background: linear-gradient(to left, purple, blue);
            color: white;
            padding: 30px;
            font-weight: bolder;
            text-align: center;
        }
        .holder {
            margin: 10px auto;
        }
        button {
            background: linear-gradient(to left, purple, blue);
            padding: 0.8rem 1.5rem;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px;
        }
        img {
            height: 150px;
            width: auto;
        }
        .mic-icon {
            color: green;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
    <script>
           let timeLeft = <?= $time ?>; 

function startTimer() {
    const timerElement = document.getElementById('timer');
    const interval = setInterval(() => {
        if (timeLeft <= 0) {
           window.location.href = "result.php";
            clearInterval(interval);
        } else {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            timeLeft--;
        }
    }, 1000);
}

window.onload = startTimer;

        // Function to read a specific question
        function readQuestion(questionText) {
    // Cancel any ongoing speech
    if (window.speechSynthesis.speaking) {
        window.speechSynthesis.cancel();
    }

    // Create a new speech synthesis utterance
    const speech = new SpeechSynthesisUtterance(questionText);
    speech.lang = 'en-UK'; // Set the language
    speech.rate = 1; // Adjust the rate for better clarity
    speech.pitch = 1; // Adjust the pitch if needed

    // Speak the question
    speech.onstart = () => {
        console.log("Speech started for: " + questionText);
    };

    speech.onend = () => {
        console.log("Speech ended for: " + questionText);
    };

    speech.onerror = (event) => {
        console.error("Speech error: ", event.error);
    };

    window.speechSynthesis.speak(speech);
}
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
<div class="quest_info">
    <p class="yr"> </p>
    <p> <?=$t['title']?> <?=$t['year']?></p>
    <p><?=$t['subject']?></p>
    <p><i class="fa fa-clock"></i> <label for="" id="timer"></label></p>
</div>
<form action="result.php" method="post">
<div style="margin-left:20px;">
    <p><?=$t['instructions']?></p>

<?php
    foreach ($result as $key => $q) {
        $real_ans = $q['ans'];
        $num++;
        $_SESSION['num']++;
?>

        <p style="font-weight: bolder;">
            <?=$num . '. ' . $q['question']?>
            
            <?php 
            if($_SESSION['mic_set']==true){
            ?>
            <i class="fa fa-microphone mic-icon" onclick="readQuestion('<?=htmlspecialchars($q['question'], ENT_QUOTES)?>')"></i>
                <?php }
                ?>
        </p>
<?php
        if (!empty($q['img'])) {
            $path = "diagrams/" . $q['img'];
?>
            <img src="<?=$path?>">
<?php
        }
?>
        <p>A<input type="radio" name="us_ans[<?=$num?>]" value="<?=$q['A']?>" onclick="checkAns('<?=$q['A']?>','<?=$real_ans?>','<?=$num?>')"> <?=$q['A']?> </p>
        <p>B<input type="radio" name="us_ans[<?=$num?>]" value="<?=$q['B']?>" onclick="checkAns('<?=$q['B']?>','<?=$real_ans?>','<?=$num?>')"> <?=$q['B']?> </p>
        <p>C<input type="radio" name="us_ans[<?=$num?>]" value="<?=$q['C']?>" onclick="checkAns('<?=$q['C']?>','<?=$real_ans?>','<?=$num?>')"> <?=$q['C']?> </p>
        <p>D<input type="radio" name="us_ans[<?=$num?>]" value="<?=$q['D']?>" onclick="checkAns('<?=$q['D']?>','<?=$real_ans?>','<?=$num?>')"> <?=$q['D']?> </p>

<?php
    }
?>

</div>

<button type="submit">I'm done</button>
</div>
</form>
<a href="quest_selection.php">
    <button><i class="fa fa-arrow-left"></i> Leave</button>
</a>
<script src="jquery-3.1.0.min.js"></script> 
<script>
    function updateTimerClass() {
        const timerElement = document.getElementById('timer');
        if (timeLeft < 60) { // Less than 1 minute
            timerElement.classList.add('timer-danger');
        } else if (timeLeft < 180) { // Less than 3 minutes
            timerElement.classList.add('timer-warning');
        }
    }
</script>
</body>
</html>

<?php
} else {
    header("location:quest_selection.php");
}
?>