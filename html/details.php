<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Logs</title>
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
    <div class="container">
    <?php
		session_start();

		// Check if the user is not logged in, then redirect to login page
		if (!isset($_SESSION['user_id'])) {
		    header("Location: ./users.php");
		    exit();
		}

		// Validate the input
		$plant_id = isset($_GET['plant_id']) ? (int)$_GET['plant_id'] : 0; // Cast to integer for safety
		$user_id = $_SESSION['user_id'];

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
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
		    if ($_POST['action'] == 'water') {
		        // Prepare your INSERT INTO user_plants SQL statement here
		        // Since your query seemed a bit off, assuming you're updating the last_watered date:
		        $updateSql = "UPDATE user_plants SET last_watered = NOW() WHERE user_id = ? AND plant_id = ?";
		        if ($stmt = $conn->prepare($updateSql)) {
		            $stmt->bind_param("ii", $user_id, $_POST['plant_id']);
		            $stmt->execute();
		            $stmt->close();
		
		            // Feedback message
		            echo "<p>You have successfully watered your plant.</p>";
		            header("Location: details.php?plant_id=" . $plant_id);
		        }
		    } elseif ($_POST['action'] == 'delete') {
		        // Prepare your DELETE FROM user_plants SQL statement here
		        $deleteSql = "DELETE FROM user_plants WHERE user_id = ? AND plant_id = ?";
		        if ($stmt = $conn->prepare($deleteSql)) {
		            $stmt->bind_param("ii", $user_id, $_POST['plant_id']);
		            $stmt->execute();
		            $stmt->close();
		
		            // Feedback message and potentially redirect or disable further actions
		            echo "<p>The plant has been removed from your shelf.</p>";
		            header("Location: ../html/myPlants.php");
		        }
		    }
		    // It might be a good idea to redirect to avoid form resubmission issues
		  
		    exit();
		}
		
		// Your SQL query
		$sql = "SELECT
		        p.name AS PlantName,
		        p.sun,
		        p.traits,
		        p.water_freq_weekly,
		        i.path
		            FROM
		                user_plants as up
		            INNER JOIN plants as p ON up.plant_id = p.id
		            Inner join images as i on p.id = i.plant_id
		            WHERE up.user_id = ? and p.id = ?;";

		// Prepare the SQL statement to prevent SQL injection
		if ($stmt = $conn->prepare($sql)) {
		    // Bind the user id and plant id to the statement
		    $stmt->bind_param("ii", $user_id, $plant_id);

		    // Execute the statement
		    $stmt->execute();

		    // Get the result of the query
		    $result = $stmt->get_result();

		    if ($result->num_rows > 0) {
		        // Output data of the plant name, traits, water_freq_weekly date watered, and image
		        while($row = $result->fetch_assoc()) {
		            echo '<div class="plant-image">';
		            echo '<img class="plant-image" src="../' . htmlspecialchars($row['path']) . '" alt="Plant Image">';
					echo '</div>';
					echo '<div class="plant-name">';
					echo '<h2>' . htmlspecialchars($row['PlantName']) . '</h2>';
					echo '</div>';
					echo '<div class="plant-details">';
	                echo '<p>' . htmlspecialchars($row['traits']) . '</p>';
	                echo '<br>';
	                echo '<p>Sun amount: ' . htmlspecialchars($row['sun']) . '</p>';
	                echo '<br>';
	                echo '<p>Water-Frequency: ' . htmlspecialchars($row['water_freq_weekly']) . ' times a week</p>';
					echo '</div>';
		            	       } 
		    } else {
		        echo "No plant found with the given ID.";
		    }
		    // Close the statement
		    $stmt->close();
		} else {
		    // Handle errors with preparing the statement
		    echo "Error preparing statement: " . $conn->error;
		}
		// Close the connection
		$conn->close();
		?>
		<div class="plant-detail-buttons">
		<form method="POST" action="">
		    <input type="hidden" name="action" value="delete">
		    <input type="hidden" name="plant_id" value="<?= htmlspecialchars($plant_id) ?>">
		    <button type="submit" class="button delete-button">Delete from Shelf</button>
		</form>
		<form method="POST" action="">
		    <input type="hidden" name="action" value="water">
		    <input type="hidden" name="plant_id" value="<?= htmlspecialchars($plant_id) ?>">
		    <button type="submit" class="button water-button">Water Plant</button>
		</form>
		</div>
    </div>
</body>
</html>
