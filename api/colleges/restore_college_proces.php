<?php
// api/colleges/restore_college_process.php
// Handles restoring a soft-deleted college by setting its status back to 1 (active).

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json'); // Ensure JSON response

// Include database connection and configuration files
require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';

// Check if $conn is valid after including dbinclude.php
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in restore_college_process.php.");
    echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
    exit();
}

// Ensure the user is authenticated (e.g., an admin)
// In a real application, you'd have more robust authentication and authorization checks
if (!isset($loggedInUserId)) { // Assuming $loggedInUserId is set by a prior authentication process (e.g., in header.php)
    echo json_encode(['status' => 'error', 'message' => 'Authentication required.']);
    exit();
}

// Check if college_id is provided via POST
if (!isset($_POST['college_id']) || empty($_POST['college_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'College ID not provided.']);
    exit();
}

$collegeId = (int) $_POST['college_id'];

// Start a transaction for atomicity
$conn->begin_transaction();

try {
    // Prepare statement to update college status
    // Setting status to 1 means it's active
    $stmt = $conn->prepare("UPDATE colleges SET status = 1, updated_at = NOW() WHERE college_id = ?");
    if (!$stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("i", $collegeId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'College restored successfully!']);
        } else {
            // No rows affected, possibly college_id not found or already active
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'College not found or already active.']);
        }
    } else {
        throw new Exception("Execute statement failed: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error restoring college: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while restoring the college.']);
}

$conn->close();
?>