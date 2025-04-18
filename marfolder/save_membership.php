<?php
session_start();

// Connect to DB
$conn = new mysqli("localhost", "root", "", "fittrack_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected plan
$plan = $_POST['plan'];

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Save membership plan
$stmt = $conn->prepare("UPDATE users SET membership_plan = ? WHERE user_id = ?");
$stmt->bind_param("si", $plan, $user_id);

if ($stmt->execute()) {
    header("Location: conditions.html"); // Replace with your actual page
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
