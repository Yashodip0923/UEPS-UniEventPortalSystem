<?php
require_once __DIR__ . '/api/dbinclude.php';
require_once __DIR__ . '/includes/header_main.php';

$success_message = '';
$error_message = '';

$first_name = '';
$middle_name = '';
$last_name = '';
$email = '';
$dob = '';
$contact_number = '';
$college_id_selected = '';
$department_id_selected = '';

$colleges = [];
try {
    $stmt = $conn->prepare("SELECT college_id, college_name FROM colleges ORDER BY college_name ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $colleges = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    error_log("Error loading colleges: " . $e->getMessage());
}

$all_departments = [];
try {
    $stmt = $conn->prepare("SELECT department_id, department_name, college_id FROM departments ORDER BY college_id, department_name ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $all_departments = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    error_log("Error loading departments: " . $e->getMessage());
}

$conn->close();
?>

<style>
    body {
        font-family: 'Inter', sans-serif;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .navbar-brand .logo-img {
        max-height: 40px;
        width: auto;
    }

    .btn-primary {
        background-color: #6610f2;
        border-color: #6610f2;
    }

    .btn-primary:hover {
        background-color: #550bb7;
        border-color: #550bb7;
    }

    .btn-outline-primary {
        color: #6610f2;
        border-color: #6610f2;
    }

    .btn-outline-primary:hover {
        background-color: #6610f2;
        color: white;
    }

    .form-control {
        border: 2px solid #ced4da;
        box-shadow: none !important;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: none !important;
    }

    .form-control.is-invalid {
        border-color: #dc3545 !important;
        padding-right: calc(0.1rem) !important;
        background-image: none !important;
    }

    .form-control.is-valid {
        border-color: #198754 !important;
        padding-right: calc(.1rem) !important;
        background-image: none !important;
    }

    .input-icon-container {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        pointer-events: none;
        width: 1.25rem;
        height: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-icon-container svg {
        width: 100%;
        height: 100%;
        display: none;
    }

    .input-icon-container svg.icon-show {
        display: block;
    }

    .icon-success {
        fill: #198754;
    }

    .icon-error {
        fill: #dc3545;
    }

    .custom-select-wrapper {
        position: relative;
    }

    .custom-select-display {
        cursor: pointer;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 2px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        min-height: calc(1.5em + 0.75rem + 4px);
        display: flex;
        align-items: center;
    }

    .custom-select-display.is-invalid {
        border-color: #dc3545 !important;
    }

    .custom-select-display.is-valid {
        border-color: #198754 !important;
    }


    .custom-select-options-container {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        border: 2px solid #ced4da;
        border-radius: 0.25rem;
        background-color: #fff;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
        padding: 0.5rem;
        max-height: 200px;
        overflow-y: auto;
        display: none;
    }

    .custom-option {
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        border-radius: 0.2rem;
    }

    .custom-option:hover {
        background-color: #e9ecef;
    }

    .custom-option.selected {
        background-color: #0d6efd;
        color: white;
    }

    p a {
        text-decoration: none;
    }
</style>

<main class="container pt-5 pb-3">

    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-lg rounded-3">
                <div class="card-body">
                    <h2 class="card-title text-center mb-3 fw-bold">Register</h2>

                    <div id="alertContainer">
                        <!-- Alerts will be dynamically inserted here by JavaScript -->
                    </div>

                    <form id="registerForm" action="api/users/register_user.php" method="POST" novalidate>
                        <input type="hidden" name="user_type" value="student">

                        <div class="mb-2 position-relative">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required>
                            <div class="input-icon-container">
                                <svg id="firstNameSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="firstNameErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="firstNameError" class="text-danger small"></span>
                        </div>
                        <div class="mb-2 position-relative">
                            <label for="middleName" class="form-label">Middle Name (Optional)</label>
                            <input type="text" class="form-control" id="middleName" name="middle_name">
                            <div class="input-icon-container">
                                <svg id="middleNameSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="middleNameErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="middleNameError" class="text-danger small"></span>
                        </div>
                        <div class="mb-2 position-relative">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required>
                            <div class="input-icon-container">
                                <svg id="lastNameSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="lastNameErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="lastNameError" class="text-danger small"></span>
                        </div>
                        <div class="mb-2 position-relative">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="input-icon-container">
                                <svg id="emailSuccessIcon" class="icon-success" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="emailErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="emailError" class="text-danger small"></span>
                        </div>
                        <div class="mb-2 position-relative">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-icon-container">
                                <svg id="passwordSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="passwordErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="passwordError" class="text-danger small"></span>
                        </div>
                        <div class="mb-2 position-relative">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password"
                                required>
                            <div class="input-icon-container">
                                <svg id="confirmPasswordSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="confirmPasswordErrorIcon" class="icon-error" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="confirmPasswordError" class="text-danger small"></span>
                        </div>
                        <div class="mb-2 position-relative">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob">
                            <div class="input-icon-container">
                                <svg id="dobSuccessIcon" class="icon-success" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="dobErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="dobError" class="text-danger small"></span>
                        </div>
                        <div class="mb-2 position-relative">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contactNumber" name="contact_number">
                            <div class="input-icon-container">
                                <svg id="contactNumberSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="contactNumberErrorIcon" class="icon-error" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="contactNumberError" class="text-danger small"></span>
                        </div>

                        <div id="studentFields">
                            <div class="mb-2 position-relative">
                                <label for="studentCollege" class="form-label">Your College</label>
                                <div class="custom-select-wrapper">
                                    <div class="custom-select-display form-control" id="customCollegeDisplay"
                                        tabindex="0">
                                        Select your college
                                    </div>
                                    <input type="hidden" name="college_id" id="hiddenCollegeId">
                                    <div class="custom-select-options-container">
                                        <input type="text" class="form-control mb-2" id="collegeSearch"
                                            placeholder="Type to search colleges...">
                                        <div id="collegeOptionsList">
                                        </div>
                                    </div>
                                </div>
                                <div class="input-icon-container">
                                    <svg id="collegeSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                        fill="currentColor" style="display: none;">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                    </svg>
                                    <svg id="collegeErrorIcon" class="icon-error" viewBox="0 0 24 24"
                                        fill="currentColor" style="display: none;">
                                        <path
                                            d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                    </svg>
                                </div>
                                <span id="collegeError" class="text-danger small"></span>
                            </div>
                            <div class="mb-3 position-relative" id="departmentFieldWrapper" style="display: none;">
                                <label for="studentDepartment" class="form-label">Your Department</label>
                                <div class="custom-select-wrapper">
                                    <div class="custom-select-display form-control" id="customDepartmentDisplay"
                                        tabindex="0">
                                        Select your college first
                                    </div>
                                    <input type="hidden" name="department_id" id="hiddenDepartmentId">
                                    <div class="custom-select-options-container">
                                        <input type="text" class="form-control mb-2" id="departmentSearch"
                                            placeholder="Type to search departments...">
                                        <div id="departmentOptionsList">
                                        </div>
                                    </div>
                                </div>
                                <div class="input-icon-container">
                                    <svg id="departmentSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                        fill="currentColor" style="display: none;">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                    </svg>
                                    <svg id="departmentErrorIcon" class="icon-error" viewBox="0 0 24 24"
                                        fill="currentColor" style="display: none;">
                                        <path
                                            d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                    </svg>
                                </div>
                                <span id="departmentError" class="text-danger small"></span>
                            </div>
                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">Register</button>
                    </form>
                    <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . "/includes/footer_main.php";
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script>
    const allColleges = <?php echo json_encode($colleges); ?>;
    const allDepartments = <?php echo json_encode($all_departments); ?>;
</script>
<script src="api/js/register_validation.js"></script>