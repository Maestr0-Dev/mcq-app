&lt;?php
session_start();
include 'classes.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['student_content_id'])) {
    header('Location: student_content.php');
    exit;
}

$db = new DB();
$student_id = $_SESSION['id'];
$student_content_id = (int)$_POST['student_content_id'];
$answers = $_POST['answers'];

$content = $db->getStudentContentById($student_content_id, $student_id);

if (!$content || $content['content_type'] !== 'quiz') {
    header('Location: student_content.php');
    exit;
}

// Simple quiz parser
function parse_quiz($quiz_content) {
    $questions = [];
    $lines = explode("\n", $quiz_content);
    foreach ($lines as $line) {
        if (preg_match('/^Q\d+:\s*(.*)\|(.*)\|(.*)\|(.*)\|(.*)\|(.*)$/', $line, $matches)) {
            $questions[] = [
                'question' => trim($matches[1]),
                'options' => [
                    'A' => trim($matches[2]),
                    'B' => trim($matches[3]),
                    'C' => trim($matches[4]),
                    'D' => trim($matches[5]),
                ],
                'answer' => trim($matches[6])
            ];
        }
    }
    return $questions;
}

$questions = parse_quiz($content['content']);
$score = 0;
$total_questions = count($questions);

foreach ($questions as $i =&gt; $q) {
    if (isset($answers[$i]) && strtoupper($answers[$i]) === strtoupper($q['answer'])) {
        $score++;
    }
}

$db->updateStudentContentStatus($student_content_id, 'completed');

// Store the result in the session to display on the result page
$_SESSION['quiz_result'] = [
    'title' => $content['title'],
    'score' => $score,
    'total' => $total_questions
];

header('Location: quiz_result.php');
exit;
?&gt;
