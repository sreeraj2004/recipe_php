<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/5bafccf36f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <div class="container" id="main">
        <div class="sign-up">
            <form action="./login.php" method="post">
                <h1>Create Account</h1>
                <span class="social-container">
                    <a href="login_with_google.php"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                </span>
                <p class="error-message">
                    <?php echo isset($_GET['signup_error']) ? $_GET['signup_error'] : ''; ?>
                </p>
                <input type="text" placeholder="Name" name="signupname" required>
                <input type="email" placeholder="Email" name="signupemail" required>
                <input type="password" placeholder="Password" name="signuppassword" required>
                <button class="login-btn" name="login-btn" value="signup">Sign Up</button>
                <p class="success-message">
                    <?php echo isset($_GET['signup_success']) ? "Sign-up successful! You can now sign in." : ''; ?>
                </p>
            </form>
        </div>
        
        <div class="sign-in">
            <form action="./login.php" method="post">
                <h1>Sign in</h1>
                <span class="social-container">
                    <a href="login_with_google.php"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                </span>
                <p class="error-message">
                    <?php echo isset($_GET['signin_error']) ? $_GET['signin_error'] : ''; ?>
                </p>
                <input type="email" placeholder="Email" name="signinemail" required>
                <input type="password" placeholder="Password" name="signinpassword" required>
                <a href="forgetPassword.php">Forget your Password?</a>
                <button class="login-btn" name="login-btn" value="signin">Sign In</button>
            </form>
        </div>        

        

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="login-btn" id="signIn" style="border: 3px solid #fff;">Sign In</button>
                </div>
                <div class="overlay-right">
                    <h1>Hello, Friend</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="login-btn" id="signUp" style="border: 3px solid #fff;">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const main = document.getElementById('main');

        signUpButton.addEventListener('click', () => {
            main.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            main.classList.remove('right-panel-active');
        });
    </script>
</body>
</html> -->


<?php
session_start(); // Ensure this is at the top

require __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Aiven MySQL credentials
$username = $_ENV['username'];
$server = $_ENV['server'];
$password = $_ENV['password'];
$dbname = $_ENV['dbname'];
$port = $_ENV['port'];

// Create MySQL connection without SSL for simplicity
$mysqli = new mysqli($server, $username, $password, $dbname, $port);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to validate input
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $button = $_POST['login-btn'];

    if ($button == 'signup') {
        $email = $_POST['signupemail'];
        $name = $_POST['signupname'];
        $pass = $_POST['signuppassword'];

        // Validate input
        $validationError = validate_input($name, $email, $pass);
        if ($validationError) {
            header("Location: login1.php?signup_error=" . urlencode($validationError));
            exit;
        }

        // Check if email already exists
        $checkEmailQuery = "SELECT * FROM login WHERE email = ?";
        $stmt = $mysqli->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: login1.php?signup_error=" . urlencode("Email already exists."));
        } else {
            // Insert new user
            $signupQuery = "INSERT INTO login (name, email, password, date) VALUES (?, ?, ?, current_timestamp())";
            $stmt = $mysqli->prepare($signupQuery);
            $stmt->bind_param("sss", $name, $email, $pass);

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

        // Check credentials
        $signinQuery = "SELECT * FROM login WHERE email = ?";
        $stmt = $mysqli->prepare($signinQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Check password
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/5bafccf36f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <div class="container" id="main">
        <!-- Sign Up Form -->
        <div class="sign-up">
            <form action="login1.php" method="post">
                <h1>Create Account</h1>
                <span class="social-container">
                    <a href="login_with_google.php"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                </span>
                <p class="error-message">
                    <?php echo isset($_GET['signup_error']) ? $_GET['signup_error'] : ''; ?>
                </p>
                <input type="text" placeholder="Name" name="signupname" required>
                <input type="email" placeholder="Email" name="signupemail" required>
                <input type="password" placeholder="Password" name="signuppassword" required>
                <button class="login-btn" name="login-btn" value="signup">Sign Up</button>
                <p class="success-message">
                    <?php echo isset($_GET['signup_success']) ? "Sign-up successful! You can now sign in." : ''; ?>
                </p>
            </form>
        </div>

        <!-- Sign In Form -->
        <div class="sign-in">
            <form action="login1.php" method="post">
                <h1>Sign in</h1>
                <span class="social-container">
                    <a href="login_with_google.php"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                </span>
                <p class="error-message">
                    <?php echo isset($_GET['signin_error']) ? $_GET['signin_error'] : ''; ?>
                </p>
                <input type="email" placeholder="Email" name="signinemail" required>
                <input type="password" placeholder="Password" name="signinpassword" required>
                <a href="forgetPassword.php">Forget your Password?</a>
                <button class="login-btn" name="login-btn" value="signin">Sign In</button>
            </form>
        </div>

        <!-- Overlay (Switch between sign-in and sign-up) -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="login-btn" id="signIn" style="border: 3px solid #fff;">Sign In</button>
                </div>
                <div class="overlay-right">
                    <h1>Hello, Friend</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="login-btn" id="signUp" style="border: 3px solid #fff;">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const main = document.getElementById('main');

        signUpButton.addEventListener('click', () => {
            main.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            main.classList.remove('right-panel-active');
        });
    </script>
</body>
</html>

