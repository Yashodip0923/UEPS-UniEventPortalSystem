<?php
// api/admin/update_general_settings.php
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
    $settings_to_update['site_title'] = filter_input(INPUT_POST, 'site_title', FILTER_SANITIZE_STRING);
    $settings_to_update['contact_email'] = filter_input(INPUT_POST, 'contact_email', FILTER_VALIDATE_EMAIL);
    $settings_to_update['maintenance_mode'] = isset($_POST['maintenance_mode']) ? 1 : 0;
    $settings_to_update['email_notifications'] = isset($_POST['email_notifications']) ? 1 : 0;
    $settings_to_update['auto_logout_time'] = filter_input(INPUT_POST, 'auto_logout_time', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 120]]);
    $settings_to_update['date_format'] = filter_input(INPUT_POST, 'date_format', FILTER_SANITIZE_STRING);
    $settings_to_update['time_format'] = filter_input(INPUT_POST, 'time_format', FILTER_SANITIZE_STRING);
    $settings_to_update['timezone'] = filter_input(INPUT_POST, 'timezone', FILTER_SANITIZE_STRING);

    // Basic validation check
    if (empty($settings_to_update['site_title']) || empty($settings_to_update['contact_email']) || $settings_to_update['auto_logout_time'] === false || empty($settings_to_update['date_format']) || empty($settings_to_update['time_format']) || empty($settings_to_update['timezone'])) {
        redirect_with_message('error', 'Please fill all required fields correctly.');
    }
    if (!filter_var($settings_to_update['contact_email'], FILTER_VALIDATE_EMAIL)) {
        redirect_with_message('error', 'Invalid contact email format.');
    }


    // Prepare and execute the update/insert statements
    $success = true;
    $stmt = $conn->prepare("INSERT INTO app_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    if (!$stmt) {
        error_log("General settings prepare failed: " . $conn->error);
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
        redirect_with_message('success', 'General application settings saved successfully!');
    } else {
        redirect_with_message('error', 'Failed to save some general application settings.');
    }

} else {
    redirect_with_message('error', 'Invalid request method.');
}

$conn->close();
?>
