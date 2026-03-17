<?php
session_start();

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

$username = getUsernameFromFile();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Food Order Summary</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: white;
    }
    .container {
      width: 85%;
      margin: auto;
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: rgb(47, 184, 211);
    }
    .summary-header {
      background-color: #cce5ff;
      color: #004080;
      padding: 15px;
      border-radius: 8px;
      text-align: center;
    }
    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #b3d9ff;
      text-align: left;
    }
    th {
      background-color: #cce5ff;
      color: #004080;
    }
    .total-row {
      font-weight: bold;
      background-color: #e6f2ff;
    }
    .btn {
      background-color: rgb(89, 191, 209);
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 20px;
    }
    .btn:hover {
      background-color: rgb(87, 111, 197);
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
    <h1>Food Order Summary</h1>

    <div class="summary-header">
      <h2> <span id="hotel-name">Loading...</span></h2>
      <h3>Customer: <?php echo htmlspecialchars($username); ?></h3>
      <p>Order ID: <span id="order-id">#ORDER123</span></p>
    </div>

    <table class="summary-table" id="food-table">
      <thead>
        <tr>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Total Price</th>
        </tr>
      </thead>
      <tbody>
        <!-- Items will be inserted by JS -->
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="2" style="text-align:right;">Total:</td>
          <td id="total-cost">₹0.00</td>
        </tr>
      </tfoot>
    </table>

    <button class="btn" id="confirm-order">Confirm Order</button>
    <button class="btn" id="download-receipt" style="display:none;">Download Receipt</button>

    <p class="note">Please check your order carefully before confirming.</p>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script>
  let orderData = {
    items: [],
    total: 0
  };

  function generateOrderID() {
    return "#" + Math.floor(Math.random() * 1000000000);
  }

  function loadFoodData() {
    const foodCart = JSON.parse(localStorage.getItem('foodCart')) || {};
    const tableBody = document.querySelector("#food-table tbody");
    let total = 0;

    for (const itemName in foodCart) {
      const foodItem = foodCart[itemName];
      const qty = foodItem.quantity;
      const itemTotal = foodItem.total;

      total += itemTotal;

      orderData.items.push({
        name: itemName,
        qty: qty,
        totalPrice: itemTotal
      });

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${itemName}</td>
        <td>${qty}</td>
        <td>₹${itemTotal.toFixed(2)}</td>
      `;
      tableBody.appendChild(tr);
    }

    document.getElementById("total-cost").innerText = `₹${total.toFixed(2)}`;
    orderData.total = total;
  }

  function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(18);
    doc.text("Food Order Receipt", 20, 20);

    doc.setFontSize(12);
    const orderId = document.getElementById("order-id").innerText;
    const hotelName = localStorage.getItem("Naame") || "Not selected";
    document.getElementById("hotel-name").innerText = hotelName;

    doc.text(`Order ID: ${orderId}`, 20, 30);
    doc.text(`Hotel: ${hotelName}`, 20, 38);
    doc.text("Customer: <?php echo htmlspecialchars($username); ?>", 20, 46);

    let y = 60;
    doc.text("Items:", 20, y);
    y += 10;

    orderData.items.forEach(item => {
      doc.text(`${item.name} - Qty: ${item.qty} = ₹${item.totalPrice.toFixed(2)}`, 20, y);
      y += 8;
    });

    y += 8;
    doc.text(`Total: ₹${orderData.total.toFixed(2)}`, 20, y);

    doc.save("Food_Receipt.pdf");
  }

  window.onload = () => {
    const orderId = generateOrderID();
    document.getElementById("order-id").innerText = orderId;
    loadFoodData();

    // Load the hotel name from localStorage and update the page
    const hotelName = localStorage.getItem("Naame");
    if (hotelName) {
      document.getElementById("hotel-name").innerText = hotelName;
    }
  };

  document.getElementById("confirm-order").addEventListener("click", () => {
    if (confirm("Do you want to confirm this order?")) {
      alert("Order Confirmed!");
      document.getElementById("confirm-order").disabled = true;
      document.getElementById("confirm-order").innerText = "Order Confirmed";
      document.getElementById("download-receipt").style.display = "inline-block";
    }
  });

  document.getElementById("download-receipt").addEventListener("click", () => {
    generatePDF();
    setTimeout(() => {
      window.location.href = "hotel.html";
    }, 2000);
  });
  </script>
</body>
</html>
