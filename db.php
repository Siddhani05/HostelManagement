<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';  // Make sure your MySQL root user has no password (or use your password)
$dbname = 'hostel_management';
$port = '3307';  // Use the appropriate port, default is 3306 unless you're using XAMPP on a different port

// Create the database connection
$conn = mysqli_connect($host, $user, $password, $dbname, $port);

// Welcome message
$welcomeMessage = "Welcome to Hostel Management!";
echo "<h1>$welcomeMessage</h1>";

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>


