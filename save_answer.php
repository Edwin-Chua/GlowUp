<?php
include 'connection.php';
session_start();

header("Content-Type: application/json");

// get session data
$data = json_decode(file_get_contents("php://input"),true);

$play_id =$_SESSION['play_id'];
$user_id =$_SESSION['user_id'];
$quiz_id =$data['quizId'];
$selected_answer = $data['selectedOption'] ??'';
$correct_answer = $data['correctAnswer'];
$currentQuestionIndex = $_SESSION['currentQuestionIndex']??0;
$totalQuestions = $_SESSION['totalQuestions'];

//determine if the answer is correct
$is_correct =($selected_answer === $correct_answer)? 1:0;

//fetch the points from 'quiz_db
$sql_points = "SELECT points FROM quiz_db WHERE quiz_id = ?";
$stmt_points = $connection->prepare($sql_points);
$stmt_points->bind_param("i", $quiz_id);
$stmt_points->execute();
$result_points = $stmt_points->get_result();
$quiz_points = 0;

if($row = $result_points->fetch_assoc()){
    $quiz_points = $row['points'];
}
$stmt_points->close();

//assign points, if correct use quiz points, otherwise set to 0
$earned_points = $is_correct ? $quiz_points:0;

// cehck if the answer already exists
$sql_check = "SELECT COUNT(*) AS count FROM user_answers WHERE play_id =? AND quiz_id =?";
$stmt_check = $connection->prepare($sql_check);
$stmt_check->bind_param("si",$play_id,$quiz_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row = $result_check->fetch_assoc();
$stmt_check->close();

if ($row['count'] >0 ){
    echo json_encode(["status" => "success", "message" => "Answer already submitted"]);
    exit();
}

//insert user answer into database
$sql = "INSERT INTO user_answers (play_id, user_id, quiz_id, selected_answer, correct_answer, is_correct, points)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $connection->prepare($sql);
$stmt->bind_param("ssissii", $play_id, $user_id, $quiz_id, $selected_answer, $correct_answer, $is_correct, $earned_points);

if (!$stmt->execute()){
    die(json_encode(["status" => "error", "message" =>"Database error: ".$stmt->error]));
}

$stmt->close();

//if is the last question, update all answer with the timestamp
if ($currentQuestionIndex + 1 == $totalQuestions){
    $update_sql ="UPDATE user_answers SET completed_at = NOW() WHERE play_id =?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt ->bind_param("s",$play_id);
    $update_stmt->execute();
    $update_stmt->close();

    //reset quiz session after completing all questions
    $_SESSION['currentQuestionIndex'] = 0;
    unset($_SESSION['play_id']);

    //redirect user to results page after last question
    echo json_encode(["status" => "completed"]);
    exit();
}

echo json_encode(["status" => "success", "newIndex" => $_SESSION['currentQuestionIndex']]);

$connection->close();
exit();
?>