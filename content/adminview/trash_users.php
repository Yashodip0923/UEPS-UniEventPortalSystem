<?php
// content/adminview/trash_users.php
// This file displays a list of soft-deleted (trashed) users and provides options to restore or permanently delete them.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/header.php'; // This includes your header and likely sidebar logic

// Define a default placeholder image URL (must match the one in add_user.php)
define('DEFAULT_PROFILE_PHOTO', base_url . 'assets/images/default_profile.png'); // Adjust path as needed

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in trash_users.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

if (!isset($loggedInUserId) || ($_SESSION['role_id'] ?? null) != 5) {
    die("Unauthorized access. Admin privileges required.");
}

// Pagination setup
$itemsPerPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $itemsPerPage;

// Fetch trashed users (status = 0)
$trashedUsers = [];
$stmt = $conn->prepare("SELECT u.user_id, u.first_name, u.middle_name, u.last_name, u.email, u.photourl,
                               u.updated_at, c.college_name, d.department_name, r.role_name
                        FROM users u
                        LEFT JOIN colleges c ON u.college_id = c.college_id
                        LEFT JOIN departments d ON u.dept_id = d.department_id
                        LEFT JOIN roles r ON u.role_id = r.role_id
                        WHERE u.status = 0
                        ORDER BY u.updated_at DESC LIMIT ?, ?");

if ($stmt) {
    $stmt->bind_param("ii", $offset, $itemsPerPage);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $trashedUsers[] = $row;
    }
    $stmt->close();
} else {
    error_log("Failed to prepare statement for fetching trashed users: " . $conn->error);
    $error_message = 'Failed to load trashed user data.';
}

// Get total count of trashed users for pagination
$totalResult = $conn->query("SELECT COUNT(*) as total FROM users WHERE status = 0");
$totalUsers = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $itemsPerPage);

$showingFrom = ($totalUsers > 0) ? (($page - 1) * $itemsPerPage) + 1 : 0;
$showingTo = min($page * $itemsPerPage, $totalUsers);

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
    /* General table styling for better responsiveness and control */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 1rem;
    }

    .table-responsive table {
        table-layout: auto;
        width: 100%;
        min-width: 900px; /* Adjusted min-width for more columns */
    }

    .table-responsive table th,
    .table-responsive table td {
        padding: 0.75rem;
        vertical-align: middle;
        white-space: normal;
        word-break: break-word;
    }

    /* ID Column: Keep it concise and prevent wrapping */
    .table-responsive table th:nth-child(1),
    .table-responsive table td:nth-child(1) {
        width: 5%;
        white-space: nowrap;
    }

    /* Photo Column */
    .table-responsive table th:nth-child(2),
    .table-responsive table td:nth-child(2) {
        width: 5%;
        white-space: nowrap;
    }

    /* Name, Email, Contact, College, Department, Role Columns */
    .table-responsive table th:nth-child(3), .table-responsive table td:nth-child(3), /* Name */
    .table-responsive table th:nth-child(4), .table-responsive table td:nth-child(4), /* Email */
    .table-responsive table th:nth-child(5), .table-responsive table td:nth-child(5), /* Contact */
    .table-responsive table th:nth-child(6), .table-responsive table td:nth-child(6), /* College */
    .table-responsive table th:nth-child(7), .table-responsive table td:nth-child(7), /* Department */
    .table-responsive table th:nth-child(8), .table-responsive table td:nth-child(8)  /* Role */
    {
        width: auto; /* Allow flexibility */
    }

    /* Last Updated Column */
    .table-responsive table th:nth-child(9),
    .table-responsive table td:nth-child(9) {
        width: 12%; /* Adjusted width for timestamp */
        white-space: nowrap;
    }

    /* Actions Column: Crucial for Restore/Delete buttons */
    .table-responsive table th:last-child,
    .table-responsive table td:last-child {
        width: 1%; /* Small percentage, min-width will control actual size */
        min-width: 180px; /* Wide enough for "Restore" + "Delete Permanently" buttons + spacing */
        white-space: nowrap;
        padding-right: 0.5rem;
    }

    /* Style for the flex container holding the action buttons */
    .table-responsive table td:last-child .d-flex {
        flex-wrap: nowrap;
        justify-content: flex-start;
        gap: 0.5rem;
    }

    /* Specific styling for profile photo thumbnail */
    .profile-thumbnail {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #ddd;
    }

    /* Optional: If pagination info and controls wrap strangely on small screens */
    .pagination-info-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-top: 1rem;
    }

    .trash-controls-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 10px;
    }

    @media (min-width: 768px) {
        .pagination-info-container {
            flex-direction: row;
            justify-content: space-between;
        }
    }
     body {
        user-select: none;
        
    }
</style>

