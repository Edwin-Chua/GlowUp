<?php
session_start();
include 'connection.php';
include 'header2.php';

// Check if user_id and play_id are provided in URL
if (!isset($_GET['user_id']) || !isset($_GET['play_id'])) {
    header("Location: quiz_history.php");
    exit();
}

$user_id = $_GET['user_id'];
$play_id = $_GET['play_id'];

// Store play_id in session for leaderboard
$_SESSION['play_id'] = $play_id;

// Verify that the logged-in user has permission to view these results
if ($_SESSION['user_id'] != $user_id && $_SESSION['role'] != 'admin') {
    header("Location: 01homepage.php");
    exit();
}

// Get quiz results for this specific play_id
$query = "SELECT 
            ua.*,
            q.question,
            q.A,
            q.B,
            q.C,
            q.D,
            q.correct_answer,
            ug.name as username
          FROM user_answers ua
          JOIN quiz_db q ON ua.quiz_id = q.quiz_id
          JOIN userglowup ug ON ua.user_id = ug.studentid
          WHERE ua.user_id = ? AND ua.play_id = ?
          ORDER BY ua.quiz_id ASC";

$stmt = $connection->prepare($query);
$stmt->bind_param("ii", $user_id, $play_id);
$stmt->execute();
$result = $stmt->get_result();

// Step 1: Get the username from userglowup table using the user_id
$query_name = "SELECT name FROM userglowup WHERE studentid = ?";
$stmt_name = $connection->prepare($query_name);
$stmt_name->bind_param("i", $user_id);  // Bind the user_id to the query
$stmt_name->execute();
$result_name = $stmt_name->get_result();

if ($result_name->num_rows > 0) {
    $row_name = $result_name->fetch_assoc();
    $username = $row_name['name'];  // Fetch the name from the database
} else {
    $username = "Guest";  // Default name if no user found
}
// Step 2: Sum the points based on user_id and play_id
$query_points = "SELECT SUM(points) AS total_points FROM user_answers WHERE user_id = ? AND play_id = ?";
$stmt_points = $connection->prepare($query_points);
$stmt_points->bind_param("ii", $user_id, $play_id);  // Bind both user_id and play_id to the query
$stmt_points->execute();
$result_points = $stmt_points->get_result();

if ($result_points->num_rows > 0) {
    $row_points = $result_points->fetch_assoc();
    $total_points = $row_points['total_points'];  // Fetch the summed points
} else {
    $total_points = 0;  // Default to 0 if no points found for that user and play_id
}

// Step 3: Calculate rank based on total points from all plays
$query_all_points = "SELECT 
    ua.user_id,
    ua.play_id,
    SUM(ua.points) as total_points,
    ug.name
    FROM user_answers ua
    JOIN userglowup ug ON ua.user_id = ug.studentid
    GROUP BY ua.user_id, ua.play_id
    HAVING SUM(ua.points) >= (
        SELECT SUM(points) 
        FROM user_answers 
        WHERE user_id = ? AND play_id = ?
    )
    ORDER BY total_points DESC";

$stmt_all_points = $connection->prepare($query_all_points);
$stmt_all_points->bind_param("ss", $user_id, $play_id);
$stmt_all_points->execute();
$result_all_points = $stmt_all_points->get_result();

// The rank will be the number of rows returned
$rank = $result_all_points->num_rows;

// Get current user's points
$query_user_points = "SELECT SUM(points) as user_points 
                     FROM user_answers 
                     WHERE user_id = ? AND play_id = ?";
$stmt_user_points = $connection->prepare($query_user_points);
$stmt_user_points->bind_param("ss", $user_id, $play_id);
$stmt_user_points->execute();
$result_user_points = $stmt_user_points->get_result();
$row_user_points = $result_user_points->fetch_assoc();
$user_points = $row_user_points['user_points'] ?? 0;

// Step 4: Count the correct and wrong answers
$correct_count = 0;
$wrong_count = 0;

$query_answers = "SELECT is_correct FROM user_answers WHERE user_id = ? AND play_id = ? LIMIT 10";
$stmt_answers = $connection->prepare($query_answers);
$stmt_answers->bind_param("ii", $user_id, $play_id);
$stmt_answers->execute();
$result_answers = $stmt_answers->get_result();

while ($row = $result_answers->fetch_assoc()) {
    if ($row['is_correct'] == 1) {
        $correct_count++;  // Count correct answers
    } else {
        $wrong_count++;  // Count wrong answers
    }
}

// Step 5: Fetch the quiz questions and answers for review
$query_review = "SELECT q.question, q.A, q.B, q.C, q.D, ua.selected_answer, ua.is_correct, q.correct_answer 
                 FROM user_answers ua
                 JOIN quiz_db q ON ua.quiz_id = q.quiz_id
                 WHERE ua.user_id = ? AND ua.play_id = ?
                 ORDER BY ua.quiz_id ASC";
$stmt_review = $connection->prepare($query_review);
$stmt_review->bind_param("ii", $user_id, $play_id);
$stmt_review->execute();
$result_review = $stmt_review->get_result();

