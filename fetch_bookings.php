<?php
$conn = new mysqli("localhost", "root", "", "hotel_db");

$result = $conn->query("SELECT * FROM bookings");

if ($result->num_rows == 0) {
    echo "<p>No bookings found.</p>";
    exit;
}

echo "<table>
<tr>
    <th>Customer Name</th>
    <th>Hotel Name</th>
    <th>Check-in</th>
    <th>Check-out</th>
    <th>Action</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['customer_name']}</td>
        <td>{$row['hotel_name']}</td>
        <td>{$row['check_in']}</td>
        <td>{$row['check_out']}</td>
        <td>
            <button class='btn-cancel' onclick='cancelBooking({$row['id']})'>
                Cancel
            </button>
        </td>
    </tr>";
}

echo "</table>";

$conn->close();
?>
