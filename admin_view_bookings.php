<?php
session_start();
require 'db.php';

// Admin check
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}

// SEARCH filters
$search = $_GET['search'] ?? '';
$searchQuery = "";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $searchQuery = "WHERE hotel_name LIKE '%$search%' OR room_type LIKE '%$search%' OR username LIKE '%$search%'";
}

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="hotel_bookings.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Hotel Name', 'Room Type', 'Room Price', 'Username', 'Created At']);

    $result = $conn->query("SELECT * FROM bookings_hotel $searchQuery ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Load bookings
$result = $conn->query("SELECT * FROM bookings_hotel $searchQuery ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Hotel Room Bookings</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        .top-bar {
            width: 90%;
            margin: auto;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .top-bar form {
            display: flex;
        }
        .top-bar input[type="text"] {
            padding: 8px;
            width: 250px;
        }
        .top-bar button, .export {
            padding: 8px 15px;
            margin-left: 10px;
            cursor: pointer;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: auto;
            background: white;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

<h2>🏨 Hotel Room Bookings (User Selections)</h2>

<div class="top-bar">
    <form method="GET" action="admin_view_bookings.php">
        <input type="text" name="search" placeholder="Search by hotel, room, username" value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <a class="export" href="admin_view_bookings.php?export=csv<?= $search ? '&search=' . urlencode($search) : '' ?>">Export to CSV</a>
    </form>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Hotel Name</th>
        <th>Room Type</th>
        <th>Room Price</th>
        <th>Username</th>
        <th>Booked On</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['hotel_name']); ?></td>
            <td><?= htmlspecialchars($row['room_type']); ?></td>
            <td><?= htmlspecialchars($row['room_price']); ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= $row['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">No hotel room bookings found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
