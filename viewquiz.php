<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "glowup";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['quiz_id'])) {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
        exit;
    }

    $quizId = $conn->real_escape_string($data['quiz_id']);

    $sql = "DELETE FROM quiz_db WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quizId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Question deleted"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting question: " . $stmt->error]);
    }

    $conn->close();
    exit;
}

// Fetch all quiz questions
$sql = "SELECT * FROM quiz_db";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Quiz</title>
    <link rel="stylesheet" href="css/viewquiz.css">
</head>
<?php
        include 'adminheader.php'
    ?>
<body>

    <h2>Quiz Questions</h2>
         <!-- Topic Section -->
         <div class="topic-card">
            <div class="topic-title">Nutrition</div>
            <div class="topic-description">
                Discover how a balanced diet can fuel your body with the right nutrients for optimal performance.
            </div>
        </div>
    
    <div class="quiz-container">
        <?php if ($result->num_rows > 0): ?>
            <?php 
            $questionNumber = 1;
            while ($row = $result->fetch_assoc()): ?>
                <div class="quiz-item">
                    <p class="question">
                        <strong>Question <?= $questionNumber ?>:</strong> <?= htmlspecialchars($row['question']) ?>
                        <span class="points">(5 Points)</span>
                        <button class="delete-btn" onclick="deleteQuestion(<?= $row['quiz_id'] ?>, this)">Delete</button>
                    </p>
                    <ul class="answers">
                        <li <?= ($row['correct_answer'] == "A") ? 'class="correct"' : '' ?>>A. <?= htmlspecialchars($row['A']) ?></li>
                        <li <?= ($row['correct_answer'] == "B") ? 'class="correct"' : '' ?>>B. <?= htmlspecialchars($row['B']) ?></li>
                        <li <?= ($row['correct_answer'] == "C") ? 'class="correct"' : '' ?>>C. <?= htmlspecialchars($row['C']) ?></li>
                        <li <?= ($row['correct_answer'] == "D") ? 'class="correct"' : '' ?>>D. <?= htmlspecialchars($row['D']) ?></li>
                    </ul>
                </div>
                <?php $questionNumber++; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No quiz questions found.</p>
        <?php endif; ?>
    </div>

    <div class="sticky-add-btn">
        <button class="add-btn" onclick="window.location.href='manage.php'">Add Question</button>
    </div>

    <script>
    function deleteQuestion(quizId, buttonElement) {
        console.log("Deleting quiz_id:", quizId); // Debugging log
    
        fetch("viewquiz.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ quiz_id: quizId })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Server Response:", data); // Debugging log
            if (data.success) {
                alert("Question deleted successfully!");
                buttonElement.closest(".quiz-item").remove(); // Remove from UI
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Fetch Error:", error));
    }
    </script>
        <?php
    include 'adminfooter.php'
    ?>
</body>
</html>

<?php
$conn->close();
?>
