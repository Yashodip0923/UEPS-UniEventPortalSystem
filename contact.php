<?php
// contact.php
// UniEventPortal - Contact Us page.

// Include the main header file
require_once __DIR__ . '/includes/header_main.php';
?>
<style>
    /* Page-specific styles for the contact form */
    .form-control {
        border: 2px solid #ced4da;
        box-shadow: none !important;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: none !important;
    }

    .form-control.is-invalid {
        border-color: #dc3545 !important;
        padding-right: calc(1.5em + 0.75rem + 1.25rem) !important;
        background-image: none !important;
    }

    .form-control.is-valid {
        border-color: #198754 !important;
        padding-right: calc(1.5em + 0.75rem + 1.25rem) !important;
        background-image: none !important;
    }

    .input-icon-container {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        pointer-events: none;
        width: 1.25rem;
        height: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-icon-container svg {
        width: 100%;
        height: 100%;
        display: none;
    }

    .input-icon-container svg.icon-show {
        display: block;
    }

    .icon-success {
        fill: #198754;
    }

    .icon-error {
        fill: #dc3545;
    }
</style>

<body class="bg-light text-dark">
    <main class="container py-3 flex-grow-1"> <!-- Added flex-grow-1 here -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg rounded-3">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-3 fw-bold">Contact Us</h2>
                        <p class="text-center text-muted mb-3">Have questions, feedback, or need support? Reach out to
                            us!</p>

                        <?php
                        if (isset($_GET['status'])) {
                            $status = $_GET['status'];
                            $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

                            if ($status == 'success') {
                                echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                        Your message has been sent successfully!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                            } elseif ($status == 'error') {
                                $displayMessage = !empty($message) ? $message : "There was an error sending your message. Please try again.";
                                echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                        ' . $displayMessage . '
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                            }
                        }
                        ?>

                        <form id="contactForm" action="api/contact/submit_message.php" method="POST" novalidate>
                            <div class="mb-2 position-relative">
                                <label for="contactName" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="contactName" name="name" required>
                                <div class="input-icon-container">
                                    <svg id="nameSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                        fill="currentColor" style="display: none;">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                    </svg>
                                    <svg id="nameErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                        style="display: none;">
                                        <path
                                            d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                    </svg>
                                </div>
                                <span id="nameError" class="text-danger small"></span>
                            </div>
                            <div class="mb-2 position-relative">
                                <label for="contactEmail" class="form-label">Your Email</label>
                                <input type="email" class="form-control" id="contactEmail" name="email" required>
                                <div class="input-icon-container">
                                    <svg id="emailSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                        fill="currentColor" style="display: none;">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                    </svg>
                                    <svg id="emailErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                        style="display: none;">
                                        <path
                                            d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                    </svg>
                                </div>
                                <span id="emailError" class="text-danger small"></span>
                            </div>
                            <div class="mb-2 position-relative">
                                <label for="contactSubject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="contactSubject" name="subject" required>
                                <div class="input-icon-container">
                                    <svg id="subjectSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                        fill="currentColor" style="display: none;">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                    </svg>
                                    <svg id="subjectErrorIcon" class="icon-error" viewBox="0 0 24 24"
                                        fill="currentColor" style="display: none;">
                                        <path
                                            d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                    </svg>
                                </div>
                                <span id="subjectError" class="text-danger small"></span>
                            </div>
                            <div class="mb-2">
                                <label for="contactMessage" class="form-label">Message</label>
                                <textarea class="form-control" id="contactMessage" name="message" rows="5"
                                    required></textarea>
                                <span id="messageError" class="text-danger small"></span>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-lg fw-semibold mt-3">Send
                                Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="api/js/contact_validation.js"></script>
    <?php
    // Include the main footer file
    require_once __DIR__ . '/includes/footer_main.php';
    ?>