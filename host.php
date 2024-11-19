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
// Handle form submission for hostel details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve hostel details
    $hostel_id =$_POST['hostel_id'];
    $hostel_name = $_POST['hostel_name'];
    $no_of_floors = $_POST['no_of_floors'];
    $no_of_rooms = $_POST['no_of_rooms'];
    $capacity = $_POST['capacity'];

    // Insert hostel data into the database
    $sql_hostel = "INSERT INTO hostel (hostel_id,hostel_name, no_of_floors, no_of_rooms, capacity) 
                   VALUES ('$hostel_id','$hostel_name', '$no_of_floors', '$no_of_rooms', '$capacity')";
    if (mysqli_query($conn, $sql_hostel)) {
        echo "Hostel details successfully added.<br>";
    } else {
        echo "Error: " . mysqli_error($conn) . "<br>";
    }

    // Retrieve admission details
    $s_id = $_POST['s_id'];
    $DOJ = $_POST['DOJ'];
    $fees = $_POST['fees'];
    $payment_mode = $_POST['payment_mode'];
    $payment_status = $_POST['payment_status'];
    $payment_date = $_POST['payment_date'];

    // Insert admission data into the database
    $sql_admission = "INSERT INTO admission (s_id, DOJ, fees, payment_mode, payment_status, payment_date)
                      VALUES ((SELECT s_id FROM student WHERE s_id = '$s_id'), '$DOJ', '$fees', '$payment_mode', '$payment_status', '$payment_date')";

    if (mysqli_query($conn, $sql_admission)) {
        echo "Admission details successfully added.<br>";
    } else {
        echo "Error: " . mysqli_error($conn) . "<br>";
    }

    echo "Error: " . mysqli_error($conn) . "<br>";
    // }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Details</title>
</head>
<body>
    <h1>Enter Hostel Details</h1>
    <form action="host.php" method="POST">
        <h2>Hostel Details</h2>
        <label>hostel_id:</label>
        <input type="number" name="hostel_id" required><br><br>

        <label>Hostel Name:</label>
        <input type="text" name="hostel_name" required><br><br>

        <label>no_of_floors:</label>
        <input type="number" name="no_of_floors" required><br><br>

        <label>no_of_rooms:</label>
        <input type="number" name="no_of_rooms" required><br><br>

        <label>Capacity:</label>
        <input type="number" name="capacity" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
