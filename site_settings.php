<?php

// 1. Database Connection (replace with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unieventportal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Fetch Settings from Database
$siteSettings = []; // Initialize an empty array to store settings

$sql = "SELECT setting_key, setting_value FROM app_settings"; // Assuming your table is named 'settings'
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $siteSettings[$row["setting_key"]] = $row["setting_value"];
    }
} else {
    // Handle case where no settings are found (e.g., set default values)
    $siteSettings['site_title'] = "UniEventPortal";
    $siteSettings['admin_email'] = "abhishekbhat014@gmail.com";
    $siteSettings['site_logo_url'] = "assets/images/websiteLogo.png"; // Default logo URL
    
}

// 3. Close Database Connection
$conn->close();
?>