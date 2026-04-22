<?php
// api/departments/edit_department_process.php
// This script handles updating an existing department in the database.
// It expects 'department_id', 'department_name', 'college_id' via POST and redirects with a status message.

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
$department_id = filter_input(INPUT_POST, 'department_id', FILTER_VALIDATE_INT);
$department_name = filter_input(INPUT_POST, 'department_name', FILTER_SANITIZE_STRING);
$college_id = filter_input(INPUT_POST, 'college_id', FILTER_VALIDATE_INT);

if (!$department_id || empty($department_name) || !$college_id) {
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('All fields (including Department ID) are required.'));
    exit();
}

// Check if database connection is established
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in edit_department_process.php.");
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


// Prepare and execute the UPDATE statement for the department
$stmt = $conn->prepare("UPDATE departments SET department_name = ?, college_id = ?, updated_at = NOW() WHERE department_id = ? AND status = 1");
if ($stmt === false) {
    error_log("Prepare failed in edit_department_process.php: " . $conn->error);
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Database query preparation failed.'));
    exit();
}

$stmt->bind_param("sii", $department_name, $college_id, $department_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header('Location: ' . $redirect_url . '&status=success&message=' . urlencode('Department updated successfully!'));
    } else {
        header('Location: ' . $redirect_url . '&status=info&message=' . urlencode('No changes made or department not found/active.'));
    }
} else {
    error_log("Execute failed in edit_department_process.php: " . $stmt->error);
    header('Location: ' . $redirect_url . '&status=error&message=' . urlencode('Failed to update department.'));
}

$stmt->close();
$conn->close();
exit();
?>
