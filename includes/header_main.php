<?php
// includes/header_main.php
// This file contains the HTML <head> section and the main navigation bar for public-facing pages.
// It is designed to be included at the beginning of any new public page (e.g., index.php, events.php, contact.php).
require_once __DIR__ ."/../site_settings.php";  
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Define pages where Login/Register buttons should be hidden
$hide_auth_buttons_pages = ['login.php', 'register.php'];

// Check if current page is one where auth buttons should be hidden
$hide_buttons = in_array($current_page, $hide_auth_buttons_pages);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteSettings['site_title'];?> - Your College Events Hub</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts - Inter for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles to ensure Inter font is applied globally */
        html, body { /* Added height: 100% for proper flexbox behavior */
            height: 100%;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            /* Light grey background */
            color: #343a40;
            /* Dark grey text */
            /* Disable text selection */
            -webkit-touch-callout: none;
            /* iOS Safari */
            -webkit-user-select: none;
            /* Safari */
            -khtml-user-select: none;
            /* Konqueror HTML */
            -moz-user-select: none;
            /* Old versions of Firefox */
            -ms-user-select: none;
            /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
            display: flex; /* Added for footer to stick to bottom */
            flex-direction: column; /* Added for footer to stick to bottom */
            min-height: 100vh; /* Added for footer to stick to bottom */
        }

        .navbar-brand .logo-img {
            max-height: 40px;
            /* Adjust as needed for your logo size */
            width: auto;
        }

        .hero-section {
            background: linear-gradient(to right, #6f42c1, #0d6efd);
            /* Bootstrap Purple to Blue */
            color: white;
            padding: 6rem 1rem;
            text-align: center;
            border-bottom-left-radius: 1rem;
            /* Rounded corners for bottom */
            border-bottom-right-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
        }

        .card {
            border-radius: 0.75rem;
            /* More rounded cards */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-0.25rem);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
        }

        .btn-primary {
            background-color: #6610f2;
            /* Custom primary button color */
            border-color: #6610f2;
        }

        .btn-primary:hover {
            background-color: #550bb7;
            /* Darker hover */
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

        .footer {
            background-color: #343a40;
            /* Dark background */
            color: white;
            padding: 2rem 0;
            /* margin-top: auto; */ /* Removed from here, handled by mt-auto class on footer tag */
            border-top-left-radius: 1rem;
            /* Rounded corners for top */
            border-top-right-radius: 1rem;
        }

        .team-member img {
            border: 4px solid #6610f2;
            /* Bootstrap Purple border */
        }

        .icon-small {
            width: 1rem;
            /* 16px */
            height: 1rem;
            /* 16px */
        }

        /* NEW: Remove underline from anchor tags within forms */
        form a {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-bottom-lg py-3 px-4">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <!-- Website Logo -->
                <svg width="40" height="40" viewBox="0 0 220 200" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="me-2">
                    <title><?php echo $siteSettings['site_title'];?> Logo Option 3 - AYLY Burst</title>
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
                <span class="fs-4 fw-bold text-primary"><?php echo $siteSettings['site_title'];?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'events.php') ? 'active' : ''; ?>" href="events.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact Us</a>
                    </li>
                    <?php if (!$hide_buttons): ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary shadow-sm px-4 py-2 rounded-lg" href="login.php">Login</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary shadow-sm px-4 py-2 rounded-lg" href="register.php">Register</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
