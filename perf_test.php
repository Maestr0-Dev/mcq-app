<?php
// Create this as a separate file (e.g., debug_performance.php) to test your methods

session_start();
include 'classes.php';

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    die("Please log in first");
}

$stud_id = $_SESSION['id'];
echo "<h2>Debug Performance Dashboard</h2>";
echo "<p>Student ID: " . $stud_id . "</p>";

$performance = new Performance();

// Test 1: Debug database connection and structure
echo "<h3>1. Database Connection Test</h3>";
try {
    $debugResult = $performance->debugDatabase($stud_id);
    echo "<pre>" . print_r($debugResult, true) . "</pre>";
} catch(Exception $e) {
    echo "<p style='color: red;'>Database debug failed: " . $e->getMessage() . "</p>";
}

// Test 2: Test getSubjects method
echo "<h3>2. Get Subjects Test</h3>";
try {
    $subjectsResult = $performance->getSubjects($stud_id);
    echo "<pre>" . print_r($subjectsResult, true) . "</pre>";
} catch(Exception $e) {
    echo "<p style='color: red;'>getSubjects failed: " . $e->getMessage() . "</p>";
}

// Test 3: Test getSummaryStats method
echo "<h3>3. Get Summary Stats Test</h3>";
try {
    $summaryResult = $performance->getSummaryStats($stud_id);
    echo "<pre>" . print_r($summaryResult, true) . "</pre>";
} catch(Exception $e) {
    echo "<p style='color: red;'>getSummaryStats failed: " . $e->getMessage() . "</p>";
}

// Test 4: Test getRecentPerformances method
echo "<h3>4. Get Recent Performances Test</h3>";
try {
    $recentResult = $performance->getRecentPerformances($stud_id, 5);
    echo "<pre>" . print_r($recentResult, true) . "</pre>";
} catch(Exception $e) {
    echo "<p style='color: red;'>getRecentPerformances failed: " . $e->getMessage() . "</p>";
}

// Test 5: Raw database query test
echo "<h3>5. Raw Database Query Test</h3>";
try {
    // This should be added as a method to your Performance class
    $conn = new PDO("mysql:host=localhost;dbname=YOUR_DB_NAME;charset=utf8mb4", "YOUR_USERNAME", "YOUR_PASSWORD");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("SELECT * FROM performances WHERE stud_id = ? LIMIT 5");
    $stmt->execute([$stud_id]);
    $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Raw data from performances table:</p>";
    echo "<pre>" . print_r($rawData, true) . "</pre>";
    
} catch(Exception $e) {
    echo "<p style='color: red;'>Raw query failed: " . $e->getMessage() . "</p>";
}

// Test 6: Check PHP error logs
echo "<h3>6. Recent PHP Error Logs</h3>";
echo "<p>Check your PHP error log file for detailed error messages.</p>";
echo "<p>Common locations:</p>";
echo "<ul>";
echo "<li>/var/log/php_errors.log</li>";
echo "<li>/var/log/apache2/error.log</li>";
echo "<li>Check phpinfo() for log file location</li>";
echo "</ul>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2, h3 { color: #333; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
p { margin: 10px 0; }
</style>