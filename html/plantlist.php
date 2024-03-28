<?php
    session_start();
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

     // Initialize the variable to ensure it has a default value
    $location_id = 0;
    $userLoggedIn = isset($_SESSION['user_id']);
    $locationName = "";
    $locationTemperature = "";
    $locationClimate = "";
    


     // Check if 'location_id' is set in the GET request
    if (isset($_GET['location_id']) && is_numeric($_GET['location_id'])) {
        $location_id = $conn->real_escape_string($_GET['location_id']);
    }
     // Your SQL query
    $sql = "SELECT 
    l.id as location_id,
    l.name as location_name, 
    l.temperature,
    l.climate,
    p.id,
    p.name, 
    p.traits, 
    p.sun, 
    p.water_freq_weekly,
    images.path 
    FROM plants as p 
    join images on p.id = images.plant_id
    join locations as l on p.location_id = l.id
    where p.location_id = $location_id;";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $firstRow = $result->fetch_assoc();
        $locationName = $firstRow["location_name"]; // Make sure "name" matches your column name in SQL
        $locationTemperature = $firstRow["temperature"]; // Match column name
        $locationClimate = $firstRow["climate"]; // Match column name
        // If the column names are prefixed with 'l.' in your database, use that exact name.
        $location_id = $firstRow["location_id"];
        $id = $firstRow["id"];
        // Then you reset the pointer to the beginning
        $result->data_seek(0);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Plant list</title>
    <link rel="stylesheet" href="../css/mainStyle.css" />
</head>
<body>
    <nav class="nav-links">
        <div class="logo">
        <a href="../index.php">
            <img src="../images/Waterlogged_Logo.png" alt="Waterlogged Logo" id="logo"/>
		</a>
        </div>
        <div class="please"style="position: absolute; left: 50%; transform: translateX(-50%);">Waterlogged</div>
        <ul>
            <li><a href="../index.php">Plant Home</a></li>
            <li><a href="./myPlants.php">My Plants</a></li>
            <li><a href="./logs.php">Logs</a></li>
            <li><a href="./users.php">Log In</a></li>
        </ul>
    </nav>
    <?php if (!empty($_SESSION['added_to_shelf'])): ?>
        <div id="shelfAnimation" style="text-align:center;">
            <img src="../images/shelf.gif" alt="Shelf Animation">
        </div>
        <script>
            // Optional: Hide the GIF after a few seconds
            setTimeout(function() {
                document.getElementById('shelfAnimation').style.display = 'none';
            }, 5000); // Adjust time as needed
        </script>
        <?php unset($_SESSION['added_to_shelf']); // Remove the variable after showing the GIF ?>
    <?php endif; ?>
    
    <h1><?php echo htmlspecialchars($locationName); ?> Plant List</h1>
    <p>Temperature: <?php echo htmlspecialchars($locationTemperature); ?></p>
    <p>Climate: <?php echo htmlspecialchars($locationClimate); ?></p>
    <table style=" table-layout: fixed;">
    <tr>
        <th style="border: 1px solid black;">Image</th>
        <th style="border: 1px solid black;">Plant</th>
        <th style="border: 1px solid black;">Traits</th>
        <th style="border: 1px solid black;">Sun</th>
        <th style="border: 1px solid black;">Water</th>
        <?php if ($userLoggedIn): ?>
            <th style="border: 1px solid black;">Action</th>
        <?php endif; ?>
    </tr>
    <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td style='border: 1px solid black;'>
                        <img src="../<?php echo htmlspecialchars($row['path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:100px;height:auto;">
                    </td>
                    <td style='border: 1px solid black;'><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td style='border: 1px solid black;'><?php echo htmlspecialchars($row["traits"]); ?></td>
                    <td style='border: 1px solid black;'><?php echo htmlspecialchars($row["sun"]); ?></td>
                    <td style='border: 1px solid black;'><?php echo htmlspecialchars($row["water_freq_weekly"]); ?></td>
                    <?php if ($userLoggedIn): ?>
                        <td style='border: 1px solid black;'>
                        <?php
			                // Check if the plant is already in the user's shelf
			                $userId = $_SESSION['user_id'];
			                $plantId = $row['id'];
			                $shelfCheckSql = "SELECT * FROM user_plants WHERE user_id = '$userId' AND plant_id = '$plantId'";
			                $shelfCheckResult = $conn->query($shelfCheckSql);
			                    // Plant is not in the user's shelf, show the add button
			                ?>
		                    <form method='post' action='addToShelf.php' id="addToShelfForm">
		                        <input type='hidden' name='plant_id' value='<?php echo htmlspecialchars($row['id']); ?>'>
		                        <input type='hidden' name='user_id' value='<?php echo htmlspecialchars($_SESSION['user_id']); ?>'>
		                        <input type='hidden' name='location_id' value='<?php echo htmlspecialchars($row['location_id']); ?>'>
		                        <input type='submit' name='add_to_shelf' value='Add to My Shelf'>
		                    </form>
		                    
                      		
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan='5' style='border: 1px solid black;'>0 results</td></tr>
        <?php endif; ?>
    </table>

</body>
</html>