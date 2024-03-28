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
    <div id="wateringAnimation" style="display:none;">
        <img src="../images/watering.gif" alt="Watering Animation" style="width: 100px;">
    </div>
    
    <?php
		session_start();
;
		
		// Check if the user is not logged in, then redirect to login page
		if (!isset($_SESSION['user_id'])) {
		    header("Location: ./users.php");
		    exit();
		}

		// Validate the input
		$user_plant_id = isset($_GET['user_plant_id']) ? (int)$_GET['user_plant_id'] : 0; // Cast to integer for safety
		$plant_id = isset($_GET['plant_id']) ? (int)$_GET['plant_id'] : 0;
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
		        $updateSql = "insert into logs (id, user_plant_id, date_watered) SELECT COALESCE(MAX(id), 0) + 1, ?, NOW() from logs";
		        if ($stmt = $conn->prepare($updateSql)) {
		            $stmt->bind_param("i", $_POST['user_plant_id']);
		            $stmt->execute();
		            $stmt->close();

					$updateWaterSql = "UPDATE user_plants JOIN (SELECT user_plant_id, MAX(date_watered) AS latest_watering_date FROM logs GROUP BY user_plant_id) AS most_recent_logs ON user_plants.id = most_recent_logs.user_plant_id SET user_plants.last_watered = most_recent_logs.latest_watering_date";
					if ($stmt = $conn->prepare($updateWaterSql)) {
					    // Assuming no parameters to bind
				    if(!$stmt->execute()) {
				        echo "Error executing query: " . $stmt->error;
				    }
				    $stmt->close();
					} else {
					    echo "Error preparing statement: " . $conn->error;
					}
				
		            // Feedback message
		            echo "<p>You have successfully watered your plant.</p>";
		            header("Location: details.php?user_plant_id=" . $user_plant_id);
		        
		        }
		    } elseif ($_POST['action'] == 'delete') {
		   		 // First, delete related logs
		        $deleteLogsSql = "DELETE FROM logs WHERE user_plant_id = ?";
		        if ($stmt = $conn->prepare($deleteLogsSql)) {
		            $stmt->bind_param("i", $_POST['user_plant_id']);
		            $stmt->execute();
		            $stmt->close();
		        } else {
		            echo "Error preparing statement: " . $conn->error;
		            exit();
		        }
		    
		        // Then, delete the plant
		        // Prepare your DELETE FROM user_plants SQL statement here
		        $deleteSql = "DELETE FROM user_plants WHERE id = ?";
		        if ($stmt = $conn->prepare($deleteSql)) {
		            $stmt->bind_param("i", $_POST['user_plant_id']);
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
    up.last_watered,
    i.path
        FROM
            user_plants as up
        INNER JOIN plants as p ON up.plant_id = p.id
        INNER JOIN images as i on p.id = i.plant_id
        WHERE up.id = ?;";
		// Prepare the SQL statement to prevent SQL injection
		if ($stmt = $conn->prepare($sql)) {
		    // Bind the user id and plant id to the statement
		    $stmt->bind_param("i", $user_plant_id);

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
	                echo '<p>Water Frequency: ' . htmlspecialchars($row['water_freq_weekly']) . ' times a week</p>';
	                echo '<br>';
	                echo '<p>Last Watered: ' . htmlspecialchars($row['last_watered']) . '</p>';
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
		    <input type="hidden" name="user_plant_id" value="<?= htmlspecialchars($user_plant_id) ?>">
		    <button type="submit" class="button delete-button">Delete from Shelf</button>
		</form>
		<form method="POST" action="">
		    <input type="hidden" name="action" value="water">
		    <input type="hidden" name="user_plant_id" value="<?= htmlspecialchars($user_plant_id) ?>">
		    <button type="submit" class="button water-button">Water Plant</button>
		</form>
		</div>
    </div>
    <script>
    	document.querySelector(".water-button").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent form submission for demonstration
        var animation = document.getElementById("wateringAnimation");
        animation.style.display = "block"; // Show the GIF
    
        // Hide the GIF after it has played for a set duration
        setTimeout(function() {
            animation.style.display = "none";
            event.target.form.submit();
        }, 6000); // Adjust the timeout to match the length of your GIF animation
    });
    </script>
    
</body>
</html>
