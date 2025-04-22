<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$conn = new mysqli("localhost", "root", "", "fittrack_db");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'];
$time_out = $data['time_out'] ?? null;

if (!$time_out) {
    echo json_encode(['success' => false, 'message' => 'Missing time_out']);
    exit();
}

$stmt = $conn->prepare("UPDATE attendance_logs SET time_out = ? WHERE user_id = ? AND time_out IS NULL");
$stmt->bind_param("si", $time_out, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'time_out' => $time_out]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving time-out']);
}

$stmt->close();
$conn->close();
?>
