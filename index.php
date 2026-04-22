<?php
// index.php
// This is the main homepage file for UniEventPortal.
// It includes the common header and footer for public-facing pages.

// Include the main header file
require_once __DIR__ . '/includes/header_main.php';
require_once __DIR__ .'/config.php'; // Include config file for base_url and other constants

// Optional: Include database connection if you want to dynamically fetch events
// require_once 'api/dbinclude.php'; 

// Example: Fetch events data if db_connection is included
// $events = [];
// try {
//     if (isset($pdo)) { // Check if $pdo object exists from dbinclude.php
//         $stmt = $pdo->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");
//         $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     }
// } catch (PDOException $e) {
//     // Log error: error_log("Error fetching events for homepage: " . $e->getMessage());
//     // Optionally, set an error message to display on the page
// }
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">
            Discover & Join Exciting College Events
        </h1>
        <p class="lead mb-5 opacity-90">
            Your one-stop portal to explore, register, and manage all academic, cultural, and sports events across
            your college.
        </p>
        <a href="events.php"
            class="btn btn-light btn-lg rounded-pill shadow-lg fw-bold px-5 py-3 transition-transform-hover">
            Explore Events
        </a>
    </div>
</section>

<!-- Upcoming Events Section -->
<main class="container py-5">
    <h2 class="text-center mb-5 fw-bold">Upcoming Events</h2>
    <div id="events-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <!-- Event cards will be dynamically loaded here by PHP from your database -->
        <?php
        // Example PHP code to fetch events (this is just a placeholder)
        // Uncomment and use if you include db_connection.php and have actual events
        // if (!empty($events)) {
        //     foreach ($events as $event) {
        //         $event_type_class = '';
        //         $event_type_text = '';
        //         if ($event['is_departmental']) {
        //             $event_type_class = 'text-primary'; // Using Bootstrap's primary color
        //             $event_type_text = 'Departmental Event';
        //         } elseif ($event['is_competition_event']) {
        //             $event_type_class = 'text-danger'; // Bootstrap's danger color
        //             $event_type_text = 'Competition Event';
        //         } else {
        //             $event_type_class = 'text-success'; // Bootstrap's success color
        //             $event_type_text = 'Non-Departmental Event';
        //         }
        ?>
        <!--
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title fs-4 fw-semibold mb-2"><?php //echo htmlspecialchars($event['event_name']); ?></h3>
                        <p class="card-subtitle mb-2 <?php //echo $event_type_class; ?> fs-6"><?php //echo $event_type_text; ?></p>
                        <p class="card-text text-muted fs-6 mb-4"><?php //echo htmlspecialchars($event['description']); ?></p>
                        <div class="d-flex align-items-center text-muted fs-6 mb-2">
                            <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                            <span>Date: <?php //echo htmlspecialchars(date('F j, Y', strtotime($event['event_date']))); ?></span>
                        </div>
                        <div class="d-flex align-items-center text-muted fs-6 mb-4">
                            <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 100-4 0 2z" clip-rule="evenodd"></path></svg>
                            <span>Location: <?php //echo htmlspecialchars($event['location']); ?></span>
                        </div>
                        <a href="event_details.php?id=<?php //echo $event['event_id']; ?>" class="btn btn-primary w-100 mt-2 rounded-lg fw-semibold">View Details</a>
                    </div>
                </div>
            </div>
            -->
        <?php
        // } // End of PHP loop
        // } else {
        //     echo '<div class="col-12"><div class="alert alert-info text-center" role="alert">No upcoming events found.</div></div>';
        // }
        ?>

        <!-- Dummy Event Cards (Will be replaced by dynamic PHP content) -->
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title fs-4 fw-semibold mb-2">Annual Sports Meet</h3>
                    <p class="card-subtitle mb-2 text-primary fs-6">Departmental Event (Physical Education)</p>
                    <p class="card-text text-muted fs-6 mb-4">
                        Join us for a day of exhilarating sports competitions including track and field, basketball,
                        and more. All departments welcome!
                    </p>
                    <div class="d-flex align-items-center text-muted fs-6 mb-2">
                        <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Date: July 15, 2025</span>
                    </div>
                    <div class="d-flex align-items-center text-muted fs-6 mb-4">
                        <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 100-4 0 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Location: College Ground</span>
                    </div>
                    <button class="btn btn-primary w-100 mt-2 rounded-lg fw-semibold">View Details</button>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title fs-4 fw-semibold mb-2">Inter-College Debate Competition</h3>
                    <p class="card-subtitle mb-2 text-danger fs-6">Competition Event</p>
                    <p class="card-text text-muted fs-6 mb-4">
                        Showcase your oratorical skills! Compete against students from various colleges on pressing
                        social issues.
                    </p>
                    <div class="d-flex align-items-center text-muted fs-6 mb-2">
                        <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Date: August 5, 2025</span>
                    </div>
                    <div class="d-flex align-items-center text-muted fs-6 mb-4">
                        <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 100-4 0 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Location: Auditorium Hall</span>
                    </div>
                    <button class="btn btn-primary w-100 mt-2 rounded-lg fw-semibold">View Details</button>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title fs-4 fw-semibold mb-2">Cultural Fest: 'Rendition 2025'</h3>
                    <p class="card-subtitle mb-2 text-success fs-6">Non-Departmental Event</p>
                    <p class="card-text text-muted fs-6 mb-4">
                        A grand celebration of arts and culture with music, dance, drama, and art exhibitions from
                        all colleges.
                    </p>
                    <div class="d-flex align-items-center text-muted fs-6 mb-2">
                        <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Date: September 1-3, 2025</span>
                    </div>
                    <div class="d-flex align-items-center text-muted fs-6 mb-4">
                        <svg class="icon-small me-2 text-primary" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 100-4 0 2z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Location: College Campus</span>
                    </div>
                    <button class="btn btn-primary w-100 mt-2 rounded-lg fw-semibold">View Details</button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Our Team Section -->
<section class="container text-center py-5">
    <h2 class="mb-5 fw-bold">Our Team</h2>
    <div class="d-flex justify-content-center align-items-center mb-3">
        <!-- Team Photo -->
        <!-- Ensure team.jpg is in the 'images' folder inside your project root -->
        <img src="<?php echo base_url . "assets/images/team.jpg"; ?>" alt="Our UniEventPortal Team: Abhishek, Yojana, Yashodip, and Lajri"
            class="img-fluid rounded-3 shadow-lg" style="max-width: 600px; height: auto; border: 4px solid #6610f2;">
    </div>
    <div class="fs-4 fw-semibold text-dark">
        Abhishek, Yojana, Lajari & Yashodip
    </div>
    <p class="text-primary fs-5 mt-2">BSc IT Students</p>
</section>
<?php
// Include the main footer file
require_once __DIR__ . '/includes/footer_main.php';
?>