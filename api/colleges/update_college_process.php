<?php
// api/colleges/update_college_process.php
// This file handles the form submission for updating an existing college.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../dbinclude.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_id = filter_input(INPUT_POST, 'college_id', FILTER_VALIDATE_INT);
    $college_name = trim($_POST['college_name'] ?? '');
    // college_code is readonly in edit mode, so we just retrieve it for validation/consistency
    $college_code = trim($_POST['college_code'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $errors = [];

    if ($college_id === false || $college_id <= 0) {
        $errors[] = "Invalid College ID for update.";
    }

    // Server-side validation (similar to add process)
    if (empty($college_name)) {
        $errors[] = "College Name is required.";
    } elseif (strlen($college_name) < 3) {
        $errors[] = "College Name must be at least 3 characters long.";
    }

    if (empty($college_code)) {
        $errors[] = "College Code is required.";
    } elseif (strlen($college_code) < 2 || !preg_match('/^[A-Z0-9]+$/', $college_code)) {
        $errors[] = "College Code must be at least 2 alphanumeric characters (uppercase).";
    }

    if (empty($address)) {
        $errors[] = "Address is required.";
    } elseif (strlen($address) < 10) {
        $errors[] = "Address must be at least 10 characters long.";
    }

    // Check if college_name already exists for a DIFFERENT college_id
    if (empty($errors)) {
        $stmt_check_name = $conn->prepare("SELECT COUNT(*) FROM colleges WHERE college_name = ? AND college_id != ?");
        $stmt_check_name->bind_param("si", $college_name, $college_id);
        $stmt_check_name->execute();
        $stmt_check_name->bind_result($count);
        $stmt_check_name->fetch();
        $stmt_check_name->close();

        if ($count > 0) {
            $errors[] = "College Name already exists for another college. Please use a unique name.";
        }
    }

    // If no validation errors, proceed with database update
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE colleges SET college_name = ?, address = ?, updated_at = NOW() WHERE college_id = ?");

        if ($stmt === false) {
            error_log("Failed to prepare update statement: " . $conn->error);
            $message = "Database error: Could not prepare update statement.";
            header("Location: ../../content/adminview/add_college.php?college_id=" . urlencode($college_id) . "&status=error&message=" . urlencode($message));
            exit();
        }

        $stmt->bind_param("ssi", $college_name, $address, $college_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $message = "College updated successfully!";
            } else {
                $message = "No changes made or college not found.";
            }
            header("Location: ../../admin_dashboard.php?section=colleges&status=success&message=" . urlencode($message));
            exit();
        } else {
            error_log("Error updating college data: " . $stmt->error);
            $message = "Error updating college: " . $stmt->error;
            header("Location: ../../content/adminview/add_college.php?college_id=" . urlencode($college_id) . "&status=error&message=" . urlencode($message));
            exit();
        }

        $stmt->close();

    } else {
        $message = implode("<br>", $errors);
        header("Location: ../../content/adminview/add_college.php?college_id=" . urlencode($college_id) . "&status=error&message=" . urlencode($message));
        exit();
    }
} else {
    $message = "Invalid request method.";
    header("Location: ../../content/adminview/add_college.php?status=error&message=" . urlencode($message));
    exit();
}

if (isset($conn) && $conn) {
    $conn->close();
}
?>
