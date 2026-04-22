<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure this path is correct based on your file structure
// Use require_once to prevent multiple inclusions if this file is accidentally called more than once
require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';

// Check if $conn is valid after including dbinclude.php
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in colleges.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

$itemsPerPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $itemsPerPage;

// Prepared statement for security for fetching colleges
$stmt = $conn->prepare("SELECT * FROM colleges WHERE status = 1 ORDER BY college_id ASC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();
$colleges = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close(); // Close the statement after use

// Get total count of non-status co1leges for pagination
$totalResult = $conn->query("SELECT COUNT(*) as total FROM colleges WHERE status = 1");
$totalColleges = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalColleges / $itemsPerPage);

$showingFrom = ($totalColleges > 0) ? (($page - 1) * $itemsPerPage) + 1 : 0;
$showingTo = min($page * $itemsPerPage, $totalColleges);
?>

<style>
    /* General table styling for better responsiveness and control */
    .table-responsive table {
        table-layout: fixed;
        /* Force fixed table layout */
        width: 100%;
        /* Ensure table takes full available width */
    }

    /* Column widths for fixed table layout */
    .table-responsive table th:nth-child(1),
    .table-responsive table td:nth-child(1) {
        /* ID */
        width: 5%;
    }

    .table-responsive table th:nth-child(2),
    .table-responsive table td:nth-child(2) {
        /* College Name */
        width: 30%;
        overflow: hidden;
        text-overflow: ellipsis;
        /* Add ellipsis for overflowing text */
    }

    .table-responsive table th:nth-child(3),
    .table-responsive table td:nth-child(3) {
        /* Address */
        width: 40%;
        overflow: hidden;
        text-overflow: ellipsis;
        /* Add ellipsis for overflowing text */
    }

    .table-responsive table th:nth-child(4),
    .table-responsive table td:nth-child(4) {
        /* Created At */
        width: 15%;
        white-space: nowrap;
        /* Keep date on one line */
    }

    .table-responsive table th:nth-child(5),
    .table-responsive table td:nth-child(5) {
        /* Actions */
        width: 10%;
        /* Set a percentage, min-width will ensure buttons fit */
        min-width: 120px;
        /* Keep a minimum width for the buttons */
        white-space: nowrap;
        /* Prevent buttons from wrapping */
    }

    /* Style for the actions column to ensure buttons don't push content out */
    .table-responsive table td.actions-column .d-flex {
        flex-wrap: nowrap;
        /* Keep buttons in a single row */
        justify-content: flex-start;
        /* Align buttons to the start */
    }

    /* Custom style to align items in the action/per-page row */
    .action-and-per-page-row {
        display: flex;
        justify-content: space-between;
        /* Distribute items between ends */
        align-items: center;
        /* Vertically align items */
        margin-bottom: 1rem;
        /* Add some space below the row */
        flex-wrap: wrap;
        /* Allow items to wrap on smaller screens if needed */
        gap: 10px;
        /* Space between wrapped items */
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        /* Space between buttons */
    }

    /* Adjust pagination alignment for better responsiveness and centering */
    .pagination-info-container {
        display: flex;
        flex-direction: column;
        /* Stack on small screens */
        align-items: center;
        /* Center horizontally when stacked */
        gap: 10px;
        /* Space between info and pagination */
    }

    @media (min-width: 768px) {

        /* Adjust for medium and larger screens */
        .pagination-info-container {
            flex-direction: row;
            /* Row on larger screens */
            justify-content: space-between;
            /* Space out info and pagination */
        }
    }

    /* Disable text selection globally */
    body {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
    }
    /* Add custom styles for the departments section */
    .admin-section {
        padding: 20px;
    }

    .admin-section h2 {
        margin-bottom: 20px;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .btn {
        margin-right: 5px;
    }
</style>

<div class="admin-section" id="colleges">
    <h2 class="h3 fw-bold">Colleges Management</h2>

    <div class="action-and-per-page-row">
        <form method="GET" class="d-flex align-items-center mb-0">
            <input type="hidden" name="section" value="colleges">
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
            <a href="<?php echo base_url . 'admin_dashboard.php?section=add_college'; ?>"
                class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Add New College
            </a>
            <a href="<?php echo base_url . 'admin_dashboard.php?section=trash_colleges'; ?>"
                class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash-fill me-1"></i> Trash
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">College Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Created At</th>
                    <th scope="col" class="actions-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($colleges)): ?>
                    <?php foreach ($colleges as $college): ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($college['college_id']); ?></th>
                            <td><?php echo htmlspecialchars($college['college_name']); ?></td>
                            <td><?php echo htmlspecialchars($college['address']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($college['created_at']))); ?>
                            </td>
                            <td class="actions-column">
                                <div class="d-flex">
                                    <a href="<?php echo base_url . 'admin_dashboard.php?section=add_college&college_id=';?><?php echo htmlspecialchars($college['college_id']); ?>"
                                        class="btn btn-sm btn-info text-white me-2">Edit</a>
                                    <button class="btn btn-sm btn-danger delete-college-btn"
                                        data-id="<?php echo htmlspecialchars($college['college_id']); ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No colleges found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation">
        <div class="pagination-info-container">
            <p class="text-muted mb-0 fw-bold">Showing <?php echo $showingFrom; ?> to
                <?php echo $showingTo; ?> of
                <?php echo $totalColleges; ?> Colleges
            </p>
            <ul class="pagination justify-content-center">
                <?php
                $numLinks = 4; // Number of page links to show directly
                $startPageDisplay = max(1, $page - floor($numLinks / 2));
                $endPageDisplay = min($totalPages, $page + floor($numLinks / 2));

                // Show "First" page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?section=colleges&page=1&per_page=' . $itemsPerPage . '">First</a></li>';
                }

                // Show "Previous" page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?section=colleges&page=' . ($page - 1) . '&per_page=' . $itemsPerPage . '">Previous</a></li>';
                }

                // Show ellipsis if needed at the beginning
                if ($startPageDisplay > 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                for ($i = $startPageDisplay; $i <= $endPageDisplay; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="?section=colleges&page=<?php echo $i; ?>&per_page=<?php echo $itemsPerPage; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor;

                // Show ellipsis if needed at the end
                if ($endPageDisplay < $totalPages) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                // Show "Next" page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?section=colleges&page=' . ($page + 1) . '&per_page=' . $itemsPerPage . '">Next</a></li>';
                }

                // Show "Last" page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?section=colleges&page=' . $totalPages . '&per_page=' . $itemsPerPage . '">Last</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-college-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const collegeId = this.dataset.id;
                if (confirm('Are you sure you want to soft delete this college? It can be restored from Trash.')) {
                    fetch('<?php echo base_url; ?>api/colleges/delete_college_process.php', { // <--- CORRECTED LINE
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'college_id=' + collegeId
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
                                alert(data.message);
                                window.location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while trying to delete the college. Check console for details.');
                        });
                }
            });
        });
    });
</script>