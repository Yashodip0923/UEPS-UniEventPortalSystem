<?php
// includes/header.php
// This file contains the HTML <head> section, top navigation bar, and the sidebar.
// It also includes the database connection and fetches initial data needed across sections.

// Ensure session is started for user authentication and data
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
// Make sure 'api/dbinclude.php' exists and has your database connection logic
require_once __DIR__ . '/../api/dbinclude.php'; // Corrected path

// --- Authentication and Authorization Check (Admin Panel specific) ---
// In a real application, you'd check if the user is logged in and has 'admin' user_type.
// For demonstration, we'll assume an admin user ID for data fetching.
// IMPORTANT: In a production environment, $loggedInUserId should come from a secure session
// after a user has successfully logged in.
$loggedInUserId = $_SESSION['user_id'] ?? null; // Get user ID from session
$loggedInUserType = $_SESSION['role_id'] ?? null; // Get user type from session (e.g., 'admin')

// Redirect if not logged in or not an admin
if (!isset($loggedInUserId) || $loggedInUserType != 5) {
    header('Location: login.php');
    exit();
}
// --- End Authentication Check ---

// Initialize arrays for data that might be needed across multiple sections or for dashboard charts
$colleges = [];
$departments = [];
$users = [];
$events = [];
$error_message_header = ''; // Renamed to avoid conflict with profile.php's $error_message
$adminDetails = []; // Initialize adminDetails array

