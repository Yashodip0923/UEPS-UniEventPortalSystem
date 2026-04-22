<?php
// content/adminview/add_user.php
// This file allows adding a new user or editing an existing one.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config.php'; // Ensure config.php is included

// Define a default placeholder image URL (you can change this)
// Make sure this path is accessible from the web root
define('DEFAULT_PROFILE_PHOTO', base_url . 'assets/images/default_profile.png'); // Adjust path as needed

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in add_user.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

if (!isset($loggedInUserId)) {
    die("User not authenticated. Please log in.");
}

$success_message = ''; // These will now be handled by JavaScript
$error_message = '';   // These will now be handled by JavaScript
$user = null; // Initialize user data to null
$isEditing = false; // Flag to determine if in edit mode

// Check for existing user ID in GET request for editing
if (isset($_GET['user_id'])) {
    $userIdToEdit = filter_var($_GET['user_id'], FILTER_VALIDATE_INT);
    if ($userIdToEdit) {
        $isEditing = true;
        // Fetch user data from database based on the provided table structure
        $stmt = $conn->prepare("SELECT user_id, first_name, middle_name, last_name, email, dob, contact_number, college_id, dept_id, role_id, photourl FROM users WHERE user_id = ? AND status = 1");
        if ($stmt) {
            $stmt->bind_param("i", $userIdToEdit);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close(); // Close statement after fetching user data

            if (!$user) {
                // User not found or not active, redirect with error (this is a PHP redirect, not AJAX)
                header('Location: ' . base_url . 'admin_dashboard.php?section=users&status=error&message=' . urlencode('User not found or not active.'));
                exit();
            }
        } else {
            error_log("Failed to prepare statement for fetching user: " . $conn->error);
            $error_message = 'Failed to load user data.';
        }
    }
}

// Handle messages from redirection (these will now be displayed by the JS if form submission is via AJAX)
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = htmlspecialchars($_GET['message']);
    if ($status === 'success' || $status === 'info') {
        $success_message = $message;
    } else {
        $error_message = $message;
    }
}

// --- Fetch Colleges for Dropdown ---
$college_options = [];
// Re-establish connection if it was closed or not available for this part
if (!isset($conn) || !$conn instanceof mysqli || !$conn->ping()) {
    require_once __DIR__ . '/../../api/dbinclude.php'; // Re-include to get $conn
    if (!isset($conn) || !$conn instanceof mysqli) {
        error_log("Database connection (MySQLi) failed during college/role fetch in add_user.php.");
        $error_message = 'Database connection error for colleges/roles. Please try again later.';
    }
}

if (isset($conn) && $conn instanceof mysqli && $conn->ping()) {
    $stmt_colleges = $conn->prepare("SELECT college_id, college_name FROM colleges WHERE status = 1 ORDER BY college_name ASC");
    if ($stmt_colleges) {
        $stmt_colleges->execute();
        $result_colleges = $stmt_colleges->get_result();
        while ($row = $result_colleges->fetch_assoc()) {
            $college_options[] = $row;
        }
        $stmt_colleges->close();
    } else {
        error_log("Failed to prepare statement for fetching colleges: " . $conn->error);
        $error_message = 'Failed to load college data.';
    }

    // --- Fetch Roles for Dropdown ---
    $role_options = [];
    $stmt_roles = $conn->prepare("SELECT role_id, role_name FROM roles ORDER BY role_id ASC");
    if ($stmt_roles) {
        $stmt_roles->execute();
        $result_roles = $stmt_roles->get_result();
        while ($row = $result_roles->fetch_assoc()) {
            $role_options[] = $row;
        }
        $stmt_roles->close();
    } else {
        error_log("Failed to prepare statement for fetching roles: " . $conn->error);
        $error_message = 'Failed to load role data.';
    }
}

$conn->close(); // Close the connection at the end
?>

<style>
    /* Styling from add_college.php, adapted for add_user.php */
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

    /* Style for profile photo preview */
    #profilePhotoPreview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ddd;
        margin-bottom: 15px;
        display: block; /* Ensures it takes its own line */
        margin-left: auto;
        margin-right: auto;
    }
     body {
        user-select: none;
        
    }
</style>

