<?php
// db.php

$servername = "localhost"; 
    // Usually 'localhost' if database is on the same server
$dbUsername = "root";          // Your database username (adjust accordingly)
$dbPassword = "Harshi@0987";              // Your database password
$dbName = "hotel_booking";     // Your actual database name

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName,);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>