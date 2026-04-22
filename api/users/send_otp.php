<?php

require_once __DIR__ . '/../dbinclude.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));

    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!empty($errors)) {
        $errorMessage = implode("<br>", $errors);
        header("Location: ../../forgot_password.php?status=error&message=" . urlencode($errorMessage) . "&email=" . urlencode($email));
        exit();
    }

    $stmt_check_user = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    if ($stmt_check_user === false) {
        error_log("Send OTP prepare statement (check user) failed: " . $conn->error);
        header("Location: ../../forgot_password.php?status=error&message=" . urlencode("An internal error occurred. Please try again later."));
        exit();
    }
    $stmt_check_user->bind_param("s", $email);
    $stmt_check_user->execute();
    $stmt_check_user->store_result();

    if ($stmt_check_user->num_rows === 0) {
        header("Location: ../../forgot_password.php?status=error&message=" . urlencode("No account found with that email address.") . "&email=" . urlencode($email));
        exit();
    }
    $stmt_check_user->close();

    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $stmt_delete_otp = $conn->prepare("DELETE FROM reset_passwords WHERE email = ?");
    if ($stmt_delete_otp === false) {
        error_log("Send OTP prepare statement (delete old OTP) failed: " . $conn->error);
    } else {
        $stmt_delete_otp->bind_param("s", $email);
        $stmt_delete_otp->execute();
        $stmt_delete_otp->close();
    }

    $stmt_insert_otp = $conn->prepare("INSERT INTO reset_passwords (email, otp, expires_at) VALUES (?, ?, ?)");
    if ($stmt_insert_otp === false) {
        error_log("Send OTP prepare statement (insert new OTP) failed: " . $conn->error);
        header("Location: ../../forgot_password.php?status=error&message=" . urlencode("An internal error occurred. Please try again later."));
        exit();
    }
    $stmt_insert_otp->bind_param("sss", $email, $otp, $expires_at);

    if ($stmt_insert_otp->execute()) {
        header("Location: ../../verify_otp.php?status=success&message=" . urlencode("An OTP has been sent to your registered email. Please check your inbox/spam. (Simulated OTP: " . $otp . ")") . "&email=" . urlencode($email));
        exit();
    } else {
        error_log("Send OTP execute statement failed: " . $stmt_insert_otp->error);
        header("Location: ../../forgot_password.php?status=error&message=" . urlencode("Failed to send OTP. Please try again. Error: " . $stmt_insert_otp->error) . "&email=" . urlencode($email));
        exit();
    }

    $stmt_insert_otp->close();
} else {
    header("Location: ../../forgot_password.php");
    exit();
}

$conn->close();
?>
