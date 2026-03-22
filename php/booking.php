<?php
require 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["status" => "error", "message" => "Please log in first."]);
    exit;
}

$username = $_SESSION['username'];

// Read users.txt to verify username still exists
$userFound = false;
$file = fopen("../users.txt", "r");
if ($file) {
    while (($line = fgets($file)) !== false) {
        list($storedUsername, $storedEmail, $storedHashedPassword) = explode("||", trim($line));
        if ($username === $storedUsername || $username === $storedEmail) {
            $userFound = true;
            break;
        }
    }
    fclose($file);
}

if (!$userFound) {
    echo json_encode(["status" => "error", "message" => "Username not found in users.txt"]);
    exit;
}

// Get data from POST request
$checkIn = $_POST['check_in'] ?? '';
$checkOut = $_POST['check_out'] ?? '';
$guests = $_POST['guests'] ?? '';


if (empty($checkIn) || empty($checkOut) || empty($guests)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

if (strtotime($checkOut) <= strtotime($checkIn)) {
    echo json_encode(["status" => "error", "message" => "Check-out must be after check-in."]);
    exit;
}

if (!is_numeric($guests) || $guests > 5) {
    echo json_encode(["status" => "error", "message" => "Maximum 5 guests allowed."]);
    exit;
}

// Insert booking into database
$stmt = $conn->prepare("INSERT INTO bookings (username, check_in, check_out, guests) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $username, $checkIn, $checkOut, $guests);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
      
        "redirect" => "../html/Hotels.html"
    ]);
} else {
    echo json_encode(["status" => "error", "redirect" => "../html/Hotels.html" ]);
}

?>
