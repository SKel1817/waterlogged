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
            <img src="../images/Waterlogged_Logo.png" alt="Waterlogged Logo" id="logo"/>
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

    // Your SQL query
    $sql = "SELECT
    p.name AS PlantName,
    p.sun,
    p.traits,
    p.water_freq_weekly,
    l.date_watered AS DateWatered,
    i.path
        FROM
            logs as l
        INNER JOIN user_plants up ON l.user_plant_id = up.id
        INNER JOIN plants as p ON up.plant_id = p.id
        Inner join images as i on p.id = i.plant_id
        WHERE up.user_id = $user_id and p.id = $plant_id;";

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
                if ($userLoggedIn) {
                    echo '<div class="plant-detail-buttons">';
                    echo '<button class="plant-detail-button plant-detail-button-delete">Delete from shelf</button>';
                    echo '<button class="plant-detail-button plant-detail-button-water">Water Plant</button>';
                    echo '</div>';
                }
                echo '</article>';
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
    </article>
    </div>
</body>
</html>