<?php
// gemini_quiz_ajax.php - Create this as a separate file for AJAX calls

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set error reporting for debugging (remove or set to 0 in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    require_once 'classes.php'; // Your classes file
    
    $geminiQuiz = new GeminiQuizGenerator('AIzaSyAcQR-u0L_189b-I0rrWb7qi-NyIg0SOoc');
    
    // Validate input
    if (!isset($input['action'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'No action specified.']);
        exit;
    }
    
    if ($input['action'] === 'generate') {
        if (empty($input['stud_id']) || empty($input['subject']) || !isset($input['level'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Missing required parameters.']);
            exit;
        }
        $result = $geminiQuiz->generateQuiz($input['stud_id'], $input['subject'], $input['level']);
        // Log Gemini API response for debugging
        file_put_contents('gemini_api_debug.log', print_r($result, true) . PHP_EOL, FILE_APPEND);
        header('Content-Type: application/json');
        echo json_encode($result);
        
    } elseif ($input['action'] === 'submit') {
        if (empty($input['answers'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'No answers provided.']);
            exit;
        }
        $result = $geminiQuiz->submitQuiz($input['answers']);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Unknown action.']);
    }
}
?>
