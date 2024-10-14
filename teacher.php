<?php
function getStudentsAttendance() {
    // Assuming you've established a database connection
    $host = "127.0.0.1";  // replace with your actual database host
    $username = "root";    // replace with your actual database username
    $password = "";        // replace with your actual database password
    $database = "ams_db";  // replace with your actual database name

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }


    // Fetch students' attendance data from the database
    $query = "SELECT student.StudentID, student.FirstName, student.LastName, attendance.Date, attendance.Status 
              FROM student 
              LEFT JOIN attendance ON student.StudentID = attendance.studentID";
    $result = mysqli_query($conn, $query);

    $students_data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $students_data[] = $row;
    }

    mysqli_close($conn);

    return $students_data;
}
function getRecordHistory() {
    // Assuming you've established a database connection
    $host = "127.0.0.1";  // replace with your actual database host
    $username = "root";    // replace with your actual database username
    $password = "";        // replace with your actual database password
    $database = "ams_db";  // replace with your actual database name

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }


    // Fetch students' attendance data from the database
    $query = "SELECT student.StudentID, student.FirstName, student.LastName, attendance_history.Date, attendance_history.Status 
              FROM student 
              LEFT JOIN attendance_history ON student.StudentID = attendance_history.studentID";
    $result = mysqli_query($conn, $query);

    $record_data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $record_data[] = $row;
    }

    mysqli_close($conn);

    return $record_data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Teacher Dashboard</title>
</head>
<body>
<div class="left">
    <a class="logout" href ="index.html" method="post">Log Out</a>
</div>  
<div class="right">

         <!-- Form to add a new student -->
         <h2 class="h2">Add New Student</h2><hr>
        <form action="add_student.php" method="post" class="addStu">
            <label for="username">UserName:</label>
            <input type="text" name="username" required>

            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" required>

            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" required>

            <label for="class">Class:</label>
            <input type="text" name="class" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
           title="Password must contain at least one digit, one lowercase and one uppercase letter, and be at least 8 characters long">

            <label for="role">Role:</label>
            <input type="text" name="role" value="Student" required>

            <input class="add" type="submit"  value="Add Student">
        </form>
</div>
    <div class="container">
        <h1 class="texth1">Welcome Teacher!</h1><hr>
        <?php
session_start();
if (isset($_SESSION['user_id'])) {
    $teacher_id = $_SESSION['user_id'];

    // Assuming you have a function to get students and their attendance data
    $students_data = getStudentsAttendance();

    echo "<h2>Students Attendance</h2>";
    echo '<form action="update_attendance.php" method="post">';
    echo "<table>";
    echo "<tr><th>Student Name</th><th>Date</th><th>Status</th></tr>";
    foreach ($students_data as $student) {
        echo "<tr>";
        echo "<td>{$student['FirstName']} 
                  {$student['LastName']}<input type='hidden' name='student_ids[]' value='{$student['StudentID']}'></td>";
        echo "<td><input type='date' name='attendance_dates[]'  value='2024-01-02' required></td>";
        echo "<td><input type='checkbox' name='attendance_status[]' value='{$student['StudentID']}' ";
        echo ($student['Status'] == 'Present') ? 'checked' : '';
        echo "></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo '<input type="submit" class="submit" value="Submit Attendance">';
    echo '</form>';

$record_data = getRecordHistory();
echo "<h2>Attendance History</h2>";
            echo "<table>";
            echo "<tr><th>Student Name</th><th>Date</th><th>Status</th></tr>";
            foreach ($record_data as $record) {
                echo "<tr><td>{$record['FirstName']} 
                          {$record['LastName']}<input type='hidden' name='student_ids[]' value='{$student['StudentID']}'></td>";
                echo "<td>{$record['Date']}</td>";
                echo "<td>{$record['Status']}</tr>";
            }
            echo "</table>";
        }else {
    header("Location: index.html");
}

?>
  </div>
</body>
</html>