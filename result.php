<?php
session_start();
include 'classes.php';

$year = $_SESSION['year'];
$subj = $_SESSION['subj'];
$table = $_SESSION['exam'];
$num = $_SESSION['num'];
$lvl = $_SESSION['level'];
$stud_id = $_SESSION['id'];
$score = $_SESSION['SCR'];
$total_questions = $_SESSION['tot'];
$correct_answers = $score;
$newScore = ($score / $total_questions) * 20;
$date = date('Y-m-d H:i:s');
$status = ($score >= ($num / 2)) ? 'passed' : 'failed';
if ( !isset($table)) {
    echo "Error: Missing session variables.";
    exit;
}

$dur=0;
$date=date('Y-m-d h:i:s');

$perf_data = [
    'stud_id' => $stud_id,
    'exam_type' => $table, 
    'level' => $lvl,
    'subject' => $subj,
    'year' => $year,
    'score' => $newScore,
    'total_questions' => $total_questions,
    'correct_answers' => $score,
    'date_taken' => $date,
    'status' => $status
];

$db = new DB();
$performance_id = $db->savePerf($perf_data);

// if ($performance_id) {
//     echo " Performance saved successfully with ID: $performance_id -->";
// } else {
//     echo " Error saving performance -->";
// }

$data = [$year, $subj];
$dB = new DB();
$result = $dB->Get($table,$data);

