<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/users.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "user"; // replace with your database username
$password = "pass!"; // replace with your database password
$dbname = "waterlogged";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to get user plants
$sql = "SELECT 
user_plants.id as user_pant_id,
users.id, 
plants.id as plant_Id, 
plants.name, 
images.path FROM user_plants 
JOIN users ON user_plants.user_id = users.id 
JOIN plants ON user_plants.plant_id = plants.id 
JOIN images on plants.id = images.plant_id 
WHERE users.id = ?";

$userPlants = [];
$imagePaths = []; // Corrected variable name

if ($stmt = $conn->prepare($sql)) {
  // Bind the user id to the statement
  $stmt->bind_param("i", $_SESSION['user_id']);
  
  // Execute the statement
  $stmt->execute();
  
  // Bind the results to variables
  $stmt->bind_result($user_plant_id, $userId, $plantId, $plantName, $imagePath);
  
  // Fetch the results into an array
  while ($stmt->fetch()) {
    $userPlants[] = [
            'name' => $plantName, 
            'imagePath' => $imagePath, 
            'plant_Id' => $plantId,  // Corrected variable name
            'user_plant_id' => $user_plant_id
        ];  }
  
  // Close the statement
  $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Plants</title>
    <link rel="stylesheet" href="../css/style.css" />
  </head>
  <body>
    <nav class="nav-links">
      <div class="logo">
      <a href="../index.php">
        <img
          src="..\images\Waterlogged_Logo.png"
          alt="Waterlogged Logo"
          id="logo"
        />
        </a>
      </div>
      <div class="please"style="position: absolute; left: 50%; transform: translateX(-50%);">Waterlogged</div>
      <ul>
        <li><a href="../index.php">Plant Home</a></li>
        <li><a href="#">My Plants</a></li>
        <li><a href="../html/logs.php">Logs</a></li>
        <li><a href="../html/users.php">Log In</a></li>
      </ul>
    </nav>
    <div id="frame"> </div>
        <?php
        $shelfIndex = 1;
        if (!empty($userPlants)) {
            foreach ($userPlants as $index => $plantDetails) {
                // Open a new shelf section if it's a new row or the first plant
                if ($index % 3 === 0) {
                    if ($index > 0) {
                        // Close the previous shelf if it's not the first plant
                        echo '</section>';
                    }
                    echo '<section class="layout" id="shelf' . $shelfIndex . '">';
                    $shelfIndex++;
                }
                 // Notice that you only need user_plant_id to uniquely identify the record, so plant_id and user_id are not needed
                 $detailsUrl = 'details.php?user_plant_id=' . urlencode($plantDetails['user_plant_id']);
                 
                
                echo '<div style="color: white">';
                echo '<a href="' . htmlspecialchars($detailsUrl) . '" style="text-decoration: none; color: inherit;">';
                echo '<img src="../' . htmlspecialchars($plantDetails['imagePath']) . '" alt="' . htmlspecialchars($plantDetails['name']) . '" style="width:150px;height:150px; display:block; margin:auto;">';
                echo htmlspecialchars($plantDetails['name']);
                echo '</a>';
                echo '</div>';
                
                // Close the shelf section if it's the end of a row or the last plant
                if (($index + 1) % 3 === 0 || $index === count($userPlants) - 1) {
                    echo '</section>';
                }
            }
        } else {
            echo '<p id="noPlantTXT">You have no plants yet.</br> <a id"noPlantA" href="../index.php">Click here to add some!</a></p>';
        }
        ?>
</div>

  </body>
</html>