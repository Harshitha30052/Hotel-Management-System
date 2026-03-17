// Simulated user database
const users = [];

// Registration form submission
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('regEmail').value;
    const password = document.getElementById('regPassword').value;
    const confirmPassword = document.getElementById('regConfirmPassword').value;
    const regMessage = document.getElementById('regMessage');

    // Validate registration
    if (password !== confirmPassword) {
        regMessage.textContent = "Passwords do not match!";
        return;
    }

    // Check if email already exists
    const userExists = users.some(user => user.email === email);
    if (userExists) {
        regMessage.textContent = "Email already in use!";
        return;
    }

    // Register user
    users.push({ email, password });
    regMessage.textContent = "Registration successful! You can now log in.";
    document.getElementById('registrationForm').reset();
});

// Login form submission
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const loginMessage = document.getElementById('loginMessage');

    // Validate login
    const user = users.find(user => user.email === email && user.password === password);
    if (user) {
        loginMessage.textContent = "Login successful! Welcome!";
        setTimeout(() => {
            window.location.href = "hotel.html"; // Redirect to home page
        }, 1000);
        
    } else {
        loginMessage.textContent = "Invalid email or password!";
    }
});
