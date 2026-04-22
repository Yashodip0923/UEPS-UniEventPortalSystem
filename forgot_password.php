<?php
require_once __DIR__ . "/includes/header_main.php";
?>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
        color: #343a40;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .navbar-brand .logo-img {
        max-height: 40px;
        width: auto;
    }

    .btn-primary {
        background-color: #6610f2;
        border-color: #6610f2;
    }

    .btn-primary:hover {
        background-color: #550bb7;
        border-color: #550bb7;
    }

    .btn-outline-primary {
        color: #6610f2;
        border-color: #6610f2;
    }

    .btn-outline-primary:hover {
        background-color: #6610f2;
        color: white;
    }

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

    main {
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

<main class="container justify-content-center">
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="card shadow-lg rounded-3">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4 fw-bold">Forgot Password</h2>
                    <p class="text-center text-muted mb-3">Enter your email address to receive a verification code.</p>

                    <div id="alertContainer">
                        <?php
                        if (isset($_GET['status']) && isset($_GET['message'])) {
                            $status = htmlspecialchars($_GET['status']);
                            $message = htmlspecialchars($_GET['message']);
                            echo '<div class="alert alert-' . $status . ' alert-dismissible fade show text-center" role="alert">' . $message . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        }
                        $email = htmlspecialchars($_GET['email'] ?? '');
                        ?>
                    </div>

                    <form action="api/users/send_otp.php" method="POST">
                        <div class="mb-4 position-relative">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($email); ?>" required>
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
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 rounded-lg fw-semibold mt-3">Send
                                OTP</button>
                        </div>
                        <p class="text-center mt-4 mb-0"><a href="login.php">Back to Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . "/includes/footer_main.php";
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script src="api/js/forgot_password_validation.js"></script>