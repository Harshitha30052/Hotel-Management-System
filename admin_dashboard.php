<?php
session_start();

// Admin check
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: admin.php"); // Redirect if not admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url("login1.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            text-align: center;
            padding: 60px;
        }
        h1 {
            color: #333;
            margin-bottom: 40px;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        .dashboard-btn {
            padding: 20px 40px;
            font-size: 18px;
            border: none;
            background-color:white;
            color: black;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .dashboard-btn:hover {
            background-color: #0056b3;
        }
        .logout-link {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 16px;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

<a href="admin.php" class="logout-link">Logout</a>

<h1>Welcome, Admin..</h1>

<div class="button-container">
    <a href="admin_bookings.php" class="dashboard-btn">Manage Manual Bookings</a>
    <a href="admin_view_bookings.php" class="dashboard-btn">View Hotel Room Bookings</a>
</div>

</body>
</html>
