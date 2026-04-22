<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - UniEventPortal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
</head>

<body class="bg-light text-dark">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-bottom-lg py-3 px-4">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <svg width="40" height="40" viewBox="0 0 220 200" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="me-2">
                    <title>UniEventPortal Logo Option 3 - AYLY Burst</title>
                    <defs>
                        <linearGradient id="grad3_AYLY_nav" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#6f42c1;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#0d6efd;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle cx="100" cy="100" r="60" fill="url(#grad3_AYLY_nav)" />
                    <path d="M100 40 L110 70 L140 70 L115 90 L125 120 L100 110 L75 120 L85 90 L60 70 L90 70 Z"
                        fill="white" opacity="0.2" />
                    <text x="100" y="110" font-family="Inter, sans-serif" font-size="38" font-weight="bold" fill="white"
                        text-anchor="middle">AYLY</text>
                    <path d="M100 50 L100 30" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M150 100 L170 100" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M100 150 L100 170" stroke="white" stroke-width="4" stroke-linecap="round" />
                    <path d="M50 100 L30 100" stroke="white" stroke-width="4" stroke-linecap="round" />
                </svg>
                <span class="fs-4 fw-bold text-primary">UniEventPortal</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary shadow-sm px-4 py-2 rounded-lg" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container justify-content-center">
        <div class="row justify-content-center">
            <div class="col-6 col-auto">
                <div class="card shadow-lg rounded-3">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4 fw-bold">Verify OTP</h2>
                        <p class="text-center text-muted mb-3">A verification code has been sent to your email. Please enter it below.</p>

                        <div id="alertContainer">
                            <?php
                            if (isset($_GET['status']) && isset($_GET['message'])) {
                                $status = htmlspecialchars($_GET['status']);
                                $message = htmlspecialchars($_GET['message']);
                                echo '<div class="alert alert-' . $status . ' alert-dismissible fade show text-center" role="alert">' . $message . '</div>';
                            }
                            $email = htmlspecialchars($_GET['email'] ?? '');
                            ?>
                        </div>

                        <form action="api/users/verify_otp_process.php" method="POST">
                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                            <div class="mb-1 position-relative">
                                <label for="otp" class="form-label">Verification Code (OTP)</label>
                                <input type="text" class="form-control" id="otp" name="otp" required maxlength="6">
                                <div class="input-icon-container">
                                    <svg id="otpSuccessIcon" class="icon-success" viewBox="0 0 24 24" fill="currentColor" style="display: none;">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                    <svg id="otpErrorIcon" class="icon-error" viewBox="0 0 24 24" fill="currentColor" style="display: none;">
                                        <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                                    </svg>
                                </div>
                                <span id="otpError" class="text-danger small"></span>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2 rounded-lg fw-semibold mt-3">Verify Code</button>
                            </div>
                            <p class="text-center mt-4 mb-0"><a href="forgot_password.php?email=<?php echo urlencode($email); ?>">Resend Code</a> Or
                            <a href="login.php">Back to Login</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-0 rounded-top-lg text-center">
        <div class="container">
            <p class="mb-2">&copy; 2025 UniEventPortal. All rights reserved.</p>
            <p class="mb-0">Designed by Abhishek, Yojana, Lajari & Yashodip</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="api/js/verify_otp_validation.js"></script>
</body>

</html>