try {
    // Fetch Colleges (needed for dashboard chart and College Management)
    $result = $conn->query("SELECT * FROM colleges");
    if ($result) {
        $colleges = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }

    // Fetch Departments (needed for dashboard chart and Department Management)
    $result = $conn->query("
       SELECT d.*, c.college_name
       FROM departments d
       JOIN colleges c ON d.college_id = c.college_id;
    ");
    if ($result) {
        $departments = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }

    // Fetch Users (needed for dashboard chart and User Management)
    $result = $conn->query("
        SELECT u.*, c.college_name, d.department_name
        FROM users u
        LEFT JOIN colleges c ON u.college_id = c.college_id
        LEFT JOIN departments d ON u.dept_id = d.department_id
    ");
    if ($result) {
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }

    // Fetch events (needed for dashboard chart and Events Management)
    $result = $conn->query("
        SELECT * FROM events ORDER BY event_date DESC;
    ");
    if ($result) {
        $events = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }

    // Fetch Admin Details specifically for the logged-in user
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND role_id = 5");
    if ($stmt === false) {
        throw new Exception("Failed to prepare statement for fetching admin details: " . $conn->error);
    }
    $stmt->bind_param('i', $loggedInUserId); // 'i' for integer type
    $stmt->execute();
    $result = $stmt->get_result();
    $adminDetails = $result->fetch_assoc(); // Use fetch_assoc() for single row
    $stmt->close();

    // If adminDetails are not found (e.g., dummy ID doesn't exist or user is not admin),
    // set default values to prevent errors in display.
    if (!$adminDetails) {
        // If the user ID exists but isn't type 5 (admin), they shouldn't even be on this page due to the initial redirect.
        // This case should ideally not be hit if the redirect works, but as a safeguard.
        header('Location: login.php?error=access_denied');
        exit();
    }

} catch (Exception $e) { // Catch generic Exception for MySQLi errors
    $error_message_header = "Error loading initial data: " . $e->getMessage();
    error_log("Admin Panel header data fetch error: " . $e->getMessage());
    $adminDetails = [
        'first_name' => 'Unknown',
        'middle_name' => '',
        'last_name' => 'Admin',
        'email' => 'error@example.com',
        'dob' => '1970-01-01',
        'contact_number' => 'N/A',
        'college_id' => null,
        'dept_id' => null,
        'photourl' => 'https://placehold.co/50x50/cccccc/333333?text=Error'
    ];
} finally {
    // Ensure the connection is closed after data fetching if it was successfully opened.
    // However, if dbinclude.php manages a persistent connection or if other parts of the script
    // expect $conn to remain open, you might adjust this. For typical web requests, closing is good.
    if (isset($conn) && $conn && !$conn->connect_error) {
        // $conn->close(); // Commented out as profile.php also uses $conn
    }
}


// Determine which section to show based on URL parameter
// Default to 'dashboard' if no parameter or invalid parameter is given
$currentSection = isset($_GET['section']) ? $_GET['section'] : 'dashboard';
$managementMenuActive = ($currentSection == 'profile' || $currentSection == 'settings') ? 'show' : '';
// List of valid sections (important for security to avoid loading arbitrary files)
$validSections = ['dashboard', 'colleges', 'profile', 'users', 'events', 'departments', 'settings', 'trash_colleges', 'trash_departments', 'trash_users', 'trash_events', 'add_college', 'add_department', 'add_event', 'add_user'];

// Ensure the requested section is valid
if (!in_array($currentSection, $validSections)) {
    $currentSection = 'dashboard'; // Fallback to dashboard
}

// Get admin's name for display, default to 'Admin' if not found
$adminName = trim(
    ($adminDetails['first_name'])
);

// Get admin's profile picture, or use a placeholder
$adminProfilePic = $adminDetails['photourl'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UniEventPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        
        :root {
            --navbar-height: 70px;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: var(--navbar-height);
        }

        #wrapper {
            display: flex;
            flex-grow: 1;
        }

        .navbar {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1030;
            background-color: #fff !important;
            border-bottom: 1px solid rgba(0, 0, 0, .075);
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
            height: var(--navbar-height);
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
        }

        #sidebar-wrapper {
            width: 15rem;
            background-color: #343a40;
            color: white;
            box-shadow: 0.125rem 0 0.25rem rgba(0, 0, 0, .075);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            z-index: 1020;
            display: flex;
            flex-direction: column;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
            transition: all 0.4s ease;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 1.5rem 1.25rem;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
        }

        .avatar-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border: 2px solid #fff;
            margin-right: 10px;
        }

        #sidebar-wrapper .list-group {
            width: 100%;
        }

        #sidebar-wrapper .list-group-item {
            border: none;
            padding: 0.8rem 1.25rem;
            color: rgba(255, 255, 255, 0.7);
            background-color: transparent;
            border-radius: 0;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        #sidebar-wrapper .list-group-item:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        #sidebar-wrapper .list-group-item.active {
            color: #fff;
            background-color: #6610f2;
            font-weight: bold;
        }

        #sidebar-wrapper .list-group-item.dropdown-toggle::after {
            float: right;
            margin-top: .5em;
        }

        #sidebar-wrapper .list-group-item .collapse .list-group-item {
            background-color: rgba(0, 0, 0, 0.2);
            color: rgba(255, 255, 255, 0.6);
        }

        #personalSubmenu .list-group-item,
        #managementSubmenu .list-group-item {
            padding-left: 3rem;
        }

        #sidebar-wrapper .list-group-item .collapse .list-group-item:hover {
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
        }

        #page-content-wrapper {
            flex-grow: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
            transition: all 0.4s ease;
        }

        /* Desktop View */
        @media (min-width: 768px) {
            #sidebar-wrapper {
                position: fixed;
                height: 100vh;
                top: var(--navbar-height);
                left: 0;
                z-index: 1020;
            }

            #page-content-wrapper {
                margin-left: 15rem;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -15rem;
            }

            #wrapper.toggled #page-content-wrapper {
                margin-left: 0;
            }
        }

        /* Mobile View */
        @media (max-width: 767.98px) {
            #sidebar-wrapper {
                position: fixed;
                height: 100vh;
                top: 0;
                left: 0;
                transform: translateX(-15rem);
                transition: transform .25s ease-out;
                box-shadow: 0.125rem 0 0.5rem rgba(0, 0, 0, .2);
            }

            #wrapper.toggled #sidebar-wrapper {
                transform: translateX(0);
            }

            #page-content-wrapper {
                transform: translateX(0);
                transition: transform .25s ease-out;
                position: relative;
                z-index: 1;
            }

            #wrapper.toggled #page-content-wrapper {
                transform: translateX(15rem);
            }
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: auto;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            position: relative;
            z-index: 1025;
        }

        .admin-section,
        .card,
        .table-responsive {
            border-radius: 0.75rem;
        }

        .table thead {
            background-color: #6610f2;
            color: white;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
        }

        .table thead th:first-child {
            border-top-left-radius: 0.75rem;
        }

        .table thead th:last-child {
            border-top-right-radius: 0.75rem;
        }

        canvas {
            max-height: 300px;
            width: 100% !important;
            height: auto !important;
        }

        /* Conditional styling for dashboard-disabled */
        /* This class will now be conditionally applied via PHP */
        /* .dashboard-disabled {
            pointer-events: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            opacity: 0.8;
            cursor: not-allowed;
        } */

        .hello-text {
            color: rgba(184, 184, 184, 0.75);
            font-weight: 500;
        }

        #sidebarToggle {
            border: none;
            background: transparent;
            font-size: 1.5rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="btn btn-outline-primary me-2" id="sidebarToggle" aria-label="Toggle Sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand d-flex align-items-center" href="admin_dashboard.php">
                <svg width="40" height="40" viewBox="0 0 220 200" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="me-2">
                    <title>UniEventPortal Logo Option 3 - AYLY Burst</title>
                    <defs>
                        <linearGradient id="grad3_AYLY_navbar" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#6f42c1;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#0d6efd;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="100" cy="100" r="60" fill="url(#grad3_AYLY_navbar)" />
                    <path d="M100 40 L110 70 L140 70 L115 90 L125 120 L100 110 L75 120 L85 90 L60 70 L90 70 Z"
                        fill="white" opacity="0.2" />
                    <text x="100" y="110" font-family="Inter, sans-serif" font-size="38" font-weight="bold" fill="white"
                        text-anchor="middle">AYLY</text>
                    <path d="M100 50 L100 30" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M150 100 L170 100" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M100 150 L100 170" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M50 100 L30 100" stroke="white" stroke-width="4" stroke-linecap="round" />
                </svg>
                <span class="fs-4 fw-bold text-primary"><?php
                switch ($loggedInUserType) {
                    case 1:
                        echo "Student";
                        break;
                    case 2:
                        echo "Coordinator";
                        break;
                    case 3:
                        echo "Event Leader";
                        break;
                    case 4:
                        echo "Organizer";
                        break;
                    case 5:
                        echo "Admin";
                        break;
                    default:
                        echo "User";
                        break; // Fallback for undefined roles
                }
                ?> Dashboard</span>
            </a>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <img src="<?php echo htmlspecialchars($adminProfilePic); ?>" alt="User Avatar"
                    class="rounded-circle avatar-img">
                <span class="hello-text">Hello, </span><?php echo htmlspecialchars($adminName); ?>
            </div>

            <div class="list-group list-group-flush">
                <a href="admin_dashboard.php?section=dashboard"
                    class="list-group-item list-group-item-action bg-dark text-white <?php echo ($currentSection == 'dashboard') ? 'active' : ''; ?>">
                    <i class="bi bi-bar-chart-line-fill"></i> Dashboard
                </a>

                <a href="#personalSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?php echo (in_array($currentSection, ['profile', 'settings'])) ? 'true' : 'false'; ?>"
                    class="list-group-item list-group-item-action bg-dark text-white dropdown-toggle <?php echo (in_array($currentSection, ['profile', 'settings'])) ? 'active' : ''; ?>">
                    <i class="bi bi-person-fill"></i> Personal
                </a>
                <div class="collapse <?php echo (in_array($currentSection, ['profile', 'settings'])) ? 'show' : ''; ?>"
                    id="personalSubmenu">
                    <a href="admin_dashboard.php?section=profile"
                        class="list-group-item list-group-item-action bg-dark text-white <?php echo ($currentSection == 'profile') ? 'active' : ''; ?>">
                        <i class="bi bi-person-lines-fill"></i> Profile
                    </a>
                    <a href="admin_dashboard.php?section=settings"
                        class="list-group-item list-group-item-action bg-dark text-white <?php echo ($currentSection == 'settings') ? 'active' : ''; ?>">
                        <i class="bi bi-gear-fill"></i> Settings
                    </a>
                </div>

                <a href="#managementSubmenu" data-bs-toggle="collapse"
                    aria-expanded="<?php echo (in_array($currentSection, ['colleges', 'departments', 'events', 'users'])) ? 'true' : 'false'; ?>"
                    class="list-group-item list-group-item-action bg-dark text-white dropdown-toggle <?php echo (in_array($currentSection, ['colleges', 'departments', 'events', 'users'])) ? 'active' : ''; ?>">
                    <i class="bi bi-tools"></i> Management
                </a>
                <div class="collapse <?php echo (in_array($currentSection, ['colleges', 'departments', 'events', 'users'])) ? 'show' : ''; ?>"
                    id="managementSubmenu">
                    <a href="admin_dashboard.php?section=colleges"
                        class="list-group-item list-group-item-action bg-dark text-white <?php echo ($currentSection == 'colleges') ? 'active' : ''; ?>">
                        <i class="bi bi-building-fill"></i> College
                    </a>
                    <a href="admin_dashboard.php?section=departments"
                        class="list-group-item list-group-item-action bg-dark text-white <?php echo ($currentSection == 'departments') ? 'active' : ''; ?>">
                        <i class="bi bi-diagram-3-fill"></i> Department
                    </a>
                    <a href="admin_dashboard.php?section=events"
                        class="list-group-item list-group-item-action bg-dark text-white <?php echo ($currentSection == 'events') ? 'active' : ''; ?>">
                        <i class="bi bi-calendar2-event-fill"></i> Event
                    </a>
                    <a href="admin_dashboard.php?section=users"
                        class="list-group-item list-group-item-action bg-dark text-white <?php echo ($currentSection == 'users') ? 'active' : ''; ?>">
                        <i class="bi bi-person-fill"></i> User
                    </a>
                </div>
            </div>
            <div class="p-3">
                <a href="logout.php" class="btn btn-danger w-100 rounded-lg">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>

        </div>
        <div id="page-content-wrapper"
            class="<?php echo ($currentSection == 'dashboard') ? 'dashboard-disabled' : ''; ?>">