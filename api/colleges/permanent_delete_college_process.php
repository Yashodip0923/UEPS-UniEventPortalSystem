<?php
// api/colleges/permanent_delete_college_process.php

// Start session at the very beginning
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Only include necessary files for database connection and configuration
require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';
// REMOVE: require_once __DIR__ . '/../../includes/header.php'; // This line is causing the issue!

// Crucial: Tell the client we're sending JSON. This must be sent before any other output.
header('Content-Type: application/json');

// Ensure only POST requests are processed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

// Ensure $loggedInUserId is set (assuming it's set during login and persisted in session)
// It's good practice to ensure the user is authenticated for such actions.
// If $loggedInUserId isn't set, this means the user isn't properly logged in or their session expired.
if (!isset($_SESSION['user_id'])) { // Assuming user_id is stored in session
    echo json_encode(['status' => 'error', 'message' => 'Authentication required. Please log in again.']);
    exit();
}
// You might also want to check if the user has appropriate permissions (e.g., is an admin)
// For example: if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) { ... }


$college_id = filter_input(INPUT_POST, 'college_id', FILTER_VALIDATE_INT);

if (!$college_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid college ID provided.']);
    exit();
}

// Check database connection *after* includes and before preparing statement
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in permanent_delete_college_process.php.");
    echo json_encode(['status' => 'error', 'message' => 'Database connection error. Please try again later.']);
    exit();
}

// Delete the college permanently from the database
// Add AND status = 0 to ensure only soft-deleted items are hard deleted
$stmt = $conn->prepare("DELETE FROM colleges WHERE college_id = ? AND status = 0");
if ($stmt === false) {
    error_log("Prepare failed in permanent_delete_college_process.php: " . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed.']);
    exit();
}

$stmt->bind_param("i", $college_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'College permanently deleted successfully!']);
    } else {
        // This case means the college_id was valid, but it either didn't exist or its status was not 0 (not in trash)
        echo json_encode(['status' => 'error', 'message' => 'College not found or not in trash (status is not 0).']);
    }
} else {
    error_log("Execute failed in permanent_delete_college_process.php: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to permanently delete college due to a database error.']);
}

$stmt->close();
$conn->close();
exit(); // Ensure no further output
?>