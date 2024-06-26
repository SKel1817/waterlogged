<?php
session_start();

// Check if the form was submitted and the user is logged in
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $plantId = $_POST['plant_id'];
    $location_id = $_POST['location_id'];
 	//print_r($_SESSION);
 	//print_r($_POST);
    // Database connection code...
    // Database connection details
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "waterlogged";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Prepare the INSERT statement to add the plant to the user's shelf

    $stmt = $conn->prepare("INSERT INTO user_plants (id, user_id, plant_id) SELECT COALESCE(MAX(id), 0) + 1, ? , ? FROM user_plants;");
    $stmt->bind_param("ii", $userId, $plantId);

	if ($stmt->execute()) {
	    $_SESSION['added_to_shelf'] = true; // Indicate a plant was successfully added
	}
	
    // Redirect back to the plant list or show a success message
 	header('Location: plantlist.php?location_id=' . $location_id);
    exit();
}
?>
