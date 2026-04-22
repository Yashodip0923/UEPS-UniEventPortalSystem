<?php

require_once __DIR__ . '/../dbinclude.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $otp = htmlspecialchars(trim($_POST['otp'] ?? ''));

    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is missing.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($otp)) {
        $errors[] = "OTP is required.";
    } elseif (!preg_match("/^\d{6}$/", $otp)) {
        $errors[] = "OTP must be a 6-digit number.";
    }

    if (!empty($errors)) {
        $errorMessage = implode("<br>", $errors);
        header("Location: ../../verify_otp.php?status=error&message=" . urlencode($errorMessage) . "&email=" . urlencode($email));
        exit();
    }

    $current_time = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("SELECT id FROM reset_passwords WHERE email = ? AND otp = ? AND expires_at > ?");
    if ($stmt === false) {
        error_log("Verify OTP prepare statement failed: " . $conn->error);
        header("Location: ../../verify_otp.php?status=error&message=" . urlencode("An internal error occurred. Please try again later.") . "&email=" . urlencode($email));
        exit();
    }
    $stmt->bind_param("sss", $email, $otp, $current_time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt_delete = $conn->prepare("DELETE FROM reset_passwords WHERE email = ?");
        if ($stmt_delete === false) {
            error_log("Verify OTP prepare statement (delete) failed: " . $conn->error);
        } else {
            $stmt_delete->bind_param("s", $email);
            $stmt_delete->execute();
            $stmt_delete->close();
        }

        header("Location: ../../reset_new_password.php?email=" . urlencode($email) . "&status=success&message=" . urlencode("OTP verified successfully. Please set your new password."));
        exit();
    } else {
        header("Location: ../../verify_otp.php?status=error&message=" . urlencode("Invalid or expired OTP. Please try again.") . "&email=" . urlencode($email));
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../../forgot_password.php");
    exit();
}

$conn->close();
?>
