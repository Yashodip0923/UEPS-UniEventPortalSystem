<?php
// api/admin/restore_user_process.php
// This script handles restoring a soft-deleted user (setting their status to 1).
// It expects 'user_id' via POST and returns a JSON response.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../dbinclude.php';
require_once __DIR__ . '/../../config.php';

// Set content type to JSON for the response
header('Content-Type: application/json');

// Initialize response array
$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit();
}

// Check for user authentication and admin role
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

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in restore_user_process.php.");
    $response['message'] = 'Database connection error. Please try again later.';
    echo json_encode($response);
    exit();
}

$stmt = null; // Initialize $stmt to null
try {
    // Prepare and execute the UPDATE statement to restore the user.
    // The `AND status = 0` ensures we only update users that are currently soft-deleted.
    $stmt = $conn->prepare("UPDATE users SET status = 1, updated_at = NOW() WHERE user_id = ? AND status = 0");
    if ($stmt === false) {
        throw new Exception("Failed to prepare restore statement: " . $conn->error);
    }
    $stmt->bind_param('i', $userId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'User restored successfully.';
            $response['user_id'] = $userId; // Send back ID for frontend manipulation
        } else {
            $response['status'] = 'info';
            $response['message'] = 'User not found or already active.';
        }
    } else {
        throw new Exception("Failed to execute restore statement: " . $stmt->error);
    }

} catch (Exception $e) {
    error_log("Error restoring user (ID: $userId): " . $e->getMessage());
    $response['message'] = 'Failed to restore user: ' . $e->getMessage();
} finally {
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    if ($conn && $conn->ping()) {
        $conn->close();
    }
    echo json_encode($response); // Always echo JSON response
    exit();
}
?>
