<?php
session_start();
include 'classes.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: discover.php');
    exit;
}

$db = new DB();
$student_id = $_SESSION['id'];
$teacher_id = (int)$_GET['id'];
$tutor_content = $db->getStudentContentFromTutor($student_id, $teacher_id);
$teacher = $db->getTeacherById($teacher_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content from <?= htmlspecialchars($teacher['full_name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Content from <?= htmlspecialchars($teacher['full_name']) ?></h1>

        <div class="content-list">
            <?php if (empty($tutor_content)): ?>
                <p>This tutor has not sent you any content yet.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Received At</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($tutor_content as $content): ?>
                        <tr>
                            <td><?= htmlspecialchars($content['title']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($content['content_type'])) ?></td>
                            <td><?= htmlspecialchars($content['sent_at']) ?></td>
                            <td>
                                <a href="view_content.php?id=<?= urlencode($content['student_content_id']) ?>">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
