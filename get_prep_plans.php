<?php
session_start();
require_once 'classes.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$stud_id = $_SESSION['id'];

try {
    $conn = new PDO("mysql:host=localhost;dbname=interactives_mcqs", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT p.prep_id, p.subject, p.exam_date, d.plan_day_id, d.plan_date, d.topics, q.quiz_id
            FROM exam_preps p
            JOIN prep_plan_days d ON p.prep_id = d.prep_id
            LEFT JOIN prep_quizzes q ON d.plan_day_id = q.plan_day_id
            WHERE p.stud_id = ?
            ORDER BY p.exam_date ASC, d.plan_date ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$stud_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $plans = [];
    foreach ($results as $row) {
        $plans[$row['prep_id']]['prep_id'] = $row['prep_id'];
        $plans[$row['prep_id']]['subject'] = $row['subject'];
        $plans[$row['prep_id']]['exam_date'] = $row['exam_date'];
        $plans[$row['prep_id']]['days'][] = [
            'plan_day_id' => $row['plan_day_id'],
            'plan_date' => $row['plan_date'],
            'topics' => $row['topics'],
            'quiz_id' => $row['quiz_id']
        ];
    }

    echo json_encode(['success' => true, 'plans' => array_values($plans)]);

} catch (Exception $e) {
    error_log("Error fetching prep plans: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error while fetching prep plans.']);
}
?>
