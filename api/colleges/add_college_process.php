<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../dbinclude.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_name = trim($_POST['college_name'] ?? '');
    $college_code = trim($_POST['college_code'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $errors = [];

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

    if (empty($errors)) {
        $stmt_check_code = $conn->prepare("SELECT COUNT(*) FROM colleges WHERE college_code = ?");
        $stmt_check_code->bind_param("s", $college_code);
        $stmt_check_code->execute();
        $stmt_check_code->bind_result($count);
        $stmt_check_code->fetch();
        $stmt_check_code->close();

        if ($count > 0) {
            $errors[] = "College Code already exists. Please use a unique code.";
        }
    }

    if (empty($errors)) {
        $stmt_check_name = $conn->prepare("SELECT COUNT(*) FROM colleges WHERE college_name = ?");
        $stmt_check_name->bind_param("s", $college_name);
        $stmt_check_name->execute();
        $stmt_check_name->bind_result($count);
        $stmt_check_name->fetch();
        $stmt_check_name->close();

        if ($count > 0) {
            $errors[] = "College Name already exists. Please use a unique name.";
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO colleges (college_name, college_code, address) VALUES (?, ?, ?)");

        if ($stmt === false) {
            error_log("Failed to prepare statement for adding college: " . $conn->error);
            $message = "Database error: Could not prepare statement.";
            header("Location: ../../content/adminview/add_college.php?status=error&message=" . urlencode($message));
            exit();
        }

        $stmt->bind_param("sss", $college_name, $college_code, $address);

        if ($stmt->execute()) {
            $message = "College added successfully!";
            // Changed redirect to add_college.php
            header("Location: ../../content/adminview/add_college.php?status=success&message=" . urlencode($message));
            exit();
        } else {
            error_log("Error inserting college data: " . $stmt->error);
            $message = "Error adding college: " . $stmt->error;
            header("Location: ../../content/adminview/add_college.php?status=error&message=" . urlencode($message));
            exit();
        }

        $stmt->close();

    } else {
        $message = implode("<br>", $errors);
        header("Location: ../../content/adminview/add_college.php?status=error&message=" . urlencode($message));
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