<div class="container-fluid py-4">
    <div class="row px-3">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h1 class="mb-4"><?php echo $isEditing ? 'Edit User' : 'Add New User'; ?></h1>

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
                    <h5 class="card-title mb-3">User Details</h5>
                    <!-- IMPORTANT: Add enctype="multipart/form-data" for file uploads -->
                    <form id="userForm" action="<?php echo $isEditing ? base_url . 'api/admin/update_user_process.php' : base_url . 'api/admin/add_user_process.php'; ?>" method="POST" novalidate enctype="multipart/form-data">

                        <?php if ($isEditing): ?>
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                            <!-- Keep existing photo path if no new photo is uploaded -->
                            <input type="hidden" name="existing_photo_path" value="<?php echo htmlspecialchars($user['photourl'] ?? ''); ?>">
                        <?php endif; ?>

                        <div class="mb-3 text-center">
                            <label for="profilePhoto" class="form-label">Profile Photo</label>
                            <img id="profilePhotoPreview" src="<?php echo htmlspecialchars($user['photourl'] ?? DEFAULT_PROFILE_PHOTO); ?>" alt="Profile Photo Preview">
                            <input type="file" class="form-control mt-2" id="profilePhoto" name="profile_photo" accept="image/*">
                            <span id="profilePhotoError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required
                                value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                            <span id="firstNameError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="middleName" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middleName" name="middle_name"
                                value="<?php echo htmlspecialchars($user['middle_name'] ?? ''); ?>">
                            <span id="middleNameError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required
                                value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                            <span id="lastNameError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            <span id="emailError" class="text-danger small"></span>
                        </div>

                        <?php if (!$isEditing): // Password field only for adding new user ?>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span id="passwordError" class="text-danger small"></span>
                            </div>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                                <span id="confirmPasswordError" class="text-danger small"></span>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob"
                                value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>">
                            <span id="dobError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contactNumber" name="contact_number"
                                value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>">
                            <span id="contactNumberError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="collegeId" class="form-label">College</label>
                            <select class="form-select" id="collegeId" name="college_id">
                                <option value="">Select College</option>
                                <?php foreach ($college_options as $college): ?>
                                    <option value="<?php echo htmlspecialchars($college['college_id']); ?>"
                                        <?php echo (($user['college_id'] ?? '') == $college['college_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($college['college_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span id="collegeIdError" class="text-danger small"></span>
                        </div>

                        <!-- Department field container - Initially hidden -->
                        <div id="departmentFieldContainer" class="mb-3" style="display: none;">
                            <label for="deptId" class="form-label">Department</label>
                            <select class="form-select" id="deptId" name="dept_id">
                                <option value="">Select Department</option>
                                <!-- Departments will be loaded dynamically via JavaScript -->
                            </select>
                            <span id="deptIdError" class="text-danger small"></span>
                        </div>

                        <div class="mb-3">
                            <label for="roleId" class="form-label">Role</label>
                            <select class="form-select" id="roleId" name="role_id" required>
                                <option value="">Select Role</option>
                                <?php foreach ($role_options as $role): // Loop through fetched roles ?>
                                    <option value="<?php echo htmlspecialchars($role['role_id']); ?>"
                                        <?php echo (($user['role_id'] ?? '') == $role['role_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($role['role_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span id="roleIdError" class="text-danger small"></span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">
                            <?php echo $isEditing ? 'Update User' : 'Add User'; ?>
                        </button>
                        <a href="<?php echo base_url . 'admin_dashboard.php?section=users'; ?>"
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

<script src="../../api/js/add_user_validation.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const collegeSelect = document.getElementById('collegeId');
        const deptSelect = document.getElementById('deptId');
        const departmentFieldContainer = document.getElementById('departmentFieldContainer');
        const initialCollegeId = "<?php echo htmlspecialchars($user['college_id'] ?? ''); ?>";
        const initialDeptId = "<?php echo htmlspecialchars($user['dept_id'] ?? ''); ?>";

        // Profile Photo Preview Logic
        const profilePhotoInput = document.getElementById('profilePhoto');
        const profilePhotoPreview = document.getElementById('profilePhotoPreview');
        const defaultProfilePhoto = "<?php echo DEFAULT_PROFILE_PHOTO; ?>";

        if (profilePhotoInput && profilePhotoPreview) {
            profilePhotoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePhotoPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // If no file is selected, revert to the current user photo or default
                    profilePhotoPreview.src = "<?php echo htmlspecialchars($user['photourl'] ?? DEFAULT_PROFILE_PHOTO); ?>";
                }
            });
        }


        /**
         * Clears and populates the department dropdown.
         * @param {Array} departments - An array of department objects {department_id, department_name}.
         * @param {string} selectedDeptId - The ID of the department to be pre-selected.
         */
        function populateDepartments(departments, selectedDeptId = '') {
            deptSelect.innerHTML = '<option value="">Select Department</option>'; // Clear existing options
            departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept.department_id;
                option.textContent = dept.department_name;
                if (selectedDeptId && dept.department_id == selectedDeptId) {
                    option.selected = true;
                }
                deptSelect.appendChild(option);
            });
        }

        /**
         * Fetches departments for a given college ID via AJAX.
         * @param {string} collegeId - The ID of the selected college.
         * @param {string} initialDeptIdForSelection - The department ID to pre-select after loading.
         */
        async function loadDepartments(collegeId, initialDeptIdForSelection = '') {
            if (!collegeId) {
                populateDepartments([]); // Clear departments if no college is selected
                departmentFieldContainer.style.display = 'none'; // Hide department field
                return;
            }

            // Show department field before fetching
            departmentFieldContainer.style.display = 'block';

            try {
                const fetchUrl = `<?php echo base_url; ?>api/departments/get_departments_by_college.php?college_id=${collegeId}`;
                const response = await fetch(fetchUrl);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const departments = await response.json();
                populateDepartments(departments, initialDeptIdForSelection);
            } catch (error) {
                console.error('Error fetching departments:', error);
                populateDepartments([]); // Clear departments on error
                departmentFieldContainer.style.display = 'none'; // Hide on error
            }
        }

        // Event listener for college selection change
        collegeSelect.addEventListener('change', function() {
            const selectedCollegeId = this.value;
            loadDepartments(selectedCollegeId); // Do not pass initialDeptIdForSelection on change
        });

        // Initial load for departments if a college is already selected (e.g., in edit mode)
        // Also control visibility on initial load
        if (initialCollegeId) {
            loadDepartments(initialCollegeId, initialDeptId);
        } else {
            departmentFieldContainer.style.display = 'none'; // Ensure hidden if no initial college
        }

        // --- Form Submission with AJAX and Alert Container ---
        const userForm = document.getElementById('userForm');
        const alertContainer = document.getElementById('alertContainer');

        function displayAlert(type, message) {
            // Clear existing alerts
            alertContainer.innerHTML = '';

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show text-center`;
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alertDiv);
        }

        userForm.addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevent default form submission

            // Clear previous alerts
            alertContainer.innerHTML = '';

            // Basic client-side validation (from add_user_validation.js)
            // Assuming add_user_validation.js is correctly handling individual field errors.
            // This part focuses on the AJAX submission.

            // Collect form data, including files
            const formData = new FormData(userForm);
            const actionUrl = userForm.getAttribute('action');

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData // FormData automatically sets 'Content-Type': 'multipart/form-data'
                });

                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    if (data.status === 'success') {
                        displayAlert('success', data.message);
                        // Clear form fields only for Add User mode
                        if (!userForm.querySelector('input[name="user_id"]')) {
                            userForm.reset(); // Resets all form fields
                            // Also clear validation classes
                            userForm.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                                el.classList.remove('is-valid', 'is-invalid');
                            });
                            // Reset photo preview to default
                            profilePhotoPreview.src = defaultProfilePhoto;
                            // Hide department field if college is reset
                            departmentFieldContainer.style.display = 'none';
                        }
                    } else {
                        displayAlert('danger', data.message);
                    }
                } else {
                    const text = await response.text();
                    console.error("Non-JSON response from server:", text);
                    displayAlert('danger', 'Server error: Unexpected response. Please check console.');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                displayAlert('danger', `An error occurred: ${error.message}`);
            }
        });
    });
</script>
