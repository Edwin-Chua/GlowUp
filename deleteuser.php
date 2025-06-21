<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "glowup");
if (!$conn) {
    die(json_encode(['success' => false, 'message' => 'Connection failed']));
}

// Get user ID from request
$userId = $_GET['id'];

// Delete user
$sql = "DELETE FROM userglowup WHERE studentid = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);

$response = ['success' => false];

if (mysqli_stmt_execute($stmt)) {
    $response['success'] = true;
} else {
    $response['message'] = mysqli_error($conn);
}

mysqli_close($conn);
header('Content-Type: application/json');
echo json_encode($response);
?> 