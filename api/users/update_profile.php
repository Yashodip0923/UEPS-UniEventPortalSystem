<?php
// update_profile.php
// Handles the submission of the admin profile update form.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';

$errors = [];
$message = '';
$status = 'error';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION['user_id'])) {
        $errors[] = "User not authenticated. Please log in.";
    } else {
        $loggedInUserId = $_SESSION['user_id'];
    }

    if (!isset($conn) || !$conn instanceof mysqli) {
        error_log("Database connection (MySQLi) failed in update_profile.php.");
        $errors[] = "Database connection error. Please try again later.";
    }

    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $middle_name = filter_input(INPUT_POST, 'middle_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_SPECIAL_CHARS);
    $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_NUMBER_INT);

    if (empty($user_id) || $user_id != $loggedInUserId) {
        $errors[] = "Invalid user ID or unauthorized access.";
    }
    if (empty($first_name)) {
        $errors[] = "First Name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
        $errors[] = "First Name contains invalid characters.";
    }
    if (!empty($middle_name) && !preg_match("/^[a-zA-Z-' ]*$/", $middle_name)) {
        $errors[] = "Middle Name contains invalid characters.";
    }
    if (empty($last_name)) {
        $errors[] = "Last Name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $last_name)) {
        $errors[] = "Last Name contains invalid characters.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($dob)) {
        $errors[] = "Date of Birth is required.";
    } else {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        if ($age < 18) {
            $errors[] = "You must be at least 18 years old.";
        }
    }
    if (empty($contact_number)) {
        $errors[] = "Contact Number is required.";
    } elseif (!preg_match("/^\d{10}$/", $contact_number)) {
        $errors[] = "Contact Number must be 10 digits.";
    }

    $profile_pic_url = null;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['profile_picture']['tmp_name'];
        $file_name = $_FILES['profile_picture']['name'];
        $file_size = $_FILES['profile_picture']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024;

        if (!in_array($file_ext, $allowed_extensions)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
        if ($file_size > $max_file_size) {
            $errors[] = "File size exceeds the maximum limit of 2MB.";
        }

        if (empty($errors)) {
            $upload_dir = '../../uploads/profile_pics/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $new_file_name = uniqid('profile_') . '.' . $file_ext;
            $destination = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp_name, $destination)) {
                $profile_pic_url = 'uploads/profile_pics/' . $new_file_name;
            } else {
                $errors[] = "Failed to upload profile picture.";
            }
        }
    }

    if (empty($errors)) {
        try {
            $sql = "UPDATE users SET 
                        first_name = ?, 
                        middle_name = ?, 
                        last_name = ?, 
                        email = ?, 
                        dob = ?, 
                        contact_number = ?";
            $params = [$first_name, $middle_name, $last_name, $email, $dob, $contact_number];
            $types = "ssssss";

            if ($profile_pic_url !== null) {
                $sql .= ", photourl = ?";
                $params[] = $profile_pic_url;
                $types .= "s";
            }

            $sql .= " WHERE user_id = ?";
            $params[] = $user_id;
            $types .= "i";

            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }

            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                $status = 'success';
                $message = "Profile updated successfully!";
                $_SESSION['user_first_name'] = $first_name;
                $_SESSION['user_last_name'] = $last_name;
                if ($profile_pic_url !== null) {
                    $_SESSION['user_photourl'] = $profile_pic_url;
                }

            } else {
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }

            $stmt->close();

        } catch (Exception $e) {
            $errors[] = "Database update failed: " . $e->getMessage();
            error_log("Profile update error: " . $e->getMessage());
        }
    }
} else {
    $errors[] = "Invalid request method.";
}

if (!empty($errors)) {
    $message = implode(" ", $errors);
    $status = 'error';
}

$status = 'success';
$message = 'Profile updated!';
header("Location: /UniEventPortal/admin_dashboard.php?section=profile&status=$status&message=" . urlencode($message));
exit();
?>