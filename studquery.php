<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'hostel_management';
$port = '3307'; // Default is 3306 unless specified in your setup

$conn = mysqli_connect($host, $user, $password, $dbname, $port);

// Welcome message
echo "<h1>Welcome to Hostel Management</h1>";

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display table results
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
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "No data found.<br>";
    }
}

// Query 22: Select a single student based on student ID
echo "<h3>Student Details</h3>";
if (isset($_POST['s_id'])) {
    $s_id = $_POST['s_id'];
    $sql_select_single_student = "SELECT * FROM student WHERE s_id = '$s_id'";
    $result = mysqli_query($conn, $sql_select_single_student);
    displayTable($result);
}

echo '<form method="POST">
        <label for="s_id">Enter Student ID:</label>
        <input type="number" name="s_id" required>
        <button type="submit">Get Student Details</button>
      </form>';

// Query 3: Count the number of visitors on a specific date
echo "<h3>List of Visitors on a Given Date</h3>";
if (isset($_POST['visitor_count_date'])) {
    $date = $_POST['visitor_count_date'];
    $sql_count_visitors = "SELECT COUNT(visitor_id) AS visitor_count FROM visitors WHERE visit_date = '$date'";
    $result = mysqli_query($conn, $sql_count_visitors);
    displayTable($result);
}

echo '<form method="POST">
        <label for="visitor_count_date">Visitor Count Date:</label>
        <input type="date" name="visitor_count_date" required>
        <button type="submit">Get Visitor Count</button>
      </form>';

// Query 10: Calculate total night-out requests by a student
echo "<h3>Total Night-Out Requests for a Student</h3>";
if (isset($_POST['nightout_student_id'])) {
    $student_id = $_POST['nightout_student_id'];
    $sql_nightout_requests = "SELECT TotalNightoutRequests($student_id) AS total_requests";
    $result = mysqli_query($conn, $sql_nightout_requests);
    displayTable($result);
}

echo '<form method="POST">
        <label for="nightout_student_id">Student ID for Night-Out Requests:</label>
        <input type="number" name="nightout_student_id" required>
        <button type="submit">Get Total Requests</button>
      </form>';

// Query 11: List all visitors for a specific student
echo "<h3>Visitors for a Specific Student</h3>";
if (isset($_POST['list_visitors_student_id'])) {
    $student_id = $_POST['list_visitors_student_id'];
    $sql_list_visitors = "CALL ListVisitors($student_id)";
    $result = mysqli_query($conn, $sql_list_visitors);
    displayTable($result);
}

echo '<form method="POST">
        <label for="list_visitors_student_id">Student ID to List Visitors:</label>
        <input type="number" name="list_visitors_student_id" required>
        <button type="submit">List Visitors</button>
      </form>';

// Query 14: Display Guardian Contact Information
echo "<h3>Guardian Contact Information</h3>";
$sql_guardian_info = "SELECT * FROM guardiancontactinfo";
$result = mysqli_query($conn, $sql_guardian_info);
displayTable($result);

// Query 16: LIKE queries
echo "<h3>Students by Branch (Like 'Comp%')</h3>";
$sql_like_branch = "SELECT s_id, s_name, branch FROM student WHERE branch LIKE 'Comp%'";
$result = mysqli_query($conn, $sql_like_branch);
displayTable($result);

echo "<h3>Students with 'an' in Name</h3>";
$sql_like_name = "SELECT s_id, s_name FROM student WHERE s_name LIKE '%an%'";
$result = mysqli_query($conn, $sql_like_name);
displayTable($result);

// Query 20: LEFT JOIN to get students and admission details
echo "<h3>Students and Their Admission Details</h3>";
$sql_left_join = "SELECT s.s_id, s.s_name, s.branch, s.year, a.doj, a.fees, a.payment_status FROM student s LEFT JOIN admission a ON s.s_id = a.s_id";
$result = mysqli_query($conn, $sql_left_join);
displayTable($result);

// Query 9: Call UpdatePaymentStatus procedure and display result
echo "<h3>Updated Payment Status</h3>";
$sql_call = "CALL UpdatePaymentStatus()";
$result = mysqli_query($conn, $sql_call);
displayTable($result);

mysqli_close($conn);
?>
