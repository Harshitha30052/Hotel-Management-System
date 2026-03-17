<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $username = $_POST['username'];
    $email = $_POST['email'];  // Capture the email
    $password = $_POST['password'];

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Store user data in the users.txt file (append mode)
    // Format: username||email||hashedPassword
    $userData = "$username||$email||$hashedPassword\n";

    // Open the users.txt file and append the data
    $file = fopen("users.txt", "a");
    if (!$file) {
        die("Error: Unable to open the file.");
    }

    // Write the user data to the file
    fwrite($file, $userData);

    // Close the file
    fclose($file);

    // Redirect to the login page or any confirmation page
    header("Location: random.html"); // Change to your actual login page URL
    exit();
} else {
    echo "Invalid Request.";
}
?>