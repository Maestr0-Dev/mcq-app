<?php
session_start();
require_once 'classes.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$quiz_id = $_POST['quiz_id'] ?? 0;
if (empty($quiz_id)) {
    echo json_encode(['success' => false, 'error' => 'No quiz selected']);
    exit;
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=interactives_mcqs", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT quiz_data FROM prep_quizzes WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$quiz_id]);
    $quiz_json = $stmt->fetchColumn();

    if (!$quiz_json) {
        echo json_encode(['success' => false, 'error' => 'Quiz not found']);
        exit;
    }

    $quiz = json_decode($quiz_json, true);
    $results = [];

    foreach ($quiz['questions'] as $index => $question) {
        $user_answer = $_POST['question_' . $index] ?? null;
        $correct_answer = $question['correct'];
        
        $results[] = [
            'question' => $question['question'],
            'user_answer' => $user_answer,
            'correct_answer' => $correct_answer,
            'is_correct' => $user_answer === $correct_answer,
            'explanation' => $question['explanation']
        ];
    }

    echo json_encode(['success' => true, 'results' => $results]);

} catch (Exception $e) {
    error_log("Error submitting prep quiz: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error.']);
}
?>
