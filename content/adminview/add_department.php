<?php
// content/adminview/add_department.php
// This file allows adding a new department or editing an existing one.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../includes/header.php'; // This includes your site's main header, likely opening <head> and <body>
require_once __DIR__ . '/../../config.php'; // This should define your 'base_url' constant

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in add_department.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

if (!isset($loggedInUserId)) {
    die("User not authenticated. Please log in.");
}

$success_message = '';
$error_message = '';
$department = null; // Initialize department data to null
$isEditing = false; // Flag to determine if in edit mode

// Fetch active colleges for the dropdown
$collegesQuery = $conn->query("SELECT college_id, college_name FROM colleges WHERE status = 1 ORDER BY college_name ASC");
$activeColleges = [];
if ($collegesQuery) {
    $activeColleges = $collegesQuery->fetch_all(MYSQLI_ASSOC);
} else {
    error_log("Failed to fetch active colleges: " . $conn->error);
    $error_message = 'Failed to load colleges for selection.';
}


// Check for existing department ID in GET request for editing
if (isset($_GET['department_id'])) {
    $departmentId = filter_var($_GET['department_id'], FILTER_VALIDATE_INT);
    if ($departmentId) {
        $isEditing = true;
        // Fetch department data from database along with college name
        $stmt = $conn->prepare("SELECT d.*, c.college_name FROM departments d JOIN colleges c ON d.college_id = c.college_id WHERE d.department_id = ? AND d.status = 1");
        if ($stmt) {
            $stmt->bind_param("i", $departmentId);
            $stmt->execute();
            $result = $stmt->get_result();
            $department = $result->fetch_assoc();
            $stmt->close();

            if (!$department) {
                // Department not found or not active, redirect with error
                header('Location: ' . base_url . 'admin_dashboard.php?section=departments&status=error&message=' . urlencode('Department not found or not active.'));
                exit();
            }
        } else {
            error_log("Failed to prepare statement for fetching department: " . $conn->error);
            $error_message = 'Failed to load department data.';
        }
    }
}

// Handle messages from redirection (e.g., after an add/edit process)
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = htmlspecialchars($_GET['message']);
    if ($status === 'success' || $status === 'info') {
        $success_message = $message;
    } else {
        $error_message = $message;
    }
}

$conn->close(); // Close connection after all data fetching
?>

<style>
    .form-label {
        font-weight: 500;
    }

    .card {
        border-radius: 0.75rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
    }

    .form-control:focus, .form-select:focus { /* Added .form-select */
        border-color: #6610f2;
        box-shadow: 0 0 0 0.25rem rgba(102, 16, 242, 0.25);
    }

    .btn-primary {
        background-color: #6610f2;
        border-color: #6610f2;
    }

    .btn-primary:hover {
        background-color: #550bb7;
        border-color: #550bb7;
    }
     body {
        user-select: none;
        
    }
</style>
<div class="container-fluid py-4">
    <div class="row px-3">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h1 class="mb-4"><?php echo $isEditing ? 'Edit Department' : 'Add New Department'; ?></h1>

            <div id="alertContainer">
                <?php if ($success_message): ?>
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <?php echo $success_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card shadow-lg rounded-3 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Department Details</h5>
                    <form id="departmentForm" action="<?php echo $isEditing ? base_url . 'api/departments/edit_department_process.php' : base_url . 'api/departments/add_department_process.php'; ?>" method="POST" novalidate>

                        <?php if ($isEditing): ?>
                            <input type="hidden" name="department_id" value="<?php echo htmlspecialchars($department['department_id']); ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="departmentName" class="form-label">Department Name</label>
                            <input type="text" class="form-control" id="departmentName" name="department_name" required
                                value="<?php echo htmlspecialchars($department['department_name'] ?? ''); ?>">
                            <span id="departmentNameError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="collegeSelect" class="form-label">Select College</label>
                            <select class="form-select" id="collegeSelect" name="college_id" required>
                                <option value="">-- Select a College --</option>
                                <?php foreach ($activeColleges as $collegeOption): ?>
                                    <option value="<?php echo htmlspecialchars($collegeOption['college_id']); ?>"
                                        <?php echo ($isEditing && isset($department['college_id']) && $department['college_id'] == $collegeOption['college_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($collegeOption['college_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span id="collegeSelectError" class="text-danger small"></span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">
                            <?php echo $isEditing ? 'Update Department' : 'Add Department'; ?>
                        </button>
                        <a href="<?php echo base_url . 'admin_dashboard.php?section=departments'; ?>"
                            class="btn btn-secondary w-100 py-2 rounded-lg fw-semibold mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . "/../../includes/footer.php";
?>

<!-- Bootstrap Bundle with Popper (for modals, dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>

<!-- Custom JavaScript for form validation -->
<script src="<?php echo base_url . 'api/js/add_department_validation.js'; ?>"></script>
