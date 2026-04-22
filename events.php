<?php
// events.php
// UniEventPortal - Displays all upcoming events from the database across various colleges.

// Database connection include (This needs to be before header_main if header_main needs DB data)
require_once __DIR__ . '/api/dbinclude.php';

// Include the main header file
require_once __DIR__ . '/includes/header_main.php';

// Initialize events array
$all_upcoming_events = [];
$fetch_error = '';

// try {
//     // Fetch all upcoming events from the database
//     // Event ke saath college aur department ka naam bhi mile.
//     // Logic: events -> users (via coordinator_user_id) -> college_coordinators -> colleges
//     $stmt = $pdo->prepare("
//         SELECT
//             e.event_id,
//             e.event_name,
//             e.description,
//             e.event_date,
//             e.event_time,
//             e.location,
//             e.is_departmental,
//             e.is_competition_event,
//             c.college_name,
//             d.department_name
//         FROM
//             events e
//         JOIN
//             users u ON e.coordinator_user_id = u.user_id /* Event coordinator ko user table se joda */
//         JOIN
//             college_coordinators cc ON u.user_id = cc.user_id /* Coordinator ko unke assigned college se joda */
//         JOIN
//             colleges c ON cc.college_id = c.college_id /* College ka naam fetch kiya */
//         LEFT JOIN
//             departments d ON e.department_id = d.department_id AND e.is_departmental = 1
//         WHERE
//             e.event_date >= CURDATE()
//         ORDER BY
//             e.event_date ASC, e.event_time ASC
//     ");
//     $stmt->execute();
//     $all_upcoming_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// } catch (PDOException $e) {
//     // Error handling if events cannot be fetched
//     $fetch_error = "Error loading events: " . $e->getMessage();
//     // For production, you should log this error: error_log("All Events fetch error: " . $e->getMessage());
// }
?>

<section class="container py-5 text-center">
    <h1 class="display-4 fw-bold mb-3">All Upcoming Events</h1>
    <p class="lead text-muted">Explore events happening across various colleges.</p>
</section>

<main class="container py-3 flex-grow-1">
    <div id="events-listing-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if (!empty($all_upcoming_events)): ?>
            <?php foreach ($all_upcoming_events as $event): ?>
                <?php
                $event_type_class = '';
                $event_type_text = ''; // Default for general event
                if ($event['is_departmental']) {
                    $event_type_class = 'text-primary'; // Bootstrap's primary color
                    $event_type_text = 'Departmental Event' . (empty($event['department_name']) ? '' : ' (' . htmlspecialchars($event['department_name']) . ')');
                } elseif ($event['is_competition_event']) {
                    $event_type_class = 'text-danger'; // Bootstrap's danger color
                    $event_type_text = 'Competition Event';
                } else {
                    $event_type_class = 'text-success'; // Bootstrap's success color
                    $event_type_text = 'General Event';
                }
                ?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title fs-4 fw-semibold mb-2">
                                <?php echo htmlspecialchars($event['event_name']); ?>
                            </h3>
                            <p class="card-subtitle mb-2 <?php echo $event_type_class; ?> fs-6">
                                <?php echo $event_type_text; ?>
                            </p>
                            <p class="card-text text-muted fs-6 mb-3 flex-grow-1">
                                <?php echo htmlspecialchars($event['description']); ?>
                            </p>

                            <div class="d-flex align-items-center text-muted fs-6 mb-2">
                                <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Date:
                                    <?php echo htmlspecialchars(date('F j, Y', strtotime($event['event_date']))); ?></span>
                            </div>
                            <div class="d-flex align-items-center text-muted fs-6 mb-2">
                                <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 10-4 0 2z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Location: <?php echo htmlspecialchars($event['location']); ?></span>
                            </div>
                            <div class="d-flex align-items-center text-muted fs-6 mb-4">
                                <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 002 2v4a2 2 0 002 2V6a2 2 0 00-2-2H4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>College: <?php echo htmlspecialchars($event['college_name']); ?></span>
                            </div>

                            <a href="event_details.php?id=<?php echo htmlspecialchars($event['event_id']); ?>"
                                class="btn btn-primary w-100 mt-auto rounded-lg fw-semibold">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <?php echo !empty($fetch_error) ? htmlspecialchars($fetch_error) : 'Currently, koi upcoming events nahi hain across any college.'; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
// Include the main footer file
require_once __DIR__ . '/includes/footer_main.php';
?>