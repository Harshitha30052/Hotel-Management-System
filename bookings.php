<?php
session_start();
require 'db.php';  // Include the database connection

// Fetch the current username using the custom function
function getUsernameFromFile($filename = "users.txt") {
    if (!isset($_SESSION['username'])) {
        return "Guest"; 
    }

    $currentUser = $_SESSION['username'];

    if (file_exists($filename)) {
        $lines = file($filename);
        foreach ($lines as $line) {
            $parts = explode("||", trim($line));
          
            if (count($parts) >= 1 && (
              $parts[0] === $currentUser || 
              (isset($parts[1]) && $parts[1] === $currentUser)
          )) {
              return $parts[0];
          }
        }
    }

    return "Guest"; 
}

$username = getUsernameFromFile();  // Fetch the username

// Query the database for the user's bookings, including the username
$stmt = $conn->prepare("SELECT * FROM bookings WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Retrieve the session data for hotel_name, room_type, and room_price
$hotel_name = isset($_GET['hotelName']) ? $_GET['hotelName'] : 'Not selected';
$room_type = isset($_GET['roomType']) ? $_GET['roomType'] : 'Not selected';
$room_price = isset($_GET['roomPrice']) ? $_GET['roomPrice'] : 'Not available';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <style>
        /* Global Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

h1 {
    text-align: center;
    color: #333;
    padding: 20px;
    background-color: #4CAF50;
    color: white;
}

/* Table Styling */
table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

table thead {
    background-color: #4CAF50;
    color: white;
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    text-transform: uppercase;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tr:hover {
    background-color: #f1f1f1;
}

table td button {
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

table td button:hover {
    background-color: #45a049;
}

/* Extend Checkout Form Styling */
#extend-checkout-form {
    width: 300px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

#extend-checkout-form h3 {
    text-align: center;
    color: #333;
}

#extend-checkout-form input[type="date"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
}

#extend-checkout-form button {
    width: 48%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin: 5px 1%;
}

#extend-checkout-form button:hover {
    background-color: #45a049;
}

#extend-checkout-form button:nth-child(2) {
    background-color: #f44336;
}

#extend-checkout-form button:nth-child(2):hover {
    background-color: #e53935;
}

    </style>
</head>
<body>

    <h1>Your Bookings</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Hotel Name</th>
                    <th>Room Type</th>
                    <th>Room Price</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Guests</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['hotel_name'] ?? $hotel_name); ?></td>
                        <td><?php echo htmlspecialchars($row['room_type'] ?? $room_type); ?></td>
                        <td><?php echo htmlspecialchars($row['room_price'] ?? $room_price); ?></td>
                        <td><?php echo htmlspecialchars($row['check_in']); ?></td>
                        <td><?php echo htmlspecialchars($row['check_out']); ?></td>
                        <td><?php echo htmlspecialchars($row['guests']); ?></td>
                        <td>
                            <!-- Button to extend the checkout date -->
                            <button onclick="showExtendForm(<?php echo $row['id']; ?>, '<?php echo $row['check_out']; ?>')">Extend Checkout</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You don't have any bookings yet.</p>
    <?php endif; ?>

    <!-- Extend Checkout Form -->
    <div id="extend-checkout-form" style="display: none;">
        <h3>Extend Checkout Date</h3>
        <input type="date" id="extend-checkout-date">
        <button onclick="extendCheckout()">Submit</button>
        <button onclick="hideExtendForm()">Cancel</button>
    </div>

    <script>
        // Store the booking ID to extend checkout
        let bookingIdToExtend = null;

        // Show the extend form with the current checkout date
        function showExtendForm(bookingId, currentCheckout) {
            bookingIdToExtend = bookingId;
            document.getElementById('extend-checkout-form').style.display = 'block';
            document.getElementById('extend-checkout-date').value = currentCheckout;
        }

        // Hide the extend form
        function hideExtendForm() {
            document.getElementById('extend-checkout-form').style.display = 'none';
        }

        // Extend checkout date by sending the data to the server
        function extendCheckout() {
            const newCheckoutDate = document.getElementById('extend-checkout-date').value;
            if (!newCheckoutDate || !bookingIdToExtend) {
                alert('Missing information');
                return;
            }

            fetch('extend_checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'booking_id=' + encodeURIComponent(bookingIdToExtend) + '&new_checkout=' + encodeURIComponent(newCheckoutDate)
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    location.reload(); // Reload the page to reflect the updated checkout date
                }
            })
            .catch(err => {
                console.error(err);
                alert("Something went wrong.");
            });
        }
    </script>

</body>
</html>
