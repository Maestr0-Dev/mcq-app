<?php
session_start();
include 'classes.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$db = new DB();
$student_id = $_SESSION['id'];
$my_content = $db->getStudentContent($student_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Content</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>My Content</h1>

        <div class="content-list">
            <h2>Content from my Mentors</h2>
            <?php if (empty($my_content)): ?>
                <p>You have not received any content yet.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>From</th>
                        <th>Received At</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($my_content as $content): ?>
                        <tr>
                            <td><?= $content['title'] ?></td>
                            <td><?= ucfirst($content['content_type']) ?></td>
                            <td><?= $content['full_name'] ?></td>
                            <td><?= $content['sent_at'] ?></td>
                            <td>
                                <a href="view_content.php?id=<?= $content['student_content_id'] ?>">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
