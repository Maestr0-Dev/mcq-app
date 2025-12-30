<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['quiz_result'])) {
    header('Location: student_content.php');
    exit;
}

$result = $_SESSION['quiz_result'];
unset($_SESSION['quiz_result']); // Clear the result from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        h2 {
            color: #555;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: conic-gradient(#4caf50 <?= ($result['score'] / $result['total']) * 360 ?>deg, #e0e0e0 0deg);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto;
            font-size: 2.5em;
            font-weight: bold;
            color: #4caf50;
        }
        p {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 30px;
        }
        a {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 1em;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quiz Result</h1>
        <h2><?= htmlspecialchars($result['title']) ?></h2>
        <div class="score-circle">
            <?= htmlspecialchars($result['score']) ?>
        </div>
        <p>You scored <?= htmlspecialchars($result['score']) ?> out of <?= htmlspecialchars($result['total']) ?>.</p>
        <a href="student_content.php">Back to My Content</a>
    </div>
</body>
>>>>>>> Stashed changes
</html>
