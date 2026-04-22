<?php
// admin_dashboard.php
// This is the main entry point for the Admin Panel.
// It includes the header, dynamically loads content based on URL, and includes the footer.

// Include the header which handles session, auth, initial data fetching, and HTML <head>
require_once __DIR__ . '/includes/header.php';

// The $currentSection variable is set in header.php based on $_GET['section']
// The $colleges, $departments, $users, $error_message variables are also available from header.php
?>

<main class="container-fluid pt-2 flex-grow-1">

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger text-center" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php
    // Dynamically include the content based on $currentSection
    $contentFile = 'content/adminview/' . $currentSection . '.php';
    if (file_exists($contentFile)) {
        include $contentFile;
    } else {
        // Fallback if file doesn't exist (though already handled by $validSections in header.php)
        include 'content/adminview/dashboard.php';
    }
    ?>

</main>
</div>
<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

<?php
// Include the footer which contains closing tags and JavaScript
require_once __DIR__ . '/includes/footer.php';
?>