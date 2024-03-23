<?php
// Connect to database (replace placeholders with actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to check if username and password exist in database
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Start session and set session variables
    session_start();
    $_SESSION['username'] = $username;
    echo "Login successful. Welcome, $username!";
} else {
    echo "Invalid username or password.";
}

$conn->close();
?>