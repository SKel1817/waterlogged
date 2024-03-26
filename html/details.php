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
    <div>
    <article class="plant-detail-article">

    
    <?php
    session_start();

    // Check if the user is not logged in, then redirect to login page
    if (!isset($_SESSION['user_id'])) {
        header("Location: ./users.php");
        exit();
    }

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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'] ?? '';
    
    if ($action == 'water_plant') {
        // Prepare statement to insert the water log
        $water_sql = "INSERT INTO user_plants (id, user_id, plant_id, last_watered)
        SELECT COALESCE(MAX(id), 0) + 1, $user_id, $plant_id, NOW() FROM user_plants;";
        if ($water_stmt = $conn->prepare($water_sql)) {
            // Assuming user_plant_id is needed, we'll need to retrieve it
            // This will depend on your database schema
            $user_id = $_SESSION['$user_id']; // You need to get the appropriate user_plant_id here
            $plant_id = $_GET['plant_id'];
            $water_stmt->bind_param("i", $user_plant_id);
            $water_stmt->execute();
            $water_stmt->close();

            // Set a session message to confirm watering
            $_SESSION['message'] = 'You have watered your plant.';
        } else {
            // Handle errors with preparing the statement
            $_SESSION['message'] = "Error preparing statement: " . $conn->error;
        }
    } elseif ($action == 'delete_plant') {
        // Prepare statement to delete the plant
        $delete_sql = "DELETE FROM user_plants WHERE user_id = ? AND plant_id = ?";
        if ($delete_stmt = $conn->prepare($delete_sql)) {
            $delete_stmt->bind_param("ii", $user_id, $plant_id);
            $delete_stmt->execute();
            $delete_stmt->close();

            // Set a session message to confirm deletion
            $_SESSION['message'] = 'The plant has been deleted from your shelf.';
        } else {
            // Handle errors with preparing the statement
            $_SESSION['message'] = "Error preparing statement: " . $conn->error;
        }
    }

        // After the action, redirect to prevent form resubmission
        header("Location: ../html/myPlants.php");
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
        WHERE up.user_id = 3 and p.id = 11;";

    // Prepare the SQL statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user id to the statement
        $stmt->bind_param("i", $_SESSION['user_id']);

        // Execute the statement
        $stmt->execute();

        // Get the result of the query
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Output data of the plant name, traits, water_freq_weekly date watered, and image
            while($row = $result->fetch_assoc()) {
                echo '<article class="plant-detail-article">';
                echo '<img src="../images/' . htmlspecialchars($row['path']) . '" alt="Plant Image">';
                echo '<h2>' . htmlspecialchars($row['PlantName']) . '</h2>';
                echo '<p>' . htmlspecialchars($row['traits']) . '</p>';
                echo '<p>Sun amount: ' . htmlspecialchars($row['sun']) . '</p>';
                echo '<p>Water-Frequency: ' . htmlspecialchars($row['water_freq_weekly']) . '</p>';
                echo '<div class="plant-detail-buttons">';
                echo '<button class="plant-detail-button plant-detail-button-delete">Delete from shelf</button>';
                echo '<button class="plant-detail-button plant-detail-button-water">Water Plant</button>';
                echo '</div>';
                echo '</article>';
            }
            if (isset($_SESSION['message'])) {
                echo '<p class="message">' . htmlspecialchars($_SESSION['message']) . '</p>';
                // Clear the message after displaying it
                unset($_SESSION['message']);
            }
        // Close the statement
        $stmt->close();
    } else {
        // Handle errors with preparing the statement
        echo "Error preparing statement: " . $conn->error;
        }
    }
    // Close the connection
    $conn->close();
?>
    <form method="post" action="details.php?plant_id=<?= urlencode($plant_id) ?>">
        <input type="hidden" name="action" value="water_plant">
        <input type="submit" class="button water-button" value="Water Plant">
    </form>

    <!-- Delete Plant Form -->
    <form method="post" action="details.php?plant_id=<?= urlencode($plant_id) ?>">
        <input type="hidden" name="action" value="delete_plant">
        <input type="submit" class="button delete-button" value="Delete from Shelf" onclick="return confirm('Are you sure you want to delete this plant from your shelf?');">
    </form>
    </article>
    </div>
</body>
</html>