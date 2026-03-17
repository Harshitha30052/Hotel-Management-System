<?php
require 'db.php';

$adminName = "admin";
$adminEmail = "admin@hotel.com";
$adminPass = password_hash("admin123", PASSWORD_DEFAULT); // choose your own password

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $adminName, $adminEmail, $adminPass);

if ($stmt->execute()) {
    echo "Admin created successfully!";
} else {
    echo "Error creating admin: " . $stmt->error;
}
?>