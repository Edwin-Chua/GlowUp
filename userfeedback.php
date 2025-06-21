<?php
// Include the database connection
include 'connection.php';

// Get the feedback ID from URL
$feedbackId = isset($_GET['id']) ? $_GET['id'] : null;


if ($feedbackId) {
    // Prepare and execute query to get specific feedback
    $query = "SELECT * FROM feedback WHERE feedbackid = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $feedbackId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $feedback = mysqli_fetch_assoc($result);
} else {
    // Redirect if no ID provided
    header("Location: userFeedbackList.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback - GlowUp</title>
    <link rel="stylesheet" href="css/headstyle.css">
    <link rel="stylesheet" href="css/footstyle.css">
    <link rel="stylesheet" href="css/userfeedback.css">
</head>
<body>
    <?php include 'adminheader.php'; ?>

    <div class="container">
        <h1>User Feedback Details</h1>

        <?php if ($feedback): ?>
        <div class="feedback-card">
            <div class="user-info">
                <div class="user-avatar">
                <img src="icons/profile.svg" alt="User Profile" width="80" height="80">
                </div>
                <div>
                    <h3><?php echo htmlspecialchars($feedback['name']); ?></h3>
                    <small><?php echo date('M d, Y H:i', strtotime($feedback['feedback_date'])); ?></small>
                </div>
                <div class="rating">
                    <?php 
                    $rating = $feedback['rating'];
                    echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                    ?>
                </div>
            </div>
            <h4>Subject: <?php echo htmlspecialchars($feedback['subject']); ?></h4>
            <p><?php echo htmlspecialchars($feedback['message']); ?></p>
        </div>
        <?php else: ?>
        <div class="feedback-card">
            <p>Feedback not found.</p>
        </div>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="userFeedbackList.php" class="back-link">
                &larr; Back to Feedback List
            </a>
        </div>
    </div>

    <?php include 'adminfooter.php'; ?>
</body>
</html>
<?php
// Close the database connection
mysqli_close($connection);
?> 
