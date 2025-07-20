<?php
session_start();
include 'classes.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

if (!isset($_GET['com_id'])) {
    echo json_encode(['error' => 'Community ID not provided']);
    exit;
}

$com_id = (int)$_GET['com_id'];
$db = new DB();
$posts = $db->getCommunityPosts($com_id);

echo json_encode($posts);
?>
