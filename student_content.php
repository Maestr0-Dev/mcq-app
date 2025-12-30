<?php
session_start();
include 'classes.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$db = new DB();
$student_id = $_SESSION['id'];
$my_content = $db->getStudentContent($student_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Content</title>
    <link rel="stylesheet" href="css/style.css">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
            text-align: center;
            position: relative;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 2px;
        }

        /* Content list section */
        .content-list {
            padding: 30px;
            background: #fafafa;
        }

        .content-list h2 {
            color: #444;
            font-size: 1.8em;
            font-weight: 500;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            position: relative;
        }

        .content-list h2::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 60px;
            height: 3px;
            background: #764ba2;
            border-radius: 2px;
        }

        /* Empty state message */
        .content-list > p {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            color: #666;
            font-size: 1.2em;
            border: 2px dashed #ddd;
            margin-top: 20px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        /* Table header */
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 1em;
            letter-spacing: 0.5px;
            position: relative;
        }

        th:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 25%;
            height: 50%;
            width: 1px;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Table rows */
        tr {
            transition: all 0.3s ease;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        tr:hover {
            background: linear-gradient(135deg, #667eea08, #764ba208);
            transform: scale(1.002);
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
        }

        /* Table cells */
        td {
            padding: 18px 20px;
            border-bottom: 1px solid #eee;
            font-size: 0.95em;
            vertical-align: middle;
        }

        td:first-child {
            font-weight: 600;
            color: #444;
        }

        td:nth-child(2) {
            color: #667eea;
            font-weight: 500;
        }

        td:nth-child(3) {
            color: #666;
        }

        td:nth-child(4) {
            color: #888;
            font-size: 0.9em;
        }

        /* Action links */
        td a {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
        }

        td a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        td a:active {
            transform: translateY(0);
        }

        .view-button {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
            border: none;
            cursor: pointer;
        }

        .view-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        /* Content type badges */
        td:nth-child(2) {
            position: relative;
        }

        td:nth-child(2)::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }

        /* Different colors for content types */
        tr:has(td:nth-child(2):contains("Note"))::before,
        td:nth-child(2):contains("Note")::before {
            background: #4CAF50;
        }

        tr:has(td:nth-child(2):contains("Quiz"))::before,
        td:nth-child(2):contains("Quiz")::before {
            background: #FF9800;
        }

        tr:has(td:nth-child(2):contains("Assignment"))::before,
        td:nth-child(2):contains("Assignment")::before {
            background: #f44336;
        }

        /* Remove last row border */
        tr:last-child td {
            border-bottom: none;
        }

        /* Responsive design */
        @media (max-width: 992px) {
            .container {
                margin: 0 10px;
            }
            
            th, td {
                padding: 15px 12px;
                font-size: 0.9em;
            }
            
            h1 {
                font-size: 2.2em;
                padding: 25px;
            }
            
            .content-list {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                border-radius: 8px;
                margin: 0;
            }
            
            h1 {
                font-size: 1.8em;
                padding: 20px;
            }
            
            .content-list {
                padding: 15px;
            }
            
            .content-list h2 {
                font-size: 1.5em;
            }
            
            /* Stack table for mobile */
            table, thead, tbody, th, td, tr {
                display: block;
            }
            
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            
            tr {
                border: 1px solid #ccc;
                margin-bottom: 10px;
                padding: 0;
                border-radius: 8px;
                background: white;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }
            
            tr:hover {
                transform: none;
                background: white;
            }
            
            td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding: 15px 15px 15px 100px;
                text-align: left;
            }
            
            td:before {
                content: attr(data-label) ": ";
                position: absolute;
                left: 15px;
                width: 80px;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                color: #667eea;
                top: 15px;
            }
            
            td:last-child {
                border-bottom: none;
                padding-bottom: 20px;
            }
            
            td a {
                margin-left: -85px;
                display: inline-block;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5em;
                padding: 15px;
            }
            
            .content-list {
                padding: 10px;
            }
            
            .content-list h2 {
                font-size: 1.3em;
            }
            
            td {
                padding: 12px 12px 12px 85px;
                font-size: 0.9em;
            }
            
            td:before {
                width: 70px;
                left: 12px;
                font-size: 0.85em;
            }
            
            td a {
                margin-left: -73px;
                padding: 8px 16px;
                font-size: 0.85em;
            }
        }

        /* Additional mobile table labels - you'll need to add data-label attributes to td elements in PHP */
        @media (max-width: 768px) {
            td:nth-child(1):before { content: "Title"; }
            td:nth-child(2):before { content: "Type"; }
            td:nth-child(3):before { content: "From"; }
            td:nth-child(4):before { content: "Received"; }
            td:nth-child(5):before { content: "Actions"; }
        }
    </style>
</head>
<body>
    <div class="container">
<a href="home.php"><i class="fa fa-home"></i></a>

        <h1>My Content</h1>

        <div class="content-list">
            <h2>Content from my Mentors</h2>
            <?php if (empty($my_content)): ?>
                <p>You have not received any content yet.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>From</th>
                        <th>Received At</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($my_content as $content): ?>
                        <tr>
                            <td><?= $content['title'] ?></td>
                            <td><?= ucfirst($content['content_type']) ?></td>
                            <td><?= $content['full_name'] ?></td>
                            <td><?= $content['sent_at'] ?></td>
                            <td>
                                <form action="view_content.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $content['student_content_id'] ?>">
                                    <button type="submit" class="view-button">View</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
