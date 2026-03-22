<?php
session_start();

function getUsernameFromFile($filename = "../users.txt") {
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

$username = getUsernameFromFile();
$roomType = isset($_GET['roomType']) ? $_GET['roomType'] : 'Not selected';
$roomPrice = isset($_GET['roomPrice']) ? $_GET['roomPrice'] : 'Not available';
$hotelName = isset($_GET['hotelName']) ? $_GET['hotelName'] : 'Not selected';
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
      background-color:white;
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
      color: #b33b80; /* Dark Pink */
      margin-bottom: 30px;
    }
    .summary-header {
      background-color: #ec407a; 
      color: white;
      padding: 15px;
      margin-bottom: 20px;
      text-align: center;
      border-radius: 8px;
    }
    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      padding: 15px;
      border: 1px solid #f48fb1; 
      text-align: left;
    }
    th {
      background-color: #f8bbd0; 
      color: #b33b80;
    }
    td {
      background-color: #ffffff;
    }
    .total-row {
      font-weight: bold;
      background-color: #f9f9f9;
    }
    .total-price {
      color: #b33b80; /* Dark Pink for Price */
    }
    .btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #ec407a; /* Vibrant Pink */
      color: white;
      text-decoration: none;
      border-radius: 5px;
      text-align: center;
      margin-top: 20px;
      border: none;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #d81b60; 
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
      <tbody id="reservation-table">

      </tbody>
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
    <button class="btn" id="download-button" style="display:none;">Download Receipt</button>

    <p class="note">Please ensure all details are correct before confirming the booking.</p>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <script>
    let bookingData = {};

    function addRowToTable(guestName, checkin, checkout, hotelName, roomType, roomPrice) {
      const tableBody = document.getElementById("reservation-table");
      const newRow = document.createElement("tr");

      const cells = [guestName, checkin, checkout, hotelName, roomType, `₹${parseFloat(roomPrice).toFixed(2)}`];
      [bookingData.name, bookingData.checkin, bookingData.checkout, bookingData.hotel, bookingData.roomType, bookingData.roomPrice] = cells;

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
      bookingData.total = `₹${total.toFixed(2)}`;

      return total;
    }

    function populateTable() {
      const guestName = "<?php echo $username; ?>";
      const checkin = localStorage.getItem("check-in");
      const checkout = localStorage.getItem("check-out");
      const hotelName = "<?php echo $hotelName; ?>";
      const roomType = "<?php echo $roomType; ?>";
      const roomPrice = "<?php echo $roomPrice; ?>";

      addRowToTable(guestName, checkin, checkout, hotelName, roomType, roomPrice);
      calculateTotal(roomPrice);
    }

    function displayBookingReference() {
      const bookingReference = "#" + Math.floor(Math.random() * 1000000000);
      document.getElementById("booking-reference").innerText = bookingReference;
      bookingData.reference = bookingReference;
    }

    function generatePDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();

      doc.setFontSize(18);
      doc.text("Hotel Booking Receipt", 20, 20);

      doc.setFontSize(12);
      const details = [
        ["Booking Reference:", bookingData.reference],
        ["Guest Name:", bookingData.name],
        ["Check-in:", bookingData.checkin],
        ["Check-out:", bookingData.checkout],
        ["Hotel Name:", bookingData.hotel],
        ["Room Type:", bookingData.roomType],
        ["Room Price per Night:", bookingData.roomPrice],
        ["Total Price:", bookingData.total]
      ];

      const tableStartY = 40;
      const rowHeight = 10;
      const tableWidth = 180;

      doc.rect(20, tableStartY, tableWidth, rowHeight);
      doc.text("Description", 25, tableStartY + 6);
      doc.text("Details", 120, tableStartY + 6);

      let y = tableStartY + rowHeight;

      details.forEach(row => {
        doc.rect(20, y, tableWidth, rowHeight);
        doc.text(row[0], 25, y + 6);
        doc.text(row[1], 120, y + 6);
        y += rowHeight;
      });

      doc.save("Booking_Summary.pdf");
    }

    window.onload = function () {
      populateTable();
      displayBookingReference();
    };

    // document.getElementById("confirm-button").addEventListener("click", function () {
    //   const confirmBooking = confirm("Do you want to confirm the booking?");
    //   if (confirmBooking) {
    //     alert("Booking is Confirmed");
    //     document.getElementById("confirm-button").innerText = "Booking Confirmed";
    //     document.getElementById("confirm-button").disabled = true;
    //     document.getElementById("download-button").style.display = "inline-block";
    //   } else {
    //     alert("Booking Cancelled");
    //   }
    // });

    // document.getElementById("download-button").addEventListener("click", function () {
    //   generatePDF();
      
    
    //     setTimeout(()=>{
    //     window.location.href="hotel.html"
    //   },2000);
    // });
    document.getElementById("confirm-button").addEventListener("click", function () {
  const confirmBooking = confirm("Do you want to confirm the booking?");
  if (confirmBooking) {
    alert("Booking is Confirmed");

    // Sending data to the server (store the booking in the database)
    const bookingData = {
      hotelName: "<?php echo $hotelName; ?>",
      roomType: "<?php echo $roomType; ?>",
      roomPrice: "<?php echo $roomPrice; ?>",
      username: "<?php echo $username; ?>"
    };

    fetch("store_booking.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(bookingData)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        console.log("Booking saved successfully!");
      } else {
        console.error("Error saving booking:", data.error);
      }
    })
    .catch(err => {
      console.error("Error:", err);
    });

    document.getElementById("confirm-button").innerText = "Booking Confirmed";
    document.getElementById("confirm-button").disabled = true;
    document.getElementById("download-button").style.display = "inline-block";
  } else {
    alert("Booking Cancelled");
  }
});

document.getElementById("download-button").addEventListener("click", function () {
  generatePDF();
  
 
  setTimeout(() => {
    window.location.href = "../html/hotel.html";
  }, 2000);
});

  </script>

</body>
</html>
