<?php
session_start();
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "Harshi@0987", "hotel_booking");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$hotel = $data["hotelName"];
$room = $data["roomType"];
$price = $data["roomPrice"];
$user = $_SESSION["username"];
$date = date("Y-m-d H:i:s");

$sql = "INSERT INTO bookings_hotel (username, hotel_name, room_type, room_price, created_at, status)
        VALUES ('$user', '$hotel', '$room', '$price', '$date', 'active')";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>
