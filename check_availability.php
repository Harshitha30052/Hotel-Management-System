<?php
require 'db.php';

$checkIn = $_POST['check_in'] ?? '';
$checkOut = $_POST['check_out'] ?? '';
$guests = $_POST['guests'] ?? '';

if (!$checkIn || !$checkOut || !$guests) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

if (strtotime($checkOut) <= strtotime($checkIn)) {
    echo json_encode(["status" => "error", "message" => "Check-out must be after Check-in."]);
    exit;
}

if ($guests > 5) {
    echo json_encode(["status" => "error", "message" => "Maximum 5 guests allowed."]);
    exit;
}

// Overlap check: if ANY booking overlaps
$sql = "SELECT * FROM bookings 
        WHERE check_in <= ? AND check_out >= ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $checkOut, $checkIn);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "The selected dates are not available."]);
} else {
    echo json_encode(["status" => "success", "message" => "Dates are available."]);
}
?>
