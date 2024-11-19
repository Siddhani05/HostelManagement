<?php
// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'hostel_management';
$port = '3307';

$conn = mysqli_connect($host, $user, $password, $dbname, $port);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handling Student Details form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_student'])) {
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

// Handling Visitor Details form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_visitor'])) {
    $s_id = $_POST['s_id'];
    $visitor_name = $_POST['visitor_name'];
    $check_in_time = $_POST['check_in_time'];
    $check_out_time = $_POST['check_out_time'];
    $visitor_contact = $_POST['visitor_contact'];
    $relation_to_student = $_POST['relation_to_student'];

    $sql_visitor = "INSERT INTO visitors (s_id, visitor_name, visit_date, check_in_time, check_out_time, visitor_contact, relation_to_student)
                    VALUES ('$s_id', '$visitor_name', NOW(), '$check_in_time', '$check_out_time', '$visitor_contact', '$relation_to_student')";
    if (mysqli_query($conn, $sql_visitor)) {
        echo "Visitor details successfully added.<br>";
    } else {
        echo "Error: " . mysqli_error($conn) . "<br>";
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' &&  isset($_POST['submit_hostel'])) {
        // Hostel details form submission
        $hostel_id = $_POST['hostel_id'];
        $hostel_name = $_POST['hostel_name'];
        $no_of_floors = $_POST['no_of_floors'];
        $no_of_rooms = $_POST['no_of_rooms'];
        $capacity = $_POST['capacity'];

        $sql_hostel = "INSERT INTO hostel (hostel_id, hostel_name, no_of_floors, no_of_rooms, capacity) 
                       VALUES ('$hostel_id', '$hostel_name', '$no_of_floors', '$no_of_rooms', '$capacity')";
        if (mysqli_query($conn, $sql_hostel)) {
            echo "Hostel details successfully added.<br>";
        } else {
            echo "Error: " . mysqli_error($conn) . "<br>";
        }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' &&  isset($_POST['submit_admission'])) {
        // Admission details form submission
        $s_id = $_POST['s_id'];
        $DOJ = $_POST['DOJ'];
        $fees = $_POST['fees'];
        $payment_mode = $_POST['payment_mode'];
        $payment_status = $_POST['payment_status'];
        $payment_date = $_POST['payment_date'];

        $sql_admission = "INSERT INTO admission (s_id, DOJ, fees, payment_mode, payment_status, payment_date)
                          VALUES ('$s_id', '$DOJ', '$fees', '$payment_mode', '$payment_status', '$payment_date')";
        if (mysqli_query($conn, $sql_admission)) {
            echo "Admission details successfully added.<br>";
        } else {
            echo "Error: " . mysqli_error($conn) . "<br>";
        }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' &&  isset($_POST['submit_nightout'])) {
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
            echo "Nightout Details successfully added.<br>";
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
    <title>Hostel Management</title>
    <style>
        .tab { display: none; }
        .tab-content { margin-top: 20px; }
        .tabs button { padding: 10px; margin-right: 5px; cursor: pointer; }
        .active { background-color: #4CAF50; color: white; }
    </style>
    <script>
        function showTab(tabId) {
    // Hide all the forms first
    document.getElementById("student-form").style.display = 'none';
    document.getElementById("visitor-form").style.display = 'none';
    document.getElementById("hostel-form").style.display = 'none';
    document.getElementById("admission-form").style.display = 'none';
    document.getElementById("nightout-form").style.display = 'none';
    document.getElementById("hostelrulesForm").style.display = 'none';
    document.getElementById("hostelQueryForm").style.display = 'none';
    document.getElementById("studQueryForm").style.display = 'none';
    
    // Display the selected tab
    if (tabId === 'student') {
        document.getElementById("student-form").style.display = 'block';
    } else if (tabId === 'visitor') {
        document.getElementById("visitor-form").style.display = 'block';
    } else if (tabId === 'hostel') {
        document.getElementById("hostel-form").style.display = 'block';
    } else if (tabId === 'admission') {
        document.getElementById("admission-form").style.display = 'block';
    }else if(tabId === 'nightout'){
        document.getElementById("nightout-form").style.display = 'block';
    }else if (tabId === 'hostelrules') {
        document.getElementById("hostelrulesForm").style.display = 'block';
    }else if (tabId === 'hostelQuery') {
        document.getElementById("hostelQueryForm").style.display = 'block';
    }else if (tabId === 'studQuery') {
        document.getElementById("studQueryForm").style.display = 'block';
    }

}

    </script>
</head>
<body>
    <div class="tabs">
    <button onclick="showTab('student')" class="active">Student Details</button>
    <button onclick="showTab('visitor')" class="active">Visitor Details</button>
    <button onclick="showTab('hostel')" class="active">Hostel Details</button>
    <button onclick="showTab('admission')" class="active">Admission Details</button>
    <button onclick="showTab('nightout')" class="active">Nightout Details</button>
    <button onclick="showTab('hostelrules')" class="active">Hostel Rules</button>
    <button onclick="showTab('hostelQuery')" class="active">Hostel Query Display</button>
    <button onclick="showTab('studQuery')" class="active">Student Query Display</button>



    </div>
    <div class="tab-content">
        <!-- Student Details Form -->
        <div id="student-form" class="tab" style="display:block;">
            <h2>Enter Student Details</h2>
            <form method="POST">
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

                <button type="submit" name="submit_student">Submit Student Details</button>
            </form>
        </div>
        
        <!-- Visitor Details Form -->
        <div id="visitor-form" class="tab" style="display:block;">
            <h2>Visitor Details</h2>
            <form method="POST">
                <label>Student ID:</label><br>
                <input type="number" name="s_id" required><br>
                
                <label>Visitor's Name:</label><br>
                <input type="text" name="visitor_name" required><br>
                
                <label>Check-in Time:</label><br>
                <input type="datetime-local" name="check_in_time" required><br>
                
                <label>Check-out Time:</label><br>
                <input type="datetime-local" name="check_out_time" required><br>
                
                <label>Visitor's Contact:</label><br>
                <input type="text" name="visitor_contact" required><br>
                
                <label>Relation to Student:</label><br>
                <input type="text" name="relation_to_student" required><br>
                
                <button type="submit" name="submit_visitor">Submit Visitor Details</button>
            </form>
        </div>

        <div id="hostel-form" class="tab" style="display:block;">
            <h3>Hostel Details</h3>
            <form method="POST">
                <label>Hostel ID:</label>
                <input type="number" name="hostel_id" required><br><br>
                
                <label>Hostel Name:</label>
                <input type="text" name="hostel_name" required><br><br>
                
                <label>No of Floors:</label>
                <input type="number" name="no_of_floors" required><br><br>
                
                <label>No of Rooms:</label>
                <input type="number" name="no_of_rooms" required><br><br>
                
                <label>Capacity:</label>
                <input type="number" name="capacity" required><br><br>
                
                <button type="submit" name="submit_hostel">Submit Hostel Details</button>
            </form>
        </div>

        <div id="admission-form" class="tab" style="display:none;">
            <h3>Admission Details</h3>
            <form method="POST">
            <label>Student ID:</label>
            <input type="number" name="s_id" required><br><br>

            <label>Date of Joining (DOJ):</label>
            <input type="date" name="DOJ" required><br><br>

            <label>Fees:</label>
            <input type="number" name="fees" required><br><br>

            <label>Payment Mode:</label>
            <input type="text" name="payment_mode" required><br><br>

            <label>Payment Status:</label>
            <input type="text" name="payment_status" required><br><br>

            <label>Payment Date (DOJ):</label>
            <input type="date" name="payment_date" required><br><br>

                <button type="submit" name="submit_admission">Submit Admission Details</button>
            </form>
        </div>

        <div id="nightout-form" class="tab" style="display:none;">
            <h3>Nightout Details</h3>
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

                <button type="submit" name="submit_nightout">Submit Nightout Details</button>
            </form>
        </div>

        <div id="hostelrulesForm" class="tab" style="display:none;">
            <h3>Hostel Rules</h3>
            <form method="POST">
                <?php
                include 'db.php';
                function displayTable3($result) {
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

                // Query for Hostel Rules
                $sql_hostel_rules = "SELECT * FROM rules";
                $result = mysqli_query($conn, $sql_hostel_rules);
                displayTable3($result);
                mysqli_close($conn);
                ?>        
            </form>
        </div>

        <div id="hostelQueryForm" style="display:none;">
            <h3>Hostel Queries Display</h3>
            <form method="POST">
                <?php
                include 'db.php'; // Ensure connection to the database
                
                function displayTable2($result) {
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
                
                // Hostel Information
                echo "<h3>Hostel Information</h3>";
                $sql_hostel_info = "SELECT * FROM hostel";
                $result = mysqli_query($conn, $sql_hostel_info);
                displayTable2($result);
                
                // Visitor Information (Grouped by Student ID)
                echo "<h3>Visitor Information (Grouped by Student ID)</h3>";
                $sql_visitor_info = "SELECT s_id, GROUP_CONCAT(visitor_name SEPARATOR ', ') AS visitor_names FROM visitors GROUP BY s_id";
                $result = mysqli_query($conn, $sql_visitor_info);
                displayTable2($result);

                // Admission Information
                echo "<h3>Admission Information</h3>";
                $sql_admission_info = "SELECT * FROM admission";
                $result = mysqli_query($conn, $sql_admission_info);
                displayTable2($result);

                // Students with Pending Payment Status
                echo "<h3>Students with Pending Payment Status</h3>";
                $sql = "SELECT s_id, fees, payment_status FROM admission WHERE payment_status = 'Pending'";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Approved Night-Out Requests
                echo "<h3>Approved Night-Out Requests</h3>";
                $sql = "SELECT request_id, s_id, reason, from_date, to_date FROM nightout WHERE status = 'Approved'";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Pending Night-Out Requests
                echo "<h3>Pending Night-Out Requests</h3>";
                $sql = "SELECT request_id, s_id, reason, from_date, to_date FROM nightout WHERE status = 'Pending'";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Rejected Night-Out Requests
                echo "<h3>Rejected Night-Out Requests</h3>";
                $sql = "SELECT request_id, s_id, reason, from_date, to_date FROM nightout WHERE status = 'Rejected'";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Total Fees Collected by Payment Mode
                echo "<h3>Total Fees Collected by Payment Mode</h3>";
                $sql = "SELECT payment_mode, SUM(fees) AS total_fees FROM admission GROUP BY payment_mode";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Number of Students in Each Hostel
                echo "<h3>Number of Students in Each Hostel</h3>";
                $sql = "SELECT hostel_id, COUNT(s_id) AS student_count FROM student GROUP BY hostel_id";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                echo "<h3>Students lisst hostelwise</h3>";
                $sql = "SELECT * FROM student ORDER BY hostel_id;";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Students with More Than One Night-Out Request
                echo "<h3>Students with More Than One Night-Out Request</h3>";
                $sql = "SELECT s_id, COUNT(request_id) AS nightout_count FROM nightout GROUP BY s_id HAVING COUNT(request_id) > 1";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Students Who Joined Within 2023
                echo "<h3>Students Who Joined Within 2023</h3>";
                $sql = "SELECT s_id, doj, fees, payment_status FROM admission WHERE doj >= '2023-01-01' AND doj <= '2023-12-31'";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Students by Most Recent Admission Date
                echo "<h3>Students by Most Recent Admission Date</h3>";
                $sql = "SELECT s_id, doj, fees, payment_status FROM admission ORDER BY doj DESC";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // All Students and Their Hostels
                echo "<h3>All Students and Their Hostels</h3>";
                $sql = "SELECT * FROM student GROUP BY hostel_id";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                // Updated Payment Status
                echo "<h3>Updated Payment Status</h3>";
                $sql = "CALL UpdatePaymentStatus()";
                $result = mysqli_query($conn, $sql);
                displayTable2($result);

                mysqli_close($conn); // Properly close the connection here
                ?>        

            </form>
        </div>

        <div id="studQueryForm" style="display:none;">
            <h3>Student Query Display</h3>
            <form method="POST">
                <?php
                include 'db.php';
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
                if (isset($_POST['nightout_s_id'])) {
                    $s_id = $_POST['nightout_s_id'];
                    $sql_nightout_requests = "SELECT TotalNightoutRequests($s_id) AS total_requests";
                    $result = mysqli_query($conn, $sql_nightout_requests);
                    displayTable($result);
                }

                echo '<form method="POST">
                        <label for="nightout_s_id">Student ID for Night-Out Requests:</label>
                        <input type="number" name="nightout_s_id" required>
                        <button type="submit">Get Total Requests</button>
                    </form>';

                // Query 11: List all visitors for a specific student
                echo "<h3>Visitors for a Specific Student</h3>";
                if (isset($_POST['list_visitors_s_id'])) {
                    $s_id = $_POST['list_visitors_s_id'];
                    $sql_list_visitors = "CALL ListVisitors($s_id)";
                    $result = mysqli_query($conn, $sql_list_visitors);
                    displayTable($result);
                }

                echo '<form method="POST">
                        <label for="list_visitors_s_id">Student ID to List Visitors:</label>
                        <input type="number" name="list_visitors_s_id" required>
                        <button type="submit">List Visitors</button>
                    </form>';

                
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

            </form>
        </div>

    </div>
</body>