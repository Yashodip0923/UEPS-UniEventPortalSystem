<?php
// api/departments/delete_department_process.php

// Ensure session is started if not already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json'); // Set header to indicate JSON response

// Adjust paths as necessary
require_once __DIR__ . '/../dbinclude.php'; // Path to your database connection
require_once __DIR__ . '/../../config.php'; // Path to your config file

// Check if $conn is valid
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in delete_department_process.php.");
    echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

// Check if department_id is set and is an integer
if (!isset($_POST['department_id']) || !filter_var($_POST['department_id'], FILTER_VALIDATE_INT)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid department ID.']);
    exit();
}

$departmentId = (int) $_POST['department_id'];

// Perform the soft delete (update status to 0 or another inactive value)
// Assuming 'status' column exists and 0 means trashed/inactive
$stmt = $conn->prepare("UPDATE departments SET status = 0 WHERE department_id = ?");
$stmt->bind_param("i", $departmentId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Department moved to trash successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Department not found or already in trash.']);
    }
} else {
    error_log("Error soft deleting department ID $departmentId: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to move department to trash. Database error.']);
}

$stmt->close();
$conn->close();
?>