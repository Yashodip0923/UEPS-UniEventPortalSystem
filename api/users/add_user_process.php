<?php
// api/admin/add_user_process.php
// This script handles adding a new user to the database, including profile photo upload.
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
    mkdir(UPLOAD_DIR, 0777, true); // Create directory recursively with full permissions
}

// Define a default placeholder image URL (must match the one in add_user.php)
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
$first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
$middle_name = filter_input(INPUT_POST, 'middle_name', FILTER_SANITIZE_STRING);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? ''; // Hash password later
$confirm_password = $_POST['confirm_password'] ?? '';
$dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
$contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
$college_id = filter_input(INPUT_POST, 'college_id', FILTER_VALIDATE_INT);
$dept_id = filter_input(INPUT_POST, 'dept_id', FILTER_VALIDATE_INT);
$role_id = filter_input(INPUT_POST, 'role_id', FILTER_VALIDATE_INT);

// Basic validation (more robust validation should be in JS and PHP)
if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password) || empty($dob) || empty($contact_number) || !$role_id) {
    echo json_encode(['status' => 'error', 'message' => 'Required fields are missing.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit();
}

if ($password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Handle profile photo upload
$profilePhotoPath = DEFAULT_PROFILE_PHOTO_PATH_DB; // Default path if no file is uploaded or upload fails

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
            // Store relative path in database
            $profilePhotoPath = 'uploads/profile_photos/' . $newFileName;
        } else {
            error_log("Failed to move uploaded file: " . $fileTmpPath . " to " . $destPath);
            // If upload fails, continue with default photo path
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type for profile photo. Only JPG, JPEG, PNG, GIF allowed.']);
        exit();
    }
}

// Check if database connection is established
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection failed in add_user_process.php.");
    echo json_encode(['status' => 'error', 'message' => 'Database connection error.']);
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // Check if email already exists
    $stmt_check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND status = 1");
    if ($stmt_check_email === false) {
        throw new Exception("Failed to prepare email check statement: " . $conn->error);
    }
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $check_email_result = $stmt_check_email->get_result();
    if ($check_email_result->num_rows > 0) {
        throw new Exception("Email already exists. Please use a different email.");
    }
    $stmt_check_email->close();

    // Prepare and execute the INSERT statement
    $stmt = $conn->prepare("INSERT INTO users (first_name, middle_name, last_name, email, password_hash, dob, contact_number, college_id, dept_id, role_id, photourl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception("Failed to prepare user insert statement: " . $conn->error);
    }

    // Handle optional middle_name, college_id, dept_id
    $middle_name_val = empty($middle_name) ? null : $middle_name;
    $college_id_val = ($college_id === 0 || $college_id === null) ? null : $college_id; // If 0 or null is sent for "Select College"
    $dept_id_val = ($dept_id === 0 || $dept_id === null) ? null : $dept_id; // If 0 or null is sent for "Select Department"

    $stmt->bind_param("sssssssiiss",
        $first_name,
        $middle_name_val, // Use null for empty middle name
        $last_name,
        $email,
        $hashed_password,
        $dob,
        $contact_number,
        $college_id_val, // Use null for empty college
        $dept_id_val,    // Use null for empty department
        $role_id,
        $profilePhotoPath
    );

    if ($stmt->execute()) {
        $conn->commit(); // Commit transaction
        echo json_encode(['status' => 'success', 'message' => 'User added successfully!']);
    } else {
        throw new Exception("Failed to add user: " . $stmt->error);
    }

} catch (Exception $e) {
    $conn->rollback(); // Rollback on error
    error_log("Error adding user: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to add user: ' . $e->getMessage()]);
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
