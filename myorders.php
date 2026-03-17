<?php
$conn = mysqli_connect("localhost", "root", "Harshi@0987", "hotel_booking");

$result = mysqli_query($conn, "SELECT * FROM bookings");

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

echo json_encode($orders);
?>
