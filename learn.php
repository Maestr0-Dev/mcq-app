<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Learn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background: linear-gradient(to left, purple, blue);
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        
        .main-content {
            padding: 20px;
            text-align: center;
        }
        .main-content h2 {
            color: #333;
        }
        .main-content p {
            color: #666;
        }
        .resources {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .resource {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 300px;
            margin: 10px;
            text-align: center;
        }
        .resource img {
            max-width: 100%;
            border-radius: 10px;
        }
        .resource h3 {
            color: #333;
        }
        .resource p {
            color: #666;
        }
        .footer {
            background: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .footer p {
            margin: 0;
        }
        @media (max-width: 768px) {
            .resources {
                flex-direction: column;
                align-items: center;
            }
            .resource {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Learn</h1>
    <p>Explore our learning resources and materials</p>
</div>

<?php
include 'menu.php';
?>

<div class="main-content">
    <h2>Learning Resources</h2>
    <p>At Quiz-Master, we provide a variety of learning resources to help you excel in your studies. Explore our materials below:</p>
</div>

<div class="resources">
    <div class="resource">
        <img src="img/resource1.jpg" alt="Resource 1">
        <h3>Resource 1</h3>
        <p>Detailed notes and guides on various subjects to help you understand the concepts better.</p>
        <!-- <a href="resource1.php" class="button">Explore Resource 1</a> -->
    </div>
    <div class="resource">
        <img src="img/resource2.jpg" alt="Resource 2">
        <h3>Resource 2</h3>
        <p>Interactive video tutorials to make learning more engaging and effective.</p>
        <!-- <a href="resource2.php" class="button">Explore Resource 2</a> -->
    </div>
    <div class="resource">
        <img src="img/resource3.jpg" alt="Resource 3">
        <h3>Resource 3</h3>
        <p>Practice exercises and quizzes to test your knowledge and improve your skills.</p>
        <!-- <a href="resource3.php" class="button">Explore Resource 3</a> -->
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Quiz-Master. All rights reserved.</p>
</div>

</body>
</html>