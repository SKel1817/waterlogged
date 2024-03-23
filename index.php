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
          <img
            src=".\images\Waterlogged_Logo.png"
            alt="Waterlogged Logo"
            id="logo"
          />
        </div>
        <div class="please"style="position: absolute; left: 50%; transform: translateX(-50%);">Waterlogged</div>
        <ul>
          <li><a href="#">Plant Home</a></li>
          <li><a href="./html/myPlants.html">My Plants</a></li>
          <li><a href="./html/logs.php">Logs</a></li>
          <li><a href="./html/users.html">Log In</a></li>
        </ul>
      </nav>
    </header>
    <section class="layout">
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
      // Assuming you have a database connection established
      $sql = "SELECT name from locations";

      $result = $conn->query($sql);

      while ($row = mysqli_fetch_assoc($result)) {
        echo '<div><article><a href="../waterlogged/html/plantlist.php?location_id=' . $row['location_id'] . '" class="location-button">' . $row['name'] . '</a></article></div>';
      }
      ?>
    </section>
  </body>
</html>
