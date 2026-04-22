<?php
session_start(); // Start the session to access $_SESSION variables

// Include the database connection file
require_once __DIR__ . '/../../api/dbinclude.php'; // Adjust path if necessary

// Ensure the connection object ($conn) is available and valid
if (!isset($conn) || !$conn instanceof mysqli) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Database connection failed via dbinclude.php.']);
    exit();
}

// 1. Handle POST Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the POST request
    $user_id_to_change = $_POST['user_id'] ?? null; // ID of the user whose password is being changed
    $current_password = $_POST['current_password'] ?? null; // Only required for self-password change
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    // Determine if this is a self-password change or an admin-initiated change
    $is_self_change = ($current_password !== null && isset($_SESSION['user_id']) && $user_id_to_change == $_SESSION['user_id']);
    // For admin change, ensure the user is logged in and has an 'admin' role.
    // Adjust 'admin' to your actual role name or privilege check.
    $is_admin_change = (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' && $user_id_to_change !== null);

    // --- Authorization Check ---
    if (!$is_self_change && !$is_admin_change) {
        http_response_code(403); // Forbidden
        echo json_encode(['success' => false, 'message' => 'Access denied. You are not authorized to perform this action.']);
        exit();
    }

    // --- Input Validation ---
    if (empty($user_id_to_change) || empty($new_password) || empty($confirm_new_password)) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'All required fields are missing.']);
        exit();
    }

    if (!filter_var($user_id_to_change, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid user ID format.']);
        exit();
    }

    if ($new_password !== $confirm_new_password) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'New password and confirm new password do not match.']);
        exit();
    }

    // Password strength policy (customize as needed)
    if (strlen($new_password) < 8) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'New password must be at least 8 characters long.']);
        exit();
    }
    // Add more complexity checks (e.g., requires uppercase, lowercase, number, special char) if desired

    // 2. Fetch User's Current Hashed Password (if self-change) and verify user existence
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id_to_change);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404); // Not Found
        echo json_encode(['success' => false, 'message' => 'Target user not found.']);
        $stmt->close();
        exit();
    }

    $user = $result->fetch_assoc();
    $hashed_password_from_db = $user['password'];
    $stmt->close();

    // 3. Verify Current Password (only for self-change)
    if ($is_self_change) {
        if (!password_verify($current_password, $hashed_password_from_db)) {
            http_response_code(401); // Unauthorized
            echo json_encode(['success' => false, 'message' => 'Incorrect current password.']);
            exit();
        }
    }

    // 4. Hash the New Password
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);


    // 5. Update Password in Database
    // Note: No current_password check is done here if it's an admin change.
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_new_password, $user_id_to_change);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Error updating password: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    // 6. Handle Non-POST Requests
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Only POST requests are accepted.']);
}

// The database connection is handled by dbinclude.php, so no $conn->close() here if dbinclude.php manages connection persistence.
// If dbinclude.php closes the connection, it should do so, otherwise, it's typically closed at script termination.
// If you want to explicitly close it here, uncomment the line below.
// $conn->close();
?>