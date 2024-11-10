<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $button = $_POST['login-btn'];
    $username = "root";
    $password = "";
    $host = "localhost";
    $database = "project";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function validate_input($name, $email, $password) {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            return "Only letters and whitespace allowed in name.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }
        if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
            return "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        }
        return "";
    }

    if ($button == 'signup') {
        $email = $_POST['signupemail'];
        $name = $_POST['signupname'];
        $pass = $_POST['signuppassword'];

        $validationError = validate_input($name, $email, $pass);
        if ($validationError) {
            header("Location: login.html?signup_error=" . urlencode($validationError));
            exit;
        }

        $passPlain = $pass; 

        $checkEmailQuery = "SELECT * FROM login WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: login.html?signup_error=" . urlencode("Email already exists."));
        } else {
            $signupQuery = "INSERT INTO login (name, email, password, date) VALUES (?, ?, ?, current_timestamp())";
            $stmt = $conn->prepare($signupQuery);
            $stmt->bind_param("sss", $name, $email, $passPlain); 

            if ($stmt->execute()) {
                header("Location: login.html?signup_success=1");
            } else {
                header("Location: login.html?signup_error=" . urlencode("Sign-up failed. Try again later."));
            }
        }
        $stmt->close();
    } elseif ($button == 'signin') {
        $email = $_POST['signinemail'];
        $pass = $_POST['signinpassword'];

        $signinQuery = "SELECT * FROM login WHERE email = ?";
        $stmt = $conn->prepare($signinQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($pass === $row['password']) {
                $_SESSION['user'] = $row['name'];
                $_SESSION['email'] = $row['email']; // store email for dropdown display
                header("Location: home.php");
            } else {
                header("Location: login.html?signin_error=" . urlencode("Incorrect password."));
            }
        } else {
            header("Location: login.html?signin_error=" . urlencode("No account found with that email."));
        }
        $stmt->close();
    }
    $conn->close();
}
?>
