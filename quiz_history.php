<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'];

include 'connection.php';

// Fetch unique play_ids and their latest completion
$sql = "SELECT play_id, MAX(completed_at) AS completed_at
        FROM user_answers
        WHERE user_id=?
        GROUP BY play_id
        ORDER BY completed_at DESC";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$quiz_history = [];
while ($row = $result->fetch_assoc()){
    $quiz_history[] = $row;
}

$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz History</title>
    <link rel="stylesheet" href="css/quiz_history.css">

</head>

<body>    
    <?php include 'userheader.php'; ?>
    <div class="quiz-history-bg">
        <h1>Quiz History</h1>
        <div class="quiz-history-grid">
            <?php 
            if (!empty($quiz_history)){
                foreach ($quiz_history as $row){
                    $timestamp = strtotime($row['completed_at']);
                    if ($timestamp){
                        $time = date("h:i A",$timestamp);
                        $date = date("Y-m-d", $timestamp);
                        echo '
                        <div class="quiz-history-section">
                            <div class="quiz-timestamp">
                                <span class="time">' . htmlspecialchars($time) . '</span>
                                <span class="date">' . htmlspecialchars($date) . '</span>
                            </div>
                            <a href="quizResult.php?user_id=' . $user_id . '&play_id=' . $row['play_id'] . '" class="play-btn">
                                <div class="play-btn-icon"></div>
                            </a>
                        </div>';
                    
                    } 
                } 
            }
            ?>
        </div>
    </div>
   <footer>
            <?php include 'userfooter.php'?>
   </footer>
</body>
</html>