<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    require_once 'classes.php'; 
    
    $stud_id = $_POST['stud_id'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $level = $_POST['level'] ?? '';
    
    if (empty($stud_id) || empty($subject)) {
        echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
        exit;
    }
    
    $quizGenerator = new QuizGenerator();
    $result = $quizGenerator->generatePersonalizedQuiz($stud_id, $subject, $level);
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
?>