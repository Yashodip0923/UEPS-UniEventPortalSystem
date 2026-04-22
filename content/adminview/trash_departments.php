<?php
// content/adminview/trash_departments.php
// This file displays soft-deleted departments and allows restoration or permanent deletion.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/header.php';

if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in trash_departments.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

if (!isset($loggedInUserId)) {
    die("User not authenticated. Please log in.");
}

$itemsPerPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $itemsPerPage;

// Fetch trashed departments (status = 0)
$stmt = $conn->prepare("SELECT d.*, c.college_name FROM departments d JOIN colleges c ON d.college_id = c.college_id WHERE d.status = 0 ORDER BY d.department_id ASC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();
$departments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get total count of trashed departments for pagination
$totalResult = $conn->query("SELECT COUNT(*) as total FROM departments WHERE status = 0");
$totalDepartments = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalDepartments / $itemsPerPage);

$showingFrom = ($totalDepartments > 0) ? (($page - 1) * $itemsPerPage) + 1 : 0;
$showingTo = min($page * $itemsPerPage, $totalDepartments);

$success_message = '';
$error_message = '';
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = htmlspecialchars($_GET['message']);
    if ($status === 'success') {
        $success_message = $message;
    } else {
        $error_message = $message;
    }
}
?>

<style>
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; margin-bottom: 1rem; }
    .table-responsive table { table-layout: auto; width: 100%; min-width: 800px; }
    .table-responsive table th, .table-responsive table td { padding: 0.75rem; vertical-align: middle; white-space: normal; word-break: break-word; }
    .table-responsive table th:nth-child(1), .table-responsive table td:nth-child(1) { width: 5%; white-space: nowrap; }
    .table-responsive table th:nth-child(2), .table-responsive table td:nth-child(2) { width: auto; } /* Department Name */
    .table-responsive table th:nth-child(3), .table-responsive table td:nth-child(3) { width: 25%; } /* College Name */
    .table-responsive table th:nth-child(4), .table-responsive table td:nth-child(4) { width: 15%; white-space: nowrap; } /* Deleted At */
    .table-responsive table th:last-child, .table-responsive table td:last-child { width: 1%; min-width: 160px; white-space: nowrap; padding-right: 0.5rem; }
    .table-responsive table td:last-child .d-flex { flex-wrap: nowrap; justify-content: flex-start; gap: 0.5rem; }
    .pagination-info-container { display: flex; flex-direction: column; align-items: center; gap: 10px; margin-top: 1rem; }
    .trash-controls-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 10px; }
    @media (min-width: 768px) { .pagination-info-container { flex-direction: row; justify-content: space-between; } }
     body {
        user-select: none;
        
    }
</style>

