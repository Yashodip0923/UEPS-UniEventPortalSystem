<?php
// login.php
// UniEventPortal - Login page.

// Include the main header file
require_once __DIR__ . '/includes/header_main.php';
?>
<style>
    /* Page-specific styles for the login form */
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
        padding-right: calc(0rem) !important;
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

    main {
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<main class="container justify-content-center">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg rounded-3">
                <div class="card-body col-12">
                    <h2 class="card-title text-center mb-0 fw-bold">Login</h2>
                    <p class="text-center text-muted mb-2">Log in to access your UniEventPortal account.</p>

                    <div id="alertContainer">
                        <?php
                        if (isset($_GET['status']) && isset($_GET['message'])) {
                            $status = htmlspecialchars($_GET['status']);
                            $message = htmlspecialchars($_GET['message']);
                            echo '<div class="alert alert-' . $status . ' alert-dismissible fade show text-center" role="alert">' . $message . '</div>';
                        }
                        ?>
                    </div>

                    <form action="api/users/login_process.php" method="POST">
                        <div class="mb-1 position-relative">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" size="20"
                                value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" required>
                            <div class="input-icon-container">
                                <svg id="emailSuccessIcon" class="icon-success" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
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
                        <div class="mb-1 position-relative">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-icon-container">
                                <svg id="passwordSuccessIcon" class="icon-success" viewBox="0 0 24 24"
                                    fill="currentColor" style="display: none;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <svg id="passwordErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor"
                                    style="display: none;">
                                    <path
                                        d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z" />
                                </svg>
                            </div>
                            <span id="passwordError" class="text-danger small"></span>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit"
                                class="btn btn-primary py-2 rounded-lg fw-semibold mt-3">Login</button>
                        </div>
                        <p class="text-center mt-2 mb-0">Don't have an account? <a href="register.php">Register</a>
                        </p>
                        <p class="text-center mt-1 mb-0"><a href="forgot_password.php">Forgot Password?</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="api/js/login_validation.js"></script>
</body>

</html>
<?php
// Include the main footer file
require_once __DIR__ . '/includes/footer_main.php';
?>