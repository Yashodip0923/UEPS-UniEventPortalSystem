<?php
// includes/footer.php
// This file contains the closing HTML tags, footer, and JavaScript includes.
?>
</div>
<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper - This closes the #wrapper div started in header.php -->

<footer class="bg-dark text-white py-4 text-center footer"
    style="border-top-right-radius: 1rem; border-top-left-radius: 1rem;">
    <div class="container">
        <p class="mb-2">&copy; 2025 UniEventPortal. All rights reserved.</p>
        <p class="mb-0">Designed by Abhishek, Yojana, Lajari & Yashodip</p>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script>
    // Right-click disable script
    document.addEventListener('contextmenu', event => event.preventDefault());

    // Sidebar Toggle Script (remains the same as it controls layout, not content)
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('wrapper');
        const sidebarToggle = document.getElementById('sidebarToggle');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                wrapper.classList.toggle('toggled');
            });
        }

        // --- Chart Re-initialization for Dashboard ---
        // Since we are using full page reloads, the charts will naturally re-initialize
        // when the dashboard.php content is loaded.
        // However, if you want to ensure they redraw correctly, you might need to
        // pass PHP data to JS variables and then call initializeCharts.
        // This part is now handled within content/dashboard.php itself.
        // The previous JS event listener for dashboard link is removed from here.

        // Dummy Chart Data and Initialization (moved inside a function to redraw if needed)
        function initializeCharts() {
            // User Distribution Chart
            const userTypeCtx = document.getElementById('userTypeChart');
            if (userTypeCtx) {
                new Chart(userTypeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Students', 'Coordinators', 'Admins', 'Event Leaders', 'Organizers'],
                        datasets: [{
                            // Data will be passed from PHP in content/dashboard.php
                            data: typeof userTypeChartData !== 'undefined' ? userTypeChartData : [0, 0, 0, 0, 0],
                            backgroundColor: ['#6610f2', '#0d6efd', '#343a40', '#fd7e14', '#20c997'],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: false,
                                text: 'User Distribution by Type'
                            }
                        }
                    }
                });
            }

            // Events per Month Chart (Dummy Data)
            const eventsPerMonthCtx = document.getElementById('eventsPerMonthChart');
            if (eventsPerMonthCtx) {
                new Chart(eventsPerMonthCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        // Data will be passed from PHP in content/dashboard.php
                        datasets: [{
                            label: 'Number of Events',
                            data: typeof eventsPerMonthChartData !== 'undefined' ? eventsPerMonthChartData : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            backgroundColor: '#6610f2',
                            borderColor: '#550bb7',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                            title: {
                                display: false,
                                text: 'Events per Month (Upcoming)'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }

            // College Count Chart
            const collegeCountCtx = document.getElementById('collegeCountChart');
            if (collegeCountCtx) {
                new Chart(collegeCountCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Total Colleges: <?php echo $collegeCountChartData ?? 0 ?>'],
                        datasets: [{
                            // Data will be passed from PHP in content/dashboard.php
                            data: typeof collegeCountChartData !== 'undefined' ? [collegeCountChartData] : [0],
                            backgroundColor: ['#0d6efd'],
                            borderColor: '#0b5ed7',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: false,
                                text: 'College Count'
                            }
                        }
                    }
                });
            }

            // Department Count Chart
            const departmentCountCtx = document.getElementById('departmentCountChart');
            if (departmentCountCtx) {
                new Chart(departmentCountCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Total Departments: <?php echo $departmentCountChartData ?? 0 ?>'],
                        datasets: [{
                            // Data will be passed from PHP in content/dashboard.php
                            data: typeof departmentCountChartData !== 'undefined' ? [departmentCountChartData] : [0],
                            backgroundColor: ['#20c997'],
                            hoverOffset: 4,
                            borderWidth: .0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: false,
                                text: 'Department Count'
                            }
                        }
                    }
                });
            }
        }

        // Call initializeCharts on page load
        // This will run AFTER the content script has defined the data variables
        initializeCharts();
    });
</script>

<style>
    /* Make footer text unselectable */
    .footer {
        /* Target the main footer element */
        -webkit-user-select: none;
        /* For Safari */
        -moz-user-select: none;
        /* For Firefox */
        -ms-user-select: none;
        /* For IE/Edge */
        user-select: none;
        /* Standard */
    }

    /* You can also be more specific, e.g., to target only the paragraphs within the footer */
    .footer p {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>

</body>

</html>