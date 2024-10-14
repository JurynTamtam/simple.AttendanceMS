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
    // Collect form data
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $class = $_POST['class'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if the username already exists
    $check_query = "SELECT * FROM student WHERE Username = '$username'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        echo "<p style='font-size: 20px; color:red; font-weight:bold;'>Error adding student: Duplicate entry.</p>";
    } else {
        // Insert new student into the database
        $insert_query = "INSERT INTO student (Username, FirstName, LastName, Class, Password, Role) 
        VALUES ('$username','$firstname', '$lastname', '$class','$password', '$role')";

        if (mysqli_query($conn, $insert_query)) {
            echo "<p style='font-size: 30px; color:green; font-weight:bold;'>Student added successfully!</p>";
        } else {
            echo "<p style='font-size: 20px; color:red; font-weight:bold;'>Error adding student: " . mysqli_error($conn) . "</p>";
        }
    }
}

mysqli_close($conn);

?>
