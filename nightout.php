<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'hostel_management';
$port = '3307'; // Use the appropriate port, default is 3306 unless you're using XAMPP on a different port

$conn = mysqli_connect($host, $user, $password, $dbname, $port);

// Welcome message
$welcomeMessage = "Welcome to Hostel Management!";
echo "<h1>$welcomeMessage</h1>";

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Retrieve night-out details from the form
    $s_id = $_POST['s_id']; // Student ID
    $reason = $_POST['reason'];
    $place = $_POST['place'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $status = $_POST['status'];
    
    // Insert night-out data into the database
    $sql_nightout = "INSERT INTO nightout (s_id, reason, place, from_date, to_date, status, request_date)
                     VALUES ('$s_id', '$reason', '$place', '$from_date', '$to_date', '$status', NOW())";
    
    if (mysqli_query($conn, $sql_nightout)) {
        echo "Night-out details successfully added.<br>";
    } else {
        echo "Error: " . mysqli_error($conn) . "<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Night-out Request</title>
</head>
<body>
    <h2>Night-out Request</h2>
    <form method="POST">
        <label>Student ID:</label><br>
        <input type="number" name="s_id" required><br>
        
        <label>Reason for Night-out:</label><br>
        <input type="text" name="reason" required><br>
        
        <label>Place of Night-out:</label><br>
        <input type="text" name="place" required><br>
        
        <label>From Date (YYYY-MM-DD):</label><br>
        <input type="date" name="from_date" required><br>
        
        <label>To Date (YYYY-MM-DD):</label><br>
        <input type="date" name="to_date" required><br>
        
        <label>Status (Pending/Approved/Rejected):</label><br>
        <input type="text" name="status" required><br>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>
