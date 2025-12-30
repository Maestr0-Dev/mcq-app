<?php
session_start();
include '../classes.php';

if (!isset($_SESSION['admin_logged_in']) || !in_array($_SESSION['admin_level'], [1, 2, 3])) {
    header("location:" . BASE_URL . "admin/login.php");
    exit();
}

if (!isset($_GET['table'], $_GET['year'], $_GET['subject'])) {
    header("Location: view_questions.php");
    exit();
}
$db = new DB();
if (isset($_GET['action']) && $_GET['action'] === 'delete_question' && isset($_GET['table']) && isset($_GET['id'])) {
    $db->deleteQuestion($_GET['table'], $_GET['id']);
    $_SESSION['success_message'] = 'Question deleted successfully!';
    $url = "edit_questions.php?table=" . urlencode($_GET['table']) . "&year=" . urlencode($_GET['year']) . "&subject=" . urlencode($_GET['subject']);
    header("Location: " . $url);
    exit();
}

$db = new DB();
$table = $_GET['table'];
$year = $_GET['year'];
$subject = $_GET['subject'];

$questions = $db->Get($table, [$year, $subject]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['questions'] as $id => $data) {
        $db->updateQuestion($table, $id, $data);
    }
    $_SESSION['success_message'] = 'Questions updated successfully!';
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Questions</title>
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
    <script>
    MathJax = {
      tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']]
      }
    };
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <style>
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .question-editor {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
        }
        .delete-icon {
            color: red;
            cursor: pointer;
            position: absolute;
            top: 15px;
            right: 15px;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
        .save-btn {
            background: linear-gradient(to left, purple, blue);
            padding: 0.8rem 1.5rem;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color:white;">Edit Questions</h1>
            <p>Editing questions for: <?php echo htmlspecialchars($subject) . ' ' . htmlspecialchars($year); ?></p>
        </div>

        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
            unset($_SESSION['success_message']);
        }
        ?>

        <form method="post">
            <?php foreach ($questions as $index => $q): ?>
                <div class="question-editor">
                    <a href="edit_questions.php?action=delete_question&table=<?php echo urlencode($table); ?>&year=<?php echo urlencode($year); ?>&subject=<?php echo urlencode($subject); ?>&id=<?php echo $q['id']; ?>" class="delete-icon" onclick="return confirm('Are you sure you want to delete this question?');">🗑️</a>
                    <h4>Question <?php echo $index + 1; ?></h4>
                    <textarea name="questions[<?php echo $q['id']; ?>][question]"><?php echo htmlspecialchars($q['question']); ?></textarea>
                    <p>A: <input type="text" name="questions[<?php echo $q['id']; ?>][A]" value="<?php echo htmlspecialchars($q['A']); ?>"></p>
                    <p>B: <input type="text" name="questions[<?php echo $q['id']; ?>][B]" value="<?php echo htmlspecialchars($q['B']); ?>"></p>
                    <p>C: <input type="text" name="questions[<?php echo $q['id']; ?>][C]" value="<?php echo htmlspecialchars($q['C']); ?>"></p>
                    <p>D: <input type="text" name="questions[<?php echo $q['id']; ?>][D]" value="<?php echo htmlspecialchars($q['D']); ?>"></p>
                    <p>Answer: <input type="text" name="questions[<?php echo $q['id']; ?>][ans]" value="<?php echo htmlspecialchars($q['ans']); ?>"></p>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="save-btn">Save Changes</button>
        </form>
        <a href="view_questions.php"><button class="save-btn" style="background: #777;">Back to Quiz List</button></a>
    </div>
</body>
</html>
