<?php
// api/admin/delete_user_permanent.php
// This script permanently deletes a user record from the database.
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

// Prevent self-deletion for the currently logged-in admin
if ($userId === (int)$_SESSION['user_id']) {
    $response['message'] = 'You cannot permanently delete your own account.';
    echo json_encode($response);
    exit();
}

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in delete_user_permanent.php.");
    $response['message'] = 'Database connection error. Please try again later.';
    echo json_encode($response);
    exit();
}

$stmt = null; // Initialize $stmt to null
try {
    // Start a transaction for data integrity
    $conn->begin_transaction();

    // Optional: Delete the profile photo file from the server
    // First, fetch the photo URL
    $stmt_photo = $conn->prepare("SELECT photourl FROM users WHERE user_id = ?");
    if ($stmt_photo === false) {
        throw new Exception("Failed to prepare photo fetch statement: " . $conn->error);
    }
    $stmt_photo->bind_param("i", $userId);
    $stmt_photo->execute();
    $result_photo = $stmt_photo->get_result();
    $user_photo = $result_photo->fetch_assoc();
    $stmt_photo->close();

    // Define a default photo path for comparison
    // This should match DEFAULT_PROFILE_PHOTO_PATH_DB in add_user_process.php etc.
    $default_photo_path_db = 'assets/images/default_profile.png'; 

    if ($user_photo && !empty($user_photo['photourl'])) {
        $photoPath = __DIR__ . '/../../' . $user_photo['photourl'];
        // Ensure it's not the default photo and the file exists before unlinking
        if ($user_photo['photourl'] !== $default_photo_path_db && file_exists($photoPath) && is_file($photoPath)) {
            unlink($photoPath);
            error_log("Deleted user photo file: " . $photoPath);
        }
    }

    // IMPORTANT: Handle foreign key constraints for 'users' table if they exist.
    // If user_id is referenced in other tables without ON DELETE CASCADE,
    // you must update/delete those referencing records first.
    // For college_id and dept_id within the users table, we don't need to do anything
    // as we are deleting the user record itself.
    // If user_id is a foreign key in other tables (e.g., events, registrations),
    // you need to handle those here. Example:
    // $stmt_events = $conn->prepare("DELETE FROM events WHERE created_by_user_id = ?");
    // $stmt_events->bind_param('i', $userId);
    // $stmt_events->execute();
    // $stmt_events->close();

    // Now, permanently delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    if ($stmt === false) {
        throw new Exception("Failed to prepare permanent delete statement: " . $conn->error);
    }
    $stmt->bind_param('i', $userId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $conn->commit(); // Commit transaction if successful
            $response['status'] = 'success';
            $response['message'] = 'User permanently deleted.';
            $response['user_id'] = $userId; // Send back ID for frontend removal
        } else {
            $conn->rollback(); // Rollback if no rows were affected (user not found)
            $response['status'] = 'info';
            $response['message'] = 'User not found or already deleted.';
        }
    } else {
        throw new Exception("Failed to execute permanent delete statement: " . $stmt->error);
    }

} catch (Exception $e) {
    $conn->rollback(); // Rollback on any error
    error_log("Error permanently deleting user (ID: $userId): " . $e->getMessage());
    $response['message'] = 'Failed to permanently delete user: ' . $e->getMessage();
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
