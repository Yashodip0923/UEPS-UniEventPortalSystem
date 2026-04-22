<?php
// api/admin/update_user_process.php
// This script handles updating an existing user in the database, including profile photo upload.
// It expects user details via POST and returns a JSON response.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../dbinclude.php';
require_once __DIR__ . '/../../config.php';

// Define the upload directory relative to this script's location
define('UPLOAD_DIR', __DIR__ . '/../../uploads/profile_photos/');
// Ensure the directory exists and is writable
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Define a default placeholder image path (must match the one in add_user.php)
define('DEFAULT_PROFILE_PHOTO_PATH_DB', 'assets/images/default_profile.png'); // Path to store in DB

// Set content type to JSON for the response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] ?? null) != 5) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. Admin privileges required.']);
    exit();
}

// Sanitize and validate inputs
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
$middle_name = filter_input(INPUT_POST, 'middle_name', FILTER_SANITIZE_STRING);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
$contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
$college_id = filter_input(INPUT_POST, 'college_id', FILTER_VALIDATE_INT);
$dept_id = filter_input(INPUT_POST, 'dept_id', FILTER_VALIDATE_INT);
$role_id = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);
$existing_photo_path = filter_input(INPUT_POST, 'existing_photo_path', FILTER_SANITIZE_STRING); // Get existing path

if (!$user_id || empty($first_name) || empty($last_name) || empty($email) || empty($dob) || empty($contact_number) || !$role_id) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields are missing.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit();
}

// Determine photo path: new upload, existing, or default
$profilePhotoPath = $existing_photo_path; // Start with existing path

if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_photo']['tmp_name'];
    $fileName = $_FILES['profile_photo']['name'];
    $fileSize = $_FILES['profile_photo']['size'];
    $fileType = $_FILES['profile_photo']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];
    if (in_array($fileExtension, $allowedfileExtensions)) {
        // Generate a unique filename
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $destPath = UPLOAD_DIR . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // New photo uploaded successfully, update path
            $profilePhotoPath = 'uploads/profile_photos/' . $newFileName;

            // Optional: Delete old photo if it's not the default and exists
            if (!empty($existing_photo_path) && $existing_photo_path !== DEFAULT_PROFILE_PHOTO_PATH_DB) {
                $oldFilePath = __DIR__ . '/../../' . $existing_photo_path;
                if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                    unlink($oldFilePath); // Delete the old file
                }
            }
        } else {
            error_log("Failed to move uploaded file: " . $fileTmpPath . " to " . $destPath);
            // If upload fails, retain existing photo path or default
            // For AJAX, we can return an error, but for now, we'll just use the old path
            // echo json_encode(['status' => 'error', 'message' => 'Failed to upload profile photo.']);
            // exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type for profile photo. Only JPG, JPEG, PNG, GIF allowed.']);
        exit();
    }
}
// If no new file is uploaded and existing_photo_path was empty, set to default
if (empty($profilePhotoPath)) {
    $profilePhotoPath = DEFAULT_PROFILE_PHOTO_PATH_DB;
}


// Check if database connection is established
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in update_user_process.php.");
    echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // Check if email already exists for another user (only if email changed)
    $stmt_check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ? AND status = 1");
    if ($stmt_check_email === false) {
        throw new Exception("Failed to prepare email check statement: " . $conn->error);
    }
    $stmt_check_email->bind_param("si", $email, $user_id);
    $stmt_check_email->execute();
    $check_email_result = $stmt_check_email->get_result();
    if ($check_email_result->num_rows > 0) {
        throw new Exception("Email already exists for another user. Please use a different email.");
    }
    $stmt_check_email->close();

    // Prepare and execute the UPDATE statement
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, email = ?, dob = ?, contact_number = ?, college_id = ?, dept_id = ?, role_id = ?, photourl = ?, updated_at = NOW() WHERE user_id = ? AND status = 1");
    if ($stmt === false) {
        throw new Exception("Failed to prepare user update statement: " . $conn->error);
    }

    // Handle optional middle_name, college_id, dept_id
    $middle_name_val = empty($middle_name) ? null : $middle_name;
    $college_id_val = ($college_id === 0 || $college_id === null) ? null : $college_id; // If 0 or null is sent for "Select College"
    $dept_id_val = ($dept_id === 0 || $dept_id === null) ? null : $dept_id; // If 0 or null is sent for "Select Department"


    $stmt->bind_param("sssssssiisi",
        $first_name,
        $middle_name_val,
        $last_name,
        $email,
        $dob,
        $contact_number,
        $college_id_val,
        $dept_id_val,
        $role_id,
        $profilePhotoPath,
        $user_id
    );

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'User updated successfully!']);
        } else {
            echo json_encode(['status' => 'info', 'message' => 'No changes made or user not found/active.']);
        }
    } else {
        throw new Exception("Failed to update user: " . $stmt->error);
    }

} catch (Exception $e) {
    $conn->rollback(); // Rollback on error
    error_log("Error updating user (ID: $user_id): " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to update user: ' . $e->getMessage()]);
} finally {
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    if ($conn && $conn->ping()) {
        $conn->close();
    }
    exit();
}
?>
