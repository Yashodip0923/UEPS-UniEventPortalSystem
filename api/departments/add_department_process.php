<?php
// api/departments/add_department_process.php
// This script handles adding a new department to the database.
// It expects 'department_name' and 'college_id' via POST and redirects with a status message.

// Ensure session is started for user authentication and data
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection and config
require_once __DIR__ . '/../dbinclude.php';
require_once __DIR__ . '/../../config.php'; // Ensure config.php is included to get base_url

// Define the redirect URL for success/error
// Assuming add_department.php is in content/adminview/
$redirect_url = base_url . 'admin_dashboard.php?section=add_department';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Invalid request method.'));
    exit();
}

// Check for user authentication (assuming role_id 5 is admin)
if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] ?? null) != 5) {
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Unauthorized access. Admin privileges required.'));
    exit();
}

// Validate input from POST
$department_name = filter_input(INPUT_POST, 'department_name', FILTER_SANITIZE_STRING);
$college_id = filter_input(INPUT_POST, 'college_id', FILTER_VALIDATE_INT);

if (empty($department_name) || !$college_id) {
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Department name and college must be provided.'));
    exit();
}

// Check if database connection is established
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in add_department_process.php.");
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Database connection error.'));
    exit();
}

// Check if the college exists and is active
$stmt_check_college = $conn->prepare("SELECT college_id FROM colleges WHERE college_id = ? AND status = 1");
if ($stmt_check_college === false) {
    error_log("Failed to prepare college check statement: " . $conn->error);
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Database query preparation failed for college check.'));
    exit();
}
$stmt_check_college->bind_param("i", $college_id);
$stmt_check_college->execute();
$check_result = $stmt_check_college->get_result();
if ($check_result->num_rows === 0) {
    $stmt_check_college->close();
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Selected college does not exist or is not active.'));
    exit();
}
$stmt_check_college->close();

// Prepare and execute the INSERT statement for the new department
$stmt = $conn->prepare("INSERT INTO departments (department_name, college_id) VALUES (?, ?)");
if ($stmt === false) {
    error_log("Prepare failed in add_department_process.php: " . $conn->error);
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Database query preparation failed for department insert.'));
    exit();
}

$stmt->bind_param("si", $department_name, $college_id);

if ($stmt->execute()) {
    header('Location: ' . $redirect_url . '&status=success&message=' . urlencode('Department added successfully!'));
} else {
    error_log("Execute failed in add_department_process.php: " . $stmt->error);
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Failed to add department.'));
}

$stmt->close();
$conn->close();
exit();
?>
