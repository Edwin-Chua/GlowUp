<?php
session_start();
include 'connection.php';

header('Content-Type: application/json');

if (!isset($_GET['action']) || !isset($_GET['current_quiz'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit;
}

$action = $_GET['action'];
$current_quiz = $_GET['current_quiz'];

// Get current question number from session
$current_question = isset($_SESSION['current_question']) ? $_SESSION['current_question'] : 1;
$total_questions = isset($_SESSION['totalQuestions']) ? $_SESSION['totalQuestions'] : 10;

if ($action === 'next') {
    // Check if this is the last question
    if ($current_question >= $total_questions) {
        // Last question, indicate completion
        echo json_encode([
            'status' => 'completed',
            'redirect' => 'quizResult.php'
        ]);
    } else {
        // Not the last question, proceed to next
        $_SESSION['current_question'] = $current_question + 1;
        echo json_encode([
            'status' => 'success',
            'newIndex' => $current_question + 1,
            'isLastQuestion' => ($current_question + 1 >= $total_questions)
        ]);
    }
} elseif ($action === 'prev') {
    // Check if there's a previous question
    if ($current_question > 1) {
        $_SESSION['current_question'] = $current_question - 1;
        echo json_encode([
            'status' => 'success',
            'newIndex' => $current_question - 1,
            'isLastQuestion' => false
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Already at first question']);
    }
}

mysqli_close($connection);
?>