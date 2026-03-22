<?php
session_start();

// Function to get the username from the session or a default "Guest"
function getUsernameFromFile($filename = "../users.txt") {
    if (!isset($_SESSION['username'])) {
        return "Guest"; // Default username if no session is found
    }

    $currentUser = $_SESSION['username'];

    if (file_exists($filename)) {
        $lines = file($filename);
        foreach ($lines as $line) {
            $parts = explode("||", trim($line));
            // if (count($parts) >= 1 && ($parts[0] === $currentUser || $parts[1] === $currentUser)) {
            //     return $parts[0]; // Return the actual username from the file
            // }
            if (count($parts) >= 1 && (
              $parts[0] === $currentUser || 
              (isset($parts[1]) && $parts[1] === $currentUser)
          )) {
              return $parts[0];
          }
          
        }
    }

    return "Guest"; // Fallback if username is not found in the file
}

$username = getUsernameFromFile();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hotel Website</title>
  <link rel="stylesheet" href="../css/hotel.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    .favorite-icon {
      cursor: pointer;
      margin-left: 30px;
      font-size: 1.2em;
      color: white;
      transition: color 0.3s;
    }

    .favorite-icon.filled {
      color: red;
    }
    .favorite-icon.fa-solid {
       color: red; /* Red color for filled heart */
     }
      .profile_pic {
    position: relative;
    width: 45px;
    height: 45px;
    cursor: pointer;
}
.profile_pic img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid white;
    object-fit: cover;
}
.profile_dropdown {
    display: none;
    position: absolute;
    top: 55px;
    right: 0;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    border-radius: 5px;
    overflow: hidden;
    z-index: 2000;
    min-width: 140px;
}
.profile_dropdown a {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
}
.profile_dropdown a:hover {
    background-color: #ec407a;
    color: white;
}

    /* .hotel_b {
      cursor: pointer;
      padding: 10px;
      background-color: #f0f0f0;
      border: 1px solid #ccc;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .hotel_b.selected {
      background-color: green;
      color: white;
      border: 2px solid grey;
    } */
  </style>
</head>
<body>

  <div class="nav_bar">
    <div class="logo"></div>
    <div class="nav_menu">
      <ul>
        <li><a href="../html/hotel.html" id="a">Home</a></li>
        <li><a href="../html/hotel.html#footer" id="a">About Us</a></li>
        <li><a href="../html/hotel.html#footer" id="a">Contact Us</a></li>
        <li><a href="../html/random.html" id="a">Logout</a></li>
        <li><a href="Hotels.php" id="a">Packages</a></li>
        <li><a href="../html/Food.html" id="a">Meals</a></li>
        <li><a href="favorites.php" id="a">My Favorites</a></li>
      </ul>
    </div>
    <div class="nav_but">
      <button class="batton">Book Now</button>
    </div>
    <div class="profile_pic">
        <img src="../images/profile.jpg" alt="Profile" id="profileToggle">
        <div class="profile_dropdown" id="profileDropdown">
            <a href="my_orders.php">My Orders</a>
            <a href="logout.php">Logout</a>
        </div>
      </div>
  </div>

  <br /><br /><br />

  <div class="sel_hotel">
    <div class="fav_head">
      <div class="fav_head_hotel">
        <h1>Select Hotel</h1>
        <h3>Check Out now our Best Hotels</h3>
      </div>
    </div>

    <br />

    <div class="gal_hotel">
      <!-- Hotel 1 -->
      <div class="gal_res1 galq">
        <div class="galyy">
          <div class="box_gal1"></div>
          <div class="box1_m box1_ma">
            <h3 class="selhot">
              <span class="hotel-name">The Park Hyderabad</span>
              <i class="fa-regular fa-heart favorite-icon" onclick="toggleFavorite(this, 'The Park Hyderabad')"></i>
            </h3>
            <button class="hotel_b" onclick="togglebutton(this)">Select Hotel</button>
          </div>
        </div>
      </div>

      <!-- Hotel 2 -->
      <div class="gal_res2 galq">
        <div class="galyy">
          <div class="box_gal2"></div>
          <div class="box2_m box1_ma">
            <h3 class="selhot">
              <span class="hotel-name">Taj Hotel</span>
              <i class="fa-regular fa-heart favorite-icon" onclick="toggleFavorite(this, 'Taj Hotel')"></i>
            </h3>
            <button class="hotel_b" onclick="togglebutton(this)">Select Hotel</button>
          </div>
        </div>
      </div>

      <!-- Hotel 3 -->
      <div class="gal_res3 galq">
        <div class="galyy">
          <div class="box_gal3"></div>
          <div class="box3_m box1_ma">
            <h3 class="selhot">
                <span class="hotel-name">Taj Krishna</span>
              <i class="fa-regular fa-heart favorite-icon" onclick="toggleFavorite(this, 'Taj Krishna')"></i>
            </h3>
            <button class="hotel_b" onclick="togglebutton(this)">Select Hotel</button>
          </div>
        </div>
      </div>

      <!-- Hotel 4 -->
      <div class="gal_res4 galq">
        <div class="galyy">
          <div class="box_gal4"></div>
          <div class="box4_m box1_ma">
            <h3 class="selhot">
                <span class="hotel-name">Taj Deccan</span>
              <i class="fa-regular fa-heart favorite-icon" onclick="toggleFavorite(this, 'Taj Deccan')"></i>
            </h3>
            <button class="hotel_b" onclick="togglebutton(this)">Select Hotel</button>
          </div>
        </div>
      </div>
    </div>

    <div class="gal_hotel">
      <!-- Hotel 5 -->
      <div class="gal_res5 galq">
        <div class="galyy">
          <div class="box_gal5"></div>
          <div class="box5_m box1_ma">
            <h3 class="selhot">
                <span class="hotel-name">Lemon Tree</span>
              <i class="fa-regular fa-heart favorite-icon" onclick="toggleFavorite(this, 'Lemon Tree')"></i>
            </h3>
            <button class="hotel_b" onclick="togglebutton(this)">Select Hotel</button>
          </div>
        </div>
      </div>

      <!-- Hotel 6 -->
      <div class="gal_res6 galq">
        <div class="galyy">
          <div class="box_gal6"></div>
          <div class="box6_m box1_ma">
            <h3 class="selhot">
                <span class="hotel-name">Golconda Hotel</span>
              <i class="fa-regular fa-heart favorite-icon" onclick="toggleFavorite(this, 'Golconda Hotel')"></i>
            </h3>
            <button class="hotel_b" onclick="togglebutton(this)">Select Hotel</button>
          </div>
        </div>
      </div>

      <!-- Hotel 7 -->
      <div class="gal_res7 galq">
        <div class="galyy">
          <div class="box_gal7"></div>
          <div class="box7_m box1_ma">
            <h3 class="selhot">
                <span class="hotel-name">Trident Hotel</span>
              <i class="fa-regular fa-heart favorite-icon" onclick="toggleFavorite(this, 'Trident Hotel')"></i>
            </h3>
            <button class="hotel_b" onclick="togglebutton(this)">Select Hotel</button>
          </div>
        </div>
      </div>

      <!-- Hotel 8 -->
      <div class="gal_res8 galq">
        <div class="galyy">
          <div class="box_gal8"></div>
          <div class="box8_m box1_ma">
            <h3 class="selhot">
                <span class="hotel-name">Ibis Hotel</span>
              <i class="fa-regular fa-heart favorite-icon" onclick="toggleFavorite(this, 'Ibis Hotel')"></i>
            </h3>
            <button class="hotel_b" onclick="togglebutton(this)">Select Hotel</button>
          </div>
        </div>
      </div>


      <!-- More Hotels... -->
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    const profileToggle = document.getElementById("profileToggle");
const profileDropdown = document.getElementById("profileDropdown");

