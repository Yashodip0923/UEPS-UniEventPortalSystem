<?php
// content/dashboard.php
// This file contains the Dashboard section content for admin_panel.php.
// It assumes $colleges, $departments, $users, $error_message are available from header.php.

// Prepare data for Chart.js
$userTypeChartData = [
    count(array_filter($users, fn($u) => $u['role_id'] == 1)),
    count(array_filter($users, fn($u) => $u['role_id'] == 2)),
    count(array_filter($users, fn($u) => $u['role_id'] == 3)),
    count(array_filter($users, fn($u) => $u['role_id'] == 4)),
    count(array_filter($users, fn($u) => $u['role_id'] == 5))
];


$eventsPerMonthChartData = [];
$collegeCountChartData = count($colleges);
$departmentCountChartData = count($departments);

?>
<style>
     body {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
    }
</style>
<div class="admin-section" id="dashboard">
    <h2 class="h3 fw-bold mb-4">Dashboard Overview</h2>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5 class="card-title">User Distribution by Type</h5>
                <canvas id="userTypeChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5 class="card-title">Events per Month (Upcoming)</h5>
                <canvas id="eventsPerMonthChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5 class="card-title">College Count</h5>
                <canvas id="collegeCountChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5 class="card-title">Department Count</h5>
                <canvas id="departmentCountChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Define JavaScript variables from PHP data for Chart.js
    const userTypeChartData = <?php echo json_encode($userTypeChartData); ?>;
    const eventsPerMonthChartData = <?php echo json_encode($eventsPerMonthChartData); ?>;
    const collegeCountChartData = <?php echo json_encode($collegeCountChartData); ?>;
    const departmentCountChartData = <?php echo json_encode($departmentCountChartData); ?>;

    // The initializeCharts function is defined in footer.php and will be called on DOMContentLoaded.
    // No need to call it here directly, as it will be called after this script runs.
    // However, if you want to ensure charts are redrawn on *every* dashboard load (even without full page reload if JS was used for section switching),
    // you would call initializeCharts() here. But since we're doing full page reloads, DOMContentLoaded in footer is sufficient.
</script>