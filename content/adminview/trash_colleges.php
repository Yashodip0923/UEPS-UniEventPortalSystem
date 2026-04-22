<?php
// content/adminview/trash_colleges.php
// This file displays soft-deleted colleges and allows restoration or permanent deletion.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure these paths are correct based on your file structure
require_once __DIR__ . '/../../api/dbinclude.php';
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/header.php'; // This includes your header and likely sidebar logic

// Check if $conn is valid after including dbinclude.php
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in trash_colleges.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

// Ensure $loggedInUserId is set (it should be set in header.php after successful authentication)
if (!isset($loggedInUserId)) {
    // In a real application, you might redirect to a login page or display an error
    die("User not authenticated. Please log in.");
}

$itemsPerPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $itemsPerPage;

// Prepared statement for security for fetching trashed colleges (status = 0)
$stmt = $conn->prepare("SELECT * FROM colleges WHERE status = 0 ORDER BY college_id ASC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();
$colleges = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close(); // Close the statement after use

// Get total count of trashed colleges for pagination
$totalResult = $conn->query("SELECT COUNT(*) as total FROM colleges WHERE status = 0");
$totalColleges = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalColleges / $itemsPerPage);

$showingFrom = ($totalColleges > 0) ? (($page - 1) * $itemsPerPage) + 1 : 0;
$showingTo = min($page * $itemsPerPage, $totalColleges);

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
    /* General table styling for better responsiveness and control */
    .table-responsive {
        /* Enables horizontal scrolling if the table content overflows its container.
       This is crucial for preventing content from going off-screen. */
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        /* Improves scrolling experience on iOS devices */
        margin-bottom: 1rem;
        /* Add some space below the table */
    }

    .table-responsive table {
        /* Allow table width to adjust automatically based on content and container,
       but ensure it takes full available width. */
        table-layout: auto;
        width: 100%;
        /* Sets a minimum width for the table. If the screen is narrower than this,
       the `overflow-x: auto` on `.table-responsive` will kick in,
       allowing horizontal scrolling instead of pushing content out. */
        min-width: 700px;
        /* Adjust this value (e.g., 600px-800px) based on your content density */
    }

    /* Common styling for all table headers and data cells */
    .table-responsive table th,
    .table-responsive table td {
        padding: 0.75rem;
        /* Standard padding */
        vertical-align: middle;
        /* Vertically align content in the middle of the cell */
        white-space: normal;
        /* Allow text to wrap by default */
        word-break: break-word;
        /* Break long words if necessary to fit within the cell */
    }

    /* ID Column: Keep it concise and prevent wrapping */
    .table-responsive table th:nth-child(1),
    .table-responsive table td:nth-child(1) {
        width: 5%;
        /* Allocate a small percentage of width */
        white-space: nowrap;
        /* Prevent ID numbers from wrapping */
    }

    /* College Name & Address Columns: Allow flexibility and wrapping */
    .table-responsive table th:nth-child(2),
    .table-responsive table td:nth-child(2),
    .table-responsive table th:nth-child(3),
    .table-responsive table td:nth-child(3) {
        /* These columns will share remaining space and allow text to wrap naturally.
       Avoid fixed widths here to enable better responsiveness. */
        width: auto;
    }

    /* Deleted At / Created At Column (if applicable): Often fixed date/time, so nowrap is fine */
    /* This would be for the 4th column in trash_colleges.php, and not in colleges.php.
   Adjust nth-child based on your specific table structure. */
    .table-responsive table th:nth-child(4),
    .table-responsive table td:nth-child(4) {
        width: 15%;
        /* Or adjust to fit date/time comfortably */
        white-space: nowrap;
        /* Keep date/time on a single line */
    }


    /* Actions Column: Crucial for Edit/Delete buttons */
    /* This is typically the last column, so adjust nth-child accordingly for colleges.php and trash_colleges.php */
    .table-responsive table th:last-child,
    .table-responsive table td:last-child {
        /* Using :last-child is more robust than nth-child for the final column */
        /* Make this column's width primarily content-driven */
        width: 1%;
        /* A very small percentage, letting min-width control the actual size */
        /* **Most Important for buttons:** Ensure enough minimum space for the buttons */
        min-width: 160px;
        /* Needs to be wide enough for "Edit" + "Delete" buttons + spacing */
        white-space: nowrap;
        /* Prevent the buttons themselves from wrapping to new lines */
        padding-right: 0.5rem;
        /* Adjust padding if buttons look too cramped */
    }

    /* Style for the flex container holding the action buttons */
    .table-responsive table td:last-child .d-flex {
        flex-wrap: nowrap;
        /* Ensure buttons stay in a single row within the cell */
        justify-content: flex-start;
        /* Align buttons to the left */
        gap: 0.5rem;
        /* Space between the "Edit" and "Delete" buttons */
    }

    /* Optional: If pagination info and controls wrap strangely on small screens */
    .pagination-info-container {
        display: flex;
        flex-direction: column;
        /* Stack items vertically on smaller screens */
        align-items: center;
        /* Center items when stacked */
        gap: 10px;
        /* Space between info and pagination links */
        margin-top: 1rem;
    }

    .trash-controls-row {
        display: flex;
        justify-content: space-between;
        /* This is the key change: pushes items to far ends */
        align-items: center;
        /* Vertically align items in the middle */
        margin-bottom: 1rem;
        /* Space below this row */
        flex-wrap: wrap;
        /* Allow wrapping on smaller screens if content becomes too wide */
        gap: 10px;
        /* Space between wrapped items */
    }

    @media (min-width: 768px) {
        .pagination-info-container {
            flex-direction: row;
            /* Layout items in a row on medium screens and larger */
            justify-content: space-between;
            /* Space out items horizontally */
        }
    }
     body {
        user-select: none;
        
    }
