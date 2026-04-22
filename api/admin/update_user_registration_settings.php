<?php
// api/admin/update_user_registration_settings.php
session_start();

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';

// Function to redirect with status message
function redirect_with_message($status, $message) {
    $redirect_url = base_url . 'admin_dashboard.php?section=settings&status=' . $status . '&message=' . urlencode($message);
    header("Location: " . $redirect_url);
    exit();
}

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    redirect_with_message('error', 'You must be logged in to access this page.');
}

$loggedInUserId = $_SESSION['user_id'];
$isAdmin = false;
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

if (!$isAdmin) {
    redirect_with_message('error', 'Access Denied. You do not have administrative privileges.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings_to_update = [];

    // Sanitize and validate inputs
    $settings_to_update['default_user_role'] = filter_input(INPUT_POST, 'default_user_role', FILTER_VALIDATE_INT);
    $settings_to_update['approval_required_for_new_users'] = isset($_POST['approval_required_for_new_users']) ? 1 : 0;
    $settings_to_update['password_min_length'] = filter_input(INPUT_POST, 'password_min_length', FILTER_VALIDATE_INT, ['options' => ['min_range' => 6, 'max_range' => 32]]);
    $settings_to_update['password_require_special'] = isset($_POST['password_require_special']) ? 1 : 0;

    // Basic validation check
    if ($settings_to_update['default_user_role'] === false || $settings_to_update['password_min_length'] === false) {
        redirect_with_message('error', 'Please fill all required fields correctly.');
    }

    // Prepare and execute the update/insert statements
    $success = true;
    $stmt = $conn->prepare("INSERT INTO app_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    if (!$stmt) {
        error_log("User registration settings prepare failed: " . $conn->error);
        redirect_with_message('error', 'Database error occurred. Please try again.');
    }

    foreach ($settings_to_update as $key => $value) {
        // Convert boolean values to '0' or '1' string for storage
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } elseif (is_int($value)) {
            $value = (string)$value;
        }

        $stmt->bind_param("sss", $key, $value, $value);
        if (!$stmt->execute()) {
            error_log("Failed to update setting '{$key}': " . $stmt->error);
            $success = false;
            break;
        }
    }
    $stmt->close();

    if ($success) {
        redirect_with_message('success', 'User registration settings saved successfully!');
    } else {
        redirect_with_message('error', 'Failed to save some user registration settings.');
    }

} else {
    redirect_with_message('error', 'Invalid request method.');
}

$conn->close();
?>
