<?php session_start();
include 'classes.php';
if (isset($_SESSION['started'])) {
    $year = $_SESSION['year'];
    $subj = $_SESSION['subj'];
    $table = $_SESSION['exam'];
    $time = $_SESSION['time'];
    $lvl = $_SESSION['level'];
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
    <script>
    MathJax = {
      tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']]
      }
    };
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="myCss/quiz-style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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

        // Function to read a question from a DOM element by its ID
        function readQuestionById(elementId) {
            const questionElement = document.getElementById(elementId);
            if (!questionElement) {
                console.error("Question element not found: ", elementId);
                return;
            }
            // Prioritize the data-speech-text attribute for pronunciation, fallback to innerText
            const questionText = questionElement.dataset.speechText || questionElement.innerText;

            // Cancel any ongoing speech to prevent overlap
            if (window.speechSynthesis.speaking) {
                window.speechSynthesis.cancel();
            }

            // Create a new speech synthesis utterance
            const speech = new SpeechSynthesisUtterance(questionText);
            speech.lang = 'en-UK'; // Set language for consistency
            speech.rate = 0.75;       // Normal speech rate
            speech.pitch = 1;      // Normal pitch

            // Log events for debugging
            speech.onstart = () => console.log("Speech started for: " + questionText);
            speech.onend = () => console.log("Speech ended for: " + questionText);
            speech.onerror = (event) => console.error("Speech error: ", event.error);

            // Speak the text
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
    <p><?=$t['subject']?> | <?=$t['year']?></p>

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

        // Create a speech-friendly version of the question
        $speech_text = str_replace(
            ['X', '⅒', '⁶', '²', '⁹', '°', '½', '¼', '¾', '³', '⁴', '⁵', '⁷', '⁸', '¹', '⁰'],
            [' times ', ' one tenth ', ' to the power of 6 ', ' squared ', ' to the power of 9 ', ' degrees ', ' one half ', ' one quarter ', ' three quarters ', ' cubed ', ' to the power of 4 ', ' to the power of 5 ', ' to the power of 7 ', ' to the power of 8 ', ' to the power of 1 ', ' to the power of 0 '],
            $q['question']
        );
?>

        <p style="font-weight: bolder;">
            <span id="question-text-<?=$num?>" data-speech-text="<?=htmlspecialchars($speech_text, ENT_QUOTES)?>"><?=$num . '. ' . $q['question']?></span>
            <?php 
            if($_SESSION['mic_set']==true){
            ?>
            <i class="fa fa-microphone mic-icon" onclick="readQuestionById('question-text-<?=$num?>')"></i>
            <?php } ?>
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
        $_SESSION['tot']= $num;
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
