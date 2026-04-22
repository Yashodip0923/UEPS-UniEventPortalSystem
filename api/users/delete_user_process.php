<?php
// api/admin/delete_user_process.php
// This script handles soft-deleting a user (setting their status to 0).
// It expects 'user_id' via POST and returns a JSON response.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../dbinclude.php';
require_once __DIR__ . '/../../config.php'; // config.php is included for base_url if needed, but not directly used for redirect here.

// Set content type to JSON for the response
header('Content-Type: application/json');

// Initialize response array
$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit();
}

// Check for user authentication and admin role (role_id 5 is admin)
if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] ?? null) != 5) {
    $response['message'] = 'Unauthorized access. Admin privileges required.';
    echo json_encode($response);
    exit();
}

// Validate user_id input
if (!isset($_POST['user_id']) || !filter_var($_POST['user_id'], FILTER_VALIDATE_INT)) {
    $response['message'] = 'Invalid user ID provided.';
    echo json_encode($response);
    exit();
}

$userId = (int)$_POST['user_id'];

// Prevent self-deletion for the currently logged-in admin
if ($userId === (int)$_SESSION['user_id']) {
    $response['message'] = 'You cannot delete your own account.';
    echo json_encode($response);
    exit();
}

// Check if database connection is established
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in delete_user_process.php.");
    $response['message'] = 'Database connection error. Please try again later.';
    echo json_encode($response);
    exit();
}

$stmt = null; // Initialize statement variable

try {
    // Prepare and execute the UPDATE statement to soft-delete the user.
    // The `AND status = 1` ensures we only update active users.
    // If the user is already status=0, affected_rows will be 0.
    $stmt = $conn->prepare("UPDATE users SET status = 0, updated_at = NOW() WHERE user_id = ? AND status = 1");
    if ($stmt === false) {
        // If prepare fails, it's a critical SQL syntax or connection error
        throw new Exception("Failed to prepare soft-delete statement: " . $conn->error);
    }
    $stmt->bind_param('i', $userId);

    if ($stmt->execute()) {
        // Check if any rows were actually updated
        if ($stmt->affected_rows > 0) {
            // Success: User was active and is now soft-deleted
            $response['status'] = 'success';
            $response['message'] = 'User moved to trash successfully.';
            $response['user_id'] = $userId; // Optionally return the ID for frontend manipulation
        } else {
            // No rows affected: User ID not found, or user was already in trash (status = 0)
            $response['status'] = 'info';
            $response['message'] = 'User not found or already in trash.';
        }
    } else {
        // If execute fails, it's a database execution error (e.g., constraint violation)
        throw new Exception("Failed to execute soft-delete statement: " . $stmt->error);
    }

} catch (Exception $e) {
    // Catch any exceptions thrown in the try block (e.g., prepare errors, explicit throws)
    error_log("Error soft-deleting user (ID: $userId): " . $e->getMessage());
    $response['message'] = 'Failed to move user to trash: ' . $e->getMessage();
} finally {
    // Ensure statement and connection are closed
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    if ($conn && $conn->ping()) { // Check if connection is still alive before closing
        $conn->close();
    }
    echo json_encode($response); // Always echo JSON response
    exit(); // Always exit after outputting JSON
}
?>
