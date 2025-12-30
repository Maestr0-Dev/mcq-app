<?php
session_start();
require_once 'classes.php';


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$stud_id = $_SESSION['id'];
$subject = $_POST['subject'] ?? '';
$topics = $_POST['topics'] ?? '';
$exam_date_str = $_POST['exam-date'] ?? '';

if (empty($subject) || empty($topics) || empty($exam_date_str)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

$exam_date = new DateTime($exam_date_str);
$today = new DateTime();
$interval = $today->diff($exam_date);
$days_to_exam = $interval->days;

if ($days_to_exam <= 0) {
    echo json_encode(['success' => false, 'error' => 'Exam date must be in the future']);
    exit;
}

// --- Helper function to call Gemini API ---
function callGeminiAPI($prompt) {
    $apiKey = getenv('GEMINI_API_KEY');
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=" . $apiKey;

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120); // 120-second timeout

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return ['success' => false, 'error' => "Gemini API error ($httpCode): " . $response];
    }

    $result = json_decode($response, true);
    if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return ['success' => false, 'error' => 'Invalid API response'];
    }

    return ['success' => true, 'text' => $result['candidates'][0]['content']['parts'][0]['text']];
}

// 1. Generate the study plan
$plan_prompt = "Create a day-by-day study plan for a student preparing for an exam in '{$subject}'.
The exam is in {$days_to_exam} days.
The topics to cover are: {$topics}.
Distribute these topics evenly across the {$days_to_exam} days.
Format the response as a JSON object with this exact structure:
{
  \"plan\": [
    {\"day\": 1, \"topics\": \"Topic A, Topic B\"},
    {\"day\": 2, \"topics\": \"Topic C, Topic D\"}
  ]
}";

$plan_response = callGeminiAPI($plan_prompt);

if (!$plan_response['success']) {
    error_log('Failed to generate study plan: ' . $plan_response['error']);
    echo json_encode(['success' => false, 'error' => 'Failed to generate study plan. Please try again later.']);
    exit;
}

$plan_json_text = $plan_response['text'];
$plan_data = json_decode(str_replace(['```json', '```'], '', $plan_json_text), true);

if (json_last_error() !== JSON_ERROR_NONE || !isset($plan_data['plan'])) {
    echo json_encode(['success' => false, 'error' => 'Failed to parse study plan from AI response.']);
    exit;
}

$daily_plans = $plan_data['plan'];

// 2. Save the main prep plan and generate quizzes for each day
$db = new DB();

// Start a transaction
$conn = new PDO("mysql:host=".$db->host().";dbname=".$db->DBname(), $db->username(), $db->pass());
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->beginTransaction();

try {
    // Save the main prep plan
    $sql = "INSERT INTO exam_preps (stud_id, subject, exam_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$stud_id, $subject, $exam_date_str]);
    $prep_id = $conn->lastInsertId();

    foreach ($daily_plans as $day_plan) {
        $plan_date = (new DateTime())->add(new DateInterval('P' . ($day_plan['day'] - 1) . 'D'))->format('Y-m-d');
        
        $sql = "INSERT INTO prep_plan_days (prep_id, plan_date, topics) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$prep_id, $plan_date, $day_plan['topics']]);
        $plan_day_id = $conn->lastInsertId();

        $quiz_prompt = "Create a 30-question multiple-choice quiz with increasing difficulty on the following topics in '{$subject}': {$day_plan['topics']}.
        Each question should have 4 options (A, B, C, D) with only one correct answer.
        Format the response as a JSON object with this exact structure:
        {
          \"title\": \"Quiz for {$day_plan['topics']}\",
          \"questions\": [
            {\"question\": \"...\", \"options\": {\"A\": \"...\", \"B\": \"...\", \"C\": \"...\", \"D\": \"...\"}, \"correct\": \"A\", \"explanation\": \"...\"}
          ]
        }";

        $quiz_response = callGeminiAPI($quiz_prompt);
if (!$quiz_response['success']) {
            throw new Exception("Failed to generate quiz for day {$day_plan['day']}: " . $quiz_response['error']);
        }
        
        $quiz_json_text = $quiz_response['text'];
        $quiz_data = json_decode(str_replace(['```json', '```'], '', $quiz_json_text), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to parse quiz JSON for day {$day_plan['day']}");
        }

        // Save the quiz
        $sql = "INSERT INTO prep_quizzes (plan_day_id, quiz_data) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$plan_day_id, json_encode($quiz_data)]);
    }

    // If all went well, commit the transaction
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Study plan and quizzes generated successfully!']);

} catch (Exception $e) {
    // If anything went wrong, roll back the transaction
    $conn->rollBack();
    error_log("Error generating study plan: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An error occurred while generating the study plan.']);
}
?>
