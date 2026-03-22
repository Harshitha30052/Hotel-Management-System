function togglebutton(button) {
    // Get all hotel selection buttons
    let allButtons = document.querySelectorAll('.hotel_b');

    // Reset all buttons except the clicked one
    allButtons.forEach(function (btn) {
        if (btn !== button) {
            btn.innerText = "Select Hotel";  // Reset button text
            btn.style.backgroundColor = ""; // Reset background color
            btn.style.color = "";           // Reset text color
            btn.style.border = "";          // Reset border style
        }
    });

    // Get the hotel name (it's inside the <h3> tag with class "selhot" for each hotel)
    let hotelName = button.closest('.galyy').querySelector('.selhot').innerText.trim();
    if (hotelName.includes('❤') || hotelName.includes('🤍')) {
        hotelName = hotelName.split('❤')[0].trim(); // or split by heart emoji used
      }

    let isSelecting = button.innerText === "Select Hotel"; // Check if the button is in "Select" state

    if (isSelecting && hotelName) {
        // Change button to "Selected" state
        button.innerText = "Selected";
        button.style.backgroundColor = "green";  // Highlight selected button
        button.style.color = "white";            // Change text color
        button.style.border = "2px solid grey";  // Add border to selected button

        // Store hotel name in localStorage
        localStorage.setItem("name", hotelName);

        // After 500ms, redirect to Rooms.html
        setTimeout(function () {
            window.location.href = "../html/Rooms.html?hotel=" + encodeURIComponent(hotelName);
        }, 500);
    } else {
        // Reset the button to "Select Hotel" if it was already selected
        button.innerText = "Select Hotel";
        button.style.backgroundColor = "";  // Reset background color
        button.style.color = "";            // Reset text color
        button.style.border = "";           // Reset border style
    }
}
