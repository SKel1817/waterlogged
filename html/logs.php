GNU nano 4.8                                                                                                        logs.php                                                                                                                   
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
    <table style="width:100%; table-layout: fixed;">
        <tr>
            <td style="border: 1px solid black;">id</td>
            <td style="border: 1px solid black;">plant id</td>
            <td style="border: 1px solid black;">date watered</td>
        </tr>
        <?php
        // Database connection details
        $servername = "localhost";
        $username = "cow";
        $password = "cOwmoo1324!";
        $dbname = "waterlogged";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM logs WHERE plant_id in (SELECT id FROM user_plants WHERE user_id = $user_id)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td style='border: 1px solid black;'>" . $row["id"] . "</td><td style='border: 1px solid black;'>" . $row["date_watered"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3' style='border: 1px solid black;'>0 results</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
