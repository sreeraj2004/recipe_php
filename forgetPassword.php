<?php
// Start session to store email temporarily
session_start();

// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "project";

$conc = mysqli_connect($server, $username, $password, $database);

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
                echo "Email sent successfully!";
            } else {
                echo "Email could not be sent.";
            }
        } else {
            echo "User not found";
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
                echo "OTP validated successfully. Proceed with resetting the password.";
            } else {
                echo "Invalid OTP.";
            }
        } else {
            echo "Session expired. Please request a new OTP.";
        }
    }

    if ($button == 'change') {
        $npass = $_POST['new_pass'];
        $cpass = $_POST['confirm_pass'];
        if ($npass != $cpass) {
            echo "Password or confirm password are incorrect";
        } else if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $npass)) {
            echo "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        } else {
            $email = $_SESSION['email'];
            $sql = "UPDATE login SET password = '$npass' WHERE email = '$email'";
            $result = mysqli_query($conc, $sql);
            if ($result) {
                echo "Password changed successfully.";
            } else {
                echo "Failed to change password.";
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
</head>
<body>

<div class="form-slider">
    <div class="slider-container" id="sliderContainer">
        <!-- Slide 1 - Email Form -->
        <div class="slide active" id="slide1">
            <div class="form-wrapper">
                <form action="#" method="post">
                    <h3>Email Submission</h3>
                    <input type="email" placeholder="Enter email..." name="email" required>
                    <input type="submit" name="submit" value="esub">
                </form>
            </div>
        </div>

        <!-- Slide 2 - OTP Form -->
        <div class="slide" id="slide2">
            <div class="form-wrapper">
                <form action="#" method="post">
                    <h3>Enter OTP</h3>
                    <input type="text" placeholder="Enter OTP..." name="otp" required>
                    <input type="submit" name="submit" value="osub">
                </form>
            </div>
        </div>

        <!-- Slide 3 - Password Reset Form -->
        <div class="slide" id="slide3">
            <div class="form-wrapper">
                <form action="#" method="post">
                    <h3>Reset Password</h3>
                    <input type="password" placeholder="New password" name="new_pass" required>
                    <input type="password" placeholder="Confirm password" name="confirm_pass" required>
                    <input type="submit" name="submit" value="change">
                </form>
                <button class="navigation-btn" onclick="window.location.href='login.html'">Go to Login</button>
            </div>
        </div>
    </div>
</div>



</body>
</html>
