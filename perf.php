<?php
session_start();
include 'classes.php';

if (isset($_SESSION['id'])) {
    $db = new DB();
    $id = $_SESSION['id'];
    $perform = $db->getPerf($id);
    $topPerformers = $db->getTopPerformers($_SESSION['level']);

    $scores = [];
    $dates = [];

    foreach ($perform as $p) {
        $scores[] = $p['score'];
        $dates[] = $p['date']; // Assuming 'date' is the column name for the date in your database
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <h1>Performance</h1>
        <div style="width: 75%; margin: auto;">
            <canvas id="scoreChart"></canvas>
        </div>
        <script>
            const scores = <?= json_encode($scores) ?>;
            const dates = <?= json_encode($dates) ?>;

            console.log(scores);
            console.log(dates);

            const scoreCtx = document.getElementById('scoreChart').getContext('2d');

            const scoreChart = new Chart(scoreCtx, {
                type: 'line', // Change to 'line' for a line chart
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Scores',
                        data: scores,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false // Do not fill the area under the line
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Score'
                            }
                        }
                    }
                }
            });
        </script>
        <h2>Leaderboard</h2>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Student Name</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                foreach ($topPerformers as $performer) {
                    echo "<tr>";
                    echo "<td>{$rank}</td>";
                    echo "<td>{$performer['stud_name']}</td>";
                    echo "<td>{$performer['max_score']}</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
        <a href="home.php">
            <button>Back to menu</button>
        </a>
    </div>
</body>
</html>