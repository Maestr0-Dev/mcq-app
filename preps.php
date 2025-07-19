<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Preps</title>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="fonts/css/all.css">
    <style>
        .preps-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }

               

        
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #334155;
            line-height: 1.6;
        }

        .preps-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h2 {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(45deg, #6366f1, #8b5cf6);
            border-radius: 2px;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        label {
            font-weight: 500;
            color: #475569;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
            letter-spacing: 0.025em;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            background: linear-gradient(135deg, #5855f7 0%, #7c3aed 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Small Buttons */
        .btn-sm {
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-sm.btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
        }

        .btn-sm.btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
        }

        .btn-sm.btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
        }

        .btn-sm.btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border: none;
            color: white;
            box-shadow: 0 2px 8px rgba(100, 116, 139, 0.25);
        }

        .btn-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(100, 116, 139, 0.35);
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
            padding: 1.25rem;
            border-radius: 0;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
            color: #1e293b;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Alert Styling */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            font-weight: 400;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            color: #4338ca;
            border-left: 4px solid #6366f1;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(22, 163, 74, 0.1) 100%);
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }

        .alert-secondary {
            background: linear-gradient(135deg, rgba(100, 116, 139, 0.1) 0%, rgba(71, 85, 105, 0.1) 100%);
            color: #475569;
            border-left: 4px solid #64748b;
        }

        /* Border Bottom for Day Items */
        .border-bottom {
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 0.75rem !important;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .border-bottom:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
            border-left: 3px solid #6366f1;
            padding-left: 1rem !important;
        }

        .border-bottom:last-child {
            border-bottom: none !important;
            margin-bottom: 0;
        }

        /* HR Styling */
        hr {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #6366f1 50%, transparent 100%);
            margin: 3rem 0;
            opacity: 0.6;
        }

        /* Float Right Elements */
        .float-right {
            transition: all 0.3s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .preps-container {
                margin: 10px;
                padding: 15px;
                border-radius: 12px;
            }
            
            .btn-primary {
                width: 100%;
                margin-top: 1rem;
            }
            
            .float-right {
                float: none !important;
                display: block;
                width: 100%;
                margin-top: 0.5rem;
            }
            
            .card-header h4 {
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }
        }

        /* Loading Animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .alert-info:has-text("Loading") {
            animation: pulse 2s infinite;
        }

        /* Smooth Transitions */
        * {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        /* Focus Visible for Accessibility */
        *:focus-visible {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
}
    </style>
</head>
<body>
    <div class="preps-container">
        <h2>Create a New Exam Prep Plan</h2>
        <form id="prep-form">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="topics">Topics (comma-separated)</label>
                <textarea class="form-control" id="topics" name="topics" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="exam-date">Exam Date</label>
                <input type="date" class="form-control" id="exam-date" name="exam-date" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Prep Plan</button>
        </form>
    </div>

    <div id="status-message" style="margin-top: 20px;"></div>

    <hr>

    <h2>Your Prep Plans</h2>
    <div id="prep-plans-list"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadPrepPlans();
        });

        document.getElementById('prep-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const statusMessage = document.getElementById('status-message');

            statusMessage.innerHTML = '<div class="alert alert-info">Generating your personalized study plan and daily quizzes... This may take a moment.</div>';

            fetch('generate_study_plan.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusMessage.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    form.reset();
                    loadPrepPlans(); // Refresh the list
                } else {
                    statusMessage.innerHTML = '<div class="alert alert-danger">Error: ' + data.error + '</div>';
                }
            })
            .catch(error => {
                statusMessage.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                console.error('Error:', error);
            });
        });

        function loadPrepPlans() {
            const prepPlansList = document.getElementById('prep-plans-list');
            prepPlansList.innerHTML = '<div class="alert alert-info">Loading prep plans...</div>';

            fetch('get_prep_plans.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.plans.length > 0) {
                            let html = '';
                            data.plans.forEach(plan => {
                                html += `<div class="card mb-3">
                                    <div class="card-header">
                                        <h4>${plan.subject} - Exam on ${plan.exam_date}</h4>
                                        <button class="btn btn-sm btn-danger float-right" onclick="deletePrepPlan(${plan.prep_id})">Delete</button>
                                    </div>
                                    <div class="card-body">`;
                                plan.days.forEach(day => {
                                    html += `<div class="p-2 border-bottom">
                                        <strong>${day.plan_date}:</strong> ${day.topics}
                                        <a href="prep_quiz.php?quiz_id=${day.quiz_id}" class="btn btn-sm btn-primary float-right">Take Quiz</a>
                                    </div>`;
                                });
                                html += `</div></div>`;
                            });
                            prepPlansList.innerHTML = html;
                        } else {
                            prepPlansList.innerHTML = '<div class="alert alert-secondary">You have no active prep plans.</div>';
                        }
                    } else {
                        prepPlansList.innerHTML = '<div class="alert alert-danger">Error: ' + data.error + '</div>';
                    }
                })
                .catch(error => {
                    prepPlansList.innerHTML = '<div class="alert alert-danger">An error occurred while fetching prep plans.</div>';
                    console.error('Error:', error);
                });
        }

        function deletePrepPlan(prepId) {
            if (!confirm('Are you sure you want to delete this prep plan? This action cannot be undone.')) {
                return;
            }

            const formData = new FormData();
            formData.append('prep_id', prepId);

            fetch('delete_prep_plan.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPrepPlans();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
