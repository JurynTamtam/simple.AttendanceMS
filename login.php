<?php
session_start();

// Assuming you've established a database connection
$host = "127.0.0.1";  // replace with your actual database host
$username = "root";    // replace with your actual database username
$password = "";        // replace with your actual database password
$database = "ams_db";  // replace with your actual database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Assuming you've received form data
$username = $_POST['username'];
$password = $_POST['password'];

// Validate user credentials for Student
$queryStudent = "SELECT * FROM Student WHERE Username='$username' AND Password='$password'";
$resultStudent = mysqli_query($conn, $queryStudent);

if (mysqli_num_rows($resultStudent) > 0) {
    $rowStudent = mysqli_fetch_assoc($resultStudent);
    $_SESSION['user_id'] = $rowStudent['StudentID'];
    $roleStudent = $rowStudent['Role'];

    // Redirect based on user role
    if ($roleStudent === 'Student') {
        header("Location: student.php");
    } else {
        echo "Invalid Role";
    }
} else {
    // If not found in Student table, check Teacher table
    $queryTeacher = "SELECT * FROM teacher WHERE Username='$username' AND Password='$password'";
    $resultTeacher = mysqli_query($conn, $queryTeacher);

    if (mysqli_num_rows($resultTeacher) > 0) {
        $rowTeacher = mysqli_fetch_assoc($resultTeacher);
        $_SESSION['user_id'] = $rowTeacher['TeacherID'];
        $roleTeacher = $rowTeacher['Role'];

        // Redirect based on user role
        if ($roleTeacher === 'Teacher') {
            header("Location: teacher.php");
        } else {
            echo "<p style='font-size: 25px; color:red; font-weight:bold;'>Invalid Role.</p>";
        }
    } else {
        echo "<p style='font-size: 25px; color:red; font-weight:bold;'>Invalid Username or Password.</p>";
    }
}

mysqli_close($conn);
?>
