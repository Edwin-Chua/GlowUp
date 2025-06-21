<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - GlowUp</title>
    <link rel="stylesheet" href="css/leaderboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php 
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        // Redirect to login page if not logged in
        header("Location: login.php");
        exit();
    }

    include 'header2.php';
    include 'connection.php';
    
    // Get current user's info
    $current_user_id = $_SESSION['user_id'];
    $current_user_name = $_SESSION['username'];
    $current_user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';

    // Get quiz session points for current user
    $current_quiz_points = 0;
    if (isset($_SESSION['play_id'])) {
        $play_id = $_SESSION['play_id'];
        $quiz_points_query = "SELECT SUM(points) as quiz_points 
                             FROM user_answers 
                             WHERE user_id = ? 
                             AND play_id = ?";
        $stmt = $connection->prepare($quiz_points_query);
        $stmt->bind_param("ii", $current_user_id, $play_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $current_quiz_points = $row['quiz_points'] ?? 0;
        }
    }

    // Get total points for all users - summing up all scores across all play sessions
    $query = "SELECT 
                ua.user_id, 
                CASE 
                    WHEN ag.name IS NOT NULL THEN ag.name
                    WHEN ug.name IS NOT NULL THEN ug.name
                    ELSE CONCAT('User ', ua.user_id)
                END as name,
                (SELECT SUM(points) 
                 FROM user_answers 
                 WHERE user_id = ua.user_id) as total_points 
              FROM user_answers ua 
              LEFT JOIN userglowup ug ON ua.user_id = ug.studentid 
              LEFT JOIN adminglowup ag ON ua.user_id = ag.adminid
              GROUP BY ua.user_id, 
                       CASE 
                           WHEN ag.name IS NOT NULL THEN ag.name
                           WHEN ug.name IS NOT NULL THEN ug.name
                           ELSE CONCAT('User ', ua.user_id)
                       END
              HAVING total_points > 0
              ORDER BY total_points DESC";
    
    $result = mysqli_query($connection, $query);
    $leaderboard = array();
    $current_user_rank = 0;
    $current_user_points = 0;
    $rank = 1;
    
    while($row = mysqli_fetch_assoc($result)) {
        $leaderboard[] = $row;
        if($row['user_id'] == $current_user_id) {
            $current_user_rank = $rank;
            $current_user_points = $row['total_points'];
        }
        $rank++;
    }

    // If current user is not in leaderboard (no points yet)
    if($current_user_rank == 0) {
        $current_user_points = 0;
        $current_user_rank = count($leaderboard) + 1;
    }
    ?>

    <div class="leaderboard-container">
        <div class="leaderboard-card">
            <h1>Leaderboard</h1>
            <p class="subtitle">You're glowing with knowledge!</p>

            <div class="leaderboard-content">
                <!-- Current User Stats -->
                <div class="current-user-stats">
                    <h2><?php echo htmlspecialchars($current_user_name); ?></h2>
                    <div class="stats-container">
                        <div class="stat">
                            <img src="icons/star.svg" alt="Points">
                            <div class="stat-details">
                                <span class="label">QUIZ POINTS</span>
                                <span class="value"><?php echo number_format($current_quiz_points); ?></span>
                            </div>
                        </div>
                        <div class="stat">
                            <img src="icons/globe.svg" alt="Rank">
                            <div class="stat-details">
                                <span class="label">RANK</span>
                                <span class="value">#<?php echo $current_user_rank; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Podium Section -->
                <div class="podium-section">
                    <div class="podium-container">
                        <!-- Second Place -->
                        <div class="podium-item second">
                            <div class="user-avatar">
                                <img src="icons/profile.svg" alt="Second">
                            </div>
                            <span class="username"><?php echo isset($leaderboard[1]) ? htmlspecialchars($leaderboard[1]['name']) : 'N/A'; ?></span>
                            <span class="score"><?php echo isset($leaderboard[1]) ? number_format($leaderboard[1]['total_points']) . ' QP' : '0 QP'; ?></span>
                        </div>

                        <!-- First Place -->
                        <div class="podium-item first">
                            <div class="crown">👑</div>
                            <div class="user-avatar">
                                <img src="icons/profile.svg" alt="First">
                            </div>
                            <span class="username"><?php echo isset($leaderboard[0]) ? htmlspecialchars($leaderboard[0]['name']) : 'N/A'; ?></span>
                            <span class="score"><?php echo isset($leaderboard[0]) ? number_format($leaderboard[0]['total_points']) . ' QP' : '0 QP'; ?></span>
                        </div>

                        <!-- Third Place -->
                        <div class="podium-item third">
                            <div class="user-avatar">
                                <img src="icons/profile.svg" alt="Third">
                            </div>
                            <span class="username"><?php echo isset($leaderboard[2]) ? htmlspecialchars($leaderboard[2]['name']) : 'N/A'; ?></span>
                            <span class="score"><?php echo isset($leaderboard[2]) ? number_format($leaderboard[2]['total_points']) . ' QP' : '0 QP'; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Other Rankings -->
                <div class="other-rankings">
                    <?php
                    // Display ranks 4-6
                    for($i = 3; $i < min(6, count($leaderboard)); $i++) {
                        $rank = $i + 1;
                        ?>
                        <div class="ranking-card">
                            <span class="rank"><?php echo $rank; ?></span>
                            <div class="user-avatar">
                                <img src="icons/profile.svg" alt="<?php echo htmlspecialchars($leaderboard[$i]['name']); ?>">
                            </div>
                            <div class="user-info">
                                <span class="username"><?php echo htmlspecialchars($leaderboard[$i]['name']); ?></span>
                                <span class="score"><?php echo number_format($leaderboard[$i]['total_points']); ?> points</span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'adminfooter.php'; ?>
</body>
</html>
