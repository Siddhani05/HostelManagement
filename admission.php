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
    
    // Retrieve admission details from the form
    $s_id = $_POST['s_id'];
    $DOJ = $_POST['DOJ'];
    $fees = $_POST['fees'];
    $payment_mode = $_POST['payment_mode'];
    $payment_status = $_POST['payment_status'];
    $payment_date = $_POST['payment_date'];
    
    // Insert admission data into the database
    $sql_admission = "INSERT INTO admission (s_id, DOJ, fees, payment_mode, payment_status, payment_date)
                      VALUES ('$s_id', '$DOJ', '$fees', '$payment_mode', '$payment_status', '$payment_date')";
    
    if (mysqli_query($conn, $sql_admission)) {
        echo "Admission details successfully added.<br>";
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
    <title>Admission Details</title>
</head>
<body>
    <h2>Admission Details</h2>
    <form method="POST">
        <label>Student ID:</label><br>
        <input type="number" name="s_id" required><br><br>
        
        <label>Date of Joining (DOJ):</label><br>
        <input type="date" name="DOJ" required><br><br>
        
        <label>Fees:</label><br>
        <input type="number" name="fees" required><br><br>
        
        <label>Payment Mode:</label><br>
        <input type="text" name="payment_mode" required><br><br>
        
        <label>Payment Status:</label><br>
        <input type="text" name="payment_status" required><br><br>
        
        <label>Payment Date:</label><br>
        <input type="date" name="payment_date" required><br><br>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>
