<?php
session_start();
include 'connection.php';

// Ensure user is logged in and user_id is available
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get user id

// Check if this is a new quiz start
if (isset($_GET['new_quiz'])) {
    // Reset all quiz-related session variables
    $_SESSION['play_id'] = time();
    $_SESSION['current_question'] = 1;
    unset($_SESSION['quiz_ids']);
    unset($_SESSION['totalQuestions']);
}

// Initialize play_id if not set
if (!isset($_SESSION['play_id'])) {
    $_SESSION['play_id'] = time();
    $_SESSION['current_question'] = 1; // Initialize question counter
}

$play_id = $_SESSION['play_id']; // Make sure play_id is available for later use

// Get total number of questions from database
$total_questions_query = "SELECT COUNT(*) as total FROM quiz_db";
$total_result = $connection->query($total_questions_query);
$total_row = $total_result->fetch_assoc();
$total_questions = $total_row['total'];

// Set the maximum number of questions for the quiz
$_SESSION['totalQuestions'] = $total_questions;

// Fetch all quiz ids if not already stored in session
if (!isset($_SESSION['quiz_ids'])) {
    $quiz_id_list = [];
    $sql = "SELECT quiz_id FROM quiz_db ORDER BY quiz_id ASC";
    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        $quiz_id_list[] = $row['quiz_id'];
    }
    
    $_SESSION['quiz_ids'] = $quiz_id_list;
}

// Initialize current question if not set
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 1;
}

// Check if we've reached beyond the last question
if ($_SESSION['current_question'] > $_SESSION['totalQuestions']) {
    header("Location: quizResult.php");
    exit();
}

$current_question = $_SESSION['current_question'];
$current_quiz_id = $_SESSION['quiz_ids'][$current_question - 1]; // Get the current quiz_id from the list

// Fetch the current question using quiz_id
$query = "SELECT * FROM quiz_db WHERE quiz_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $current_quiz_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No more questions, redirect to results
    header("Location: quizResult.php");
    exit();
}

$question = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer'])) {
    $selected_answer = $_POST['answer'];
    $is_correct = ($selected_answer === $question['correct_answer']) ? 1 : 0;
    $points = $is_correct ? $question['points'] : 0; // Use points from the question
    
    // Save the answer
    $save_query = "INSERT INTO user_answers (user_id, play_id, quiz_id, selected_answer, is_correct, points) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    $save_stmt = $connection->prepare($save_query);
    $save_stmt->bind_param("iiisii", 
        $_SESSION['user_id'],
        $_SESSION['play_id'],
        $current_quiz_id, // Use current_quiz_id instead of question['quiz_id']
        $selected_answer,
        $is_correct,
        $points
    );
    
    if (!$save_stmt->execute()) {
        // If there's an error, log it
        error_log("Error saving answer: " . $save_stmt->error);
    }
    $save_stmt->close();

    // Increment question counter
    $_SESSION['current_question']++;

    // Check if this was the last question
    if ($_SESSION['current_question'] > $_SESSION['totalQuestions']) {
        header("Location: quizResult.php");
        exit();
    } else {
        header("Location: quiz.php");
        exit();
    }
}

// Add this after session_start()
if (isset($_POST['action']) && $_POST['action'] === 'previous') {
    if ($_SESSION['current_question'] > 1) {
        $_SESSION['current_question']--;
        
        // Delete the previous answer for this question
        $current_quiz_id = $_SESSION['quiz_ids'][$_SESSION['current_question'] - 1];
        $delete_query = "DELETE FROM user_answers 
                        WHERE user_id = ? 
                        AND play_id = ? 
                        AND quiz_id = ?";
        $delete_stmt = $connection->prepare($delete_query);
        $delete_stmt->bind_param("iii", 
            $_SESSION['user_id'],
            $_SESSION['play_id'],
            $current_quiz_id
        );
        $delete_stmt->execute();
        header("Location: quiz.php");
        exit();
    }
}

$connection->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Interface</title>
    <link rel="stylesheet" href="css/quiz.css">
</head>
<?php include 'header2.php'; ?>
<body>
    <!-- store play_id in hiden -->
     <span id="play_id" style="display:none";><?php echo $_SESSION['play_id'];?></span>
    

    
    <!-- content -->
    <div class="question-bg">
        <div class="question-area">

            <div class="time-circle">
                <h2 id = "minutes">00:00</h2>
            </div>

            <div id="quiz" class="justify-center flex-column">
                <h2 id = "questionNumber">Question 
                    <span id="currentQuestion"><?php echo $current_question;?></span>/
                    <span id = "totalQuestions"><?php echo $_SESSION['totalQuestions'];?></span>
                </h2>
                    <h2 id ="question-text"><?php echo htmlspecialchars($question["question"]);?></h2>

                <!-- hidden correct answer -->
                <span id="correctAnswer" style="display:none;">
                    <?php echo htmlspecialchars($question["correct_answer"]);?>
                </span>

                <!-- hidden Quiz ID -->
                <span id="quizId" style="display:none;">
                    <?php echo $question['quiz_id'];?>
                </span>

                <!-- Answer choices -->
                <?php foreach (["A", "B", "C", "D"] as $option) :?>
                    <div class="choice-container" data-option= "<?php echo $option; ?>">
                        <img class ="result-icon" src="" alt ="Result icon" style = "display: none;">
                        <p class="choice-text"><?php echo htmlspecialchars($question[$option]);?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- button container -->
            <div class ="button-container">
                <img src="icons/icon_Return.svg" alt="Return Button" id="returnBtn">
                <img src="icons/icon_Next.svg" alt="Next Button" id="nextBtn">
            </div>
        </div>
    </div>
   
    <script src="js/quiz.js"></script>
    <script src="js/timer.js"></script>
    
    <!-- Footer -->
    <footer>
        <?php include 'adminfooter.php';?>
    </footer>
    
</body>
</html>