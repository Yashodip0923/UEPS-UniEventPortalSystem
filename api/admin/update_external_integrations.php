<?php
// api/admin/update_external_integrations.php
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
    $settings_to_update['facebook_url'] = filter_input(INPUT_POST, 'facebook_url', FILTER_VALIDATE_URL);
    $settings_to_update['twitter_url'] = filter_input(INPUT_POST, 'twitter_url', FILTER_VALIDATE_URL);
    $settings_to_update['linkedin_url'] = filter_input(INPUT_POST, 'linkedin_url', FILTER_VALIDATE_URL);
    $settings_to_update['google_analytics_id'] = filter_input(INPUT_POST, 'google_analytics_id', FILTER_SANITIZE_STRING);

    // Filter_validate_url returns false if invalid, so check for that
    foreach(['facebook_url', 'twitter_url', 'linkedin_url'] as $url_key) {
        if ($settings_to_update[$url_key] === false) {
            $settings_to_update[$url_key] = ''; // Store as empty string if invalid URL
        }
    }


    // Prepare and execute the update/insert statements
    $success = true;
    $stmt = $conn->prepare("INSERT INTO app_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    if (!$stmt) {
        error_log("External integrations settings prepare failed: " . $conn->error);
        redirect_with_message('error', 'Database error occurred. Please try again.');
    }

    foreach ($settings_to_update as $key => $value) {
        $stmt->bind_param("sss", $key, $value, $value);
        if (!$stmt->execute()) {
            error_log("Failed to update setting '{$key}': " . $stmt->error);
            $success = false;
            break;
        }
    }
    $stmt->close();

    if ($success) {
        redirect_with_message('success', 'External integrations settings saved successfully!');
    } else {
        redirect_with_message('error', 'Failed to save some external integrations settings.');
    }

} else {
    redirect_with_message('error', 'Invalid request method.');
}

$conn->close();
?>
