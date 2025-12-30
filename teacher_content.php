<?php
session_start();
include 'classes.php';

if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php');
    exit;
}

$db = new DB();
$teacher_id = $_SESSION['teacher_id'];
$my_content = $db->getTeacherContent($teacher_id);
$mentees = $db->getMenteesForTeacher($teacher_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_content'])) {
    $content_type = $_POST['content_type'];
    $title = htmlspecialchars($_POST['title']);
    $content = '';

    if ($content_type === 'pdf') {
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['pdf_file'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($file_ext !== 'pdf') {
                $error = "Only PDF files are allowed.";
            } else {
                $upload_dir = 'uploads/pdfs/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $file_name = uniqid('', true) . '.' . $file_ext;
                $file_path = $upload_dir . $file_name;

                if (move_uploaded_file($file['tmp_name'], $file_path)) {
                    $content = $file_path;
                } else {
                    $error = "Failed to upload file.";
                }
            }
        } else {
            $error = "Please select a PDF file to upload.";
        }
    } else {
        $content = htmlspecialchars($_POST['content']);
    }

    if (!isset($error) && !empty($title) && (!empty($content) || $content_type === 'pdf')) {
        if ($db->createTeacherContent($teacher_id, $content_type, $title, $content)) {
            header('Location: teacher_content.php');
            exit;
        } else {
            $error = "Failed to create content.";
        }
    } elseif (!isset($error)) {
        $error = "Title and content/PDF are required.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_content'])) {
    $content_id = $_POST['content_id'];
    $student_id = $_POST['student_id'];
    
    if ($db->sendContentToStudent($content_id, $student_id, $teacher_id)) {
        $success = "Content sent successfully.";
    } else {
        $error = "Failed to send content.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Content</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: white;
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1 {
            font-size: 36px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 600;
            position: relative;
            padding-left: 20px;
        }

        h2::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .content-creation {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .content-creation form {
            display: grid;
            gap: 20px;
        }

        .content-creation select,
        .content-creation input,
        .content-creation textarea {
            padding: 15px 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px;
            font-size: 16px;
            font-family: inherit;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
            outline: none;
        }

        .content-creation select:focus,
        .content-creation input:focus,
        .content-creation textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .content-creation textarea {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
        }

        .content-creation button {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
            justify-self: center;
            width: fit-content;
        }

        .content-creation button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }

        .content-creation button:active {
            transform: translateY(-1px);
        }

        .content-list {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .content-list p {
            text-align: center;
            color: #7f8c8d;
            font-size: 16px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 12px;
            border: 2px dashed rgba(102, 126, 234, 0.3);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        table th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table th:first-child {
            border-top-left-radius: 12px;
        }

        table th:last-child {
            border-top-right-radius: 12px;
        }

        table td {
            padding: 18px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        table tr:hover td {
            background: rgba(102, 126, 234, 0.05);
            transform: translateY(-1px);
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        table tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        table td form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        table td select {
            padding: 8px 12px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 8px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.9);
            outline: none;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        table td select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        table td button {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 12px rgba(52, 152, 219, 0.3);
            white-space: nowrap;
        }

        table td button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 16px rgba(52, 152, 219, 0.4);
        }

        table td button:active {
            transform: translateY(0);
        }

        .error {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-top: 15px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
            border: none;
        }

        .success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-top: 15px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
            border: none;
        }

        /* Content type badges */
        table td:nth-child(2) {
            font-weight: 600;
            text-transform: capitalize;
        }

        table td:nth-child(2)::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
            background: #667eea;
        }

        /* Date styling */
        table td:nth-child(3) {
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            h2 {
                font-size: 20px;
            }
            
            .content-creation,
            .content-list {
                padding: 20px;
            }
            
            table {
                font-size: 14px;
            }
            
            table th,
            table td {
                padding: 12px 8px;
            }
            
            table td form {
                flex-direction: column;
                gap: 8px;
                align-items: stretch;
            }
            
            table td select {
                min-width: auto;
            }
        }

        @media (max-width: 480px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .container {
                padding: 15px;
            }
            
            h1 {
                font-size: 24px;
            }
        }

    </style>

</head>
<body>
    <div class="container">
        <h1>My Content</h1>

        <div class="content-creation">
            <h2>Create New Content</h2>
            <form action="teacher_content.php" method="POST" enctype="multipart/form-data">
                <select name="content_type" id="contentType" required onchange="toggleContentFields()">
                    <option value="note">Note</option>
                    <option value="quiz">Quiz</option>
                    <option value="pdf">PDF</option>
                </select>
                <input type="text" name="title" placeholder="Content Title" required>
                <textarea name="content" id="contentText" placeholder="Content (for quizzes, use a simple format like Q1: Question|A|B|C|D|Answer)" required></textarea>
                <input type="file" name="pdf_file" id="pdfFile" style="display: none;" accept=".pdf">
                <button type="submit" name="create_content">Create Content</button>
            </form>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </div>

        <div class="content-list">
            <h2>My Created Content</h2>
            <?php if (empty($my_content)): ?>
                <p>You have not created any content yet.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($my_content as $content): ?>
                        <tr>
                            <td><?= $content['title'] ?></td>
                            <td><?= ucfirst($content['content_type']) ?></td>
                            <td><?= $content['created_at'] ?></td>
                            <td>
                                <form action="teacher_content.php" method="POST">
                                    <input type="hidden" name="content_id" value="<?= $content['content_id'] ?>">
                                    <select name="student_id" required>
                                        <option value="">Select a student</option>
                                        <?php foreach ($mentees as $mentee): ?>
                                            <option value="<?= $mentee['stud_id'] ?>"><?= $mentee['stud_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="send_content">Send</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        </div>
    </div>
    <script>
        function toggleContentFields() {
            const contentType = document.getElementById('contentType').value;
            const contentText = document.getElementById('contentText');
            const pdfFile = document.getElementById('pdfFile');

            if (contentType === 'pdf') {
                contentText.style.display = 'none';
                contentText.required = false;
                pdfFile.style.display = 'block';
                pdfFile.required = true;
            } else {
                contentText.style.display = 'block';
                contentText.required = true;
                pdfFile.style.display = 'none';
                pdfFile.required = false;
            }
        }
        // Call it on page load to set the initial state
        window.onload = toggleContentFields;
    </script>
</body>
</html>
