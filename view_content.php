<?php
session_start();
include 'classes.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: student_content.php');
    exit;
}

$db = new DB();
$student_id = $_SESSION['id'];
$student_content_id = (int)$_POST['id'];

$content = $db->getStudentContentById($student_content_id, $student_id);

if (!$content) {
    header('Location: student_content.php');
    exit;
}

if ($content['status'] === 'unread') {
    $db->updateStudentContentStatus($student_content_id, 'read');
}

if ($content['content_type'] === 'pdf') {
    $file_path = $content['content'];
    if (file_exists($file_path)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        die("Error: The file could not be found. Path: " . htmlspecialchars($file_path));
    }
}

// Simple quiz parser
function parse_quiz($quiz_content) {
    $questions = [];
    $lines = explode("\n", $quiz_content);
    foreach ($lines as $line) {
        if (preg_match('/^Q\d+:\s*(.*)\|(.*)\|(.*)\|(.*)\|(.*)\|(.*)$/', $line, $matches)) {
            $questions[] = [
                'question' => trim($matches[1]),
                'options' => [
                    'A' => trim($matches[2]),
                    'B' => trim($matches[3]),
                    'C' => trim($matches[4]),
                    'D' => trim($matches[5]),
                ],
                'answer' => trim($matches[6])
            ];
        }
    }
    return $questions;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($content['title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>

              
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
            max-width: 800px;
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
            font-size: 2.2em;
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
            width: 60px;
            height: 3px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 2px;
        }

        /* Content metadata */
        .container > p {
            padding: 15px 30px 0;
            margin: 0;
            color: #666;
            font-size: 0.95em;
            border-bottom: none;
        }

        .container > p:last-of-type {
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 0;
        }

        .container > p strong {
            color: #444;
            font-weight: 600;
        }

        /* Content body */
        .content-body {
            padding: 30px;
            background: #fafafa;
        }

        .content-body > p {
            font-size: 1.1em;
            line-height: 1.8;
            color: #444;
            background: white;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Quiz styles */
        .quiz-question {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #667eea;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .quiz-question:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.12);
        }

        .quiz-question p {
            font-size: 1.15em;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .quiz-question ul {
            list-style: none;
            padding: 0;
        }

        .quiz-question li {
            margin-bottom: 12px;
            padding: 0;
        }

        .quiz-question label {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            border-radius: 8px;
            background: #f8f9fa;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1em;
            line-height: 1.5;
        }

        .quiz-question label:hover {
            background: #e3f2fd;
            border-color: #667eea;
            transform: translateX(5px);
        }

        .quiz-question input[type="radio"] {
            margin-right: 12px;
            margin-top: 3px;
            width: 18px;
            height: 18px;
            accent-color: #667eea;
            cursor: pointer;
            flex-shrink: 0;
        }

        .quiz-question input[type="radio"]:checked + span {
            color: #667eea;
            font-weight: 600;
        }

        .quiz-question label:has(input[type="radio"]:checked) {
            background: linear-gradient(135deg, #667eea15, #764ba215);
            border-color: #667eea;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
        }

        /* Submit button */
        button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 25px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        /* Form styles */
        form {
            margin: 0;
        }

        input[type="hidden"] {
            display: none;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                border-radius: 8px;
            }
            
            h1 {
                padding: 20px;
                font-size: 1.8em;
            }
            
            .container > p {
                padding: 10px 20px 0;
            }
            
            .container > p:last-of-type {
                padding-bottom: 15px;
            }
            
            .content-body {
                padding: 20px;
            }
            
            .content-body > p {
                padding: 20px;
            }
            
            .quiz-question {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .quiz-question p {
                font-size: 1.05em;
            }
            
            .quiz-question label {
                padding: 12px;
            }
            
            button[type="submit"] {
                padding: 12px 30px;
                font-size: 1em;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5em;
                padding: 15px;
            }
            
            .content-body {
                padding: 15px;
            }
            
            .quiz-question {
                padding: 15px;
            }
            
            .quiz-question label {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .quiz-question input[type="radio"] {
                margin-bottom: 8px;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($content['title']) ?></h1>
        <p><strong>From:</strong> <?= htmlspecialchars($content['full_name']) ?></p>
        <p><strong>Type:</strong> <?= ucfirst(htmlspecialchars($content['content_type'])) ?></p>
        <div class="content-body">
            <?php if ($content['content_type'] === 'note'): ?>
                <p><?= nl2br(htmlspecialchars($content['content'])) ?></p>
            <?php elseif ($content['content_type'] === 'quiz'): ?>
                <form action="submit_teacher_quiz.php" method="POST">
                    <input type="hidden" name="student_content_id" value="<?= $student_content_id ?>">
                    <?php $questions = parse_quiz($content['content']); ?>
                    <?php foreach ($questions as $i => $q): ?>
                        <div class="quiz-question">
                            <p><strong>Question <?= $i + 1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></p>
                            <ul>
                                <?php foreach ($q['options'] as $key => $option): ?>
                                    <li>
                                        <label>
                                            <input type="radio" name="answers[<?= $i ?>]" value="<?= $key ?>" required>
                                            <?= htmlspecialchars($option) ?>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit">Submit Quiz</button>
                </form>
            <?php elseif ($content['content_type'] === 'pdf'): ?>
                <p>
                    Your PDF should be displayed above. If not, you can <a href="<?= htmlspecialchars($content['content']) ?>" download>download it directly</a>.
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
