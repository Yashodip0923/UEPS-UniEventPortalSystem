<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "unieventportal"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("An error occurred while connecting to the database. Please try again later.");
}
?>