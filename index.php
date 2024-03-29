<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Plants Home</title>
    <link rel="stylesheet" href="./css/mainStyle.css" />
  </head>
  <body>
    <header>
      <nav class="nav-links">
        <div class="logo">
        <a href="#">
          <img
            src=".\images\Waterlogged_Logo.png"
            alt="Waterlogged Logo"
            id="logo"
          />
         </a>
        </div>
        <div class="please"style="position: absolute; left: 50%; transform: translateX(-50%);">Waterlogged</div>
        <ul>
          <li><a href="#">Plant Home</a></li>
          <li><a href="./html/myPlants.php">My Plants</a></li>
          <li><a href="./html/logs.php">Logs</a></li>
          <li><a href="./html/users.php">Log In</a></li>
        </ul>
      </nav>
    </header>
    <section class="layout">
      <?php
       // Database connection details
        $servername = "localhost";
        $username = "user";
        $password = "!";
        $dbname = "waterlogged";

       // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

       // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
        // Your existing PHP code for the database connection

        $sql = "SELECT id, name, path from locations join images on locations.id = images.location_id";
        $result = $conn->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div> <a href="../html/plantlist.php?location_id=' . htmlspecialchars($row['id']) . '"><article id="aLocation">';
            // Image
            echo '<img id="lImg" src="' . htmlspecialchars($row['path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            // Button (styled link)
            echo '<span class="location-button">View ' . htmlspecialchars($row['name']) . '</span>';
            echo '</article></a></div>';
        }

        
      ?>
    </section>
  </body>
</html>

