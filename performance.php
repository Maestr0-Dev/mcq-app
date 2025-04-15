<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Performance</title>
    <style>
        
    </style>
</head>
<body>
    <div class="wrapper">
        <a href="home.php" class="back-button">
        <i class="fa fa-arrow-left"></i>
    </a>
        <div class="performance-container">
            <h1>Your Performance Analytics</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fa fa-check-circle"></i>
                    <h3>Accuracy</h3>
                    <div class="stat-value">85%</div>
                </div>
                
                <div class="stat-card">
                    <i class="fa fa-clock"></i>
                    <h3>Average Time</h3>
                    <div class="stat-value">1.5m</div>
                </div>
                
                <div class="stat-card">
                    <i class="fa fa-star"></i>
                    <h3>Total Score</h3>
                    <div class="stat-value">750</div>
                </div>
                
                <div class="stat-card">
                    <i class="fa fa-trophy"></i>
                    <h3>Ranking</h3>
                    <div class="stat-value">#42</div>
                </div>
            </div>
            
            <div class="chart-container">
                <h2>Progress Over Time</h2>
                <canvas id="progressChart"></canvas>
            </div>
            
            <div class="chart-container">
                <h2>Subject Performance</h2>
                <canvas id="subjectChart"></canvas>
            </div>
        </div>
        
        <div class="back">
            <a href="landing.html">
                <button class="button float-start">
                    <i class="fa fa-arrow-left"></i>
                </button>
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Progress Chart
   const progressCtx = document.getElementById('progressChart').getContext('2d');
   new Chart(progressCtx, {
       type: 'line',
       data: {
           labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
           datasets: [{
               label: 'Score',
               data: [65, 70, 75, 72, 80, 85],
               borderColor: '#007bff',
               tension: 0.1
           }]
       },
       options: {
           responsive: true,
           maintainAspectRatio: false
       }
   });
   
   // Subject Chart
   const subjectCtx = document.getElementById('subjectChart').getContext('2d');
   new Chart(subjectCtx, {
       type: 'bar',
       data: {
           labels: ['Math', 'Science', 'History', 'English', 'Geography'],
           datasets: [{
               label: 'Performance by Subject',
               data: [85, 78, 92, 88, 75],
               backgroundColor: '#28a745'
           }]
       },
       options: {
           responsive: true,
           maintainAspectRatio: false
       }
   });
    </script>
</body>
</html>