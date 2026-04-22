<?php
// api/admin/get_departments_by_college.php
require_once __DIR__ . '/../../api/dbinclude.php'; // Adjust path as necessary
require_once __DIR__ . '/../../config.php'; // Adjust path as necessary

header('Content-Type: application/json');

$departments = [];

if (isset($_GET['college_id'])) {
    $collegeId = filter_var($_GET['college_id'], FILTER_VALIDATE_INT);

    if ($collegeId && isset($conn) && $conn instanceof mysqli && $conn->ping()) {
        $stmt = $conn->prepare("SELECT dept_id, dept_name FROM departments WHERE college_id = ? AND status = 1 ORDER BY dept_name ASC");
        if ($stmt) {
            $stmt->bind_param("i", $collegeId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $departments[] = $row;
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare statement for fetching departments: " . $conn->error);
        }
    }
}

$conn->close(); // Close the database connection
echo json_encode($departments);
?>
