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
plants.name,
images.path
FROM user_plants 
JOIN users ON user_plants.user_id = users.id 
JOIN plants ON user_plants.plant_id = plants.id 
Join images on plants.id = images.plant_id
WHERE users.id = ?";
$userPlants = [];
$path =[];

if ($stmt = $conn->prepare($sql)) {
  // Bind the user id to the statement
  $stmt->bind_param("i", $_SESSION['user_id']);
  
  // Execute the statement
  $stmt->execute();
  
  // Bind the results to variables
  $stmt->bind_result($plantName, $path);
  
  // Fetch the results into an array
  while ($stmt->fetch()) {
    $userPlants[] = $plantName;
    $path[] = $path;
  }
  
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
        <img
          src="..\images\Waterlogged_Logo.png"
          alt="Waterlogged Logo"
          id="logo"
        />
      </div>
      <div class="please"style="position: absolute; left: 50%; transform: translateX(-50%);">Waterlogged</div>
      <ul>
        <li><a href="../index.php">Plant Home</a></li>
        <li><a href="#">My Plants</a></li>
        <li><a href="../html/logs.php">Logs</a></li>
        <li><a href="../html/users.php">Log In</a></li>
      </ul>
    </nav>
    <div id="frame">
        <?php $shelfIndex = 1; ?>
        <?php foreach ($userPlants as $index => $plantName): ?>
          <?php if ($index % 3 === 0): ?>
            <section class="layout" id="shelf<?= $shelfIndex ?>">
              <?php $shelfIndex++; ?>
          <?php endif; ?>
          <div style="color: white">
            <img src="../<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($plantName) ?>" style="width:150px;height:150px; display:block; margin:auto;">
          <?= htmlspecialchars($plantName) ?>
          </div>
          <?php if (($index + 1) % 3 === 0 || $index === count($userPlants) - 1): ?>
            </section>
          <?php endif; ?>
        <?php endforeach; ?>
        <?php if (empty($userPlants)): ?>
            <p>You have no plants yet.</p>
        <?php endif; ?>
    </div>
    </div>
  </body>
</html>