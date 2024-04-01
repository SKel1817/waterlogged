GNU nano 4.8                                                                                                                                                                                                                                                                                                                    users.php                                                                                                                                                                                                                                                                                                                              
<?php
session_start();

// Define variables for database connection
$servername = "localhost";
$dbUsername = "username";
$dbPassword = "password";
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
$loggedInUser = "";
$loggedInName = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Login logic
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);

        $sql = "SELECT id FROM users WHERE LOWER(username) = LOWER('$username') AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            header("Location: ../html/myPlants.php"); // Redirect to the plants page
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } 
}
if (isset($_SESSION['user_id'])) {
    $loggedInUser = $_SESSION['user_id'];

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log In / Register</title>
    <link rel="stylesheet" href="../css/mainStyle.css" />
</head>
<body>
    <header>
        <nav class="nav-links">

            <a href="../index.php" class="logo">
                <img src="../images/Waterlogged_Logo.png" alt="Waterlogged Logo" id="logo"/>
                </a>

            <div class="please" style="position: absolute; left: 50%; transform: translateX(-50%);">
                Waterlogged
            </div>
            <ul>
            <li><a href="../index.php">Plant Home</a></li>
            <li><a href="../html/myPlants.php">My Plants</a></li>            <li><a href="../html/logs.php">Logs</a></li>
            <li><a href="#">Log In</a></li>
        </ul>
        </nav>
    </header>
 
    <section class="login"style="text-align: center;">
        <?php if ($error): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($loggedInUser): ?>
<div style=>
<div class="logged-in-container">
        <p style="color:#C2A83E;font-size: 35px;">Already logged in as <?php echo htmlspecialchars($loggedInUser); ?></p><br>
        <form action="logout.php" method="post"><br><br><br>
            <input type="submit" name="logout" value="Sign Out"style="font-size: larger; background-color:#7CA982; color:#F1F7ED; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; box-shadow:0px 0px 11px 1px #ffffff;">
        </form>
</div>
</div>
        <?php endif; ?>
        <!-- Login Form -->
        <?php if (!$loggedInUser): ?>


            <article id="registerForm" >



                 <form action="users.php" method="post">

                   <h2 class="centered-heading" style="color:#243E36 ; font-size: 35px;">Login</h2><br>
                    <div style="text-align: left; margin-left: 2%;">
                    <label for="username"style="font-weight: bold; font-size: 20px;"class="centered-label">Username:</label><br><br>
                     </div>
                    <div style="text-align: left; margin-left: 12.5%;">
                    <input type="text" id="username" name="username" value="" style="background-color:#E0EEC6 ;"class="centered-input">
                    </div>
                    <div style="text-align: left; margin-left: 2%;">
                    <label for="password"style="font-weight: bold; font-size: 20px;"class="centered-label">Password:</label><br><br>
                    </div>
                    <div style="text-align: left; margin-left: 12.5%;">
                    <input type="password" id="password" name="password" value="" style="background-color:#E0EEC6;"class="centered-input"><br>

                    </div><br>
                    <div style="text-align: left; margin-left: 12%;">
                    <input  type="submit" name="login" value="Login" style="background-color:#C2A83E; color:#F1F7ED; padding: 14px 20px; font-size: 20px; border: none; border-radius: 5px; cursor: pointer;"class="centered-button"><br><br>
                    </div>
<div style="text-align: left; margin-left: 12.5%;">
                    <a class="register-button" href='../html/register.php'>Don't have an account? Register</a>
</div>
                </form>   
            </article>
        <?php endif; ?>

    </section>
<div class="footer">
    <p>&copy; 2024 Waterlogged Inc. </p>
</div>
</body>
</html>