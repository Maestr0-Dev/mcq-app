<?php
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

foreach ($questions as $i => $q) {
    if (isset($answers[$i]) && $answers[$i] === $q['answer']) {
        $score++;
    }
}

// Check if score and total_questions columns exist, and add them if they don't
if (!$db->columnExists('student_content', 'score')) {
    $db->addColumn('student_content', 'score', 'INT(11) NOT NULL DEFAULT 0');
}
if (!$db->columnExists('student_content', 'total_questions')) {
    $db->addColumn('student_content', 'total_questions', 'INT(11) NOT NULL DEFAULT 0');
}

$db->updateStudentContentStatus($student_content_id, 'completed');
$db->saveQuizScore($student_content_id, $score, $total_questions);

// Store the result in the session to display on the result page
$_SESSION['quiz_result'] = [
    'title' => $content['title'],
    'score' => $score,
    'total' => $total_questions
];

header('Location: quiz_result.php');
exit;
?>
