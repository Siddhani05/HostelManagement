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
    
    // Retrieve visitor details from the form
    $s_id = $_POST['s_id']; // Student ID
    $visitor_name = $_POST['visitor_name'];
    $check_in_time = $_POST['check_in_time'];
    $check_out_time = $_POST['check_out_time'];
    $visitor_contact = $_POST['visitor_contact'];
    $relation_to_student = $_POST['relation_to_student'];
    
    // Insert visitor data into the database
    $sql_visitor = "INSERT INTO visitors (s_id, visitor_name, visit_date, check_in_time, check_out_time, visitor_contact, relation_to_student)
                    VALUES ('$s_id', '$visitor_name', NOW(), '$check_in_time', '$check_out_time', '$visitor_contact', '$relation_to_student')";
    
    if (mysqli_query($conn, $sql_visitor)) {
        echo "Visitor details successfully added.<br>";
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
    <title>Visitor Details</title>
</head>
<body>
    <h2>Visitor Details</h2>
    <form method="POST">
        <label>Student ID:</label><br>
        <input type="number" name="s_id" required><br>
        
        <label>Visitor's Name:</label><br>
        <input type="text" name="visitor_name" required><br>
        
        <label>Check-in Time:</label><br>
        <input type="datetime" name="check_in_time" required><br>
        
        <label>Check-out Time:</label><br>
        <input type="datetime" name="check_out_time" required><br>
        
        <label>Visitor's Contact:</label><br>
        <input type="text" name="visitor_contact" required><br>
        
        <label>Relation to Student:</label><br>
        <input type="text" name="relation_to_student" required><br>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>
