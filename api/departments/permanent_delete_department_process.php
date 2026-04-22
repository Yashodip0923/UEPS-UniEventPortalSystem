<?php
// api/departments/permanent_delete_department_process.php

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
    error_log("Database connection failed in permanent_delete_department_process.php.");
    echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
    exit();
}

// Delete the department permanently from the database
$stmt = $conn->prepare("DELETE FROM departments WHERE department_id = ? AND status = 0");
if ($stmt === false) {
    error_log("Prepare failed in permanent_delete_department_process.php: " . $conn->error);
    echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed.']);
    exit();
}

$stmt->bind_param("i", $department_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Department permanently deleted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Department not found or not in trash (status is not 0).']);
    }
} else {
    error_log("Execute failed in permanent_delete_department_process.php: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Failed to permanently delete department.']);
}

$stmt->close();
$conn->close();
exit();
?>