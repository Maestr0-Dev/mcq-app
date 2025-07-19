<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalized Quiz</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .quiz-container { max-width: 800px; margin: 0 auto; }
        .quiz-header { background: #f4f4f4; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .question-block { background: #fff; border: 1px solid #ddd; padding: 20px; margin-bottom: 15px; border-radius: 5px; }
        .question-title { font-weight: bold; margin-bottom: 15px; }
        .options { margin: 10px 0; }
        .option { margin: 8px 0; padding: 10px; border: 1px solid #eee; border-radius: 3px; cursor: pointer; }
        .option:hover { background: #f9f9f9; }
        .option.selected { background: #e3f2fd; border-color: #2196f3; }
        .option.correct { background: #e8f5e8; border-color: #4caf50; }
        .option.incorrect { background: #ffebee; border-color: #f44336; }
        .explanation { margin-top: 10px; padding: 10px; background: #f0f8ff; border-radius: 3px; display: none; }
        .submit-btn { background: #007bff; color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .submit-btn:hover { background: #0056b3; }
        .submit-btn:disabled { background: #ccc; cursor: not-allowed; }
        .results { margin-top: 20px; padding: 20px; border-radius: 5px; }
        .score { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .hidden { display: none; }
        .progress { background: #f0f0f0; border-radius: 10px; height: 20px; margin-bottom: 20px; }
        .progress-bar { background: #007bff; height: 100%; border-radius: 10px; transition: width 0.3s; }
    </style>
</head>
<body>
    <div class="quiz-container">
        <?php
        session_start();
        
        if (!isset($_SESSION['generated_quiz'])) {
            echo "<div class='quiz-header'><h2>No quiz found. Please generate a quiz first.</h2></div>";
            exit;
        }
        
        $quiz = $_SESSION['generated_quiz'];
        $subject = $_SESSION['quiz_subject'];
        $level = $_SESSION['quiz_level'];
        $student_id = $_SESSION['student_id'];
        ?>
        
        <div class="quiz-header">
            <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
            <p>Subject: <?php echo htmlspecialchars($subject); ?> (<?php echo htmlspecialchars($level); ?>)</p>
            <div class="progress">
                <div class="progress-bar" id="progressBar" style="width: 0%"></div>
            </div>
        </div>

        <form id="quizForm">
            <?php foreach ($quiz['questions'] as $index => $question): ?>
                <div class="question-block" data-question="<?php echo $index; ?>">
                    <div class="question-title">
                        <?php echo ($index + 1) . ". " . htmlspecialchars($question['question']); ?>
                    </div>
                    
                    <div class="options">
                        <?php foreach ($question['options'] as $optionKey => $optionText): ?>
                            <div class="option" data-option="<?php echo $optionKey; ?>" onclick="selectOption(<?php echo $index; ?>, '<?php echo $optionKey; ?>')">
                                <strong><?php echo $optionKey; ?>:</strong> <?php echo htmlspecialchars($optionText); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="explanation" id="explanation-<?php echo $index; ?>">
                        <strong>Explanation:</strong> <?php echo htmlspecialchars($question['explanation']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <button type="button" class="submit-btn" onclick="submitQuiz()">Submit Quiz</button>
        </form>

        <div class="results hidden" id="results">
            <div class="score" id="scoreDisplay"></div>
            <div id="detailedResults"></div>
            <button class="submit-btn" onclick="saveResults()">Save Results</button>
        </div>
    </div>

    <script>
        const quiz = <?php echo json_encode($quiz); ?>;
        const studentId = <?php echo json_encode($student_id); ?>;
        const subject = <?php echo json_encode($subject); ?>;
        const level = <?php echo json_encode($level); ?>;
        let selectedAnswers = {};
        let quizCompleted = false;

        function selectOption(questionIndex, option) {
            if (quizCompleted) return;
            
            const questionBlock = document.querySelector(`[data-question="${questionIndex}"]`);
            questionBlock.querySelectorAll('.option').forEach(opt => opt.classList.remove('selected'));
            
            questionBlock.querySelector(`[data-option="${option}"]`).classList.add('selected');
            
            selectedAnswers[questionIndex] = option;
            
            updateProgress();
        }

        function updateProgress() {
            const answered = Object.keys(selectedAnswers).length;
            const total = quiz.questions.length;
            const percentage = (answered / total) * 100;
            document.getElementById('progressBar').style.width = percentage + '%';
        }

        function submitQuiz() {
            if (Object.keys(selectedAnswers).length < quiz.questions.length) {
                alert('Please answer all questions before submitting.');
                return;
            }
            
            quizCompleted = true;
            let score = 0;
            let results = [];
            
            quiz.questions.forEach((question, index) => {
                const userAnswer = selectedAnswers[index];
                const correctAnswer = question.correct;
                const isCorrect = userAnswer === correctAnswer;
                
                if (isCorrect) score++;
                
                const questionBlock = document.querySelector(`[data-question="${index}"]`);
                const options = questionBlock.querySelectorAll('.option');
                
                options.forEach(option => {
                    const optionKey = option.getAttribute('data-option');
                    if (optionKey === correctAnswer) {
                        option.classList.add('correct');
                    } else if (optionKey === userAnswer && !isCorrect) {
                        option.classList.add('incorrect');
                    }
                });
                
                document.getElementById(`explanation-${index}`).style.display = 'block';
                
                results.push({
                    question: question.question,
                    userAnswer: userAnswer,
                    correctAnswer: correctAnswer,
                    isCorrect: isCorrect
                });
            });
            
            const percentage = Math.round((score / quiz.questions.length) * 100);
            const status = percentage >= 60 ? 'Passed' : 'Failed';
            
            document.getElementById('scoreDisplay').innerHTML = 
                `Your Score: ${score}/${quiz.questions.length} (${percentage}%) - ${status}`;
            
            document.getElementById('results').classList.remove('hidden');
            document.querySelector('.submit-btn').style.display = 'none';
            
            window.quizResults = {
                score: score,
                total: quiz.questions.length,
                percentage: percentage,
                status: status.toLowerCase(),
                details: results
            };
        }

        function saveResults() {
            if (!window.quizResults) return;
            
            const data = {
                student_id: studentId,
                subject: subject,
                level: level,
                score: window.quizResults.score,
                total_questions: window.quizResults.total,
                percentage: window.quizResults.percentage,
                status: window.quizResults.status,
                quiz_type: 'personalized'
            };
            
            fetch('save_quiz_results.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Results saved successfully!');
                } else {
                    alert('Error saving results: ' + result.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving results');
            });
        }
    </script>
</body>
</html>