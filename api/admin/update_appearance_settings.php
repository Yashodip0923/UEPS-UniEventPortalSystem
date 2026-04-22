<?php
// api/admin/update_appearance_settings.php
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
    $upload_dir = __DIR__ . '/../../assets/uploads/'; // Adjust path as necessary
    $new_logo_url = '';

    // Handle site logo upload
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['site_logo']['tmp_name'];
        $file_name = $_FILES['site_logo']['name'];
        $file_size = $_FILES['site_logo']['size'];
        $file_type = $_FILES['site_logo']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($file_ext, $allowed_extensions)) {
            redirect_with_message('error', 'Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.');
        }
        if ($file_size > $max_file_size) {
            redirect_with_message('error', 'File size exceeds 2MB limit.');
        }

        // Generate a unique file name to prevent overwrites
        $new_file_name = uniqid('logo_', true) . '.' . $file_ext;
        $destination_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_name, $destination_path)) {
            $new_logo_url = 'assets/uploads/' . $new_file_name; // Path relative to base_url
        } else {
            error_log("Failed to move uploaded file: " . $file_tmp_name . " to " . $destination_path);
            redirect_with_message('error', 'Failed to upload new logo. Please check server permissions.');
        }
    }

    if (!empty($new_logo_url)) {
        // Update the site_logo_url in app_settings
        $stmt = $conn->prepare("INSERT INTO app_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        if (!$stmt) {
            error_log("Appearance settings prepare failed: " . $conn->error);
            redirect_with_message('error', 'Database error occurred. Please try again.');
        }
        $setting_key = 'site_logo_url';
        $stmt->bind_param("sss", $setting_key, $new_logo_url, $new_logo_url);

        if ($stmt->execute()) {
            redirect_with_message('success', 'Appearance settings saved successfully!');
        } else {
            error_log("Failed to update site_logo_url: " . $stmt->error);
            redirect_with_message('error', 'Failed to save appearance settings.');
        }
        $stmt->close();
    } else {
        redirect_with_message('error', 'No new logo was uploaded or an error occurred during upload.');
    }

} else {
    redirect_with_message('error', 'Invalid request method.');
}

$conn->close();
?>
