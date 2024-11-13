<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database credentials
$username = "avnadmin";
$server = "mysql-32792ed8-sreerajmutha-01b8.d.aivencloud.com";
$password = "AVNS_4NXT9Kevg9jAQuVubwG";  // Replace with actual password
$dbname = "defaultdb";
$port = 20576;

// Create database connection
$conc = mysqli_connect($server, $username, $password, $dbname, $port);
if (!$conc) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set up Google Client
$client = new Google\Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID'] ?? null);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET'] ?? null);
$client->setRedirectUri("http://localhost/project/login_with_google.php"); 
$client->addScope("email");
$client->addScope("profile");

if (!isset($_GET['code'])) {
    $loginUrl = $client->createAuthUrl();
    header("Location: $loginUrl");
    exit; 
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    exit('Error fetching access token: ' . htmlspecialchars($token['error']));
}

$client->setAccessToken($token['access_token']);
$oauth = new Google\Service\Oauth2($client);
$userinfo = $oauth->userinfo->get();

$email = $userinfo->email;
$name = $userinfo->name;
$password = "Guest@123"; 
$date = date('Y-m-d H:i:s'); 

$sql = "SELECT * FROM login WHERE email = ?";
$stmt = mysqli_prepare($conc, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // User does not exist, insert them
    $sql = "INSERT INTO login (email, name, password, date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conc, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $email, $name, $password, $date);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['user'] = $name;
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit;
    } else {
        echo "Error inserting user: " . mysqli_error($conc);
    }
} else {
    // User exists, retrieve and store name and email in session
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user'] = $row['name'];
    $_SESSION['email'] = $row['email'];
    header("Location: index.php");
    exit;
}

mysqli_close($conc);
?>
