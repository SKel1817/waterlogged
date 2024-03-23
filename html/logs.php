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
            <li><a href="../index.html">Plant Home</a></li>
            <li><a href="./myPlants.html">My Plants</a></li>
            <li><a href="./logs.php">Logs</a></li>
            <li><a href="./users.html">Log In</a></li>
        </ul>
    </nav>
    <table style="width:100%; table-layout: fixed;border: 8px solid brown;">
        <tr>
            <td style="border: 5px solid green;">plant name</td>
            <td style="border: 5px solid green;">date watered</td>
        </tr>
        <?php
        // Database connection details
        $servername = "localhost";
        $username = "cof";
        $password = "cOwmoo1324!";
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
		        ORDER BY
		            l.date_watered DESC;";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		    // Output data of each row
		    while($row = $result->fetch_assoc()) {
		        echo "<tr><td style='border: 5px solid green;'>" . $row["PlantName"] . "</td><td style='border: 5px solid brown;'>" . $row["DateWatered"] . "</td></tr>";
		    }
		} else {
		    echo "<tr><td colspan='2' style='border: 5px solid green;'>0 results</td></tr>";
		}
		$conn->close();
        ?>
    </table>
</body>
</html>

