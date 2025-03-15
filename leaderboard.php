<?php
session_start();
include 'classes.php';

$db = new DB();
$topPerformers = $db->getTopPerformers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <style>
        .container {
            width: 75%;
            margin: auto;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Leaderboard</h1>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Student ID</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                foreach ($topPerformers as $performer) {
                    echo "<tr>";
                    echo "<td>{$rank}</td>";
                    echo "<td>{$performer['stud_id']}</td>";
                    echo "<td>{$performer['max_score']}</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
        <a href="menu.php">
            <button>Back to menu</button>
        </a>
    </div>
</body>
</html>