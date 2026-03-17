<?php

$usersFile = "users.txt";
$username = "";  


if (file_exists($usersFile)) {
    $lines = file($usersFile, FILE_IGNORE_NEW_LINES); 
    foreach ($lines as $line) {
        list($storedUsername, $storedEmail, $storedHashedPassword) = explode("||", $line);
    
        if (isset($_SESSION['username']) && $_SESSION['username'] === $storedUsername) {
            $username = $storedUsername;
            break;
        }
    }
}

if (empty($username)) {
    echo "Error: User not logged in.";
    exit;
}

$hotel = isset($_POST['hotel_name']) ? trim($_POST['hotel_name']) : '';

if (!empty($hotel)) {
   
    $host = "localhost";
    
    $dbUsername = "root"; 
    $dbPassword = "Harshi@0987"; 
    $database = "hotel_booking"; 
    
    $conn = new mysqli($host, $dbUsername, $dbPassword, $database, $port);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("
        UPDATE bookings 
        SET hotel_name = ? 
        WHERE username = ? AND (hotel_name = '' OR hotel_name IS NULL)
        ORDER BY created_at DESC
        LIMIT 1
    ");
    
    $stmt->bind_param("ss", $hotel, $username);

    if ($stmt->execute()) {
        echo "Hotel name stored successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error: Hotel name is missing.";
}
?>
