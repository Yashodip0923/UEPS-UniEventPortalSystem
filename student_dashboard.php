<?php
// student_dashboard.php
// UniEventPortal Student Dashboard - Displays registered events, competition applications, and profile.
// NOTE: This is a basic display panel. For a real application, robust authentication
// and authorization (e.g., checking if $_SESSION['user_id'] is set and user_type is 'student') are CRITICAL.

// Database connection include
require_once 'includes/db_connection.php'; // Apni database connection file include karein

// --- Simulate a logged-in student user ID for testing ---
// In a real application, this would come from a session variable after successful login:
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
//     header('Location: login.php'); // Redirect to login if not authenticated or not a student
//     exit();
// }
// $loggedInUserId = $_SESSION['user_id'];
$loggedInUserId = 1; // FOR TESTING: Use a dummy user ID. Replace with actual session user ID in production.
// --- End Simulation ---

// Initialize arrays for data
$userDetails = [];
$registeredEvents = [];
$competitionApplications = [];
$error_message = '';

try {
    // 1. Fetch User Details
    $stmt = $pdo->prepare("SELECT u.*, c.college_name, d.department_name FROM users u LEFT JOIN colleges c ON u.college_id = c.college_id LEFT JOIN departments d ON u.department_id = d.department_id WHERE u.user_id = ?");
    $stmt->execute([$loggedInUserId]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userDetails) {
        $error_message = "User not found or invalid user ID.";
        // Log out or redirect if user doesn't exist
        // header('Location: logout.php'); exit();
    }

    // 2. Fetch Registered Events for this user
    $stmt = $pdo->prepare("
        SELECT 
            er.*, 
            e.event_name, 
            e.description, 
            e.event_date, 
            e.event_time, 
            e.location,
            e.is_departmental,
            e.is_competition_event,
            d.department_name as event_department_name,
            coll.college_name as event_college_name
        FROM event_registrations er
        JOIN events e ON er.event_id = e.event_id
        LEFT JOIN departments d ON e.department_id = d.department_id
        LEFT JOIN users coord ON e.coordinator_user_id = coord.user_id
        LEFT JOIN colleges coll ON coord.college_id = coll.college_id -- Join through coordinator's college for event college
        WHERE er.user_id = ?
        ORDER BY e.event_date ASC
    ");
    $stmt->execute([$loggedInUserId]);
    $registeredEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Fetch Competition Applications for this user
    $stmt = $pdo->prepare("
        SELECT 
            ca.*, 
            comp.competition_name, 
            comp.description as competition_description, 
            e.event_name 
        FROM competition_applications ca
        JOIN competitions comp ON ca.competition_id = comp.competition_id
        JOIN events e ON comp.event_id = e.event_id
        WHERE ca.user_id = ?
        ORDER BY ca.application_date DESC
    ");
    $stmt->execute([$loggedInUserId]);
    $competitionApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = "Error loading data: " . $e->getMessage();
    // For production, log this error: error_log("Student Dashboard data fetch error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - UniEventPortal</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <!-- Google Fonts - Inter for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navbar-height: 70px;
            /* Define a CSS variable for navbar height */
        }

        html,
        body {
            height: 100%;
            /* Ensures HTML and body take full viewport height */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            /* Light grey background */
            color: #343a40;
            /* Dark grey text */
            overflow-x: hidden;
            /* Prevent horizontal scroll when sidebar is toggled on mobile */
            min-height: 100vh;
            /* Make body at least viewport height */
            display: flex;
            /* Enable flexbox */
            flex-direction: column;
            /* Stack children vertically */
            padding-top: var(--navbar-height);
            /* Add padding for fixed navbar */
        }

        #wrapper {
            display: flex;
            flex-grow: 1;
            /* Allow wrapper to take remaining vertical space */
        }

        /* Fixed Navbar - ensuring it's always on top for toggle button visibility */
        .navbar {
            position: fixed;
            /* Keep it fixed */
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1030;
            /* Ensure it's the highest z-index */
            background-color: #fff !important;
            /* Force white background */
            border-bottom: 1px solid rgba(0, 0, 0, .075);
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
            height: var(--navbar-height);
            border-bottom-left-radius: 0;
            /* No rounding */
            border-bottom-right-radius: 0;
            /* No rounding */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
            /* Consistent shadow */
        }

        #sidebar-wrapper {
            width: 15rem;
            /* Sidebar width */
            background-color: #343a40;
            /* Dark background for sidebar */
            color: white;
            box-shadow: 0.125rem 0 0.25rem rgba(0, 0, 0, .075);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            z-index: 1020;
            /* Below fixed navbar, above main content on mobile */
            display: flex;
            /* Flex container */
            flex-direction: column;
            /* Stack children vertically */
            border-bottom-left-radius: 0;
            /* Ensure no rounding at bottom of sidebar for desktop */
            border-bottom-right-radius: 0;
            /* Ensure no rounding at bottom of sidebar for desktop */
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

        #sidebar-wrapper .list-group {
            width: 100%;
            flex-grow: 1;
            /* Allow list group to take available space to push logout to bottom */
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
            /* Active item color */
            font-weight: bold;
        }

        #sidebar-wrapper .list-group-item.dropdown-toggle::after {
            float: right;
            margin-top: .5em;
        }

        #sidebar-wrapper .list-group-item .collapse .list-group-item {
            padding-left: 2.5rem;
            /* Indent for sub-items */
            background-color: rgba(0, 0, 0, 0.2);
            color: rgba(255, 255, 255, 0.6);
        }

        #sidebar-wrapper .list-group-item .collapse .list-group-item:hover {
            background-color: rgba(0, 0, 0, 0.3);
            color: #fff;
        }

        #page-content-wrapper {
            flex-grow: 1;
            /* Takes up remaining horizontal space */
            width: 100%;
            /* Ensures it doesn't overflow initially on small screens */
            display: flex;
            /* Make it a flex container for its own content */
            flex-direction: column;
            /* Stack its children vertically */
        }

        /* Desktop View (Sidebar always visible, no special toggle effect needed) */
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
                /* Default visible */
                position: sticky;
                /* Sticky below navbar */
                top: var(--navbar-height);
                min-height: calc(100vh - var(--navbar-height));
                /* Fills remaining height */
            }

            #wrapper.toggled #sidebar-wrapper {
                /* If toggle is also for desktop, this hides it */
                margin-left: -15rem;
            }

            #page-content-wrapper {
                transition: margin-left .25s ease-out;
                /* Smooth transition for content when sidebar toggles */
            }

            #wrapper.toggled #page-content-wrapper {
                margin-left: 0;
                /* No margin when sidebar is hidden */
            }
        }

        /* Mobile View (Sidebar fixed off-screen, pushes content when open) */
        @media (max-width: 767.98px) {
            #sidebar-wrapper {
                position: fixed;
                /* Fixed relative to viewport */
                height: 100vh;
                /* Full viewport height */
                top: 0;
                left: 0;
                transform: translateX(-15rem);
                /* Start off-screen to the left */
                transition: transform .25s ease-out;
                box-shadow: 0.125rem 0 0.5rem rgba(0, 0, 0, .2);
                /* Stronger shadow for sliding panel */
            }

            #wrapper.toggled #sidebar-wrapper {
                transform: translateX(0);
                /* Slide in */
            }

            #page-content-wrapper {
                transform: translateX(0);
                /* Default position */
                transition: transform .25s ease-out;
                /* Smooth transition for content shift */
                position: relative;
                /* Needed for z-index to work */
                z-index: 1;
                /* Lower than sidebar if it overlays, but here it's pushing */
            }

            #wrapper.toggled #page-content-wrapper {
                transform: translateX(15rem);
                /* Shift main content to the right when sidebar is open */
            }
        }

        /* Footer Styling */
        .footer {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: auto;
            /* Push footer to the bottom */
            border-top-left-radius: 1rem;
            /* Curvy edges for footer */
            border-top-right-radius: 1rem;
            /* Curvy edges for footer */
        }

        /* Ensure consistent curvy edges for content sections */
        .student-section,
        .card,
        .table-responsive {
            /* Renamed admin-section to student-section */
            border-radius: 0.75rem;
        }

        .table thead {
            background-color: #6610f2;
            color: white;
            /* Ensure header has rounded corners matching table-responsive container */
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .table {
            border-collapse: separate;
            /* Required for border-radius on table */
            border-spacing: 0;
            /* Remove default spacing between cells */
            overflow: hidden;
            /* Ensures child elements respect border-radius */
        }

        /* Specific rounding for first and last header cells */
        .table thead th:first-child {
            border-top-left-radius: 0.75rem;
        }

        .table thead th:last-child {
            border-top-right-radius: 0.75rem;
        }

        /* Crucial fix: Ensure hidden sections are truly hidden */
        .student-section.hidden {
            /* Renamed admin-section to student-section */
            display: none !important;
        }

        /* Chart Canvas specific styling to control height */
        canvas {
            max-height: 300px;
            /* Limit the maximum height of all charts */
            width: 100% !important;
            /* Ensure charts take full width of their container */
            height: auto !important;
            /* Allow height to adjust proportionally */
        }
    </style>
</head>

<body>

    <!-- Top Fixed Navigation Bar (Contains the Toggle Button) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="btn btn-primary me-2" id="sidebarToggle">Toggle Menu</button>
            <a class="navbar-brand d-flex align-items-center" href="admin_panel.php">
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
                <span class="fs-4 fw-bold text-primary">Student</span>
            </a>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <svg width="40" height="40" viewBox="0 0 220 200" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="me-2">
                    <title>UniEventPortal Logo Option 3 - AYLY Burst</title>
                    <defs>
                        <linearGradient id="grad3_AYLY_sidebar" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#6f42c1;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#0d6efd;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="100" cy="100" r="60" fill="url(#grad3_AYLY_sidebar)" />
                    <path d="M100 40 L110 70 L140 70 L115 90 L125 120 L100 110 L75 120 L85 90 L60 70 L90 70 Z"
                        fill="white" opacity="0.2" />
                    <text x="100" y="110" font-family="Inter, sans-serif" font-size="38" font-weight="bold" fill="white"
                        text-anchor="middle">AYLY</text>
                    <path d="M100 50 L100 30" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M150 100 L170 100" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M100 150 L100 170" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M50 100 L30 100" stroke="white" stroke-width="4" stroke-linecap="round" />
                </svg>
                Student Panel
            </div>
            <div class="list-group list-group-flush">
                <a href="#dashboard"
                    class="list-group-item list-group-item-action bg-dark text-white active">Dashboard</a>
                <a href="#myEvents" class="list-group-item list-group-item-action bg-dark text-white">My Events</a>
                <a href="#myApplications" class="list-group-item list-group-item-action bg-dark text-white">My
                    Applications</a>
                <a href="#profile" class="list-group-item list-group-item-action bg-dark text-white">Profile</a>
                <a href="#settings" class="list-group-item list-group-item-action bg-dark text-white">Settings</a>
            </div>
            <!-- Logout Button outside list-group but inside sidebar-wrapper -->
            <div class="p-3">
                <a href="login.php" class="btn btn-danger w-100 rounded-lg">Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <main class="container-fluid py-5 flex-grow-1">
                <h1 class="display-4 fw-bold text-center mb-5">Student Dashboard</h1>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <!-- Dashboard Section -->
                <div class="student-section" id="dashboard">
                    <h2 class="h3 fw-bold mb-4">Dashboard Overview</h2>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm h-100 p-3">
                                <h5 class="card-title">Total Registered Events</h5>
                                <p class="display-4 text-primary fw-bold text-center">
                                    <?php echo count($registeredEvents); ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm h-100 p-3">
                                <h5 class="card-title">Total Competition Applications</h5>
                                <p class="display-4 text-info fw-bold text-center">
                                    <?php echo count($competitionApplications); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Events Section -->
                <div class="student-section mb-4 hidden" id="myEvents">
                    <h2 class="h3 fw-bold mb-4">My Registered Events</h2>
                    <?php if (!empty($registeredEvents)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Event Name</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Registered On</th>
                                        <th scope="col">Attended</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($registeredEvents as $event): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                                            <td><?php echo htmlspecialchars(date('F j, Y', strtotime($event['event_date']))); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(date('h:i A', strtotime($event['event_time']))); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($event['registration_date']))); ?>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-<?php echo $event['attended'] ? 'success' : 'warning'; ?>">
                                                    <?php echo $event['attended'] ? 'Yes' : 'No'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info text-white me-1">View Details</button>
                                                <!-- Add unregister button if applicable -->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            You haven't registered for any events yet.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- My Applications Section -->
                <div class="student-section mb-4 hidden" id="myApplications">
                    <h2 class="h3 fw-bold mb-4">My Competition Applications</h2>
                    <?php if (!empty($competitionApplications)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Competition Name</th>
                                        <th scope="col">Event Name</th>
                                        <th scope="col">Application Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($competitionApplications as $app): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($app['competition_name']); ?></td>
                                            <td><?php echo htmlspecialchars($app['event_name']); ?></td>
                                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($app['application_date']))); ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php
                                                if ($app['status'] == 'approved')
                                                    echo 'success';
                                                else if ($app['status'] == 'rejected')
                                                    echo 'danger';
                                                else
                                                    echo 'warning';
                                                ?>">
                                                    <?php echo htmlspecialchars(ucfirst($app['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info text-white me-1">View Details</button>
                                                <!-- Add withdraw/edit application button if applicable -->
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            You haven't applied for any competitions yet.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Profile Section -->
                <div class="student-section mb-4 hidden" id="profile">
                    <h2 class="h3 fw-bold mb-4">My Profile</h2>
                    <?php if (!empty($userDetails)): ?>
                        <div class="card shadow-sm p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Full Name:</strong> <?php echo htmlspecialchars($userDetails['full_name']); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($userDetails['email']); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>User Type:</strong> <span
                                        class="badge bg-primary"><?php echo htmlspecialchars(ucfirst($userDetails['user_type'])); ?></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>College:</strong>
                                    <?php echo htmlspecialchars($userDetails['college_name'] ?? 'N/A'); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Department:</strong>
                                    <?php echo htmlspecialchars($userDetails['department_name'] ?? 'N/A'); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Member Since:</strong>
                                    <?php echo htmlspecialchars(date('F j, Y', strtotime($userDetails['created_at']))); ?>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary">Edit Profile</button>
                                <button class="btn btn-outline-secondary ms-2">Change Password</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning text-center" role="alert">
                            Unable to load profile details.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Settings Section (Placeholder) -->
                <div class="student-section hidden" id="settings">
                    <h2 class="h3 fw-bold mb-4">Settings</h2>
                    <p class="text-muted">Settings and preferences yahan manage hongi.</p>
                    <button class="btn btn-secondary">Save Settings</button>
                </div>

            </main>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Footer (Consistent with other pages) -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-2">&copy; 2025 UniEventPortal. All rights reserved.</p>
            <p class="mb-0">Designed by Abhishek, Yojana, Lajari & Yashodip</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script>
        // Right-click disable script
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Sidebar Toggle Script and Section Management
        document.addEventListener('DOMContentLoaded', function () {
            const wrapper = document.getElementById('wrapper');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarLinks = document.querySelectorAll('#sidebar-wrapper .list-group-item');
            const studentSections = document.querySelectorAll('.student-section'); // All content sections

            // Function to show a specific section and hide others
            function showSection(sectionId) {
                studentSections.forEach(section => {
                    section.classList.add('hidden'); // Hide all sections using !important
                });
                const targetSection = document.querySelector(sectionId);
                if (targetSection) {
                    targetSection.classList.remove('hidden'); // Show the target section
                    // Scroll to the top of the section for better UX if it's not the dashboard
                    if (sectionId !== '#dashboard') {
                        targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            }

            // Function to manage active state of sidebar links
            function setActiveLink(clickedLink) {
                // Remove 'active' class from all sidebar links first
                sidebarLinks.forEach(link => {
                    link.classList.remove('active');
                });

                // Add 'active' class to the clicked link
                if (clickedLink) {
                    clickedLink.classList.add('active');
                }
            }

            // Toggle sidebar visibility
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    wrapper.classList.toggle('toggled');
                });
            }

            // Event listeners for sidebar links
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href && href.startsWith('#')) { // It's an internal anchor
                        e.preventDefault(); // Prevent default anchor behavior for content sections
                        setActiveLink(this);
                        showSection(href);
                    }
                    // Optional: Close sidebar on smaller screens after clicking a content link
                    if (window.innerWidth < 768) { // Only close on smaller screens
                        wrapper.classList.remove('toggled');
                    }
                });
            });

            // Initialize Dashboard content on page load
            showSection('#dashboard'); // Show dashboard on initial load
            setActiveLink(document.querySelector('a[href="#dashboard"]')); // Mark Dashboard as active

            // Charts are only on Dashboard, so no special re-initialization needed like Admin Panel had.
            // If you add charts to other sections, you'll need similar logic there too.
        });
    </script>
</body>

</html>