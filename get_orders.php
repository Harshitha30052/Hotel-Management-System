<?php
session_start();
header("Content-Type: application/json");
include 'db.php';

if(!isset($_SESSION['username'])){
    echo json_encode(['success'=>false,'error'=>'User not logged in']);
    exit;
}

$username = $_SESSION['username'];

$sql = "SELECT id, hotel_name, room_type, room_price, created_at, status FROM bookings_hotel WHERE username=? AND status='active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
$serverNow = time(); // server time

while($row = $result->fetch_assoc()){
    $createdTime = strtotime($row['created_at']);
    $row['cancel_allowed'] = ($serverNow - $createdTime) <= 900; // 15 minutes
    $orders[] = $row;
}

echo json_encode(['success'=>true,'orders'=>$orders]);
$conn->close();
?>
