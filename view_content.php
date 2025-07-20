<?php
session_start();
include 'classes.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: student_content.php');
    exit;
}

$db = new DB();
$student_id = $_SESSION['id'];
$student_content_id = (int)$_GET['id'];

$content = $db->getStudentContentById($student_content_id, $student_id);

if (!$content) {
    header('Location: student_content.php');
    exit;
}

if ($content['status'] === 'unread') {
    $db->updateStudentContentStatus($student_content_id, 'read');
}

// Simple quiz parser
function parse_quiz($quiz_content) {
    $questions = [];
    $lines = explode("\n", $quiz_content);
    foreach ($lines as $line) {
        if (preg_match('/^Q\d+:\s*(.*)\|(.*)\|(.*)\|(.*)\|(.*)\|(.*)$/', $line, $matches)) {
            $questions[] = [
                'question' => trim($matches[1]),
                'options' => [
                    'A' => trim($matches[2]),
                    'B' => trim($matches[3]),
                    'C' => trim($matches[4]),
                    'D' => trim($matches[5]),
                ],
                'answer' => trim($matches[6])
            ];
        }
    }
    return $questions;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($content['title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($content['title']) ?></h1>
        <p><strong>From:</strong> <?= htmlspecialchars($content['full_name']) ?></p>
        <p><strong>Type:</strong> <?= ucfirst(htmlspecialchars($content['content_type'])) ?></p>
        <div class="content-body">
            <?php if ($content['content_type'] === 'note'): ?>
                <p><?= nl2br(htmlspecialchars($content['content'])) ?></p>
            <?php elseif ($content['content_type'] === 'quiz'): ?>
                <form action="submit_teacher_quiz.php" method="POST">
                    <input type="hidden" name="student_content_id" value="<?= $student_content_id ?>">
                    <?php $questions = parse_quiz($content['content']); ?>
                    <?php foreach ($questions as $i => $q): ?>
                        <div class="quiz-question">
                            <p><strong>Question <?= $i + 1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></p>
                            <ul>
                                <?php foreach ($q['options'] as $key => $option): ?>
                                    <li>
                                        <label>
                                            <input type="radio" name="answers[<?= $i ?>]" value="<?= $key ?>" required>
                                            <?= htmlspecialchars($option) ?>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit">Submit Quiz</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