<main class="container-fluid pt-2 flex-grow-1">
    <div class="admin-section" id="trash-users">
        <h2 class="h3 fw-bold">Trashed Users</h2>

        <div class="trash-controls-row">
            <form method="GET" class="d-flex align-items-center mb-0">
                <input type="hidden" name="section" value="trash_users">
                <label for="per_page" class="form-label mb-0">Items per page:</label>
                <select name="per_page" id="per_page" class="form-select form-select-sm d-inline w-auto ms-2"
                    onchange="this.form.submit()">
                    <option value="5" <?php echo ($itemsPerPage == 5) ? 'selected' : ''; ?>>5</option>
                    <option value="10" <?php echo ($itemsPerPage == 10) ? 'selected' : ''; ?>>10</option>
                    <option value="20" <?php echo ($itemsPerPage == 20) ? 'selected' : ''; ?>>20</option>
                    <option value="50" <?php echo ($itemsPerPage == 50) ? 'selected' : ''; ?>>50</option>
                </select>
            </form>

            <div>
                <a href="<?php echo base_url . 'admin_dashboard.php?section=users'; ?>"
                    class="btn btn-outline-primary fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Active Users
                </a>
            </div>
        </div>

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

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">College</th>
                        <th scope="col">Department</th>
                        <th scope="col">Role</th>
                        <th scope="col">Deleted At</th>
                        <th scope="col" class="actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($trashedUsers)): ?>
                        <?php foreach ($trashedUsers as $user): ?>
                            <tr class="align-middle">
                                <th scope="row"><?php echo htmlspecialchars($user['user_id']); ?></th>
                                <td>
                                    <img src="<?php echo htmlspecialchars($user['photourl'] ? base_url . $user['photourl'] : DEFAULT_PROFILE_PHOTO); ?>"
                                         alt="Profile" class="profile-thumbnail">
                                </td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . ($user['middle_name'] ? $user['middle_name'] . ' ' : '') . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['college_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['department_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($user['role_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($user['updated_at']))); ?></td>
                                <td class="actions-column">
                                    <div class="d-flex justify-content-start flex-nowrap">
                                        <button class="btn btn-success text-white restore-user-btn me-2"
                                            data-id="<?php echo htmlspecialchars($user['user_id']); ?>">Restore</button>
                                        <button class="btn btn-danger permanent-delete-user-btn"
                                            data-id="<?php echo htmlspecialchars($user['user_id']); ?>">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">No trashed users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            <div class="pagination-info-container">
                <p class="text-muted mb-0 fw-bold">Showing <?php echo $showingFrom; ?> to
                    <?php echo $showingTo; ?> of <?php echo $totalUsers; ?> Trashed Users
                </p>
                <ul class="pagination justify-content-center">
                    <?php
                    $numLinks = 3; // Number of page links to show directly
                    $startPage = max(1, $page - $numLinks);
                    $endPage = min($totalPages, $page + $numLinks);

                    // First page link
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="&section=trash_users&page=1&per_page=' . $itemsPerPage . '">First</a></li>';
                    }

                    // Previous page link
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="&section=trash_users&page=' . ($page - 1) . '&per_page=' . $itemsPerPage . '">Previous</a></li>';
                    }

                    // Ellipsis at the start
                    if ($startPage > 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link"
                                href="?section=trash_users&page=<?php echo $i; ?>&per_page=<?php echo $itemsPerPage; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor;

                    // Ellipsis at the end
                    if ($endPage < $totalPages) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }

                    // Next page link
                    if ($page < $totalPages) {
                        echo '<li class="page-item"><a class="page-link" href="&section=trash_users&page=' . ($page + 1) . '&per_page=' . $itemsPerPage . '">Next</a></li>';
                    }

                    // Last page link
                    if ($page < $totalPages) {
                        echo '<li class="page-item"><a class="page-link" href="&section=trash_users&page=' . $totalPages . '&per_page=' . $itemsPerPage . '">Last</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<!-- Bootstrap Bundle with Popper (for modals, dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const restoreButtons = document.querySelectorAll('.restore-user-btn');
        const permanentDeleteButtons = document.querySelectorAll('.permanent-delete-user-btn');

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

        restoreButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                if (confirm('Are you sure you want to RESTORE this user?')) {
                    fetch(BASE_URL + 'api/users/restore_user_process.php', {
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
                            // Remove the row from the table on successful restore
                            const row = button.closest('tr');
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
                        console.error('Error restoring user:', error);
                        displayAlert('danger', `An error occurred while trying to restore the user: ${error.message}`);
                    });
                }
            });
        });

        permanentDeleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.id;
                if (confirm('WARNING: Are you absolutely sure you want to PERMANENTLY DELETE this user? This action cannot be undone.')) {
                    fetch(BASE_URL + 'api/users/delete_user_permanent.php', {
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
                            // Remove the row from the table on successful permanent delete
                            const row = button.closest('tr');
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
                        console.error('Error permanently deleting user:', error);
                        displayAlert('danger', `An error occurred while trying to permanently delete the user: ${error.message}`);
                    });
                }
            });
        });
    });
</script>
