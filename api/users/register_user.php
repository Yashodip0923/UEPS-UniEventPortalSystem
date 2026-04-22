<?php

require_once __DIR__ . '/../dbinclude.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $middle_name = htmlspecialchars(trim($_POST['middle_name'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $dob = htmlspecialchars(trim($_POST['dob'] ?? ''));
    $contact_number = htmlspecialchars(trim($_POST['contact_number'] ?? ''));
    $college_id = htmlspecialchars(trim($_POST['college_id'] ?? ''));
    $department_id = htmlspecialchars(trim($_POST['department_id'] ?? ''));
    $role_id = 1;

    $errors = [];

    if (empty($first_name)) {
        $errors[] = "First Name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $first_name)) {
        $errors[] = "Only letters, spaces, hyphens, and apostrophes allowed in First Name.";
    }

    if (!empty($middle_name) && !preg_match("/^[a-zA-Z-' ]*$/", $middle_name)) {
        $errors[] = "Only letters, spaces, hyphens, and apostrophes allowed in Middle Name.";
    }

    if (empty($last_name)) {
        $errors[] = "Last Name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $last_name)) {
        $errors[] = "Only letters, spaces, hyphens, and apostrophes allowed in Last Name.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        $stmt_check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $stmt_check_email->store_result();
        if ($stmt_check_email->num_rows > 0) {
            $errors[] = "Email already registered.";
        }
        $stmt_check_email->close();
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Password must contain an uppercase letter.";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Password must contain a lowercase letter.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must contain a number.";
    } elseif (!preg_match("/[^A-Za-z0-9]/", $password)) {
        $errors[] = "Password must contain a special character.";
    }

    if (empty($confirm_password)) {
        $errors[] = "Confirm Password is required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($dob)) {
        $errors[] = "Date of Birth is required.";
    } else {
        $today = new DateTime();
        $birthDate = new DateTime($dob);
        $age = $today->diff($birthDate)->y;
        if ($age < 18) {
            $errors[] = "You must be at least 18 years old to register.";
        }
    }

    if (empty($contact_number)) {
        $errors[] = "Contact Number is required.";
    } elseif (!preg_match("/^\d{10}$/", $contact_number)) {
        $errors[] = "Contact Number must be 10 digits.";
    }

    if (empty($college_id)) {
        $errors[] = "College is required.";
    }

    if (empty($department_id)) {
        $errors[] = "Department is required.";
    }

    if (!empty($errors)) {
        $queryString = http_build_query([
            'status' => 'error',
            'message' => implode("<br>", $errors),
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'email' => $email,
            'dob' => $dob,
            'contact_number' => $contact_number,
            'college_id' => $college_id,
            'department_id' => $department_id
        ]);
        header("Location: ../../register.php?" . $queryString);
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, middle_name, last_name, email, password_hash, dob, contact_number, college_id, dept_id, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Register user prepare statement failed: " . $conn->error);
        header("Location: ../../register.php?status=error&message=" . urlencode("An internal error occurred during registration. Please try again later."));
        exit();
    }

    $stmt->bind_param("sssssssiii", $first_name, $middle_name, $last_name, $email, $hashed_password, $dob, $contact_number, $college_id, $department_id, $role_id);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['role_id'] = $role_id;
        header("Location: ../../login.php?status=success&message=" . urlencode("Registration successful! Welcome to UniEventPortal."));
        exit();
    } else {
        error_log("Register user execute failed: " . $stmt->error);
        header("Location: ../../register.php?status=error&message=" . urlencode("Registration failed. Please try again. Error: " . $stmt->error));
        exit();
    }

    $stmt->close();
} else {
    header(header: "Location: ../../register.php");
    exit();
}

$conn->close();
?>