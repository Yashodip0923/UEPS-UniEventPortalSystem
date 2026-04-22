<?php

require_once __DIR__ . '/../dbinclude.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (!empty($errors)) {
        $errorMessage = implode("<br>", $errors);
        header("Location: ../../login.php?status=error&message=" . urlencode($errorMessage) . "&email=" . urlencode($email));
        exit();
    }

    $stmt = $conn->prepare("SELECT user_id, password_hash, role_id FROM users WHERE email = ?");
    if ($stmt === false) {
        error_log("Login prepare statement failed: " . $conn->error);
        header("Location: ../../login.php?status=error&message=" . urlencode("An internal error occurred. Please try again later."));
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $password_hash, $role_id);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['role_id'] = $role_id;

            switch ($role_id) {
                case 5:
                    header("Location: ../../admin_dashboard.php");
                    break;
                case 2:
                    header("Location: ../../coordinator_dashboard.php");
                    break;
                case 3:
                    header("Location: ../../event_leader_dashboard.php");
                    break;
                case 4:
                    header("Location: ../../organizer_dashboard.php");
                    break;
                case 1:
                default:
                    header("Location: ../../student_dashboard.php.php");
                    break;
            }
            exit();
        } else {
            header("Location: ../../login.php?status=error&message=" . urlencode("Invalid email or password.") . "&email=" . urlencode($email));
            exit();
        }
    } else {
        header("Location: ../../login.php?status=error&message=" . urlencode("Invalid email or password.") . "&email=" . urlencode($email));
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../../login.php");
    exit();
}

$conn->close();
?>
