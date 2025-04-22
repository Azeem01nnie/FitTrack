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
$time_in = $data['time_in'] ?? null;

if (!$time_in) {
    echo json_encode(['success' => false, 'message' => 'Missing time_in']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO attendance_logs (user_id, time_in) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $time_in);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'time_in' => $time_in]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving time-in']);
}

$stmt->close();
$conn->close();
?>
