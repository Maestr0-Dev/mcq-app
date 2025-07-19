<?php
session_start();
include 'classes.php';

// Set content type first
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$stud_id = $_SESSION['id'];
$action = $_GET['action'] ?? 'test'; // Default to test if no action specified

// Create Performance class instance
class PerformanceManager {
    private $db;
    
    public function __construct() {
        $this->db = new DB();
    }
    
    private function getConnection() {
        try {
            $conn = new PDO("mysql:host=localhost;dbname=".$this->db->DBname(), $this->db->username(), $this->db->pass());
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public function getSubjects($stud_id) {
        try {
            $conn = $this->getConnection();
            
            $sql = "SELECT 
                        subject,
                        COUNT(*) as test_count,
                        AVG((score/20)*100) as average_score,
                        MAX(date_taken) as last_test,
                        level,
                        exam_type
                    FROM performances 
                    WHERE stud_id = ? 
                    GROUP BY subject, level, exam_type
                    ORDER BY last_test DESC";
            
            $statement = $conn->prepare($sql);
            $statement->execute([$stud_id]);
            $subjects = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the data
            foreach($subjects as &$subject) {
                $subject['average_score'] = round((float)$subject['average_score'], 1);
                $subject['test_count'] = (int)$subject['test_count'];
                // Add a display name that includes level
                $subject['display_name'] = $subject['subject'] . ' (' . $subject['level'] . '-level)';
            }
            
            return ['success' => true, 'subjects' => $subjects];
            
        } catch(Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getPerformanceData($stud_id, $subject) {
        if (empty($subject)) {
            return ['success' => false, 'error' => 'Subject not specified'];
        }
        
        try {
            $conn = $this->getConnection();
            
            // Get performance data for the subject
            $sql = "SELECT 
                        performance_id,
                        score,
                        total_questions,
                        correct_answers,
                        date_taken,
                        status,
                        year,
                        level,
                        exam_type
                    FROM performances 
                    WHERE stud_id = ? AND subject = ? 
                    ORDER BY date_taken ASC";
            
            $statement = $conn->prepare($sql);
            $statement->execute([$stud_id, $subject]);
            $performance = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            // Get subject statistics
            $statsSql = "SELECT 
                            COUNT(*) as total_attempts,
                            AVG((score/20)*100) as average_score,
                            MAX(score) as best_score,
                            COUNT(CASE WHEN status = 'passed' THEN 1 END) as passed_count
                        FROM performances 
                        WHERE stud_id = ? AND subject = ?";
            
            $statsStatement = $conn->prepare($statsSql);
            $statsStatement->execute([$stud_id, $subject]);
            $stats = $statsStatement->fetch(PDO::FETCH_ASSOC);
            
            // Calculate pass rate
            $stats['pass_rate'] = $stats['total_attempts'] > 0 ? 
                ($stats['passed_count'] / $stats['total_attempts']) * 100 : 0;
            
            // Format the data
            foreach($performance as &$perf) {
                $perf['score'] = (float)$perf['score'];
                $perf['total_questions'] = (int)$perf['total_questions'];
                $perf['correct_answers'] = (int)$perf['correct_answers'];
            }
            
            $stats['total_attempts'] = (int)$stats['total_attempts'];
            $stats['average_score'] = (float)$stats['average_score'];
            $stats['best_score'] = (float)$stats['best_score'];
            $stats['pass_rate'] = (float)$stats['pass_rate'];
            
            return [
                'success' => true, 
                'performance' => $performance,
                'stats' => $stats
            ];
            
        } catch(Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getSummaryStats($stud_id) {
        try {
            $conn = $this->getConnection();
            
            $sql = "SELECT 
                        COUNT(*) as total_attempts,
                        AVG((score/20)*100) as average_score,
                        COUNT(CASE WHEN status = 'passed' THEN 1 END) as passed_count,
                        COUNT(DISTINCT CONCAT(subject, '-', level)) as total_subjects,
                        MAX(score) as best_score,
                        MIN(score) as lowest_score
                    FROM performances 
                    WHERE stud_id = ?";
            
            $statement = $conn->prepare($sql);
            $statement->execute([$stud_id]);
            $stats = $statement->fetch(PDO::FETCH_ASSOC);
            
            // Calculate pass rate
            $stats['pass_rate'] = $stats['total_attempts'] > 0 ? 
                ($stats['passed_count'] / $stats['total_attempts']) * 100 : 0;
            
            // Format the data
            $stats['total_attempts'] = (int)$stats['total_attempts'];
            $stats['average_score'] = round((float)$stats['average_score'], 1);
            $stats['pass_rate'] = round((float)$stats['pass_rate'], 1);
            $stats['total_subjects'] = (int)$stats['total_subjects'];
            $stats['best_score'] = (float)$stats['best_score'];
            $stats['lowest_score'] = (float)$stats['lowest_score'];
            
            return ['success' => true, 'stats' => $stats];
            
        } catch(Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getRecentPerformances($stud_id, $limit = 10) {
        try {
            $conn = $this->getConnection();
            
            $sql = "SELECT 
                        subject,
                        score,
                        total_questions,
                        correct_answers,
                        date_taken,
                        status,
                        year,
                        level,
                        exam_type
                    FROM performances 
                    WHERE stud_id = ? 
                    ORDER BY date_taken DESC
                    LIMIT ?";
            
            $statement = $conn->prepare($sql);
            $statement->execute([$stud_id, $limit]);
            $performances = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the data
            foreach($performances as &$perf) {
                $perf['score'] = (float)$perf['score'];
                $perf['total_questions'] = (int)$perf['total_questions'];
                $perf['correct_answers'] = (int)$perf['correct_answers'];
                $perf['percentage'] = ($perf['score'] / 20) * 100;
            }
            
            return ['success' => true, 'performances' => $performances];
            
        } catch(Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

// Create performance manager instance
$performanceManager = new PerformanceManager();

// Handle different actions
switch($action) {
    case 'subjects':
        $result = $performanceManager->getSubjects($stud_id);
        break;
        
    case 'performance':
        $subject = $_GET['subject'] ?? '';
        $result = $performanceManager->getPerformanceData($stud_id, $subject);
        break;
        
    case 'summary':
        $result = $performanceManager->getSummaryStats($stud_id);
        break;
        
    case 'recent':
        $limit = $_GET['limit'] ?? 10;
        $result = $performanceManager->getRecentPerformances($stud_id, $limit);
        break;
        
    case 'test':
        // Test endpoint to verify the script is working
        try {
            $testStats = $performanceManager->getSummaryStats($stud_id);
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

// Output the result
echo json_encode($result, JSON_PRETTY_PRINT);
?>