</style>

<main class="container-fluid pt-2 flex-grow-1">
    <div class="admin-section" id="trash-colleges">
        <h2 class="h3 fw-bold">Trashed Colleges</h2>

        <div class="trash-controls-row">
            <form method="GET" class="d-flex align-items-center mb-0">
                <input type="hidden" name="section" value="trash_colleges">
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
                <a href="<?php echo base_url . 'admin_dashboard.php?section=colleges'; ?>"
                    class="btn btn-outline-primary fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Active Colleges
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
                        <th scope="col">College Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Deleted At</th>
                        <th scope="col" class="actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($colleges)): ?>
                        <?php foreach ($colleges as $college): ?>
                            <tr class="align-middle">
                                <th scope="row"><?php echo htmlspecialchars($college['college_id']); ?></th>
                                <td><?php echo htmlspecialchars($college['college_name']); ?></td>
                                <td><?php echo htmlspecialchars($college['address']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($college['updated_at']))); ?></td>
                                <td class="actions-column">
                                    <div class="d-flex justify-content-start flex-nowrap">
                                        <button class="btn btn-success text-white restore-college-btn me-2"
                                            data-id="<?php echo htmlspecialchars($college['college_id']); ?>">Restore</button>
                                        <button class="btn btn-danger permanent-delete-college-btn"
                                            data-id="<?php echo htmlspecialchars($college['college_id']); ?>">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No trashed colleges found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            <div class="pagination-info-container">
                <p class="text-muted mb-0 fw-bold">Showing <?php echo $showingFrom; ?> to
                    <?php echo $showingTo; ?> of <?php echo $totalColleges; ?> Trashed Colleges
                </p>
                <ul class="pagination justify-content-center">
                    <?php
                    $numLinks = 3; // Number of page links to show directly
                    $startPage = max(1, $page - $numLinks);
                    $endPage = min($totalPages, $page + $numLinks);

                    // First page link
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?section=trash_colleges&page=1&per_page=' . $itemsPerPage . '">First</a></li>';
                    }

                    // Previous page link
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?section=trash_colleges&page=' . ($page - 1) . '&per_page=' . $itemsPerPage . '">Previous</a></li>';
                    }

                    // Ellipsis at the start
                    if ($startPage > 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link"
                                href="?section=trash_colleges&page=<?php echo $i; ?>&per_page=<?php echo $itemsPerPage; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor;

                    // Ellipsis at the end
                    if ($endPage < $totalPages) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }

                    // Next page link
                    if ($page < $totalPages) {
                        echo '<li class="page-item"><a class="page-link" href="?section=trash_colleges&page=' . ($page + 1) . '&per_page=' . $itemsPerPage . '">Next</a></li>';
                    }

                    // Last page link
                    if ($page < $totalPages) {
                        echo '<li class="page-item"><a class="page-link" href="?section=trash_colleges&page=' . $totalPages . '&per_page=' . $itemsPerPage . '">Last</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; // Assuming you have a footer to include ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const restoreButtons = document.querySelectorAll('.restore-college-btn');
    const permanentDeleteButtons = document.querySelectorAll('.permanent-delete-college-btn');

    // Make base_url available in JavaScript (if not already)
    // Add this line at the top of your <script> block
    const BASE_URL = "<?php echo base_url; ?>";

    restoreButtons.forEach(button => {
        button.addEventListener('click', function () {
            const collegeId = this.dataset.id;
            if (confirm('Are you sure you want to restore this college?')) {
                // Use BASE_URL for the fetch path
                fetch(BASE_URL + 'api/colleges/restore_college_process.php', {
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
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show text-center';
                        alertDiv.role = 'alert';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.getElementById('alertContainer').innerHTML = '';
                        document.getElementById('alertContainer').appendChild(alertDiv);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show text-center';
                        alertDiv.role = 'alert';
                        alertDiv.innerHTML = `
                            Error: ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.getElementById('alertContainer').innerHTML = '';
                        document.getElementById('alertContainer').appendChild(alertDiv);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show text-center';
                    alertDiv.role = 'alert';
                    alertDiv.innerHTML = `
                        An error occurred while trying to restore the college.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.getElementById('alertContainer').innerHTML = '';
                    document.getElementById('alertContainer').appendChild(alertDiv);
                });
            }
        });
    });

    permanentDeleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const collegeId = this.dataset.id;
            if (confirm('WARNING: Are you absolutely sure you want to PERMANENTLY DELETE this college? This action cannot be undone.')) {
                // Use BASE_URL for the fetch path
                fetch(BASE_URL + 'api/colleges/permanent_delete_college_process.php', {
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
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show text-center';
                        alertDiv.role = 'alert';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.getElementById('alertContainer').innerHTML = '';
                        document.getElementById('alertContainer').appendChild(alertDiv);
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show text-center';
                        alertDiv.role = 'alert';
                        alertDiv.innerHTML = `
                            Error: ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.getElementById('alertContainer').innerHTML = '';
                        document.getElementById('alertContainer').appendChild(alertDiv);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show text-center';
                    alertDiv.role = 'alert';
                    alertDiv.innerHTML = `
                        An error occurred while trying to permanently delete the college.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.getElementById('alertContainer').innerHTML = '';
                    document.getElementById('alertContainer').appendChild(alertDiv);
                });
            }
        });
    });
});</script>