<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "127.0.0.1";
$username = "root"; // Update if necessary
$password = ""; // Update if necessary
$database = "glowup"; // Update with your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

header("Content-Type: application/json");

// Handle GET request: Load existing questions
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        $sql = "SELECT * FROM quiz_db ORDER BY quiz_id";
        $result = $conn->query($sql);
        
        if ($result) {
            $quizData = [];
            while ($row = $result->fetch_assoc()) {
                $quizData[] = [
                    'quiz_id' => $row['quiz_id'],
                    'question' => $row['question'],
                    'answers' => [
                        $row['A'],
                        $row['B'],
                        $row['C'],
                        $row['D']
                    ],
                    'correctAnswer' => $row['correct_answer'],
                    'points' => $row['points']
                ];
            }
            echo json_encode([
                "success" => true,
                "quizData" => $quizData
            ]);
        } else {
            throw new Exception("Error loading questions: " . $conn->error);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}

// Handle POST request: Save modified or new questions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (!isset($data['quizData']) || !is_array($data['quizData'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid input data"]);
        exit;
    }

    $processedQuestions = [];
    $conn->begin_transaction(); // Start transaction

    try {
        foreach ($data['quizData'] as $quiz) {
            $questionId = $quiz['quiz_id'] ?? null;
            $questionText = $quiz['question'] ?? "";
            $answerA = $quiz['A'] ?? "";
            $answerB = $quiz['B'] ?? "";
            $answerC = $quiz['C'] ?? "";
            $answerD = $quiz['D'] ?? "";
            $correctAnswer = $quiz['correctAnswer'] ?? "";
            $points = 5;

            // Skip incomplete entries
            if (empty($questionText) || empty($answerA) || empty($answerB) || 
                empty($answerC) || empty($answerD) || empty($correctAnswer)) {
                continue;
            }

            if ($questionId) {
                // Update existing question
                $sql = "UPDATE quiz_db SET 
                        question = ?, 
                        A = ?, 
                        B = ?, 
                        C = ?, 
                        D = ?, 
                        correct_answer = ?, 
                        points = ? 
                        WHERE quiz_id = ?";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssis", 
                    $questionText, $answerA, $answerB, $answerC, 
                    $answerD, $correctAnswer, $points, $questionId
                );
            } else {
                // Insert new question
                $sql = "INSERT INTO quiz_db (question, A, B, C, D, correct_answer, points) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssi", 
                    $questionText, $answerA, $answerB, $answerC, 
                    $answerD, $correctAnswer, $points
                );
            }
            
            if ($stmt->execute()) {
                $id = $questionId ?? $conn->insert_id;
                $processedQuestions[] = [
                    "question_id" => $id,
                    "status" => $questionId ? "updated" : "inserted"
                ];
            } else {
                throw new Exception("Error processing question: " . $stmt->error);
            }
            
            $stmt->close();
        }

        $conn->commit(); // Commit transaction if all queries succeed
        
        echo json_encode([
            "success" => true, 
            "message" => "Quiz saved successfully.", 
            "questions" => $processedQuestions
        ]);

    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction if any query fails
        http_response_code(500);
        echo json_encode([
            "success" => false, 
            "message" => $e->getMessage()
        ]);
    }
}

$conn->close();
?>
