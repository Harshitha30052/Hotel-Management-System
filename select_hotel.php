<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log incoming POST data to a file for debugging
file_put_contents("log.txt", "POST:\n" . print_r($_POST, true), FILE_APPEND);

// Connect to DB
$conn = new mysqli("localhost", "root", "", "hotel_booking");
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Check if POST request contains hotelName and action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotelName'], $_POST['action'])) {
    // Sanitize and store the input data
    $hotelName = $conn->real_escape_string(trim($_POST['hotelName']));
    $action = $_POST['action'];

    // Debug: Log the action and hotelName
    file_put_contents("log.txt", "Action: $action, Hotel: $hotelName\n", FILE_APPEND);

    if ($action === 'select') {
        // Check if the hotel already exists
        $check = $conn->query("SELECT * FROM hotels WHERE hotel_name = '$hotelName'");
        if ($check && $check->num_rows > 0) {
            // If it exists, update it
            $conn->query("UPDATE hotels SET selected = 1 WHERE hotel_name = '$hotelName'");
        } else {
            // If not, insert a new record
            $conn->query("INSERT INTO hotels (hotel_name, selected) VALUES ('$hotelName', 1)");
        }
        // Respond with a success message
        echo json_encode(["message" => "Hotel selected", "selected" => true]);
    } elseif ($action === 'unselect') {
        // Unselect the hotel
        $conn->query("UPDATE hotels SET selected = 0 WHERE hotel_name = '$hotelName'");
        // Respond with a success message
        echo json_encode(["message" => "Hotel unselected", "selected" => false]);
    } else {
        // Invalid action
        echo json_encode(["message" => "Invalid action"]);
    }
} else {
    // Missing hotelName or action
    echo json_encode(["message" => "Invalid request"]);
}

// Close the database connection
$conn->close();
?>
