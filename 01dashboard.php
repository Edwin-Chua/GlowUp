<?php 
include 'connection.php';

//count total users
$query = "SELECT COUNT(*) AS total_users FROM userglowup";
$result = mysqli_query($connection,$query);

//fetch the result
$row = mysqli_fetch_assoc($result);
$total_users = $row['total_users'];

//fetch count of unique play_ids
$sql = "SELECT COUNT(DISTINCT play_id) AS total_play FROM user_answers";
$result = $connection->query($sql);

//get the total plays
$total_plays=0;

if($result->num_rows >0){
    $row = $result->fetch_assoc();
    $total_plays =$row["total_play"];
}

//fetch all recent plays
$query = "SELECT ua.play_id, ua.user_id, MAX(ua.completed_at) AS completed_at, u.name
        FROM user_answers ua
        JOIN userglowup u ON ua.user_id = u.studentid
        GROUP BY ua.play_id, ua.user_id, u.name
        ORDER BY ua.completed_at DESC";

$result = mysqli_query($connection,$query);

//ratings
// Function to fetch ratings for a specific topic
function getRatings($topic, $conn) {
    $sql = "SELECT rating_1, rating_2, rating_3, rating_4, rating_5 FROM ratings WHERE topic = '$topic'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// Fetch ratings for each topic
$nutrition_ratings = getRatings('Nutrition', $connection);
$fitness_ratings = getRatings('Fitness', $connection);
$mental_health_ratings = getRatings('Mental Health', $connection);
$self_care_ratings = getRatings('Self-Care', $connection);

// Calculate total ratings for each topic
function calculateTotalRatings($ratings) {
    return array_sum($ratings);
}

$nutrition_total = calculateTotalRatings($nutrition_ratings);
$fitness_total = calculateTotalRatings($fitness_ratings);
$mental_health_total = calculateTotalRatings($mental_health_ratings);
$self_care_total = calculateTotalRatings($self_care_ratings);

// Close the database connection
$connection->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/01dashboard.css">
</head>

<body>
    <header>
        <?php include 'adminheader.php';?>
    </header>
    
    <div class="dashboard-title">Dashboard</div>

    <div class="container">
        <div class="row">
            <img src="icons\icon_TotalUsers.svg" alt="Total Users Icon" class="icon">
            <div class="raw-content">
                <p>Total Users</p>
            </div>
            <p class="total-count"><?php echo $total_users;?></p>
        </div>

        <div class="row">
            <img src="icons\icon_TotalPlays.svg" alt="Total Users Icon" class="icon">
            <div class="raw-content">
                <p>Total Plays</p>
            </div>
            <p class="total-count"><?php echo $total_plays;?></p>
        </div>

        <div class="row">
            <img src="icons\icon-TotalTopics.svg" alt="Total Users Icon" class="icon">
            <div class="raw-content">
                <p>Total Topics</p>
            </div>
            <p class="total-count">5</p>
        </div>
    </div> 

    <!-- table section -->
    <div class="table-container">
        <h1>Recent Plays</h1>
        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Topic</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)){?>
                    <tr>
                        <td><?php echo $row['name'];?></td>
                        <td>Nutrition</td>
                        <td><?php echo $row['completed_at'];?></td>
                        <td><a href="quizResult.php?user_id=<?php echo $row['user_id']; ?>&play_id=<?php echo $row['play_id']; ?>&source=dashboard">View Details</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <h4>Topic Ratings</h4>
    <div class="ratings-container">
        <!-- Nutrition Ratings Section -->
        <div class="rating-box">
            <h2>Nutrition Ratings</h2>
            <div class="star-rating">
                <?php
                // Calculate the percentages for Nutrition
                $percent_1_nutrition = ($nutrition_ratings['rating_1'] / $nutrition_total) * 100;
                $percent_2_nutrition = ($nutrition_ratings['rating_2'] / $nutrition_total) * 100;
                $percent_3_nutrition = ($nutrition_ratings['rating_3'] / $nutrition_total) * 100;
                $percent_4_nutrition = ($nutrition_ratings['rating_4'] / $nutrition_total) * 100;
                $percent_5_nutrition = ($nutrition_ratings['rating_5'] / $nutrition_total) * 100;
                ?>
                
                <!-- Rating Bars for 1 to 5 stars -->
                <div class="rating-bar" style="width: <?php echo $percent_5_nutrition; ?>%">
                    <span class="star-label">5 star</span>
                    <span class="percentage"><?php echo round($percent_5_nutrition); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_4_nutrition; ?>%">
                    <span class="star-label">4 star</span>
                    <span class="percentage"><?php echo round($percent_4_nutrition); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_3_nutrition; ?>%">
                    <span class="star-label">3 star</span>
                    <span class="percentage"><?php echo round($percent_3_nutrition); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_2_nutrition; ?>%">
                    <span class="star-label">2 star</span>
                    <span class="percentage"><?php echo round($percent_2_nutrition); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_1_nutrition; ?>%">
                    <span class="star-label">1 star</span>
                    <span class="percentage"><?php echo round($percent_1_nutrition); ?>%</span>
                </div>
            </div>
        </div>

        <!-- Fitness Ratings Section -->
        <div class="rating-box">
            <h2>Fitness Ratings</h2>
            <div class="star-rating">
                <?php
                // Calculate the percentages for Fitness
                $percent_1_fitness = ($fitness_ratings['rating_1'] / $fitness_total) * 100;
                $percent_2_fitness = ($fitness_ratings['rating_2'] / $fitness_total) * 100;
                $percent_3_fitness = ($fitness_ratings['rating_3'] / $fitness_total) * 100;
                $percent_4_fitness = ($fitness_ratings['rating_4'] / $fitness_total) * 100;
                $percent_5_fitness = ($fitness_ratings['rating_5'] / $fitness_total) * 100;
                ?>
                
                <!-- Rating Bars for 1 to 5 stars -->
                <div class="rating-bar" style="width: <?php echo $percent_5_fitness; ?>%">
                    <span class="star-label">5 star</span>
                    <span class="percentage"><?php echo round($percent_5_fitness); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_4_fitness; ?>%">
                    <span class="star-label">4 star</span>
                    <span class="percentage"><?php echo round($percent_4_fitness); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_3_fitness; ?>%">
                    <span class="star-label">3 star</span>
                    <span class="percentage"><?php echo round($percent_3_fitness); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_2_fitness; ?>%">
                    <span class="star-label">2 star</span>
                    <span class="percentage"><?php echo round($percent_2_fitness); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_1_fitness; ?>%">
                    <span class="star-label">1 star</span>
                    <span class="percentage"><?php echo round($percent_1_fitness); ?>%</span>
                </div>
            </div>
        </div>

        <!-- Mental Health Ratings Section -->
        <div class="rating-box">
            <h2>Mental Health Ratings</h2>
            <div class="star-rating">
                <?php
                // Calculate the percentages for Mental Health
                $percent_1_mental_health = ($mental_health_ratings['rating_1'] / $mental_health_total) * 100;
                $percent_2_mental_health = ($mental_health_ratings['rating_2'] / $mental_health_total) * 100;
                $percent_3_mental_health = ($mental_health_ratings['rating_3'] / $mental_health_total) * 100;
                $percent_4_mental_health = ($mental_health_ratings['rating_4'] / $mental_health_total) * 100;
                $percent_5_mental_health = ($mental_health_ratings['rating_5'] / $mental_health_total) * 100;
                ?>
                
                <!-- Rating Bars for 1 to 5 stars -->
                <div class="rating-bar" style="width: <?php echo $percent_5_mental_health; ?>%">
                    <span class="star-label">5 star</span>
                    <span class="percentage"><?php echo round($percent_5_mental_health); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_4_mental_health; ?>%">
                    <span class="star-label">4 star</span>
                    <span class="percentage"><?php echo round($percent_4_mental_health); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_3_mental_health; ?>%">
                    <span class="star-label">3 star</span>
                    <span class="percentage"><?php echo round($percent_3_mental_health); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_2_mental_health; ?>%">
                    <span class="star-label">2 star</span>
                    <span class="percentage"><?php echo round($percent_2_mental_health); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_1_mental_health; ?>%">
                    <span class="star-label">1 star</span>
                    <span class="percentage"><?php echo round($percent_1_mental_health); ?>%</span>
                </div>
            </div>
        </div>

        <!-- Self-Care Ratings Section -->
        <div class="rating-box">
            <h2>Self-Care Ratings</h2>
            <div class="star-rating">
                <?php
                // Calculate the percentages for Self-Care
                $percent_1_self_care = ($self_care_ratings['rating_1'] / $self_care_total) * 100;
                $percent_2_self_care = ($self_care_ratings['rating_2'] / $self_care_total) * 100;
                $percent_3_self_care = ($self_care_ratings['rating_3'] / $self_care_total) * 100;
                $percent_4_self_care = ($self_care_ratings['rating_4'] / $self_care_total) * 100;
                $percent_5_self_care = ($self_care_ratings['rating_5'] / $self_care_total) * 100;
                ?>
                
                <!-- Rating Bars for 1 to 5 stars -->
                <div class="rating-bar" style="width: <?php echo $percent_5_self_care; ?>%">
                    <span class="star-label">5 star</span>
                    <span class="percentage"><?php echo round($percent_5_self_care); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_4_self_care; ?>%">
                    <span class="star-label">4 star</span>
                    <span class="percentage"><?php echo round($percent_4_self_care); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_3_self_care; ?>%">
                    <span class="star-label">3 star</span>
                    <span class="percentage"><?php echo round($percent_3_self_care); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_2_self_care; ?>%">
                    <span class="star-label">2 star</span>
                    <span class="percentage"><?php echo round($percent_2_self_care); ?>%</span>
                </div>
                <div class="rating-bar" style="width: <?php echo $percent_1_self_care; ?>%">
                    <span class="star-label">1 star</span>
                    <span class="percentage"><?php echo round($percent_1_self_care); ?>%</span>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php include 'adminfooter.php';?>
    </footer>
</body>
</html>