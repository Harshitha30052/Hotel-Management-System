<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email, new password, and confirm password from the form
    $email = $_POST['email'];
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match. Please try again.'); window.location.href='forget.html';</script>";
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Open the users.txt file for reading
    $file = fopen("users.txt", "r");
    if (!$file) {
        die("Error: Unable to open the file.");
    }

    $lines = file("users.txt");  
    $updated = false;  // Flag to track if the user's password is updated

    // Find and update the user's password based on email
    foreach ($lines as $key => $line) {
        list($storedUsername, $storedEmail, $storedPassword) = explode('||', trim($line)); // Split data by "||"

        // Check if the email matches
        if ($storedEmail === $email) {
            // Update the user's password with the new hashed password
            $lines[$key] = "$storedUsername||$storedEmail||$hashedPassword\n";
            $updated = true;
            break;
        }
    }

    if ($updated) {
        file_put_contents("users.txt", implode("", $lines));  // Rewrite the file with updated data
        echo "<script>alert('Password has been reset successfully.'); window.location.href='random.html';</script>";
    } else {
        echo "<script>alert('Email not found.'); window.location.href='forget.html';</script>";
    }

    fclose($file);  // Close the file
}
?>
