<?php
require 'db.php';  // Include the database connection

// Check if the booking ID and new checkout date are provided
$bookingId = $_POST['booking_id'] ?? '';
$newCheckoutDate = $_POST['new_checkout'] ?? '';

if (empty($bookingId) || empty($newCheckoutDate)) {
    echo json_encode(["status" => "error", "message" => "Missing information."]);
    exit;
}

// Update the checkout date in the database
$stmt = $conn->prepare("UPDATE bookings SET check_out = ? WHERE id = ?");
$stmt->bind_param("si", $newCheckoutDate, $bookingId);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Checkout date extended successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to extend checkout date."]);
}
?>