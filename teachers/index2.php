<?php
session_start();
include 'C:\xampp\htdocs\mcq-app\classes.php';

if (!isset($_SESSION['teacher_id'])) {
    header("location:login.php");
    exit();
}

$db = new DB();
$teacher_id = $_SESSION['teacher_id'];
$requests = $db->getMentorRequestsForTeacher($teacher_id);
$approved_students_count = $db->getApprovedStudentsCount($teacher_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mentor_id = $_POST['mentor_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $db->updateMentorRequestState($mentor_id, 'Yes');
    } elseif ($action === 'reject') {
        $db->updateMentorRequestState($mentor_id, 'No');
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dash Board</title>
    <link type="text/css" rel="stylesheet" href="myCss/dash_board.css">
 
</head>
<body>
<?php  include 'teacher_nav.php';
    ?>
    <div class="stats-container">
        <div class="stat-card">
        <a href="myStudents.php">

    <p> Students: <strong><?= $approved_students_count ?></strong></p>
    <?php
$average_score = $db->getAverageStudentScore($teacher_id);
?>
</a>

</div>
<div class="stat-card">
<p>Average Student Score: <strong> <?=$average_score?> </strong></p>
</div>
</div>
    <h1>Mentorship Requests</h1>

    <table class="table table-zebra">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Level</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= $request['stud_name'] ?></td>
                    <td><?=$request['level']?>
                    <td><?= $request['state'] === 'Yes' ? 'Approved' : 'Pending' ?></td>
                    <td>
                       
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="mentor_id" value="<?= $request['mentor_id'] ?>">
                                <button type="submit" name="action" value="approve">Approve</button>
                            </form>
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="mentor_id" value="<?= $request['mentor_id'] ?>">
                                <button type="submit" name="action" value="reject">Reject</button>
                            </form>
                        
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>