<?php
session_start();
include 'classes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['id'] ?? null;
    $teacher_id = $_POST['teacher_id'] ?? null;

    if ($student_id && $teacher_id) {
        $db = new DB();
        $success = $db->requestMentor($student_id, $teacher_id);

        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Request sent successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send request.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    }
}