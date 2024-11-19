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


echo "<h1>Hostel Management Queries</h1>";

function displayTable($result) {
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
        $first_row = true;
        while ($row = mysqli_fetch_assoc($result)) {
            if ($first_row) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                }
                echo "</tr>";
                $first_row = false;
            }
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "No data found.<br>";
    }
}

// Display Hostel Rules
echo "<h3>Hostel Rules</h3>";
$sql_hostel_rules = "SELECT * FROM rules";
$result = mysqli_query($conn, $sql_hostel_rules);
displayTable($result);

// Display Hostel Information
echo "<h3>Hostel Information</h3>";
$sql_hostel_info = "SELECT * FROM hostel";
$result = mysqli_query($conn, $sql_hostel_info);
displayTable($result);

// Display Visitor Information (Grouped by Student ID)
echo "<h3>Visitor Information (Grouped by Student ID)</h3>";
$sql_visitor_info = "SELECT s_id, GROUP_CONCAT(visitor_name SEPARATOR ', ') AS visitor_names FROM visitors GROUP BY s_id";
$result = mysqli_query($conn, $sql_visitor_info);
displayTable($result);

// Display Admission Information
echo "<h3>Admission Information</h3>";
$sql_admission_info = "SELECT * FROM admission";
$result = mysqli_query($conn, $sql_admission_info);
displayTable($result);

// Query 2: Find students with pending payment status
echo "<h3>Students with Pending Payment Status</h3>";
$sql = "SELECT s_id, fees, payment_status FROM admission WHERE payment_status = 'Pending'";
$result = mysqli_query($conn, $sql);
displayTable($result);

// Query 4: Retrieve all night-out requests that are pending
echo "<h3>Approved Night-Out Requests</h3>";
$sql = "SELECT request_id, s_id, reason, from_date, to_date FROM nightout WHERE status = 'Approved'";
$result = mysqli_query($conn, $sql);
displayTable($result);

// Query 4: Retrieve all night-out requests that are pending
echo "<h3>Pending Night-Out Requests</h3>";
$sql = "SELECT request_id, s_id, reason, from_date, to_date FROM nightout WHERE status = 'Pending'";
$result = mysqli_query($conn, $sql);
displayTable($result);

// Query 4: Retrieve all night-out requests that are pending
echo "<h3>Rejected Night-Out Requests</h3>";
$sql = "SELECT request_id, s_id, reason, from_date, to_date FROM nightout WHERE status = 'Rejected'";
$result = mysqli_query($conn, $sql);
displayTable($result);

// Query 5: Calculate total fees collected by payment mode
echo "<h3>Total Fees Collected by Payment Mode</h3>";
$sql = "SELECT payment_mode, SUM(fees) AS total_fees FROM admission GROUP BY payment_mode";
$result = mysqli_query($conn, $sql);
displayTable($result);

// Query 6: Find number of students in each hostel
echo "<h3>Number of Students in Each Hostel</h3>";
$sql = "SELECT hostel_id, COUNT(s_id) AS student_count FROM student GROUP BY hostel_id";
$result = mysqli_query($conn, $sql);
displayTable($result);

// Query 7: List all students with mother’s and father’s contact details
echo "<h3>Students with Guardian Contact Details</h3>";
$sql = "SELECT s.s_id, s.s_name, g.mother_mob_number, g.father_mob_number, FROM student s JOIN guardian g ON s.s_id = g.s_id";
$result = mysqli_query($conn, $sql);
displayTable($result);

// Query 8: Find students with more than one night-out request
echo "<h3>Students with More Than One Night-Out Request</h3>";
$sql = "SELECT s_id, COUNT(request_id) AS nightout_count FROM nightout GROUP BY s_id HAVING COUNT(request_id) > 1";
$result = mysqli_query($conn, $sql);
displayTable($result);





// Query 17: Find students who joined within a specific date range

echo "<h3>Students Who Joined Within 2023</h3>";
$sql = "SELECT s_id, doj, fees, payment_status FROM admission WHERE doj >= '2023-01-01' AND doj <= '2023-12-31'";
$result = mysqli_query($conn, $sql);
displayTable($result);


// Query 18: List students by admission date (most recent first)
echo "<h3>Students by Most Recent Admission Date</h3>";
$sql = "SELECT s_id, doj, fees, payment_status FROM admission ORDER BY doj DESC";
$result = mysqli_query($conn, $sql);
displayTable($result);


// Query 13: Cursor to List All Students and Their Hostel Information (simplified version without cursor for PHP)
echo "<h3>All Students and Their Hostels</h3>";
$sql = "SELECT * from student GROUP BY hostel_id";
$result = mysqli_query($conn, $sql);
displayTable($result);

// Query 9: Call UpdatePaymentStatus procedure
echo "<h3>Updated Payment Status</h3>";
$sql = "CALL UpdatePaymentStatus()";
$result = mysqli_query($conn, $sql);
displayTable($result);

mysqli_close($conn);
?>
