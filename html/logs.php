GNU nano 4.8                                                                                                                                                                                                                                   logs.php                                                                                                                                                                                                                                    Modified  
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
    <table style="width:100%; table-layout: fixed;border: 8px solid brown;">
        <tr>
            <td style="border: 3px solid green;">plant name</td>
            <td style="border: 3px solid green;">date watered</td>
        </tr>
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
            l.date_watered AS DateWatered
        FROM
            logs as l
        INNER JOIN user_plants up ON l.user_plant_id = up.id
        INNER JOIN plants as p ON up.plant_id = p.id
        WHERE up.user_id = ?
        ORDER BY
            l.date_watered DESC;";

// Prepare the SQL statement to prevent SQL injection
if ($stmt = $conn->prepare($sql)) {
    // Bind the user id to the statement
    $stmt->bind_param("i", $_SESSION['user_id']);

    // Execute the statement
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr><td style='border: 3px solid green;'>" . htmlspecialchars($row["PlantName"]) . "</td><td style='border: 3px solid green;'>" . htmlspecialchars($row["DateWatered"]) . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='2' style='border: 3px solid green;'>No logs found</td></tr>";
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
    </table>
</body>
</html>