$source = isset($_GET['source']) ? $_GET['source'] : 'quiz';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if the 'quiz_origin' session variable is set
  if (isset($_SESSION['quiz_origin'])) {
      $origin_page = $_SESSION['quiz_origin'];
  } else {
      // Default page if no origin is found
      $origin_page = '01homepage.php';
  }

  // Redirect to the original page the user came from
  header("Location: $origin_page");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz Result</title>
  <link rel="stylesheet" href="css/quizResult.css">
</head>
<body>

  <!-- Main Container -->
  <div class="container">
    <section class="summary">
        <div class="summary-container">
          <h2>Summary</h2>
          <p class="subtitle">You're glowing with knowledge!</p>
      
          <!-- Points and Rank Section -->
          <div class="summary-container2">
            <!-- Points and Rank -->
            <div class="group-parent">
              <div class="points-parent">
                <div class="points">
                  <img class="icon1" alt="Points Icon" src="icons/point.svg">
                  <div class="points1">POINTS</div> 
                  <b class="b"><?php echo number_format($total_points); ?></b>                
                </div>
                <div class="divider"></div>
                <div class="world-rank">
                  <img class="icon1" alt="Rank Icon" src="icons/rank.svg">
                  <div class="rank">RANK</div>
                  <b class="b1">#<?php echo $rank; ?></b>
                </div>
                <p><b class="edwin"><?php echo htmlspecialchars($username); ?></b></p> <!-- Display the username -->              </div>
            </div>
      
            <!-- Summary Info Grid -->
            <div class="summary-info">
              <div class="completion-parent">
                <div class="completion">Completion</div>
                <b class="b3">100%</b>
              </div>
              <div class="total-question-parent">
                <div class="total-question">Total Question</div>
                <b class="b2">10</b>
              </div>
              <div class="correct-parent">
                  <div class="correct">Correct</div>
                  <img class="icon" alt="Correct Icon" src="icons/correct.svg">
                  <b class="number"><?= $correct_count ?></b>  <!-- PHP to display correct count -->
              </div>
              <div class="wrong-parent">
                  <div class="wrong">Wrong</div>
                  <img class="icon" alt="Wrong Icon" src="icons/wrong.svg">
                  <b class="number"><?= $wrong_count ?></b>  <!-- PHP to display wrong count -->
              </div>
            </div>
      
            <!-- Buttons -->
            <div class="summary-actions">
              <form method="POST">
                  <button class="btn">
                      <img src="icons/return.svg" alt="Return Icon" class="return-icon">
                      <span class="return-text">Return</span>
                  </button>
              </form>              
              <form action="quiz.php" method="GET">
                  <input type="hidden" name="new_quiz" value="1">
                  <button type="submit" class="btn">
                      <img src="icons/playAgain.svg" alt="Play Again Icon" class="playagain-icon">
                      <span class="playagain-text">Play Again</span>
                  </button>
              </form>              
              <form method="POST">     
                  <button class="btn">
                      <img src="icons/next.svg" alt="Next Icon" class="next-icon">
                      <span class="next-text">Next</span>
                  </button>              
              </form>    
            </div>
      
            <!-- Leaderboard -->
            <div class="leaderboard-container">
                <a href="leaderboard.php">
                    <button class="leaderboard-btn">Leaderboard</button>
                </a>
            </div>          
          </div>
        </div>
    </section>

    <section class="review-questions-section">
        <h2 class="review-title">Review Questions</h2>
        <p class="review-subtitle">You're glowing with knowledge!</p>
        <div class="review-items">
            <?php
            // Reset the result pointer
            $stmt_review->execute();
            $result_review = $stmt_review->get_result();
            
            $question_number = 1;
            while ($row_review = $result_review->fetch_assoc()) {
                echo '<div class="review-item">';
                echo '<h3>Question ' . $question_number . ': ' . htmlspecialchars($row_review['question']) . '</h3>';
                echo '<div class="answers">';
                
                // Loop through options (A, B, C, D)
                $options = ['A', 'B', 'C', 'D'];
                foreach ($options as $option) {
                    $is_selected = ($row_review['selected_answer'] == $option);
                    $is_correct_answer = ($option == $row_review['correct_answer']);
                    
                    $class = '';
                    if ($is_selected) {
                        $class = ($row_review['is_correct'] == 1) ? 'correct' : 'wrong';
                    } elseif ($is_correct_answer && $row_review['is_correct'] == 0) {
                        $class = 'correct-answer'; // Show the correct answer if user got it wrong
                    }
                    
                    echo '<div class="answer ' . $class . '">';
                    if ($is_selected) {
                        echo '<img src="icons/' . ($row_review['is_correct'] == 1 ? 'correctAns' : 'wrongAns') . '.svg" alt="Answer Icon">';
                    }
                    echo '<span class="option-label">' . $option . ': </span>';
                    echo htmlspecialchars($row_review[$option]);
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
                $question_number++;
            }
            ?>
        </div>

        <div class="home-button">
            <button class="home-btn" onclick="goToHomePage()">
                <img src="icons/home_quizresult.svg" alt="Home Icon">
            </button>
        </div>

        <script>
            function goToHomePage() {
                <?php if ($source === 'dashboard'): ?>
                    window.location.href = '01dashboard.php';
                <?php else: ?>
                    window.location.href = '01homepage.php';
                <?php endif; ?>
            }
        </script>
    </section>

  </div>
  
<?php include('userfooter.php'); ?>

</body>
</html>
