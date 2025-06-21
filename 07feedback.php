<?php
session_start(); // Start the session at the beginning of the script

// Database connection settings
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "glowup"; 

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Debugging: Uncomment to check session variables
// var_dump($_SESSION);

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Use logged-in user's name
} else {
    $username = "Guest"; // Default to Guest if not logged in
}

// Check if the feedback form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $rating = $_POST['rating']; // Rating value from JavaScript

    // Insert feedback into the database
    $query = "INSERT INTO feedback (name, subject, message, rating) 
              VALUES ('$username', '$subject', '$message', $rating)";
    if ($connection->query($query) === TRUE) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error: " . $connection->error;
    }

    header('Location: feedbackConfirm.php');
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link rel="stylesheet" href="css/07feedback.css">
</head>
<body>
    <?php include 'userheader.php'; ?>

    <form action="07feedback.php" method="POST">
        <h1>Feedback</h1>
        <h2>We value your opinion! Please share your thoughts below.</h2>
        <fieldset>
            <label for="subject">Subject</label>
            <input type="text" class="subject" id="subject" name="subject" placeholder="Enter Subject" required>

            <label for="message">Message</label>
            <textarea class="message" id="message" name="message" placeholder="Write your message here" required></textarea>

            <!-- Rating Section -->
            <div class="rating-section">
                <h2>Rate this topic!</h2>
                <div class="rating-box">
                    Please rate your experience!
                    <div class="stars-container">
                        <img src="icons/icon-starhalf.svg" alt="Star" class="star" data-value="1">
                        <img src="icons/icon-starhalf.svg" alt="Star" class="star" data-value="2">
                        <img src="icons/icon-starhalf.svg" alt="Star" class="star" data-value="3">
                        <img src="icons/icon-starhalf.svg" alt="Star" class="star" data-value="4">
                        <img src="icons/icon-starhalf.svg" alt="Star" class="star" data-value="5">
                    </div>
                </div>
            </div>

            <!-- Hidden Rating Value -->
            <input type="hidden" name="rating" id="rating" value="0"> <!-- Rating will be set here by JS -->

            <!-- Send Message Button -->
            <button type="submit" class="send-button">Send Message</button>
        </fieldset>
    </form>

    <script src="js/feedback_rating.js"></script>

    <?php include 'userfooter.php'; ?>
</body>
</html>