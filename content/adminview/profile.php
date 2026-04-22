<?php
// profile.php
// Admin Profile Management Page

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../config.php'; // Ensure config is included for constants


if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in profile.php.");
    // Changed to simply exit as the JS will not pick up PHP echoed errors in this specific div
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

// Ensure $loggedInUserId and $adminDetails are set.
// This part is crucial and assumed to be handled by 'includes/header.php' or previous logic.
// If not, you might need to fetch adminDetails here based on $loggedInUserId.
if (!isset($loggedInUserId)) {
    die("User not authenticated. Please log in.");
}

// Placeholder for adminDetails if it's not coming from includes/header.php
// You'll need to fetch this from your database based on $loggedInUserId
if (!isset($adminDetails)) {
    // Example: Fetch admin details from the database
    // This is a placeholder; replace with your actual database fetch logic
    $stmt = $conn->prepare("SELECT first_name, middle_name, last_name, email, dob, contact_number, college_id, dept_id, photourl FROM users WHERE id = ?");
    $stmt->bind_param("i", $loggedInUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    $adminDetails = $result->fetch_assoc();
    $stmt->close();

    if (!$adminDetails) {
        // Handle case where user details are not found
        error_log("Admin details not found for user ID: " . $loggedInUserId);
        echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">User profile not found.</div></div>';
        exit();
    }
}

// Removed PHP logic for success/error messages as it will now be handled by JavaScript for the snackbar.
// The URL parameters will still be there for JS to read.

$current_first_name = $adminDetails['first_name'] ?? '';
$current_middle_name = $adminDetails['middle_name'] ?? '';
$current_last_name = $adminDetails['last_name'] ?? '';
$current_email = $adminDetails['email'] ?? '';
$current_dob = $adminDetails['dob'] ?? '';
$current_contact_number = $adminDetails['contact_number'] ?? '';
// Ensure the photourl is correctly prefixed if stored relatively, or use a default
$current_profile_pic = $adminDetails['photourl'];

// Check if the file exists for the profile picture if it's a relative path
if ($adminDetails['photourl'] && !file_exists($current_profile_pic)) {
    $current_profile_pic = 'https://placehold.co/150x150/cccccc/333333?text=No+Image';
}

?>

<style>
    .profile-avatar {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 4px solid #6610f2;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
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

    input:valid,
    input:invalid {
        background-image: none !important;
        padding-right: 0.75rem !important;
    }

    /* Disable text selection globally */
    body {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
    }

    /* Allow selection on buttons, links, inputs, textareas */
    a,
    button,
    input,
    textarea {
        user-select: auto;
    }

    /* Snackbar styles */
    #snackbar {
        visibility: hidden;
        /* Hidden by default. */
        min-width: 250px;
        /* Set a minimum width */
        margin-left: -125px;
        /* Divide value of min-width by 2 */
        background-color: #333;
        /* Black background color */
        color: #fff;
        /* White text color */
        text-align: center;
        /* Centered text */
        border-radius: 2px;
        /* Rounded borders */
        padding: 16px;
        /* Padding */
        position: fixed;
        /* Sit on top of the screen */
        z-index: 10000;
        /* Add a z-index if needed */
        left: 50%;
        /* Center the snackbar */
        bottom: 30px;
        /* 30px from the bottom */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Show the snackbar when adding the "show" class to DIV */
    #snackbar.show {
        visibility: visible;
        /* Show the snackbar */
        /* Add animation: Take 0.5 seconds to fade in and out the snackbar.
        However, delay the fade out process for 2.5 seconds */
        -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }

    /* Animations to fade the snackbar in and out */
    @-webkit-keyframes fadein {
        from {
            bottom: 0;
            opacity: 0;
        }

        to {
            bottom: 30px;
            opacity: 1;
        }
    }

    @keyframes fadein {
        from {
            bottom: 0;
            opacity: 0;
        }

        to {
            bottom: 30px;
            opacity: 1;
        }
    }

    @-webkit-keyframes fadeout {
        from {
            bottom: 30px;
            opacity: 1;
        }

        to {
            bottom: 0;
            opacity: 0;
        }
    }

    @keyframes fadeout {
        from {
            bottom: 30px;
            opacity: 1;
        }

        to {
            bottom: 0;
            opacity: 0;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="row px-3">
        <div class="col-12">
            <h1 class="mb-4">Admin Profile</h1>

            <div id="alertContainer">
            </div>

            <div class="card shadow-lg rounded-3 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Personal Information</h5>
                    <form id="profileForm" action="<?php echo base_url . '/api/users/update_profile.php' ?>"
                        method="POST" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($loggedInUserId); ?>">
                        <div class="text-center mb-4">
                            <img id="profilePreview" src="<?php echo htmlspecialchars($current_profile_pic); ?>"
                                alt="Profile Avatar" class="rounded-circle profile-avatar mb-3">
                            <input type="file" class="form-control mx-auto" style="max-width: 280px;"
                                id="profilePicture" name="profile_picture" accept="image/*">
                            <small class="form-text text-muted d-block mt-2">Upload a new profile picture (Max
                                2MB)</small>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3 position-relative">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="first_name"
                                    placeholder="e.g., John"
                                    value="<?php echo htmlspecialchars($current_first_name); ?>" required>
                                <span id="firstNameError" class="text-danger small"></span>
                            </div>
                            <div class="col-md-4 mb-3 position-relative">
                                <label for="middleName" class="form-label">Middle Name (Optional)</label>
                                <input type="text" class="form-control" id="middleName" name="middle_name"
                                    placeholder="e.g., D."
                                    value="<?php echo htmlspecialchars($current_middle_name); ?>">
                                <span id="middleNameError" class="text-danger small"></span>
                            </div>
                            <div class="col-md-4 mb-3 position-relative">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="last_name"
                                    placeholder="e.g., Doe" value="<?php echo htmlspecialchars($current_last_name); ?>"
                                    required>
                                <span id="lastNameError" class="text-danger small"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3 position-relative">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="e.g., john.doe@example.com"
                                    value="<?php echo htmlspecialchars($current_email); ?>" required>
                                <span id="emailError" class="text-danger small"></span>
                            </div>
                            <div class="col-md-6 mb-3 position-relative">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob"
                                    value="<?php echo htmlspecialchars($current_dob); ?>" required>
                                <span id="dobError" class="text-danger small"></span>
                            </div>
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="contactNumber" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contactNumber" name="contact_number"
                                placeholder="e.g., +91 9876543210"
                                value="<?php echo htmlspecialchars($current_contact_number); ?>" required>
                            <span id="contactNumberError" class="text-danger small"></span>
                        </div>
                        <input type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3"
                            value="Save Changes">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="snackbar"></div>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
<script>
    // Snackbar Function
    function showSnackbar(message, status) {
        const snackbar = document.getElementById("snackbar");
        snackbar.textContent = message;
        if (status === 'success') {
            snackbar.style.backgroundColor = '#28a745';  // Green for success
        } else {
            snackbar.style.backgroundColor = '#dc3545';  // Red for error
        }
        snackbar.classList.add("show");
        setTimeout(() => {
            snackbar.classList.remove("show");
        }, 3500); // Visible for 3.5 seconds
    }

    // Auto-show Snackbar from URL Params
    document.addEventListener("DOMContentLoaded", () => {
        const params = new URLSearchParams(window.location.search);
        const status = params.get('status');
        const message = params.get('message');
        if (status && message) {
            showSnackbar(decodeURIComponent(message), status);
        }
    });
</script>

<script src="api/js/profile.js"></script>