<?php
// content/events.php
// This file contains the Event Management section content for admin_panel.php.

// Ensure session is started if not already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure these paths are correct based on your file structure
require_once __DIR__ . '/../../api/dbinclude.php'; // Adjust path as necessary
require_once __DIR__ . '/../../config.php'; // Adjust path as necessary

// Check if $conn is valid after including dbinclude.php
if (!isset($conn) || !$conn instanceof mysqli) {
    error_log("Database connection (MySQLi) failed in events.php.");
    echo '<div class="container mt-5"><div class="alert alert-danger text-center" role="alert">Database connection error. Please try again later.</div></div>';
    exit();
}

// Pagination variables
$itemsPerPage = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $itemsPerPage;

// Fetch total count of events for pagination
// Assuming 'status = 1' for active events
$totalResult = $conn->query("SELECT COUNT(*) as total FROM events WHERE status = 1");
$totalEvents = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalEvents / $itemsPerPage);

// Prepared statement for fetching events with pagination
$stmt = $conn->prepare("SELECT * FROM events WHERE status = 1 ORDER BY event_id ASC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close(); // Close the statement after use

$showingFrom = ($totalEvents > 0) ? (($page - 1) * $itemsPerPage) + 1 : 0;
$showingTo = min($page * $itemsPerPage, $totalEvents);

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
}

.table th,
.table td {
    vertical-align: middle;
}

/* Button spacing - Adjusted for clarity */
.btn {
    /* This rule might be too broad; fine-tuned spacing is handled by Bootstrap classes like me-1 */
    /* margin-right: 5px; */
}

/* General table styling for better responsiveness and control */
.table-responsive table {
    table-layout: fixed;
    /* Force fixed table layout */
    width: 100%;
    /* Ensure table takes full available width */
}

/* Column widths for fixed table layout - ADAPTED FOR EVENTS */
.table-responsive table th:nth-child(1),
.table-responsive table td:nth-child(1) {
    /* ID */
    width: 5%;
}

.table-responsive table th:nth-child(2),
.table-responsive table td:nth-child(2) {
    /* Name */
    width: 20%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-responsive table th:nth-child(3),
.table-responsive table td:nth-child(3) {
    /* Date */
    width: 15%;
    white-space: nowrap;
}

.table-responsive table th:nth-child(4),
.table-responsive table td:nth-child(4) {
    /* Location */
    width: 20%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-responsive table th:nth-child(5),
.table-responsive table td:nth-child(5) {
    /* Coordinator */
    width: 20%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.table-responsive table th:nth-child(6),
.table-responsive table td:nth-child(6) {
    /* Actions - No fixed width, let content dictate */
    white-space: nowrap;
}

/* Style for the actions column to ensure buttons don't push content out */
.table-responsive table td.actions-column .d-flex {
    flex-wrap: nowrap;
    /* Keep buttons in a single row */
    justify-content: center;
    /* Align buttons to the center */
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
</style>

<div class="admin-section" id="events">
    <h2 class="h3 fw-bold">Event Management</h2>

    <div class="action-and-per-page-row">
        <form method="GET" class="d-flex align-items-center mb-0">
            <input type="hidden" name="section" value="events">
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
            <a href="<?php echo base_url . 'admin_dashboard.php?section=add_event'; ?>" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Add New Event
            </a>
            <a href="<?php echo base_url . 'admin_dashboard.php?section=trash_events'; ?>"
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
                    <th scope="col">Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Location</th>
                    <th scope="col">Coordinator</th>
                    <th scope="col" class="actions-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($event['event_id']); ?></th>
                            <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($event['event_date']))); ?></td>
                            <td><?php echo htmlspecialchars($event['event_location']); ?></td>
                            <td><?php echo htmlspecialchars($event['event_coordinator']); ?></td>
                            <td class="actions-column">
                                <div class="d-flex">
                                    <a href="content/adminview/add_event.php?event_id=<?php echo htmlspecialchars($event['event_id']); ?>"
                                        class="btn btn-sm btn-info text-white me-1">Edit</a>
                                    <button class="btn btn-sm btn-danger delete-event-btn"
                                        data-id="<?php echo htmlspecialchars($event['event_id']); ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No events found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation">
        <div class="pagination-info-container">
            <p class="text-muted mb-0 fw-bold">Showing <?php echo $showingFrom; ?> to
                <?php echo $showingTo; ?> of
                <?php echo $totalEvents; ?> Events
            </p>
            <ul class="pagination justify-content-center">
                <?php
                $numLinks = 4; // Number of page links to show directly
                $startPageDisplay = max(1, $page - floor($numLinks / 2));
                $endPageDisplay = min($totalPages, $page + floor($numLinks / 2));

                // Show "First" page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?section=events&page=1&per_page=' . $itemsPerPage . '">First</a></li>';
                }

                // Show "Previous" page link
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?section=events&page=' . ($page - 1) . '&per_page=' . $itemsPerPage . '">Previous</a></li>';
                }

                // Show ellipsis if needed at the beginning
                if ($startPageDisplay > 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                for ($i = $startPageDisplay; $i <= $endPageDisplay; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="?section=events&page=<?php echo $i; ?>&per_page=<?php echo $itemsPerPage; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor;

                // Show ellipsis if needed at the end
                if ($endPageDisplay < $totalPages) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }

                // Show "Next" page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?section=events&page=' . ($page + 1) . '&per_page=' . $itemsPerPage . '">Next</a></li>';
                }

                // Show "Last" page link
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?section=events&page=' . $totalPages . '&per_page=' . $itemsPerPage . '">Last</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-event-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const eventId = this.dataset.id;
                if (confirm('Are you sure you want to soft delete this event? It can be restored from Trash.')) {
                    // You'll need to create a new API endpoint for deleting events
                    fetch('../api/events/delete_event_process.php', { // <--- **IMPORTANT: CREATE THIS FILE**
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'event_id=' + eventId
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
                            alert('An error occurred while trying to delete the event. Check console for details.');
                        });
                }
            });
        });
    });
</script>