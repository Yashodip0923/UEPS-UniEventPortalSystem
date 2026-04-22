<?php
// api/admin/update_college_process.php
// This file handles the form submission for updating an existing college.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../dbinclude.php'; // Adjust path to dbinclude.php
require_once __DIR__ . '/../../config.php'; // Adjust path to config.php

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url . 'login.php?error=unauthenticated');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($conn) || !$conn instanceof mysqli) {
        error_log("Database connection (MySQLi) failed in update_college_process.php.");
        header('Location: ' . base_url . 'admin_dashboard.php?section=colleges&status=error&message=' . urlencode('Database connection error. Please try again later.'));
        exit();
    }

    // Sanitize and validate input
    $collegeId = filter_var($_POST['college_id'] ?? '', FILTER_VALIDATE_INT);
    $collegeName = trim($_POST['college_name'] ?? '');
    $collegeCode = trim($_POST['college_code'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $errors = [];

    if (!$collegeId) {
        $errors[] = 'Invalid College ID.';
    }
    if (empty($collegeName)) {
        $errors[] = 'College Name is required.';
    }
    if (empty($collegeCode)) {
        $errors[] = 'College Code is required.';
    }
    if (empty($address)) {
        $errors[] = 'Address is required.';
    }

    if (!empty($errors)) {
        $errorMessage = implode(' ', $errors);
        header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&college_id=' . $collegeId . '&status=error&message=' . urlencode($errorMessage));
        exit();
    }

    // Check for duplicate college name or code, excluding the current college being updated
    $checkStmt = $conn->prepare("SELECT college_id FROM colleges WHERE (LOWER(college_name) = LOWER(?) OR LOWER(college_code) = LOWER(?)) AND college_id != ? AND status = 1");
    $checkStmt->bind_param("ssi", $collegeName, $collegeCode, $collegeId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows > 0) {
        header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&college_id=' . $collegeId . '&status=error&message=' . urlencode('A college with this name or code already exists.'));
        $checkStmt->close();
        $conn->close();
        exit();
    }
    $checkStmt->close();


    // Prepare SQL UPDATE statement
    $stmt = $conn->prepare("UPDATE colleges SET college_name = ?, college_code = ?, address = ?, updated_at = NOW() WHERE college_id = ?");

    if ($stmt) {
        $stmt->bind_param("sssi", $collegeName, $collegeCode, $address, $collegeId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Success: Redirect back to colleges list with success message
                header('Location: ' . base_url . 'admin_dashboard.php?section=colleges&status=success&message=' . urlencode('College updated successfully!'));
            } else {
                // No rows affected, possibly no changes made
                header('Location: ' . base_url . 'admin_dashboard.php?section=colleges&status=info&message=' . urlencode('College details were submitted, but no changes were made.'));
            }
            exit();
        } else {
            error_log("Database update failed: " . $stmt->error);
            header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&college_id=' . $collegeId . '&status=error&message=' . urlencode('Failed to update college. Database error.'));
            exit();
        }
        $stmt->close();
    } else {
        error_log("Failed to prepare update statement: " . $conn->error);
        header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&college_id=' . $collegeId . '&status=error&message=' . urlencode('Server error: Could not prepare statement.'));
        exit();
    }

    $conn->close();

} else {
    header('Location: ' . base_url . 'admin_dashboard.php?section=colleges&status=error&message=' . urlencode('Invalid request method.'));
    exit();
}
?>