profileToggle.addEventListener("click", () => {
    profileDropdown.style.display = profileDropdown.style.display === "block" ? "none" : "block";
});

window.addEventListener("click", (e) => {
    if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.style.display = "none";
    }
});
 function toggleFavorite(icon, hotelName) {
  const username = "<?php echo $username; ?>"; // from PHP session
  let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

  const isFavorited = icon.classList.contains("fa-solid");

  if (!isFavorited) {
    // Add to favorites
    favorites.push({ username, hotel: hotelName });
    icon.classList.remove("fa-regular");
    icon.classList.add("fa-solid", "filled");
  } else {
    // Remove from favorites
    favorites = favorites.filter(
      fav => !(fav.username === username && fav.hotel === hotelName)
    );
    icon.classList.remove("fa-solid", "filled");
    icon.classList.add("fa-regular");
  }

  localStorage.setItem('favorites', JSON.stringify(favorites));
}

document.addEventListener("DOMContentLoaded", function () {
      const favorites = JSON.parse(localStorage.getItem("favorites")) || [];

      document.querySelectorAll(".favorite-icon").forEach(icon => {
        const hotelName = icon.getAttribute("data-hotel");

        const isFavorited = favorites.some(fav =>
          fav.username === username && fav.hotel === hotelName
        );

        if (isFavorited) {
          icon.classList.remove("fa-regular");
          icon.classList.add("fa-solid", "filled");
        } else {
          icon.classList.remove("fa-solid", "filled");
          icon.classList.add("fa-regular");
        }

        // Attach click handler
        icon.addEventListener("click", function () {
          toggleFavorite(this, hotelName);
        });
      });
    });

    // Function to toggle the hotel selection button
    function togglebutton(button) {
  const allButtons = document.querySelectorAll('.hotel_b');

  const isAlreadySelected = button.innerText === "Selected";
  const hotelName = button.closest('.galyy').querySelector('.selhot .hotel-name').innerText.trim();

  if (isAlreadySelected) {
    // If this was already selected, unselect everything
    allButtons.forEach(btn => {
      btn.innerText = "Select Hotel";
      btn.classList.remove("selected");
      btn.disabled = false;
      btn.style.opacity = 1;
      btn.style.cursor = "pointer";
      btn.style.backgroundColor = "rgb(55, 9, 104)";
    });

    localStorage.removeItem("name"); // Clear selection from storage
  } else {
    // First, reset all buttons
    allButtons.forEach(btn => {
      btn.innerText = "Select Hotel";
      btn.classList.remove("selected");
      btn.disabled = false;
      btn.style.opacity = 1;
      btn.style.cursor = "pointer";
      btn.style.backgroundColor = "rgb(55, 9, 104)";
    });

    // Then mark the clicked one as selected
    button.innerText = "Selected";
    button.classList.add("selected");
    button.style.backgroundColor = "green";
    localStorage.setItem("name", hotelName);

    // Disable all other buttons
    allButtons.forEach(btn => {
      if (btn !== button) {
        btn.disabled = true;
        btn.style.opacity = 0.6;
        btn.style.cursor = "not-allowed";
      }
    });

    // Optional: redirect after delay
    setTimeout(() => {
      window.location.href = "../html/Rooms.html?hotel=" + encodeURIComponent(hotelName);
    }, 500);
  }
}

  </script>
</body>
</html>
