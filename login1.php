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
</html>
