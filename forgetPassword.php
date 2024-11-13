<?php
// Start session to store email temporarily
session_start();
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Database connection
$username = $_ENV['username'];
$server = $_ENV['server'];
$password = $_ENV['password'];  // Replace with actual password
$dbname = $_ENV['dbname'];
$port = $_ENV['port'];

// Create connection with the updated database details
$conc = mysqli_connect($server, $username, $password, $dbname, $port);

$success_message = $error_message = ""; // Message variables

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $button = $_POST['submit'];

    // Handle email submission (for OTP generation)
    if ($button == 'esub') {
        $email = $_POST['email'];
        $sql = "SELECT * FROM login WHERE email = '$email'";
        $result = mysqli_query($conc, $sql);
        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $_SESSION['email'] = $email;

            // Generate OTP and send email
            $random = rand(1000, 9999);
            $sql = "UPDATE login SET reset_token = '$random' WHERE email = '$email'";
            mysqli_query($conc, $sql);

            // Send OTP to the user
            $to = $email;
            $sub = "Update Password Mail";
            $message = "You can use this OTP $random to reset your password.";
            $from = "sreerajmutha@gmail.com";
            $headers = "From: $from";

            $check = mail($to, $sub, $message, $headers);
            if ($check) {
                $success_message = "Email sent successfully!";
            } else {
                $error_message = "Email could not be sent.";
            }
        } else {
            $error_message = "User not found";
        }
    }

    // Handle OTP submission (for password reset)
    if ($button == 'osub') {
        $otp = $_POST['otp'];

        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $sql = "SELECT * FROM login WHERE email = '$email' AND reset_token = '$otp'";
            $result = mysqli_query($conc, $sql);
            if (mysqli_num_rows($result) == 1) {
                $success_message = "OTP validated successfully. Proceed with resetting the password.";
            } else {
                $error_message = "Invalid OTP.";
            }
        } else {
            $error_message = "Session expired. Please request a new OTP.";
        }
    }

    if ($button == 'change') {
        $npass = $_POST['new_pass'];
        $cpass = $_POST['confirm_pass'];
        if ($npass != $cpass) {
            $error_message = "Password and confirm password do not match.";
        } else if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $npass)) {
            $error_message = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        } else {
            $email = $_SESSION['email'];
            $sql = "UPDATE login SET password = '$npass' WHERE email = '$email'";
            $result = mysqli_query($conc, $sql);
            if ($result) {
                $success_message = "Password changed successfully.";
            } else {
                $error_message = "Failed to change password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        /* Reset Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #e9ecef;
            padding: 20px;
        }

        /* Main Container */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            max-width: 400px;
            width: 100%;
        }

        /* Form Wrapper */
        form {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            text-align: center;
            transition: transform 0.3s ease;
        }

        form:hover {
            transform: translateY(-5px);
        }

        /* Headings */
        h3 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Input Fields */
        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            color: #555;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Submit Button */
        input[type="submit"],
        .navigation-btn {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        .navigation-btn:hover {
            background-color: #218838;
        }

        /* Message Styles */
        .success-message {
            color: #28a745;
            font-weight: bold;
        }

        .error-message {
            color: #dc3545;
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 500px) {
            .container {
                width: 90%;
            }

            form {
                padding: 20px;
            }

            h3 {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div>
        <form action="#" method="post">
            <h3>Email Submission</h3>
            <input type="email" placeholder="Enter email..." name="email" required>
            <input type="submit" name="submit" value="esub">
            <?php if ($success_message && $button == 'esub') echo "<p class='success-message'>$success_message</p>"; ?>
            <?php if ($error_message && $button == 'esub') echo "<p class='error-message'>$error_message</p>"; ?>
        </form>
    </div>

    <div>
        <form action="#" method="post">
            <h3>Enter OTP</h3>
            <input type="text" placeholder="Enter OTP..." name="otp" required>
            <input type="submit" name="submit" value="osub">
            <?php if ($success_message && $button == 'osub') echo "<p class='success-message'>$success_message</p>"; ?>
            <?php if ($error_message && $button == 'osub') echo "<p class='error-message'>$error_message</p>"; ?>
        </form>
    </div>

    <div>
        <form action="#" method="post">
            <h3>Reset Password</h3>
            <input type="password" placeholder="New password" name="new_pass" required>
            <input type="password" placeholder="Confirm password" name="confirm_pass" required>
            <input type="submit" name="submit" value="change">
            <?php if ($success_message && $button == 'change') echo "<p class='success-message'>$success_message</p>"; ?>
            <?php if ($error_message && $button == 'change') echo "<p class='error-message'>$error_message</p>"; ?>
        </form>
        <button class="navigation-btn" onclick="window.location.href='login.html'">Go to Login</button>
    </div>
</div>

</body>
</html>
