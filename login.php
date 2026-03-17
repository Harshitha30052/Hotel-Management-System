<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['usenam'];  // This can be either a username or email now
    $pass = $_POST['pass'];

    $file = fopen("users.txt", "r");

    if (!$file) {
        die("Error: Unable to open the file.");
    }

    $userFound = false;
    $passwordMatch = false;

    // Iterate through each line in the file
    while (($line = fgets($file)) !== false) {
        list($storedUsername, $storedEmail, $storedHashedPassword) = explode('||', trim($line));

        // Check if the input matches either the stored username or email
        if ($user === $storedUsername || $user === $storedEmail) {
            $userFound = true;

            // Verify the password with the stored hashed password
            if (password_verify($pass, $storedHashedPassword)) {
                $passwordMatch = true;
                break;
            }
        }
    }

    fclose($file);

    // If both username/email and password are correct
    if ($userFound && $passwordMatch) {
        $_SESSION['username'] = $user;
        echo "<script>localStorage.setItem('Name', '$user');</script>";
        header("Location: hotel.html");
        exit();
    } else {
        echo "<script>alert('Invalid username/email or password.');</script>";
    }
}
?>
