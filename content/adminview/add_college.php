<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config.php';

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in add_college.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

if (!isset($loggedInUserId)) {
    die("User not authenticated. Please log in.");
}

$success_message = '';
$error_message = '';
$college = null; // Initialize college data to null
$isEditing = false; // Flag to determine if in edit mode

// Check for existing college ID in GET request for editing
if (isset($_GET['college_id'])) {
    $collegeId = filter_var($_GET['college_id'], FILTER_VALIDATE_INT);
    if ($collegeId) {
        $isEditing = true;
        // Fetch college data from database
        $stmt = $conn->prepare("SELECT * FROM colleges WHERE college_id = ? AND status = 1");
        if ($stmt) {
            $stmt->bind_param("i", $collegeId);
            $stmt->execute();
            $result = $stmt->get_result();
            $college = $result->fetch_assoc();
            $stmt->close();

            if (!$college) {
                // College not found or not active, redirect with error
                header('Location: ' . base_url . 'admin_dashboard.php?section=colleges&status=error&message=' . urlencode('College not found.'));
                exit();
            }
        } else {
            error_log("Failed to prepare statement for fetching college: " . $conn->error);
            $error_message = 'Failed to load college data.';
        }
    }
}

// Handle messages from redirection
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = htmlspecialchars($_GET['message']);
    if ($status === 'success' || $status === 'info') {
        $success_message = $message;
    } else {
        $error_message = $message;
    }
}

$conn->close();
?>

<style>
    .form-label {
        font-weight: 500;
    }

    .card {
        border-radius: 0.75rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
    }

    .form-control:focus {
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
            <h1 class="mb-4"><?php echo $isEditing ? 'Edit College' : 'Add New College'; ?></h1>

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
                    <h5 class="card-title mb-3">College Details</h5>
                    <form id="collegeForm" action="<?php echo $isEditing ? base_url . 'api/admin/update_college_process.php' : base_url . 'api/admin/add_college_process.php'; ?>" method="POST" novalidate>

                        <?php if ($isEditing): ?>
                            <input type="hidden" name="college_id" value="<?php echo htmlspecialchars($college['college_id']); ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="collegeName" class="form-label">College Name</label>
                            <input type="text" class="form-control" id="collegeName" name="college_name" required
                                value="<?php echo htmlspecialchars($college['college_name'] ?? ''); ?>">
                            <span id="collegeNameError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="collegeCode" class="form-label">College Code</label>
                            <input type="text" class="form-control" id="collegeCode" name="college_code" required
                                value="<?php echo htmlspecialchars($college['college_code'] ?? ''); ?>">
                            <span id="collegeCodeError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"
                                required><?php echo htmlspecialchars($college['address'] ?? ''); ?></textarea>
                            <span id="addressError" class="text-danger small"></span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">
                            <?php echo $isEditing ? 'Update College' : 'Add College'; ?>
                        </button>
                        <a href="<?php echo base_url . 'admin_dashboard.php?section=colleges'; ?>"
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

<script src="../../api/js/add_college_validation.js"></script>