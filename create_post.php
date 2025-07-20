<?php
session_start();
include 'classes.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['com_id']) || !isset($data['content'])) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$com_id = (int)$data['com_id'];
$content = htmlspecialchars($data['content']);
$user_id = $_SESSION['id'];

$db = new DB();
$result = $db->createPost($com_id, $user_id, 'text', $content);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to create post']);
}
?>