<main class="container-fluid pt-2 flex-grow-1">
    <div class="admin-section" id="trash-departments">
        <h2 class="h3 fw-bold">Trashed Departments</h2>

        <div class="trash-controls-row">
            <form method="GET" class="d-flex align-items-center mb-0">
                <input type="hidden" name="section" value="trash_departments">
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
                <a href="<?php echo base_url . 'admin_dashboard.php?section=departments'; ?>"
                    class="btn btn-outline-primary fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Active Departments
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
                        <th scope="col">Department Name</th>
                        <th scope="col">College Name</th>
                        <th scope="col">Deleted At</th>
                        <th scope="col" class="actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($departments)): ?>
                        <?php foreach ($departments as $department): ?>
                            <tr class="align-middle">
                                <th scope="row"><?php echo htmlspecialchars($department['department_id']); ?></th>
                                <td><?php echo htmlspecialchars($department['department_name']); ?></td>
                                <td><?php echo htmlspecialchars($department['college_name']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($department['updated_at']))); ?></td>
                                <td class="actions-column">
                                    <div class="d-flex justify-content-start flex-nowrap">
                                        <button class="btn btn-success text-white restore-department-btn me-2"
                                            data-id="<?php echo htmlspecialchars($department['department_id']); ?>">Restore</button>
                                        <button class="btn btn-danger permanent-delete-department-btn"
                                            data-id="<?php echo htmlspecialchars($department['department_id']); ?>">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No trashed departments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            <div class="pagination-info-container">
                <p class="text-muted mb-0 fw-bold">Showing <?php echo $showingFrom; ?> to
                    <?php echo $showingTo; ?> of <?php echo $totalDepartments; ?> Trashed Departments
                </p>
                <ul class="pagination justify-content-center">
                    <?php
                    $numLinks = 3;
                    $startPage = max(1, $page - $numLinks);
                    $endPage = min($totalPages, $page + $numLinks);

                    if ($page > 1) { echo '<li class="page-item"><a class="page-link" href="?section=trash_departments&page=1&per_page=' . $itemsPerPage . '">First</a></li>'; }
                    if ($page > 1) { echo '<li class="page-item"><a class="page-link" href="?section=trash_departments&page=' . ($page - 1) . '&per_page=' . $itemsPerPage . '">Previous</a></li>'; }
                    if ($startPage > 1) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?section=trash_departments&page=<?php echo $i; ?>&per_page=<?php echo $itemsPerPage; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor;

                    if ($endPage < $totalPages) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
                    if ($page < $totalPages) { echo '<li class="page-item"><a class="page-link" href="?section=trash_departments&page=' . ($page + 1) . '&per_page=' . $itemsPerPage . '">Next</a></li>'; }
                    if ($page < $totalPages) { echo '<li class="page-item"><a class="page-link" href="?section=trash_departments&page=' . $totalPages . '&per_page=' . $itemsPerPage . '">Last</a></li>'; }
                    ?>
                </ul>
            </div>
        </nav>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const BASE_URL = "<?php echo base_url; ?>";
        const alertContainer = document.getElementById('alertContainer');

        function showAlert(status, message) {
            alertContainer.innerHTML = ''; // Clear previous alerts
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${status} alert-dismissible fade show text-center`;
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alertDiv);
            if (status === 'success') {
                setTimeout(() => window.location.reload(), 1500); // Reload on success
            }
        }

        // --- Restore Department ---
        const restoreButtons = document.querySelectorAll('.restore-department-btn');
        restoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const departmentId = this.dataset.id;
                if (confirm('Are you sure you want to restore this department?')) {
                    fetch(BASE_URL + 'api/departments/restore_department_process.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'department_id=' + departmentId
                    })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            return response.text().then(text => {
                                console.error("Non-JSON response from server:", text);
                                throw new Error('Server did not return JSON. Response: ' + text.substring(0, 200) + '...');
                            });
                        }
                    })
                    .then(data => {
                        showAlert(data.status, data.message);
                    })
                    .catch(error => {
                        console.error('Error restoring department:', error);
                        showAlert('danger', `An error occurred while restoring the department: ${error.message}`);
                    });
                }
            });
        });

        // --- Permanent Delete Department ---
        const permanentDeleteButtons = document.querySelectorAll('.permanent-delete-department-btn');
        permanentDeleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const departmentId = this.dataset.id;
                if (confirm('WARNING: Are you absolutely sure you want to PERMANENTLY DELETE this department? This action cannot be undone.')) {
                    fetch(BASE_URL + 'api/departments/permanent_delete_department_process.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'department_id=' + departmentId
                    })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            return response.text().then(text => {
                                console.error("Non-JSON response from server:", text);
                                throw new Error('Server did not return JSON. Response: ' + text.substring(0, 200) + '...');
                            });
                        }
                    })
                    .then(data => {
                        showAlert(data.status, data.message);
                    })
                    .catch(error => {
                        console.error('Error permanently deleting department:', error);
                        showAlert('danger', `An error occurred while permanently deleting the department: ${error.message}`);
                    });
                }
            });
        });
    });
</script>