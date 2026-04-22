<?php
// api/admin/add_college_process.php
// This file handles the form submission for adding a new college.

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once __DIR__ . '/../dbinclude.php'; // Adjust path as necessary for database connection
require_once __DIR__ . '/../../config.php'; // Adjust path as necessary for base_url

// Check if user is authenticated (assuming loggedInUserId is set in session or header.php)
// You might need more robust role-based access control here
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url . 'login.php?error=unauthenticated'); // Redirect to login if not authenticated
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if $conn is valid after including dbinclude.php
    if (!isset($conn) || !$conn instanceof mysqli) {
        error_log("Database connection (MySQLi) failed in add_college_process.php.");
        // Redirect with error message
        header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&status=error&message=' . urlencode('Database connection error. Please try again later.'));
        exit();
    }

    // Sanitize and validate input
    $collegeName = trim($_POST['college_name'] ?? '');
    $collegeCode = trim($_POST['college_code'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $errors = [];

    if (empty($collegeName)) {
        $errors[] = 'College Name is required.';
    }
    if (empty($collegeCode)) {
        $errors[] = 'College Code is required.';
    }
    if (empty($address)) {
        $errors[] = 'Address is required.';
    }

    // If there are validation errors, redirect back with error messages
    if (!empty($errors)) {
        $errorMessage = implode(' ', $errors);
        header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&status=error&message=' . urlencode($errorMessage));
        exit();
    }

    // Check for duplicate college name or code (case-insensitive)
    $checkStmt = $conn->prepare("SELECT college_id FROM colleges WHERE LOWER(college_name) = LOWER(?) OR LOWER(college_code) = LOWER(?)");
    $checkStmt->bind_param("ss", $collegeName, $collegeCode);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    if ($checkResult->num_rows > 0) {
        // Duplicate found, redirect with error
        header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&status=error&message=' . urlencode('A college with this name or code already exists.'));
        $checkStmt->close();
        $conn->close();
        exit();
    }
    $checkStmt->close();

    // Prepare an SQL INSERT statement
    $stmt = $conn->prepare("INSERT INTO colleges (college_name, college_code, address, created_at, status) VALUES (?, ?, ?, NOW(), 1)");

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("sss", $collegeName, $collegeCode, $address);

        if ($stmt->execute()) {
            // Success: Redirect back to the add_college page with a success message
            header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&status=success&message=' . urlencode('College added successfully!'));
            exit();
        } else {
            // Error during execution: Redirect with error message
            error_log("Database insertion failed: " . $stmt->error);
            header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&status=error&message=' . urlencode('Failed to add college. Database error.'));
            exit();
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement: Redirect with error message
        error_log("Failed to prepare statement: " . $conn->error);
        header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&status=error&message=' . urlencode('Failed to add college. Server error.'));
        exit();
    }

    // Close the database connection
    $conn->close();

} else {
    // Not a POST request: Redirect to add_college page or dashboard with an error
    header('Location: ' . base_url . 'admin_dashboard.php?section=add_college&status=error&message=' . urlencode('Invalid request method.'));
    exit();
}
?>