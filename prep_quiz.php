<?php
session_start();
require_once 'classes.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? 0;

if (empty($quiz_id)) {
    die("No quiz selected.");
}

require_once 'classes.php';
$db = new DB();
try {
    $conn = new PDO("mysql:host=".$db->host().";dbname=".$db->DBname(), $db->username(), $db->pass());
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT quiz_data FROM prep_quizzes WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$quiz_id]);
    $quiz_json = $stmt->fetchColumn();

    if (!$quiz_json) {
        die("Quiz not found.");
    }

    $quiz = json_decode($quiz_json, true);

} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quiz['title']); ?></title>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
  
<!-- <style>

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #334155;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
        }

        h1 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
            text-align: center;
            font-size: 2.5rem;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(45deg, #6366f1, #8b5cf6);
            border-radius: 2px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
            overflow: hidden;
            margin: 1.5rem 0;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
            padding: 1.25rem;
            font-weight: 600;
            color: #4338ca;
            font-size: 1.1rem;
            letter-spacing: 0.025em;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .form-check {
            margin-bottom: 0.75rem;
            padding: 0.75rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .form-check:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
            transform: translateX(4px);
        }

        .form-check-input {
            margin-top: 0.25rem;
            margin-right: 0.75rem;
            width: 1.2rem;
            height: 1.2rem;
            border: 2px solid #d1d5db;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }

        .form-check-label {
            font-weight: 400;
            color: #475569;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1.6;
        }

        .form-check:has(.form-check-input:checked) {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            border-left: 4px solid #6366f1;
            padding-left: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 3rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            font-size: 1.1rem;
            display: block;
            margin: 2rem auto;
            min-width: 200px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            background: linear-gradient(135deg, #5855f7 0%, #7c3aed 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(100, 116, 139, 0.4);
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            color: white;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1.25rem;
            font-weight: 500;
            margin-top: 1rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(22, 163, 74, 0.15) 100%);
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%);
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%);
            color: #4338ca;
            border-left: 4px solid #6366f1;
            text-align: center;
        }

        .alert h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .border-success {
            border-left: 6px solid #22c55e !important;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.05) 0%, rgba(22, 163, 74, 0.05) 100%);
        }

        .border-danger {
            border-left: 6px solid #ef4444 !important;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, rgba(220, 38, 38, 0.05) 100%);
        }

        #quiz-result {
            text-align: center;
            padding: 2rem;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: slideInUp 0.6s ease-out;
        }

        .card:nth-child(even) {
            animation-delay: 0.1s;
        }

        .card:nth-child(odd) {
            animation-delay: 0.2s;
        }

        /* Loading States */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .alert-info:has-text("Loading") {
            animation: pulse 2s infinite;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            h1 {
                font-size: 2rem;
                margin-bottom: 1.5rem;
            }
            
            .card {
                margin: 1rem 0;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .card-text {
                font-size: 1rem;
            }
            
            .btn-primary {
                width: 100%;
                padding: 0.75rem 2rem;
                font-size: 1rem;
            }
            
            .form-check {
                padding: 0.5rem;
            }
            
            .form-check-label {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.75rem;
            }
            
            .card-header {
                padding: 1rem;
                font-size: 1rem;
            }
            
            .card-text {
                font-size: 0.95rem;
                margin-bottom: 1rem;
            }
            
            .alert {
                padding: 1rem;
            }
        }

        *:focus-visible {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
        }

        * {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5855f7 0%, #7c3aed 100%);
        }

</style> -->

</head>
<body>
    <div class="container mt-5">
        <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
        <form id="quiz-form">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            <?php foreach ($quiz['questions'] as $index => $question): ?>
                <div class="card my-3" id="question-card-<?php echo $index; ?>">
                    <div class="card-header">
                        Question <?php echo $index + 1; ?>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo htmlspecialchars($question['question']); ?></p>
                        <?php foreach ($question['options'] as $key => $option): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="question_<?php echo $index; ?>" id="q<?php echo $index; ?>_<?php echo $key; ?>" value="<?php echo $key; ?>">
                                <label class="form-check-label" for="q<?php echo $index; ?>_<?php echo $key; ?>">
                                    <?php echo htmlspecialchars($option); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <div class="mt-3" id="result-<?php echo $index; ?>" style="display: none;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Submit Quiz</button>
        </form>
        <div id="quiz-result" class="mt-4"></div>
    </div>

    <script>
        document.getElementById('quiz-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const quizResultDiv = document.getElementById('quiz-result');

            fetch('submit_prep_quiz.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let score = 0;
                    data.results.forEach((result, index) => {
                        const questionCard = document.getElementById('question-card-' + index);
                        const resultDiv = document.getElementById('result-' + index);
                        
                        if (result.is_correct) {
                            score++;
                            questionCard.classList.add('border-success');
                            resultDiv.innerHTML = `<div class="alert alert-success"><strong>Correct!</strong> ${result.explanation}</div>`;
                        } else {
                            questionCard.classList.add('border-danger');
                            resultDiv.innerHTML = `<div class="alert alert-danger"><strong>Incorrect.</strong> The correct answer was ${result.correct_answer}.<br>${result.explanation}</div>`;
                        }
                        resultDiv.style.display = 'block';
                    });

                    quizResultDiv.innerHTML = `<div class="alert alert-info"><h3>You scored ${score} out of ${data.results.length}</h3></div>`;
                    
                    // Change the submit button
                    const submitButton = form.querySelector('button[type="submit"]');
                    submitButton.style.display = 'none';
                    
                    const viewPlansButton = document.createElement('a');
                    viewPlansButton.href = 'preps.php';
                    viewPlansButton.className = 'btn btn-secondary mt-3';
                    viewPlansButton.textContent = 'View Plans';
                    quizResultDiv.appendChild(viewPlansButton);

                } else {
                    quizResultDiv.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
                }
            })
            .catch(error => {
                quizResultDiv.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
