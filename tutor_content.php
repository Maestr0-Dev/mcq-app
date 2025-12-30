<?php
session_start();
include 'classes.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: discover.php');
    exit;
}

$db = new DB();
$student_id = $_SESSION['id'];
$teacher_id = (int)$_GET['id'];
$tutor_content = $db->getStudentContentFromTutor($student_id, $teacher_id);
$teacher = $db->getTeacherById($teacher_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content from <?= htmlspecialchars($teacher['full_name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
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
                max-width: 1000px;
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
                padding: 35px 30px;
                margin: 0;
                font-size: 2.2em;
                font-weight: 400;
                text-align: center;
                position: relative;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            h1::before {
                content: '👨‍🏫';
                display: block;
                font-size: 0.6em;
                margin-bottom: 10px;
                opacity: 0.9;
            }

            h1::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 100px;
                height: 3px;
                background: rgba(255, 255, 255, 0.8);
                border-radius: 2px;
            }

            /* Content list section */
            .content-list {
                padding: 35px 30px;
                background: linear-gradient(to bottom, #fafafa, #f5f7fa);
                min-height: 300px;
            }

            /* Empty state message */
            .content-list > p {
                background: white;
                padding: 50px 30px;
                border-radius: 12px;
                text-align: center;
                color: #666;
                font-size: 1.2em;
                border: 2px dashed #ddd;
                margin-top: 20px;
                position: relative;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .content-list > p::before {
                content: '📚';
                display: block;
                font-size: 3em;
                margin-bottom: 15px;
                opacity: 0.3;
            }

            /* Table container for better responsive handling */
            .table-container {
                overflow-x: auto;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                background: white;
            }

            /* Table styles */
            table {
                width: 100%;
                border-collapse: collapse;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                margin-top: 0;
            }

            /* Table header */
            th {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px 25px;
                text-align: left;
                font-weight: 600;
                font-size: 1.05em;
                letter-spacing: 0.5px;
                position: relative;
                border: none;
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

            th:first-child {
                width: 40%;
            }

            th:nth-child(2) {
                width: 20%;
            }

            th:nth-child(3) {
                width: 25%;
            }

            th:last-child {
                width: 15%;
                text-align: center;
            }

            /* Table rows */
            tbody tr {
                transition: all 0.3s ease;
                border-bottom: 1px solid #eee;
            }

            tbody tr:nth-child(even) {
                background: #f8f9fa;
            }

            tbody tr:hover {
                background: linear-gradient(135deg, #667eea12, #764ba212);
                transform: translateX(5px);
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
            }

            tbody tr:last-child {
                border-bottom: none;
            }

            /* Table cells */
            td {
                padding: 20px 25px;
                font-size: 0.95em;
                vertical-align: middle;
                border: none;
            }

            /* Title cell */
            td:first-child {
                font-weight: 600;
                color: #333;
                position: relative;
                padding-left: 35px;
            }

            td:first-child::before {
                content: '📄';
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 1.1em;
                opacity: 0.6;
            }

            /* Type cell with badges */
            td:nth-child(2) {
                font-weight: 500;
                position: relative;
            }

            /* Content type styling */
            td:nth-child(2):contains("Note") {
                color: #28a745;
            }

            td:nth-child(2):contains("Quiz") {
                color: #fd7e14;
            }

            td:nth-child(2):contains("Assignment") {
                color: #dc3545;
            }

            /* Add type badges */
            td:nth-child(2) {
                position: relative;
                padding-left: 35px;
            }

            td:nth-child(2)::before {
                content: '';
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #667eea;
            }

            /* Date cell */
            td:nth-child(3) {
                color: #666;
                font-size: 0.9em;
                font-family: 'Courier New', monospace;
            }

            /* Action cell */
            td:last-child {
                text-align: center;
            }

            /* Action links */
            td a {
                display: inline-block;
                padding: 12px 24px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-decoration: none;
                border-radius: 25px;
                font-size: 0.9em;
                font-weight: 500;
                transition: all 0.3s ease;
                box-shadow: 0 3px 12px rgba(102, 126, 234, 0.25);
                position: relative;
                overflow: hidden;
            }

            td a::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }

            td a:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
                background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            }

            td a:hover::before {
                left: 100%;
            }

            td a:active {
                transform: translateY(-1px);
            }

            /* Add icons to action buttons */
            td a::after {
               
                font-size: 0.9em;
                margin-left: 5px;
            }

            /* Responsive design */
            @media (max-width: 992px) {
                .container {
                    margin: 0 10px;
                }
                
                h1 {
                    font-size: 2em;
                    padding: 30px 25px;
                }
                
                .content-list {
                    padding: 25px 20px;
                }
                
                th, td {
                    padding: 15px 12px;
                    font-size: 0.9em;
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
                    font-size: 1.7em;
                    padding: 25px 20px;
                }
                
                h1::before {
                    font-size: 0.8em;
                    margin-bottom: 8px;
                }
                
                .content-list {
                    padding: 20px 15px;
                }
                
                /* Mobile table transformation */
                table, thead, tbody, th, td, tr {
                    display: block;
                }
                
                thead tr {
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }
                
                tbody tr {
                    border: 1px solid #ddd;
                    margin-bottom: 15px;
                    padding: 0;
                    border-radius: 10px;
                    background: white;
                    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
                    transform: none;
                }
                
                tbody tr:hover {
                    transform: translateY(-2px);
                    background: white;
                }
                
                td {
                    border: none;
                    border-bottom: 1px solid #eee;
                    position: relative;
                    padding: 18px 18px 18px 120px;
                    text-align: left;
                }
                
                td:before {
                    content: attr(data-label);
                    position: absolute;
                    left: 18px;
                    width: 90px;
                    padding-right: 10px;
                    white-space: nowrap;
                    font-weight: 600;
                    color: #667eea;
                    top: 18px;
                }
                
                td:last-child {
                    border-bottom: none;
                    padding-bottom: 20px;
                    text-align: left;
                }
                
                td:first-child::before {
                    display: none;
                }
                
                td a {
                    margin-left: -102px;
                    display: inline-block;
                }
                
                /* Remove emoji prefixes on mobile */
                td:first-child::before,
                td:nth-child(2)::before {
                    display: none;
                }
                
                td:first-child {
                    padding-left: 18px;
                }
                
                td:nth-child(2) {
                    padding-left: 18px;
                }
            }

            @media (max-width: 480px) {
                h1 {
                    font-size: 1.4em;
                    padding: 20px 15px;
                }
                
                .content-list {
                    padding: 15px 10px;
                }
                
                .content-list > p {
                    padding: 35px 20px;
                    font-size: 1.1em;
                }
                
                td {
                    padding: 15px 15px 15px 100px;
                    font-size: 0.9em;
                }
                
                td:before {
                    width: 80px;
                    left: 15px;
                    font-size: 0.85em;
                }
                
                td a {
                    margin-left: -85px;
                    padding: 10px 20px;
                    font-size: 0.85em;
                }
            }

            /* Mobile table labels */
            @media (max-width: 768px) {
                td:nth-child(1):before { content: "Title:"; }
                td:nth-child(2):before { content: "Type:"; }
                td:nth-child(3):before { content: "Received:"; }
                td:nth-child(4):before { content: "Actions:"; }
            }
    </style>
</head>
<body>
    <div class="container">
        <h1>Content from <?= htmlspecialchars($teacher['full_name']) ?></h1>

        <div class="content-list">
            <?php if (empty($tutor_content)): ?>
                <p>This tutor has not sent you any content yet.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Received At</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($tutor_content as $content): ?>
                        <tr>
                            <td><?= htmlspecialchars($content['title']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($content['content_type'])) ?></td>
                            <td><?= htmlspecialchars($content['sent_at']) ?></td>
                            <td>
                                <a href="view_content.php?id=<?= urlencode($content['student_content_id']) ?>">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
