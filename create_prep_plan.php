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

$stud_id = $_SESSION['id'];
$subject = $_POST['subject'] ?? '';
$topics = $_POST['topics'] ?? '';
$exam_date = $_POST['exam-date'] ?? '';

if (empty($subject) || empty($topics) || empty($exam_date)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

$db = new DB();
$data = [$stud_id, $subject, $topics, $exam_date];
$result = $db->createPrepPlan($data);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Prep plan created successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to create prep plan']);
}
?>
