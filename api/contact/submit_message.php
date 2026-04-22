<?php

require_once __DIR__ . '/../dbinclude.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // <!-- ========== Start Server Side Validation Yash0923 ========== -->
    
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $errors[] = "Only letters, spaces, hyphens, and apostrophes allowed in name.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($subject)) {
        $errors[] = "Subject is required.";
    }

    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    if (!empty($errors)) {
        $errorMessage = implode("<br>", $errors); 
        header("Location: ../../contact.php?status=error&message=" . urlencode($errorMessage));
        exit();
    }
    
    // <!-- ========== End Server Side Validation Yash0923 ========== -->
    

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");

    if ($stmt === false) {
        error_log("Failed to prepare statement: " . $conn->error);
        header("Location: ../../contact.php?status=error&message=" . urlencode("An internal error occurred. Please try again later."));
        exit();
    }

    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        header("Location: ../../contact.php?status=success");
        exit();
    } else {
        error_log("Failed to execute statement: " . $stmt->error);
        header("Location: ../../contact.php?status=error&message=" . urlencode("Failed to send your message. " . $stmt->error));
        exit();
    }

    $stmt->close();
    
} else {
    header("Location: ../../contact.php"); 
    exit();
}

$conn->close();
?>
