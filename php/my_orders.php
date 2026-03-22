<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../html/hotel.html"); // redirect if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Orders</title>
<link rel="stylesheet" href="../css/hotel.css">
<link rel="stylesheet" href="../css/hotel1.css">
<style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
    header { background: #ec407a; color: white; padding: 15px; text-align: center; }
    .orders-container { max-width: 800px; margin: 40px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .order-box { border: 1px solid #ccc; border-radius: 8px; padding: 15px; margin-bottom: 15px; background: #fff; position: relative; }
    .order-box h3 { margin: 0 0 8px 0; color: #ec407a; }
    .order-box p { margin: 5px 0; }
    .cancel-btn { position: absolute; top: 15px; right: 15px; background: red; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; }
    .cancel-btn:disabled { background: gray; cursor: not-allowed; }
    .no-orders { text-align: center; padding: 30px; color: #777; }
    .back-btn { display: inline-block; margin-bottom: 20px; padding: 8px 16px; background: #ec407a; color: white; text-decoration: none; border-radius: 5px; }
    .back-btn:hover { background: #d32f2f; }
</style>
</head>
<body>
<header>
    <h1>My Orders</h1>
</header>

<div class="orders-container">
    <a href="../html/hotel.html" class="back-btn">← Back to Home</a>
    <div id="ordersList">
        <p class="no-orders">Loading orders...</p>
    </div>
</div>

<script>
function loadOrders() {
    const ordersList = document.getElementById("ordersList");
    ordersList.innerHTML = "<p class='no-orders'>Loading orders...</p>";

    fetch("get_orders.php")
    .then(res => res.json())
    .then(data => {
        if(!data.success || !data.orders || data.orders.length === 0){
            ordersList.innerHTML = "<p class='no-orders'>No orders found.</p>";
            return;
        }

        ordersList.innerHTML = ""; // clear

        data.orders.forEach(order => {
            const div = document.createElement("div");
            div.classList.add("order-box");

            const displayTime = new Date(order.created_at).toLocaleString('en-IN', {hour12:true});

            let cancelBtnHTML = "";
            if(order.cancel_allowed){
                cancelBtnHTML = `<button id="btn_${order.id}" class="cancel-btn" onclick="cancelOrder(${order.id})">Cancel</button>`;
            } else {
                cancelBtnHTML = `<span style="color:red;">Expired</span>`;
            }

            div.innerHTML = `
                <h3>${order.hotel_name}</h3>
                <p><b>Room:</b> ${order.room_type}</p>
                <p><b>Price:</b> ₹${order.room_price}</p>
                <p><b>Date:</b> ${displayTime}</p>
                <p><b>Status:</b> ${order.status}</p>
                ${cancelBtnHTML}
            `;

            ordersList.appendChild(div);

            // Start countdown if cancel_allowed
            if(order.cancel_allowed && order.expiry_time){
                startCountdownOnButton(order.id, order.expiry_time);
            }
        });
    })
    .catch(err => {
        ordersList.innerHTML = "<p class='no-orders'>Error loading orders.</p>";
        console.error(err);
    });
}

function cancelOrder(orderId) {
    if(!confirm("Are you sure you want to cancel this booking?")) return;

    fetch("cancel_order.php", {
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: `id=${orderId}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert("Booking cancelled successfully");
        } else {
            alert(data.error || "Failed to cancel booking");
        }
        loadOrders(); // refresh orders list
    })
    .catch(err => {
        alert("Failed to cancel booking.");
        console.error(err);
    });
}

// Countdown timer for cancel button
function startCountdownOnButton(orderId, expiryTimestamp){
    const btn = document.getElementById(`btn_${orderId}`);

    const interval = setInterval(() => {
        const now = new Date().getTime();
        let distance = expiryTimestamp - now;

        if(distance > 0){
            const minutes = Math.floor(distance / (1000*60));
            const seconds = Math.floor((distance % (1000*60)) / 1000);
            btn.textContent = `Cancel (${minutes}:${seconds < 10 ? '0' : ''}${seconds})`;
        } else {
            clearInterval(interval);
            btn.textContent = "Expired";
            btn.disabled = true;
        }
    }, 1000);
}

// Load orders on page load
window.onload = loadOrders;
</script>
</body>
</html>
