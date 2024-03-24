<?php
session_start();

// Define variables for database connection
$servername = "localhost";
$dbUsername = "cof";
$dbPassword = "cOwmoo1324!";
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
            <div class="logo">
                <img src="../images/Waterlogged_Logo.png" alt="Waterlogged Logo" id="logo"/>
            </div>
            <div class="please" style="position: absolute; left: 50%; transform: translateX(-50%);">
                Waterlogged
            </div>
            <ul>
            <li><a href="../index.php">Plant Home</a></li>
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
        <?php if ($loggedInUser): ?>
        <p>Already logged in as <?php echo htmlspecialchars($loggedInUser); ?></p>
        <form action="logout.php" method="post">
            <input type="submit" name="logout" value="Sign Out">
        </form>
        <?php endif; ?>
        <!-- Login Form -->
        <?php if (!$loggedInUser): ?>
            <article id="registerForm">
                <h2>Login</h2>
                <form action="users.php" method="post">
                    <br>
                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" value=""><br>
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" value=""><br><br>
                    <input type="submit" name="login" value="Login">
                    <a class="register-button" href='../html/register.php'>Don't have an account? Register</a>
                </form>  
            </article>
        <?php endif; ?>
        <script>
    </section>
</body>
</html>
