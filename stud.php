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

if (isset($_POST['submit'])) {
    // Retrieve student details
    $s_name = $_POST['s_name'];
    $mobile_number = $_POST['mobile_number'];
    $gender = $_POST['gender'];
    $DOB = $_POST['DOB'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $blood_group = $_POST['blood_group'];
    $room_id = $_POST['room_id'];
    $hostel_id = $_POST['hostel_id'];
    
    // Insert student data into the database
    $sql = "INSERT INTO student (s_name, mobile_number, gender, DOB, year, branch, address, email, blood_group, room_id, hostel_id)
            VALUES ('$s_name', '$mobile_number', '$gender', '$DOB', '$year', '$branch', '$address', '$email', '$blood_group', '$room_id', '$hostel_id')";
    
    if (mysqli_query($conn, $sql)) {
        // Get the last inserted student ID (s_id)
        $s_id = mysqli_insert_id($conn);

        // Retrieve guardian details
        $mother_name = $_POST['mother_name'];
        $father_name = $_POST['father_name'];
        $guardian_name = $_POST['guardian_name'];
        $mother_mob = $_POST['mother_mob'];
        $father_mob = $_POST['father_mob'];
        $guardian_mob = $_POST['guardian_mob'];
        
        // Insert guardian data into the database
        $sql_guardian = "INSERT INTO guardian (s_id, mother_name, father_name, guardian_name, mother_mob, father_mob, guardian_mob)
                         VALUES ('$s_id', '$mother_name', '$father_name', '$guardian_name', '$mother_mob', '$father_mob', '$guardian_mob')";
        
        if (mysqli_query($conn, $sql_guardian)) {
            echo "Student and Guardian details successfully added.<br>";
        } else {
            echo "Error inserting guardian: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Error inserting student: " . mysqli_error($conn) . "<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student and Guardian Details</title>
</head>
<body>
    <h1>Enter Student Details</h1>
    <form method="POST" action="">
        <label>Student Name:</label><br>
        <input type="text" name="s_name" required><br>
        
        <label>Mobile Number:</label><br>
        <input type="text" name="mobile_number" required><br>
        
        <label>Gender:</label><br>
        <input type="text" name="gender" required><br>
        
        <label>Date of Birth:</label><br>
        <input type="date" name="DOB" required><br>
        
        <label>Year:</label><br>
        <input type="number" name="year" required><br>
        
        <label>Branch:</label><br>
        <input type="text" name="branch" required><br>
        
        <label>Address:</label><br>
        <input type="text" name="address" required><br>
        
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        
        <label>Blood Group:</label><br>
        <input type="text" name="blood_group" required><br>
        
        <label>Room ID:</label><br>
        <input type="number" name="room_id" required><br>
        
        <label>Hostel ID:</label><br>
        <input type="number" name="hostel_id" required><br>

        <h2>Guardian Details</h2>
        <label>Mother's Name:</label><br>
        <input type="text" name="mother_name" required><br>
        
        <label>Father's Name:</label><br>
        <input type="text" name="father_name" required><br>

        <label>Guardian's Name:</label><br>
        <input type="text" name="guardian_name" required><br>

        <label>Mother's Mobile Number:</label><br>
        <input type="text" name="mother_mob" required><br>

        <label>Father's Mobile Number:</label><br>
        <input type="text" name="father_mob" required><br>

        <label>Guardian's Mobile Number:</label><br>
        <input type="text" name="guardian_mob" required><br>

        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>
