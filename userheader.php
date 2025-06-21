<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'connection.php';

// Check if the user is logged in by verifying the session
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];  // Fetch the username from the session
    $role = $_SESSION['role'];          // Fetch the role from the session (admin/user)
} else {
    $username = "Guest";  // Default if no user is logged in
    $role = "guest";      // Default role for guest
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="css/headstyle.css"> 
</head>
<body>
    <header>
        <div class="top-row">
            <div class="logo">
                <a href="01homepage.php">
                    <img src="icons/TopLogo.svg" alt="Logo">
                </a>
            </div>
        </div>
        <div class="divider"></div>
        <nav class="nav">
            <a href="01homepage.php" class="nav-item">Quiz Selection</a>
            <a href="quiz_history.php" class="nav-item">Quiz History</a>
            <a href="aboutus.php" class="nav-item">About Us</a>
            <a href="07feedback.php" class="nav-item">Feedback</a>
            <div class="profile">
                <!-- Display username and profile icon -->
                <span class="username"><?php echo $username; ?></span>
                <a href="javascript:void(0);" onclick="openLogoutPopup()">
                <img src="icons/icon-profile.svg" alt="Profile Icon"></a>
            </div>
        </nav>
    </header>

    <!-- Pop-up Modal for Logout Confirmation -->
    <div id="logoutPopup" class="popup-container">
        <div class="popup-box">
            <img src="icons/icon-logout.svg" alt="Exit icon" class="popup-icon">
            <h2>Log Out</h2>
            <p>Are you sure you want to log out?</p>
            <button onclick="logoutUser()">Yes, Log Out</button>
            <img src="icons/icon-close.svg" alt="Close" class="popup-close" onclick="closePopup()">
        </div>
    </div>

    <script src="js/logout_popup.js"></script>
</body>
</html>

