<?php
// api/departments/get_departments_by_college.php
// This script fetches departments associated with a given college ID.
// It expects 'college_id' via GET parameter and returns a JSON array of departments.

// Ensure session is started if needed for authentication, though not strictly required for this public API endpoint.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once __DIR__ . '/../dbinclude.php'; // Adjust path if necessary

// Set content type to JSON
header('Content-Type: application/json');

// Check if college_id is provided and is a valid integer
if (!isset($_GET['college_id']) || !filter_var($_GET['college_id'], FILTER_VALIDATE_INT)) {
    echo json_encode([]); // Return empty array if no valid college_id is provided
    exit();
}

$collegeId = (int)$_GET['college_id'];

// Check if database connection is established
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in get_departments_by_college.php.");
    echo json_encode([]); // Return empty array on DB error
    exit();
}

$departments = [];

// Prepare and execute the query to fetch active departments for the given college
$stmt = $conn->prepare("SELECT department_id, department_name FROM departments WHERE college_id = ? AND status = 1 ORDER BY department_name ASC");
if ($stmt === false) {
    error_log("Failed to prepare statement in get_departments_by_college.php: " . $conn->error);
    echo json_encode([]); // Return empty array on query preparation error
    $conn->close();
    exit();
}

$stmt->bind_param("i", $collegeId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($departments);
exit();
?>
