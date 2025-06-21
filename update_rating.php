<?php
$servername = "localhost";
$username = "root";
$password = "";                                
$dbname = "glowup";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if data is received from AJAX request
if (isset($_POST['topic']) && isset($_POST['rating'])) {
    $topic = $_POST['topic'];
    $rating = intval($_POST['rating']); // Ensure it's an integer

    if ($rating >= 1 && $rating <= 5) {
        $column = "rating_" . $rating; // Selects the right rating column

        // Update the rating count for the selected topic
        $sql = "UPDATE ratings SET $column = $column + 1 WHERE topic = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $topic);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "SQL Error: " . $conn->error; // Debugging line
        }
        $stmt->close();
    } else {
        echo "invalid_rating";
    }
} else {
    echo "no_data";
}

?>
