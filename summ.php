<?php
// Get username from users.txt
function getUsernameFromFile($filename = "users.txt") {
    if (file_exists($filename)) {
        $lines = file($filename);
        foreach ($lines as $line) {
            $parts = explode("||", trim($line));
            if (count($parts) >= 3) {
                return $parts[0];
            }
        }
    }
    return "Not selected";
}

$username = getUsernameFromFile();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking Summary</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 80%;
      margin: 0 auto;
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: #2e2e2e;
      margin-bottom: 30px;
    }
    .summary-header {
      background-color: #b93384;
      color: white;
      padding: 15px;
      margin-bottom: 20px;
      text-align: center;
    }
    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      padding: 15px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    td {
      background-color: #ffffff;
    }
    .total-row {
      font-weight: bold;
      background-color: #f9f9f9;
    }
    .total-price {
      color: green;
    }
    .btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #b5219a;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      text-align: center;
      margin-top: 20px;
      border: none;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #0056b3;
    }
    .note {
      font-style: italic;
      color: #777;
      margin-top: 20px;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Booking Summary</h1>

    <div class="summary-header">
      <h2>Your Reservation Details</h2>
      <p>Booking Reference: <span id="booking-reference">#123456789</span></p>
    </div>

    <table class="summary-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Check-in</th>
          <th>Check-out</th>
          <th>Hotel Name</th>
          <th>Room Type</th>
          <th>Room Price</th>
        </tr>
      </thead>
      <tbody id="reservation-table"></tbody>
    </table>

    <table class="summary-table">
      <tbody>
        <tr class="total-row">
          <td colspan="5" style="text-align:right;">Total Price:</td>
          <td id="total-price" class="total-price">Not Available</td>
        </tr>
      </tbody>
    </table>

    <button class="btn" id="confirm-button">Confirm Booking</button>
    <p class="note">Please ensure all details are correct before confirming the booking.</p>
  </div>

  <script>
    function addRowToTable(guestName, checkin, checkout, hotelName, roomType, roomPrice) {
      const tableBody = document.getElementById("reservation-table");
      const newRow = document.createElement("tr");

      const cells = [guestName, checkin, checkout, hotelName, roomType, `₹${parseFloat(roomPrice).toFixed(2)}`];
      cells.forEach(value => {
        const td = document.createElement("td");
        td.innerText = value || "Not selected";
        newRow.appendChild(td);
      });

      tableBody.appendChild(newRow);
    }

    function calculateTotal(roomPrice) {
      let price = parseFloat(roomPrice);
      if (isNaN(price)) price = 0;

      const checkin = new Date(localStorage.getItem('check-in'));
      const checkout = new Date(localStorage.getItem('check-out'));
      let days = 1;

      if (!isNaN(checkin) && !isNaN(checkout)) {
        days = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
        if (days < 1) days = 1;
      }

      const total = price * days;
      document.getElementById("total-price").innerText = `₹${total.toFixed(2)}`;

      return total;
    }

    function populateTable() {
      const guestName = "<?php echo $username; ?>";
      const checkin = localStorage.getItem("check-in");
      const checkout = localStorage.getItem("check-out");
      const hotelName = localStorage.getItem("name");
      const roomType = localStorage.getItem("room-type");
      const roomPrice = localStorage.getItem("room-price");

      addRowToTable(guestName, checkin, checkout, hotelName, roomType, roomPrice);
      calculateTotal(roomPrice);
    }

    function displayBookingReference() {
      const bookingReference = "#" + Math.floor(Math.random() * 1000000000);
      document.getElementById("booking-reference").innerText = bookingReference;
    }

    window.onload = function () {
      populateTable();
      displayBookingReference();
    }

    document.getElementById("confirm-button").addEventListener("click", function () {
      const data = {
        guest_name: "<?php echo $username; ?>",
        check_in: localStorage.getItem("check-in"),
        check_out: localStorage.getItem("check-out"),
        hotel_name: localStorage.getItem("name"),
        room_type: localStorage.getItem("room-type"),
        room_price: localStorage.getItem("room-price"),
        food_items: localStorage.getItem("food-items"),
        total_food_cost: localStorage.getItem("total-food-cost")
      };

      fetch("insert.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      })
      .then(res => res.text())
      .then(response => {
        alert("Booking Confirmed");
        window.location.href = "Food.html";
      })
      .catch(err => {
        console.error("Error:", err);
        alert("Error saving booking.");
      });
    });
  </script>

</body>
</html>
