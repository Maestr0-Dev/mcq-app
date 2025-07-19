<?php
session_start();
include 'classes.php';

$db = new DB();
$archives = $db->getArchives();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Archives</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .archive-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .archive-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .archive-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .archive-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .archive-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .archive-info {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        .question-count {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
        }
        .no-archives {
            text-align: center;
            color: #95a5a6;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="archive-container">
<a href="home.php"><i class="fa fa-home"></i></a>

        <h1><i class="fa fa-archive"></i> Question Archives</h1>
        
        <?php if (empty($archives)): ?>
            <div class="no-archives">
                <i class="fa fa-folder-open" style="font-size: 3em; margin-bottom: 20px;"></i>
                <p>No archived questions found.</p>
            </div>
        <?php else: ?>
            <?php foreach ($archives as $archive): ?>
                <div class="archive-card" onclick="viewArchive('<?=$archive['table_name']?>', '<?=$archive['year']?>', '<?=$archive['subject']?>')">
                    <div class="archive-title"><?=$archive['title']?></div>
                    <div class="archive-details">
                        <div class="archive-info">
                            <i class="fa fa-book"></i> <?=$archive['subject']?> • 
                            <i class="fa fa-calendar"></i> <?=$archive['year']?>
                        </div>
                        <div class="question-count"><?=$archive['question_count']?> questions</div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        function viewArchive(table, year, subject) {
            window.location.href = `archive_view.php?table=${table}&year=${year}&subject=${subject}`;
        }
    </script>
</body>
</html>