<?php

require_once __DIR__ . '/../dbinclude.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is missing.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($new_password)) {
        $errors[] = "New Password is required.";
    } elseif (strlen($new_password) < 8) {
        $errors[] = "New Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $new_password)) {
        $errors[] = "New Password must contain an uppercase letter.";
    } elseif (!preg_match("/[a-z]/", $new_password)) {
        $errors[] = "New Password must contain a lowercase letter.";
    } elseif (!preg_match("/[0-9]/", $new_password)) {
        $errors[] = "New Password must contain a number.";
    } elseif (!preg_match("/[^A-Za-z0-9]/", $new_password)) {
        $errors[] = "New Password must contain a special character.";
    }

    if (empty($confirm_new_password)) {
        $errors[] = "Confirm New Password is required.";
    } elseif ($new_password !== $confirm_new_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!empty($errors)) {
        $queryString = http_build_query([
            'status' => 'error',
            'message' => implode("<br>", $errors),
            'email' => $email
        ]);
        header("Location: ../../reset_new_password.php?" . $queryString);
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    if ($stmt === false) {
        error_log("Reset password prepare statement failed: " . $conn->error);
        header("Location: ../../reset_new_password.php?status=error&message=" . urlencode("An internal error occurred. Please try again later.") . "&email=" . urlencode($email));
        exit();
    }
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            header("Location: ../../login.php?status=success&message=" . urlencode("Your password has been successfully reset. Please log in with your new password."));
            exit();
        } else {
            header("Location: ../../reset_new_password.php?status=error&message=" . urlencode("Failed to update password. User not found or password is the same.") . "&email=" . urlencode($email));
            exit();
        }
    } else {
        error_log("Reset password execute failed: " . $stmt->error);
        header("Location: ../../reset_new_password.php?status=error&message=" . urlencode("Failed to reset password. Please try again. Error: " . $stmt->error) . "&email=" . urlencode($email));
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../../login.php");
    exit();
}

$conn->close();
?>
