<?php
session_start();
header("Content-Type: application/json");
include 'db.php';

if(!isset($_SESSION['username'])){
    echo json_encode(['success'=>false,'error'=>'Not logged in']);
    exit;
}
if(!isset($_POST['id'])){
    echo json_encode(['success'=>false,'error'=>'No booking ID']);
    exit;
}

$orderId = intval($_POST['id']);
$username = $_SESSION['username'];

// fetch booking
$stmt = $conn->prepare("SELECT created_at, status FROM bookings_hotel WHERE id=? AND username=?");
$stmt->bind_param("is",$orderId,$username);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows === 0){
    echo json_encode(['success'=>false,'error'=>'Booking not found']);
    exit;
}

$order = $res->fetch_assoc();

// check status
if($order['status'] !== 'active'){
    echo json_encode(['success'=>false,'error'=>'Booking cannot be cancelled']);
    exit;
}

// check 15 minutes server time
$createdTime = strtotime($order['created_at']);
if(time() - $createdTime > 900){
    echo json_encode(['success'=>false,'error'=>'Cancel not allowed. Time limit exceeded']);
    exit;
}

// delete booking
$stmt = $conn->prepare("DELETE FROM bookings_hotel WHERE id=? AND username=?");
$stmt->bind_param("is",$orderId,$username);

if($stmt->execute()){
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false,'error'=>$stmt->error]);
}

$stmt->close();
$conn->close();
?>
