<?php
session_start();
include 'connection.php';

$loginError = $signupError = $signupSuccess = "";

// Sign-In
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnLogin'])) {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    // Check if user is in adminglowup table (admin login)
    $queryAdmin = "SELECT * FROM adminglowup WHERE email = '$email' AND password = '$password'";
    $resultAdmin = mysqli_query($connection, $queryAdmin);

    // Check if user is in userglowup table (regular user login)
    $queryUser = "SELECT * FROM userglowup WHERE email = '$email' AND password = '$password'";
    $resultUser = mysqli_query($connection, $queryUser);

    // Admin login check
    if ($row = mysqli_fetch_assoc($resultAdmin)) {
        $_SESSION['user_id'] = $row['adminid'];  
        $_SESSION['username'] = $row['name'];   
        $_SESSION['role'] = 'admin';             
        header("Location: 01dashboard.php");

    // Regular user login check
    } elseif ($row = mysqli_fetch_assoc($resultUser)) {
        $_SESSION['user_id'] = $row['studentid']; 
        $_SESSION['username'] = $row['name'];     
        $_SESSION['role'] = 'user';               
        header("Location: 01homepage.php");

    } else {
        $loginError = "Invalid email or password!";
    }
}

// Sign-Up (Only for userglowup)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSignUp'])) {
    $username = $_POST['signupUsername'];
    $email = $_POST['signupEmail'];
    $password = $_POST['signupPassword'];

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM userglowup WHERE email = '$email'";
    $checkEmailResult = mysqli_query($connection, $checkEmailQuery);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        $signupError = "This email is already registered. Please use another.";
    } else {
        $insertQuery = "INSERT INTO userglowup (name, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($connection, $insertQuery)) {
            $signupSuccess = "Sign-up successful. You can now log in.";
        } else {
            $signupError = "Error: " . mysqli_error($connection);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Login Page</title>
</head>
<body>
    <div class="container">
        <div class="form-container sign-up">
            <form method="post" action="">
                <h1>Create Account</h1>
                <div class="social-icon">
                    <a href="#" class="icon"><i class="fa-brands fa-google"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin"></i></a>
                </div>
                <span>or use your email for registration</span>
                <?php if (!empty($signupError)): ?>
                    <p style="color: red; font-size: 14px;"> <?php echo $signupError; ?> </p>
                <?php elseif (!empty($signupSuccess)): ?>
                    <p style="color: green; font-size: 14px;"> <?php echo $signupSuccess; ?> </p>
                <?php endif; ?>
                <input type="text" name="signupUsername" placeholder="Name" required>
                <input type="email" name="signupEmail" placeholder="Email" required>
                <input type="password" name="signupPassword" placeholder="Password" required>
                <button type="submit" name="btnSignUp">Sign Up</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <form method="post" action="">
                <h1>Sign In</h1>
                <div class="social-icon">
                    <a href="#" class="icon"><i class="fa-brands fa-google"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin"></i></a>
                </div>
                <span>or use your email password</span>
                <?php if (!empty($loginError)): ?>
                    <p style="color: red; font-size: 14px;"> <?php echo $loginError; ?> </p>
                <?php endif; ?>
                <input type="email" name="loginEmail" placeholder="Email" required>
                <input type="password" name="loginPassword" placeholder="Password" required>
                <a href="">Forget Your Password?</a>
                <button type="submit" name="btnLogin">Sign In</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>     
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div> 
            </div>
        </div>
    </div>

    <script src="js/login.js"></script>
</body>
</html>