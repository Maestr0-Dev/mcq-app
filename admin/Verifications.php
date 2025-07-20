<?php
session_start();
include '../classes.php';

if (!isset($_SESSION['admin_logged_in'])) {
    // header("location:admin_login.php");
    // exit();
}

$db = new DB();
$verification_requests = $db->getVerificationRequests();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $verification_id = $_POST['verification_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $db->updateVerificationState($verification_id, 'Yes');
    } elseif ($action === 'disapprove') {
        $db->updateVerificationState($verification_id, 'No');
    }

    header("location:Verifications.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifications</title>
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
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-approve {
            background: green;
            color: white;
        }
        .btn-disapprove {
            background: red;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Teacher Verifications</h1>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>ID Card</th>
                    <th>Certificate</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($verification_requests as $request): ?>
                    <tr>
                        <td><?= $request['full_name'] ?></td>
                        <td><?= $request['dob'] ?></td>
                        <td><a href="../teachers/<?= $request['id_card'] ?>" target="_blank">View ID Card</a></td>
                        <td><a href="../teachers/<?= $request['certificate'] ?>" target="_blank">View Certificate</a></td>
                        <td><?= $request['state'] ?></td>
                        <td>
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="verification_id" value="<?= $request['verification_id'] ?>">
                                <button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button>
                            </form>
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="verification_id" value="<?= $request['verification_id'] ?>">
                                <button type="submit" name="action" value="disapprove" class="btn btn-disapprove">Disapprove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>