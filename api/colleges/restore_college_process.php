<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../dbinclude.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_id = filter_input(INPUT_POST, 'college_id', FILTER_VALIDATE_INT);

    if ($college_id === false || $college_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid college ID.']);
        exit();
    }

    $stmt = $conn->prepare("UPDATE colleges SET status = 1 WHERE college_id = ?");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: Could not prepare statement.']);
        error_log("Failed to prepare restore statement: " . $conn->error);
        exit();
    }

    $stmt->bind_param("i", $college_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'College restored successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'College not found or already active.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error restoring college: ' . $stmt->error]);
        error_log("Error executing restore: " . $stmt->error);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

if (isset($conn) && $conn) {
    $conn->close();
}
?>
