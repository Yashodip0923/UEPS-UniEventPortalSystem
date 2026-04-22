<?php
// content/users.php
// This file contains the User Management section content for admin_panel.php.

// Ensure session is started if not already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure these paths are correct based on your file structure
require_once __DIR__ . '/../../api/dbinclude.php'; // Adjust path as necessary
require_once __DIR__ . '/../../config.php'; // Adjust path as necessary

// Define a default placeholder image URL (you can change this)
define('DEFAULT_PROFILE_PHOTO', base_url . 'assets/images/default_profile.png'); // Adjust path as needed

// Check if $conn is valid after including dbinclude.php
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in users.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

// Check for user authentication and admin role (role_id 5 is admin)
if (!isset($loggedInUserId) || ($_SESSION['role_id'] ?? null) != 5) {
    die("Unauthorized access. Admin privileges required.");
}

// Pagination variables
$itemsPerPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $itemsPerPage;

// Fetch total count of users for pagination
// Assuming 'status = 1' for active users
$totalResult = $conn->query("SELECT COUNT(*) as total FROM users WHERE status = 1");
$totalUsers = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $itemsPerPage);

// Prepared statement for fetching users with pagination
// Join with colleges and departments tables to get their names, and roles table for role name
$stmt = $conn->prepare("
    SELECT
        u.user_id, u.first_name, u.middle_name, u.last_name, u.email, u.contact_number, u.photourl, u.created_at,
        c.college_name,
        d.department_name,
        r.role_name
    FROM
        users u
    LEFT JOIN
        colleges c ON u.college_id = c.college_id
    LEFT JOIN
        departments d ON u.dept_id = d.department_id
    LEFT JOIN
        roles r ON u.role_id = r.role_id
    WHERE
        u.status = 1
    ORDER BY
        u.user_id ASC
    LIMIT ?, ?
");
$stmt->bind_param("ii", $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close(); // Close the statement after use

$showingFrom = ($totalUsers > 0) ? (($page - 1) * $itemsPerPage) + 1 : 0;
$showingTo = min($page * $itemsPerPage, $totalUsers);

// Handle messages from redirection (if any, though AJAX will typically handle this now)
$success_message = '';
$error_message = '';
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
/* Base styles */
body {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
}

/* Admin section styling */
.admin-section {
    padding: 20px;
}

.admin-section h2 {
    margin-bottom: 20px;
}

.table-responsive {
    margin-top: 20px;
    overflow-x: auto; /* Enables horizontal scrolling if content overflows */
    -webkit-overflow-scrolling: touch; /* Improves scrolling on iOS */
    margin-bottom: 1rem;
}

.table-responsive table {
    table-layout: auto; /* Changed to auto for better content fit */
    width: 100%;
    min-width: 1000px; /* Adjusted min-width to accommodate new photo column and other data */
}

.table-responsive table th,
.table-responsive table td {
    padding: 0.75rem;
    vertical-align: middle;
    white-space: normal;
    word-break: break-word;
}

/* Column widths - ADAPTED FOR USERS WITH PHOTO */
.table-responsive table th:nth-child(1),
.table-responsive table td:nth-child(1) {
    /* ID */
    width: 5%;
    white-space: nowrap;
}

.table-responsive table th:nth-child(2),
.table-responsive table td:nth-child(2) {
    /* Photo */
    width: 5%;
    white-space: nowrap;
}

.table-responsive table th:nth-child(3),
.table-responsive table td:nth-child(3) {
    /* Name */
    width: 15%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-responsive table th:nth-child(4),
.table-responsive table td:nth-child(4) {
    /* Email */
    width: 18%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-responsive table th:nth-child(5),
.table-responsive table td:nth-child(5) {
    /* Role */
    width: 10%;
    white-space: nowrap;
}

.table-responsive table th:nth-child(6),
.table-responsive table td:nth-child(6) {
    /* College */
    width: 15%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-responsive table th:nth-child(7),
.table-responsive table td:nth-child(7) {
    /* Department */
    width: 15%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-responsive table th:nth-child(8),
.table-responsive table td:nth-child(8) {
    /* Created At */
    width: 12%;
    white-space: nowrap;
}

.table-responsive table th:nth-child(9),
.table-responsive table td:nth-child(9) {
    /* Actions */
    width: 1%; /* Small percentage, min-width will dictate */
    min-width: 120px; /* Enough space for Edit + Delete */
    white-space: nowrap;
    padding-right: 0.5rem;
}

/* Style for the actions column to ensure buttons don't push content out */
.table-responsive table td.actions-column .d-flex {
    flex-wrap: nowrap;
    justify-content: center;
    gap: 0.5rem;
}

/* Custom style to align items in the action/per-page row */
.action-and-per-page-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 10px;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

/* Adjust pagination alignment for better responsiveness and centering */
.pagination-info-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

@media (min-width: 768px) {
    .pagination-info-container {
        flex-direction: row;
        justify-content: space-between;
    }
}

/* Style for profile photo thumbnail */
.profile-thumbnail {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #ddd;
}
</style>

<div class="admin-section" id="users">
    <h2 class="h3 fw-bold">Users Management</h2>

    <div class="action-and-per-page-row">
        <form method="GET" class="d-flex align-items-center mb-0">
            <input type="hidden" name="section" value="users">
            <label for="per_page" class="form-label mb-0">Items per page:</label>
            <select name="per_page" id="per_page" class="form-select form-select-sm d-inline w-auto ms-2"
                onchange="this.form.submit()">
                <option value="5" <?php echo ($itemsPerPage == 5) ? 'selected' : ''; ?>>5</option>
                <option value="10" <?php echo ($itemsPerPage == 10) ? 'selected' : ''; ?>>10</option>
                <option value="20" <?php echo ($itemsPerPage == 20) ? 'selected' : ''; ?>>20</option>
                <option value="50" <?php echo ($itemsPerPage == 50) ? 'selected' : ''; ?>>50</option>
            </select>
        </form>

        <div class="action-buttons">
            <a href="<?php echo base_url . 'admin_dashboard.php?section=add_user'; ?>" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Add New User
            </a>
            <a href="<?php echo base_url . 'admin_dashboard.php?section=trash_users'; ?>"
                class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash-fill me-1"></i> Trash
            </a>
        </div>
    </div>

    <div id="alertContainer">
        <?php // Messages from PHP redirects will appear here if any, but now AJAX handles them ?>
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

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">College</th>
                    <th scope="col">Department</th>
                    <th scope="col">Created At</th>
                    <th scope="col" class="actions-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr id="user-row-<?php echo htmlspecialchars($user['user_id']); ?>" class="align-middle">
                            <th scope="row"><?php echo htmlspecialchars($user['user_id']); ?></th>
                            <td>
                                <img src="<?php echo htmlspecialchars($user['photourl'] ? base_url . $user['photourl'] : DEFAULT_PROFILE_PHOTO); ?>"
                                     alt="Profile" class="profile-thumbnail">
                            </td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . ($user['middle_name'] ? $user['middle_name'] . ' ' : '') . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge bg-<?php
                                if ($user['role_name'] == 'Admin')
                                    echo 'dark';
                                else if ($user['role_name'] == 'Coordinator')
                                    echo 'primary';
                                else if ($user['role_name'] == 'Student')
                                    echo 'secondary';
                                else
                                    echo 'info';
                                ?>">
                                    <?php echo htmlspecialchars($user['role_name']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($user['college_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($user['department_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($user['created_at']))); ?></td>
                            <td class="actions-column">
                                <div class="d-flex">
                                    <a href="<?php echo base_url . 'admin_dashboard.php?section=add_user&user_id=' . htmlspecialchars($user['user_id']); ?>"
                                        class="btn btn-sm btn-info text-white me-1">Edit</a>
                                    <!-- Changed to a button with data-id for AJAX handling -->
                                    <button type="button" class="btn btn-sm btn-danger delete-user-btn"
                                        data-id="<?php echo htmlspecialchars($user['user_id']); ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation">
        <div class="pagination-info-container">
            <p class="text-muted mb-0 fw-bold">Showing <?php echo $showingFrom; ?> to
                <?php echo $showingTo; ?> of
                <?php echo $totalUsers; ?> Users
            </p>
            <ul class="pagination justify-content-center">
                <?php
                $numLinks = 4; // Number of page links to show directly
                $startPageDisplay = max(1, $page - floor($numLinks / 2));
                $endPageDisplay = min($totalPages, $page + floor($numLinks / 2));

                // Show "First" page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?section=users&page=1&per_page=' . $itemsPerPage . '">First</a></li>';
                }

                // Show "Previous" page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?section=users&page=' . ($page - 1) . '&per_page=' . $itemsPerPage . '">Previous</a></li>';
                }

                // Show ellipsis if needed at the beginning
                if ($startPageDisplay > 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                for ($i = $startPageDisplay; $i <= $endPageDisplay; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="?section=users&page=<?php echo $i; ?>&per_page=<?php echo $itemsPerPage; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor;

                // Show ellipsis if needed at the end
                if ($endPageDisplay < $totalPages) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                // Show "Next" page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?section=users&page=' . ($page + 1) . '&per_page=' . $itemsPerPage . '">Next</a></li>';
                }

                // Show "Last" page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?section=users&page=' . $totalPages . '&per_page=' . $itemsPerPage . '">Last</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
</div>

<?php
// Assuming footer.php includes necessary Bootstrap JS bundle
require_once __DIR__ . "/../../includes/footer.php";
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-user-btn');
        const BASE_URL = "<?php echo base_url; ?>";

        // Function to display Bootstrap alerts dynamically
        function displayAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            if (!alertContainer) {
                console.error('Alert container not found!');
                return;
            }
            alertContainer.innerHTML = ''; // Clear existing alerts

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show text-center`;
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alertDiv);
        }

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                if (confirm('Are you sure you want to soft delete this user? It can be restored from Trash.')) {
                    fetch(BASE_URL + 'api/users/delete_user_process.php', { // Corrected path to api/admin
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'user_id=' + userId
                    })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            return response.text().then(text => {
                                throw new Error('Server did not return JSON. Response: ' + text);
                            });
                        }
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            displayAlert('success', data.message);
                            // Remove the row from the table on successful soft delete
                            const row = document.getElementById(`user-row-${userId}`);
                            if (row) {
                                row.remove();
                            }
                            // Optional: Reload page after a short delay to update pagination info
                            // setTimeout(() => window.location.reload(), 1500);
                        } else {
                            displayAlert(data.status === 'info' ? 'info' : 'danger', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error soft deleting user:', error);
                        displayAlert('danger', `An error occurred while trying to soft delete the user: ${error.message}`);
                    });
                }
            });
        });
    });
</script>
