<?php
require_once 'classes.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

$required = ['student_id', 'subject', 'score', 'total_questions', 'percentage', 'status'];
foreach ($required as $field) {
    if (!isset($input[$field])) {
        echo json_encode(['success' => false, 'error' => "Missing required field: $field"]);
        exit;
    }
}

try {
    $performance = new Performance();
    
    $perf_data = [
        'stud_id' => $input['student_id'],
        'exam_type' => $input['quiz_type'] ?? 'personalized',
        'level' => $input['level'] ?? '',
        'subject' => $input['subject'],
        'year' => date('Y'),
        'score' => $input['score'],
        'total_questions' => $input['total_questions'],
        'correct_answers' => $input['score'],
        'date_taken' => date('Y-m-d H:i:s'),
        'status' => $input['status']
    ];
    
    $result = $performance->savePersonalizedQuizPerf($perf_data);
    
    if ($result !== false) {
        session_start();
        unset($_SESSION['generated_quiz']);
        unset($_SESSION['quiz_subject']);
        unset($_SESSION['quiz_level']);
        unset($_SESSION['student_id']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Quiz results saved successfully',
            'performance_id' => $result
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save performance data.']);
    }
    
} catch (Exception $e) {
    error_log("Error saving quiz results: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An exception occurred: ' . $e->getMessage()]);
}
?>
