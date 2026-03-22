<?php
$servername = "localhost";
$username = "root";
$password = "Harshi@0987"; // or your MySQL password
$dbname = "hotel_booking";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if values are posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotel_name = $_POST['hotel_name'] ?? null;
    $room_type = $_POST['room_type'] ?? null;
    $room_price = $_POST['room_price'] ?? null;
    $food_items = $_POST['food_items'] ?? null;
    $total_food_cost = $_POST['total_food_cost'] ?? null;

    $sql = "INSERT INTO booking_details (hotel_name, room_type, room_price, food_items, total_food_cost) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $hotel_name, $room_type, $room_price, $food_items, $total_food_cost);

    if ($stmt->execute()) {
        echo "Booking saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
