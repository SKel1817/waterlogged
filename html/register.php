<?php
session_start();

// Define variables for database connection
$servername = "localhost";
$dbUsername = "user";
$dbPassword = "!";
$dbname = "waterlogged";

// Create database connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$username = "";
$password = "";
$error = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Login logic
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);

        $sql = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            header("Location: ../html/myPlants.php"); // Redirect to the plants page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } elseif (isset($_POST['register'])) {
        // Registration logic
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']); // You should hash the password
        $result = $conn->query("SELECT MAX(id) AS max_id FROM users");
        $row = $result->fetch_assoc();
        $maxId = $row['max_id'];
        $nextId = $maxId + 1;
        $sql = "INSERT INTO users (id, username, password) VALUES ($nextId,'$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Login the user or send to a thank you page
            $_SESSION['user_id'] = $conn->insert_id;
            header("Location: ../html/myPlants.php"); // Redirect to the plants page
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="stylesheet" href="../css/mainStyle.css" />
</head>
<body>
    <header>
        <nav class="nav-links">
            <div class="logo">
            <a href="../index.php">
                <img src="../images/Waterlogged_Logo.png" alt="Waterlogged Logo" id="logo"/>
			</a>
            </div>
            <div class="please" style="position: absolute; left: 50%; transform: translateX(-50%);">
                Waterlogged
            </div>
            <ul>
            <li><a href="../index.php"> Plant Home</a></li>
            <li><a href="../html/myPlants.php">My Plants</a></li>
            <li><a href="../html/logs.php">Logs</a></li>
            <li><a href="#">Log In</a></li>
        </ul>
        </nav>
    </header>
    <section class="login">
        <?php if ($error): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <!-- Registration Form -->
        <article id="registerForm">
            <form id="loginForm" action="register.php" method="post">
            <h2 style="color: darkgreen; font-size: 35px;">Register</h2>
            <label for="new_username" style="font-weight: bold; font-size: 20px;">Username:</label><br>
            <input type="text" id="new_username" name="username" value="" style="background-color: #90EE90;"><br>
            <label for="new_password"style="font-weight: bold; font-size: 20px;">Password:</label><br>
            <input type="password" id="new_password" name="password" value="" style="background-color: #90EE90;"><br><br>
            <input id="submitButton" type="submit" name="register" value="Register" disabled style="background-color: #4CAF50; color: white; padding: 14px 20px; font-size: 20px; border: none; border-radius: 5px; cursor: pointer;">

            </form>
        </article>
    </section>
    <script>
    	document.getElementById('loginForm').addEventListener('input', function() {
    	  var username = document.getElementById('new_username').value;
    	  var password = document.getElementById('new_password').value;
    	  if (username && password) {
    	    document.getElementById('submitButton').disabled = false;
    	  } else {
    	    document.getElementById('submitButton').disabled = true;
    	  }
    	});
    </script>
</body>
</html>
