<?php

// Assuming you've established a database connection
$host = "127.0.0.1";  // replace with your actual database host
$username = "root";    // replace with your actual database username
$password = "";        // replace with your actual database password
$database = "ams_db";  // replace with your actual database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if there is data to submit
    if (empty($_POST['student_ids'])) {
        echo "<p style='font-size: 30px; color:red; font-weight:bold;'>No data to submit!</p>";
    } else {
        // Collect form data
        $student_ids = $_POST['student_ids'];
        $attendance_dates = $_POST['attendance_dates'];
        $attendance_status = isset($_POST['attendance_status']) ? $_POST['attendance_status'] : [];

        // Loop through the submitted data and update the database
        for ($i = 0; $i < count($student_ids); $i++) {
            $student_id = $student_ids[$i];
            $date = $attendance_dates[$i];
            $status = in_array($student_id, $attendance_status) ? 'Present' : 'Absent';

            // Check if there's an existing record for the given date
            $existing_record_query = "SELECT * FROM attendance_history 
                                      WHERE StudentID = '$student_id' AND Date = '$date'";
            $existing_record_result = mysqli_query($conn, $existing_record_query);

            if (mysqli_num_rows($existing_record_result) > 0) {
                // Update the existing record
                $update_query = "UPDATE attendance_history 
                                 SET Status = '$status' 
                                 WHERE StudentID = '$student_id' AND Date = '$date'";
            } else {
                // Insert a new record
                $update_query = "INSERT INTO attendance_history (StudentID, Date, Status) 
                                 VALUES ('$student_id', '$date', '$status')";
            }

            if (!mysqli_query($conn, $update_query)) {
                echo "Error updating attendance: " . mysqli_error($conn);
                break;
            }
        }

        echo "<p style='font-size: 30px; color:green; font-weight:bold;'>Attendance updated successfully!</p>";
    }
} else {
    echo "<p style='font-size: 30px; color:red; font-weight:bold;'>Invalid request.</p>";
}

// Fetch and display attendance history grouped by date
$attendance_history_query = "SELECT Date, GROUP_CONCAT(CONCAT(FirstName, ' ', LastName, ': ', Status) SEPARATOR '<br>') AS AttendanceDetails
                             FROM student
                             LEFT JOIN attendance_history ON student.StudentID = attendance_history.StudentID
                             GROUP BY Date
                             ORDER BY Date DESC";

$attendance_history_result = mysqli_query($conn, $attendance_history_query);

echo "<table>";
echo "<tr><th>Date</th><th>Attendance Details</th></tr>";

while ($row = mysqli_fetch_assoc($attendance_history_result)) {
    echo "<tr>";
    echo "<td>{$row['Date']}</td>";
    echo "<td>{$row['AttendanceDetails']}</td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($conn);

?>
