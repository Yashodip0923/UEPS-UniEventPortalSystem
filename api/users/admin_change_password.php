<?php
session_start();

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';

// Function to redirect with status message
function redirect_with_message($status, $message) {
    $redirect_url = base_url.'admin_dashboard.php?section=settings&status=' . $status . '&message=' . urlencode($message);
    header("Location: " . $redirect_url);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect_with_message('error', 'You must be logged in to change your password.');
}

$loggedInUserId = $_SESSION['user_id'];

// Check if the logged-in user has admin role (assuming role_id 5 is admin)
$isAdmin = false;
if (isset($loggedInUserId)) {
    $stmt = $conn->prepare("SELECT role_id FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if ($user['role_id'] == 5) { // Assuming 5 is the admin role_id
            $isAdmin = true;
        }
    }
    $stmt->close();
}

if (!$isAdmin) {
    redirect_with_message('error', 'Access Denied. You do not have administrative privileges to use this function.');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    // Validate inputs
    if (!$user_id || $user_id !== $loggedInUserId) {
        redirect_with_message('error', 'Invalid user ID or unauthorized request.');
    }
    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        redirect_with_message('error', 'All password fields are required.');
    }
    if ($new_password !== $confirm_new_password) {
        redirect_with_message('error', 'New password and confirm new password do not match.');
    }

    // Fetch password requirements from app_settings
    $stmt_settings = $conn->prepare("SELECT setting_key, setting_value FROM app_settings WHERE setting_key IN ('password_min_length', 'password_require_special')");
    $stmt_settings->execute();
    $result_settings = $stmt_settings->get_result();
    $settings = [];
    while ($row = $result_settings->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    $stmt_settings->close();

    $min_length = isset($settings['password_min_length']) ? (int)$settings['password_min_length'] : 8;
    $require_special = isset($settings['password_require_special']) ? (bool)$settings['password_require_special'] : false;

    if (strlen($new_password) < $min_length) {
        redirect_with_message('error', 'New password must be at least ' . $min_length . ' characters long.');
    }
    if ($require_special && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
        redirect_with_message('error', 'New password must contain at least one special character.');
    }


    // Fetch current password hash from the database
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    if (!$stmt) {
        error_log("Password change prepare failed: " . $conn->error);
        redirect_with_message('error', 'Database error occurred. Please try again.');
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt->close();
        redirect_with_message('error', 'User not found.');
    }

    $user = $result->fetch_assoc();
    $stored_password_hash = $user['password_hash'];
    $stmt->close();

    // Verify current password
    if (!password_verify($current_password, $stored_password_hash)) {
        redirect_with_message('error', 'Incorrect current password.');
    }

    // Hash the new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
    if (!$stmt) {
        error_log("Password update prepare failed: " . $conn->error);
        redirect_with_message('error', 'Database error occurred. Please try again.');
    }
    $stmt->bind_param("si", $new_password_hash, $user_id);

    if ($stmt->execute()) {
        redirect_with_message('success', 'Password changed successfully!');
    } else {
        error_log("Password update execute failed: " . $stmt->error);
        redirect_with_message('error', 'Failed to change password. Please try again.');
    }

    $stmt->close();
} else {
    redirect_with_message('error', 'Invalid request method.');
}

$conn->close();
?>
