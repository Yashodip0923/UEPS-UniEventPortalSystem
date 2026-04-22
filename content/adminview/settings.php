<?php
// settings.php
// Admin Settings Management Page

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config.php'; // Make sure config.php defines base_url

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in settings.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

// Ensure $loggedInUserId is set (from header.php)
if (!isset($loggedInUserId)) {
    header("Location: login.php?error=not_authenticated");
    exit("User not authenticated. Please log in.");
}

// Check if the logged-in user has admin role (assuming role_id 5 is admin)
// You might need to adjust this based on your actual role management logic
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
    // Redirect to a non-admin page or show an access denied message
    header("Location: dashboard.php?status=error&message=" . urlencode("Access Denied. You do not have administrative privileges."));
    exit();
}


$success_message = '';
$error_message = '';

if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = htmlspecialchars($_GET['message']);
    if ($status === 'success') {
        $success_message = $message;
    } else {
        $error_message = $message;
    }
}

// Fetch current settings from DB or set defaults
$settings_from_db = [];
$result = $conn->query("SELECT setting_key, setting_value FROM app_settings");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $settings_from_db[$row['setting_key']] = $row['setting_value'];
    }
    $result->free();
} else {
    error_log("Failed to fetch app settings: " . $conn->error);
}

// Define default values and override with DB values if available
$current_auto_logout_time = isset($settings_from_db['auto_logout_time']) ? (int)$settings_from_db['auto_logout_time'] : 30;
$current_password_min_length = isset($settings_from_db['password_min_length']) ? (int)$settings_from_db['password_min_length'] : 8;
$current_password_require_special = isset($settings_from_db['password_require_special']) ? (bool)$settings_from_db['password_require_special'] : false;

// Existing new settings fields
$current_site_title = isset($settings_from_db['site_title']) ? htmlspecialchars($settings_from_db['site_title']) : "UniEventPortal";
$current_contact_email = isset($settings_from_db['contact_email']) ? htmlspecialchars($settings_from_db['contact_email']) : "info@unieventportal.com";
$current_maintenance_mode = isset($settings_from_db['maintenance_mode']) ? (bool)$settings_from_db['maintenance_mode'] : false;
$current_email_notifications = isset($settings_from_db['email_notifications']) ? (bool)$settings_from_db['email_notifications'] : true; // New field

// General settings fields
$current_date_format = isset($settings_from_db['date_format']) ? htmlspecialchars($settings_from_db['date_format']) : "Y-m-d";
$current_time_format = isset($settings_from_db['time_format']) ? htmlspecialchars($settings_from_db['time_format']) : "H:i";
$current_timezone = isset($settings_from_db['timezone']) ? htmlspecialchars($settings_from_db['timezone']) : "Asia/Kolkata";

// Appearance settings fields
$current_site_logo_url = isset($settings_from_db['site_logo_url']) ? htmlspecialchars($settings_from_db['site_logo_url']) : "assets/images/websiteLogo.png";

// User Registration settings fields
$current_default_user_role = isset($settings_from_db['default_user_role']) ? (int)$settings_from_db['default_user_role'] : 1;
$current_approval_required_for_new_users = isset($settings_from_db['approval_required_for_new_users']) ? (bool)$settings_from_db['approval_required_for_new_users'] : false;

// New fields: Social Media Links & Analytics Tracking ID
$current_facebook_url = isset($settings_from_db['facebook_url']) ? htmlspecialchars($settings_from_db['facebook_url']) : "https://facebook.com/unieventportal";
$current_twitter_url = isset($settings_from_db['twitter_url']) ? htmlspecialchars($settings_from_db['twitter_url']) : "https://twitter.com/unieventportal";
$current_linkedin_url = isset($settings_from_db['linkedin_url']) ? htmlspecialchars($settings_from_db['linkedin_url']) : "https://linkedin.com/company/unieventportal";
$current_google_analytics_id = isset($settings_from_db['google_analytics_id']) ? htmlspecialchars($settings_from_db['google_analytics_id']) : "UA-XXXXX-Y";


// Fetch user roles for the dropdown (assuming 'roles' table exists)
$user_roles = [];
$result_roles = $conn->query("SELECT role_id, role_name FROM roles ORDER BY role_id ASC");
if ($result_roles) {
    while ($row = $result_roles->fetch_assoc()) {
        $user_roles[] = $row;
    }
    $result_roles->free();
} else {
    error_log("Failed to fetch user roles: " . $conn->error);
}

