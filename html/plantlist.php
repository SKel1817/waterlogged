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
            <img src="../images/Waterlogged_Logo.png" alt="Waterlogged Logo" id="logo"/>
        </div>
        <div class="please"style="position: absolute; left: 50%; transform: translateX(-50%);">Waterlogged</div>
        <ul>
            <li><a href="../index.html">Plant Home</a></li>
            <li><a href="./myPlants.html">My Plants</a></li>
            <li><a href="./logs.php">Logs</a></li>
            <li><a href="./users.html">Log In</a></li>
        </ul>
    </nav>
    <table style="width:100%; table-layout: fixed;">
        <tr>
            <td style="border: 1px solid black;">plant</td>
            <td style="border: 1px solid black;">traits</td>
            <td style="border: 1px solid black;">sun</td>
            <td style="border: 1px solid black;">water</td>
        </tr>
        <?php
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

        // Check if 'location_id' is set in the GET request
        if (isset($_GET['location_id']) && is_numeric($_GET['location_id'])) {
            $location_id = $conn->real_escape_string($_GET['location_id']);
        }
		// Your SQL query
		$sql = "SELECT name, traits, sun, water_freq_weekly FROM plants where location_id = 1";

		$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='border: 1px solid black;'>" . $row["name"] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["traits"] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["sun"] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["water_freq_weekly"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' style='border: 1px solid black;'>0 results</td></tr>";
}
		$conn->close();
        ?>
    </table>
</body>
</html>
