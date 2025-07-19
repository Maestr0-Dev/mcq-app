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
$prep_id = $_POST['prep_id'] ?? 0;

if (empty($prep_id)) {
    echo json_encode(['success' => false, 'error' => 'Prep plan ID is required']);
    exit;
}

$db = new DB();
$result = $db->deletePrepPlan($prep_id, $stud_id);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Prep plan deleted successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete prep plan']);
}
?>
