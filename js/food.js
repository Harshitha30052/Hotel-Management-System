document.addEventListener("DOMContentLoaded", function() {
    // Show quantity controls when "Add to Cart" is clicked
    const addToCartButtons = document.querySelectorAll(".add-to-cart");

    addToCartButtons.forEach(button => {
        button.addEventListener("click", function() {
            const item = button.getAttribute("data-item");
            const quantityControls = document.getElementById(item.toLowerCase().replace(/ /g, '-') + '-controls');
            quantityControls.style.display = "block"; // Show increment and decrement buttons
            button.style.display = "none"; // Hide "Add to Cart" button
        });
    });

    // Increment functionality
    const incrementButtons = document.querySelectorAll(".increment");
    incrementButtons.forEach(button => {
        button.addEventListener("click", function() {
            const item = button.getAttribute("data-item");
            const quantitySpan = document.getElementById(item.toLowerCase().replace(/ /g, '-') + '-quantity');
            let quantity = parseInt(quantitySpan.textContent);
            quantity++;
            quantitySpan.textContent = quantity;
        });
    });

    // Decrement functionality
    const decrementButtons = document.querySelectorAll(".decrement");
    decrementButtons.forEach(button => {
        button.addEventListener("click", function() {
            const item = button.getAttribute("data-item");
            const quantitySpan = document.getElementById(item.toLowerCase().replace(/ /g, '-') + '-quantity');
            let quantity = parseInt(quantitySpan.textContent);
            if (quantity > 0) {
                quantity--;
            }

            // Update the quantity display
            quantitySpan.textContent = quantity;

            // If quantity is 0, hide the quantity controls and show "Add to Cart" button
            if (quantity === 0) {
                const quantityControls = document.getElementById(item.toLowerCase().replace(/ /g, '-') + '-controls');
                const addToCartButton = document.querySelector(`.add-to-cart[data-item="${item}"]`);
                quantityControls.style.display = "none"; // Hide increment and decrement buttons
                addToCartButton.style.display = "block"; // Show "Add to Cart" button
            }
        });
    });
});