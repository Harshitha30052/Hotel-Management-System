<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["booking_id"];

$conn = mysqli_connect("localhost", "root", "Harshi@0987", "hotel_booking");

$sql = "DELETE FROM bookings WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
