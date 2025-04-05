<?php
session_start();
include 'classes.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['id'] ?? null; // Assuming student ID is stored in the session
    $input = json_decode(file_get_contents('php://input'), true);
    $teacher_id = $input['teacher_id'] ?? null;

    if (!$student_id) {
        echo json_encode(['status' => 'error', 'message' => 'Student ID is missing.']);
        exit();
    }

    if (!$teacher_id) {
        echo json_encode(['status' => 'error', 'message' => 'Teacher ID is missing.']);
        exit();
    }

    $db = new DB();

    // Check if a request already exists
    $is_request_sent = $db->isMentorRequestSent($student_id, $teacher_id);

    if ($is_request_sent) {
        echo json_encode(['status' => 'error', 'message' => 'Request already sent.']);
        exit();
    }

    // Insert the mentor request with a default state of 'No'
    $success = $db->requestMentor($student_id, $teacher_id);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Request sent successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send request.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}