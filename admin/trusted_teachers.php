<?php
session_start();
include '../classes.php';

if (!isset($_SESSION['admin_logged_in'])) {
    // header("location:admin_login.php");
    // exit();
}

$db = new DB();
$trusted_teachers = $db->getTrustedTeachers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trusted Teachers</title>
    <link type="text/css" rel="stylesheet" href="myCss/admin_verifications.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background: #333;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'admin_nav.php'; ?>

    <div class="container">
        <h1>Trusted Teachers</h1>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subjects</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trusted_teachers as $teacher): ?>
                    <tr>
                        <td><?= $teacher['full_name'] ?></td>
                        <td><?= $teacher['email'] ?></td>
                        <td><?= $teacher['phone'] ?></td>
                        <td><?= $teacher['subjects'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
