<?php
session_start();
include 'classes.php';

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$stud_id = $_SESSION['id'];

// Handle AJAX requests
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    header('Content-Type: application/json');
    
    $action = $_GET['action'] ?? 'test';
    $performance = new Performance();
    
    switch($action) {
        case 'subjects':
            $result = $performance->getSubjects($stud_id);
            break;
            
        case 'performance':
            $subject = $_GET['subject'] ?? '';
            $level = $_GET['level'] ?? '';
            $result = $performance->getPerformanceData($stud_id, $subject, $level);
            break;
            
        case 'summary':
            $result = $performance->getSummaryStats($stud_id);
            break;
            
        case 'recent':
            $limit = $_GET['limit'] ?? 10;
            $result = $performance->getRecentPerformances($stud_id, $limit);
            break;
            
        case 'test':
            try {
                $testStats = $performance->getSummaryStats($stud_id);
                $result = [
                    'success' => true, 
                    'message' => 'Performance data API is working',
                    'user_id' => $stud_id,
                    'available_actions' => ['subjects', 'performance', 'summary', 'recent', 'test'],
                    'current_time' => date('Y-m-d H:i:s'),
                    'sample_data' => $testStats
                ];
            } catch(Exception $e) {
                $result = [
                    'success' => false,
                    'error' => 'Test failed: ' . $e->getMessage(),
                    'user_id' => $stud_id
                ];
            }
            break;
            
        default:
            $result = [
                'success' => false, 
                'error' => 'Invalid action. Available actions: subjects, performance, summary, recent, test',
                'requested_action' => $action
            ];
    }
    
    echo json_encode($result, JSON_PRETTY_PRINT);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_ai_response') {
    header('Content-Type: application/json');
    
    $message = $_POST['message'] ?? '';
    
    
    if (empty($message)) {
        echo json_encode(['error' => 'Message is required']);
        exit();
    }
    
    $apiKey = 'AIzaSyAcQR-u0L_189b-I0rrWb7qi-NyIg0SOoc';
    
    $systemMessage = 
        "You are Braze AI, a helpful assistant. You respond to user queries in a short, simple, friendly and informative manner, waiting for user instructions before taking action.And you do all that in one paragraph";
    
    $fullMessage = $systemMessage . "\n\nUser: " . $message;
    
    $data = [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => $fullMessage
                    ]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 500,
        ],
        'safetySettings' => [
            [
                'category' => 'HARM_CATEGORY_HARASSMENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_HATE_SPEECH',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ]
        ]
    ];
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
        CURLOPT_TIMEOUT => 30
    ]);
    
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if (curl_errno($curl)) {
        echo json_encode(['error' => 'Connection error: ' . curl_error($curl)]);
        curl_close($curl);
        exit();
    }
    
    curl_close($curl);
    
    if ($httpCode !== 200) {
        echo json_encode(['error' => 'API request failed with status: ' . $httpCode . '. Response: ' . $response]);
        exit();
    }
    
    $result = json_decode($response, true);
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        echo json_encode(['response' => $result['candidates'][0]['content']['parts'][0]['text']]);
    } else {
        echo json_encode(['error' => 'Invalid API response: ' . $response]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Dashboard</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        .performance-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .subject-selector {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .subject-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .subject-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .subject-card:hover {
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,123,255,0.2);
        }
        
        .subject-card.active {
            border-color: #28a745;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .subject-card h5 {
            margin: 0 0 10px 0;
            font-size: 1.1rem;
        }
        
        .subject-stats {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: none;
        }
        
        .chart-container.active {
            display: block;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .chart-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        
        .chart-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            min-width: 80px;
        }
        
        .stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .chart-wrapper {
            position: relative;
            height: 400px;
            margin-top: 20px;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .loading i {
            font-size: 2rem;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .nav-buttons {
            display: flex;
            gap:20px;
            margin-bottom: 20px;
        }
        
        .nav-btn {
           color:white;
           border:none;
           border-radius:10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-decoration:none;

        }
        
        .nav-btn:hover {
            background: #0056b3;
            transform: translateY(-1px);
            text-decoration: none;
            color: white;
        }
        
        .performance-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .summary-item {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        
        .summary-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
        }
        
        .recent-tests {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .recent-test-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .recent-test-item:last-child {
            border-bottom: none;
        }
        
        .test-info {
            flex: 1;
        }
        
        .test-subject {
            font-weight: bold;
            color: #333;
        }
        
        .test-date {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .test-score {
            font-size: 1.2rem;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
        }
        
        .test-passed {
            background: #d4edda;
            color: #155724;
        }
        
        .test-failed {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .chart-stats {
                flex-direction: column;
                gap: 10px;
            }
            
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .nav-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="performance-container">
        <div class="nav-buttons">
            <a href="home.php" class="nav-btn">
                <i class="fa fa-home"></i>
                
            </a>
            <a href="quest_selection.php" class="nav-btn">
                <i class="fa fa-question-circle"></i>
            </a>
            <a class="nav-btn" onclick="refreshData()">
                <i class="fa fa-refresh"></i>
    </a>
        </div>
        
        <div class="performance-summary">
            <h2><i class="fa fa-chart-line"></i> Performance Dashboard</h2>
            <p>Track your progress and analyze your performance trends</p>
            <div class="summary-grid" id="summaryGrid">
                <div class="summary-item">
                    <div class="summary-value" id="totalAttempts">0</div>
                    <div class="summary-label">Total Attempts</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value" id="averageScore">0%</div>
                    <div class="summary-label">Average Score</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value" id="passRate">0%</div>
                    <div class="summary-label">Pass Rate</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value" id="totalSubjects">0</div>
                    <div class="summary-label">Subjects</div>
                </div>
            </div>
        </div>
        
        <!-- <div class="recent-tests" id="recentTests">
            <h3><i class="fa fa-clock"></i> Recent Test Results</h3>
            <div id="recentTestsList">
                <div class="loading">
                    <i class="fa fa-spinner"></i>
                    <p>Loading recent tests...</p>
                </div>
            </div>
        </div> -->
        
        <div class="subject-selector">
            <h3><i class="fa fa-book"></i> Select Subject to View Performance</h3>
            <p>Choose a subject to see your detailed performance over time</p>
            <div class="subject-grid" id="subjectGrid">
                <div class="loading">
                    <i class="fa fa-spinner"></i>
                    <p>Loading subjects...</p>
                </div>
            </div>
        </div>
        
        <div class="chart-container" id="chartContainer">
            <div class="chart-header">
                <div class="chart-title" id="chartTitle">Subject Performance</div>
                <div class="chart-stats" id="chartStats">
                    <!-- Stats will be populated here -->
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
        
        <div class="no-data" id="noDataMessage" style="display: none;">
            <i class="fa fa-chart-line"></i>
            <h4>No Performance Data</h4>
            <p>You haven't taken any tests yet. Start by taking a quiz to see your performance here.</p>
            <a href="quest_selection.php" class="nav-btn">
                <i class="fa fa-play"></i>
                Take Your First Quiz
            </a>
        </div>
    </div>

</div>
</div>



  <!-- Add this button to your performance chart page -->
<button id="generateQuizBtn" onclick="generatePersonalizedQuiz()" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 10px 0;">
    Generate Personalized Quiz
</button>

<div id="quizStatus" style="margin: 10px 0; padding: 10px; border-radius: 5px; display: none;"></div>

<script>
function generatePersonalizedQuiz() {

    const studentId = getCurrentStudentId(); 
    const subject = getCurrentSubject();     
    const level = getCurrentLevel();         
    
    if (!studentId || !subject) {
        showStatus('Please select a subject first.', 'error');
        return;
    }
    
    showStatus('Generating personalized quiz...', 'loading');
    
    const formData = new FormData();
    formData.append('stud_id', studentId);
    formData.append('subject', subject);
    formData.append('level', level);
    
    fetch('generate_quiz.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showStatus('Quiz generated successfully! Opening quiz page...', 'success');
            setTimeout(() => {
                window.location.href = 'quiz_page.php';
            }, 1000);
        } else {
            showStatus('Error generatingg quiz: ' + result.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showStatus('Error generating quiz. Please try again.', 'error');
    });
}

function showStatus(message, type) {
    const statusDiv = document.getElementById('quizStatus');
    statusDiv.textContent = message;
    statusDiv.style.display = 'block';
    
    // Style based on type
    if (type === 'success') {
        statusDiv.style.background = '#d4edda';
        statusDiv.style.color = '#155724';
        statusDiv.style.border = '1px solid #c3e6cb';
    } else if (type === 'error') {
        statusDiv.style.background = '#f8d7da';
        statusDiv.style.color = '#721c24';
        statusDiv.style.border = '1px solid #f5c6cb';
    } else if (type === 'loading') {
        statusDiv.style.background = '#d1ecf1';
        statusDiv.style.color = '#0c5460';
        statusDiv.style.border = '1px solid #bee5eb';
    }
}

//funtions still to be implemented.. just use mock data for now bruh
function getCurrentStudentId() {
   
    return 4; 
}

function getCurrentSubject() {
    
    return 'Physics'; 
}
function getCurrentLevel() {
   
    return 'A'; 
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////

        let currentChart = null;
        let performanceData = {};
        let subjectData = {};
        
        document.addEventListener('DOMContentLoaded', function() {
            loadInitialData();
        });
        
        function loadInitialData() {
            loadSubjects();
            loadSummaryStats();
            loadRecentTests();
        }
        
        function refreshData() {
            showMessage('Refreshing data...', 'info');
            loadInitialData();
        }
        
        function showMessage(message, type = 'info') {
            const messageDiv = document.createElement('div');
            messageDiv.className = type === 'error' ? 'error-message' : 'success-message';
            messageDiv.textContent = message;
            
            const container = document.querySelector('.performance-container');
            container.insertBefore(messageDiv, container.firstChild);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }
        
        function loadSubjects() {
            fetch('?ajax=1&action=subjects')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displaySubjects(data.subjects);
                        if (data.subjects.length === 0) {
                            document.getElementById('noDataMessage').style.display = 'block';
                        }
                    } else {
                        console.error('Error loading subjects:', data.error);
                        showMessage('Error loading subjects: ' + data.error, 'error');
                        document.getElementById('subjectGrid').innerHTML = '<p>Error loading subjects</p>';
                        

                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Network error loading subjects', 'error');
                    document.getElementById('subjectGrid').innerHTML = '<p>Error loading subjects</p>';
                });
        }
        
        function loadSummaryStats() {
            fetch('?ajax=1&action=summary')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateSummaryStats(data.stats);
                    } else {
                        console.error('Error loading summary stats:', data.error);
                        showMessage('Error loading summary stats: ' + data.error, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading summary stats:', error);
                    showMessage('Network error loading summary stats', 'error');
                });
        }
        
        function loadRecentTests() {
            fetch('?ajax=1&action=recent&limit=5')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRecentTests(data.performances);
                    } else {
                        console.error('Error loading recent tests:', data.error);
                        document.getElementById('recentTestsList').innerHTML = '<p>Error loading recent tests</p>';
                    }
                })
                .catch(error => {
                    console.error('Error loading recent tests:', error);
                    document.getElementById('recentTestsList').innerHTML = '<p>Error loading recent tests</p>';
                });
        }
        
        function displayRecentTests(tests) {
            const recentTestsList = document.getElementById('recentTestsList');
            
            if (tests.length === 0) {
                recentTestsList.innerHTML = '<p>No recent tests found</p>';
                return;
            }
            
            recentTestsList.innerHTML = tests.map(test => `
                <div class="recent-test-item">
                    <div class="test-info">
                        <div class="test-subject">${test.subject} (${test.level}-level)</div>
                        <div class="test-date">${new Date(test.date_taken).toLocaleDateString()} ${new Date(test.date_taken).toLocaleTimeString()}</div>
                    </div>
                    <div class="test-score ${test.status === 'passed' ? 'test-passed' : 'test-failed'}">
                        ${test.score}/20 (${test.percentage.toFixed(1)}%)
                    </div>
                </div>
            `).join('');
        }
        
        function updateSummaryStats(stats) {
            document.getElementById('totalAttempts').textContent = stats.total_attempts || 0;
            document.getElementById('averageScore').textContent = (stats.average_score || 0) + '%';
            document.getElementById('passRate').textContent = (stats.pass_rate || 0) + '%';
            document.getElementById('totalSubjects').textContent = stats.total_subjects || 0;
        }
        
        function displaySubjects(subjects) {
            const subjectGrid = document.getElementById('subjectGrid');
            
            if (subjects.length === 0) {
                subjectGrid.innerHTML = '<p>No subjects found. Take a quiz to see your performance data.</p>';
                return;
            }
            
            subjectGrid.innerHTML = subjects.map(subject => `
                <div class="subject-card" onclick="selectSubject('${subject.subject}', '${subject.level}')">
                    <h5>${subject.display_name || subject.subject}</h5>
                    <div class="subject-stats">
                        <div>Tests: ${subject.test_count}</div>
                        <div>Avg: ${subject.average_score}%</div>
                        <div>Last: ${new Date(subject.last_test).toLocaleDateString()}</div>
                    </div>
                </div>
            `).join('');
        }
        
        function selectSubject(subject, level) {
            // Remove active class from all cards
            document.querySelectorAll('.subject-card').forEach(card => {
                card.classList.remove('active');
            });
            
            // Add active class to selected card
            event.target.closest('.subject-card').classList.add('active');
            
            // Load performance data for this subject
            loadPerformanceData(subject, level);
        }
        
        function loadPerformanceData(subject, level) {
            const chartContainer = document.getElementById('chartContainer');
            chartContainer.style.display = 'block';
            
            // Show loading state
            document.getElementById('chartTitle').textContent = `Loading ${subject} (${level}-level) Performance...`;
            
            fetch(`?ajax=1&action=performance&subject=${encodeURIComponent(subject)}&level=${encodeURIComponent(level)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.performance && data.performance.length > 0) {
                            displayChart(subject, level, data.performance);
                            updateChartStats(data.stats);
                        } else {
                            handleNoPerformanceData(subject, level);
                        }
                    } else {
                        console.error('Error loading performance data:', data.error);
                        showMessage('Error loading performance data: ' + data.error, 'error');
                        handleNoPerformanceData(subject, level);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Network error loading performance data', 'error');
                    handleNoPerformanceData(subject, level);
                });
        }
        
        function displayChart(subject, level, performances) {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (currentChart) {
                currentChart.destroy();
            }
            
            // Prepare data for chart
            const labels = performances.map(p => new Date(p.date_taken).toLocaleDateString());
            const scores = performances.map(p => parseFloat(p.score));
            
            // Update chart title
            document.getElementById('chartTitle').textContent = `${subject} (${level}-level) Performance Over Time`;
            
            currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Score',
                        data: scores,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: scores.map((score, index) => 
                            performances[index].status === 'passed' ? '#28a745' : '#dc3545'
                        )
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 20,
                            title: {
                                display: true,
                                text: 'Score (out of 20)'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const performance = performances[context.dataIndex];
                                    return [
                                        `Score: ${context.parsed.y}/20`,
                                        `Percentage: ${((context.parsed.y/20)*100).toFixed(1)}%`,
                                        `Status: ${performance.status}`,
                                        `Questions: ${performance.correct_answers}/${performance.total_questions}`,
                                        `Year: ${performance.year}`
                                    ];
                                }
                            },
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    }
                }
            });
        }
        
        function updateChartStats(stats) {
            const chartStats = document.getElementById('chartStats');
            chartStats.innerHTML = `
                <div class="stat-item">
                    <div class="stat-value">${stats.total_attempts || 0}</div>
                    <div class="stat-label">Attempts</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${(stats.average_score || 0).toFixed(1)}%</div>
                    <div class="stat-label">Average Score</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${(stats.best_score || 0).toFixed(1)}%</div>
                    <div class="stat-label">Best Score</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">${(stats.pass_rate || 0).toFixed(1)}%</div>
                    <div class="stat-label">Pass Rate</div>
                </div>
            `;
        }
        
        function handleNoPerformanceData(subject, level) {
            document.getElementById('chartTitle').textContent = `${subject} (${level}-level) Performance Over Time`;
            document.getElementById('chartStats').innerHTML = '';
            if (currentChart) {
                currentChart.destroy();
            }
            const ctx = document.getElementById('performanceChart').getContext('2d');
            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            document.getElementById('chartContainer').innerHTML += `
                <div class="no-data">
                    <i class="fa fa-chart-line"></i>
                    <h4>No Performance Data</h4>
                    <p>No tests found for this subject and level.</p>
                </div>
            `;
        }

      
    </script>
</body>
</html>
