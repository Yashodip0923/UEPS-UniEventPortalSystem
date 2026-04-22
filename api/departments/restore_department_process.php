<?php
// api/departments/restore_department_process.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Authentication required.']);
    exit();
}

$department_id = filter_input(INPUT_POST, 'department_id', FILTER_VALIDATE_INT);

if (!$department_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid department ID provided.']);
    exit();
}

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in restore_department_process.php.");
    echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
    exit();
}

// Update the department status to 1 (active)
$stmt = $conn->prepare("UPDATE departments SET status = 1, updated_at = NOW() WHERE department_id = ? AND status = 0");
if ($stmt === false) {
    error_log("Prepare failed in restore_department_process.php: " . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed.']);
    exit();
}

$stmt->bind_param("i", $department_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Department restored successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Department not found or already active.']);
    }
} else {
    error_log("Execute failed in restore_department_process.php: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to restore department.']);
}

$stmt->close();
$conn->close();
exit();
?>