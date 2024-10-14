<?php

function getStudentAttendance($student_id) {
    // Replace these values with your actual database connection details
    $host = "127.0.0.1";  // replace with your actual database host
    $username = "root";    // replace with your actual database username
    $password = "";        // replace with your actual database password
    $database = "ams_db";  // replace with your actual database name

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch student attendance data from the database
    $query = "SELECT * FROM attendance_history WHERE StudentID = $student_id";
    $result = mysqli_query($conn, $query);

    $attendance_data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $attendance_data[] = $row;
    }

    mysqli_close($conn);

    return $attendance_data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Student Dashboard</title>
</head>
<body>

    <div class="container">
        <h1 class="texth1">Welcome Student!</h1><hr>
        <?php
            session_start();
            if (isset($_SESSION['user_id'])) {
                $student_id = $_SESSION['user_id'];

                // Call the function to get student attendance data
                $attendance_data = getStudentAttendance($student_id);

                echo "<h2>Your Attendance</h2>";
                echo "<table>";
                echo "<tr><th>Date</th><th>Status</th></tr>";
                foreach ($attendance_data as $attendance) {
                    echo "<tr><td>{$attendance['Date']}</td>
                              <td>{$attendance['Status']}</td></tr>";
                }
                echo "</table>";
            } else {
                header("Location: index.html");
            }
            ?>
<div class="left">
    <a class="logout" href ="index.html" method="post">Log Out</a>
</div>
    </div>
</body>
</html>
