<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $button = $_POST['login-btn'];

    // Aiven MySQL credentials
    $username = $_ENV['username'];
    $server = $_ENV['server'];
    $password = $_ENV['password'];
    $dbname = $_ENV['dbname'];
    $port = $_ENV['port'];

    // Create MySQL connection with SSL
    $mysqli = new mysqli($server, $username, $password, $dbname, $port);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Set SSL if required (adjust the path to your certificate)
    $mysqli->ssl_set(NULL, NULL, "/path/to/ca-cert.pem", NULL, NULL);
    $mysqli->real_connect($server, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);

    function validate_input($name, $email, $password) {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            return "Only letters and whitespace allowed in name.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long.";
        }
        return "";
    }

    if ($button == 'signup') {
        $email = $_POST['signupemail'];
        $name = $_POST['signupname'];
        $pass = $_POST['signuppassword'];

        $validationError = validate_input($name, $email, $pass);
        if ($validationError) {
            header("Location: login1.php?signup_error=" . urlencode($validationError));
            exit;
        }

        // Plain password handling
        $plainPassword = $pass;

        $checkEmailQuery = "SELECT * FROM login WHERE email = ?";
        $stmt = $mysqli->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: login1.php?signup_error=" . urlencode("Email already exists."));
        } else {
            $signupQuery = "INSERT INTO login (name, email, password, date) VALUES (?, ?, ?, current_timestamp())";
            $stmt = $mysqli->prepare($signupQuery);
            $stmt->bind_param("sss", $name, $email, $plainPassword);

            if ($stmt->execute()) {
                header("Location: login1.php?signup_success=1");
            } else {
                header("Location: login1.php?signup_error=" . urlencode("Sign-up failed. Try again later."));
            }
        }
        $stmt->close();
    } elseif ($button == 'signin') {
        $email = $_POST['signinemail'];
        $pass = $_POST['signinpassword'];

        $signinQuery = "SELECT * FROM login WHERE email = ?";
        $stmt = $mysqli->prepare($signinQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Direct comparison of plain password
            if ($pass === $row['password']) {
                $_SESSION['user'] = $row['name'];
                $_SESSION['email'] = $row['email']; // Store email for dropdown display
                header("Location: index.php");
            } else {
                header("Location: login1.php?signin_error=" . urlencode("Incorrect password."));
            }
        } else {
            header("Location: login1.php?signin_error=" . urlencode("No account found with that email."));
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>
