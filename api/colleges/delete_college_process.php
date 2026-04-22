<?php
// api/colleges/delete_college_process.php
// Handles the soft deletion of a college.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../dbinclude.php'; // Adjust path to dbinclude.php
require_once __DIR__ . '/../../config.php'; // Adjust path to config.php

header('Content-Type: application/json'); // Set header for JSON response

// Check if user is authenticated (e.g., if a user ID is in the session)
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($conn) || !$conn instanceof mysqli) {
        error_log("Database connection (MySQLi) failed in delete_college_process.php.");
        echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
        exit();
    }

    $collegeId = filter_var($_POST['college_id'] ?? '', FILTER_VALIDATE_INT);

    if (!$collegeId) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid college ID.']);
        $conn->close();
        exit();
    }

    // Prepare SQL to soft delete (update status to 0)
    $stmt = $conn->prepare("UPDATE colleges SET status = 0 WHERE college_id = ?");

    if ($stmt) {
        $stmt->bind_param("i", $collegeId);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'College moved to trash successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'College not found or already in trash.']);
            }
        } else {
            error_log("Failed to execute soft delete statement: " . $stmt->error);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete college. Database error.']);
        }
        $stmt->close();
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Server error: Could not prepare statement.']);
    }

    $conn->close();

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>