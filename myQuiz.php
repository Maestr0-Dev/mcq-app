<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Quizzes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            padding: 20px;
        }
        
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .back-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-right: 15px;
            box-shadow: 0 4px 6px rgba(99, 102, 241, 0.2);
            transition: transform 0.2s;
        }
        
        .back-btn:hover {
            transform: translateX(-3px);
        }
        
        h1 {
            font-size: 28px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .teacher-section {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .teacher-name {
            font-size: 20px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .quiz-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 15px;
        }
        
        .quiz-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .quiz-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }
        
        .quiz-title {
            font-weight: 500;
            color: #334155;
        }
        
        .start-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        
        .start-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="home.php" class="back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"></path>
                <path d="M12 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1>My Quizzes</h1>
    </div>
    
    <div class="teacher-section">
        <h2 class="teacher-name">Mr. Simplice</h2>
        <div class="quiz-list">
            <div class="quiz-item">
                <span class="quiz-title">Math Quiz: Algebra Basics</span>
                <button class="start-btn">Start</button>
            </div>
            <div class="quiz-item">
                <span class="quiz-title">Math Quiz: Geometry</span>
                <button class="start-btn">Start</button>
            </div>
            <div class="quiz-item">
                <span class="quiz-title">Physics: Mechanics</span>
                <button class="start-btn">Start</button>
            </div>
            <div class="quiz-item">
                <span class="quiz-title">Physics: Electricity</span>
                <button class="start-btn">Start</button>
            </div>
        </div>
    </div>
    
    <div class="teacher-section">
        <h2 class="teacher-name">Mme. Mikasa</h2>
        <div class="quiz-list">
            <div class="quiz-item">
                <span class="quiz-title">Literature: Shakespeare</span>
                <button class="start-btn">Start</button>
            </div>
            <div class="quiz-item">
                <span class="quiz-title">French: Past Tenses</span>
                <button class="start-btn">Start</button>
            </div>
            <div class="quiz-item">
                <span class="quiz-title">French: Vocabulary Test</span>
                <button class="start-btn">Start</button>
            </div>
            <div class="quiz-item">
                <span class="quiz-title">History: World War II</span>
                <button class="start-btn">Start</button>
            </div>
        </div>
    </div>
</body>
</html>