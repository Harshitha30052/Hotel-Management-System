<?php
session_start();
require 'db.php';

// Admin check
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}

// DELETE booking
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    $conn->query("DELETE FROM bookings WHERE id = $deleteId");
    header("Location: admin_bookings.php");
    exit;
}

// SEARCH filters
$search = $_GET['search'] ?? '';
$searchQuery = "";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $searchQuery = "WHERE username LIKE '%$search%' OR check_in LIKE '%$search%' OR check_out LIKE '%$search%'";
}

// Export CSV
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="bookings.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Username', 'Check-in', 'Check-out', 'Guests', 'Created At']);

    $result = $conn->query("SELECT * FROM bookings $searchQuery ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Load bookings
$result = $conn->query("SELECT * FROM bookings $searchQuery ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - All Bookings</title>
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
            width: 200px;
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
            background-color: #333;
            color: white;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
            padding: 6px 12px;
            border: none;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>📋 All Hotel Bookings (Admin View)</h2>

<div class="top-bar">
    <form method="GET" action="admin_bookings.php">
        <input type="text" name="search" placeholder="Search by username/date" value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <a class="export" href="admin_bookings.php?export=csv<?= $search ? '&search=' . urlencode($search) : '' ?>">Export to CSV</a>
    </form>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Check-in</th>
        <th>Check-out</th>
        <th>Guests</th>
        <th>Booked On</th>
        <th>Action</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['username']); ?></td>
            <td><?= $row['check_in']; ?></td>
            <td><?= $row['check_out']; ?></td>
            <td><?= $row['guests']; ?></td>
            <td><?= $row['created_at']; ?></td>
            <td>
                <a href="admin_bookings.php?delete_id=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?')">
                    <button class="delete-btn">Delete</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">No bookings found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>