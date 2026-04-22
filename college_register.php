<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>College Registration | UniEventPortal</title>

    <!-- Google Font and Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .section-title {
            font-weight: 501;
            border-bottom: 2px solid #0d6efd;
            display: inline-block;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }
        .form-card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
            <img src="images/UniEventPortalLogo.png" alt="UniEventPortal Logo" style="height: 40px; margin-right: 10px;">
            UniEventPortal
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <!-- same links as home -->
            </ul>
        </div>
    </div>
</nav>

<!-- Main Container -->
<div class="container mt-5 pt-5">
    <h4 class="section-title">College Registration</h4>
    <div class="card form-card p-4">
    <form action="college_register_process.php" method="POST" class="mt-4">
    <div class="mb-3">
        <label>College Name:</label>
        <input type="text" name="college_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>College Code:</label>
        <input type="text" name="college_code" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Contact Number:</label>
        <input type="number" name="contact" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Address:</label>
        <textarea name="address" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label>City:</label>
        <input type="text" name="city" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>State:</label>
        <input type="text" name="state" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="departments">Number of Departments:</label>
        <input type="number" id="departments" name="departments" class="form-control" min="1" required oninput="generateDepartmentFields()">
    </div>

    <!-- Dynamic Department Names -->
    <div id="departmentNames"></div>

</form>
    <div class="mb-3">
        <label>College Website (optional):</label>
        <input type="text" name="website" class="form-control">
    </div>
    <div class="mb-3">
        <label>Coordinator Name:</label>
        <input type="text" name="coordinator_name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Coordinator Contact:</label>
        <input type="number" name="coordinator_contact" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Coordinator email:</label>
        <input type="number" name="coordinator_email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Coordinator Password:</label>
        <input type="password" name="coordinator_password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>College Logo:</label>
        <input type="file" name="college_logo" class="form-control" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-primary">Register College</button>
</form>

    </div>
</div>

<!-- Footer -->
<footer class="mt-5">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid justify-content-center">
            <span class="navbar-text text-white">
                © 2025 UniEventPortal | Designed by Abhishek & Team
            </span>
        </div>
    </nav>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">function generateDepartmentFields() {
    let count = document.getElementById('departments').value;
    let container = document.getElementById('departmentNames');
    container.innerHTML = '';

    for (let i = 1; i <= count; i++) {
        let div = document.createElement('div');
        div.className = 'mb-3';
        div.innerHTML = `<label>Department ${i} Name:</label>
                         <input type="text" name="department_names[]" class="form-control" required>`;
        container.appendChild(div);
    }
}</script>

</body>
</html>