// Common timezones for dropdown (a simplified list)
$timezones = [
    'Asia/Kolkata' => 'Asia/Kolkata (IST, UTC+5:30)',
    'America/New_York' => 'America/New_York (EST, UTC-5:00)',
    'Europe/London' => 'Europe/London (GMT/BST, UTC+0:00/UTC+1:00)',
    'Asia/Tokyo' => 'Asia/Tokyo (JST, UTC+9:00)',
    'Australia/Sydney' => 'Australia/Sydney (AEST, UTC+10:00)',
    // Add more timezones as needed
];

?>

<style>
    /* Add any specific styles for settings.php here if needed */
    .card-header {
        background-color: #6610f2;
        color: white;
        font-weight: bold;
    }

    .current-logo-preview {
        max-width: 150px;
        height: auto;
        display: block;
        margin-top: 10px;
        border: 1px solid #ddd;
        padding: 5px;
        background-color: #f8f9fa;
    }

    /* Disable text selection globally */
    body {
        user-select: none ;
        -webkit-user-select: none;
        -moz-user-select: none;
    }

    /* Allow selection on buttons, links, inputs, textareas */
    a,
    button,
    input,
    textarea {
        user-select: auto !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="row px-3">
        <div class="col-12">
            <h1 class="mb-4">Admin Settings</h1>

            <div id="alertContainer">
                <?php if ($success_message): ?>
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card shadow-lg rounded-3 mb-4">
                <div class="card-header">
                    Change Password
                </div>
                <div class="card-body">
                    <form id="changePasswordForm" action="<?php echo base_url . 'api/users/admin_change_password.php'; ?>"
                        method="POST" novalidate>
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($loggedInUserId); ?>">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password"
                                required>
                            <div class="invalid-feedback">
                                Please enter your current password.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" required
                                pattern=".{8,}" title="Password must be at least 8 characters long">
                            <div class="invalid-feedback">
                                New password must be at least 8 characters long.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmNewPassword"
                                name="confirm_new_password" required>
                            <div class="invalid-feedback">
                                Please confirm your new password.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">Change
                            Password</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-lg rounded-3 mb-4">
                <div class="card-header">
                    General Application Settings
                </div>
                <div class="card-body">
                    <form id="generalSettingsForm" action="<?php echo base_url . 'api/admin/update_general_settings.php'; ?>" method="POST">
                        <div class="mb-3">
                            <label for="siteTitle" class="form-label">Site Title</label>
                            <input type="text" class="form-control" id="siteTitle" name="site_title"
                                value="<?php echo htmlspecialchars($current_site_title); ?>" required>
                            <small class="form-text text-muted">The main title or name of your portal.</small>
                        </div>
                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contactEmail" name="contact_email"
                                value="<?php echo htmlspecialchars($current_contact_email); ?>" required>
                            <small class="form-text text-muted">The email address for general inquiries and system
                                notifications.</small>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="maintenanceMode" name="maintenance_mode"
                                <?php echo $current_maintenance_mode ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="maintenanceMode">Enable Maintenance Mode</label>
                            <small class="form-text text-muted">Toggle to put the website in maintenance mode. Only
                                administrators will be able to access the site.</small>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotifications"
                                name="email_notifications" <?php echo $current_email_notifications ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="emailNotifications">Enable Email Notifications</label>
                            <small class="form-text text-muted">Toggle to enable or disable system email notifications.</small>
                        </div>
                        <div class="mb-3">
                            <label for="autoLogoutTime" class="form-label">Auto Logout Time (minutes)</label>
                            <input type="number" class="form-control" id="autoLogoutTime" name="auto_logout_time"
                                value="<?php echo htmlspecialchars($current_auto_logout_time); ?>" min="1" max="120"
                                required>
                            <small class="form-text text-muted">Session will expire after this many minutes of
                                inactivity (min: 1, max: 120).</small>
                        </div>
                        <div class="mb-3">
                            <label for="dateFormat" class="form-label">Date Format</label>
                            <select class="form-select" id="dateFormat" name="date_format">
                                <option value="Y-m-d" <?php echo ($current_date_format == 'Y-m-d') ? 'selected' : ''; ?>>
                                    YYYY-MM-DD (e.g., 2025-07-09)</option>
                                <option value="d-m-Y" <?php echo ($current_date_format == 'd-m-Y') ? 'selected' : ''; ?>>
                                    DD-MM-YYYY (e.g., 09-07-2025)</option>
                                <option value="m/d/Y" <?php echo ($current_date_format == 'm/d/Y') ? 'selected' : ''; ?>>
                                    MM/DD/YYYY (e.g., 07/09/2025)</option>
                            </select>
                            <small class="form-text text-muted">Choose how dates are displayed throughout the
                                application.</small>
                        </div>
                        <div class="mb-3">
                            <label for="timeFormat" class="form-label">Time Format</label>
                            <select class="form-select" id="timeFormat" name="time_format">
                                <option value="H:i" <?php echo ($current_time_format == 'H:i') ? 'selected' : ''; ?>>
                                    24-hour (HH:MM)</option>
                                <option value="h:i A" <?php echo ($current_time_format == 'h:i A') ? 'selected' : ''; ?>>
                                    12-hour (HH:MM AM/PM)</option>
                            </select>
                            <small class="form-text text-muted">Choose how times are displayed.</small>
                        </div>
                        <div class="mb-3">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <?php foreach ($timezones as $tz_id => $tz_name): ?>
                                    <option value="<?php echo htmlspecialchars($tz_id); ?>" <?php echo ($current_timezone == $tz_id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tz_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Set the default timezone for the application.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">Save
                            General Settings</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-lg rounded-3 mb-4">
                <div class="card-header">
                    Appearance Settings
                </div>
                <div class="card-body">
                    <form id="appearanceSettingsForm" action="<?php echo base_url . 'api/admin/update_appearance_settings.php'; ?>" method="POST"
                        enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="siteLogo" class="form-label">Site Logo</label>
                            <?php if (!empty($current_site_logo_url)): ?>
                                <img src="<?php echo htmlspecialchars($current_site_logo_url); ?>" alt="Current Site Logo"
                                    class="current-logo-preview mb-2">
                                <small class="form-text text-muted d-block">Current Logo</small>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="siteLogo" name="site_logo" accept="image/*">
                            <small class="form-text text-muted">Upload a new logo for your website (e.g., PNG,
                                JPG). Max size: 2MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">Save
                            Appearance Settings</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-lg rounded-3 mb-4">
                <div class="card-header">
                    User Registration Settings
                </div>
                <div class="card-body">
                    <form id="userRegistrationSettingsForm" action="<?php echo base_url . 'api/admin/update_user_registration_settings.php'; ?>"
                        method="POST">
                        <div class="mb-3">
                            <label for="defaultUserRole" class="form-label">Default User Role for New
                                Registrations</label>
                            <select class="form-select" id="defaultUserRole" name="default_user_role">
                                <?php foreach ($user_roles as $role): ?>
                                    <option value="<?php echo htmlspecialchars($role['role_id']); ?>" <?php echo ($current_default_user_role == $role['role_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">The role assigned to new users upon
                                self-registration.</small>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="approvalRequired"
                                name="approval_required_for_new_users" <?php echo $current_approval_required_for_new_users ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="approvalRequired">Require Admin Approval for New
                                Users</label>
                            <small class="form-text text-muted">If enabled, new user registrations will require an
                                administrator to approve them before they can log in.</small>
                        </div>
                        <div class="mb-3">
                            <label for="passwordMinLength" class="form-label">Minimum Password Length</label>
                            <input type="number" class="form-control" id="passwordMinLength" name="password_min_length"
                                value="<?php echo htmlspecialchars($current_password_min_length); ?>" min="6" max="32" required>
                            <small class="form-text text-muted">Minimum number of characters required for user passwords (min: 6, max: 32).</small>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="passwordRequireSpecial" name="password_require_special"
                                <?php echo $current_password_require_special ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="passwordRequireSpecial">Require Special Characters in Password</label>
                            <small class="form-text text-muted">If enabled, passwords must contain at least one special character.</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">Save User
                            Registration Settings</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-lg rounded-3 mb-4">
                <div class="card-header">
                    External Integrations
                </div>
                <div class="card-body">
                    <form id="externalIntegrationsForm"
                        action="<?php echo base_url . 'api/admin/update_external_integrations.php'; ?>" method="POST">
                        <div class="mb-3">
                            <label for="facebookUrl" class="form-label">Facebook Page URL</label>
                            <input type="url" class="form-control" id="facebookUrl" name="facebook_url"
                                value="<?php echo htmlspecialchars($current_facebook_url); ?>"
                                placeholder="e.g., https://facebook.com/yourpage">
                            <small class="form-text text-muted">Link to your organization's Facebook page.</small>
                        </div>
                        <div class="mb-3">
                            <label for="twitterUrl" class="form-label">Twitter Profile URL</label>
                            <input type="url" class="form-control" id="twitterUrl" name="twitter_url"
                                value="<?php echo htmlspecialchars($current_twitter_url); ?>"
                                placeholder="e.g., https://twitter.com/yourhandle">
                            <small class="form-text text-muted">Link to your organization's Twitter (X) profile.</small>
                        </div>
                        <div class="mb-3">
                            <label for="linkedinUrl" class="form-label">LinkedIn Page URL</label>
                            <input type="url" class="form-control" id="linkedinUrl" name="linkedin_url"
                                value="<?php echo htmlspecialchars($current_linkedin_url); ?>"
                                placeholder="e.g., https://linkedin.com/company/yourcompany">
                            <small class="form-text text-muted">Link to your organization's LinkedIn page.</small>
                        </div>
                        <div class="mb-3">
                            <label for="googleAnalyticsId" class="form-label">Google Analytics Tracking ID</label>
                            <input type="text" class="form-control" id="googleAnalyticsId" name="google_analytics_id"
                                value="<?php echo htmlspecialchars($current_google_analytics_id); ?>"
                                placeholder="e.g., UA-XXXXX-Y">
                            <small class="form-text text-muted">Your Google Analytics tracking ID (e.g., UA-XXXXX-Y).</small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">Save
                            External Integrations</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
require_once __DIR__ . "/../../includes/footer.php";
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Form validation for Change Password
        const changePasswordForm = document.getElementById('changePasswordForm');
        if (changePasswordForm) {
            changePasswordForm.addEventListener('submit', function (event) {
                let isValid = true;
                const currentPassword = document.getElementById('currentPassword');
                const newPassword = document.getElementById('newPassword');
                const confirmNewPassword = document.getElementById('confirmNewPassword');

                // Reset custom validity
                currentPassword.setCustomValidity('');
                newPassword.setCustomValidity('');
                confirmNewPassword.setCustomValidity('');

                if (!currentPassword.value) {
                    currentPassword.setCustomValidity('Current password cannot be empty.');
                    isValid = false;
                }

                // Get min password length from the hidden input or default to 8
                const passwordMinLengthInput = document.getElementById('passwordMinLength');
                const minLength = passwordMinLengthInput ? parseInt(passwordMinLengthInput.value) : 8;

                if (newPassword.value.length < minLength) {
                    newPassword.setCustomValidity(`New password must be at least ${minLength} characters long.`);
                    isValid = false;
                }

                // Check for special character requirement
                const passwordRequireSpecial = document.getElementById('passwordRequireSpecial');
                if (passwordRequireSpecial && passwordRequireSpecial.checked) {
                    const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;
                    if (!specialCharPattern.test(newPassword.value)) {
                        newPassword.setCustomValidity('New password must contain at least one special character (!@#$%^&*(),.?":{}|<>).');
                        isValid = false;
                    }
                }


                if (newPassword.value !== confirmNewPassword.value) {
                    confirmNewPassword.setCustomValidity('Passwords do not match.');
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                changePasswordForm.classList.add('was-validated');
            }, false);
        }

        // Display success/error messages from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const message = urlParams.get('message');
        const alertContainer = document.getElementById('alertContainer');

        if (status && message && alertContainer) {
            let alertClass = '';
            if (status === 'success') {
                alertClass = 'alert-success';
            } else {
                alertClass = 'alert-danger';
            }
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show text-center" role="alert">
                    ${decodeURIComponent(message)}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertContainer.innerHTML = alertHtml;

            // Remove URL parameters after display to prevent re-showing on refresh
            setTimeout(() => {
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.delete('status');
                newUrl.searchParams.delete('message');
                window.history.replaceState({}, document.title, newUrl.toString());
            }, 3000); // Remove after 3 seconds
        }
    });
</script>
