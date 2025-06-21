<?php
// Include the database connection
include 'connection.php';

// Include the header
include('adminheader.php');

// Fetch the feedback data from the database
$query = "SELECT * FROM feedback";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback</title>
    <link rel="stylesheet" href="css/userFeedbackList.css">
</head>
<body>

    <h1>User Feedback</h1>
    <div class="container">
        <div class="feedback-section">
            <h2>Feedback</h2>

            <!-- Feedback table -->
            <table>
                <thead>
                    <tr>
                        <th>USER NAME</th>
                        <th>SUBJECT</th>
                        <th>DATE</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are feedback entries in the database
                    if (mysqli_num_rows($result) > 0) {
                        // Output the feedback data
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['subject']}</td>
                                    <td>{$row['feedback_date']}</td>
                                    <td><a href='userFeedback.php?id={$row['feedbackid']}'>View Details</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No feedback available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);

// Include the footer
include('adminfooter.php');
?>
