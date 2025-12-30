<?php
session_start();
include '../classes.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php');
    exit;
}

$teacher_id = $_SESSION['teacher_id'];
$db = new DB();
$students = $db->getMenteesForTeacher($teacher_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .student-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .student-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .student-card h3 {
            margin-top: 0;
        }
        .student-card a {
            text-decoration: none;
            color: #333;
        }
        .student-card a:hover {
            color: #007bff;
        }
        .student-card-button {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
            text-align: left;
            width: 100%;
            color: #333;
        }
        .student-card-button:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <?php include 'teacher_nav.php'; ?>
    <div class="container">
        <h1>My Students</h1>
        <div class="student-grid">
            <?php if (empty($students)): ?>
                <p>You have no students yet.</p>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <div class="student-card">
                        <form action="view_student_performance.php" method="post" style="display:inline;">
                            <input type="hidden" name="stud_id" value="<?= $student['stud_id'] ?>">
                            <button type="submit" class="student-card-button">
                                <h3><?= htmlspecialchars($student['stud_name']) ?></h3>
                                <p>Level: <?= htmlspecialchars($student['level']) ?></p>
                                <p>Phone: <?= htmlspecialchars($student['number']) ?></p>
                                <p>Email: <?= htmlspecialchars($student['email']) ?></p>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
