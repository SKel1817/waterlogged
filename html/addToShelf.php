<?php
session_start();

// Check if the form was submitted and the user is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $plantId = $_POST['plant_id'];
    $location_id = $_POST['location_id'];

    // Database connection code...
    // Database connection details
    $servername = "localhost";
    $username = "user";
    $password = "pass!";
    $dbname = "waterlogged";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Prepare the INSERT statement to add the plant to the user's shelf

    $stmt = $conn->prepare("INSERT INTO user_plants (id, user_id, plant_id) SELECT COALESCE(MAX(id), 0) + 1, 1, 6 FROM user_plants;");
    $stmt->bind_param("ii", $userId, $plantId);
    $stmt->execute();

    // Redirect back to the plant list or show a success message
    header('Location: plantlist.php?location_id=' . $location_id);
    exit();
}
?>
