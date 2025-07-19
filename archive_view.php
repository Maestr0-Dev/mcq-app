<?php
session_start();
include 'classes.php';

$table = $_GET['table'] ?? '';
$year = $_GET['year'] ?? '';
$subject = $_GET['subject'] ?? '';

if (!$table || !$year || !$subject) {
    header('Location: archive.php');
    exit;
}

$db = new DB();
$questions = $db->Get($table, [$year, $subject]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive: <?=$subject?> <?=$year?></title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .archive-view-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }
        .archive-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .question-card {
            border: 1px solid #e3e6f0;
            border-radius: 10px;
            margin: 20px 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .question-header {
            background: #f8f9fc;
            padding: 15px 20px;
            border-bottom: 1px solid #e3e6f0;
            border-radius: 10px 10px 0 0;
        }
        .question-body {
            padding: 20px;
        }
        .question-text {
            font-weight: bold;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .option {
            padding: 8px 0;
            margin: 5px 0;
        }
        .correct-answer {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            margin-top: 15px;
        }
        .question-image {
            max-width: 100%;
            height: auto;
            margin: 15px 0;
            border-radius: 5px;
        }
        .back-btn {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="archive-view-container">
        <a href="archive.php" class="back-btn">
            <i class="fa fa-arrow-left"></i> Back to Archives
        </a>

        <div class="archive-header">
            <h1><i class="fa fa-book"></i> <?=$subject?></h1>
            <p>Year: <?=$year?> | Questions: <?=count($questions)?></p>
        </div>

        <?php if (empty($questions)): ?>
            <div class="text-center" style="padding: 40px; color: #6c757d;">
                <i class="fa fa-exclamation-triangle" style="font-size: 3em; margin-bottom: 20px;"></i>
                <p>No questions found for this archive.</p>
            </div>
        <?php else: ?>
            <?php foreach ($questions as $index => $q): ?>
                <div class="question-card">
                    <div class="question-header">
                        <strong>Question <?=$index + 1?></strong>
                        <?php if (!empty($q['instructions'])): ?>
                            <div style="margin-top: 10px; font-style: italic; color: #6c757d;">
                                <?=$q['instructions']?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="question-body">
                        <div class="question-text"><?=$q['question']?></div>
                        
                        <?php if (!empty($q['img'])): ?>
                            <img src="diagrams/<?=$q['img']?>" class="question-image" alt="Question diagram">
                        <?php endif; ?>
                        
                        <div class="options">
                            <div class="option"><strong>A.</strong> <?=$q['A']?></div>
                            <div class="option"><strong>B.</strong> <?=$q['B']?></div>
                            <div class="option"><strong>C.</strong> <?=$q['C']?></div>
                            <div class="option"><strong>D.</strong> <?=$q['D']?></div>
                        </div>
                        
                        <div class="correct-answer">
                            <i class="fa fa-check-circle"></i> <strong>Correct Answer:</strong> <?=$q['ans']?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>