if (isset($_POST['get_explanation'])) {
    header('Content-Type: application/json');
    
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $options = $_POST['options'];
    $image_path = $_POST['image_path'] ?? null;
    
        $api_key = 'AIzaSyBjhglUlofps8fUgfCcvuIoP-dHHKkWsxM';
    
    $prompt = "Please provide a brief explanation (3-4 sentences) of why the answer '{$answer}' is correct for this question:\n\n";
    $prompt .= "Question: {$question}\n\n";
    $prompt .= "Options:\n{$options}\n\n";
    $prompt .= "Correct Answer: {$answer}\n\n";
    $prompt .= "Explain why this is the correct answer in simple terms.";
    
    $request_data = [
        'contents' => [
            [
                'parts' => []
            ]
        ]
    ];
    
    if ($image_path && file_exists($image_path)) {
        $image_data = base64_encode(file_get_contents($image_path));
        $mime_type = mime_content_type($image_path);
        
        $request_data['contents'][0]['parts'][] = [
            'inline_data' => [
                'mime_type' => $mime_type,
                'data' => $image_data
            ]
        ];
    }
    
    // Add text prompt
    $request_data['contents'][0]['parts'][] = [
        'text' => $prompt
    ];
    
    error_log("API Request Data: " . json_encode($request_data));
    
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $api_key;
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
            ],
            'content' => json_encode($request_data),
            'ignore_errors' => true
        ]
    ]);
    
    $response = file_get_contents($url, false, $context);
    
    $http_status = $http_response_header[0];
    error_log("API Response: " . $response);
    error_log("HTTP Response Headers: " . json_encode($http_response_header));
    
    if ($response === false) {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to make API request. HTTP Status: ' . $http_status
        ]);
        exit;
    }
    
    if (strpos($http_status, '200') === false) {
        echo json_encode([
            'success' => false,
            'error' => 'API request failed with status: ' . $http_status . '. Response: ' . substr($response, 0, 500)
        ]);
        exit;
    }
    
    $response_data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON response: ' . json_last_error_msg() . '. Raw response: ' . substr($response, 0, 500)
        ]);
        exit;
    }
    
    if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
        echo json_encode([
            'success' => true,
            'explanation' => $response_data['candidates'][0]['content']['parts'][0]['text']
        ]);
    } else if (isset($response_data['error'])) {
        echo json_encode([
            'success' => false,
            'error' => 'API Error: ' . $response_data['error']['message']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Unexpected response format. Response: ' . substr($response, 0, 500)
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link type="text/css" rel="stylesheet" href="css/style.css">
        <link type="text/css" rel="stylesheet" href="myCss/result-style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="script.js"></script>
<title>Results</title>

<style>
    .explanation-box {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 15px;
    margin-top: 10px;
    margin-bottom: 10px;
    display: none;
    }

    .explanation-box.loading {
    display: block;
    text-align: center;
    color: #6c757d;
    }

    .explanation-box.loaded {
    display: block;
    }

    .brain-icon {
    cursor: pointer;
    transition: all 0.3s ease;
    }   

    .brain-icon:hover {
    transform: scale(1.2);
    color: #28a745 !important;
    }

    .brain-icon.loading {
    animation: spin 1s linear infinite;
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
    $question_id = 'question_' . $num;
?>
<br>
<div class="question-cont">  
    <p><?=$q['instructions']?></p>
    <?php if(isset($_SESSION['id'])){
    ?>
    <?php
    } else{
        echo "no ID";}?>
    <p style="font-weight:bolder;"><?=$num . '. ' . $q['question']?></p>

    <?php
        $image_path = '';
        if (!empty($q['img'])) {
            $image_path = "diagrams/" . $q['img'];
?>
            <img src="<?=$image_path?>">
<?php
        }
?>
        <p>A. <?=$q['A']?> </p>
        <p>B. <?=$q['B']?> </p>
        <p>C. <?=$q['C']?> </p>
        <p>D. <?=$q['D']?> </p>
    <p><b>Answer:</b> <span style="color:green;"><?=$q['ans']?></span> 
     <i class="fa fa-brain brain-icon" 
        style="color:green;" 
        onclick="getExplanation('<?=$question_id?>', `<?=str_replace(['`', '\\'], ['\\`', '\\\\'], $q['question'])?>`, '<?=$q['ans']?>', `<?=str_replace(['`', '\\'], ['\\`', '\\\\'], "A. ".$q['A']." B. ".$q['B']." C. ".$q['C']." D. ".$q['D'])?>`, '<?=$image_path?>')"></i>
    </p>
    
    <div id="explanation_<?=$question_id?>" class="explanation-box">
        <!-- AI explanation will be displayed here -->
    </div>
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
    Take Another
    </button>
</a>
<a href="performnace.php">
    <button >
    <i class="fa fa-arrow-chart-line"></i>
    Performances
    </button>
</a>

<script>
function getExplanation(questionId, question, answer, options, imagePath) {
    console.log('Brain icon clicked for:', questionId); // Debug log
    
    const explanationBox = document.getElementById('explanation_' + questionId);
    const brainIcon = event.target;
    
    if (!explanationBox) {
        console.error('Explanation box not found for:', questionId);
        return;
    }
    
    // Show loading state
    brainIcon.classList.add('loading');
    explanationBox.className = 'explanation-box loading';
    explanationBox.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Getting BrazeAI explanation...';
    
    // Prepare form data
    const formData = new FormData();
    formData.append('get_explanation', '1');
    formData.append('question', question);
    formData.append('answer', answer);
    formData.append('options', options);
    if (imagePath && imagePath.trim() !== '') {
        formData.append('image_path', imagePath);
    }
    
    console.log('Sending request with data:', {
        question: question,
        answer: answer,
        options: options,
        imagePath: imagePath
    });
    
    // Make AJAX request
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        brainIcon.classList.remove('loading');
        
        if (data.success) {
            explanationBox.className = 'explanation-box loaded';
            explanationBox.innerHTML = '<h6><i class="fa fa-brain"></i> AI Explanation:</h6><p>' + data.explanation + '</p>';
        } else {
            explanationBox.className = 'explanation-box loaded';
            explanationBox.innerHTML = '<h6><i class="fa fa-exclamation-triangle"></i> Error:</h6><p>Unable to get explanation: ' + (data.error || 'Unknown error') + '</p>';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        brainIcon.classList.remove('loading');
        explanationBox.className = 'explanation-box loaded';
        explanationBox.innerHTML = '<h6><i class="fa fa-exclamation-triangle"></i> Error:</h6><p>Failed to get explanation. Please try again.</p>';
    });
}
</script>

</body>

</html>
<?php
       $_SESSION['SCR']=0;
?>
